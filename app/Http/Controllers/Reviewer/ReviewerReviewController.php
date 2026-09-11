<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReviewerReviewController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Active Reviews
    |--------------------------------------------------------------------------
    */

    public function active()
    {
        $reviewer =
            Auth::guard('reviewer')
                ->user();

        if (!$reviewer) {

            return redirect()
                ->route(
                    'reviewer.login'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Connect assignment model here later.
        |--------------------------------------------------------------------------
        */

        $reviews = collect();

        return view(
            'reviewer.reviews.active',
            compact(
                'reviewer',
                'reviews'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Completed Reviews
    |--------------------------------------------------------------------------
    */

    public function completed()
    {
        $reviewer =
            Auth::guard('reviewer')
                ->user();

        if (!$reviewer) {

            return redirect()
                ->route(
                    'reviewer.login'
                );
        }

        $reviews =
            collect();

        return view(
            'reviewer.reviews.completed',
            compact(
                'reviewer',
                'reviews'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Review History
    |--------------------------------------------------------------------------
    */

    public function history()
    {
        $reviewer =
            Auth::guard('reviewer')
                ->user();

        if (!$reviewer) {

            return redirect()
                ->route(
                    'reviewer.login'
                );
        }

        $reviews =
            collect();

        return view(
            'reviewer.reviews.history',
            compact(
                'reviewer',
                'reviews'
            )
        );
    }
}