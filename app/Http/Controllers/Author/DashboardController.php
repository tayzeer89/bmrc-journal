<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Manuscript;
use App\Models\AuthorProfile;

class DashboardController extends Controller
{

    public function index()
    {

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Author Profile
        |--------------------------------------------------------------------------
        */

        $authorProfile = AuthorProfile::where('user_id',$user->id)
            ->first();



        /*
        |--------------------------------------------------------------------------
        | Manuscripts
        |--------------------------------------------------------------------------
        */

        $manuscripts = Manuscript::where('submitted_by',$user->id)
            ->latest()
            ->get();



        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $statistics = [

            'total' => $manuscripts->count(),


            'drafts' => $manuscripts
                ->where('status','draft')
                ->count(),


            'submitted' => $manuscripts
                ->where('status','submitted')
                ->count(),


            'under_review' => $manuscripts
                ->where('status','under_review')
                ->count(),


            'accepted' => $manuscripts
                ->where('status','accepted')
                ->count(),


            'published' => $manuscripts
                ->where('status','published')
                ->count(),

        ];



        /*
        |--------------------------------------------------------------------------
        | Profile Completion
        |--------------------------------------------------------------------------
        */

        $profileCompletion = 0;


        if($authorProfile)
        {

            $fields = [

                'first_name',
                'last_name',
                'email',
                'phone',
                'affiliation',
                'country'

            ];


            $completed = 0;


            foreach($fields as $field)
            {

                if(!empty($authorProfile->$field))
                {
                    $completed++;
                }

            }


            $profileCompletion =
                round(($completed/count($fields))*100);

        }



        return view(
            'author.dashboard',
            compact(
                'authorProfile',
                'manuscripts',
                'statistics',
                'profileCompletion'
            )
        );


    }

}