<?php

namespace App\Http\Controllers\Editorial;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        abort_unless(
            auth()->user()->can('dashboard.view'),
            403,
            'You do not have permission to view the dashboard.'
        );

        return view('editorial.dashboard');
    }
}