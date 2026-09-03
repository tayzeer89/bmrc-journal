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

    private function checklist()
    {
        return [

            [
                'key'  => 'article_type',
                'name' => 'Article Type',
            ],

            [
                'key'  => 'title',
                'name' => 'Manuscript Title',
            ],

            [
                'key'  => 'abstract',
                'name' => 'Abstract',
            ],

            [
                'key'  => 'keywords',
                'name' => 'Keywords',
            ],

            [
                'key'  => 'authors',
                'name' => 'Author Information',
            ],

            [
                'key'  => 'affiliations',
                'name' => 'Author Affiliations',
            ],

            [
                'key'  => 'corresponding_author',
                'name' => 'Corresponding Author',
            ],

            [
                'key'  => 'main_manuscript',
                'name' => 'Main Manuscript File',
            ],

            [
                'key'  => 'title_page',
                'name' => 'Title Page',
            ],

            [
                'key'  => 'figures',
                'name' => 'Figures',
            ],

            [
                'key'  => 'tables',
                'name' => 'Tables',
            ],

            [
                'key'  => 'references',
                'name' => 'References',
            ],

            [
                'key'  => 'word_count',
                'name' => 'Word Count',
            ],

            [
                'key'  => 'ethics',
                'name' => 'Ethical Approval',
            ],

            [
                'key'  => 'consent',
                'name' => 'Informed Consent',
            ],

            [
                'key'  => 'conflict_of_interest',
                'name' => 'Conflict of Interest',
            ],

            [
                'key'  => 'funding',
                'name' => 'Funding Statement',
            ],

            [
                'key'  => 'author_contribution',
                'name' => 'Author Contribution',
            ],

            [
                'key'  => 'data_availability',
                'name' => 'Data Availability',
            ],

            [
                'key'  => 'blinding',
                'name' => 'Blinding / Author Identification',
            ],

        ];
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
            ->with('items')
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

        DB::transaction(function () use ($manuscript) {

            $previous = $manuscript
                ->technicalChecks()
                ->latest('check_number')
                ->first();

            $checkNumber = $previous
                ? $previous->check_number + 1
                : 1;

            $technicalCheck = $manuscript
                ->technicalChecks()
                ->create([

                    'check_number' => $checkNumber,

                    'status' => 'in_progress',

                    'assigned_to' => auth()->id(),

                    'started_by' => auth()->id(),

                    'started_at' => now(),

                ]);


            foreach ($this->checklist() as $index => $item) {

                $technicalCheck->items()->create([

                    'check_key' => $item['key'],

                    'check_name' => $item['name'],

                    'sort_order' => $index + 1,

                    'result' => 'pending',

                ]);

            }

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

        $validated = $request->validate([

            'items' => [
                'required',
                'array',
            ],

            'items.*.result' => [
                'required',
                'in:pass,fail,na',
            ],

            'items.*.comment' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'comments' => [
                'nullable',
                'string',
                'max:10000',
            ],

        ]);


        DB::transaction(function () use (
            $validated,
            $technicalCheck
        ) {

            foreach (
                $validated['items']
                as $itemId => $itemData
            ) {

                $item = $technicalCheck
                    ->items()
                    ->findOrFail($itemId);

                $item->update([

                    'result' =>
                        $itemData['result'],

                    'comment' =>
                        $itemData['comment'] ?? null,

                    'checked_by' =>
                        auth()->id(),

                    'checked_at' =>
                        now(),

                ]);
            }


            $technicalCheck->update([

                'comments' =>
                    $validated['comments'] ?? null,

            ]);

        });


        return back()->with(
            'success',
            'Technical check saved successfully.'
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

        abort_unless($manuscript, 404);


        $failedItems = $technicalCheck
            ->items()
            ->where('result', 'fail')
            ->count();


        $pendingItems = $technicalCheck
            ->items()
            ->where('result', 'pending')
            ->count();


        if ($failedItems > 0) {

            return back()->with(
                'error',
                'Technical check cannot be completed because some items failed.'
            );
        }


        if ($pendingItems > 0) {

            return back()->with(
                'error',
                'Please complete all technical check items first.'
            );
        }


        DB::transaction(function () use (
            $manuscript,
            $technicalCheck
        ) {

            $technicalCheck->update([

                'status' =>
                    'passed',

                'completed_by' =>
                    auth()->id(),

                'completed_at' =>
                    now(),

            ]);


            $manuscript->update([

                'status' =>
                    'technical_check_passed',

                'current_stage' =>
                    'similarity_check',

            ]);

        });


        return redirect()
            ->route(
                'admin.manuscripts.technical-review.index'
            )
            ->with(
                'success',
                'Technical check completed successfully.'
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

        $manuscript = $technicalCheck->manuscript;

        abort_unless($manuscript, 404);


        $validated = $request->validate([

            'comments' => [
                'required',
                'string',
                'max:10000',
            ],

        ]);


        DB::transaction(function () use (
            $manuscript,
            $technicalCheck,
            $validated
        ) {

            $technicalCheck->update([

                'status' =>
                    'correction_required',

                'comments' =>
                    $validated['comments'],

            ]);


            $manuscript->update([

                'status' =>
                    'technical_correction',

                'current_stage' =>
                    'author_correction',

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


        $validated = $request->validate([

            'description' => [
                'required',
                'string',
                'max:10000',
            ],

        ]);


        /*
        |----------------------------------------------------------------------
        | This method should be connected to your technical_issues table.
        |----------------------------------------------------------------------
        */

        $technicalCheck->issues()->create([

            'description' =>
                $validated['description'],

            'reported_by' =>
                auth()->id(),

        ]);


        return back()->with(
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


        $validated = $request->validate([

            'assigned_to' => [
                'required',
                'integer',
                'exists:users,id',
            ],

        ]);


        $technicalCheck->update([

            'assigned_to' =>
                $validated['assigned_to'],

        ]);


        return back()->with(
            'success',
            'Technical check assigned successfully.'
        );
    }
}