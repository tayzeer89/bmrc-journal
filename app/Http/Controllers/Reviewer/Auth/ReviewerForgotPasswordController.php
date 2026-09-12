<?php

namespace App\Http\Controllers\Reviewer\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ReviewerForgotPasswordController extends Controller
{
    /**
     * Show the reviewer forgot-password form.
     */
    public function create(): View
    {
        return view('reviewer.auth.forgot-password');
    }


    /**
     * Send a password-reset link to the reviewer.
     */
    public function store(Request $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Email
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Send Reset Link Using Reviewer Password Broker
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | We use the "reviewers" broker, not the default "users" broker.
        |
        */

        $status = Password::broker('reviewers')
            ->sendResetLink([
                'email' => $validated['email'],
            ]);


        /*
        |--------------------------------------------------------------------------
        | Reset Link Sent Successfully
        |--------------------------------------------------------------------------
        */

        if ($status === Password::RESET_LINK_SENT) {

            return back()->with(
                'status',
                __($status)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Failed to Send Reset Link
        |--------------------------------------------------------------------------
        */

        return back()
            ->withInput(
                $request->only('email')
            )
            ->withErrors([
                'email' => __($status),
            ]);
    }
}