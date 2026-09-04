<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Manuscript;
use App\Models\ArticleType;
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
        | Author can only view their own manuscript.
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
            |--------------------------------------------------------------------------
            | Technical Checks
            |--------------------------------------------------------------------------
            */

            'technicalChecks.items',
            'technicalChecks.completedBy',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Get Latest Technical Check
        |--------------------------------------------------------------------------
        */

        $technicalCheck = $manuscript
            ->technicalChecks
            ->sortByDesc('check_number')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Get Failed Technical Check Items
        |--------------------------------------------------------------------------
        */

        $failedTechnicalItems = collect();

        if ($technicalCheck) {

            $failedTechnicalItems = $technicalCheck
                ->items
                ->where('result', 'fail')
                ->sortBy('sort_order');
        }


        /*
        |--------------------------------------------------------------------------
        | Author Manuscript View
        |--------------------------------------------------------------------------
        */

        return view(
            'author.manuscripts.show',
            compact(
                'manuscript',
                'technicalCheck',
                'failedTechnicalItems'
            )
        );
    }
}