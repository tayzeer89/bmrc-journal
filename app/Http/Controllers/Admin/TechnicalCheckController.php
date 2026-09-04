<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manuscript;
use App\Models\TechnicalCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TechnicalCheckController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Technical Checklist
    |--------------------------------------------------------------------------
    */

    private function checklist(): array
    {
        return [

            [
                'key' => 'article_type',
                'name' => 'Article Type',
            ],

            [
                'key' => 'title',
                'name' => 'Title',
            ],

            [
                'key' => 'author_information',
                'name' => 'Author Information',
            ],

            [
                'key' => 'abstract',
                'name' => 'Abstract',
            ],

            [
                'key' => 'keywords',
                'name' => 'Keywords',
            ],

            [
                'key' => 'manuscript_file',
                'name' => 'Manuscript File',
            ],

            [
                'key' => 'blinding',
                'name' => 'Blinding',
            ],

            [
                'key' => 'manuscript_structure',
                'name' => 'Manuscript Structure',
            ],

            [
                'key' => 'tables',
                'name' => 'Tables',
            ],

            [
                'key' => 'figures',
                'name' => 'Figures',
            ],

            [
                'key' => 'references',
                'name' => 'References',
            ],

            [
                'key' => 'ethics',
                'name' => 'Ethical Approval / Research Ethics',
            ],

            [
                'key' => 'consent',
                'name' => 'Informed Consent',
            ],

            [
                'key' => 'conflict_of_interest',
                'name' => 'Conflict of Interest',
            ],

            [
                'key' => 'funding',
                'name' => 'Funding Information',
            ],

            [
                'key' => 'author_contribution',
                'name' => 'Author Contribution',
            ],

            [
                'key' => 'data_availability',
                'name' => 'Data Availability',
            ],

            [
                'key' => 'trial_registration',
                'name' => 'Trial Registration',
            ],

            [
                'key' => 'submission_completeness',
                'name' => 'Submission Completeness',
            ],

            [
                'key' => 'declarations',
                'name' => 'Declarations',
            ],

            [
                'key' => 'acknowledgement',
                'name' => 'Acknowledgement',
            ],

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Technical Review Queue
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        abort_unless(
            auth()->user()->can('technical_check.view'),
            403
        );


        $technicalChecks = TechnicalCheck::with([
            'manuscript.articleType',
            'manuscript.journal',
            'manuscript.submitter',
            'assignedUser',
            'startedBy',
            'completedBy',
        ])
            ->latest()
            ->paginate(20);


        return view(
            'admin.manuscripts.technical-review.index',
            compact('technicalChecks')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Technical Check
    |--------------------------------------------------------------------------
    */

    public function show(Manuscript $manuscript)
    {
        abort_unless(
            auth()->user()->can('technical_check.view'),
            403
        );


        $manuscript->load([
            'articleType',
            'journal',
            'submitter',
            'files',
        ]);


        $technicalCheck = $manuscript
            ->technicalChecks()
            ->with([
                'items',
                'issues.manuscriptFile',
                'issues.technicalCheckItem',
                'issues.createdBy',
                'assignedUser',
                'startedBy',
                'completedBy',
            ])
            ->latest('check_number')
            ->first();


        return view(
            'admin.manuscripts.technical-check',
            compact(
                'manuscript',
                'technicalCheck'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Start Technical Check
    |--------------------------------------------------------------------------
    */

    public function start(Manuscript $manuscript)
    {
        abort_unless(
            auth()->user()->can('technical_check.perform'),
            403
        );


        $activeCheck = $manuscript
            ->technicalChecks()
            ->whereIn('status', [
                'pending',
                'in_progress',
            ])
            ->latest('check_number')
            ->first();


        if ($activeCheck) {

            return redirect()
                ->route(
                    'admin.manuscripts.technical-check',
                    $manuscript
                )
                ->with(
                    'warning',
                    'A technical check is already in progress.'
                );
        }


        $technicalCheck = DB::transaction(
            function () use ($manuscript) {

                $previousCheck = $manuscript
                    ->technicalChecks()
                    ->latest('check_number')
                    ->first();


                $checkNumber = $previousCheck
                    ? $previousCheck->check_number + 1
                    : 1;


                $technicalCheck = $manuscript
                    ->technicalChecks()
                    ->create([

                        'check_number' =>
                            $checkNumber,

                        'status' =>
                            'in_progress',

                        'overall_result' =>
                            null,

                        'assigned_to' =>
                            auth()->id(),

                        'started_by' =>
                            auth()->id(),

                        'started_at' =>
                            now(),

                    ]);


                foreach (
                    $this->checklist()
                    as $index => $item
                ) {

                    $technicalCheck
                        ->items()
                        ->create([

                            'check_key' =>
                                $item['key'],

                            'check_name' =>
                                $item['name'],

                            'sort_order' =>
                                $index + 1,

                            'result' =>
                                'pending',

                            'comment' =>
                                null,

                        ]);
                }


                $manuscript->update([

                    'status' =>
                        'technical_check',

                    'current_stage' =>
                        'technical_review',

                ]);


                return $technicalCheck;
            }
        );


        return redirect()
            ->route(
                'admin.manuscripts.technical-check',
                $manuscript
            )
            ->with(
                'success',
                'Technical check started successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Technical Checklist
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        TechnicalCheck $technicalCheck
    ) {
        abort_unless(
            auth()->user()->can('technical_check.perform'),
            403
        );


        $validated = $request->validate([

            'items' => [
                'required',
                'array',
            ],

            'items.*.result' => [
                'required',
                'in:pass,fail,pending,na',
            ],

            'items.*.comment' => [
                'nullable',
                'string',
                'max:5000',
            ],

        ]);


        DB::transaction(
            function () use (
                $validated,
                $technicalCheck
            ) {

                foreach (
                    $validated['items']
                    as $itemId => $data
                ) {

                    $item = $technicalCheck
                        ->items()
                        ->find($itemId);


                    if (!$item) {
                        continue;
                    }


                    $item->update([

                        'result' =>
                            $data['result'],

                        'comment' =>
                            $data['comment'] ?? null,

                        'checked_by' =>
                            auth()->id(),

                        'checked_at' =>
                            now(),

                    ]);
                }


                $failed = $technicalCheck
                    ->items()
                    ->where('result', 'fail')
                    ->exists();


                $pending = $technicalCheck
                    ->items()
                    ->where('result', 'pending')
                    ->exists();


                if ($failed) {

                    $overallResult =
                        'correction_required';

                } elseif ($pending) {

                    $overallResult =
                        null;

                } else {

                    $overallResult =
                        'passed';
                }


                $technicalCheck->update([

                    'overall_result' =>
                        $overallResult,

                ]);
            }
        );


        return back()
            ->with(
                'success',
                'Technical checklist saved successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Add Technical Issue
    |--------------------------------------------------------------------------
    */

    public function addIssue(
        Request $request,
        TechnicalCheck $technicalCheck
    ) {
        abort_unless(
            auth()->user()->can('technical_check.perform'),
            403
        );


        $validated = $request->validate([

            'technical_check_item_id' => [
                'nullable',
                'exists:technical_check_items,id',
            ],

            'manuscript_file_id' => [
                'nullable',
                'exists:manuscript_files,id',
            ],

            'category' => [
                'required',
                'in:formatting,file,article_type,title,author_information,abstract,keywords,figures,tables,supplementary_files,references,word_count,ethics,consent,conflict_of_interest,funding,author_contribution,data_availability,trial_registration,blinding,submission_completeness,declarations,other',
            ],

            'severity' => [
                'required',
                'in:minor,major,critical',
            ],

            'description' => [
                'required',
                'string',
                'max:5000',
            ],

            'required_action' => [
                'nullable',
                'string',
                'max:5000',
            ],

        ]);


        $manuscript =
            $technicalCheck->manuscript;


        abort_unless(
            $manuscript,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Validate Checklist Item
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['technical_check_item_id']
            )
        ) {

            $itemExists = $technicalCheck
                ->items()
                ->whereKey(
                    $validated['technical_check_item_id']
                )
                ->exists();


            abort_unless(
                $itemExists,
                404
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Exact File
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['manuscript_file_id']
            )
        ) {

            $fileExists = $manuscript
                ->files()
                ->whereKey(
                    $validated['manuscript_file_id']
                )
                ->exists();


            abort_unless(
                $fileExists,
                404
            );
        }


        /*
        |--------------------------------------------------------------------------
        | File Category Requires File
        |--------------------------------------------------------------------------
        */

        if (
            $validated['category'] === 'file' &&
            empty(
                $validated['manuscript_file_id']
            )
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Please select the exact uploaded file that requires correction.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Issue
        |--------------------------------------------------------------------------
        */

        $technicalCheck
            ->issues()
            ->create([

                'technical_check_item_id' =>
                    $validated[
                        'technical_check_item_id'
                    ] ?? null,

                'manuscript_file_id' =>
                    $validated[
                        'manuscript_file_id'
                    ] ?? null,

                'category' =>
                    $validated['category'],

                'severity' =>
                    $validated['severity'],

                'description' =>
                    $validated['description'],

                'required_action' =>
                    $validated[
                        'required_action'
                    ] ?? null,

                'status' =>
                    'open',

                'created_by' =>
                    auth()->id(),

            ]);


        return back()
            ->with(
                'success',
                'Technical issue added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Complete Technical Check
    |--------------------------------------------------------------------------
    */

    public function complete(
        Request $request,
        TechnicalCheck $technicalCheck
    ) {
        abort_unless(
            auth()->user()->can('technical_check.complete'),
            403
        );


        $manuscript =
            $technicalCheck->manuscript;


        abort_unless(
            $manuscript,
            404
        );


        if (
            $technicalCheck->status === 'passed'
        ) {

            return back()
                ->with(
                    'warning',
                    'This technical check has already been completed.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Failed Checklist Items
        |--------------------------------------------------------------------------
        */

        $failedItems = $technicalCheck
            ->items()
            ->where('result', 'fail')
            ->count();


        if ($failedItems > 0) {

            return back()
                ->with(
                    'error',
                    'Technical check cannot be completed because some items failed. Return the manuscript to the author for correction.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Pending Items
        |--------------------------------------------------------------------------
        */

        $pendingItems = $technicalCheck
            ->items()
            ->where('result', 'pending')
            ->count();


        if ($pendingItems > 0) {

            return back()
                ->with(
                    'error',
                    'Please complete all technical check items first.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Open Issues
        |--------------------------------------------------------------------------
        */

        $openIssues = $technicalCheck
            ->issues()
            ->where('status', 'open')
            ->count();


        if ($openIssues > 0) {

            return back()
                ->with(
                    'error',
                    'There are unresolved technical issues. Please resolve them before completing the technical check.'
                );
        }


        DB::transaction(
            function () use (
                $manuscript,
                $technicalCheck
            ) {

                $technicalCheck->update([

                    'status' =>
                        'passed',

                    'overall_result' =>
                        'passed',

                    'completed_by' =>
                        auth()->id(),

                    'completed_at' =>
                        now(),

                ]);


                $manuscript->update([

                    'status' =>
                        'payment_setup',

                    'current_stage' =>
                        'payment',

                ]);
            }
        );


        return redirect()
            ->route(
                'admin.manuscripts.payment.index'
            )
            ->with(
                'success',
                'Technical check completed successfully. Manuscript moved to Payment Setup.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Return Manuscript to Author
    |--------------------------------------------------------------------------
    */

    public function returnToAuthor(
        Request $request,
        TechnicalCheck $technicalCheck
    ) {
        abort_unless(
            auth()->user()->can('technical_check.return'),
            403
        );


        $validated = $request->validate([

            'comments' => [
                'required',
                'string',
                'max:5000',
            ],

        ]);


        $manuscript =
            $technicalCheck->manuscript;


        abort_unless(
            $manuscript,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Require Failed Checklist Item
        |--------------------------------------------------------------------------
        */

        $failedItems = $technicalCheck
            ->items()
            ->where('result', 'fail')
            ->count();


        if ($failedItems === 0) {

            return back()
                ->with(
                    'error',
                    'Please mark at least one checklist item as failed.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Require At Least One Technical Issue
        |--------------------------------------------------------------------------
        */

        $openIssues = $technicalCheck
            ->issues()
            ->where('status', 'open')
            ->count();


        if ($openIssues === 0) {

            return back()
                ->with(
                    'error',
                    'Please add at least one technical issue before returning the manuscript to the author.'
                );
        }


        DB::transaction(
            function () use (
                $validated,
                $manuscript,
                $technicalCheck
            ) {

                $technicalCheck->update([

                    'status' =>
                        'correction_required',

                    /*
                    |--------------------------------------------------------------------------
                    | IMPORTANT:
                    | Database enum uses correction_required
                    |--------------------------------------------------------------------------
                    */

                    'overall_result' =>
                        'correction_required',

                    'comments' =>
                        $validated['comments'],

                    'completed_by' =>
                        auth()->id(),

                    'completed_at' =>
                        now(),

                ]);


                $manuscript->update([

                    'status' =>
                        'technical_correction',

                    'current_stage' =>
                        'author_correction',

                ]);
            }
        );


        return redirect()
            ->route(
                'admin.manuscripts.technical-review.index'
            )
            ->with(
                'success',
                'Manuscript returned to author for technical correction.'
            );
    }
}