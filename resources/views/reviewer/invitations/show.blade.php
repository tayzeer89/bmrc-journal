@extends('reviewer.layouts.app')

@section('title', 'Review Invitation | BMRC Journal')

@section('content')

{{-- =========================================================
    PAGE HEADER
========================================================= --}}

<div class="page-header mb-4">
    <h1>Review Invitation</h1>

    <p class="text-muted mb-0">
        Review the manuscript information and respond to the invitation.
    </p>
</div>


{{-- =========================================================
    FLASH MESSAGES
========================================================= --}}

@if(session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        {{ session('info') }}
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('warning') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <strong>Please correct the following:</strong>

        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- =========================================================
    PREPARE DATA
========================================================= --}}

@php
    $manuscript = $invitation->manuscript;

    $status = strtolower(
        $invitation->status ?? 'pending'
    );

    $isExpired = false;

    if ($invitation->expires_at) {
        $isExpired = \Carbon\Carbon::parse(
            $invitation->expires_at
        )->isPast();
    }

    /*
    |--------------------------------------------------------------------------
    | Review Deadline
    |--------------------------------------------------------------------------
    */

    $reviewDeadlinePassed = false;

    if ($invitation->review_deadline) {
        $reviewDeadlinePassed = \Carbon\Carbon::parse(
            $invitation->review_deadline
        )->isPast();
    }
@endphp


