<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    /**
     * Redirect authenticated users
     * to their appropriate dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        /*
        |--------------------------------------------------------------------------
        | Get and normalize all assigned roles
        |--------------------------------------------------------------------------
        |
        | Examples:
        |
        | Finance Officer   => finance_officer
        | finance-officer   => finance_officer
        | finance_officer   => finance_officer
        | FINANCE OFFICER   => finance_officer
        |
        */

        $roles = $user->getRoleNames()
            ->map(function ($role) {

                return strtolower(
                    str_replace(
                        [' ', '-'],
                        '_',
                        trim($role)
                    )
                );

            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | FINANCE / ACCOUNTS
        |--------------------------------------------------------------------------
        */

        if (
            $roles->contains('finance_officer')
            ||
            $roles->contains('accounts_officer')
            ||
            $roles->contains('finance')
            ||
            $roles->contains('accounts')
            ||
            $user->can('payment.verify')
            ||
            $user->can('payment.view')
        ) {

            return redirect()->route('finance.dashboard');

        }


        /*
        |--------------------------------------------------------------------------
        | EDITOR-IN-CHIEF
        |--------------------------------------------------------------------------
        */

        if (
            $roles->contains('editor_in_chief')
            ||
            $roles->contains('editorial_chief')
        ) {

            return redirect()->route('editor.dashboard');

        }


        /*
        |--------------------------------------------------------------------------
        | HANDLING / ASSOCIATE EDITOR
        |--------------------------------------------------------------------------
        */

        if (
            $roles->contains('handling_editor')
            ||
            $roles->contains('associate_editor')
            ||
            $roles->contains('editor')
        ) {

            return redirect()->route('editor.dashboard');

        }


        /*
        |--------------------------------------------------------------------------
        | EDITORIAL / JOURNAL OFFICER
        |--------------------------------------------------------------------------
        */

        if (
            $roles->contains('editorial_officer')
            ||
            $roles->contains('journal_officer')
        ) {

            if (\Route::has('editorial.dashboard')) {
                return redirect()->route('editorial.dashboard');
            }
        }


        /*
        |--------------------------------------------------------------------------
        | PRODUCTION STAFF
        |--------------------------------------------------------------------------
        */

        if (
            $roles->contains('production_staff')
            ||
            $roles->contains('production_officer')
        ) {

            if (\Route::has('production.dashboard')) {
                return redirect()->route('production.dashboard');
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SYSTEM ADMINISTRATOR
        |--------------------------------------------------------------------------
        */

        if (
            $roles->contains('system_administrator')
            ||
            $roles->contains('administrator')
            ||
            $roles->contains('admin')
        ) {

            return redirect()->route('admin.dashboard');

        }


        /*
        |--------------------------------------------------------------------------
        | No matching dashboard
        |--------------------------------------------------------------------------
        |
        | Development message showing actual role names.
        | This helps identify role configuration problems.
        |
        */

        abort(
            403,
            'No dashboard is assigned to this account role. Current role(s): '
            . $user->getRoleNames()->implode(', ')
        );
    }
}