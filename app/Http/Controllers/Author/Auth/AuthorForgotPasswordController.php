<?php

namespace App\Http\Controllers\Author\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AuthorForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Forgot Password Form
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view(
            'author.auth.forgot-password'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Send Password Reset Link
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'email' => [
                'required',
                'email',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Use Main Users Password Broker
        |--------------------------------------------------------------------------
        |
        | Authors are currently authenticated through the users table and
        | the default web guard. Therefore the "users" password broker
        | must be used here.
        |
        */

        $status = Password::broker('users')
            ->sendResetLink([
                'email' => $validated['email'],
            ]);


        /*
        |--------------------------------------------------------------------------
        | Password Reset Link Sent
        |--------------------------------------------------------------------------
        */

        if ($status === Password::RESET_LINK_SENT) {

            return back()
                ->with(
                    'status',
                    __($status)
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Unable to Send Reset Link
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