<div class="row g-4">

    {{-- =====================================================
        LEFT SIDE
    ====================================================== --}}

    <div class="col-lg-8">

        {{-- =================================================
            MANUSCRIPT INFORMATION
        ================================================== --}}

        <div class="reviewer-card mb-4">

            <div class="card-header">
                <i class="bi bi-file-earmark-text me-2"></i>
                Manuscript Information
            </div>

            <div class="card-body">

                {{-- Manuscript ID --}}
                <div class="mb-4">
                    <small class="text-muted d-block mb-1">
                        Manuscript ID
                    </small>

                    <strong class="text-primary">
                        {{ $manuscript->manuscript_id
                            ?? $invitation->manuscript_id }}
                    </strong>
                </div>


                {{-- Article Title --}}
                <div class="mb-4">
                    <small class="text-muted d-block mb-1">
                        Article Title
                    </small>

                    <h5 class="mb-0">
                        {{ $manuscript->title
                            ?? 'Title unavailable' }}
                    </h5>
                </div>


                <div class="row g-3">

                    {{-- Journal --}}
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">
                            Journal
                        </small>

                        <strong>
                            {{ $manuscript?->journal?->name
                                ?? 'BMRC Bulletin' }}
                        </strong>
                    </div>


                    {{-- Article Type --}}
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">
                            Article Type
                        </small>

                        <strong>
                            {{ $manuscript?->articleType?->name
                                ?? 'N/A' }}
                        </strong>
                    </div>

                </div>

            </div>
        </div>


        {{-- =================================================
            BLIND REVIEW NOTICE
        ================================================== --}}

        <div class="alert alert-warning mb-4">

            <div class="d-flex">

                <i
                    class="bi bi-shield-lock-fill me-3"
                    style="font-size:26px;"
                ></i>

                <div>
                    <strong>Blind Peer Review</strong>

                    <div class="small mt-1">
                        This reviewer portal displays only
                        reviewer-appropriate manuscript information.

                        Author names, affiliations, email addresses,
                        phone numbers, ORCID information,
                        corresponding-author information and other
                        author-identifying information are not
                        displayed in the reviewer workspace.
                    </div>
                </div>

            </div>

        </div>


        {{-- =================================================
            ABSTRACT
        ================================================== --}}

        @if(!empty($manuscript?->abstract))

            <div class="reviewer-card mb-4">

                <div class="card-header">
                    <i class="bi bi-card-text me-2"></i>
                    Abstract
                </div>

                <div class="card-body">

                    <div
                        style="
                            line-height:1.8;
                            text-align:justify;
                        "
                    >
                        {!! $manuscript->abstract !!}
                    </div>

                </div>

            </div>

        @endif


        {{-- =================================================
            INVITATION DETAILS
        ================================================== --}}

        <div class="reviewer-card mb-4">

            <div class="card-header">
                <i class="bi bi-calendar-check me-2"></i>
                Review Invitation Details
            </div>

            <div class="card-body">

                <div class="row g-4">

                    {{-- Invitation Date --}}
                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">
                            Invitation Date
                        </small>

                        <strong>
                            @if($invitation->invited_at)

                                {{ \Carbon\Carbon::parse(
                                    $invitation->invited_at
                                )->format('d M Y, h:i A') }}

                            @else
                                —
                            @endif
                        </strong>

                    </div>


                    {{-- Response Deadline --}}
                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">
                            Response Deadline
                        </small>

                        <strong>
                            @if($invitation->expires_at)

                                {{ \Carbon\Carbon::parse(
                                    $invitation->expires_at
                                )->format('d M Y') }}

                            @else
                                —
                            @endif
                        </strong>

                    </div>


                    {{-- Review Deadline --}}
                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">
                            Review Deadline
                        </small>

                        <strong>
                            @if($invitation->review_deadline)

                                {{ \Carbon\Carbon::parse(
                                    $invitation->review_deadline
                                )->format('d M Y') }}

                            @else
                                —
                            @endif
                        </strong>

                        @if(
                            $status === 'accepted'
                            &&
                            $reviewDeadlinePassed
                        )
                            <div class="mt-1">
                                <span class="badge bg-danger">
                                    Deadline Passed
                                </span>
                            </div>
                        @endif

                    </div>


                    {{-- Invited By --}}
                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">
                            Invited By
                        </small>

                        <strong>
                            BMRC Editorial Office
                        </strong>

                    </div>


                    {{-- Invitation Status --}}
                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">
                            Invitation Status
                        </small>

                        @switch($status)

                            @case('pending')
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-clock me-1"></i>
                                    Pending Response
                                </span>
                                @break

                            @case('accepted')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Accepted
                                </span>
                                @break

                            @case('declined')
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle me-1"></i>
                                    Declined
                                </span>
                                @break

                            @case('expired')
                                <span class="badge bg-secondary">
                                    <i class="bi bi-clock-history me-1"></i>
                                    Expired
                                </span>
                                @break

                            @case('cancelled')
                                <span class="badge bg-dark">
                                    Cancelled
                                </span>
                                @break

                            @default
                                <span class="badge bg-secondary">
                                    {{ ucfirst($status) }}
                                </span>

                        @endswitch

                    </div>


                    {{-- Responded Date --}}
                    @if($invitation->responded_at)

                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Responded On
                            </small>

                            <strong>
                                {{ \Carbon\Carbon::parse(
                                    $invitation->responded_at
                                )->format('d M Y, h:i A') }}
                            </strong>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- =================================================
            REVIEWER RESPONSE
        ================================================== --}}

        @if($status === 'pending' && !$isExpired)

            <div class="reviewer-card mb-4">

                <div class="card-header">
                    <i class="bi bi-reply me-2"></i>
                    Respond to Invitation
                </div>

                <div class="card-body">

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>

                        Please review the manuscript information
                        before accepting or declining this
                        review invitation.
                    </div>


                    <div class="row g-3">

                        {{-- =====================================
                            ACCEPT
                        ====================================== --}}

                        <div class="col-md-6">

                            <div class="border rounded p-4 h-100">

                                <h5 class="text-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Accept Invitation
                                </h5>

                                <p class="text-muted">
                                    Accept this invitation if you
                                    are available and have the
                                    appropriate expertise to
                                    review this manuscript.
                                </p>

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'reviewer.invitations.accept',
                                        $invitation->id
                                    ) }}"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="btn btn-success"
                                        onclick="return confirm(
                                            'Are you sure you want to accept this review invitation?'
                                        );"
                                    >
                                        <i class="bi bi-check-lg me-1"></i>
                                        Accept Review
                                    </button>

                                </form>

                            </div>

                        </div>


                        {{-- =====================================
                            DECLINE
                        ====================================== --}}

                        <div class="col-md-6">

                            <div class="border rounded p-4 h-100">

                                <h5 class="text-danger">
                                    <i class="bi bi-x-circle me-1"></i>
                                    Decline Invitation
                                </h5>

                                <p class="text-muted">
                                    If you are unable to review
                                    this manuscript, you may
                                    decline the invitation.
                                </p>

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'reviewer.invitations.decline',
                                        $invitation->id
                                    ) }}"
                                >

                                    @csrf

                                    <div class="mb-3">

                                        <label
                                            for="response_note"
                                            class="form-label"
                                        >
                                            Reason / Note
                                            <span class="text-muted">
                                                (Optional)
                                            </span>
                                        </label>

                                        <textarea
                                            name="response_note"
                                            id="response_note"
                                            rows="3"
                                            class="form-control"
                                            maxlength="2000"
                                            placeholder="You may provide a reason for declining..."
                                        >{{ old('response_note') }}</textarea>

                                    </div>

                                    <button
                                        type="submit"
                                        class="btn btn-outline-danger"
                                        onclick="return confirm(
                                            'Are you sure you want to decline this review invitation?'
                                        );"
                                    >
                                        <i class="bi bi-x-lg me-1"></i>
                                        Decline Review
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


        {{-- =================================================
            ACCEPTED INVITATION
        ================================================== --}}

        @elseif($status === 'accepted')

            <div class="alert alert-success">

                <div class="d-flex align-items-center">

                    <i
                        class="bi bi-check-circle-fill me-3"
                        style="font-size:28px;"
                    ></i>

                    <div>
                        <strong>
                            Review Invitation Accepted
                        </strong>

                        <div class="mt-1">
                            You have accepted this manuscript
                            review invitation.
                        </div>
                    </div>

                </div>

            </div>


            {{-- =============================================
                PEER REVIEW WORKSPACE
            ============================================== --}}

            <div class="reviewer-card mb-4">

                <div class="card-header">
                    <i class="bi bi-journal-check me-2"></i>
                    Peer Review Workspace
                </div>

                <div class="card-body">

                    {{-- =====================================
                        BLIND REVIEW NOTICE
                    ====================================== --}}

                    <div class="alert alert-warning">

                        <div class="d-flex">

                            <i
                                class="bi bi-shield-lock-fill me-3"
                                style="font-size:26px;"
                            ></i>

                            <div>

                                <strong>
                                    Blind Peer Review
                                </strong>

                                <div class="small mt-1">
                                    The peer-review workspace
                                    provides only reviewer-safe
                                    manuscript information.

                                    Author names, affiliations,
                                    contact information, ORCID,
                                    corresponding-author
                                    information and other
                                    identifying information are
                                    not available to reviewers.
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =====================================
                        REVIEW INFORMATION
                    ====================================== --}}

                    <div class="border rounded p-3 mb-4">

                        <div class="row g-3">

                            {{-- Manuscript ID --}}
                            <div class="col-md-6">

                                <small class="text-muted d-block mb-1">
                                    Manuscript ID
                                </small>

                                <strong class="text-primary">
                                    {{ $manuscript->manuscript_id
                                        ?? $invitation->manuscript_id }}
                                </strong>

                            </div>


                            {{-- Article Type --}}
                            <div class="col-md-6">

                                <small class="text-muted d-block mb-1">
                                    Article Type
                                </small>

                                <strong>
                                    {{ $manuscript
                                        ?->articleType
                                        ?->name
                                        ?? 'N/A' }}
                                </strong>

                            </div>


                            {{-- Review Deadline --}}
                            <div class="col-md-6">

                                <small class="text-muted d-block mb-1">
                                    Review Deadline
                                </small>

                                <strong>
                                    @if($invitation->review_deadline)

                                        {{ \Carbon\Carbon::parse(
                                            $invitation->review_deadline
                                        )->format('d M Y') }}

                                    @else
                                        Not specified
                                    @endif
                                </strong>

                            </div>


                            {{-- Review Status --}}
                            <div class="col-md-6">

                                <small class="text-muted d-block mb-1">
                                    Review Status
                                </small>

                                @if($reviewDeadlinePassed)

                                    <span class="badge bg-danger">
                                        <i class="bi bi-clock-history me-1"></i>
                                        Review Deadline Passed
                                    </span>

                                @else

                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Ready for Review
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- =====================================
                        REVIEW PROCESS
                    ====================================== --}}

                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-list-check me-1"></i>
                        BMRC Peer Review Process
                    </h6>


                    <div class="row g-3 mb-4">

                        {{-- Step 1 --}}
                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100 text-center">

                                <div class="text-primary mb-2">
                                    <i
                                        class="bi bi-file-earmark-text"
                                        style="font-size:28px;"
                                    ></i>
                                </div>

                                <strong>
                                    1. Review Manuscript
                                </strong>

                                <div class="small text-muted mt-1">
                                    Review the blinded manuscript
                                    and scientific information.
                                </div>

                            </div>

                        </div>


                        {{-- Step 2 --}}
                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100 text-center">

                                <div class="text-primary mb-2">
                                    <i
                                        class="bi bi-ui-checks-grid"
                                        style="font-size:28px;"
                                    ></i>
                                </div>

                                <strong>
                                    2. Complete Assessment
                                </strong>

                                <div class="small text-muted mt-1">
                                    Complete the structured
                                    BMRC peer-review form.
                                </div>

                            </div>

                        </div>


                        {{-- Step 3 --}}
                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100 text-center">

                                <div class="text-success mb-2">
                                    <i
                                        class="bi bi-send-check"
                                        style="font-size:28px;"
                                    ></i>
                                </div>

                                <strong>
                                    3. Submit Review
                                </strong>

                                <div class="small text-muted mt-1">
                                    Preview and submit your
                                    final recommendation.
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =====================================
                        CONFIDENTIALITY
                    ====================================== --}}

                    <div class="alert alert-light border mb-4">

                        <div class="d-flex">

                            <i class="bi bi-info-circle text-primary me-2"></i>

                            <div class="small">

                                <strong>
                                    Confidentiality:
                                </strong>

                                Manuscript materials are provided
                                solely for peer-review purposes.

                                Please do not share, distribute,
                                reproduce or use unpublished
                                information from the manuscript.

                            </div>

                        </div>

                    </div>


                    {{-- =====================================
                        START / CONTINUE REVIEW
                    ====================================== --}}

                    @if($reviewDeadlinePassed)

                        <div class="alert alert-danger mb-0">

                            <i class="bi bi-clock-history me-2"></i>

                            <strong>
                                Review Deadline Passed
                            </strong>

                            <div class="mt-1">
                                The review deadline has passed.
                                Please contact the BMRC Editorial
                                Office if you require an extension.
                            </div>

                        </div>

                    @else

                        <div
                            class="d-flex flex-wrap gap-3
                                   justify-content-between
                                   align-items-center"
                        >

                            <div>
                                <small class="text-muted">
                                    You may save your review as
                                    a draft and return before
                                    final submission.
                                </small>
                            </div>

                            <a
                                href="{{ route(
                                    'reviewer.peer-reviews.create',
                                    $invitation->id
                                ) }}"
                                class="btn btn-primary"
                            >
                                <i class="bi bi-pencil-square me-1"></i>
                                Start / Continue Peer Review
                            </a>

                        </div>

                    @endif

                </div>
            </div>

            {{-- =====================================================
    BLINDED MANUSCRIPT ATTACHMENTS
====================================================== --}}

