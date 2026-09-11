<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReviewerProfileController extends Controller
{
    public function show()
    {
        $reviewer = Auth::guard('reviewer')
            ->user();

        if (!$reviewer) {

            return redirect()
                ->route(
                    'reviewer.login'
                );
        }


        $reviewer->load('profile');

        $profile = $reviewer->profile;


        if (!$profile) {

            return redirect()
                ->route(
                    'reviewer.application.edit'
                )
                ->with(
                    'warning',
                    'Please complete your reviewer profile.'
                );
        }


        return view(
            'reviewer.profile.show',
            compact(
                'reviewer',
                'profile'
            )
        );
    }
}