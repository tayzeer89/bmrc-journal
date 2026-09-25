@extends('admin.layouts.app')

@section('title', 'Reviewer Invitation Details')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | Basic Objects
    |--------------------------------------------------------------------------
    */

    $reviewer = $reviewerInvitation->reviewer;
    $profile = $reviewer?->profile;
    $manuscript = $reviewerInvitation->manuscript;
    $inviter = $reviewerInvitation->inviter;


    /*
    |--------------------------------------------------------------------------
    | Invitation Status
    |--------------------------------------------------------------------------
    */

    $status = $reviewerInvitation->status ?? 'pending';


    /*
    |--------------------------------------------------------------------------
    | Status Bootstrap Class
    |--------------------------------------------------------------------------
    */

    $statusClass = match ($status) {

        'accepted'  => 'success',
        'declined'  => 'danger',
        'expired'   => 'secondary',
        'cancelled' => 'dark',

        default     => 'warning',

    };


    /*
    |--------------------------------------------------------------------------
    | Review Due Date
    |--------------------------------------------------------------------------
    |
    | BMRC Reviewer Policy
    |
    | Day 0  = Invitation
    | Day 3  = Invitation expires
    | Day 15 = Review due
    |
    | The 15-day review period starts from the ORIGINAL invitation date.
    |
    */

    $reviewDueDate = $reviewerInvitation->invited_at
        ? $reviewerInvitation
            ->invited_at
            ->copy()
            ->addDays(15)
        : null;


    /*
    |--------------------------------------------------------------------------
    | Invitation Expired?
    |--------------------------------------------------------------------------
    */

    $isInvitationExpired =
        $reviewerInvitation->expires_at
        &&
        $reviewerInvitation
            ->expires_at
            ->isPast();


    /*
    |--------------------------------------------------------------------------
    | Days Remaining
    |--------------------------------------------------------------------------
    */

    $daysUntilReviewDue = null;

    if ($reviewDueDate) {

        $daysUntilReviewDue = now()
            ->startOfDay()
            ->diffInDays(
                $reviewDueDate->copy()->startOfDay(),
                false
            );

    }

@endphp


