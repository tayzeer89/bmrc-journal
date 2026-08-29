<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InternalDashboardController extends Controller
{
    /**
     * Show dashboard based on the user's role.
     */
    public function index()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | System Administrator
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('system_administrator')) {
            return view('admin.dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | Editorial Officer / Journal Officer
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('editorial_officer')) {
            return view('editorial.dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | Editor-in-Chief
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('editor_in_chief')) {
            return view('editor.dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | Associate / Handling Editor
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('associate_editor')) {
            return view('editor.dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | Assistant Editor
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('Assistant-Editor')) {
            return view('assistant.dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | Accounts / Finance Officer
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('accounts_officer')) {
            return view('finance.dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | Copy Editor
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('copy_editor')) {
            return view('copyediting.dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | Proofreader
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('proofreader')) {
            return view('proofreading.dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | Production / Web Administrator
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('production_web_admin')) {
            return view('production.dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | Journal Manager
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('journal_manager')) {
            return view('journal.dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | No Role
        |--------------------------------------------------------------------------
        */

        abort(403, 'No dashboard has been assigned to your account.');
    }
}