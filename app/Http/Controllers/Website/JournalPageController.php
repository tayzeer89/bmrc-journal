<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\JournalPage;

class JournalPageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Display Published Journal Page
    |--------------------------------------------------------------------------
    */

    public function show(string $slug)
    {
        $page = JournalPage::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view(
            'website.pages.show',
            compact('page')
        );
    }
}