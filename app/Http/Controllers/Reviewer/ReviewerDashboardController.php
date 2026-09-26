<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\ReviewerInvitation;
use Illuminate\Support\Facades\Auth;

class ReviewerDashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Logged-in Reviewer
        |--------------------------------------------------------------------------
        */

        $reviewer = Auth::guard('reviewer')
            ->user();


        /*
        |--------------------------------------------------------------------------
        | Authentication Safety Check
        |--------------------------------------------------------------------------
        */

        if (!$reviewer) {

            return redirect()
                ->route('reviewer.login');
        }


        /*
        |--------------------------------------------------------------------------
        | Load Reviewer Profile
        |--------------------------------------------------------------------------
        */

        $reviewer->load('profile');

        $profile = $reviewer->profile;


        /*
        |--------------------------------------------------------------------------
        | Profile Missing
        |--------------------------------------------------------------------------
        */

        if (!$profile) {

            return redirect()
                ->route('reviewer.application.edit')
                ->with(
                    'warning',
                    'Your reviewer profile could not be found. Please complete your reviewer application.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Suspended Reviewer
        |--------------------------------------------------------------------------
        */

        if ($reviewer->isSuspended()) {

            Auth::guard('reviewer')
                ->logout();

            request()
                ->session()
                ->invalidate();

            request()
                ->session()
                ->regenerateToken();


            return redirect()
                ->route('reviewer.login')
                ->withErrors([
                    'email' =>
                        'Your reviewer account is currently suspended. Please contact the BMRC Editorial Office.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Forced Password Change
        |--------------------------------------------------------------------------
        */

        if ($reviewer->mustChangePassword()) {

            return redirect()
                ->route(
                    'reviewer.password.change'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Automatically Expire Old Review Invitations
        |--------------------------------------------------------------------------
        |
        | This keeps the dashboard synchronized with the invitation module.
        |
        */

        ReviewerInvitation::query()
            ->where(
                'reviewer_id',
                $reviewer->id
            )
            ->where(
                'status',
                'pending'
            )
            ->whereNotNull(
                'expires_at'
            )
            ->where(
                'expires_at',
                '<',
                now()
            )
            ->update([
                'status' => 'expired',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Pending Review Invitations
        |--------------------------------------------------------------------------
        |
        | These invitations will be displayed as notifications
        | on the reviewer dashboard.
        |
        */

        $pendingInvitations = ReviewerInvitation::query()
            ->with([
                'manuscript.journal',
                'manuscript.articleType',
                'inviter',
            ])
            ->where(
                'reviewer_id',
                $reviewer->id
            )
            ->where(
                'status',
                'pending'
            )
            ->orderByDesc(
                'invited_at'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Pending Invitation Count
        |--------------------------------------------------------------------------
        */

        $pendingInvitationCount =
            $pendingInvitations->count();


        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'reviewer.dashboard',
            compact(
                'reviewer',
                'profile',
                'pendingInvitations',
                'pendingInvitationCount'
            )
        );
    }
}