<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ReviewerPasswordController extends Controller
{
    public function edit()
    {
        $reviewer = Auth::guard('reviewer')->user();

        if (!$reviewer) {
            return redirect()
                ->route('reviewer.login');
        }

        /*
        |--------------------------------------------------------------------------
        | If password change is not required, send reviewer to dashboard
        |--------------------------------------------------------------------------
        */

        if (!$reviewer->mustChangePassword()) {
            return redirect()
                ->route('reviewer.dashboard');
        }

        return view(
            'reviewer.auth.change-password',
            compact('reviewer')
        );
    }


    public function update(Request $request)
    {
        $reviewer = Auth::guard('reviewer')->user();

        if (!$reviewer) {
            return redirect()
                ->route('reviewer.login');
        }

        $validated = $request->validate([

            'current_password' => [
                'required',
                'string',
            ],

            'password' => [
                'required',
                'confirmed',

                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers(),
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Current Password
        |--------------------------------------------------------------------------
        */

        if (
            !Hash::check(
                $validated['current_password'],
                $reviewer->password
            )
        ) {

            return back()
                ->withErrors([
                    'current_password' =>
                        'The current password is incorrect.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Reusing Same Password
        |--------------------------------------------------------------------------
        */

        if (
            Hash::check(
                $validated['password'],
                $reviewer->password
            )
        ) {

            return back()
                ->withErrors([
                    'password' =>
                        'The new password must be different from your current password.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Update Password
        |--------------------------------------------------------------------------
        |
        | Reviewer model already contains:
        |
        | 'password' => 'hashed'
        |
        | Therefore Hash::make() is not required here.
        |--------------------------------------------------------------------------
        */

        $reviewer->update([

            'password' =>
                $validated['password'],

            'must_change_password' =>
                false,

            'password_changed_at' =>
                now(),

        ]);


        return redirect()
            ->route('reviewer.dashboard')
            ->with(
                'success',
                'Your password has been changed successfully.'
            );
    }
}