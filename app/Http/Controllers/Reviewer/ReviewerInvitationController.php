<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\ReviewerInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewerInvitationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Invitation List
    |--------------------------------------------------------------------------
    |
    | Shows all invitations belonging to the currently logged-in reviewer.
    |
    */

    public function index()
    {
        $reviewer = Auth::guard('reviewer')
            ->user();

        if (!$reviewer) {
            return redirect()
                ->route('reviewer.login');
        }

        $reviewer->load('profile');

        /*
        |--------------------------------------------------------------------------
        | Automatically Mark Expired Invitations
        |--------------------------------------------------------------------------
        */

        ReviewerInvitation::query()
            ->where('reviewer_id', $reviewer->id)
            ->where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update([
                'status' => 'expired',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Load Reviewer's Invitations
        |--------------------------------------------------------------------------
        */

        $invitations = ReviewerInvitation::query()
            ->with([
                'manuscript.journal',
                'manuscript.articleType',
                'inviter',
            ])
            ->where(
                'reviewer_id',
                $reviewer->id
            )
            ->orderByDesc('invited_at')
            ->paginate(20);

        return view(
            'reviewer.invitations.index',
            compact(
                'reviewer',
                'invitations'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Open Invitation From Email Token
    |--------------------------------------------------------------------------
    |
    | Example:
    |
    | /reviewer/invitation/abc123....
    |
    | This route is public because the reviewer may not yet be logged in.
    |
    | IMPORTANT:
    | The token identifies an invitation.
    | It does NOT authenticate the reviewer.
    |
    */

    public function open(
        Request $request,
        string $token
    ) {
        /*
        |--------------------------------------------------------------------------
        | Find Invitation
        |--------------------------------------------------------------------------
        */

        $invitation = ReviewerInvitation::query()
            ->with([
                'reviewer',
                'manuscript',
            ])
            ->where(
                'invitation_token',
                $token
            )
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Invalid Token
        |--------------------------------------------------------------------------
        */

        if (!$invitation) {
            return redirect()
                ->route('reviewer.login')
                ->with(
                    'error',
                    'The review invitation link is invalid.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Cancelled Invitation
        |--------------------------------------------------------------------------
        */

        if ($invitation->isCancelled()) {
            return redirect()
                ->route('reviewer.login')
                ->with(
                    'error',
                    'This review invitation has been cancelled.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Check Invitation Expiry
        |--------------------------------------------------------------------------
        */

        $invitation->markExpiredIfNecessary();

        if ($invitation->isExpired()) {
            return redirect()
                ->route('reviewer.login')
                ->with(
                    'error',
                    'This review invitation has expired.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Check Reviewer Authentication
        |--------------------------------------------------------------------------
        */

        $reviewer = Auth::guard('reviewer')
            ->user();

        /*
        |--------------------------------------------------------------------------
        | Reviewer Not Logged In
        |--------------------------------------------------------------------------
        |
        | Save the invitation token in session.
        |
        | After login we will retrieve this token and return the reviewer
        | to the exact invitation.
        |
        */

        if (!$reviewer) {
            session([
                'reviewer_invitation_token'
                    => $invitation->invitation_token,
            ]);

            return redirect()
                ->route('reviewer.login')
                ->with(
                    'info',
                    'Please log in to view your review invitation.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        |
        | The invitation must belong to the currently authenticated reviewer.
        |
        */

        if (
            !$invitation->belongsToReviewer(
                $reviewer
            )
        ) {
            abort(
                403,
                'This invitation does not belong to your reviewer account.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Redirect to Invitation Details
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'reviewer.invitations.show',
                $invitation->id
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Invitation
    |--------------------------------------------------------------------------
    */

    public function show(
        ReviewerInvitation $invitation
    ) {
        $reviewer = Auth::guard('reviewer')
            ->user();

        if (!$reviewer) {
            return redirect()
                ->route('reviewer.login');
        }

        /*
        |--------------------------------------------------------------------------
        | Ownership Check
        |--------------------------------------------------------------------------
        */

        if (
            !$invitation->belongsToReviewer(
                $reviewer
            )
        ) {
            abort(
                403,
                'You are not authorized to view this invitation.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Mark Expired If Necessary
        |--------------------------------------------------------------------------
        */

        $invitation->markExpiredIfNecessary();

        /*
        |--------------------------------------------------------------------------
        | Load Relationships
        |--------------------------------------------------------------------------
        */

        $invitation->load([
            'reviewer.profile',
            'manuscript.journal',
            'manuscript.articleType',
            'inviter',
        ]);

        return view(
            'reviewer.invitations.show',
            compact(
                'reviewer',
                'invitation'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Accept Invitation
    |--------------------------------------------------------------------------
    */

    public function accept(
        ReviewerInvitation $invitation
    ) {
        $reviewer = Auth::guard('reviewer')
            ->user();

        if (!$reviewer) {
            return redirect()
                ->route('reviewer.login');
        }

        /*
        |--------------------------------------------------------------------------
        | Ownership Check
        |--------------------------------------------------------------------------
        */

        if (
            !$invitation->belongsToReviewer(
                $reviewer
            )
        ) {
            abort(
                403,
                'You are not authorized to respond to this invitation.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Check Expiry
        |--------------------------------------------------------------------------
        */

        $invitation->markExpiredIfNecessary();

        if ($invitation->isExpired()) {
            return redirect()
                ->route(
                    'reviewer.invitations.show',
                    $invitation->id
                )
                ->with(
                    'error',
                    'This review invitation has expired and can no longer be accepted.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Already Accepted
        |--------------------------------------------------------------------------
        */

        if ($invitation->isAccepted()) {
            return redirect()
                ->route(
                    'reviewer.invitations.show',
                    $invitation->id
                )
                ->with(
                    'info',
                    'You have already accepted this review invitation.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Already Declined
        |--------------------------------------------------------------------------
        */

        if ($invitation->isDeclined()) {
            return redirect()
                ->route(
                    'reviewer.invitations.show',
                    $invitation->id
                )
                ->with(
                    'error',
                    'You have already declined this review invitation.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Cancelled
        |--------------------------------------------------------------------------
        */

        if ($invitation->isCancelled()) {
            return redirect()
                ->route(
                    'reviewer.invitations.index'
                )
                ->with(
                    'error',
                    'This review invitation has been cancelled.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Accept
        |--------------------------------------------------------------------------
        */

        $accepted = $invitation->accept();

        if (!$accepted) {
            return redirect()
                ->route(
                    'reviewer.invitations.show',
                    $invitation->id
                )
                ->with(
                    'error',
                    'This invitation cannot be accepted.'
                );
        }

        return redirect()
            ->route(
                'reviewer.invitations.show',
                $invitation->id
            )
            ->with(
                'success',
                'Review invitation accepted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Decline Invitation
    |--------------------------------------------------------------------------
    */

    public function decline(
        Request $request,
        ReviewerInvitation $invitation
    ) {
        $reviewer = Auth::guard('reviewer')
            ->user();

        if (!$reviewer) {
            return redirect()
                ->route('reviewer.login');
        }

        /*
        |--------------------------------------------------------------------------
        | Ownership Check
        |--------------------------------------------------------------------------
        */

        if (
            !$invitation->belongsToReviewer(
                $reviewer
            )
        ) {
            abort(
                403,
                'You are not authorized to respond to this invitation.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Decline Note
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'response_note' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Check Expiry
        |--------------------------------------------------------------------------
        */

        $invitation->markExpiredIfNecessary();

        if ($invitation->isExpired()) {
            return redirect()
                ->route(
                    'reviewer.invitations.show',
                    $invitation->id
                )
                ->with(
                    'error',
                    'This review invitation has expired and can no longer be declined.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Already Accepted
        |--------------------------------------------------------------------------
        */

        if ($invitation->isAccepted()) {
            return redirect()
                ->route(
                    'reviewer.invitations.show',
                    $invitation->id
                )
                ->with(
                    'error',
                    'You have already accepted this review invitation.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Already Declined
        |--------------------------------------------------------------------------
        */

        if ($invitation->isDeclined()) {
            return redirect()
                ->route(
                    'reviewer.invitations.show',
                    $invitation->id
                )
                ->with(
                    'info',
                    'You have already declined this review invitation.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Cancelled
        |--------------------------------------------------------------------------
        */

        if ($invitation->isCancelled()) {
            return redirect()
                ->route(
                    'reviewer.invitations.index'
                )
                ->with(
                    'error',
                    'This review invitation has been cancelled.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Decline
        |--------------------------------------------------------------------------
        */

        $declined = $invitation->decline(
            $validated['response_note']
                ?? null
        );

        if (!$declined) {
            return redirect()
                ->route(
                    'reviewer.invitations.show',
                    $invitation->id
                )
                ->with(
                    'error',
                    'This invitation cannot be declined.'
                );
        }

        return redirect()
            ->route(
                'reviewer.invitations.index'
            )
            ->with(
                'success',
                'Review invitation declined successfully.'
            );
    }
}