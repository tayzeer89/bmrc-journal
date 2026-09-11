<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
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
        |
        | Reviewer model already contains:
        |
        | public function profile()
        | {
        |     return $this->hasOne(ReviewerProfile::class);
        | }
        |
        */

        $reviewer->load('profile');

        $profile = $reviewer->profile;


        /*
        |--------------------------------------------------------------------------
        | Profile Missing
        |--------------------------------------------------------------------------
        |
        | Normally registration creates the profile automatically.
        | This is only a safety/fallback condition.
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
        |
        | Suspended reviewers should not access the reviewer portal.
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
        |
        | This applies primarily to reviewers created by
        | Editorial Office using a temporary password.
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
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | DO NOT redirect incomplete / pending / rejected reviewers away
        | from this dashboard.
        |
        | All authenticated non-suspended reviewers may see their dashboard.
        |
        | The dashboard UI decides which actions are available according to:
        |
        | draft
        | pending_approval
        | update_requested
        | approved
        | rejected
        |
        |--------------------------------------------------------------------------
        */


        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'reviewer.dashboard',
            compact(
                'reviewer',
                'profile'
            )
        );
    }
}