<?php

namespace App\Http\Controllers\Author\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorLoginController extends Controller
{
    /**
     * Show Author Login Form
     */
    public function showLoginForm()
    {
        return view('author.auth.login');
    }

    /**
     * Handle Author Login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        /*
        |--------------------------------------------------------------------------
        | Attempt Login
        |--------------------------------------------------------------------------
        */

        if (Auth::attempt($credentials, $remember)) {

            $request->session()->regenerate();

            $user = Auth::user();

            /*
            |--------------------------------------------------------------------------
            | Check User Type
            |--------------------------------------------------------------------------
            */

            if ($user->user_type !== 'external') {

                Auth::logout();

                return back()
                    ->withErrors([
                        'email' => 'This account is not registered as an external user.',
                    ])
                    ->onlyInput('email');
            }

            /*
            |--------------------------------------------------------------------------
            | Check Author Role
            |--------------------------------------------------------------------------
            */

            if (!$user->hasRole('author')) {

                Auth::logout();

                return back()
                    ->withErrors([
                        'email' => 'This account does not have Author access.',
                    ])
                    ->onlyInput('email');
            }

            /*
            |--------------------------------------------------------------------------
            | Login Successful
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->intended(route('author.dashboard'))
                ->with('success', 'Welcome back to BMRC Journal.');
        }

        /*
        |--------------------------------------------------------------------------
        | Login Failed
        |--------------------------------------------------------------------------
        */

        return back()
            ->withErrors([
                'email' => 'The email or password is incorrect.',
            ])
            ->onlyInput('email');
    }

    /**
     * Logout Author
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('author.login')
            ->with('success', 'You have been logged out successfully.');
    }
}