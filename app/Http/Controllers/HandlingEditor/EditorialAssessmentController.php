<?php

namespace App\Http\Controllers\HandlingEditor;

use App\Http\Controllers\Controller;
use App\Models\EditorialAssessment;
use App\Models\Manuscript;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EditorialAssessmentController extends Controller
{
    /**
     * Manuscripts ready for Editorial Assessment.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Manuscript::query()
            ->with([
                'journal',
                'articleType',
                'handlingEditor',
                'currentEditorAssignment',
                'latestEditorialAssessment',
            ])
            ->where('status', 'editorial_assessment');

        /*
        |--------------------------------------------------------------------------
        | Access Control
        |--------------------------------------------------------------------------
        */

        if (!$user->hasRole('system_administrator')) {
            $query->where(
                'handling_editor_id',
                $user->id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

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
            });
        }

        $manuscripts = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'handling-editor.assessment.index',
            compact('manuscripts')
        );
    }


    /**
     * Open Editorial Assessment form.
     */
    public function show(Manuscript $manuscript)
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Access Control
        |--------------------------------------------------------------------------
        */

        if (
            !$user->hasRole('system_administrator')
            && (int) $manuscript->handling_editor_id
                !== (int) $user->id
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Workflow Check
        |--------------------------------------------------------------------------
        */

        if ($manuscript->status !== 'editorial_assessment') {

            return redirect()
                ->route('handling-editor.assessment.index')
                ->with(
                    'error',
                    'This manuscript is not currently in Editorial Assessment.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Load Manuscript
        |--------------------------------------------------------------------------
        */

        $manuscript->load([
            'journal',
            'articleType',
            'authors',
            'files',
            'handlingEditor',
            'latestTechnicalCheck',
            'latestPayment',
            'latestSimilarityCheck',
            'currentEditorAssignment',
            'latestEditorialAssessment',
        ]);

        return view(
            'handling-editor.assessment.show',
            compact('manuscript')
        );
    }





    public function store(
    Request $request,
    Manuscript $manuscript
    )
        {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Access Control
        |--------------------------------------------------------------------------
        */

        if (
            !$user->hasRole('system_administrator')
            && (int) $manuscript->handling_editor_id !== (int) $user->id
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Workflow Validation
        |--------------------------------------------------------------------------
        */

        if ($manuscript->status !== 'editorial_assessment') {

            return redirect()
                ->route('handling-editor.assessment.index')
                ->with(
                    'error',
                    'This manuscript is not currently in Editorial Assessment.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Assessment
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'scope_status' => [
                'required',
                'in:within_scope,partially_within_scope,out_of_scope',
            ],

            'scientific_quality' => [
                'required',
                'in:excellent,good,fair,poor',
            ],

            'methodology_status' => [
                'required',
                'in:appropriate,needs_clarification,major_concern,unacceptable',
            ],

            'novelty_status' => [
                'required',
                'in:high,moderate,low,none',
            ],

            'reporting_quality' => [
                'required',
                'in:excellent,good,fair,poor',
            ],

            'ethical_concern' => [
                'required',
                'boolean',
            ],

            'ethical_comment' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'conflict_of_interest' => [
                'required',
                'boolean',
            ],

            'conflict_comment' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'comments' => [
                'nullable',
                'string',
                'max:10000',
            ],

            'outcome' => [
                'required',
                'in:send_for_review,recommend_rejection,return_for_clarification',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Conditional Validation
        |--------------------------------------------------------------------------
        */

        if (
            (bool) $validated['ethical_concern']
            && blank($validated['ethical_comment'] ?? null)
        ) {
            return back()
                ->withErrors([
                    'ethical_comment' =>
                        'Please describe the ethical concern.',
                ])
                ->withInput();
        }


        if (
            (bool) $validated['conflict_of_interest']
            && blank($validated['conflict_comment'] ?? null)
        ) {
            return back()
                ->withErrors([
                    'conflict_comment' =>
                        'Please describe the conflict of interest.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Save Assessment
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $manuscript,
            $user,
            $validated
        ) {

            /*
            |--------------------------------------------------------------------------
            | Lock Manuscript
            |--------------------------------------------------------------------------
            */

            $lockedManuscript = Manuscript::query()
                ->lockForUpdate()
                ->findOrFail($manuscript->id);


            /*
            |--------------------------------------------------------------------------
            | Re-check Status
            |--------------------------------------------------------------------------
            */

            if (
                $lockedManuscript->status
                !== 'editorial_assessment'
            ) {
                abort(
                    409,
                    'The manuscript workflow has already changed.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Determine Assessment Round
            |--------------------------------------------------------------------------
            */

            $lastRound = EditorialAssessment::query()
                ->where(
                    'manuscript_id',
                    $lockedManuscript->id
                )
                ->max('assessment_round');

            $assessmentRound =
                ((int) $lastRound) + 1;


            /*
            |--------------------------------------------------------------------------
            | Create Editorial Assessment
            |--------------------------------------------------------------------------
            */

            EditorialAssessment::create([

                'manuscript_id' =>
                    $lockedManuscript->id,

                'editor_id' =>
                    $lockedManuscript->handling_editor_id
                    ?? $user->id,

                'assessment_round' =>
                    $assessmentRound,

                'scope_status' =>
                    $validated['scope_status'],

                'scientific_quality' =>
                    $validated['scientific_quality'],

                'methodology_status' =>
                    $validated['methodology_status'],

                'novelty_status' =>
                    $validated['novelty_status'],

                'reporting_quality' =>
                    $validated['reporting_quality'],

                'ethical_concern' =>
                    (bool) $validated['ethical_concern'],

                'ethical_comment' =>
                    $validated['ethical_comment']
                    ?? null,

                'conflict_of_interest' =>
                    (bool) $validated[
                        'conflict_of_interest'
                    ],

                'conflict_comment' =>
                    $validated['conflict_comment']
                    ?? null,

                'comments' =>
                    $validated['comments']
                    ?? null,

                'outcome' =>
                    $validated['outcome'],

                'assessed_at' =>
                    now(),

            ]);


            /*
            |--------------------------------------------------------------------------
            | Workflow Transition
            |--------------------------------------------------------------------------
            */

            switch ($validated['outcome']) {

                /*
                |--------------------------------------------------------------------------
                | Send to Reviewer Selection
                |--------------------------------------------------------------------------
                */

                case 'send_for_review':

                    $lockedManuscript->update([

                        'status' =>
                            'reviewer_selection',

                        'current_stage' =>
                            'reviewer_selection',

                    ]);

                    break;


                /*
                |--------------------------------------------------------------------------
                | Return for Clarification
                |--------------------------------------------------------------------------
                */

                case 'return_for_clarification':

                    $lockedManuscript->update([

                        'status' =>
                            'revision_required',

                        'current_stage' =>
                            'author_correction',

                    ]);

                    break;


                /*
                |--------------------------------------------------------------------------
                | Recommend Rejection
                |--------------------------------------------------------------------------
                */

                case 'recommend_rejection':

                    /*
                    * Do NOT mark manuscript as finally rejected here.
                    *
                    * Handling Editor recommends rejection.
                    * Editor-in-Chief makes the final decision.
                    */

                    $lockedManuscript->update([

                        'status' =>
                            'editor_recommendation',

                        'current_stage' =>
                            'editor_recommendation',

                    ]);

                    break;
            }

        });


        /*
        |--------------------------------------------------------------------------
        | Redirect According to Outcome
        |--------------------------------------------------------------------------
        */

        if (
            $validated['outcome']
            === 'send_for_review'
        ) {
            return redirect()
                ->route(
                    'handling-editor.reviewer-selection.index'
                )
                ->with(
                    'success',
                    'Editorial assessment completed. The manuscript is now ready for reviewer selection.'
                );
        }


        if (
            $validated['outcome']
            === 'return_for_clarification'
        ) {
            return redirect()
                ->route(
                    'handling-editor.assessment.index'
                )
                ->with(
                    'success',
                    'Editorial assessment completed. The manuscript has been returned for clarification/correction.'
                );
        }


        return redirect()
            ->route(
                'handling-editor.assessment.index'
            )
            ->with(
                'success',
                'Editorial assessment completed and rejection recommendation recorded for further editorial review.'
            );
    }


}