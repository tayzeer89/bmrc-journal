<?php

namespace App\Http\Controllers\Reviewer\Auth;

use App\Http\Controllers\Controller;
use App\Models\Reviewer;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class ReviewerResetPasswordController extends Controller
{
    /**
     * Show the reviewer reset-password form.
     */
    public function create(
        Request $request,
        string $token
    ): View {

        return view(
            'reviewer.auth.reset-password',
            [
                'token' => $token,
                'email' => $request->query('email'),
            ]
        );
    }


    /**
     * Reset the reviewer's password.
     */
    public function store(Request $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Reset Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'token' => [
                'required',
                'string',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],

            'password' => [
                'required',
                'string',
                'confirmed',

                PasswordRule::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers(),
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Reset Password Using Reviewer Broker
        |--------------------------------------------------------------------------
        */

        $status = Password::broker('reviewers')
            ->reset(
                [
                    'email' => $validated['email'],

                    'password' =>
                        $validated['password'],

                    'password_confirmation' =>
                        $request->input(
                            'password_confirmation'
                        ),

                    'token' =>
                        $validated['token'],
                ],

                function (
                    Reviewer $reviewer,
                    string $password
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Update Reviewer Password
                    |--------------------------------------------------------------------------
                    |
                    | Your Reviewer model uses:
                    |
                    | 'password' => 'hashed'
                    |
                    | Therefore Laravel automatically hashes the password.
                    | Do NOT use Hash::make() again here.
                    |
                    */

                    $reviewer->forceFill([
                        'password' => $password,

                        'must_change_password' =>
                            false,

                        'password_changed_at' =>
                            now(),

                        'remember_token' =>
                            Str::random(60),
                    ])->save();


                    /*
                    |--------------------------------------------------------------------------
                    | Fire Laravel Password Reset Event
                    |--------------------------------------------------------------------------
                    */

                    event(
                        new PasswordReset(
                            $reviewer
                        )
                    );
                }
            );


        /*
        |--------------------------------------------------------------------------
        | Password Reset Successful
        |--------------------------------------------------------------------------
        */

        if ($status === Password::PASSWORD_RESET) {

            return redirect()
                ->route('reviewer.login')
                ->with(
                    'success',
                    'Your password has been reset successfully. You can now sign in with your new password.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Reset Failed
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