<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Visible Editor Dashboard Modules
        |--------------------------------------------------------------------------
        */

        $visibleModules = collect([

            [
                'key' => 'assigned_manuscripts',
                'title' => 'Assigned Manuscripts',
                'description' => 'View manuscripts assigned to you.',
                'icon' => 'bi-file-earmark-text',
                'route' => null,
                'count' => 0,
            ],

            [
                'key' => 'editorial_assessment',
                'title' => 'Editorial Assessment',
                'description' => 'Assess manuscript scope and scientific quality.',
                'icon' => 'bi-clipboard-check',
                'route' => null,
                'count' => 0,
            ],

            [
                'key' => 'reviewer_management',
                'title' => 'Reviewer Management',
                'description' => 'Select and manage manuscript reviewers.',
                'icon' => 'bi-people',
                'route' => null,
                'count' => 0,
            ],

            [
                'key' => 'peer_review',
                'title' => 'Peer Review',
                'description' => 'Monitor ongoing peer-review activities.',
                'icon' => 'bi-journal-check',
                'route' => null,
                'count' => 0,
            ],

            [
                'key' => 'editorial_recommendation',
                'title' => 'Editorial Recommendation',
                'description' => 'Prepare editorial recommendations.',
                'icon' => 'bi-check2-square',
                'route' => null,
                'count' => 0,
            ],

        ]);

        return view(
            'editor.dashboard',
            compact('visibleModules')
        );
    }
}