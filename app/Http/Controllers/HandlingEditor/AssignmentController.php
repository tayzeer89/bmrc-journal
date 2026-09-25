<?php

namespace App\Http\Controllers\HandlingEditor;

use App\Http\Controllers\Controller;
use App\Models\EditorAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Assignment List
    |--------------------------------------------------------------------------
    |
    | Handling Editor:
    |   sees only assignments assigned to himself/herself.
    |
    | System Administrator:
    |   sees all assignments.
    |
    */

    public function index(Request $request)
    {
        $user = auth()->user();

        $query = EditorAssignment::query()
            ->with([
                'manuscript.journal',
                'manuscript.articleType',
                'editor',
                'assignedBy',
            ])
            ->latest('assigned_at');


        /*
        |--------------------------------------------------------------------------
        | Role restriction
        |--------------------------------------------------------------------------
        */

        if (!$user->hasRole('system_administrator')) {

            $query->where(
                'editor_id',
                $user->id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );

            $query->whereHas(
                'manuscript',
                function ($q) use ($search) {

                    $q->where(
                        'manuscript_id',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'title',
                        'like',
                        "%{$search}%"
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Assignment Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        $assignments = $query
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Counts
        |--------------------------------------------------------------------------
        */

        $countQuery = EditorAssignment::query();

        if (!$user->hasRole('system_administrator')) {

            $countQuery->where(
                'editor_id',
                $user->id
            );
        }


        $pendingCount = (clone $countQuery)
            ->where('status', 'pending')
            ->count();

        $acceptedCount = (clone $countQuery)
            ->where('status', 'accepted')
            ->count();

        $declinedCount = (clone $countQuery)
            ->where('status', 'declined')
            ->count();


        return view(
            'handling-editor.assignments.index',
            compact(
                'assignments',
                'pendingCount',
                'acceptedCount',
                'declinedCount'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Assignment
    |--------------------------------------------------------------------------
    */
        public function show(EditorAssignment $assignment)
        {
            $user = auth()->user();

            /*
            |--------------------------------------------------------------------------
            | Access Control
            |--------------------------------------------------------------------------
            | Handling Editor can see only his/her own assignment.
            | System Administrator can see all assignments.
            */

            if (
                !$user->hasRole('system_administrator')
                && (int) $assignment->editor_id !== (int) $user->id
            ) {
                abort(403);
            }

            /*
            |--------------------------------------------------------------------------
            | Load Complete Manuscript Information
            |--------------------------------------------------------------------------
            */

            $assignment->load([
                'editor',
                'assignedBy',

                'manuscript.journal',
                'manuscript.articleType',
                'manuscript.authors',
                'manuscript.files',

                'manuscript.latestTechnicalCheck',
                'manuscript.latestPayment',
                'manuscript.latestSimilarityCheck',

                'manuscript.editorAssignments.editor',
                'manuscript.editorAssignments.assignedBy',
            ]);

            return view(
                'handling-editor.assignments.show',
                compact('assignment')
            );
        }

    /*
    |--------------------------------------------------------------------------
    | Accept Assignment
    |--------------------------------------------------------------------------
    */

    public function accept(EditorAssignment $assignment)
    {
        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Ownership check
        |--------------------------------------------------------------------------
        */

        if (
            !$user->hasRole('system_administrator')
            && (int) $assignment->editor_id !== (int) $user->id
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Only pending assignment can be accepted
        |--------------------------------------------------------------------------
        */

        if ($assignment->status !== 'pending') {

            return back()->with(
                'error',
                'This assignment has already been processed.'
            );
        }


        DB::transaction(function () use ($assignment) {

            /*
            |--------------------------------------------------------------------------
            | Lock assignment
            |--------------------------------------------------------------------------
            */

            $lockedAssignment = EditorAssignment::query()
                ->lockForUpdate()
                ->findOrFail($assignment->id);


            if ($lockedAssignment->status !== 'pending') {

                throw new \RuntimeException(
                    'This assignment has already been processed.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Lock manuscript
            |--------------------------------------------------------------------------
            */

            $manuscript = $lockedAssignment
                ->manuscript()
                ->lockForUpdate()
                ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | Accept assignment
            |--------------------------------------------------------------------------
            */

            $lockedAssignment->update([
                'status'      => 'accepted',
                'accepted_at' => now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Move manuscript to Editorial Assessment
            |--------------------------------------------------------------------------
            */

            $manuscript->update([
                'handling_editor_id' => $lockedAssignment->editor_id,

                'status' => 'editorial_assessment',

                'current_stage' => 'editorial_assessment',
            ]);
        });


        return redirect()
            ->route(
                'handling-editor.assignments.show',
                $assignment->id
            )
            ->with(
                'success',
                'Assignment accepted successfully. The manuscript is now ready for Editorial Assessment.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Decline Assignment
    |--------------------------------------------------------------------------
    */

    public function decline(
        Request $request,
        EditorAssignment $assignment
    ) {
        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Ownership check
        |--------------------------------------------------------------------------
        */

        if (
            !$user->hasRole('system_administrator')
            && (int) $assignment->editor_id !== (int) $user->id
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'decline_reason' => [
                'required',
                'string',
                'min:10',
                'max:2000',
            ],
        ]);


        if ($assignment->status !== 'pending') {

            return back()->with(
                'error',
                'This assignment has already been processed.'
            );
        }


        DB::transaction(
            function () use (
                $assignment,
                $validated
            ) {

                /*
                |--------------------------------------------------------------------------
                | Lock assignment
                |--------------------------------------------------------------------------
                */

                $lockedAssignment = EditorAssignment::query()
                    ->lockForUpdate()
                    ->findOrFail(
                        $assignment->id
                    );


                if ($lockedAssignment->status !== 'pending') {

                    throw new \RuntimeException(
                        'This assignment has already been processed.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Lock manuscript
                |--------------------------------------------------------------------------
                */

                $manuscript = $lockedAssignment
                    ->manuscript()
                    ->lockForUpdate()
                    ->firstOrFail();


                /*
                |--------------------------------------------------------------------------
                | Decline
                |--------------------------------------------------------------------------
                */

                $lockedAssignment->update([
                    'status' => 'declined',

                    'decline_reason' =>
                        $validated['decline_reason'],

                    'declined_at' => now(),
                ]);


                /*
                |--------------------------------------------------------------------------
                | Return manuscript to EIC assignment queue
                |--------------------------------------------------------------------------
                */

                $manuscript->update([
                    'handling_editor_id' => null,

                    'status' =>
                        'editor_assignment',

                    'current_stage' =>
                        'editor_assignment',
                ]);
            }
        );


        return redirect()
            ->route(
                'handling-editor.assignments.index'
            )
            ->with(
                'success',
                'Assignment declined. The manuscript has been returned to the Editor-in-Chief assignment queue.'
            );
    }
}