<div class="container-fluid py-4">


    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="row mb-4">

        <div class="col-md-8">

            <h4 class="mb-1">

                <i class="bi bi-envelope-paper"></i>

                Reviewer Invitation Details

            </h4>

            <div class="text-muted">

                Reviewer invitation, response and review timeline

            </div>

        </div>


        <div class="col-md-4 text-md-right mt-3 mt-md-0">

            <a
                href="{{ route(
                    'admin.reviewers.invitations.index'
                ) }}"
                class="btn btn-outline-secondary"
            >

                <i class="bi bi-arrow-left"></i>

                Back to Invitations

            </a>

        </div>

    </div>



    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle mr-1"></i>

            {{ session('success') }}


            <button
                type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Close"
            >

                <span aria-hidden="true">
                    &times;
                </span>

            </button>

        </div>

    @endif



    {{-- =========================================================
         ERROR MESSAGE
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle mr-1"></i>

            {{ session('error') }}


            <button
                type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Close"
            >

                <span aria-hidden="true">
                    &times;
                </span>

            </button>

        </div>

    @endif



    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>

                <i class="bi bi-exclamation-circle"></i>

                Please correct the following:

            </strong>


            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif



    {{-- =========================================================
         INVITATION STATUS SUMMARY
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="row align-items-center">


                {{-- Status --}}

                <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">

                    <div class="text-muted small mb-1">

                        Invitation Status

                    </div>


                    <span
                        class="badge badge-{{ $statusClass }}"
                        style="font-size: 14px;"
                    >

                        {{
                            ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $status
                                )
                            )
                        }}

                    </span>

                </div>



                {{-- Invitation ID --}}

                <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">

                    <div class="text-muted small mb-1">

                        Invitation ID

                    </div>

                    <strong>

                        #{{ $reviewerInvitation->id }}

                    </strong>

                </div>



                {{-- Manuscript --}}

                <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">

                    <div class="text-muted small mb-1">

                        Manuscript ID

                    </div>

                    <strong>

                        {{
                            $manuscript?->manuscript_id
                            ?? $manuscript?->id
                            ?? 'N/A'
                        }}

                    </strong>

                </div>



                {{-- Reviewer --}}

                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small mb-1">

                        Reviewer

                    </div>

                    <strong>

                        {{
                            $profile?->display_name
                            ?: $reviewer?->name
                            ?: 'N/A'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>



    <div class="row">


        {{-- =====================================================
             LEFT COLUMN
        ====================================================== --}}

        <div class="col-lg-8">


            {{-- =================================================
                 1. MANUSCRIPT INFORMATION
            ================================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-primary text-white">

                    <strong>

                        <i class="bi bi-journal-text"></i>

                        1. Manuscript Information

                    </strong>

                </div>


                <div class="card-body">

                    <div class="row">


                        {{-- Manuscript ID --}}

                        <div class="col-md-4 mb-3">

                            <div class="text-muted small">

                                Manuscript ID

                            </div>

                            <strong>

                                {{
                                    $manuscript?->manuscript_id
                                    ?? $manuscript?->id
                                    ?? 'N/A'
                                }}

                            </strong>

                        </div>



                        {{-- Status --}}

                        <div class="col-md-4 mb-3">

                            <div class="text-muted small">

                                Manuscript Status

                            </div>


                            @if($manuscript)

                                <span class="badge badge-info">

                                    {{
                                        ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $manuscript->status
                                            )
                                        )
                                    }}

                                </span>

                            @else

                                N/A

                            @endif

                        </div>



                        {{-- Stage --}}

                        <div class="col-md-4 mb-3">

                            <div class="text-muted small">

                                Current Stage

                            </div>

                            <strong>

                                @if($manuscript?->current_stage)

                                    {{
                                        ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $manuscript->current_stage
                                            )
                                        )
                                    }}

                                @else

                                    N/A

                                @endif

                            </strong>

                        </div>



                        {{-- Article Title --}}

                        <div class="col-12">

                            <div class="text-muted small mb-1">

                                Article Title

                            </div>

                            <h5 class="mb-0">

                                {{
                                    $manuscript?->title
                                    ?? 'N/A'
                                }}

                            </h5>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =================================================
                 2. REVIEWER INFORMATION
            ================================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">

                    <strong>

                        <i class="bi bi-person-badge"></i>

                        2. Reviewer Information

                    </strong>

                </div>


                <div class="card-body">

                    <div class="row">


                        {{-- Reviewer Name --}}

                        <div class="col-md-6 mb-3">

                            <div class="text-muted small">

                                Reviewer Name

                            </div>

                            <h5 class="mb-0">

                                {{
                                    $profile?->display_name
                                    ?: $reviewer?->name
                                    ?: 'N/A'
                                }}

                            </h5>

                        </div>



                        {{-- Email --}}

                        <div class="col-md-6 mb-3">

                            <div class="text-muted small">

                                Email Address

                            </div>

                            <div>

                                {{
                                    $reviewer?->email
                                    ?? 'N/A'
                                }}

                            </div>

                        </div>



                        {{-- Designation --}}

                        <div class="col-md-6 mb-3">

                            <div class="text-muted small">

                                Designation

                            </div>

                            <strong>

                                {{
                                    $profile?->designation
                                    ?? 'N/A'
                                }}

                            </strong>

                        </div>



                        {{-- Department --}}

                        <div class="col-md-6 mb-3">

                            <div class="text-muted small">

                                Department

                            </div>

                            <div>

                                {{
                                    $profile?->department
                                    ?? 'N/A'
                                }}

                            </div>

                        </div>



                        {{-- Institution --}}

                        <div class="col-md-6 mb-3">

                            <div class="text-muted small">

                                Institution

                            </div>

                            <div>

                                {{
                                    $profile?->institution
                                    ?? 'N/A'
                                }}

                            </div>

                        </div>



                        {{-- Country --}}

                        <div class="col-md-6 mb-3">

                            <div class="text-muted small">

                                Country

                            </div>

                            <div>

                                {{
                                    $profile?->country
                                    ?? 'N/A'
                                }}

                            </div>

                        </div>



                        {{-- Speciality --}}

                        <div class="col-md-6 mb-3">

                            <div class="text-muted small">

                                Speciality

                            </div>


                            @if($profile?->speciality)

                                <span class="badge badge-primary">

                                    {{ $profile->speciality }}

                                </span>

                            @else

                                N/A

                            @endif

                        </div>



                        {{-- Sub-speciality --}}

                        <div class="col-md-6 mb-3">

                            <div class="text-muted small">

                                Sub-speciality

                            </div>

                            <div>

                                {{
                                    $profile?->sub_speciality
                                    ?? 'N/A'
                                }}

                            </div>

                        </div>



                        {{-- Highest Degree --}}

                        <div class="col-md-6 mb-3">

                            <div class="text-muted small">

                                Highest Degree

                            </div>

                            <div>

                                {{
                                    $profile?->highest_degree
                                    ?? 'N/A'
                                }}

                            </div>

                        </div>



                        {{-- Experience --}}

                        <div class="col-md-6 mb-3">

                            <div class="text-muted small">

                                Professional Experience

                            </div>

                            <div>

                                @if(
                                    $profile?->years_of_experience
                                    !== null
                                )

                                    {{
                                        $profile
                                            ->years_of_experience
                                    }}

                                    years

                                @else

                                    N/A

                                @endif

                            </div>

                        </div>



                        {{-- Publication Count --}}

                        <div class="col-md-6 mb-3">

                            <div class="text-muted small">

                                Publications

                            </div>

                            <strong>

                                {{
                                    $profile?->publication_count
                                    ?? 0
                                }}

                            </strong>

                        </div>



                        {{-- Previous Reviews --}}

                        <div class="col-md-6 mb-3">

                            <div class="text-muted small">

                                Previous Reviews Completed

                            </div>

                            <strong>

                                {{
                                    $profile?->external_reviews_completed
                                    ?? 0
                                }}

                            </strong>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =================================================
                 3. INVITATION & REVIEW TIMELINE
            ================================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">

                    <strong>

                        <i class="bi bi-calendar-check"></i>

                        3. Invitation & Review Timeline

                    </strong>

                </div>


                <div class="card-body">

                    <div class="row">


                        {{-- =========================================
                             DAY 0
                        ========================================== --}}

                        <div class="col-md-4 mb-3">

                            <div class="timeline-box">

                                <div
                                    class="timeline-icon
                                           bg-primary
                                           text-white"
                                >

                                    <i class="bi bi-send"></i>

                                </div>


                                <div class="small text-muted mt-3">

                                    Invitation Sent

                                </div>


                                <div class="font-weight-bold">

                                    {{
                                        $reviewerInvitation
                                            ->invited_at
                                            ?->format('d M Y')
                                        ?? 'N/A'
                                    }}

                                </div>


                                @if(
                                    $reviewerInvitation
                                        ->invited_at
                                )

                                    <div class="small text-muted">

                                        {{
                                            $reviewerInvitation
                                                ->invited_at
                                                ->format('h:i A')
                                        }}

                                    </div>

                                @endif


                                <span class="badge badge-primary mt-2">

                                    Day 0

                                </span>

                            </div>

                        </div>



                        {{-- =========================================
                             DAY 3
                        ========================================== --}}

                        <div class="col-md-4 mb-3">

                            <div class="timeline-box">

                                <div
                                    class="timeline-icon
                                           bg-warning"
                                >

                                    <i class="bi bi-hourglass-split"></i>

                                </div>


                                <div class="small text-muted mt-3">

                                    Invitation Expiry

                                </div>


                                <div class="font-weight-bold">

                                    {{
                                        $reviewerInvitation
                                            ->expires_at
                                            ?->format('d M Y')
                                        ?? 'N/A'
                                    }}

                                </div>


                                <div class="small text-muted">

                                    Accept / Decline Deadline

                                </div>


                                <span class="badge badge-warning mt-2">

                                    Day 3

                                </span>

                            </div>

                        </div>



                        {{-- =========================================
                             DAY 15
                        ========================================== --}}

                        <div class="col-md-4 mb-3">

                            <div class="timeline-box">

                                <div
                                    class="timeline-icon
                                           bg-danger
                                           text-white"
                                >

                                    <i class="bi bi-calendar-event"></i>

                                </div>


                                <div class="small text-muted mt-3">

                                    Review Due

                                </div>


                                <div class="font-weight-bold text-danger">

                                    {{
                                        $reviewDueDate
                                            ?->format('d M Y')
                                        ?? 'N/A'
                                    }}

                                </div>


                                <div class="small text-muted">

                                    Final Review Deadline

                                </div>


                                <span class="badge badge-danger mt-2">

                                    Day 15

                                </span>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                         VISUAL TIMELINE
                    ================================================== --}}

                    @if($reviewerInvitation->invited_at)

                        <div class="border rounded p-4 mt-3">

                            <div class="row text-center align-items-center">


                                <div class="col-md-3">

                                    <span class="badge badge-primary mb-2">

                                        Day 0

                                    </span>

                                    <div class="font-weight-bold small">

                                        Invitation Sent

                                    </div>

                                    <div class="small text-muted">

                                        {{
                                            $reviewerInvitation
                                                ->invited_at
                                                ->format('d M Y')
                                        }}

                                    </div>

                                </div>


                                <div class="col-md-1 d-none d-md-block">

                                    <i
                                        class="bi bi-arrow-right
                                               text-muted"
                                    ></i>

                                </div>


                                <div class="col-md-3">

                                    <span class="badge badge-warning mb-2">

                                        Day 3

                                    </span>

                                    <div class="font-weight-bold small">

                                        Invitation Expires

                                    </div>

                                    <div class="small text-muted">

                                        {{
                                            $reviewerInvitation
                                                ->expires_at
                                                ?->format('d M Y')
                                            ?? 'N/A'
                                        }}

                                    </div>

                                </div>


                                <div class="col-md-1 d-none d-md-block">

                                    <i
                                        class="bi bi-arrow-right
                                               text-muted"
                                    ></i>

                                </div>


                                <div class="col-md-4">

                                    <span class="badge badge-danger mb-2">

                                        Day 15

                                    </span>

                                    <div class="font-weight-bold small">

                                        Review Due

                                    </div>

                                    <div class="small text-muted">

                                        {{
                                            $reviewDueDate
                                                ?->format('d M Y')
                                            ?? 'N/A'
                                        }}

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endif



                    {{-- =================================================
                         DEADLINE POLICY
                    ================================================== --}}

                    <div class="alert alert-light border mt-4 mb-0">

                        <div class="d-flex">

                            <div class="mr-2">

                                <i
                                    class="bi bi-info-circle
                                           text-primary"
                                ></i>

                            </div>


                            <div class="small">

                                <strong>

                                    Review Deadline Policy:

                                </strong>

                                The reviewer has

                                <strong>
                                    3 days
                                </strong>

                                from the initial invitation to
                                accept or decline.

                                The review must be completed within

                                <strong>
                                    15 days from the original
                                    invitation date
                                </strong>.

                                Accepting the invitation later
                                does not extend the original
                                review deadline.

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =================================================
                 4. REVIEWER RESPONSE
            ================================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">

                    <strong>

                        <i class="bi bi-chat-left-text"></i>

                        4. Reviewer Response

                    </strong>

                </div>


                <div class="card-body">

                    <div class="row">


                        {{-- Status --}}

                        <div class="col-md-4 mb-3">

                            <div class="text-muted small">

                                Response Status

                            </div>


                            <span class="badge badge-{{ $statusClass }}">

                                {{
                                    ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $status
                                        )
                                    )
                                }}

                            </span>

                        </div>



                        {{-- Responded At --}}

                        <div class="col-md-4 mb-3">

                            <div class="text-muted small">

                                Responded At

                            </div>

                            <div>

                                {{
                                    $reviewerInvitation
                                        ->responded_at
                                        ?->format(
                                            'd M Y h:i A'
                                        )
                                    ?? 'Not responded yet'
                                }}

                            </div>

                        </div>



                        {{-- Reminder Count --}}

                        <div class="col-md-4 mb-3">

                            <div class="text-muted small">

                                Reminders Sent

                            </div>

                            <strong>

                                {{
                                    $reviewerInvitation
                                        ->reminder_count
                                    ?? 0
                                }}

                            </strong>

                        </div>



                        {{-- Response Note --}}

                        <div class="col-12">

                            <div class="text-muted small mb-1">

                                Response Note

                            </div>

                            <div class="border rounded bg-light p-3">

                                {{
                                    $reviewerInvitation
                                        ->response_note
                                    ?: 'No response note available.'
                                }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- =====================================================
             RIGHT COLUMN
        ====================================================== --}}

        <div class="col-lg-4">


            {{-- =================================================
                 INVITATION INFORMATION
            ================================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">

                    <strong>

                        <i class="bi bi-info-circle"></i>

                        Invitation Information

                    </strong>

                </div>


                <div class="card-body">

                    <table class="table table-sm table-borderless mb-0">

                        <tbody>


                            <tr>

                                <th width="45%">

                                    Invitation ID

                                </th>

                                <td>

                                    #{{ $reviewerInvitation->id }}

                                </td>

                            </tr>



                            <tr>

                                <th>

                                    Invited By

                                </th>

                                <td>

                                    {{
                                        $inviter?->name
                                        ?? 'N/A'
                                    }}

                                </td>

                            </tr>



                            <tr>

                                <th>

                                    Invitation Date

                                </th>

                                <td>

                                    {{
                                        $reviewerInvitation
                                            ->invited_at
                                            ?->format(
                                                'd M Y h:i A'
                                            )
                                        ?? 'N/A'
                                    }}

                                </td>

                            </tr>



                            <tr>

                                <th>

                                    Expiry Date

                                </th>

                                <td>

                                    {{
                                        $reviewerInvitation
                                            ->expires_at
                                            ?->format(
                                                'd M Y h:i A'
                                            )
                                        ?? 'N/A'
                                    }}

                                </td>

                            </tr>



                            <tr>

                                <th>

                                    Review Due

                                </th>

                                <td>

                                    {{
                                        $reviewDueDate
                                            ?->format('d M Y')
                                        ?? 'N/A'
                                    }}

                                </td>

                            </tr>



                            <tr>

                                <th>

                                    Status

                                </th>

                                <td>

                                    <span
                                        class="badge
                                               badge-{{ $statusClass }}"
                                    >

                                        {{
                                            ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $status
                                                )
                                            )
                                        }}

                                    </span>

                                </td>

                            </tr>


                        </tbody>

                    </table>

                </div>

            </div>



            {{-- =================================================
                 DEADLINE STATUS
            ================================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">

                    <strong>

                        <i class="bi bi-clock"></i>

                        Deadline Status

                    </strong>

                </div>


                <div class="card-body">


                    {{-- Invitation Expiry --}}

                    <div class="mb-4">

                        <div class="text-muted small mb-1">

                            Invitation

                        </div>


                        @if(
                            $status === 'pending'
                            &&
                            $isInvitationExpired
                        )

                            <span class="badge badge-danger">

                                Invitation Expired

                            </span>

                        @elseif($status === 'pending')

                            <span class="badge badge-warning">

                                Awaiting Response

                            </span>

                        @else

                            <span
                                class="badge
                                       badge-{{ $statusClass }}"
                            >

                                {{ ucfirst($status) }}

                            </span>

                        @endif

                    </div>



                    {{-- Review Deadline --}}

                    <div>

                        <div class="text-muted small mb-1">

                            Review Deadline

                        </div>


                        @if($reviewDueDate)

                            @if($daysUntilReviewDue > 0)

                                <strong class="text-success">

                                    {{
                                        $daysUntilReviewDue
                                    }}

                                    day(s) remaining

                                </strong>

                            @elseif($daysUntilReviewDue === 0)

                                <strong class="text-warning">

                                    Due today

                                </strong>

                            @else

                                <strong class="text-danger">

                                    Overdue by

                                    {{
                                        abs(
                                            $daysUntilReviewDue
                                        )
                                    }}

                                    day(s)

                                </strong>

                            @endif

                        @else

                            N/A

                        @endif

                    </div>

                </div>

            </div>



            {{-- =================================================
                 REMINDER INFORMATION
            ================================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">

                    <strong>

                        <i class="bi bi-bell"></i>

                        Reminder Information

                    </strong>

                </div>


                <div class="card-body">

                    <div class="row">


                        <div class="col-6">

                            <div class="text-muted small">

                                Reminder Count

                            </div>

                            <h5 class="mb-0">

                                {{
                                    $reviewerInvitation
                                        ->reminder_count
                                    ?? 0
                                }}

                            </h5>

                        </div>


                        <div class="col-6">

                            <div class="text-muted small">

                                Last Reminder

                            </div>

                            <div>

                                {{
                                    $reviewerInvitation
                                        ->last_reminder_at
                                        ?->format('d M Y')
                                    ?? 'Never'
                                }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>




{{-- =================================================
     ACTIONS
================================================== --}}

<div class="card shadow-sm mb-4">

    <div class="card-header bg-white">

        <strong>
            <i class="bi bi-gear"></i>
            Invitation Actions
        </strong>

    </div>

    <div class="card-body">

        @if($status === 'pending')

            {{-- =========================================
                 SEND REMINDER
            ========================================== --}}

            <form
                method="POST"
                action="{{ route(
                    'admin.reviewers.invitations.remind',
                    ['reviewerInvitation' => $reviewerInvitation->id]
                ) }}"
                class="mb-2"
            >
                @csrf

                <button
                    type="submit"
                    class="btn btn-warning btn-block"
                    onclick="return confirm('Send a reminder to this reviewer?');"
                >
                    <i class="bi bi-bell"></i>
                    Send Reminder
                </button>

            </form>


            {{-- =========================================
                 MARK EXPIRED
            ========================================== --}}

            @if(
                Route::has(
                    'admin.reviewers.invitations.expire'
                )
            )

                <form
                    method="POST"
                    action="{{ route(
                        'admin.reviewers.invitations.expire',
                        ['reviewerInvitation' => $reviewerInvitation->id]
                    ) }}"
                    class="mb-2"
                >
                    @csrf

                    <button
                        type="submit"
                        class="btn btn-secondary btn-block"
                        onclick="return confirm('Mark this invitation as expired?');"
                    >
                        <i class="bi bi-hourglass-bottom"></i>
                        Mark Expired
                    </button>

                </form>

            @endif


            {{-- =========================================
                 CANCEL INVITATION
            ========================================== --}}

            @if(
                Route::has(
                    'admin.reviewers.invitations.cancel'
                )
            )

                <button
                    type="button"
                    class="btn btn-danger btn-block"
                    data-toggle="modal"
                    data-target="#cancelInvitationModal"
                >
                    <i class="bi bi-x-circle"></i>
                    Cancel Invitation
                </button>

            @endif


        @else

            <div class="alert alert-light border mb-0">

                <i class="bi bi-info-circle mr-1"></i>

                No pending invitation actions are available.

                Current status:

                <strong>
                    {{ ucfirst($status) }}
                </strong>

            </div>

        @endif

    </div>

