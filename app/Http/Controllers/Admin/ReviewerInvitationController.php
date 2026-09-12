<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reviewer;
use App\Models\ReviewerInvitation;
use App\Models\Manuscript;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class ReviewerInvitationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ALL INVITATIONS
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = ReviewerInvitation::query()
            ->with([
                'reviewer.profile',
                'manuscript',
                'invitedBy',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

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
                )
                ->orWhereHas(
                    'manuscript',
                    function ($manuscriptQuery) use ($search) {

                        $manuscriptQuery
                            ->where(
                                'title',
                                'like',
                                "%{$search}%"
                            );

                        /*
                        | Adjust manuscript_number to your actual column.
                        */

                        if (
                            \Schema::hasColumn(
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

        $invitations = $query
            ->latest()
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

        if ($request->filled('manuscript_id')) {

            $manuscript =
                Manuscript::find(
                    $request->manuscript_id
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Eligible Reviewers Only
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
    | STORE / SEND INVITATION
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'manuscript_id' => [
                'required',
                'exists:manuscripts,id',
            ],

            'reviewer_id' => [
                'required',
                'exists:reviewers,id',
            ],

            'review_due_date' => [
                'required',
                'date',
                'after:today',
            ],

            'message' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Reviewer Eligibility
        |--------------------------------------------------------------------------
        */

        $reviewer = Reviewer::query()
            ->with('profile')
            ->findOrFail(
                $validated['reviewer_id']
            );

        if (
            $reviewer->status !== 'approved'
            ||
            !$reviewer->profile
            ||
            $reviewer->profile->approval_status !== 'approved'
            ||
            !$reviewer->profile->profile_completed
            ||
            !$reviewer->profile->available_for_review
            ||
            !$reviewer->profile->receive_review_invitations
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'The selected reviewer is not currently eligible to receive review invitations.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Active Invitation
        |--------------------------------------------------------------------------
        */

        $duplicate =
            ReviewerInvitation::query()
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
        | Optional Maximum Active Reviews Check
        |--------------------------------------------------------------------------
        |
        | This assumes ReviewerAssignment exists later.
        |
        */

        if (
            $reviewer->profile->maximum_active_reviews !== null
        ) {

            /*
            | Add active-assignment count here when your
            | reviewer_assignments table is implemented.
            */
        }

        DB::transaction(
            function () use (
                $validated,
                $reviewer
            ) {

                ReviewerInvitation::create([

                    'manuscript_id' =>
                        $validated['manuscript_id'],

                    'reviewer_id' =>
                        $reviewer->id,

                    'status' =>
                        'pending',

                    'invited_by' =>
                        auth()->id(),

                    'invited_at' =>
                        now(),

                    'review_due_date' =>
                        $validated['review_due_date'],

                    'message' =>
                        $validated['message']
                        ?? null,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Send invitation email here
                |--------------------------------------------------------------------------
                |
                | Example:
                |
                | Mail::to($reviewer->email)
                |     ->send(new ReviewerInvitationMail(...));
                |
                */
            }
        );

        return redirect()
            ->route(
                'admin.reviewers.invitations.index'
            )
            ->with(
                'success',
                'Reviewer invitation sent successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW INVITATION
    |--------------------------------------------------------------------------
    */

    public function show(
        ReviewerInvitation $reviewerInvitation
    ) {
        $reviewerInvitation->load([
            'reviewer.profile',
            'manuscript',
            'invitedBy',
        ]);

        return view(
            'admin.reviewers.invitations.show',
            compact(
                'reviewerInvitation'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SEND REMINDER
    |--------------------------------------------------------------------------
    */

    public function remind(
        ReviewerInvitation $reviewerInvitation
    ) {
        if (
            $reviewerInvitation->status !== 'pending'
        ) {
            return back()->with(
                'error',
                'Reminder can only be sent for pending invitations.'
            );
        }

        $reviewerInvitation->load(
            'reviewer'
        );

        /*
        |--------------------------------------------------------------------------
        | Update Reminder Tracking
        |--------------------------------------------------------------------------
        */

        $reviewerInvitation->update([

            'last_reminded_at' =>
                now(),

            'reminder_count' =>
                (
                    $reviewerInvitation->reminder_count
                    ?? 0
                ) + 1,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Send Reminder Email
        |--------------------------------------------------------------------------
        |
        | Mail::to(
        |     $reviewerInvitation->reviewer->email
        | )->send(
        |     new ReviewerInvitationReminderMail(
        |         $reviewerInvitation
        |     )
        | );
        |
        */

        return back()->with(
            'success',
            'Reviewer invitation reminder sent successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CANCEL INVITATION
    |--------------------------------------------------------------------------
    */

    public function cancel(
        Request $request,
        ReviewerInvitation $reviewerInvitation
    ) {
        if (
            !in_array(
                $reviewerInvitation->status,
                [
                    'pending',
                ],
                true
            )
        ) {
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

            'status' =>
                'cancelled',

            'cancelled_at' =>
                now(),

            'cancelled_by' =>
                auth()->id(),

            'cancellation_reason' =>
                $validated['cancellation_reason']
                ?? null,
        ]);

        return back()->with(
            'success',
            'Reviewer invitation cancelled successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MARK EXPIRED
    |--------------------------------------------------------------------------
    |
    | This can later be moved to a scheduled command.
    |
    */

    public function expire(
        ReviewerInvitation $reviewerInvitation
    ) {
        if (
            $reviewerInvitation->status !== 'pending'
        ) {
            return back()->with(
                'error',
                'Only pending invitations can be marked as expired.'
            );
        }

        $reviewerInvitation->update([

            'status' =>
                'expired',

            'expired_at' =>
                now(),
        ]);

        return back()->with(
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
        $invitations =
            ReviewerInvitation::query()
                ->with([
                    'reviewer.profile',
                    'manuscript',
                    'invitedBy',
                ])
                ->where(
                    'status',
                    $status
                )
                ->latest()
                ->paginate(20);

        return view(
            $view,
            compact(
                'invitations'
            )
        );
    }
}