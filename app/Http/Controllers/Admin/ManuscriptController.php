<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manuscript;

class ManuscriptController extends Controller
{
    /**
     * Manuscript List
     */
    public function index()
    {
        abort_unless(
            auth()->user()->can('manuscript.view'),
            403
        );

        $manuscripts = Manuscript::with([
            'articleType',
            'journal',
            'submitter',
        ])
        ->latest()
        ->paginate(20);

        return view(
            'admin.manuscripts.index',
            compact('manuscripts')
        );
    }


    /**
     * Show Manuscript
     */
    public function show(Manuscript $manuscript)
    {
        abort_unless(
            auth()->user()->can('manuscript.view'),
            403
        );

        $manuscript->load([
            'articleType',
            'journal',
            'submitter',
            'details',
            'authors',
            'files',
            'ethicalInformation',
            'fundingInformation',
            'conflictOfInterest',
            'dataAvailability',
            'acknowledgement',
            'checklist',
            'technicalChecks',
        ]);

        $technicalCheck = $manuscript
            ->technicalChecks
            ->sortByDesc('check_number')
            ->first();

        return view(
            'admin.manuscripts.show',
            compact(
                'manuscript',
                'technicalCheck'
            )
        );
    }
}
