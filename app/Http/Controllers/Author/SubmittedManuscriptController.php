<?php

namespace App\Http\Controllers\Author;


use App\Http\Controllers\Controller;
use App\Models\Manuscript;
use Illuminate\Support\Facades\Auth;



class SubmittedManuscriptController extends Controller
{


    /**
     * Submitted Manuscripts List
     */
    public function index()
    {


        $manuscripts = Manuscript::where(
                'submitted_by',
                Auth::id()
            )
            ->where(
                'status',
                'submitted'
            )
            ->with([
                'journal',
                'articleType'
            ])
            ->latest('submitted_at')
            ->paginate(10);



        return view(
            'author.submitted.index',
            compact('manuscripts')
        );


    }



    /**
     * Submitted Manuscript Details
     */
    public function show(
        Manuscript $manuscript
    )
    {


        abort_if(
            $manuscript->submitted_by != Auth::id(),
            403
        );


        $manuscript->load([

            'journal',
            'articleType',
            'authors',
            'files'

        ]);



        return view(
            'author.submitted.show',
            compact('manuscript')
        );

    }


}