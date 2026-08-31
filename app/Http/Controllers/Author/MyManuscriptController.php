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
                'articleType'
            ])
            ->latest()
            ->paginate(10);



        return view(
            'author.manuscripts.index',
            compact(
                'manuscripts'
            )
        );


    }




    /**
     * Manuscript Details
     */
    public function show(
        Manuscript $manuscript
    )
    {


        abort_if(
            $manuscript->submitted_by 
            != Auth::id(),
            403
        );


        $manuscript->load([

            'journal',
            'articleType',
            'authors',
            'files',
            'details'

        ]);



        return view(
            'author.manuscripts.show',
            compact(
                'manuscript'
            )
        );


    }



}