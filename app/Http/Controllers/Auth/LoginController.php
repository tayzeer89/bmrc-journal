<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show login page
     */
    public function showLogin()
    {
        return view('auth.login');
    }


    /**
     * Process login
     */
    public function login(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Validate
    |--------------------------------------------------------------------------
    */

    $credentials = $request->validate([
        'email' => [
            'required',
            'email',
        ],

        'password' => [
            'required',
            'string',
        ],
    ]);


    /*
    |--------------------------------------------------------------------------
    | Attempt Login
    |--------------------------------------------------------------------------
    */

    if (!Auth::guard('web')->attempt(
        [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ],
        $request->boolean('remember')
    )) {

        return back()
            ->withErrors([
                'email' => 'The email or password is incorrect.',
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
    | Get User
    |--------------------------------------------------------------------------
    */

    $user = Auth::guard('web')->user();


    /*
    |--------------------------------------------------------------------------
    | Check Active Account
    |--------------------------------------------------------------------------
    */

    if (
        isset($user->is_active) &&
        !$user->is_active
    ) {

        Auth::guard('web')->logout();

        return back()
            ->withErrors([
                'email' => 'Your account is currently inactive.',
            ])
            ->onlyInput('email');
    }


    /*
    |--------------------------------------------------------------------------
    | Redirect According to Role
    |--------------------------------------------------------------------------
    */

    if ($user->hasRole('system_administrator')) {

        return redirect()->route('admin.dashboard');
    }


    if ($user->hasRole('editorial_officer')) {

        return redirect()->route('editorial.dashboard');
    }


    if ($user->hasRole('editor_in_chief')) {

        return redirect()->route('editor.dashboard');
    }


    if ($user->hasRole('associate_editor')) {

        return redirect()->route('editor.dashboard');
    }


    if ($user->hasRole('Assistant-Editor')) {

        return redirect()->route('assistant.dashboard');
    }


    if ($user->hasRole('accounts_officer')) {

        return redirect()->route('finance.dashboard');
    }


    if ($user->hasRole('copy_editor')) {

        return redirect()->route('copyediting.dashboard');
    }


    if ($user->hasRole('proofreader')) {

        return redirect()->route('proofreading.dashboard');
    }


    if ($user->hasRole('production_web_admin')) {

        return redirect()->route('production.dashboard');
    }


    if ($user->hasRole('journal_manager')) {

        return redirect()->route('journal.dashboard');
    }


    /*
    |--------------------------------------------------------------------------
    | No Role
    |--------------------------------------------------------------------------
    */

    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return back()
        ->withErrors([
            'email' =>
                'Your account does not have an assigned role. Please contact the System Administrator.',
        ])
        ->onlyInput('email');
}


    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'You have been logged out successfully.'
            );
    }
}















