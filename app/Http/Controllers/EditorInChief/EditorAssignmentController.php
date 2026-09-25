<?php

namespace App\Http\Controllers\EditorInChief;

use App\Http\Controllers\Controller;
use App\Models\EditorAssignment;
use App\Models\Manuscript;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EditorAssignmentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Editor Assignment Queue
    |--------------------------------------------------------------------------
    |
    | Shows manuscripts that have completed:
    |
    | Technical Check
    |      ↓
    | Payment Verification
    |      ↓
    | Similarity Check
    |      ↓
    | Editor Assignment Queue
    |
    */

    public function index()
    {
        $manuscripts = Manuscript::query()
            ->with([
                'journal',
                'articleType',
                'handlingEditor',
                'latestTechnicalCheck',
                'latestPayment',
                'latestSimilarityCheck',
            ])
            ->where(
                'status',
                'editor_assignment'
            )
            ->latest('submitted_at')
            ->paginate(20);

        return view(
            'editor-in-chief.editor-assignment.index',
            compact('manuscripts')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Manuscript for Editor Assignment
    |--------------------------------------------------------------------------
    |
    | Editor-in-Chief / System Administrator can review:
    |
    | - Manuscript information
    | - Technical check
    | - Payment verification
    | - Similarity check
    | - Previous editor assignments
    | - Available Handling Editors
    |
    */

    public function show(Manuscript $manuscript)
    {
        /*
        |--------------------------------------------------------------------------
        | Manuscript Status Validation
        |--------------------------------------------------------------------------
        */

        if ($manuscript->status !== 'editor_assignment') {

            return redirect()
                ->route(
                    'eic.editor-assignment.index'
                )
                ->withErrors([
                    'manuscript' =>
                        'This manuscript is not currently awaiting Handling Editor assignment.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Load Manuscript Information
        |--------------------------------------------------------------------------
        */

        $manuscript->load([

            /*
            |--------------------------------------------------------------------------
            | Basic Manuscript Information
            |--------------------------------------------------------------------------
            */

            'submitter',

            'journal',

            'articleType',

            'authors',

            'files',


            /*
            |--------------------------------------------------------------------------
            | Previous Workflow Information
            |--------------------------------------------------------------------------
            */

            'latestTechnicalCheck',

            'latestPayment',

            'latestSimilarityCheck',


            /*
            |--------------------------------------------------------------------------
            | Current Handling Editor
            |--------------------------------------------------------------------------
            */

            'handlingEditor',


            /*
            |--------------------------------------------------------------------------
            | Assignment History
            |--------------------------------------------------------------------------
            */

            'editorAssignments' => function ($query) {

                $query->orderByDesc(
                    'assignment_round'
                );
            },

            'editorAssignments.editor',

            'editorAssignments.assignedBy',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Available Handling Editors
        |--------------------------------------------------------------------------
        |
        | Only users having handling_editor role are shown.
        |
        */

        $editors = User::role(
                'handling_editor'
            )
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Calculate Handling Editor Workload
        |--------------------------------------------------------------------------
        |
        | We calculate the number of active assignments for every Handling
        | Editor.
        |
        | Active:
        | pending
        | accepted
        |
        */

        $editorWorkloads = EditorAssignment::query()
            ->select(
                'editor_id',
                DB::raw('COUNT(*) as active_assignments')
            )
            ->whereIn(
                'status',
                [
                    'pending',
                    'accepted',
                ]
            )
            ->groupBy('editor_id')
            ->pluck(
                'active_assignments',
                'editor_id'
            );


        /*
        |--------------------------------------------------------------------------
        | Attach Workload to Editor Objects
        |--------------------------------------------------------------------------
        */

        foreach ($editors as $editor) {

            $editor->active_assignment_count =
                (int) (
                    $editorWorkloads[
                        $editor->id
                    ] ?? 0
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Return Assignment Page
        |--------------------------------------------------------------------------
        */

        return view(
            'editor-in-chief.editor-assignment.show',
            compact(
                'manuscript',
                'editors'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Assign Handling Editor
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Manuscript $manuscript
    ) {

        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'editor_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'assignment_note' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'due_date' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Find Selected Editor
        |--------------------------------------------------------------------------
        */

        $editor = User::findOrFail(
            $validated['editor_id']
        );


        /*
        |--------------------------------------------------------------------------
        | Verify Handling Editor Role
        |--------------------------------------------------------------------------
        */

        if (
            !$editor->hasRole(
                'handling_editor'
            )
        ) {

            return back()
                ->withInput()
                ->withErrors([

                    'editor_id' =>
                        'Selected user is not a Handling Editor.'

                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Database Transaction
        |--------------------------------------------------------------------------
        |
        | Everything below will succeed together or fail together.
        |
        */

        DB::transaction(
            function () use (
                $validated,
                $manuscript
            ) {

                /*
                |--------------------------------------------------------------------------
                | Lock Manuscript
                |--------------------------------------------------------------------------
                |
                | Prevent two EIC/Admin users assigning two editors to the
                | same manuscript simultaneously.
                |
                */

                $lockedManuscript =
                    Manuscript::query()
                        ->whereKey(
                            $manuscript->id
                        )
                        ->lockForUpdate()
                        ->firstOrFail();


                /*
                |--------------------------------------------------------------------------
                | Recheck Workflow Status
                |--------------------------------------------------------------------------
                */

                if (
                    $lockedManuscript->status
                    !==
                    'editor_assignment'
                ) {

                    abort(
                        409,
                        'This manuscript is no longer awaiting Handling Editor assignment.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Check Existing Active Assignment
                |--------------------------------------------------------------------------
                |
                | There must not already be:
                |
                | pending assignment
                | OR
                | accepted assignment
                |
                */

                $activeAssignment =
                    EditorAssignment::query()
                        ->where(
                            'manuscript_id',
                            $lockedManuscript->id
                        )
                        ->whereIn(
                            'status',
                            [
                                'pending',
                                'accepted',
                            ]
                        )
                        ->lockForUpdate()
                        ->first();


                /*
                |--------------------------------------------------------------------------
                | Stop Duplicate Assignment
                |--------------------------------------------------------------------------
                */

                if ($activeAssignment) {

                    abort(
                        409,
                        'This manuscript already has an active Handling Editor assignment.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Determine Assignment Round
                |--------------------------------------------------------------------------
                |
                | First assignment  = 1
                | Reassignment      = 2
                | Next reassignment = 3
                |
                */

                $lastRound =
                    EditorAssignment::query()
                        ->where(
                            'manuscript_id',
                            $lockedManuscript->id
                        )
                        ->max(
                            'assignment_round'
                        );


                $assignmentRound =
                    ((int) $lastRound) + 1;


                /*
                |--------------------------------------------------------------------------
                | Create Handling Editor Assignment
                |--------------------------------------------------------------------------
                */

                EditorAssignment::create([

                    'manuscript_id' =>
                        $lockedManuscript->id,

                    'editor_id' =>
                        $validated['editor_id'],

                    'assigned_by' =>
                        auth()->id(),

                    'assignment_round' =>
                        $assignmentRound,

                    'status' =>
                        'pending',

                    'assignment_note' =>
                        $validated[
                            'assignment_note'
                        ] ?? null,

                    'due_date' =>
                        $validated[
                            'due_date'
                        ] ?? null,

                    'assigned_at' =>
                        now(),

                    'accepted_at' =>
                        null,

                    'declined_at' =>
                        null,

                    'completed_at' =>
                        null,

                    'cancelled_at' =>
                        null,

                    'decline_reason' =>
                        null,
                ]);


                /*
                |--------------------------------------------------------------------------
                | Update Manuscript Workflow
                |--------------------------------------------------------------------------
                |
                | IMPORTANT:
                |
                | The editor has been assigned, but the editor has NOT yet
                | accepted the assignment.
                |
                | Therefore we DO NOT change the manuscript directly to:
                |
                | editorial_assessment
                |
                | It first waits for Handling Editor acceptance.
                |
                */

                $lockedManuscript->update([

                    'handling_editor_id' =>
                        $validated['editor_id'],

                    'status' =>
                        'handling_editor_assigned',

                    'current_stage' =>
                        'handling_editor_assignment',
                ]);
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Success Response
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'eic.editor-assignment.index'
            )
            ->with(
                'success',
                'Handling Editor assigned successfully. The assignment is now awaiting the Handling Editor\'s response.'
            );
    }



        public function tracking(Request $request)
        {
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
            | Search
            |--------------------------------------------------------------------------
            */

            if ($request->filled('search')) {

                $search = $request->search;

                $query->whereHas('manuscript', function ($q) use ($search) {

                    $q->where('manuscript_id', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%");

                });

            }


            /*
            |--------------------------------------------------------------------------
            | Handling Editor Filter
            |--------------------------------------------------------------------------
            */

            if ($request->filled('editor_id')) {

                $query->where(
                    'editor_id',
                    $request->editor_id
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


            /*
            |--------------------------------------------------------------------------
            | Get Assignments
            |--------------------------------------------------------------------------
            */

            $assignments = $query
                ->paginate(20)
                ->withQueryString();


            /*
            |--------------------------------------------------------------------------
            | Handling Editors
            |--------------------------------------------------------------------------
            */

            $editors = User::role('handling_editor')
                ->orderBy('name')
                ->get();


            return view(
                'editor-in-chief.editor-assignment.tracking',
                compact(
                    'assignments',
                    'editors'
                )
            );
        }

        public function trackingShow(EditorAssignment $assignment)
            {
                $assignment->load([
                    'manuscript.journal',
                    'manuscript.articleType',
                    'manuscript.authors',
                    'manuscript.files',

                    'editor',
                    'assignedBy',

                    'manuscript.latestTechnicalCheck',
                    'manuscript.latestPayment',
                    'manuscript.latestSimilarityCheck',

                    'manuscript.editorAssignments.editor',
                    'manuscript.editorAssignments.assignedBy',

                    'manuscript.latestEditorialAssessment',
                    'manuscript.latestRecommendation',
                    'manuscript.latestEditorialDecision',
                ]);

                return view(
                    'editor-in-chief.editor-assignment.tracking-show',
                    compact('assignment')
                );
            }





}