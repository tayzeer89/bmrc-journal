@extends('admin.layouts.app')

@section('title', 'Reviewer Selection')

@section('content')

<style>

/* ============================================================
   REVIEWER SELECTION PAGE - VISIBILITY FIX
============================================================ */

.reviewer-selection-page {
    color: #212529 !important;
}


/* ============================================================
   NORMAL WHITE BACKGROUND
============================================================ */

.reviewer-selection-page .card,
.reviewer-selection-page .card-body,
.reviewer-selection-page .card-footer {
    background-color: #ffffff !important;
    color: #212529 !important;
}

.reviewer-selection-page .card-body,
.reviewer-selection-page .card-body div,
.reviewer-selection-page .card-body p,
.reviewer-selection-page .card-body label,
.reviewer-selection-page .card-body strong,
.reviewer-selection-page .card-body h1,
.reviewer-selection-page .card-body h2,
.reviewer-selection-page .card-body h3,
.reviewer-selection-page .card-body h4,
.reviewer-selection-page .card-body h5,
.reviewer-selection-page .card-body h6 {
    color: #212529 !important;
}


/* ============================================================
   TEXT
============================================================ */

.reviewer-selection-page .text-dark {
    color: #212529 !important;
}

.reviewer-selection-page .text-muted,
.reviewer-selection-page small.text-muted,
.reviewer-selection-page div.text-muted,
.reviewer-selection-page p.text-muted {
    color: #6c757d !important;
}


/* ============================================================
   COLORED CARD HEADERS
============================================================ */

.reviewer-selection-page .card-header.bg-primary,
.reviewer-selection-page .card-header.bg-primary *,
.reviewer-selection-page .card-header.bg-success,
.reviewer-selection-page .card-header.bg-success *,
.reviewer-selection-page .card-header.bg-info,
.reviewer-selection-page .card-header.bg-info *,
.reviewer-selection-page .card-header.bg-secondary,
.reviewer-selection-page .card-header.bg-secondary * {
    color: #ffffff !important;
}


/* ============================================================
   WARNING HEADER
============================================================ */

.reviewer-selection-page .card-header.bg-warning,
.reviewer-selection-page .card-header.bg-warning * {
    color: #212529 !important;
}


/* ============================================================
   LIGHT HEADER
============================================================ */

.reviewer-selection-page .card-header.bg-light,
.reviewer-selection-page .card-header.bg-light * {
    color: #212529 !important;
}


/* ============================================================
   TABLE
============================================================ */

.reviewer-selection-page .table {
    background-color: #ffffff !important;
    color: #212529 !important;
}

.reviewer-selection-page .table tbody,
.reviewer-selection-page .table tbody tr,
.reviewer-selection-page .table tbody td {
    background-color: #ffffff !important;
    color: #212529 !important;
}

.reviewer-selection-page .table tbody td div,
.reviewer-selection-page .table tbody td strong,
.reviewer-selection-page .table tbody td span:not(.badge),
.reviewer-selection-page .table tbody td small:not(.badge) {
    color: #212529 !important;
}

.reviewer-selection-page .table tbody td .text-muted {
    color: #6c757d !important;
}


/* ============================================================
   TABLE HEADER
============================================================ */

.reviewer-selection-page .table thead th,
.reviewer-selection-page .thead-light th {
    background-color: #e9ecef !important;
    color: #212529 !important;
}


/* ============================================================
   FORM
============================================================ */

.reviewer-selection-page label {
    color: #212529 !important;
    font-weight: 600;
}

.reviewer-selection-page .form-control,
.reviewer-selection-page .form-control:focus,
.reviewer-selection-page select {
    background-color: #ffffff !important;
    color: #212529 !important;
}

.reviewer-selection-page option {
    background-color: #ffffff !important;
    color: #212529 !important;
}

.reviewer-selection-page .form-control::placeholder {
    color: #6c757d !important;
    opacity: 1;
}


/* ============================================================
   BADGES
============================================================ */

.reviewer-selection-page .badge-primary {
    background-color: #007bff !important;
    color: #ffffff !important;
}

.reviewer-selection-page .badge-success {
    background-color: #28a745 !important;
    color: #ffffff !important;
}

