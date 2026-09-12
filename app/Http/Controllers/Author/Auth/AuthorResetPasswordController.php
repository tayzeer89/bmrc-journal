<?php

namespace App\Http\Controllers\Author\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthorResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Reset Password Form
    |--------------------------------------------------------------------------
    */

    public function create(
        Request $request,
        string $token
    ) {
        return view(
            'author.auth.reset-password',
            [
                'token' => $token,
                'email' => $request->query('email'),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Reset Author Password
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'token' => [
                'required',
            ],

            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8),
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Reset Password Using Users Broker
        |--------------------------------------------------------------------------
        */

        $status = Password::broker('users')
            ->reset(
                [
                    'email' =>
                        $validated['email'],

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
                    User $user,
                    string $password
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Update Password
                    |--------------------------------------------------------------------------
                    */

                    $user->forceFill([

                        'password' =>
                            Hash::make($password),

                        'remember_token' =>
                            Str::random(60),

                    ])->save();


                    /*
                    |--------------------------------------------------------------------------
                    | Password Reset Event
                    |--------------------------------------------------------------------------
                    */

                    event(
                        new PasswordReset($user)
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
                ->route('author.login')
                ->with(
                    'status',
                    __($status)
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Password Reset Failed
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