</div>

            {{-- =================================================
                 WORKFLOW INFORMATION
            ================================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">

                    <strong>

                        <i class="bi bi-diagram-3"></i>

                        Workflow

                    </strong>

                </div>


                <div class="card-body">

                    <div class="workflow-item">

                        <span class="workflow-number">
                            1
                        </span>

                        <div>

                            <strong>
                                Reviewer Selected
                            </strong>

                            <div class="small text-muted">
                                Handling Editor
                            </div>

                        </div>

                    </div>


                    <div class="workflow-line"></div>


                    <div class="workflow-item">

                        <span class="workflow-number">
                            2
                        </span>

                        <div>

                            <strong>
                                Invitation Sent
                            </strong>

                            <div class="small text-muted">
                                Day 0
                            </div>

                        </div>

                    </div>


                    <div class="workflow-line"></div>


                    <div class="workflow-item">

                        <span class="workflow-number">
                            3
                        </span>

                        <div>

                            <strong>
                                Accept / Decline
                            </strong>

                            <div class="small text-muted">
                                Within 3 days
                            </div>

                        </div>

                    </div>


                    <div class="workflow-line"></div>


                    <div class="workflow-item">

                        <span class="workflow-number">
                            4
                        </span>

                        <div>

                            <strong>
                                Peer Review
                            </strong>

                            <div class="small text-muted">
                                Due Day 15
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



{{-- =============================================================
     CANCEL INVITATION MODAL
============================================================= --}}

@if(
    $status === 'pending'
    &&
    Route::has(
        'admin.reviewers.invitations.cancel'
    )
)

    <div
        class="modal fade"
        id="cancelInvitationModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="cancelInvitationModalLabel"
        aria-hidden="true"
    >

        <div
            class="modal-dialog"
            role="document"
        >

            <div class="modal-content">


                <form
                    method="POST"
                    action="{{ route(
                        'admin.reviewers.invitations.cancel',
                        [
                            'reviewerInvitation'
                                => $reviewerInvitation->id
                        ]
                    ) }}"
                >

                    @csrf


                    {{-- Modal Header --}}

                    <div class="modal-header">

                        <h5
                            class="modal-title"
                            id="cancelInvitationModalLabel"
                        >

                            <i
                                class="bi bi-x-circle
                                       text-danger"
                            ></i>

                            Cancel Reviewer Invitation

                        </h5>


                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >

                            <span aria-hidden="true">

                                &times;

                            </span>

                        </button>

                    </div>



                    {{-- Modal Body --}}

                    <div class="modal-body">

                        <div class="alert alert-warning">

                            You are about to cancel the
                            reviewer invitation sent to

                            <strong>

                                {{
                                    $profile?->display_name
                                    ?: $reviewer?->name
                                    ?: 'this reviewer'
                                }}

                            </strong>.

                        </div>


                        <div class="form-group">

                            <label class="font-weight-bold">

                                Cancellation Reason

                            </label>


                            <textarea
                                name="cancellation_reason"
                                class="form-control"
                                rows="4"
                                maxlength="3000"
                                placeholder="Enter cancellation reason..."
                            >{{ old('cancellation_reason') }}</textarea>


                            <small class="form-text text-muted">

                                Optional. Maximum 3000 characters.

                            </small>

                        </div>

                    </div>



                    {{-- Modal Footer --}}

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            data-dismiss="modal"
                        >

                            Close

                        </button>


                        <button
                            type="submit"
                            class="btn btn-danger"
                        >

                            <i class="bi bi-x-circle"></i>

                            Confirm Cancellation

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif



