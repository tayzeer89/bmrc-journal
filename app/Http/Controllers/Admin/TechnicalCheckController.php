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

        $technicalCheck = $manuscript
            ->technicalChecks()
            ->with([
                'items',
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

        /*
        |--------------------------------------------------------------------------
        | Prevent starting another check if one is already active
        |--------------------------------------------------------------------------
        */

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


        DB::transaction(function () use ($manuscript) {

            /*
            |--------------------------------------------------------------------------
            | Determine next check number
            |--------------------------------------------------------------------------
            */

            $previousCheck = $manuscript
                ->technicalChecks()
                ->latest('check_number')
                ->first();

            $checkNumber = $previousCheck
                ? $previousCheck->check_number + 1
                : 1;


            /*
            |--------------------------------------------------------------------------
            | Create Technical Check
            |--------------------------------------------------------------------------
            */

            $technicalCheck = $manuscript
                ->technicalChecks()
                ->create([

                    'check_number' => $checkNumber,

                    'status' => 'in_progress',

                    'assigned_to' => auth()->id(),

                    'started_by' => auth()->id(),

                    'started_at' => now(),

                ]);


            /*
            |--------------------------------------------------------------------------
            | Create Checklist Items
            |--------------------------------------------------------------------------
            */

            foreach ($this->checklist() as $index => $item) {

                $technicalCheck
                    ->items()
                    ->create([

                        'check_key' => $item['key'],

                        'check_name' => $item['name'],

                        'sort_order' => $index + 1,

                        'result' => 'pending',

                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Update Manuscript Workflow
            |--------------------------------------------------------------------------
            */

            $manuscript->update([

                'status' => 'technical_check',

                'current_stage' => 'technical_review',

            ]);
        });


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
    | Update Technical Check
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

        $request->validate([

            'items' => [
                'required',
                'array',
            ],

            'items.*.result' => [
                'required',
                'in:pass,fail,pending',
            ],

            'items.*.comments' => [
                'nullable',
                'string',
            ],

        ]);


        DB::transaction(function () use (
            $request,
            $technicalCheck
        ) {

            foreach (
                $request->input('items', [])
                as $itemId => $data
            ) {

                $item = $technicalCheck
                    ->items()
                    ->find($itemId);

                if (!$item) {
                    continue;
                }

                $item->update([

                    'result' => $data['result'],

                    'comments' =>
                        $data['comments'] ?? null,

                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Determine Overall Result
            |--------------------------------------------------------------------------
            */

            $failed = $technicalCheck
                ->items()
                ->where('result', 'fail')
                ->exists();

            $pending = $technicalCheck
                ->items()
                ->where('result', 'pending')
                ->exists();


           if ($failed) {

                $overallResult = 'failed';

            } elseif ($pending) {

                $overallResult = null;

            } else {

                $overallResult = 'passed';
            }


            $technicalCheck->update([

                'overall_result' => $overallResult,

            ]);
        });


        return back()
            ->with(
                'success',
                'Technical checklist saved successfully.'
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


        /*
        |--------------------------------------------------------------------------
        | Cannot complete an already completed check
        |--------------------------------------------------------------------------
        */

        if ($technicalCheck->status === 'passed') {

            return back()
                ->with(
                    'warning',
                    'This technical check has already been completed.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Check Failed Items
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
                    'Technical check cannot be completed because some items failed.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Check Pending Items
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
        | Complete
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
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
        });


       return redirect()
        ->route('admin.manuscripts.payment.index')
        ->with(
            'success',
            'Technical check completed successfully. Manuscript moved to Payment Setup.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Return to Author
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

        $request->validate([

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


        DB::transaction(function () use (
            $request,
            $manuscript,
            $technicalCheck
        ) {

            $technicalCheck->update([

                'status' => 'correction_required',

                'overall_result' => 'fail',

                'comments' => $request->comments,

            ]);


            $manuscript->update([

                'status' => 'technical_correction',

                'current_stage' => 'author_correction',

            ]);
        });


        return redirect()
            ->route(
                'admin.manuscripts.technical-review.index'
            )
            ->with(
                'success',
                'Manuscript returned to author for technical correction.'
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

        $request->validate([

            'technical_check_item_id' => [
                'nullable',
                'exists:technical_check_items,id',
            ],

            'category' => [
                'required',
                'string',
            ],

            'severity' => [
                'required',
                'in:minor,major,critical',
            ],

            'description' => [
                'required',
                'string',
            ],

            'required_action' => [
                'nullable',
                'string',
            ],

        ]);


        $technicalCheck->issues()->create([

            'technical_check_item_id' =>
                $request->technical_check_item_id,

            'category' =>
                $request->category,

            'severity' =>
                $request->severity,

            'description' =>
                $request->description,

            'required_action' =>
                $request->required_action,

            'status' => 'open',

            'created_by' => auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Technical issue added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Assign Technical Check
    |--------------------------------------------------------------------------
    */

    public function assign(
        Request $request,
        TechnicalCheck $technicalCheck
    ) {

        abort_unless(
            auth()->user()->can('technical_check.assign'),
            403
        );

        $request->validate([

            'assigned_to' => [
                'required',
                'exists:users,id',
            ],

        ]);


        $technicalCheck->update([

            'assigned_to' =>
                $request->assigned_to,

        ]);


        return back()
            ->with(
                'success',
                'Technical check assigned successfully.'
            );
    }
}