.reviewer-selection-page .badge-info {
    background-color: #17a2b8 !important;
    color: #ffffff !important;
}

.reviewer-selection-page .badge-danger {
    background-color: #dc3545 !important;
    color: #ffffff !important;
}

.reviewer-selection-page .badge-secondary {
    background-color: #6c757d !important;
    color: #ffffff !important;
}

.reviewer-selection-page .badge-dark {
    background-color: #343a40 !important;
    color: #ffffff !important;
}

.reviewer-selection-page .badge-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
}

.reviewer-selection-page .badge-light {
    background-color: #ffffff !important;
    color: #212529 !important;
    border: 1px solid #dee2e6 !important;
}


/* ============================================================
   ALERTS
============================================================ */

.reviewer-selection-page .alert-info,
.reviewer-selection-page .alert-info * {
    color: #0c5460 !important;
}

.reviewer-selection-page .alert-warning,
.reviewer-selection-page .alert-warning * {
    color: #856404 !important;
}

.reviewer-selection-page .alert-danger,
.reviewer-selection-page .alert-danger * {
    color: #721c24 !important;
}

.reviewer-selection-page .alert-success,
.reviewer-selection-page .alert-success * {
    color: #155724 !important;
}


/* ============================================================
   BUTTONS
============================================================ */

.reviewer-selection-page .btn-primary,
.reviewer-selection-page .btn-primary *,
.reviewer-selection-page .btn-success,
.reviewer-selection-page .btn-success *,
.reviewer-selection-page .btn-danger,
.reviewer-selection-page .btn-danger *,
.reviewer-selection-page .btn-secondary,
.reviewer-selection-page .btn-secondary * {
    color: #ffffff !important;
}

.reviewer-selection-page .btn-success:disabled {
    color: #ffffff !important;
    opacity: 0.65;
}


/* ============================================================
   PAGINATION
============================================================ */

.reviewer-selection-page .pagination .page-link {
    background-color: #ffffff !important;
    color: #007bff !important;
    border-color: #dee2e6 !important;
}

.reviewer-selection-page .pagination .page-item.active .page-link {
    background-color: #007bff !important;
    border-color: #007bff !important;
    color: #ffffff !important;
}

.reviewer-selection-page .pagination .page-item.disabled .page-link {
    background-color: #ffffff !important;
    color: #6c757d !important;
}


/* ============================================================
   REVIEWER CHECKBOX
============================================================ */

.reviewer-selection-page .reviewer-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
}


/* ============================================================
   SELECTED COUNTER
============================================================ */

.reviewer-selection-page #selectedReviewerCount {
    color: #212529 !important;
    font-weight: 700;
}


/* ============================================================
   SELECTION ROW
============================================================ */

.reviewer-selection-page .reviewer-selected-row td {
    background-color: #f1f8f4 !important;
}

</style>


<div class="container-fluid py-4 reviewer-selection-page">


    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1 text-dark">
                Reviewer Selection
            </h3>

            <p class="text-muted mb-0">
                Select suitable reviewers for peer review.
            </p>

        </div>


        <a
            href="{{ route('handling-editor.reviewer-selection.index') }}"
            class="btn btn-secondary"
        >
            <i class="bi bi-arrow-left"></i>
            Back
        </a>

    </div>
    
{{-- =========================================================
    VALIDATION ERRORS
========================================================== --}}

