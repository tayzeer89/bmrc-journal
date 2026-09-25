<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manuscript;
use App\Models\Reviewer;
use App\Models\ReviewerInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ReviewerInvitationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ALL INVITATIONS
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Query
        |--------------------------------------------------------------------------
        */

        $query = ReviewerInvitation::query()
            ->with([
                'reviewer.profile',
                'manuscript',
                'inviter',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                /*
                |--------------------------------------------------------------------------
                | Reviewer Search
                |--------------------------------------------------------------------------
                */

                $q->whereHas(
                    'reviewer',
                    function ($reviewerQuery) use ($search) {

                        $reviewerQuery
                            ->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'email',
                                'like',
                                "%{$search}%"
                            );

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Manuscript Search
                |--------------------------------------------------------------------------
                */

                $q->orWhereHas(
                    'manuscript',
                    function ($manuscriptQuery) use ($search) {

                        $manuscriptQuery
                            ->where(
                                'title',
                                'like',
                                "%{$search}%"
                            );

                        /*
                        |--------------------------------------------------------------------------
                        | manuscript_id
                        |--------------------------------------------------------------------------
                        */

                        if (
                            Schema::hasColumn(
                                'manuscripts',
                                'manuscript_id'
                            )
                        ) {

                            $manuscriptQuery->orWhere(
                                'manuscript_id',
                                'like',
                                "%{$search}%"
                            );

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Legacy manuscript_number
                        |--------------------------------------------------------------------------
                        */

                        if (
                            Schema::hasColumn(
                                'manuscripts',
                                'manuscript_number'
                            )
                        ) {

                            $manuscriptQuery->orWhere(
                                'manuscript_number',
                                'like',
                                "%{$search}%"
                            );

                        }

                    }
                );

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('status')
            &&
            in_array(
                $request->status,
                [
                    'pending',
                    'accepted',
                    'declined',
                    'expired',
                    'cancelled',
                ],
                true
            )
        ) {

            $query->where(
                'status',
                $request->status
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Manuscript Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('manuscript_id')) {

            $query->where(
                'manuscript_id',
                $request->manuscript_id
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Reviewer Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('reviewer_id')) {

            $query->where(
                'reviewer_id',
                $request->reviewer_id
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $statistics = [

            'total' =>
                ReviewerInvitation::count(),

            'pending' =>
                ReviewerInvitation::where(
                    'status',
                    'pending'
                )->count(),

            'accepted' =>
                ReviewerInvitation::where(
                    'status',
                    'accepted'
                )->count(),

            'declined' =>
                ReviewerInvitation::where(
                    'status',
                    'declined'
                )->count(),

            'expired' =>
                ReviewerInvitation::where(
                    'status',
                    'expired'
                )->count(),

            'cancelled' =>
                ReviewerInvitation::where(
                    'status',
                    'cancelled'
                )->count(),

        ];


        /*
        |--------------------------------------------------------------------------
        | Paginate
        |--------------------------------------------------------------------------
        */

        $invitations = $query
            ->latest('id')
            ->paginate(20)
            ->withQueryString();


        return view(
            'admin.reviewers.invitations.index',
            compact(
                'invitations',
                'statistics'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE INVITATION
    |--------------------------------------------------------------------------
    */

    public function create(Request $request)
    {
        $manuscript = null;


        /*
        |--------------------------------------------------------------------------
        | Pre-selected Manuscript
        |--------------------------------------------------------------------------
        */

        if ($request->filled('manuscript_id')) {

            $manuscript = Manuscript::find(
                $request->manuscript_id
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Eligible Reviewers
        |--------------------------------------------------------------------------
        */

        $reviewers = Reviewer::query()
            ->with('profile')
            ->where(
                'status',
                'approved'
            )
            ->whereHas(
                'profile',
                function ($query) {

                    $query
                        ->where(
                            'approval_status',
                            'approved'
                        )
                        ->where(
                            'profile_completed',
                            true
                        )
                        ->where(
                            'available_for_review',
                            true
                        )
                        ->where(
                            'receive_review_invitations',
                            true
                        );

                }
            )
            ->orderBy('name')
            ->get();


        return view(
            'admin.reviewers.invitations.create',
            compact(
                'manuscript',
                'reviewers'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE / SEND SINGLE INVITATION
    |--------------------------------------------------------------------------
    |
    | This method is mainly for the Admin invitation screen.
    |
    | Handling Editor reviewer selection uses:
    |
    | ReviewerSelectionController::invite()
    |
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'manuscript_id' => [
                'required',
                'integer',
                'exists:manuscripts,id',
            ],

            'reviewer_id' => [
                'required',
                'integer',
                'exists:reviewers,id',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Reviewer
        |--------------------------------------------------------------------------
        */

        $reviewer = Reviewer::query()
            ->with('profile')
            ->findOrFail(
                $validated['reviewer_id']
            );


        /*
        |--------------------------------------------------------------------------
        | Reviewer Eligibility
        |--------------------------------------------------------------------------
        */

        if (
            method_exists(
                $reviewer,
                'canReceiveReviewInvitations'
            )
        ) {

            if (
                !$reviewer
                    ->canReceiveReviewInvitations()
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'The selected reviewer is not currently eligible to receive review invitations.'
                    );

            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | Fallback Eligibility Check
            |--------------------------------------------------------------------------
            */

            if (
                $reviewer->status !== 'approved'
                ||
                !$reviewer->profile
                ||
                $reviewer->profile
                    ->approval_status !== 'approved'
                ||
                !$reviewer->profile
                    ->profile_completed
                ||
                !$reviewer->profile
                    ->available_for_review
                ||
                !$reviewer->profile
                    ->receive_review_invitations
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'The selected reviewer is not currently eligible to receive review invitations.'
                    );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Active Invitation
        |--------------------------------------------------------------------------
        */

        $duplicate = ReviewerInvitation::query()
            ->where(
                'manuscript_id',
                $validated['manuscript_id']
            )
            ->where(
                'reviewer_id',
                $validated['reviewer_id']
            )
            ->whereIn(
                'status',
                [
                    'pending',
                    'accepted',
                ]
            )
            ->exists();


        if ($duplicate) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'This reviewer already has an active invitation for this manuscript.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Invitation Dates
        |--------------------------------------------------------------------------
        |
        | Day 0  = invitation
        | Day 3  = invitation expiry
        | Day 15 = review deadline
        |
        | ReviewerInvitation currently stores expires_at.
        | Review due date should later be stored in ReviewerAssignment.
        |
        */

        $invitedAt = now();

        $expiresAt = $invitedAt
            ->copy()
            ->addDays(3);

        $reviewDueAt = $invitedAt
            ->copy()
            ->addDays(15);


        /*
        |--------------------------------------------------------------------------
        | Create Invitation
        |--------------------------------------------------------------------------
        */

        $invitation = DB::transaction(
            function () use (
                $validated,
                $reviewer,
                $invitedAt,
                $expiresAt
            ) {

                /*
                |--------------------------------------------------------------------------
                | Lock Manuscript
                |--------------------------------------------------------------------------
                */

                $manuscript = Manuscript::query()
                    ->lockForUpdate()
                    ->findOrFail(
                        $validated['manuscript_id']
                    );


                /*
                |--------------------------------------------------------------------------
                | Recheck Duplicate Inside Transaction
                |--------------------------------------------------------------------------
                */

                $duplicate = ReviewerInvitation::query()
                    ->where(
                        'manuscript_id',
                        $manuscript->id
                    )
                    ->where(
                        'reviewer_id',
                        $reviewer->id
                    )
                    ->whereIn(
                        'status',
                        [
                            'pending',
                            'accepted',
                        ]
                    )
                    ->exists();


                if ($duplicate) {

                    return null;

                }


                /*
                |--------------------------------------------------------------------------
                | Invitation
                |--------------------------------------------------------------------------
                */

                $invitation =
                    ReviewerInvitation::create([

                        'manuscript_id' =>
                            $manuscript->id,

                        'reviewer_id' =>
                            $reviewer->id,

                        'invited_by' =>
                            auth()->id(),

                        'invitation_token' =>
                            Str::random(64),

                        'status' =>
                            'pending',

                        'invited_at' =>
                            $invitedAt,

                        'expires_at' =>
                            $expiresAt,

                        'responded_at' =>
                            null,

                        'response_note' =>
                            null,

                        'reminder_count' =>
                            0,

                        'last_reminder_at' =>
                            null,

                    ]);


                /*
                |--------------------------------------------------------------------------
                | Update Manuscript Workflow
                |--------------------------------------------------------------------------
                */

                if (
                    in_array(
                        $manuscript->status,
                        [
                            'reviewer_selection',
                            'reviewer_invitation',
                        ],
                        true
                    )
                ) {

                    $manuscript->update([

                        'status' =>
                            'reviewer_invitation',

                        'current_stage' =>
                            'reviewer_invitation',

                    ]);

                }


                /*
                |--------------------------------------------------------------------------
                | Email Notification
                |--------------------------------------------------------------------------
                |
                | Add when your ReviewerInvitationMail is ready:
                |
                | Mail::to($reviewer->email)->send(
                |     new ReviewerInvitationMail($invitation)
                | );
                |
                */

                return $invitation;

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Duplicate Created Concurrently
        |--------------------------------------------------------------------------
        */

        if (!$invitation) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'This reviewer already has an active invitation for this manuscript.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.reviewers.invitations.index'
            )
            ->with(
                'success',
                'Reviewer invitation sent successfully. ' .
                'Invitation expires on ' .
                $expiresAt->format('d M Y') .
                '. Review deadline is ' .
                $reviewDueAt->format('d M Y') .
                ' (15 days from the initial invitation).'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW INVITATION
    |--------------------------------------------------------------------------
    */

    public function show($reviewerInvitation)
    {
        $reviewerInvitation = ReviewerInvitation::query()
            ->with([
                'reviewer.profile',
                'manuscript.journal',
                'manuscript.articleType',
                'inviter',
            ])
            ->findOrFail($reviewerInvitation);

        return view(
            'admin.reviewers.invitations.show',
            compact('reviewerInvitation')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SEND REMINDER
    |--------------------------------------------------------------------------
    */

        public function remind($reviewerInvitation)
        {
            $reviewerInvitation = ReviewerInvitation::query()
                ->with('reviewer')
                ->findOrFail($reviewerInvitation);

            if ($reviewerInvitation->status !== 'pending') {
                return back()->with(
                    'error',
                    'Reminder can only be sent for pending invitations.'
                );
            }

            if (
                $reviewerInvitation->expires_at &&
                $reviewerInvitation->expires_at->isPast()
            ) {
                $reviewerInvitation->update([
                    'status' => 'expired',
                ]);

                return back()->with(
                    'error',
                    'This reviewer invitation has already expired.'
                );
            }

            $reviewerInvitation->update([
                'last_reminder_at' => now(),

                'reminder_count' =>
                    ($reviewerInvitation->reminder_count ?? 0) + 1,
            ]);

            /*
            * Later, when your mail class is ready:
            *
            * Mail::to(
            *     $reviewerInvitation->reviewer->email
            * )->send(
            *     new ReviewerInvitationReminderMail(
            *         $reviewerInvitation
            *     )
            * );
            */

            return redirect()
                ->route(
                    'admin.reviewers.invitations.show',
                    [
                        'reviewerInvitation' =>
                            $reviewerInvitation->id
                    ]
                )
                ->with(
                    'success',
                    'Reviewer invitation reminder recorded successfully.'
                );
        }


    /*
    |--------------------------------------------------------------------------
    | CANCEL INVITATION
    |--------------------------------------------------------------------------
    */

    public function cancel(
    Request $request,
    $reviewerInvitation
    ) {
        $reviewerInvitation =
            ReviewerInvitation::findOrFail(
                $reviewerInvitation
            );

        if ($reviewerInvitation->status !== 'pending') {
            return back()->with(
                'error',
                'Only pending invitations can be cancelled.'
            );
        }

        $validated = $request->validate([
            'cancellation_reason' => [
                'nullable',
                'string',
                'max:3000',
            ],
        ]);

        $reviewerInvitation->update([
            'status' => 'cancelled',

            'response_note' =>
                $validated['cancellation_reason']
                ?? 'Invitation cancelled by editorial office.',
        ]);

        return redirect()
            ->route(
                'admin.reviewers.invitations.show',
                [
                    'reviewerInvitation' =>
                        $reviewerInvitation->id
                ]
            )
            ->with(
                'success',
                'Reviewer invitation cancelled successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | MARK EXPIRED
    |--------------------------------------------------------------------------
    */

    public function expire($reviewerInvitation)
    {
        $reviewerInvitation =
            ReviewerInvitation::findOrFail(
                $reviewerInvitation
            );

        if ($reviewerInvitation->status !== 'pending') {
            return back()->with(
                'error',
                'Only pending invitations can be marked as expired.'
            );
        }

        $reviewerInvitation->update([
            'status' => 'expired',
        ]);

        return redirect()
            ->route(
                'admin.reviewers.invitations.show',
                [
                    'reviewerInvitation' =>
                        $reviewerInvitation->id
                ]
            )
            ->with(
                'success',
                'Reviewer invitation marked as expired.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PENDING INVITATIONS
    |--------------------------------------------------------------------------
    */

    public function pending()
    {
        return $this->statusList(
            'pending',
            'admin.reviewer-invitations.pending'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACCEPTED INVITATIONS
    |--------------------------------------------------------------------------
    */

    public function accepted()
    {
        return $this->statusList(
            'accepted',
            'admin.reviewer-invitations.accepted'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DECLINED INVITATIONS
    |--------------------------------------------------------------------------
    */

    public function declined()
    {
        return $this->statusList(
            'declined',
            'admin.reviewer-invitations.declined'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EXPIRED INVITATIONS
    |--------------------------------------------------------------------------
    */

    public function expired()
    {
        return $this->statusList(
            'expired',
            'admin.reviewer-invitations.expired'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CANCELLED INVITATIONS
    |--------------------------------------------------------------------------
    */

    public function cancelled()
    {
        return $this->statusList(
            'cancelled',
            'admin.reviewer-invitations.cancelled'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PRIVATE STATUS LIST
    |--------------------------------------------------------------------------
    */

    private function statusList(
        string $status,
        string $view
    ) {
        /*
        |--------------------------------------------------------------------------
        | FIX:
        |
        | Relationship is inviter(), NOT invitedBy()
        |--------------------------------------------------------------------------
        */

        $invitations =
            ReviewerInvitation::query()
                ->with([
                    'reviewer.profile',
                    'manuscript',
                    'inviter',
                ])
                ->where(
                    'status',
                    $status
                )
                ->latest('id')
                ->paginate(20)
                ->withQueryString();


        return view(
            $view,
            compact(
                'invitations'
            )
        );
    }
}