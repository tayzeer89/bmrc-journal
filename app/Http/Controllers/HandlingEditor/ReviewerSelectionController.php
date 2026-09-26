<?php

namespace App\Http\Controllers\HandlingEditor;

use App\Http\Controllers\Controller;
use App\Models\Manuscript;
use App\Models\Reviewer;
use App\Models\ReviewerInvitation;
use App\Models\ReviewerProfile;
use App\Notifications\ReviewerInvitationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ReviewerSelectionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Manuscripts Ready for Reviewer Selection
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Manuscript::query()
            ->with([
                'journal',
                'articleType',
                'handlingEditor',
            ])
            ->withCount([
                'reviewerInvitations as active_reviewer_count' =>
                    function ($query) {
                        $query->whereIn(
                            'status',
                            [
                                'pending',
                                'accepted',
                            ]
                        );
                    },
            ])
            ->whereIn(
                'status',
                [
                    'reviewer_selection',
                    'reviewer_invitation',
                ]
            );

        /*
        |--------------------------------------------------------------------------
        | Handling Editor Access
        |--------------------------------------------------------------------------
        */

        if (
            !$user->hasRole(
                'system_administrator'
            )
        ) {
            $query->where(
                'handling_editor_id',
                $user->id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search Manuscript
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );

            $query->where(
                function ($q) use ($search) {

                    $q->where(
                        'manuscript_id',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'title',
                        'like',
                        "%{$search}%"
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $manuscripts = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'handling-editor.reviewer-selection.index',
            compact('manuscripts')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Reviewer Selection Page
    |--------------------------------------------------------------------------
    */

    public function show(
        Request $request,
        Manuscript $manuscript
    ) {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        if (
            !$user->hasRole(
                'system_administrator'
            )
            &&
            (int) $manuscript->handling_editor_id
            !==
            (int) $user->id
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Workflow Validation
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $manuscript->status,
                [
                    'reviewer_selection',
                    'reviewer_invitation',
                ],
                true
            )
        ) {
            return redirect()
                ->route(
                    'handling-editor.reviewer-selection.index'
                )
                ->with(
                    'error',
                    'This manuscript is not currently available for reviewer selection.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Manuscript Information
        |--------------------------------------------------------------------------
        */

        $manuscript->load([
            'journal',
            'articleType',
            'authors',
            'handlingEditor',

            'reviewerInvitations' =>
                function ($query) {

                    $query
                        ->with(
                            'reviewer.profile'
                        )
                        ->latest('id');
                },
        ]);

        /*
        |--------------------------------------------------------------------------
        | Active Reviewer Count
        |--------------------------------------------------------------------------
        */

        $activeReviewerCount =
            ReviewerInvitation::query()
                ->where(
                    'manuscript_id',
                    $manuscript->id
                )
                ->whereIn(
                    'status',
                    [
                        'pending',
                        'accepted',
                    ]
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | Reviewer Limit
        |--------------------------------------------------------------------------
        */

        $maximumReviewers = 3;

        $availableReviewerSlots =
            max(
                0,
                $maximumReviewers
                -
                $activeReviewerCount
            );

        /*
        |--------------------------------------------------------------------------
        | Previously Invited Reviewers
        |--------------------------------------------------------------------------
        */

        $alreadyInvitedReviewerIds =
            ReviewerInvitation::query()
                ->where(
                    'manuscript_id',
                    $manuscript->id
                )
                ->pluck('reviewer_id')
                ->unique();

        /*
        |--------------------------------------------------------------------------
        | Eligible Reviewer Query
        |--------------------------------------------------------------------------
        */

        $reviewerQuery =
            Reviewer::query()
                ->eligibleForReview()
                ->with('profile');

        /*
        |--------------------------------------------------------------------------
        | Exclude Previously Invited Reviewers
        |--------------------------------------------------------------------------
        */

        if (
            $alreadyInvitedReviewerIds
                ->isNotEmpty()
        ) {
            $reviewerQuery
                ->whereNotIn(
                    'id',
                    $alreadyInvitedReviewerIds
                );
        }

        /*
        |--------------------------------------------------------------------------
        | General Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );

            $reviewerQuery->where(
                function ($query) use ($search) {

                    $query
                        ->where(
                            'name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'email',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhereHas(
                            'profile',
                            function ($profile)
                            use ($search) {

                                $profile
                                    ->where(
                                        'display_name',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'institution',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'department',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'designation',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'speciality',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'sub_speciality',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'specialization',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'primary_expertise',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'areas_of_expertise',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'expertise_keywords',
                                        'like',
                                        "%{$search}%"
                                    );
                            }
                        );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Speciality Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'speciality'
            )
        ) {
            $speciality = trim(
                $request->speciality
            );

            $reviewerQuery
                ->whereHas(
                    'profile',
                    function ($query)
                    use ($speciality) {

                        $query->where(
                            'speciality',
                            $speciality
                        );
                    }
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Country Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'country'
            )
        ) {
            $country = trim(
                $request->country
            );

            $reviewerQuery
                ->whereHas(
                    'profile',
                    function ($query)
                    use ($country) {

                        $query->where(
                            'country',
                            $country
                        );
                    }
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Designation Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'designation'
            )
        ) {
            $designation = trim(
                $request->designation
            );

            $reviewerQuery
                ->whereHas(
                    'profile',
                    function ($query)
                    use ($designation) {

                        $query->where(
                            'designation',
                            $designation
                        );
                    }
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Reviewer Results
        |--------------------------------------------------------------------------
        */

        $reviewers =
            $reviewerQuery
                ->orderBy('name')
                ->paginate(20)
                ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $specialities =
            ReviewerProfile::query()
                ->whereNotNull(
                    'speciality'
                )
                ->where(
                    'speciality',
                    '!=',
                    ''
                )
                ->distinct()
                ->orderBy(
                    'speciality'
                )
                ->pluck(
                    'speciality'
                );

        $countries =
            ReviewerProfile::query()
                ->whereNotNull(
                    'country'
                )
                ->where(
                    'country',
                    '!=',
                    ''
                )
                ->distinct()
                ->orderBy(
                    'country'
                )
                ->pluck(
                    'country'
                );

        $designations =
            ReviewerProfile::query()
                ->whereNotNull(
                    'designation'
                )
                ->where(
                    'designation',
                    '!=',
                    ''
                )
                ->distinct()
                ->orderBy(
                    'designation'
                )
                ->pluck(
                    'designation'
                );

        /*
        |--------------------------------------------------------------------------
        | Invitation Dates
        |--------------------------------------------------------------------------
        */

        $invitationDate = now();

        $invitationExpiryDate =
            $invitationDate
                ->copy()
                ->addDays(3);

        $reviewDueDate =
            $invitationDate
                ->copy()
                ->addDays(15);

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'handling-editor.reviewer-selection.show',
            compact(
                'manuscript',
                'reviewers',
                'alreadyInvitedReviewerIds',
                'specialities',
                'countries',
                'designations',
                'activeReviewerCount',
                'maximumReviewers',
                'availableReviewerSlots',
                'invitationDate',
                'invitationExpiryDate',
                'reviewDueDate'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Invite Reviewers
    |--------------------------------------------------------------------------
    */

    public function invite(
        Request $request,
        Manuscript $manuscript
    ) {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | 1. Authorization
        |--------------------------------------------------------------------------
        */

        if (
            !$user->hasRole(
                'system_administrator'
            )
            &&
            (int) $manuscript->handling_editor_id
            !==
            (int) $user->id
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Check Manuscript Workflow
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $manuscript->status,
                [
                    'reviewer_selection',
                    'reviewer_invitation',
                ],
                true
            )
        ) {
            return redirect()
                ->route(
                    'handling-editor.reviewer-selection.index'
                )
                ->with(
                    'error',
                    'This manuscript is not currently available for reviewer selection.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Validation
        |--------------------------------------------------------------------------
        */

        $validated =
            $request->validate(
                [
                    'reviewer_ids' => [
                        'required',
                        'array',
                        'min:1',
                        'max:3',
                    ],

                    'reviewer_ids.*' => [
                        'required',
                        'integer',
                        'distinct',
                        'exists:reviewers,id',
                    ],
                ],
                [
                    'reviewer_ids.required' =>
                        'Please select at least one reviewer.',

                    'reviewer_ids.min' =>
                        'Please select at least one reviewer.',

                    'reviewer_ids.max' =>
                        'You can select a maximum of 3 reviewers.',

                    'reviewer_ids.*.distinct' =>
                        'The same reviewer cannot be selected more than once.',
                ]
            );

        /*
        |--------------------------------------------------------------------------
        | 4. Load Reviewers
        |--------------------------------------------------------------------------
        */

        $reviewers =
            Reviewer::query()
                ->with('profile')
                ->whereIn(
                    'id',
                    $validated[
                        'reviewer_ids'
                    ]
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | 5. Check Count
        |--------------------------------------------------------------------------
        */

        if (
            $reviewers->count()
            !==
            count(
                $validated[
                    'reviewer_ids'
                ]
            )
        ) {
            throw ValidationException::withMessages([
                'reviewer_ids' =>
                    'One or more selected reviewers could not be found.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Reviewer Eligibility
        |--------------------------------------------------------------------------
        */

        foreach (
            $reviewers as $reviewer
        ) {
            if (
                !$reviewer
                    ->canReceiveReviewInvitations()
            ) {
                throw ValidationException::withMessages([
                    'reviewer_ids' =>
                        $reviewer->name .
                        ' is not currently eligible to receive review invitations.',
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 7. Invitation Dates
        |--------------------------------------------------------------------------
        |
        | invited_at:
        |     Date/time invitation is created.
        |
        | expires_at:
        |     Reviewer has 3 days to Accept / Decline.
        |
        | review_deadline:
        |     Reviewer has 15 days from initial invitation
        |     to complete the manuscript review.
        |
        */

        $invitedAt = now();

        $expiresAt =
            $invitedAt
                ->copy()
                ->addDays(3);

        $reviewDueAt =
            $invitedAt
                ->copy()
                ->addDays(15);

        /*
        |--------------------------------------------------------------------------
        | Collection for Created Invitations
        |--------------------------------------------------------------------------
        */

        $createdInvitations = collect();

        /*
        |--------------------------------------------------------------------------
        | 8. Database Transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $manuscript,
                $reviewers,
                $user,
                $invitedAt,
                $expiresAt,

                // IMPORTANT:
                // Pass review deadline into transaction.
                $reviewDueAt,

                &$createdInvitations
            ) {

                /*
                |--------------------------------------------------------------------------
                | Lock Manuscript
                |--------------------------------------------------------------------------
                */

                $lockedManuscript =
                    Manuscript::query()
                        ->lockForUpdate()
                        ->findOrFail(
                            $manuscript->id
                        );

                /*
                |--------------------------------------------------------------------------
                | Re-check Workflow
                |--------------------------------------------------------------------------
                */

                if (
                    !in_array(
                        $lockedManuscript->status,
                        [
                            'reviewer_selection',
                            'reviewer_invitation',
                        ],
                        true
                    )
                ) {
                    throw ValidationException::withMessages([
                        'reviewer_ids' =>
                            'The manuscript workflow has changed. Please refresh the page.',
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Count Active Reviewers
                |--------------------------------------------------------------------------
                */

                $activeReviewerCount =
                    ReviewerInvitation::query()
                        ->where(
                            'manuscript_id',
                            $lockedManuscript->id
                        )
                        ->whereIn(
                            'status',
                            [
                                'pending',
                                'accepted',
                            ]
                        )
                        ->count();

                $maximumReviewers = 3;

                $availableSlots =
                    max(
                        0,
                        $maximumReviewers
                        -
                        $activeReviewerCount
                    );

                /*
                |--------------------------------------------------------------------------
                | Maximum Reached
                |--------------------------------------------------------------------------
                */

                if ($availableSlots <= 0) {

                    throw ValidationException::withMessages([
                        'reviewer_ids' =>
                            'Maximum reviewer limit reached. ' .
                            'This manuscript already has 3 active reviewers. ' .
                            'Please wait until a reviewer declines, expires, ' .
                            'or an invitation is cancelled before inviting another reviewer.',
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Selected Reviewers Must Fit Remaining Slots
                |--------------------------------------------------------------------------
                */

                $selectedCount =
                    $reviewers->count();

                if (
                    $selectedCount >
                    $availableSlots
                ) {
                    throw ValidationException::withMessages([
                        'reviewer_ids' =>
                            'This manuscript already has ' .
                            $activeReviewerCount .
                            ' active reviewer(s). ' .
                            'You can invite a maximum of ' .
                            $availableSlots .
                            ' additional reviewer(s).',
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Create Invitations
                |--------------------------------------------------------------------------
                */

                foreach (
                    $reviewers as $reviewer
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Prevent Duplicate Active Invitation
                    |--------------------------------------------------------------------------
                    */

                    $existingActiveInvitation =
                        ReviewerInvitation::query()
                            ->where(
                                'manuscript_id',
                                $lockedManuscript->id
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

                    if ($existingActiveInvitation) {

                        throw ValidationException::withMessages([
                            'reviewer_ids' =>
                                $reviewer->name .
                                ' already has an active invitation for this manuscript.',
                        ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Prevent Re-inviting Same Reviewer
                    |--------------------------------------------------------------------------
                    */

                    $previousInvitation =
                        ReviewerInvitation::query()
                            ->where(
                                'manuscript_id',
                                $lockedManuscript->id
                            )
                            ->where(
                                'reviewer_id',
                                $reviewer->id
                            )
                            ->exists();

                    if ($previousInvitation) {

                        throw ValidationException::withMessages([
                            'reviewer_ids' =>
                                $reviewer->name .
                                ' has already been invited to this manuscript. ' .
                                'Please select another reviewer.',
                        ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Create Reviewer Invitation
                    |--------------------------------------------------------------------------
                    */

                    $invitation =
                        ReviewerInvitation::create([

                            /*
                            |--------------------------------------------------------------------------
                            | Manuscript
                            |--------------------------------------------------------------------------
                            */

                            'manuscript_id' =>
                                $lockedManuscript->id,

                            /*
                            |--------------------------------------------------------------------------
                            | Reviewer
                            |--------------------------------------------------------------------------
                            */

                            'reviewer_id' =>
                                $reviewer->id,

                            /*
                            |--------------------------------------------------------------------------
                            | Invited By
                            |--------------------------------------------------------------------------
                            */

                            'invited_by' =>
                                $user->id,

                            /*
                            |--------------------------------------------------------------------------
                            | Secure Email Invitation Token
                            |--------------------------------------------------------------------------
                            */

                            'invitation_token' =>
                                Str::random(64),

                            /*
                            |--------------------------------------------------------------------------
                            | Invitation Status
                            |--------------------------------------------------------------------------
                            */

                            'status' =>
                                'pending',

                            /*
                            |--------------------------------------------------------------------------
                            | Invitation Date
                            |--------------------------------------------------------------------------
                            */

                            'invited_at' =>
                                $invitedAt,

                            /*
                            |--------------------------------------------------------------------------
                            | Invitation Response Deadline
                            |--------------------------------------------------------------------------
                            |
                            | Accept / Decline within 3 days.
                            |
                            */

                            'expires_at' =>
                                $expiresAt,

                            /*
                            |--------------------------------------------------------------------------
                            | Review Submission Deadline
                            |--------------------------------------------------------------------------
                            |
                            | IMPORTANT:
                            |
                            | This value is now actually stored
                            | in reviewer_invitations.review_deadline.
                            |
                            | 15 days from initial invitation.
                            |
                            */

                            'review_deadline' =>
                                $reviewDueAt,

                            /*
                            |--------------------------------------------------------------------------
                            | Reminder Information
                            |--------------------------------------------------------------------------
                            */

                            'reminder_count' =>
                                0,

                            'last_reminder_at' =>
                                null,

                            /*
                            |--------------------------------------------------------------------------
                            | Reviewer Response
                            |--------------------------------------------------------------------------
                            */

                            'responded_at' =>
                                null,

                            'response_note' =>
                                null,
                        ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Store Created Invitation
                    |--------------------------------------------------------------------------
                    */

                    $createdInvitations
                        ->push(
                            $invitation
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | Move Manuscript to Invitation Stage
                |--------------------------------------------------------------------------
                */

                $lockedManuscript->update([

                    'status' =>
                        'reviewer_invitation',

                    'current_stage' =>
                        'reviewer_invitation',
                ]);
            }
        );

        /*
        |--------------------------------------------------------------------------
        | 9. Send Reviewer Invitation Emails
        |--------------------------------------------------------------------------
        |
        | Email is sent only after database transaction succeeds.
        |
        */

        $emailSentCount = 0;
        $emailFailedCount = 0;

        foreach (
            $createdInvitations as $invitation
        ) {
            try {

                $invitation->loadMissing([
                    'reviewer',
                    'manuscript',
                    'inviter',
                ]);

                $reviewer =
                    $invitation->reviewer;

                /*
                |--------------------------------------------------------------------------
                | Reviewer / Email Validation
                |--------------------------------------------------------------------------
                */

                if (
                    !$reviewer
                    ||
                    blank(
                        $reviewer->email
                    )
                ) {
                    $emailFailedCount++;

                    Log::warning(
                        'Reviewer invitation email skipped because reviewer email is missing.',
                        [
                            'invitation_id' =>
                                $invitation->id,

                            'reviewer_id' =>
                                $invitation->reviewer_id,

                            'manuscript_id' =>
                                $invitation->manuscript_id,
                        ]
                    );

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Send Invitation Email
                |--------------------------------------------------------------------------
                */

                $reviewer->notify(
                    new ReviewerInvitationNotification(
                        $invitation
                    )
                );

                $emailSentCount++;

                Log::info(
                    'Reviewer invitation email sent successfully.',
                    [
                        'invitation_id' =>
                            $invitation->id,

                        'reviewer_id' =>
                            $reviewer->id,

                        'reviewer_email' =>
                            $reviewer->email,

                        'manuscript_id' =>
                            $invitation->manuscript_id,

                        'review_deadline' =>
                            $invitation
                                ->review_deadline
                                ?->format(
                                    'Y-m-d H:i:s'
                                ),
                    ]
                );

            } catch (\Throwable $e) {

                $emailFailedCount++;

                Log::error(
                    'Reviewer invitation email failed.',
                    [
                        'invitation_id' =>
                            $invitation->id,

                        'reviewer_id' =>
                            $invitation->reviewer_id,

                        'manuscript_id' =>
                            $invitation->manuscript_id,

                        'reviewer_email' =>
                            $invitation
                                ->reviewer
                                ?->email,

                        'error' =>
                            $e->getMessage(),
                    ]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 10. Result Message
        |--------------------------------------------------------------------------
        */

        $message =
            $createdInvitations->count() .
            ' reviewer invitation(s) created successfully. ' .
            $emailSentCount .
            ' invitation email(s) sent. ' .
            'The invitation will expire after 3 days. ' .
            'The review deadline is 15 days from the initial invitation date (' .
            $reviewDueAt->format(
                'd M Y'
            ) .
            ').';

        if ($emailFailedCount > 0) {

            $message .=
                ' ' .
                $emailFailedCount .
                ' invitation email(s) could not be sent. ' .
                'Please check the Laravel log for details.';
        }

        /*
        |--------------------------------------------------------------------------
        | 11. Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'handling-editor.reviewer-selection.show',
                $manuscript->id
            )
            ->with(
                $emailFailedCount > 0
                    ? 'warning'
                    : 'success',
                $message
            );
    }
}