<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReviewerInvitationController extends Controller
{
    public function index()
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

        $reviewer->load('profile');

        /*
        |--------------------------------------------------------------------------
        | Later
        |--------------------------------------------------------------------------
        |
        | $invitations = ReviewerInvitation::where(
        |     'reviewer_id',
        |     $reviewer->id
        | )->latest()->paginate(20);
        |
        */

        $invitations =
            collect();

        return view(
            'reviewer.invitations.index',
            compact(
                'reviewer',
                'invitations'
            )
        );
    }
}