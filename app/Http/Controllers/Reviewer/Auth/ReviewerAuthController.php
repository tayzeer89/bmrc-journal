<?php

namespace App\Http\Controllers\Reviewer\Auth;

use App\Http\Controllers\Controller;
use App\Models\Reviewer;
use App\Models\ReviewerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ReviewerAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REGISTRATION
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        return view('reviewer.auth.register');
    }


    public function register(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Registration
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:30',
            ],

            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'middle_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'last_name' => [
                'required',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | Reviewer Email
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            |
            | We check the email ONLY in reviewers table.
            |
            | Therefore the same email can exist in:
            |
            | users.email
            | reviewers.email
            |
            */

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:reviewers,email',
            ],

            'mobile' => [
                'required',
                'string',
                'max:30',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);


        DB::beginTransaction();


        try {

            /*
            |--------------------------------------------------------------------------
            | Create Reviewer Account
            |--------------------------------------------------------------------------
            */

            $reviewer = Reviewer::create([

                'name' => trim(
                    $validated['first_name']
                    . ' '
                    . ($validated['middle_name'] ?? '')
                    . ' '
                    . $validated['last_name']
                ),

                'email' => $validated['email'],

                'password' => Hash::make(
                    $validated['password']
                ),

                /*
                |--------------------------------------------------------------------------
                | IMPORTANT
                |--------------------------------------------------------------------------
                |
                | Do NOT use "active".
                |
                | Your reviewers.status column contains:
                |
                | pending
                | approved
                | rejected
                | suspended
                |
                */

                'status' => 'pending',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Generate Reviewer Code
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | BMRC-REV-A8K29XQP
            |
            */

            do {

                $reviewerCode =
                    'BMRC-REV-' .
                    strtoupper(
                        Str::random(8)
                    );

            } while (
                ReviewerProfile::where(
                    'reviewer_code',
                    $reviewerCode
                )->exists()
            );


            /*
            |--------------------------------------------------------------------------
            | Create Reviewer Profile
            |--------------------------------------------------------------------------
            */

            ReviewerProfile::create([

                /*
                |--------------------------------------------------------------------------
                | Relationship
                |--------------------------------------------------------------------------
                */

                'reviewer_id' => $reviewer->id,


                /*
                |--------------------------------------------------------------------------
                | Reviewer Identification
                |--------------------------------------------------------------------------
                */

                'reviewer_code' => $reviewerCode,


                /*
                |--------------------------------------------------------------------------
                | Personal Information
                |--------------------------------------------------------------------------
                */

                'title' =>
                    $validated['title'],

                'first_name' =>
                    $validated['first_name'],

                'middle_name' =>
                    $validated['middle_name'] ?? null,

                'last_name' =>
                    $validated['last_name'],


                /*
                |--------------------------------------------------------------------------
                | Display Name
                |--------------------------------------------------------------------------
                */

                'display_name' => trim(
                    $validated['title']
                    . ' '
                    . $validated['first_name']
                    . ' '
                    . ($validated['middle_name'] ?? '')
                    . ' '
                    . $validated['last_name']
                ),


                /*
                |--------------------------------------------------------------------------
                | Contact Information
                |--------------------------------------------------------------------------
                */

                'mobile' =>
                    $validated['mobile'],


                /*
                |--------------------------------------------------------------------------
                | Default Country
                |--------------------------------------------------------------------------
                */

                'country' =>
                    'Bangladesh',


                /*
                |--------------------------------------------------------------------------
                | Application Status
                |--------------------------------------------------------------------------
                */

                'status' =>
                    'pending',

                'applied_at' =>
                    now(),


                /*
                |--------------------------------------------------------------------------
                | Profile Completion
                |--------------------------------------------------------------------------
                */

                'profile_completed' =>
                    false,


                /*
                |--------------------------------------------------------------------------
                | Reviewer Availability
                |--------------------------------------------------------------------------
                */

                'available_for_review' =>
                    false,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Commit Transaction
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Login Reviewer
            |--------------------------------------------------------------------------
            */

            Auth::guard('reviewer')->login(
                $reviewer
            );


            /*
            |--------------------------------------------------------------------------
            | Regenerate Session
            |--------------------------------------------------------------------------
            */

            $request->session()->regenerate();


            /*
            |--------------------------------------------------------------------------
            | Redirect to Application
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('reviewer.application')
                ->with(
                    'success',
                    'Reviewer registration submitted successfully. Please complete your reviewer application. Your application will then be sent to the BMRC Editorial Office for approval.'
                );


        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Rollback Transaction
            |--------------------------------------------------------------------------
            */

            DB::rollBack();


            /*
            |--------------------------------------------------------------------------
            | Return Error
            |--------------------------------------------------------------------------
            */

            return back()
                ->withInput()
                ->withErrors([
                    'register' =>
                        'Unable to submit reviewer application. '
                        . $e->getMessage(),
                ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('reviewer.auth.login');
    }


    public function login(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Login
        |--------------------------------------------------------------------------
        */

        $credentials = $request->validate([

            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Login Using Reviewer Guard
        |--------------------------------------------------------------------------
        */

        if (
            !Auth::guard('reviewer')->attempt(
                [
                    'email' =>
                        $credentials['email'],

                    'password' =>
                        $credentials['password'],
                ],
                $request->boolean('remember')
            )
        ) {

            return back()
                ->withErrors([
                    'email' =>
                        'The email or password is incorrect.',
                ])
                ->onlyInput('email');
        }


        /*
        |--------------------------------------------------------------------------
        | Regenerate Session
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();


        /*
        |--------------------------------------------------------------------------
        | Get Logged-in Reviewer
        |--------------------------------------------------------------------------
        */

        $reviewer =
            Auth::guard('reviewer')->user();


        /*
        |--------------------------------------------------------------------------
        | Check Reviewer Account Status
        |--------------------------------------------------------------------------
        */

        if (
            $reviewer->status === 'suspended'
        ) {

            Auth::guard('reviewer')->logout();

            return back()
                ->withErrors([
                    'email' =>
                        'Your reviewer account is currently suspended.',
                ])
                ->onlyInput('email');
        }


        /*
        |--------------------------------------------------------------------------
        | Get Reviewer Profile
        |--------------------------------------------------------------------------
        */

        $profile =
            $reviewer->profile;


        /*
        |--------------------------------------------------------------------------
        | Profile Not Found
        |--------------------------------------------------------------------------
        */

        if (!$profile) {

            Auth::guard('reviewer')->logout();

            return back()
                ->withErrors([
                    'email' =>
                        'Reviewer profile was not found.',
                ])
                ->onlyInput('email');
        }


        /*
        |--------------------------------------------------------------------------
        | PENDING
        |--------------------------------------------------------------------------
        */

        if (
            $profile->status === 'pending'
        ) {

            return redirect()
                ->route(
                    'reviewer.application'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | REJECTED
        |--------------------------------------------------------------------------
        */

        if (
            $profile->status === 'rejected'
        ) {

            return redirect()
                ->route(
                    'reviewer.application'
                )
                ->withErrors([
                    'application' =>
                        'Your reviewer application was rejected. Please review the rejection reason and update your application.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | SUSPENDED
        |--------------------------------------------------------------------------
        */

        if (
            $profile->status === 'suspended'
        ) {

            Auth::guard('reviewer')->logout();

            return back()
                ->withErrors([
                    'email' =>
                        'Your reviewer account is currently suspended.',
                ])
                ->onlyInput('email');
        }


        /*
        |--------------------------------------------------------------------------
        | APPROVED
        |--------------------------------------------------------------------------
        */

        if (
            $profile->status === 'approved'
        ) {

            return redirect()
                ->intended(
                    route('reviewer.dashboard')
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Fallback
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'reviewer.application'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Logout Reviewer Guard
        |--------------------------------------------------------------------------
        */

        Auth::guard('reviewer')->logout();


        /*
        |--------------------------------------------------------------------------
        | Invalidate Session
        |--------------------------------------------------------------------------
        */

        $request->session()->invalidate();


        /*
        |--------------------------------------------------------------------------
        | Regenerate CSRF Token
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerateToken();


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'reviewer.login'
            )
            ->with(
                'success',
                'You have been logged out successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | APPLICATION
    |--------------------------------------------------------------------------
    */

    public function application()
    {
        /*
        |--------------------------------------------------------------------------
        | Get Logged-in Reviewer
        |--------------------------------------------------------------------------
        */

        $reviewer =
            Auth::guard('reviewer')->user();


        /*
        |--------------------------------------------------------------------------
        | Safety Check
        |--------------------------------------------------------------------------
        */

        if (!$reviewer) {

            return redirect()
                ->route(
                    'reviewer.login'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Reviewer Profile
        |--------------------------------------------------------------------------
        */

        $profile =
            $reviewer->profile;


        /*
        |--------------------------------------------------------------------------
        | Profile Not Found
        |--------------------------------------------------------------------------
        */

        if (!$profile) {

            abort(
                404,
                'Reviewer profile not found.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Application View
        |--------------------------------------------------------------------------
        */

        return view(
            'reviewer.application.edit',
            compact('profile')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | Get Logged-in Reviewer
        |--------------------------------------------------------------------------
        */

        $reviewer =
            Auth::guard('reviewer')->user();


        /*
        |--------------------------------------------------------------------------
        | Safety Check
        |--------------------------------------------------------------------------
        */

        if (!$reviewer) {

            return redirect()
                ->route(
                    'reviewer.login'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Reviewer Profile
        |--------------------------------------------------------------------------
        */

        $profile =
            $reviewer->profile;


        /*
        |--------------------------------------------------------------------------
        | Profile Not Found
        |--------------------------------------------------------------------------
        */

        if (!$profile) {

            abort(
                404,
                'Reviewer profile not found.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Only Approved Reviewer
        |--------------------------------------------------------------------------
        */

        if (
            $profile->status !== 'approved'
        ) {

            return redirect()
                ->route(
                    'reviewer.application'
                )
                ->with(
                    'info',
                    'Your reviewer application has not yet been approved.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'reviewer.dashboard',
            compact('profile')
        );
    }
}