<div class="reviewer-card mb-4">

    <div class="card-header">
        <i class="bi bi-paperclip me-2"></i>
        Manuscript Attachments
    </div>

    <div class="card-body">

        <div class="alert alert-light border">

            <i class="bi bi-shield-lock me-2 text-primary"></i>

            <strong>Blind Review Files</strong>

            <div class="small text-muted mt-1">
                Main manuscript and reviewer-appropriate supporting
                files are available below. Author-identifying
                administrative documents are excluded.
            </div>

        </div>


        {{-- =================================================
            PREPARE REVIEWER FILES
        ================================================== --}}

        @php

            /*
            |--------------------------------------------------------------------------
            | Reviewer-safe file types
            |--------------------------------------------------------------------------
            |
            | Main manuscript is explicitly included.
            |
            */

            $mainManuscriptTypes = [
                'main_manuscript',
                'main manuscript',
                'manuscript',
                'article',
                'article_file',
                'manuscript_file',
                'main_file',
            ];


            $supportingFileTypes = [
                'table',
                'tables',
                'figure',
                'figures',
                'supplementary',
                'supplementary_file',
                'supplementary_files',
            ];


            $allowedReviewerFileTypes = array_merge(
                $mainManuscriptTypes,
                $supportingFileTypes
            );


            /*
            |--------------------------------------------------------------------------
            | Files that should not be displayed
            |--------------------------------------------------------------------------
            */

            $unavailableFileStatuses = [
                'deleted',
                'replaced',
                'inactive',
                'archived',
            ];


            /*
            |--------------------------------------------------------------------------
            | Get manuscript files
            |--------------------------------------------------------------------------
            */

            $reviewerFiles = collect();


            if ($manuscript && $manuscript->files) {

                $reviewerFiles = $manuscript->files
                    ->filter(function ($file) use (
                        $allowedReviewerFileTypes,
                        $unavailableFileStatuses
                    ) {

                        $fileType = strtolower(
                            trim(
                                (string) ($file->file_type ?? '')
                            )
                        );


                        $fileStatus = strtolower(
                            trim(
                                (string) ($file->status ?? '')
                            )
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Remove unavailable files
                        |--------------------------------------------------------------------------
                        */

                        if (
                            in_array(
                                $fileStatus,
                                $unavailableFileStatuses,
                                true
                            )
                        ) {
                            return false;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Show reviewer-safe files
                        |--------------------------------------------------------------------------
                        */

                        return in_array(
                            $fileType,
                            $allowedReviewerFileTypes,
                            true
                        );

                    })

                    /*
                    |--------------------------------------------------------------------------
                    | Main manuscript first
                    |--------------------------------------------------------------------------
                    */

                    ->sortBy(function ($file) use ($mainManuscriptTypes) {

                        $fileType = strtolower(
                            trim(
                                (string) ($file->file_type ?? '')
                            )
                        );


                        return in_array(
                            $fileType,
                            $mainManuscriptTypes,
                            true
                        )
                            ? 0
                            : 1;

                    })

                    ->values();

            }

        @endphp


        {{-- =================================================
            DISPLAY FILES
        ================================================== --}}

        @if($reviewerFiles->count() > 0)

            <div class="list-group">

                @foreach($reviewerFiles as $file)

                    @php

                        $fileType = strtolower(
                            trim(
                                (string) ($file->file_type ?? '')
                            )
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | File Extension
                        |--------------------------------------------------------------------------
                        */

                        $extension = strtolower(
                            pathinfo(
                                $file->original_name
                                    ?? $file->stored_name
                                    ?? $file->file_path
                                    ?? '',
                                PATHINFO_EXTENSION
                            )
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Is Main Manuscript?
                        |--------------------------------------------------------------------------
                        */

                        $isMainManuscript = in_array(
                            $fileType,
                            $mainManuscriptTypes,
                            true
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Reviewer-safe Display Name
                        |--------------------------------------------------------------------------
                        */

                        if ($isMainManuscript) {

                            $displayName = 'Main Manuscript';

                        } elseif (
                            in_array(
                                $fileType,
                                ['table', 'tables'],
                                true
                            )
                        ) {

                            $displayName = 'Table';

                        } elseif (
                            in_array(
                                $fileType,
                                ['figure', 'figures'],
                                true
                            )
                        ) {

                            $displayName = 'Figure';

                        } elseif (
                            in_array(
                                $fileType,
                                [
                                    'supplementary',
                                    'supplementary_file',
                                    'supplementary_files',
                                ],
                                true
                            )
                        ) {

                            $displayName = 'Supplementary File';

                        } else {

                            $displayName = 'Manuscript File';

                        }

                    @endphp


                    <div class="list-group-item">

                        <div
                            class="
                                d-flex
                                flex-wrap
                                justify-content-between
                                align-items-center
                                gap-3
                            "
                        >

                            {{-- =========================================
                                LEFT: FILE INFORMATION
                            ========================================== --}}

                            <div class="d-flex align-items-center gap-3">


                                {{-- File Icon --}}

                                <div
                                    class="
                                        fs-3
                                        {{ $isMainManuscript
                                            ? 'text-success'
                                            : 'text-primary'
                                        }}
                                    "
                                >

                                    @if($extension === 'pdf')

                                        <i class="bi bi-file-earmark-pdf"></i>

                                    @elseif(
                                        $extension === 'doc'
                                        || $extension === 'docx'
                                    )

                                        <i class="bi bi-file-earmark-word"></i>

                                    @elseif(
                                        $extension === 'jpg'
                                        || $extension === 'jpeg'
                                        || $extension === 'png'
                                        || $extension === 'gif'
                                    )

                                        <i class="bi bi-file-earmark-image"></i>

                                    @elseif(
                                        $extension === 'xls'
                                        || $extension === 'xlsx'
                                    )

                                        <i class="bi bi-file-earmark-excel"></i>

                                    @else

                                        <i class="bi bi-file-earmark"></i>

                                    @endif

                                </div>


                                {{-- File Details --}}

                                <div>

                                    <div class="fw-semibold">

                                        {{ $displayName }}

                                        @if($isMainManuscript)

                                            <span class="badge bg-success ms-2">
                                                Main Article
                                            </span>

                                        @endif

                                    </div>


                                    <div class="small text-muted">

                                        @if($extension)

                                            {{ strtoupper($extension) }}

                                        @else

                                            File

                                        @endif


                                        @if(!empty($file->file_size))

                                            <span class="mx-1">•</span>

                                            {{ number_format(
                                                $file->file_size / 1024,
                                                1
                                            ) }} KB

                                        @endif

                                    </div>


                                    {{-- Optional File Type --}}

                                    <div class="small text-muted mt-1">

                                        @if($isMainManuscript)

                                            <i class="bi bi-file-text me-1"></i>

                                            Blinded manuscript for peer review

                                        @elseif(
                                            in_array(
                                                $fileType,
                                                ['table', 'tables'],
                                                true
                                            )
                                        )

                                            Supporting table

                                        @elseif(
                                            in_array(
                                                $fileType,
                                                ['figure', 'figures'],
                                                true
                                            )
                                        )

                                            Supporting figure

                                        @elseif(
                                            in_array(
                                                $fileType,
                                                [
                                                    'supplementary',
                                                    'supplementary_file',
                                                    'supplementary_files',
                                                ],
                                                true
                                            )
                                        )

                                            Supplementary material

                                        @endif

                                    </div>

                                </div>

                            </div>


                          {{-- =========================================
                                RIGHT: VIEW / DOWNLOAD BUTTONS
                            ========================================== --}}

                            <div class="d-flex gap-2 flex-wrap">

                                {{-- View File --}}
                                <a
                                    href="{{ route(
                                        'reviewer.peer-reviews.files.view',
                                        [
                                            'invitation' => $invitation->id,
                                            'file' => $file->id,
                                        ]
                                    ) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-primary"
                                    title="View {{ $displayName }}"
                                >
                                    <i class="bi bi-eye me-1"></i>
                                    View
                                </a>


                                {{-- Download File --}}
                                <a
                                    href="{{ route(
                                        'reviewer.peer-reviews.files.download',
                                        [
                                            'invitation' => $invitation->id,
                                            'file' => $file->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-success"
                                    title="Download {{ $displayName }}"
                                >
                                    <i class="bi bi-download me-1"></i>
                                    Download
                                </a>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="alert alert-warning mb-0">

                <i class="bi bi-exclamation-triangle me-2"></i>

                No reviewer-safe manuscript attachment is currently
                available.

            </div>

        @endif

    </div>

</div>

        {{-- =================================================
            DECLINED INVITATION
        ================================================== --}}

        @elseif($status === 'declined')

            <div class="alert alert-danger">

                <strong>

                    <i class="bi bi-x-circle me-1"></i>

                    Review Invitation Declined

                </strong>


                @if($invitation->response_note)

                    <div class="mt-2">

                        <strong>
                            Your note:
                        </strong>

                        {{ $invitation->response_note }}

                    </div>

                @endif

            </div>


        {{-- =================================================
            EXPIRED INVITATION
        ================================================== --}}

        @elseif($status === 'expired' || $isExpired)

            <div class="alert alert-secondary">

                <i class="bi bi-clock-history me-2"></i>

                This review invitation has expired and can
                no longer be accepted or declined.

            </div>


        {{-- =================================================
            CANCELLED INVITATION
        ================================================== --}}

        @elseif($status === 'cancelled')

            <div class="alert alert-dark">

                <i class="bi bi-x-octagon me-2"></i>

                This review invitation has been cancelled
                by the BMRC Editorial Office.

            </div>

        @endif

    </div>


    {{-- =====================================================
        RIGHT SIDEBAR
    ====================================================== --}}

    <div class="col-lg-4">


        {{-- =================================================
            STATUS CARD
        ================================================== --}}

        <div class="reviewer-card mb-4">

            <div class="card-header">

                <i class="bi bi-info-circle me-2"></i>

                Invitation Status

            </div>


            <div class="card-body text-center">

                @switch($status)


                    {{-- =====================================
                        PENDING
                    ====================================== --}}

                    @case('pending')

                        @if($isExpired)

                            <i
                                class="
                                    bi
                                    bi-clock-history
                                    text-secondary
                                "
                                style="font-size:45px;"
                            ></i>

                            <h5 class="mt-3">
                                Expired
                            </h5>

                            <p class="text-muted mb-0">
                                The response deadline
                                has passed.
                            </p>

                        @else

                            <i
                                class="
                                    bi
                                    bi-hourglass-split
                                    text-warning
                                "
                                style="font-size:45px;"
                            ></i>

                            <h5 class="mt-3">
                                Awaiting Response
                            </h5>

                            <p class="text-muted mb-0">
                                Please accept or decline this
                                review invitation.
                            </p>

                        @endif

                        @break


                    {{-- =====================================
                        ACCEPTED
                    ====================================== --}}

                    @case('accepted')

                        @if($reviewDeadlinePassed)

                            <i
                                class="
                                    bi
                                    bi-clock-history
                                    text-danger
                                "
                                style="font-size:45px;"
                            ></i>

                            <h5 class="mt-3">
                                Review Overdue
                            </h5>

                            <p class="text-muted mb-0">
                                The review deadline
                                has passed.
                            </p>

                        @else

                            <i
                                class="
                                    bi
                                    bi-check-circle
                                    text-success
                                "
                                style="font-size:45px;"
                            ></i>

                            <h5 class="mt-3">
                                Accepted
                            </h5>

                            <p class="text-muted mb-0">
                                You have agreed to review this
                                manuscript.
                            </p>

                        @endif

                        @break


                    {{-- =====================================
                        DECLINED
                    ====================================== --}}

                    @case('declined')

                        <i
                            class="
                                bi
                                bi-x-circle
                                text-danger
                            "
                            style="font-size:45px;"
                        ></i>

                        <h5 class="mt-3">
                            Declined
                        </h5>

                        @break


                    {{-- =====================================
                        EXPIRED
                    ====================================== --}}

                    @case('expired')

                        <i
                            class="
                                bi
                                bi-clock-history
                                text-secondary
                            "
                            style="font-size:45px;"
                        ></i>

                        <h5 class="mt-3">
                            Expired
                        </h5>

                        @break


                    {{-- =====================================
                        CANCELLED
                    ====================================== --}}

                    @case('cancelled')

                        <i
                            class="
                                bi
                                bi-x-octagon
                                text-dark
                            "
                            style="font-size:45px;"
                        ></i>

                        <h5 class="mt-3">
                            Cancelled
                        </h5>

                        @break


                    {{-- =====================================
                        DEFAULT
                    ====================================== --}}

                    @default

                        <i
                            class="
                                bi
                                bi-info-circle
                                text-secondary
                            "
                            style="font-size:45px;"
                        ></i>

                        <h5 class="mt-3">
                            {{ ucfirst($status) }}
                        </h5>

                @endswitch

            </div>

        </div>


        {{-- =================================================
            REVIEW DEADLINE CARD
        ================================================== --}}

        @if(
            $status === 'accepted'
            &&
            $invitation->review_deadline
        )

            <div class="reviewer-card mb-4">

                <div class="card-header">

                    <i class="bi bi-calendar-event me-2"></i>

                    Review Deadline

                </div>


                <div class="card-body text-center">

                    <div class="mb-2">

                        <i
                            class="
                                bi
                                bi-calendar-check
                                {{
                                    $reviewDeadlinePassed
                                        ? 'text-danger'
                                        : 'text-primary'
                                }}
                            "
                            style="font-size:35px;"
                        ></i>

                    </div>


                    <h5>

                        {{
                            \Carbon\Carbon::parse(
                                $invitation->review_deadline
                            )->format('d M Y')
                        }}

                    </h5>


                    @if($reviewDeadlinePassed)

                        <span class="badge bg-danger">
                            Deadline Passed
                        </span>

                    @else

                        <span class="badge bg-success">
                            Review in Progress
                        </span>

                    @endif

                </div>

            </div>

        @endif


        {{-- =================================================
            IMPORTANT INFORMATION
        ================================================== --}}

        <div class="reviewer-card mb-4">

            <div class="card-header">

                <i class="bi bi-shield-check me-2"></i>

                @if($status === 'accepted')

                    Reviewer Responsibilities

                @else

                    Before You Accept

                @endif

            </div>


            <div class="card-body">

                <ul class="mb-0 ps-3">

                    <li class="mb-2">

                        Confirm that the manuscript is within
                        your area of expertise.

                    </li>


                    <li class="mb-2">

                        Ensure that you have sufficient time
                        to complete the review.

                    </li>


                    <li class="mb-2">

                        Declare any potential conflict of
                        interest.

                    </li>


                    <li class="mb-2">

                        Maintain manuscript confidentiality.

                    </li>


                    @if($status === 'accepted')

                        <li class="mb-2">

                            Do not attempt to identify the
                            author(s) of the manuscript.

                        </li>


                        <li>

                            Do not include your own identifying
                            information in comments intended
                            for the author(s).

                        </li>

                    @endif

                </ul>

            </div>

        </div>


        {{-- =================================================
            BLIND REVIEW CARD
        ================================================== --}}

        @if($status === 'accepted')

            <div class="reviewer-card mb-4">

                <div class="card-header">

                    <i class="bi bi-shield-lock me-2"></i>

                    Blind Review

                </div>


                <div class="card-body">

                    <p class="small text-muted mb-3">

                        Author-identifying information is
                        intentionally excluded from the
                        reviewer workspace.

                    </p>


                    <div class="small">

                        <div class="mb-2">

                            <i
                                class="
                                    bi
                                    bi-x-circle
                                    text-danger
                                    me-1
                                "
                            ></i>

                            Author names

                        </div>


                        <div class="mb-2">

                            <i
                                class="
                                    bi
                                    bi-x-circle
                                    text-danger
                                    me-1
                                "
                            ></i>

                            Author affiliations

                        </div>


                        <div class="mb-2">

                            <i
                                class="
                                    bi
                                    bi-x-circle
                                    text-danger
                                    me-1
                                "
                            ></i>

                            Email / phone

                        </div>


                        <div class="mb-2">

                            <i
                                class="
                                    bi
                                    bi-x-circle
                                    text-danger
                                    me-1
                                "
                            ></i>

                            ORCID

                        </div>


                        <div>

                            <i
                                class="
                                    bi
                                    bi-x-circle
                                    text-danger
                                    me-1
                                "
                            ></i>

                            Corresponding author details

                        </div>

                    </div>

                </div>

            </div>

        @endif


        {{-- =================================================
            BACK BUTTON
        ================================================== --}}

        <a
            href="{{ route(
                'reviewer.invitations.index'
            ) }}"
            class="
                btn
                btn-outline-secondary
                w-100
            "
        >

            <i class="bi bi-arrow-left me-1"></i>

            Back to Review Invitations

        </a>

    </div>

</div>

@endsection