{{-- =============================================================
     PAGE CSS
============================================================= --}}

<style>

    /*
    |--------------------------------------------------------------------------
    | Timeline
    |--------------------------------------------------------------------------
    */

    .timeline-box {
        border: 1px solid #dee2e6;
        border-radius: .4rem;
        padding: 1.25rem;
        text-align: center;
        height: 100%;
        background-color: #ffffff;
    }


    .timeline-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 1.1rem;
    }


    /*
    |--------------------------------------------------------------------------
    | Workflow
    |--------------------------------------------------------------------------
    */

    .workflow-item {
        display: flex;
        align-items: center;
    }


    .workflow-number {
        width: 32px;
        height: 32px;
        min-width: 32px;
        border-radius: 50%;
        background-color: #0d6efd;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-right: 12px;
    }


    .workflow-line {
        width: 2px;
        height: 24px;
        background-color: #dee2e6;
        margin-left: 15px;
    }


    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    .table th {
        font-weight: 600;
    }


    /*
    |--------------------------------------------------------------------------
    | Cards
    |--------------------------------------------------------------------------
    */

    .card {
        border-radius: .4rem;
    }


    /*
    |--------------------------------------------------------------------------
    | Buttons
    |--------------------------------------------------------------------------
    */

    .btn-block {
        width: 100%;
    }

</style>

@endsection