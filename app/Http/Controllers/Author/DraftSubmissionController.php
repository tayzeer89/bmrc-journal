<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Manuscript;
use Illuminate\Support\Facades\Auth;

class DraftSubmissionController extends Controller
{


    /**
     * Display author draft manuscripts
     */
    public function index()
    {

        $drafts = Manuscript::where(
                'submitted_by',
                Auth::id()
            )
            ->where(
                'status',
                'draft'
            )
            ->latest()
            ->paginate(10);


        return view(
            'author.submission.drafts.index',
            compact('drafts')
        );

    }



     /**
     * Continue Draft Submission
     */
    public function edit($id)
    {

        $manuscript = Manuscript::where(
                'id',
                $id
            )
            ->where(
                'submitted_by',
                Auth::id()
            )
            ->where(
                'status',
                'draft'
            )
            ->firstOrFail();



        $nextStep = $manuscript->last_step + 1;



        switch($nextStep)
        {


            case 2:

                return redirect()
                    ->route(
                        'author.submission.step2',
                        $manuscript->id
                    );


            case 3:

                return redirect()
                    ->route(
                        'author.submission.step3',
                        $manuscript->id
                    );


            case 4:

                return redirect()
                    ->route(
                        'author.submission.step4',
                        $manuscript->id
                    );


            case 5:

                return redirect()
                    ->route(
                        'author.submission.step5',
                        $manuscript->id
                    );


            case 6:

                return redirect()
                    ->route(
                        'author.submission.step6',
                        $manuscript->id
                    );


            case 7:

                return redirect()
                    ->route(
                        'author.submission.step7',
                        $manuscript->id
                    );


            case 8:

                return redirect()
                    ->route(
                        'author.submission.step8',
                        $manuscript->id
                    );


            case 9:

                return redirect()
                    ->route(
                        'author.submission.step9',
                        $manuscript->id
                    );


            case 10:

                return redirect()
                    ->route(
                        'author.submission.step10',
                        $manuscript->id
                    );


            case 11:

                return redirect()
                    ->route(
                        'author.submission.step11',
                        $manuscript->id
                    );


            case 12:

                return redirect()
                    ->route(
                        'author.submission.step12',
                        $manuscript->id
                    );


            case 13:

                return redirect()
                    ->route(
                        'author.submission.step13',
                        $manuscript->id
                    );


            default:

                return redirect()
                    ->route(
                        'author.drafts.index'
                    );

        }

    }



    /**
     * Delete draft
     */
    public function destroy($id)
    {


        $manuscript = Manuscript::where(
                'submitted_by',
                Auth::id()
            )
            ->where(
                'status',
                'draft'
            )
            ->findOrFail($id);



        $manuscript->delete();



        return back()->with(
            'success',
            'Draft deleted successfully'
        );

    }


}