<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\PeerReview;
use Illuminate\Support\Facades\Auth;

class ReviewerReviewController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Active Reviews
    |--------------------------------------------------------------------------
    |
    | Shows reviews that the logged-in reviewer has started but has not yet
    | finally submitted.
    |
    */

    public function active()
    {
        /*
        |--------------------------------------------------------------------------
        | Authenticated Reviewer
        |--------------------------------------------------------------------------
        */

        $reviewer = Auth::guard('reviewer')
            ->user();


        if (!$reviewer) {

            return redirect()
                ->route(
                    'reviewer.login'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Active Peer Reviews
        |--------------------------------------------------------------------------
        |
        | draft
        | in_progress
        |
        */

        $reviews = PeerReview::query()

            ->with([
                'invitation.manuscript.articleType',
                'invitation.manuscript.journal',
            ])

            ->where(
                'reviewer_id',
                $reviewer->id
            )

            ->whereIn(
                'status',
                [
                    'draft',
                    'in_progress',
                ]
            )

            ->orderByDesc(
                'updated_at'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | Return Active Reviews
        |--------------------------------------------------------------------------
        */

        return view(
            'reviewer.reviews.active',
            compact(
                'reviewer',
                'reviews'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Completed Reviews
    |--------------------------------------------------------------------------
    |
    | Shows peer reviews that have been finally submitted.
    |
    */

    public function completed()
    {
        /*
        |--------------------------------------------------------------------------
        | Authenticated Reviewer
        |--------------------------------------------------------------------------
        */

        $reviewer = Auth::guard('reviewer')
            ->user();


        if (!$reviewer) {

            return redirect()
                ->route(
                    'reviewer.login'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Submitted Peer Reviews
        |--------------------------------------------------------------------------
        */

        $reviews = PeerReview::query()

            ->with([
                'invitation.manuscript.articleType',
                'invitation.manuscript.journal',
            ])

            ->where(
                'reviewer_id',
                $reviewer->id
            )

            ->where(
                'status',
                'submitted'
            )

            ->orderByDesc(
                'submitted_at'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | Return Completed Reviews
        |--------------------------------------------------------------------------
        */

        return view(
            'reviewer.reviews.completed',
            compact(
                'reviewer',
                'reviews'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Review History
    |--------------------------------------------------------------------------
    |
    | Shows the complete review history of the logged-in reviewer:
    |
    | - Draft
    | - In Progress
    | - Submitted
    |
    */

    public function history()
    {
        /*
        |--------------------------------------------------------------------------
        | Authenticated Reviewer
        |--------------------------------------------------------------------------
        */

        $reviewer = Auth::guard('reviewer')
            ->user();


        if (!$reviewer) {

            return redirect()
                ->route(
                    'reviewer.login'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Complete Peer Review History
        |--------------------------------------------------------------------------
        */

        $reviews = PeerReview::query()

            ->with([
                'invitation.manuscript.articleType',
                'invitation.manuscript.journal',
            ])

            ->where(
                'reviewer_id',
                $reviewer->id
            )

            ->orderByDesc(
                'updated_at'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | Return Review History
        |--------------------------------------------------------------------------
        */

        return view(
            'reviewer.reviews.history',
            compact(
                'reviewer',
                'reviews'
            )
        );
    }
}