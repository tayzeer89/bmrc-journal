<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\ReviewerProfile;
use Illuminate\Support\Facades\Auth;

class ReviewerDashboardController extends Controller
{
    public function index()
    {
        $reviewer = ReviewerProfile::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        if ($reviewer->approval_status !== 'approved') {

            Auth::logout();

            return redirect()
                ->route('reviewer.login')
                ->withErrors([
                    'email' =>
                        'Your reviewer account is not approved.'
                ]);
        }


        return view(
            'reviewer.dashboard',
            compact('reviewer')
        );
    }
}