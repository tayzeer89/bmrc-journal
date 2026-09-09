<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Manuscript;
use Illuminate\Support\Facades\Auth;

class MyManuscriptController extends Controller
{
    /**
     * My Manuscripts List
     */
    public function index()
    {
        $manuscripts = Manuscript::where(
            'submitted_by',
            Auth::id()
        )
        ->with([
            'journal',
            'articleType',
            'latestPayment',
        ])
        ->latest()
        ->paginate(10);

        return view(
            'author.manuscripts.index',
            compact('manuscripts')
        );
    }


    /**
     * Manuscript Details
     */
    public function show(Manuscript $manuscript)
    {
        /*
        |--------------------------------------------------------------------------
        | Security
        |--------------------------------------------------------------------------
        */

        abort_if(
            (int) $manuscript->submitted_by !== (int) Auth::id(),
            403
        );


        /*
        |--------------------------------------------------------------------------
        | Load Manuscript Data
        |--------------------------------------------------------------------------
        */

        $manuscript->load([
            'journal',
            'articleType',
            'authors',
            'files',
            'details',

            /*
            | Technical Check
            */
            'technicalChecks.items',
            'technicalChecks.issues.technicalCheckItem',
            'technicalChecks.issues.manuscriptFile',
            'technicalChecks.issues.createdBy',
            'technicalChecks.assignedUser',
            'technicalChecks.startedBy',
            'technicalChecks.completedBy',

            /*
            | Payment
            */
            'latestPayment',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Find Technical Check
        |--------------------------------------------------------------------------
        */

        $technicalCheck = null;


        /*
        |--------------------------------------------------------------------------
        | Manuscript Currently Waiting For Author Correction
        |--------------------------------------------------------------------------
        */

        if ($manuscript->status === 'technical_correction') {

            $technicalCheck = $manuscript
                ->technicalChecks
                ->filter(function ($check) {

                    return
                        $check->status === 'correction_required'
                        ||
                        $check->overall_result === 'correction_required';

                })
                ->sortByDesc(function ($check) {

                    return $check->check_number ?? $check->id;

                })
                ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | Manuscript Is In Technical Review / Other Stage
        |--------------------------------------------------------------------------
        */

        if (!$technicalCheck) {

            $technicalCheck = $manuscript
                ->technicalChecks
                ->sortByDesc(function ($check) {

                    return $check->check_number ?? $check->id;

                })
                ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | Failed Technical Checklist Items
        |--------------------------------------------------------------------------
        */

        $failedTechnicalItems = collect();

        if ($technicalCheck) {

            $failedTechnicalItems = $technicalCheck
                ->items
                ->where('result', 'fail')
                ->sortBy('sort_order')
                ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | Technical Issues
        |--------------------------------------------------------------------------
        */

        $technicalIssues = collect();

        if ($technicalCheck) {

            $technicalIssues = $technicalCheck
                ->issues
                ->sortByDesc('id')
                ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | Open Technical Issues
        |--------------------------------------------------------------------------
        */

        $openTechnicalIssues = $technicalIssues
            ->where('status', 'open')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Show Technical Correction Form ONLY For This Status
        |--------------------------------------------------------------------------
        */

        $showTechnicalCorrection =
            $manuscript->status === 'technical_correction';


        /*
        |--------------------------------------------------------------------------
        | Hide Old Correction Information After Submission
        |--------------------------------------------------------------------------
        */

        if (!$showTechnicalCorrection) {

            $failedTechnicalItems = collect();

            $technicalIssues = collect();

            $openTechnicalIssues = collect();
        }


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'author.manuscripts.show',
            compact(
                'manuscript',
                'technicalCheck',
                'failedTechnicalItems',
                'technicalIssues',
                'openTechnicalIssues',
                'showTechnicalCorrection'
            )
        );
    }
}
