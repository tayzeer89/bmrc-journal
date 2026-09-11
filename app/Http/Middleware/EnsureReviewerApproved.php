<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureReviewerApproved
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $reviewer = Auth::guard('reviewer')->user();

        if (!$reviewer) {

            return redirect()
                ->route('reviewer.login');
        }

        $reviewer->loadMissing('profile');

        /*
        |--------------------------------------------------------------------------
        | Account must be approved
        |--------------------------------------------------------------------------
        */

        if ($reviewer->status !== 'approved') {

            return redirect()
                ->route('reviewer.dashboard')
                ->with(
                    'warning',
                    'Your reviewer account has not yet been approved.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Professional profile must also be approved
        |--------------------------------------------------------------------------
        */

        if (
            !$reviewer->profile
            ||
            $reviewer->profile->approval_status
                !== 'approved'
        ) {

            return redirect()
                ->route('reviewer.dashboard')
                ->with(
                    'warning',
                    'Your reviewer profile is not yet approved.'
                );
        }

        return $next($request);
    }
}