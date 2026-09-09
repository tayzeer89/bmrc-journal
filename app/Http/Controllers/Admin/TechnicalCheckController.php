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
    | Technical Check Checklist
    |--------------------------------------------------------------------------
    */

    private function checklist(): array
    {
        return [
            ['key' => 'article_type', 'name' => 'Article Type'],
            ['key' => 'title', 'name' => 'Title'],
            ['key' => 'author_information', 'name' => 'Author Information'],
            ['key' => 'abstract', 'name' => 'Abstract'],
            ['key' => 'keywords', 'name' => 'Keywords'],
            ['key' => 'manuscript_file', 'name' => 'Manuscript File'],
            ['key' => 'blinding', 'name' => 'Blinding'],
            ['key' => 'manuscript_structure', 'name' => 'Manuscript Structure'],
            ['key' => 'tables', 'name' => 'Tables'],
            ['key' => 'figures', 'name' => 'Figures'],
            ['key' => 'references', 'name' => 'References'],
            ['key' => 'ethics', 'name' => 'Ethical Approval / Research Ethics'],
            ['key' => 'consent', 'name' => 'Informed Consent'],
            ['key' => 'conflict_of_interest', 'name' => 'Conflict of Interest'],
            ['key' => 'funding', 'name' => 'Funding Information'],
            ['key' => 'author_contribution', 'name' => 'Author Contribution'],
            ['key' => 'data_availability', 'name' => 'Data Availability'],
            ['key' => 'trial_registration', 'name' => 'Trial Registration'],
            ['key' => 'submission_completeness', 'name' => 'Submission Completeness'],
            ['key' => 'declarations', 'name' => 'Declarations'],
            ['key' => 'acknowledgement', 'name' => 'Acknowledgement'],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Active Technical Check Statuses
    |--------------------------------------------------------------------------
    */

    private function activeStatuses(): array
    {
        return [
            'pending',
            'in_progress',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Map Checklist Key To Technical Issue Category
    |--------------------------------------------------------------------------
    */

    private function issueCategory(string $checkKey): string
    {
        return match ($checkKey) {
            'article_type' => 'article_type',
            'title' => 'title',
            'author_information' => 'author_information',
            'abstract' => 'abstract',
            'keywords' => 'keywords',
            'blinding' => 'blinding',
            'tables' => 'tables',
            'figures' => 'figures',
            'references' => 'references',
            'ethics' => 'ethics',
            'consent' => 'consent',
            'conflict_of_interest' => 'conflict_of_interest',
            'funding' => 'funding',
            'author_contribution' => 'author_contribution',
            'data_availability' => 'data_availability',
            'trial_registration' => 'trial_registration',
            'submission_completeness' => 'submission_completeness',
            'declarations' => 'declarations',

            'manuscript_structure' => 'formatting',

            'manuscript_file',
            'acknowledgement' => 'other',

            default => 'other',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Technical Review Dashboard
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
    | Technical Check Details
    |--------------------------------------------------------------------------
    */

    public function show(Manuscript $manuscript)
    {
        abort_unless(
            auth()->user()->can('technical_check.view'),
            403
        );

        $manuscript->loadMissing([
            'articleType',
            'journal',
            'submitter',
            'authors',
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

        $issues = $technicalCheck
            ? $technicalCheck->issues
            : collect();

        $openIssues = $issues
            ->where('status', 'open')
            ->values();

        $failedItems = $technicalCheck
            ? $technicalCheck->items
                ->where('result', 'fail')
                ->sortBy('sort_order')
                ->values()
            : collect();

        $pendingItems = $technicalCheck
            ? $technicalCheck->items
                ->where('result', 'pending')
                ->sortBy('sort_order')
                ->values()
            : collect();

        return view(
            'admin.manuscripts.technical-check',
            compact(
                'manuscript',
                'technicalCheck',
                'issues',
                'openIssues',
                'failedItems',
                'pendingItems'
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
            ->whereIn('status', $this->activeStatuses())
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
                    ? ((int) $previousCheck->check_number + 1)
                    : 1;

                $technicalCheck = $manuscript
                    ->technicalChecks()
                    ->create([
                        'check_number' => $checkNumber,
                        'status' => 'in_progress',
                        'overall_result' => null,
                        'assigned_to' => auth()->id(),
                        'started_by' => auth()->id(),
                        'started_at' => now(),
                    ]);

                foreach ($this->checklist() as $index => $item) {
                    $technicalCheck
                        ->items()
                        ->create([
                            'check_key' => $item['key'],
                            'check_name' => $item['name'],
                            'sort_order' => $index + 1,
                            'result' => 'pending',
                            'comment' => null,
                        ]);
                }

                $manuscript->update([
                    'status' => 'technical_check',
                    'current_stage' => 'technical_review',
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
                'Technical check #' .
                $technicalCheck->check_number .
                ' started successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Checklist
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

        if (!in_array(
            $technicalCheck->status,
            $this->activeStatuses(),
            true
        )) {
            return back()->with(
                'warning',
                'This technical check is no longer active.'
            );
        }

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

        $submittedIds = array_keys(
            $validated['items']
        );

        $checkItems = $technicalCheck
            ->items()
            ->whereIn('id', $submittedIds)
            ->get()
            ->keyBy('id');

        /*
        |--------------------------------------------------------------------------
        | Validate Failed Items
        |--------------------------------------------------------------------------
        |
        | A failed checklist item is valid when it has:
        |
        | 1. A correction comment
        | OR
        | 2. A linked OPEN Technical Issue
        |
        */

        foreach ($validated['items'] as $itemId => $data) {

            if (($data['result'] ?? null) !== 'fail') {
                continue;
            }

            $item = $checkItems->get($itemId);

            if (!$item) {
                continue;
            }

            $hasComment = filled(
                trim($data['comment'] ?? '')
            );

            $hasLinkedIssue = $technicalCheck
                ->issues()
                ->where(
                    'technical_check_item_id',
                    $item->id
                )
                ->where(
                    'status',
                    'open'
                )
                ->exists();

            if (!$hasComment && !$hasLinkedIssue) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Please provide a correction comment or add a Technical Issue linked to "' .
                        $item->check_name .
                        '" because it has been marked as failed.'
                    );
            }
        }

        DB::transaction(
            function () use (
                $validated,
                $technicalCheck,
                $checkItems
            ) {

                foreach ($validated['items'] as $itemId => $data) {

                    $item = $checkItems->get($itemId);

                    if (!$item) {
                        continue;
                    }

                    $oldResult = $item->result;
                    $newResult = $data['result'];

                    $item->update([
                        'result' => $newResult,
                        'comment' => $data['comment'] ?? null,
                        'checked_by' => auth()->id(),
                        'checked_at' => now(),
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Resolve Linked Issues When Item Is No Longer Failed
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $oldResult === 'fail' &&
                        in_array(
                            $newResult,
                            ['pass', 'na'],
                            true
                        )
                    ) {
                        $technicalCheck
                            ->issues()
                            ->where(
                                'technical_check_item_id',
                                $item->id
                            )
                            ->where(
                                'status',
                                'open'
                            )
                            ->update([
                                'status' => 'resolved',
                            ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Overall Technical Result
                |--------------------------------------------------------------------------
                */

                $hasFailed = $technicalCheck
                    ->items()
                    ->where(
                        'result',
                        'fail'
                    )
                    ->exists();

                $hasPending = $technicalCheck
                    ->items()
                    ->where(
                        'result',
                        'pending'
                    )
                    ->exists();

                if ($hasFailed) {
                    $overallResult = 'correction_required';
                } elseif ($hasPending) {
                    $overallResult = null;
                } else {
                    $overallResult = 'passed';
                }

                $technicalCheck->update([
                    'overall_result' => $overallResult,
                ]);
            }
        );

        return back()->with(
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

        if (!in_array(
            $technicalCheck->status,
            $this->activeStatuses(),
            true
        )) {
            return back()->with(
                'error',
                'Technical issues can only be added to an active technical check.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | technical_check_item_id is REQUIRED.
        |
        | This ensures every manually created Technical Issue is linked to
        | the exact checklist item.
        |
        */

        $validated = $request->validate([
            'technical_check_item_id' => [
                'required',
                'integer',
                'exists:technical_check_items,id',
            ],

            'manuscript_file_id' => [
                'nullable',
                'integer',
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

        $manuscript = $technicalCheck->manuscript;

        abort_unless(
            $manuscript,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Validate Checklist Item Ownership
        |--------------------------------------------------------------------------
        */

        $checkItem = $technicalCheck
            ->items()
            ->whereKey(
                $validated['technical_check_item_id']
            )
            ->first();

        if (!$checkItem) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'The selected checklist item does not belong to this technical check.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Recommended Rule:
        | Technical Issue Should Be Added To A Failed Item
        |--------------------------------------------------------------------------
        */

        if ($checkItem->result !== 'fail') {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Please mark "' .
                    $checkItem->check_name .
                    '" as Fail and save the checklist before adding a Technical Issue.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Manuscript File Ownership
        |--------------------------------------------------------------------------
        */

        if (!empty(
            $validated['manuscript_file_id']
        )) {
            $fileExists = $manuscript
                ->files()
                ->whereKey(
                    $validated['manuscript_file_id']
                )
                ->exists();

            if (!$fileExists) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'The selected file does not belong to this manuscript.'
                    );
            }
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
                    'Please select the exact manuscript file that requires correction.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Open Issue
        |--------------------------------------------------------------------------
        */

        $duplicateIssue = $technicalCheck
            ->issues()
            ->where(
                'technical_check_item_id',
                $checkItem->id
            )
            ->where(
                'status',
                'open'
            )
            ->exists();

        if ($duplicateIssue) {
            return back()
                ->withInput()
                ->with(
                    'warning',
                    'An open Technical Issue already exists for "' .
                    $checkItem->check_name .
                    '".'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Technical Issue
        |--------------------------------------------------------------------------
        */

        $technicalCheck
            ->issues()
            ->create([
                'technical_check_item_id' =>
                    $checkItem->id,

                'manuscript_file_id' =>
                    $validated['manuscript_file_id']
                    ?? null,

                'category' =>
                    $validated['category'],

                'severity' =>
                    $validated['severity'],

                'description' =>
                    $validated['description'],

                'required_action' =>
                    $validated['required_action']
                    ?? null,

                'status' =>
                    'open',

                'created_by' =>
                    auth()->id(),
            ]);

        return back()->with(
            'success',
            'Technical issue added successfully for "' .
            $checkItem->check_name .
            '".'
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

        $manuscript = $technicalCheck->manuscript;

        abort_unless(
            $manuscript,
            404
        );

        if (!in_array(
            $technicalCheck->status,
            $this->activeStatuses(),
            true
        )) {
            return back()->with(
                'warning',
                'This technical check has already been completed or is no longer active.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Failed Checklist Items
        |--------------------------------------------------------------------------
        */

        $failedItems = $technicalCheck
            ->items()
            ->where(
                'result',
                'fail'
            )
            ->count();

        if ($failedItems > 0) {
            return back()->with(
                'error',
                'Technical check cannot be completed because one or more checklist items failed. Please return the manuscript to the author for correction.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pending Checklist Items
        |--------------------------------------------------------------------------
        */

        $pendingItems = $technicalCheck
            ->items()
            ->where(
                'result',
                'pending'
            )
            ->count();

        if ($pendingItems > 0) {
            return back()->with(
                'error',
                'Please complete all technical checklist items first.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Open Technical Issues
        |--------------------------------------------------------------------------
        */

        $openIssues = $technicalCheck
            ->issues()
            ->where(
                'status',
                'open'
            )
            ->count();

        if ($openIssues > 0) {
            return back()->with(
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
                    'status' => 'passed',
                    'overall_result' => 'passed',
                    'completed_by' => auth()->id(),
                    'completed_at' => now(),
                ]);

                $manuscript->update([
                    'status' => 'payment_setup',
                    'current_stage' => 'payment',
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
    | Return Manuscript To Author
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

        $manuscript = $technicalCheck->manuscript;

        abort_unless(
            $manuscript,
            404
        );

        if (!in_array(
            $technicalCheck->status,
            $this->activeStatuses(),
            true
        )) {
            return back()->with(
                'error',
                'This technical check is no longer active and cannot be returned to the author.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Failed Checklist Items
        |--------------------------------------------------------------------------
        */

        $failedItems = $technicalCheck
            ->items()
            ->where(
                'result',
                'fail'
            )
            ->orderBy(
                'sort_order'
            )
            ->get();

        if ($failedItems->isEmpty()) {
            return back()->with(
                'error',
                'Please mark at least one checklist item as failed before returning the manuscript to the author.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Failed Items
        |--------------------------------------------------------------------------
        |
        | Every failed item must have:
        |
        | Correction Comment
        | OR
        | Linked Open Technical Issue
        |
        */

        foreach ($failedItems as $item) {

            $hasComment = filled(
                trim($item->comment ?? '')
            );

            $hasLinkedIssue = $technicalCheck
                ->issues()
                ->where(
                    'technical_check_item_id',
                    $item->id
                )
                ->where(
                    'status',
                    'open'
                )
                ->exists();

            if (!$hasComment && !$hasLinkedIssue) {
                return back()->with(
                    'error',
                    'Please provide a correction comment or add a Technical Issue linked to "' .
                    $item->check_name .
                    '" before returning the manuscript to the author.'
                );
            }
        }

        DB::transaction(
            function () use (
                $validated,
                $manuscript,
                $technicalCheck,
                $failedItems
            ) {

                /*
                |--------------------------------------------------------------------------
                | Automatically Create Missing Issues
                |--------------------------------------------------------------------------
                |
                | If officer entered checklist correction comment but did not
                | manually add an issue, automatically create the issue.
                |
                */

                foreach ($failedItems as $item) {

                    $issueExists = $technicalCheck
                        ->issues()
                        ->where(
                            'technical_check_item_id',
                            $item->id
                        )
                        ->where(
                            'status',
                            'open'
                        )
                        ->exists();

                    $hasComment = filled(
                        trim($item->comment ?? '')
                    );

                    if (
                        !$issueExists &&
                        $hasComment
                    ) {
                        $technicalCheck
                            ->issues()
                            ->create([
                                'technical_check_item_id' =>
                                    $item->id,

                                'manuscript_file_id' =>
                                    null,

                                'category' =>
                                    $this->issueCategory(
                                        $item->check_key
                                    ),

                                'severity' =>
                                    'major',

                                'description' =>
                                    $item->comment,

                                'required_action' =>
                                    $item->comment,

                                'status' =>
                                    'open',

                                'created_by' =>
                                    auth()->id(),
                            ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Update Technical Check
                |--------------------------------------------------------------------------
                */

                $technicalCheck->update([
                    'status' =>
                        'correction_required',

                    'overall_result' =>
                        'correction_required',

                    'comments' =>
                        $validated['comments'],

                    'completed_by' =>
                        auth()->id(),

                    'completed_at' =>
                        now(),
                ]);

                /*
                |--------------------------------------------------------------------------
                | Update Manuscript Workflow
                |--------------------------------------------------------------------------
                */

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
                'Manuscript returned to author for technical correction successfully.'
            );
    }
}