@if($errors->any())

    <div class="alert alert-danger">

        <strong>
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
        1. MANUSCRIPT INFORMATION
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary text-white">

            <strong>

                <i class="bi bi-file-earmark-text"></i>

                1. Manuscript Information

            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-lg-3 col-md-6 mb-3">

                    <div class="text-muted small">
                        Manuscript ID
                    </div>

                    <strong>

                        {{
                            $manuscript->manuscript_id
                            ?? $manuscript->id
                        }}

                    </strong>

                </div>


                <div class="col-lg-3 col-md-6 mb-3">

                    <div class="text-muted small">
                        Journal
                    </div>

                    <strong>

                        {{
                            $manuscript->journal->name
                            ?? 'N/A'
                        }}

                    </strong>

                </div>


                <div class="col-lg-3 col-md-6 mb-3">

                    <div class="text-muted small">
                        Article Type
                    </div>

                    <strong>

                        {{
                            $manuscript->articleType->name
                            ?? 'N/A'
                        }}

                    </strong>

                </div>


                <div class="col-lg-3 col-md-6 mb-3">

                    <div class="text-muted small">
                        Current Status
                    </div>

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

                </div>

            </div>


            <hr>


            <div class="mb-3">

                <div class="text-muted small">
                    Article Title
                </div>

                <h5 class="mb-0 text-dark">

                    {{ $manuscript->title }}

                </h5>

            </div>


            <div class="row">

                <div class="col-md-6 mb-3">

                    <div class="text-muted small">
                        Handling Editor
                    </div>

                    <strong>

                        {{
                            $manuscript
                                ->handlingEditor
                                ->name
                            ?? 'N/A'
                        }}

                    </strong>

                </div>


                <div class="col-md-6 mb-3">

                    <div class="text-muted small">
                        Workflow Stage
                    </div>

                    <span class="badge badge-success">

                        Reviewer Selection

                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        2. REVIEWER ASSIGNMENT STATUS
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-info text-white">

            <strong>

                <i class="bi bi-people-fill"></i>

                2. Reviewer Assignment Status

            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <div class="border rounded p-3 text-center">

                        <h3 class="text-dark mb-1">

                            {{ $activeReviewerCount }}

                        </h3>

                        <div class="text-muted">

                            Active Reviewers

                        </div>

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="border rounded p-3 text-center">

                        <h3 class="text-dark mb-1">

                            {{ $maximumReviewers }}

                        </h3>

                        <div class="text-muted">

                            Maximum Reviewers

                        </div>

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="border rounded p-3 text-center">

                        <h3 class="text-dark mb-1">

                            {{ $availableReviewerSlots }}

                        </h3>

                        <div class="text-muted">

                            Available Slots

                        </div>

                    </div>

                </div>

            </div>


            @if($availableReviewerSlots > 0)

                <div class="alert alert-info mb-0">

                    You can invite minimum

                    <strong>1</strong>

                    and maximum

                    <strong>
                        {{ $availableReviewerSlots }}
                    </strong>

                    additional reviewer(s).

                </div>

            @else

                <div class="alert alert-warning mb-0">

                    <strong>
                        Maximum reviewer limit reached.
                    </strong>

                    This manuscript already has

                    <strong>
                        {{ $activeReviewerCount }}
                    </strong>

                    active reviewers.

                    You can invite another reviewer only after
                    a reviewer declines, the invitation expires,
                    or the invitation is cancelled.

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        3. REVIEWER INVITATIONS
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-secondary text-white">

            <strong>

                <i class="bi bi-envelope"></i>

                3. Reviewer Invitations

            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0">

                    <thead class="thead-light">

                        <tr>

                            <th>
                                Reviewer
                            </th>

                            <th>
                                Institution
                            </th>

                            <th>
                                Speciality
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Invited
                            </th>

                            <th>
                                Expiry
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(
                            $manuscript->reviewerInvitations
                            as $invitation
                        )

                            @php

                                $statusClass = match(
                                    $invitation->status
                                ) {
                                    'pending'   => 'badge-warning',
                                    'accepted'  => 'badge-success',
                                    'declined'  => 'badge-danger',
                                    'expired'   => 'badge-secondary',
                                    'cancelled' => 'badge-dark',
                                    default     => 'badge-light',
                                };

                            @endphp


                            <tr>

                                <td>

                                    <strong>

                                        {{
                                            $invitation
                                                ->reviewer
                                                ->name
                                            ?? 'N/A'
                                        }}

                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        {{
                                            $invitation
                                                ->reviewer
                                                ->email
                                            ?? ''
                                        }}

                                    </small>

                                </td>


                                <td>

                                    {{
                                        $invitation
                                            ->reviewer
                                            ?->profile
                                            ?->institution
                                        ?? 'N/A'
                                    }}

                                </td>


                                <td>

                                    {{
                                        $invitation
                                            ->reviewer
                                            ?->profile
                                            ?->speciality
                                        ?? 'N/A'
                                    }}

                                </td>


                                <td>

                                    <span class="badge {{ $statusClass }}">

                                        {{
                                            ucfirst(
                                                $invitation->status
                                            )
                                        }}

                                    </span>

                                </td>


                                <td>

                                    {{
                                        $invitation
                                            ->invited_at
                                            ?->format('d M Y')
                                        ?? 'N/A'
                                    }}

                                </td>


                                <td>

                                    {{
                                        $invitation
                                            ->expires_at
                                            ?->format('d M Y')
                                        ?? 'N/A'
                                    }}

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center text-muted py-4"
                                >

                                    No reviewer invitations
                                    have been sent yet.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
        ONLY SHOW SELECTION IF SLOT AVAILABLE
    ========================================================== --}}

    @if($availableReviewerSlots > 0)


        {{-- =====================================================
            4. SEARCH REVIEWERS
        ====================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-light">

                <strong class="text-dark">

                    <i class="bi bi-search"></i>

                    4. Search Existing Reviewer Database

                </strong>

            </div>


            <div class="card-body">

                <form
                    method="GET"
                    action="{{
                        route(
                            'handling-editor.reviewer-selection.show',
                            $manuscript->id
                        )
                    }}"
                >

                    <div class="row">


                        {{-- Search --}}

                        <div class="col-lg-3 col-md-6 mb-3">

                            <label>
                                Search Reviewer
                            </label>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Name, email, institution..."
                            >

                        </div>


                        {{-- Speciality --}}

                        <div class="col-lg-3 col-md-6 mb-3">

                            <label>
                                Speciality
                            </label>

                            <select
                                name="speciality"
                                class="form-control"
                            >

                                <option value="">
                                    All Specialities
                                </option>

                                @foreach(
                                    $specialities
                                    as $speciality
                                )

                                    <option
                                        value="{{ $speciality }}"
                                        {{
                                            request('speciality')
                                            === $speciality
                                            ? 'selected'
                                            : ''
                                        }}
                                    >

                                        {{ $speciality }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Country --}}

                        <div class="col-lg-3 col-md-6 mb-3">

                            <label>
                                Country
                            </label>

                            <select
                                name="country"
                                class="form-control"
                            >

                                <option value="">
                                    All Countries
                                </option>

                                @foreach(
                                    $countries
                                    as $country
                                )

                                    <option
                                        value="{{ $country }}"
                                        {{
                                            request('country')
                                            === $country
                                            ? 'selected'
                                            : ''
                                        }}
                                    >

                                        {{ $country }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Designation --}}

                        <div class="col-lg-3 col-md-6 mb-3">

                            <label>
                                Designation
                            </label>

                            <select
                                name="designation"
                                class="form-control"
                            >

                                <option value="">
                                    All Designations
                                </option>

                                @foreach(
                                    $designations
                                    as $designation
                                )

                                    <option
                                        value="{{ $designation }}"
                                        {{
                                            request('designation')
                                            === $designation
                                            ? 'selected'
                                            : ''
                                        }}
                                    >

                                        {{ $designation }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-search"></i>

                        Search

                    </button>


                    <a
                        href="{{
                            route(
                                'handling-editor.reviewer-selection.show',
                                $manuscript->id
                            )
                        }}"
                        class="btn btn-secondary"
                    >

                        Reset

                    </a>

                </form>

            </div>

        </div>


        {{-- =====================================================
            5. ELIGIBLE REVIEWERS
        ====================================================== --}}

        <form
            id="reviewerSelectionForm"
            method="POST"
            action="{{
                route(
                    'handling-editor.reviewer-selection.invite',
                    $manuscript->id
                )
            }}"
        >

            @csrf


            <div class="card shadow-sm mb-4">

                <div class="card-header bg-success text-white">

                    <div class="d-flex justify-content-between align-items-center">

                        <strong>

                            <i class="bi bi-people-fill"></i>

                            5. Eligible Reviewers

                        </strong>


                        <span class="badge badge-light">

                            {{ $reviewers->total() }}

                            Available

                        </span>

                    </div>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover mb-0">

                            <thead class="thead-light">

                                <tr>

                                    <th width="70" class="text-center">
                                        Select
                                    </th>

                                    <th>
                                        Reviewer
                                    </th>

                                    <th>
                                        Professional Information
                                    </th>

                                    <th>
                                        Speciality / Expertise
                                    </th>

                                    <th>
                                        Experience
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse(
                                    $reviewers
                                    as $reviewer
                                )

                                    @php

                                        $profile =
                                            $reviewer->profile;

                                    @endphp


                                    <tr class="reviewer-row">

                                        <td class="text-center align-middle">

                                            <input
                                                type="checkbox"
                                                name="reviewer_ids[]"
                                                value="{{ $reviewer->id }}"
                                                class="reviewer-checkbox"
                                            >

                                        </td>


                                        {{-- Reviewer --}}

                                        <td>

                                            <strong>

                                                {{ $reviewer->name }}

                                            </strong>

                                            <br>

                                            <small class="text-muted">

                                                {{ $reviewer->email }}

                                            </small>


                                            @if($profile?->country)

                                                <br>

                                                <span class="badge badge-light mt-1">

                                                    {{ $profile->country }}

                                                </span>

                                            @endif

                                        </td>


                                        {{-- Professional Information --}}

                                        <td>

                                            @if($profile?->designation)

                                                <strong>

                                                    {{
                                                        $profile
                                                            ->designation
                                                    }}

                                                </strong>

                                            @else

                                                <span class="text-muted">
                                                    Designation N/A
                                                </span>

                                            @endif


                                            @if($profile?->department)

                                                <br>

                                                <span>

                                                    {{
                                                        $profile
                                                            ->department
                                                    }}

                                                </span>

                                            @endif


                                            @if($profile?->institution)

                                                <br>

                                                <small class="text-muted">

                                                    {{
                                                        $profile
                                                            ->institution
                                                    }}

                                                </small>

                                            @endif

                                        </td>


                                        {{-- Speciality / Expertise --}}

                                        <td>

                                            @if($profile?->speciality)

                                                <span class="badge badge-primary">

                                                    {{
                                                        $profile
                                                            ->speciality
                                                    }}

                                                </span>

                                            @else

                                                <span class="text-muted">

                                                    Speciality N/A

                                                </span>

                                            @endif


                                            @if($profile?->sub_speciality)

                                                <div class="mt-2">

                                                    <small>

                                                        <strong>
                                                            Sub-speciality:
                                                        </strong>

                                                        {{
                                                            $profile
                                                                ->sub_speciality
                                                        }}

                                                    </small>

                                                </div>

                                            @endif


                                            @if($profile?->primary_expertise)

                                                <div class="mt-2">

                                                    <small>

                                                        <strong>
                                                            Expertise:
                                                        </strong>

                                                        {{
                                                            $profile
                                                                ->primary_expertise
                                                        }}

                                                    </small>

                                                </div>

                                            @endif

                                        </td>


                                        {{-- Experience --}}

                                        <td>

                                            @if($profile?->years_of_experience)

                                                <div>

                                                    <strong>

                                                        {{
                                                            $profile
                                                                ->years_of_experience
                                                        }}

                                                    </strong>

                                                    years

                                                </div>

                                            @else

                                                <span class="text-muted">
                                                    Experience N/A
                                                </span>

                                            @endif


                                            @if($profile?->publication_count)

                                                <div>

                                                    <small class="text-muted">

                                                        Publications:

                                                        {{
                                                            $profile
                                                                ->publication_count
                                                        }}

                                                    </small>

                                                </div>

                                            @endif


                                            @if($profile?->external_reviews_completed)

                                                <div>

                                                    <small class="text-muted">

                                                        Reviews:

                                                        {{
                                                            $profile
                                                                ->external_reviews_completed
                                                        }}

                                                    </small>

                                                </div>

                                            @endif

                                        </td>

                                    </tr>


                                @empty

                                    <tr>

                                        <td
                                            colspan="5"
                                            class="text-center text-muted py-4"
                                        >

                                            No eligible reviewers found.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- Pagination --}}

                @if($reviewers->hasPages())

                    <div class="card-footer">

                        {{
                            $reviewers
                                ->appends(
                                    request()->query()
                                )
                                ->links(
                                    'pagination::bootstrap-4'
                                )
                        }}

                    </div>

                @endif

            </div>


            {{-- =================================================
                6. INVITATION DETAILS
            ================================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-warning">

                    <strong>

                        <i class="bi bi-calendar-check"></i>

                        6. Invitation Details

                    </strong>

                </div>


                <div class="card-body">

                    <div class="row">


                        {{-- Invitation Date --}}

                        <div class="col-md-4 mb-3">

                            <div class="text-muted small">

                                Invitation Date

                            </div>

                            <strong>

                                {{
                                    $invitationDate
                                        ->format('d M Y')
                                }}

                            </strong>

                        </div>


                        {{-- Invitation Expiry --}}

                        <div class="col-md-4 mb-3">

                            <div class="text-muted small">

                                Invitation Expires

                            </div>

                            <strong>

                                {{
                                    $invitationExpiryDate
                                        ->format('d M Y')
                                }}

                            </strong>

                            <div class="small text-muted">

                                3 days from invitation

                            </div>

                        </div>


                        {{-- Review Deadline --}}

                        <div class="col-md-4 mb-3">

                            <div class="text-muted small">

                                Review Deadline

                            </div>

                            <strong>

                                {{
                                    $reviewDueDate
                                        ->format('d M Y')
                                }}

                            </strong>

                            <div class="small text-muted">

                                15 days from initial invitation

                            </div>

                        </div>

                    </div>


                    <div class="alert alert-info mb-0">

                        <strong>
                            Reviewer Selection Rule:
                        </strong>

                        Select minimum

                        <strong>1</strong>

                        and maximum

                        <strong>
                            {{ $availableReviewerSlots }}
                        </strong>

                        reviewer(s).

                        The manuscript cannot have more than

                        <strong>
                            3 active reviewers
                        </strong>

                        at any time.

                    </div>

                </div>

            </div>


            {{-- =================================================
                SEND INVITATION
            ================================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-body">

                    <div class="row align-items-center">

                        <div class="col-md-6 mb-3 mb-md-0">

                            <div class="text-dark">

                                Selected Reviewers:

                                <strong id="selectedReviewerCount">
                                    0
                                </strong>

                                /

                                <strong>
                                    {{ $availableReviewerSlots }}
                                </strong>

                            </div>


                            <small class="text-muted">

                                Minimum 1 reviewer must be selected.

                            </small>

                        </div>


                        <div class="col-md-6 text-md-right">

                            <button
                                type="submit"
                                id="sendInvitationButton"
                                class="btn btn-success"
                                aria-disabled="true"
                            >

                                <i class="bi bi-send"></i>

                                Send Reviewer Invitation(s)

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </form>


    @else


        {{-- =====================================================
            REVIEWER LIMIT REACHED
        ====================================================== --}}

        <div class="card shadow-sm">

            <div class="card-body text-center py-5">

                <i
                    class="bi bi-people-fill text-muted"
                    style="font-size: 42px;"
                ></i>


                <h4 class="mt-3 text-dark">

                    Reviewer Limit Reached

                </h4>


                <p class="text-muted mb-0">

                    This manuscript currently has

                    <strong>
                        {{ $activeReviewerCount }}
                    </strong>

                    active reviewers.

                    No additional reviewer can be
                    invited at this time.

                </p>

            </div>

        </div>

    @endif


</div>


{{-- =============================================================
    JAVASCRIPT
============================================================= --}}

<script>

(function () {

    /*
    |--------------------------------------------------------------------------
    | Initialize Reviewer Selection
    |--------------------------------------------------------------------------
    */

    function initReviewerSelection()
    {
        const form =
            document.getElementById(
                'reviewerSelectionForm'
            );


        /*
        |--------------------------------------------------------------------------
        | No Form
        |--------------------------------------------------------------------------
        */

        if (!form) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Elements
        |--------------------------------------------------------------------------
        */

        const checkboxes =
            Array.from(
                form.querySelectorAll(
                    '.reviewer-checkbox'
                )
            );


        const counter =
            document.getElementById(
                'selectedReviewerCount'
            );


        const sendButton =
            document.getElementById(
                'sendInvitationButton'
            );


        /*
        |--------------------------------------------------------------------------
        | Maximum Reviewers Available NOW
        |--------------------------------------------------------------------------
        */

        const maxReviewers =
            Number(
                {{ (int) $availableReviewerSlots }}
            );


        /*
        |--------------------------------------------------------------------------
        | Update Reviewer Selection
        |--------------------------------------------------------------------------
        */

        function updateReviewerSelection()
        {
            /*
            |--------------------------------------------------------------------------
            | Selected Checkboxes
            |--------------------------------------------------------------------------
            */

            const selected =
                checkboxes.filter(
                    function (checkbox) {

                        return checkbox.checked;

                    }
                );


            const count =
                selected.length;


            /*
            |--------------------------------------------------------------------------
            | Update Counter
            |--------------------------------------------------------------------------
            */

            if (counter) {

                counter.textContent =
                    count;

            }


            /*
            |--------------------------------------------------------------------------
            | Selected Row Highlight
            |--------------------------------------------------------------------------
            */

            checkboxes.forEach(
                function (checkbox) {

                    const row =
                        checkbox.closest('tr');


                    if (row) {

                        if (checkbox.checked) {

                            row.classList.add(
                                'reviewer-selected-row'
                            );

                        } else {

                            row.classList.remove(
                                'reviewer-selected-row'
                            );

                        }

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Disable Unselected Checkboxes At Maximum
            |--------------------------------------------------------------------------
            */

            checkboxes.forEach(
                function (checkbox) {

                    if (
                        maxReviewers > 0
                        &&
                        count >= maxReviewers
                        &&
                        !checkbox.checked
                    ) {

                        checkbox.disabled =
                            true;

                    } else {

                        checkbox.disabled =
                            false;

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Send Button
            |--------------------------------------------------------------------------
            |
            | 0 selected = disabled
            | 1 selected = enabled
            | 2 selected = enabled
            | 3 selected = enabled
            |
            */

            if (sendButton) {

                const shouldDisable =
                    maxReviewers < 1
                    ||
                    count < 1
                    ||
                    count > maxReviewers;


                sendButton.disabled =
                    shouldDisable;


                sendButton.setAttribute(
                    'aria-disabled',
                    shouldDisable
                        ? 'true'
                        : 'false'
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Checkbox Change
        |--------------------------------------------------------------------------
        */

        checkboxes.forEach(
            function (checkbox) {

                checkbox.addEventListener(
                    'change',
                    function () {

                        updateReviewerSelection();

                    }
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Form Submit
        |--------------------------------------------------------------------------
        */

        form.addEventListener(
            'submit',
            function (event) {

                /*
                |--------------------------------------------------------------------------
                | Selected Reviewer Count
                |--------------------------------------------------------------------------
                */

                const selectedCount =
                    checkboxes.filter(
                        function (checkbox) {

                            return checkbox.checked;

                        }
                    ).length;


                /*
                |--------------------------------------------------------------------------
                | Validate Minimum / Maximum
                |--------------------------------------------------------------------------
                */

                if (
                    selectedCount < 1
                    ||
                    selectedCount > maxReviewers
                ) {

                    event.preventDefault();


                    alert(
                        'Please select minimum 1 and maximum ' +
                        maxReviewers +
                        ' reviewer(s).'
                    );


                    updateReviewerSelection();


                    return false;

                }


                /*
                |--------------------------------------------------------------------------
                | Confirmation
                |--------------------------------------------------------------------------
                */

                const confirmed =
                    window.confirm(
                        'Are you sure you want to send invitation(s) to ' +
                        selectedCount +
                        ' reviewer(s)?'
                    );


                if (!confirmed) {

                    event.preventDefault();


                    updateReviewerSelection();


                    return false;

                }


                /*
                |--------------------------------------------------------------------------
                | Prevent Double Submission
                |--------------------------------------------------------------------------
                */

                if (sendButton) {

                    sendButton.disabled =
                        true;


                    sendButton.setAttribute(
                        'aria-disabled',
                        'true'
                    );


                    sendButton.innerHTML =
                        '<i class="bi bi-hourglass-split"></i> Sending...';

                }


                return true;

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Initial State
        |--------------------------------------------------------------------------
        */

        updateReviewerSelection();

    }


    /*
    |--------------------------------------------------------------------------
    | Run Script
    |--------------------------------------------------------------------------
    */

    if (
        document.readyState ===
        'loading'
    ) {

        document.addEventListener(
            'DOMContentLoaded',
            initReviewerSelection
        );

    } else {

        initReviewerSelection();

    }

})();

</script>

@endsection