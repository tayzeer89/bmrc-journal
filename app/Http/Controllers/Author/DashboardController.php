<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Manuscript;
use App\Models\AuthorProfile;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Author Profile
        |--------------------------------------------------------------------------
        */

        $authorProfile = AuthorProfile::where('user_id', $user->id)
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Manuscripts
        |--------------------------------------------------------------------------
        */

        $manuscripts = Manuscript::where('submitted_by', $user->id)
            ->with([
                'articleType',
                'payments',
                'versions',
            ])
            ->latest()
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $statistics = [

            'total' => $manuscripts->count(),

            'drafts' => $manuscripts
                ->where('status', 'draft')
                ->count(),

            'submitted' => $manuscripts
                ->where('status', 'submitted')
                ->count(),

            'under_review' => $manuscripts
                ->where('status', 'under_review')
                ->count(),

            'accepted' => $manuscripts
                ->where('status', 'accepted')
                ->count(),

            'published' => $manuscripts
                ->where('status', 'published')
                ->count(),

        ];


        /*
        |--------------------------------------------------------------------------
        | Profile Completion
        |--------------------------------------------------------------------------
        | The author_profiles table already contains the
        | profile_completed field.
        |
        | Therefore, we use that value directly instead of
        | recalculating the percentage from individual fields.
        |--------------------------------------------------------------------------
        */

        $profileCompletion = (int) (
            $authorProfile->profile_completed ?? 0
        );


        /*
        |--------------------------------------------------------------------------
        | Make Sure Percentage Is Between 0 and 100
        |--------------------------------------------------------------------------
        */

        $profileCompletion = max(
            0,
            min(100, $profileCompletion)
        );


        /*
        |--------------------------------------------------------------------------
        | Dashboard View
        |--------------------------------------------------------------------------
        */

        return view(
            'author.dashboard',
            compact(
                'authorProfile',
                'manuscripts',
                'statistics',
                'profileCompletion'
            )
        );
    }
}
