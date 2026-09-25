@extends('admin.layouts.app')

@section('title', 'Reviewer Selection')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Reviewer Invitation Timeline
    |--------------------------------------------------------------------------
    |
    | Invitation expiry : 3 days from initial invitation
    | Review due         : 15 days from initial invitation
    |
    | These dates are DISPLAY ONLY.
    | The controller must calculate them again server-side.
    |
    */

    $invitationDate = now();

    $invitationExpiryDate = now()
        ->copy()
        ->addDays(3);

    $reviewDueDate = now()
        ->copy()
        ->addDays(15);


    /*
    |--------------------------------------------------------------------------
    | Keywords
    |--------------------------------------------------------------------------
    */

    $keywords = [];

    if (!empty($manuscript->keywords)) {

        $keywords = is_array($manuscript->keywords)
            ? $manuscript->keywords
            : array_filter(
                array_map(
                    'trim',
                    explode(
                        ',',
                        (string) $manuscript->keywords
                    )
                )
            );
    }
@endphp


<style>
/* =========================================================
   FINAL FIX - ELIGIBLE REVIEWER TABLE TEXT
========================================================= */

/* Entire reviewer table */
#reviewerSelectionForm .table,
#reviewerSelectionForm .table tbody,
#reviewerSelectionForm .table tbody tr,
#reviewerSelectionForm .table tbody td {
    color: #212529 !important;
}

/* All normal text inside table cells */
#reviewerSelectionForm .table tbody td div,
#reviewerSelectionForm .table tbody td strong,
#reviewerSelectionForm .table tbody td span:not(.badge),
#reviewerSelectionForm .table tbody td p {
    color: #212529 !important;
}

/* Muted information */
#reviewerSelectionForm .table tbody td .text-muted {
    color: #6c757d !important;
}

/* Table headings */
#reviewerSelectionForm .table thead th {
    background-color: #e9ecef !important;
    color: #212529 !important;
}

/* =========================================================
   SPECIALITY BADGE
========================================================= */

#reviewerSelectionForm .badge-primary {
    background-color: #007bff !important;
    color: #ffffff !important;
}

#reviewerSelectionForm .badge-primary * {
    color: #ffffff !important;
}

/* Available badge */
#reviewerSelectionForm .badge-success {
    background-color: #28a745 !important;
    color: #ffffff !important;
}

#reviewerSelectionForm .badge-success * {
    color: #ffffff !important;
}

/* =========================================================
   PAGINATION FIX
========================================================= */

#reviewerSelectionForm .pagination {
    margin-bottom: 0;
}

#reviewerSelectionForm .pagination .page-item .page-link {
    background-color: #ffffff !important;
    color: #007bff !important;
    border-color: #dee2e6 !important;
}

/* Current active page */
#reviewerSelectionForm .pagination .page-item.active .page-link {
    background-color: #007bff !important;
    border-color: #007bff !important;
    color: #ffffff !important;
}

/* Previous/Next disabled */
#reviewerSelectionForm .pagination .page-item.disabled .page-link {
    background-color: #ffffff !important;
    color: #6c757d !important;
}

/* Hover */
#reviewerSelectionForm .pagination .page-item:not(.active):not(.disabled)
.page-link:hover {
    background-color: #e9ecef !important;
    color: #0056b3 !important;
}

/* Pagination information:
   Showing 1 to 20 of XX reviewers */
#reviewerSelectionForm .card-footer,
#reviewerSelectionForm .card-footer small,
#reviewerSelectionForm .card-footer .text-muted {
    color: #6c757d !important;
}

#reviewerSelectionForm .card-footer strong {
    color: #212529 !important;
}
</style>

<div class="container-fluid py-4">


    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

        <div>

            <h4 class="mb-1">

                <i class="bi bi-person-search me-1"></i>

                Reviewer Selection

            </h4>


            <div class="text-muted">

                Manuscript:

                <strong>
                    {{ $manuscript->manuscript_id }}
                </strong>

            </div>

        </div>


        <div>

            <a
                href="{{ route(
                    'handling-editor.reviewer-selection.index'
                ) }}"
                class="btn btn-outline-secondary"
            >

                <i class="bi bi-arrow-left me-1"></i>

                Back to Reviewer Selection

            </a>

        </div>

    </div>



    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

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

            <i class="bi bi-exclamation-triangle me-1"></i>

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

                <i class="bi bi-journal-text me-1"></i>

                1. Manuscript Information

            </strong>

        </div>


        <div class="card-body">

            <div class="row">


                {{-- Manuscript ID --}}

                <div class="col-lg-3 col-md-6 mb-3">

                    <div class="text-muted small">
                        Manuscript ID
                    </div>

                    <div class="font-weight-bold">
                        {{ $manuscript->manuscript_id }}
                    </div>

                </div>



                {{-- Journal --}}

                <div class="col-lg-3 col-md-6 mb-3">

                    <div class="text-muted small">
                        Journal
                    </div>

                    <div class="font-weight-bold">

                        {{ $manuscript->journal->name ?? 'N/A' }}

                    </div>

                </div>



                {{-- Article Type --}}

                <div class="col-lg-3 col-md-6 mb-3">

                    <div class="text-muted small">
                        Article Type
                    </div>

                    <div class="font-weight-bold">

                        {{ $manuscript->articleType->name ?? 'N/A' }}

                    </div>

                </div>



                {{-- Status --}}

             <div class="col-lg-3 col-md-6 mb-3">

                <div class="text-muted small">
                    Current Status
                </div>

                <span class="badge badge-info bg-info text-white">

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



                {{-- Article Title --}}

                <div class="col-12 mb-3">

                    <div class="text-muted small mb-1">
                        Article Title
                    </div>

                    <h5 class="mb-0">

                        {{ $manuscript->title }}

                    </h5>

                </div>



                {{-- Keywords --}}

                @if(count($keywords))

                    <div class="col-12 mb-3">

                        <div class="text-muted small mb-2">
                            Keywords
                        </div>


                        @foreach($keywords as $keyword)

                            @php

                                $keywordText = is_array($keyword)
                                    ? (
                                        $keyword['name']
                                        ?? $keyword['keyword']
                                        ?? implode(', ', $keyword)
                                    )
                                    : $keyword;

                            @endphp


                            <span class="badge badge-light border mr-1 mb-1">

                                {{ $keywordText }}

                            </span>

                        @endforeach

                    </div>

                @endif



                {{-- Abstract --}}

                @if(!empty($manuscript->abstract))

                    <div class="col-12">

                        <div class="text-muted small mb-2">
                            Abstract
                        </div>

                        <div class="border rounded bg-light p-3">

                            {!! $manuscript->abstract !!}

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>



    {{-- =========================================================
         2. HANDLING EDITOR
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>

                <i class="bi bi-person-badge me-1"></i>

                2. Handling Editor

            </strong>

        </div>


        <div class="card-body">

            <div class="row">


                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Name
                    </div>

                    <div class="font-weight-bold">

                        {{ $manuscript->handlingEditor->name ?? 'N/A' }}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Email
                    </div>

                    <div>

                        {{ $manuscript->handlingEditor->email ?? 'N/A' }}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Workflow Stage
                    </div>

                    <span class="badge bg-success text-white">
                        Reviewer Selection
                    </span>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
         3. CURRENT REVIEWER INVITATIONS
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>

                    <i class="bi bi-envelope me-1"></i>

                    3. Current Reviewer Invitations

                </strong>


                <span class="badge badge-secondary">

                    {{
                        $manuscript
                            ->reviewerInvitations
                            ->count()
                    }}

                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-bordered table-hover mb-0">

                <thead class="thead-light">

                    <tr>

                        <th width="50">
                            #
                        </th>

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

                            $invitationStatus =
                                $invitation->status
                                ?? 'pending';

                        @endphp


                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>


                            <td>

                                <div class="font-weight-bold">

                                    {{
                                        $invitation
                                            ->reviewer
                                            ->name
                                        ?? 'N/A'
                                    }}

                                </div>


                                <div class="small text-muted">

                                    {{
                                        $invitation
                                            ->reviewer
                                            ->email
                                        ?? ''
                                    }}

                                </div>

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

                                @if($invitationStatus === 'accepted')

                                    <span class="badge badge-success">
                                        Accepted
                                    </span>

                                @elseif($invitationStatus === 'declined')

                                    <span class="badge badge-danger">
                                        Declined
                                    </span>

                                @elseif($invitationStatus === 'pending')

                                    <span class="badge badge-warning">
                                        Pending
                                    </span>

                                @elseif($invitationStatus === 'expired')

                                    <span class="badge badge-secondary">
                                        Expired
                                    </span>

                                @else

                                    <span class="badge badge-secondary">

                                        {{
                                            ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $invitationStatus
                                                )
                                            )
                                        }}

                                    </span>

                                @endif

                            </td>


                            <td>

                                {{
                                    $invitation->invited_at
                                        ?->format('d M Y')
                                    ?? 'N/A'
                                }}

                            </td>


                            <td>

                                {{
                                    $invitation->expires_at
                                        ?->format('d M Y')
                                    ?? 'N/A'
                                }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center text-muted py-4"
                            >

                                <i class="bi bi-envelope-open d-block mb-2"></i>

                                No reviewer has been invited yet.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>



    {{-- =========================================================
         4. SEARCH REVIEWERS
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>

                <i class="bi bi-search me-1"></i>

                4. Search Existing Reviewer Database

            </strong>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route(
                    'handling-editor.reviewer-selection.show',
                    $manuscript->id
                ) }}"
            >

                <div class="row">


                    {{-- Search --}}

                    <div class="col-lg-4 col-md-6 mb-3">

                        <label class="font-weight-bold">

                            Search

                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Name, email, institution, expertise..."
                        >

                    </div>



                    {{-- Speciality --}}

                    <div class="col-lg-3 col-md-6 mb-3">

                        <label class="font-weight-bold">

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
                                $specialities ?? []
                                as $speciality
                            )

                                <option
                                    value="{{ $speciality }}"
                                    @selected(
                                        request('speciality')
                                        === $speciality
                                    )
                                >

                                    {{ $speciality }}

                                </option>

                            @endforeach

                        </select>

                    </div>



                    {{-- Country --}}

                    <div class="col-lg-2 col-md-6 mb-3">

                        <label class="font-weight-bold">

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
                                $countries ?? []
                                as $country
                            )

                                <option
                                    value="{{ $country }}"
                                    @selected(
                                        request('country')
                                        === $country
                                    )
                                >

                                    {{ $country }}

                                </option>

                            @endforeach

                        </select>

                    </div>



                    {{-- Designation --}}

                    <div class="col-lg-3 col-md-6 mb-3">

                        <label class="font-weight-bold">

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
                                $designations ?? []
                                as $designation
                            )

                                <option
                                    value="{{ $designation }}"
                                    @selected(
                                        request('designation')
                                        === $designation
                                    )
                                >

                                    {{ $designation }}

                                </option>

                            @endforeach

                        </select>

                    </div>



                    {{-- Search Buttons --}}

                    <div class="col-12">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="bi bi-search me-1"></i>

                            Search Reviewers

                        </button>


                        <a
                            href="{{ route(
                                'handling-editor.reviewer-selection.show',
                                $manuscript->id
                            ) }}"
                            class="btn btn-outline-secondary ml-1"
                        >

                            <i class="bi bi-arrow-counterclockwise me-1"></i>

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>



    {{-- =========================================================
         5. REVIEWER SELECTION FORM
    ========================================================== --}}

    <form
        method="POST"
        action="{{ route(
            'handling-editor.reviewer-selection.invite',
            $manuscript->id
        ) }}"
        id="reviewerSelectionForm"
    >

        @csrf


        {{-- =====================================================
             ELIGIBLE REVIEWERS
        ====================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-success text-white">

                <div class="d-flex justify-content-between align-items-center">

                    <strong>

                        <i class="bi bi-people me-1"></i>

                        5. Eligible Reviewers

                    </strong>


                    <span class="badge text-dark border">

                        {{ $reviewers->total() }}
                        Available

                    </span>

                </div>

            </div>


            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0">

                    <thead class="thead-light">

                        <tr>

                            <th
                                width="70"
                                class="text-center"
                            >
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

                        @forelse($reviewers as $reviewer)

                            @php

                                $profile = $reviewer->profile;

                                $oldReviewerIds =
                                    old(
                                        'reviewer_ids',
                                        []
                                    );

                            @endphp


                            <tr>


                                {{-- Select --}}

                                <td class="text-center align-middle">

                                    <input
                                        type="checkbox"
                                        name="reviewer_ids[]"
                                        value="{{ $reviewer->id }}"
                                        class="reviewer-checkbox"
                                        style="width:18px;height:18px;"
                                        @checked(
                                            in_array(
                                                $reviewer->id,
                                                $oldReviewerIds
                                            )
                                        )
                                    >

                                </td>



                                {{-- Reviewer --}}

                                <td>

                                    <div class="font-weight-bold">

                                        {{
                                            $profile?->display_name
                                            ?: $reviewer->name
                                        }}

                                    </div>


                                    <div class="small text-muted">

                                        {{ $reviewer->email }}

                                    </div>


                                    @if($profile?->highest_degree)

                                        <div class="small mt-2">

                                            <strong>
                                                Degree:
                                            </strong>

                                            {{
                                                $profile
                                                    ->highest_degree
                                            }}

                                        </div>

                                    @endif


                                    @if($profile?->country)

                                        <div class="small text-muted mt-1">

                                            <i class="bi bi-geo-alt"></i>

                                            {{ $profile->country }}

                                        </div>

                                    @endif

                                </td>



                                {{-- Professional Information --}}

                                <td>

                                    <div class="font-weight-bold">

                                        {{
                                            $profile?->designation
                                            ?? 'N/A'
                                        }}

                                    </div>


                                    @if($profile?->department)

                                        <div class="small">

                                            {{
                                                $profile
                                                    ->department
                                            }}

                                        </div>

                                    @endif


                                    <div class="small text-muted">

                                        {{
                                            $profile?->institution
                                            ?? 'N/A'
                                        }}

                                    </div>

                                </td>



                                {{-- Speciality / Expertise --}}

                                <td>

                                    @if($profile?->speciality)

                                        <div class="mb-2">

                                            <span class="badge badge-primary">

                                                {{
                                                    $profile
                                                        ->speciality
                                                }}

                                            </span>

                                        </div>

                                    @endif


                                    @if($profile?->sub_speciality)

                                        <div class="small mb-1">

                                            <strong>
                                                Sub-speciality:
                                            </strong>

                                            {{
                                                $profile
                                                    ->sub_speciality
                                            }}

                                        </div>

                                    @endif


                                    @if($profile?->primary_expertise)

                                        <div class="small mb-1">

                                            <strong>
                                                Primary Expertise:
                                            </strong>

                                            {{
                                                is_array(
                                                    $profile
                                                        ->primary_expertise
                                                )
                                                    ? implode(
                                                        ', ',
                                                        $profile
                                                            ->primary_expertise
                                                    )
                                                    : $profile
                                                        ->primary_expertise
                                            }}

                                        </div>

                                    @endif


                                    @if($profile?->expertise_keywords)

                                        <div class="small text-muted">

                                            <strong>
                                                Keywords:
                                            </strong>

                                            {{
                                                is_array(
                                                    $profile
                                                        ->expertise_keywords
                                                )
                                                    ? implode(
                                                        ', ',
                                                        $profile
                                                            ->expertise_keywords
                                                    )
                                                    : \Illuminate\Support\Str::limit(
                                                        $profile
                                                            ->expertise_keywords,
                                                        100
                                                    )
                                            }}

                                        </div>

                                    @endif

                                </td>



                                {{-- Experience --}}

                                <td>

                                    @if(
                                        $profile?->years_of_experience
                                        !== null
                                    )

                                        <div>

                                            <strong>

                                                {{
                                                    $profile
                                                        ->years_of_experience
                                                }}

                                            </strong>

                                            years

                                        </div>

                                    @endif


                                    @if(
                                        $profile?->publication_count
                                        !== null
                                    )

                                        <div class="small mt-1">

                                            Publications:

                                            <strong>

                                                {{
                                                    $profile
                                                        ->publication_count
                                                }}

                                            </strong>

                                        </div>

                                    @endif


                                    @if(
                                        $profile?->external_reviews_completed
                                        !== null
                                    )

                                        <div class="small mt-1">

                                            Previous Reviews:

                                            <strong>

                                                {{
                                                    $profile
                                                        ->external_reviews_completed
                                                }}

                                            </strong>

                                        </div>

                                    @endif


                                    <div class="mt-2">

                                        <span class="badge badge-success">

                                            <i class="bi bi-check-circle"></i>

                                            Available

                                        </span>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center py-5 text-muted"
                                >

                                    <i class="bi bi-person-x d-block mb-2"></i>

                                    <strong>
                                        No eligible reviewers found.
                                    </strong>

                                    <div class="small mt-1">

                                        Try changing the search
                                        or filter criteria.

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>



            {{-- =================================================
                 BOOTSTRAP 4 PAGINATION
            ================================================== --}}

            @if($reviewers->total() > 0)

                <div class="card-footer bg-white">

                    <div class="row align-items-center">


                        <div class="col-md-6 mb-2 mb-md-0">

                            <small class="text-muted">

                                Showing

                                <strong>
                                    {{ $reviewers->firstItem() ?? 0 }}
                                </strong>

                                to

                                <strong>
                                    {{ $reviewers->lastItem() ?? 0 }}
                                </strong>

                                of

                                <strong>
                                    {{ $reviewers->total() }}
                                </strong>

                                reviewers

                            </small>

                        </div>


                        <div class="col-md-6">

                            <div class="d-flex justify-content-md-end">

                                {{
                                    $reviewers
                                        ->appends(
                                            request()->query()
                                        )
                                        ->links('pagination::bootstrap-4')
                                }}

                            </div>

                        </div>

                    </div>

                </div>

            @endif

        </div>



        {{-- =====================================================
             6. INVITATION DETAILS
        ====================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <strong>

                            <i class="bi bi-envelope-paper me-1"></i>

                            6. Invitation Details

                        </strong>


                        <div class="small text-muted mt-1">

                            Reviewer invitation and review timeline

                        </div>

                    </div>


                    <span class="badge badge-primary">

                        Review Round 1

                    </span>

                </div>

            </div>



            <div class="card-body">


                {{-- =================================================
                     SELECTED REVIEWER INFORMATION
                ================================================== --}}

                <div class="alert alert-info mb-4">

                    <div class="d-flex">

                        <i class="bi bi-info-circle-fill mr-2 mt-1"></i>


                        <div>

                            <strong>
                                Reviewer Selection
                            </strong>


                            <div class="small mt-1">

                                Select a minimum of

                                <strong>
                                    2 reviewers
                                </strong>

                                and a maximum of

                                <strong>
                                    3 reviewers
                                </strong>.

                            </div>


                            <div class="mt-2">

                                <strong id="selectedReviewerCount">

                                    0 reviewer(s) selected.

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     TIMELINE CARDS
                ================================================== --}}

                <div class="row">


                    {{-- Invitation Initiated --}}

                    <div class="col-lg-4 col-md-6 mb-3">

                        <div class="border rounded p-3 h-100 bg-light">

                            <div class="d-flex align-items-center mb-3">


                                <div
                                    class="rounded-circle bg-primary text-white
                                           d-flex align-items-center
                                           justify-content-center mr-3"
                                    style="
                                        width:45px;
                                        height:45px;
                                        min-width:45px;
                                    "
                                >

                                    <i class="bi bi-send"></i>

                                </div>


                                <div>

                                    <div class="small text-muted">

                                        Invitation Initiated

                                    </div>

                                    <div class="font-weight-bold">

                                        {{
                                            $invitationDate
                                                ->format('d M Y')
                                        }}

                                    </div>

                                </div>

                            </div>


                            <div class="small text-muted">

                                Reviewer invitation will be initiated
                                when you click

                                <strong>
                                    Send Reviewer Invitations
                                </strong>.

                            </div>

                        </div>

                    </div>



                    {{-- Invitation Expiry --}}

                    <div class="col-lg-4 col-md-6 mb-3">

                        <div class="border rounded p-3 h-100">

                            <div class="d-flex align-items-center mb-3">


                                <div
                                    class="rounded-circle bg-warning
                                           d-flex align-items-center
                                           justify-content-center mr-3"
                                    style="
                                        width:45px;
                                        height:45px;
                                        min-width:45px;
                                    "
                                >

                                    <i class="bi bi-hourglass-split"></i>

                                </div>


                                <div>

                                    <div class="small text-muted">

                                        Invitation Expiry Date

                                    </div>

                                    <div class="font-weight-bold text-warning">

                                        {{
                                            $invitationExpiryDate
                                                ->format('d M Y')
                                        }}

                                    </div>

                                </div>

                            </div>


                            <div class="small text-muted">

                                Reviewer must

                                <strong>
                                    accept or decline
                                </strong>

                                the invitation within

                                <strong>
                                    3 days
                                </strong>

                                from the initial invitation.

                            </div>

                        </div>

                    </div>



                    {{-- Review Due --}}

                    <div class="col-lg-4 col-md-6 mb-3">

                        <div class="border rounded p-3 h-100">

                            <div class="d-flex align-items-center mb-3">


                                <div
                                    class="rounded-circle bg-danger text-white
                                           d-flex align-items-center
                                           justify-content-center mr-3"
                                    style="
                                        width:45px;
                                        height:45px;
                                        min-width:45px;
                                    "
                                >

                                    <i class="bi bi-calendar-check"></i>

                                </div>


                                <div>

                                    <div class="small text-muted">

                                        Review Due Date

                                    </div>

                                    <div class="font-weight-bold text-danger">

                                        {{
                                            $reviewDueDate
                                                ->format('d M Y')
                                        }}

                                    </div>

                                </div>

                            </div>


                            <div class="small text-muted">

                                The complete peer review should
                                be submitted within

                                <strong>
                                    15 days
                                </strong>

                                from the initial invitation date.

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     REVIEWER TIMELINE
                ================================================== --}}

                <div class="mt-3">

                    <div class="border rounded p-3">

                        <div class="font-weight-bold mb-3">

                            <i class="bi bi-diagram-3 mr-1"></i>

                            Reviewer Timeline

                        </div>


                        <div class="row text-center align-items-center">


                            {{-- Day 0 --}}

                            <div class="col-md-3 mb-3 mb-md-0">

                                <span class="badge badge-primary mb-2">

                                    Day 0

                                </span>


                                <div class="font-weight-bold small">

                                    Invitation Sent

                                </div>


                                <div class="text-muted small">

                                    {{
                                        $invitationDate
                                            ->format('d M Y')
                                    }}

                                </div>

                            </div>



                            {{-- Arrow --}}

                            <div
                                class="col-md-1
                                       d-none d-md-block"
                            >

                                <i class="bi bi-arrow-right text-muted"></i>

                            </div>



                            {{-- Day 3 --}}

                            <div class="col-md-3 mb-3 mb-md-0">

                                <span class="badge badge-warning mb-2">

                                    Day 3

                                </span>


                                <div class="font-weight-bold small">

                                    Invitation Expires

                                </div>


                                <div class="text-muted small">

                                    {{
                                        $invitationExpiryDate
                                            ->format('d M Y')
                                    }}

                                </div>

                            </div>



                            {{-- Arrow --}}

                            <div
                                class="col-md-1
                                       d-none d-md-block"
                            >

                                <i class="bi bi-arrow-right text-muted"></i>

                            </div>



                            {{-- Day 15 --}}

                            <div class="col-md-4">

                                <span class="badge badge-danger mb-2">

                                    Day 15

                                </span>


                                <div class="font-weight-bold small">

                                    Review Due

                                </div>


                                <div class="text-muted small">

                                    {{
                                        $reviewDueDate
                                            ->format('d M Y')
                                    }}

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     DEADLINE POLICY
                ================================================== --}}

                <div class="alert alert-light border mt-4 mb-0">

                    <div class="d-flex">

                        <i class="bi bi-shield-check text-success mr-2"></i>


                        <div class="small">

                            <strong>
                                Deadline Policy:
                            </strong>

                            The review due date is calculated from
                            the

                            <strong>
                                initial invitation date
                            </strong>,

                            not from the date on which the reviewer
                            accepts the invitation.

                            Accepting the invitation later does not
                            extend the original 15-day review
                            deadline.

                        </div>

                    </div>

                </div>

            </div>



            {{-- =================================================
                 SUBMIT FOOTER
            ================================================== --}}

            <div class="card-footer bg-white">

                <div class="row align-items-center">


                    <div class="col-md-6 mb-2 mb-md-0">

                        <span class="text-muted">
                            Selected:
                        </span>

                        <strong
                            id="footerSelectedCount"
                            class="text-primary"
                        >
                            0
                        </strong>

                        <span class="text-muted">
                            reviewer(s)
                        </span>

                    </div>


                    <div class="col-md-6 text-md-right">

                        <button
                            type="submit"
                            class="btn btn-success"
                            id="sendInvitationButton"
                            disabled
                        >

                            <i class="bi bi-send mr-1"></i>

                            Send Reviewer Invitations

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>



{{-- =============================================================
     PAGE CSS
============================================================= --}}

<style>
/* Reviewer Selection page: readable text on light surfaces */
.container-fluid { color: #212529 !important; }
.container-fluid h1, .container-fluid h2, .container-fluid h3,
.container-fluid h4, .container-fluid h5, .container-fluid h6,
.container-fluid p, .container-fluid label, .container-fluid strong,
.container-fluid td, .container-fluid th { color: #212529; }

.card, .card-body { background-color: #fff !important; color: #212529 !important; }
.card-footer { color: #212529 !important; }
.card-header.bg-white { background-color: #fff !important; color: #212529 !important; }
.card-header.bg-white strong, .card-header.bg-white div,
.card-header.bg-white span:not(.badge), .card-header.bg-white i { color: #212529 !important; }

.card-header.bg-primary, .card-header.bg-success, .card-header.bg-danger,
.card-header.bg-info, .card-header.bg-secondary { color: #fff !important; }
.card-header.bg-primary *, .card-header.bg-success *, .card-header.bg-danger *,
.card-header.bg-info *, .card-header.bg-secondary * { color: #fff !important; }
.card-header.bg-warning, .card-header.bg-warning * { color: #212529 !important; }

.text-muted { color: #6c757d !important; }
.text-muted strong { color: #343a40 !important; }
.bg-light { background-color: #f8f9fa !important; color: #212529 !important; }
.bg-light strong, .bg-light div, .bg-light span:not(.badge), .bg-light p { color: #212529; }

.table { color: #212529 !important; background-color: #fff; }
.table td { color: #212529 !important; background-color: #fff; vertical-align: middle; }
.table th { color: #212529 !important; vertical-align: middle; }
.table thead th, .table .thead-light th {
    color: #212529 !important;
    background-color: #e9ecef !important;
    border-color: #dee2e6 !important;
}
.table-hover tbody tr:hover td { color: #212529 !important; background-color: #f5f7f9 !important; }

label, .form-label { color: #212529 !important; font-weight: 600; }
.form-control { color: #212529 !important; background-color: #fff !important; border-color: #ced4da !important; }
.form-control:focus { color: #212529 !important; background-color: #fff !important; }
.form-control::placeholder { color: #6c757d !important; opacity: 1; }
select.form-control, select.form-control option { color: #212529 !important; background-color: #fff !important; }

.alert-light { color: #212529 !important; background-color: #f8f9fa !important; }
.alert-info { color: #0c5460 !important; }
.alert-success { color: #155724 !important; }
.alert-danger { color: #721c24 !important; }
.alert-warning { color: #856404 !important; }

.badge-primary, .badge-success, .badge-danger, .badge-info, .badge-secondary { color: #fff !important; }
.badge-warning, .badge-light { color: #212529 !important; }
.badge-light { background-color: #f8f9fa !important; }

.btn-primary, .btn-success, .btn-danger, .btn-info, .btn-secondary { color: #fff !important; }
.btn-warning { color: #212529 !important; }
.btn-outline-secondary { color: #6c757d !important; }
.btn-outline-secondary:hover { color: #fff !important; }

.font-weight-bold { color: #212529; }
.small { color: inherit; }
.border.rounded { color: #212529; }
.border.rounded .font-weight-bold { color: #212529; }

.text-primary { color: #007bff !important; }
.text-success { color: #28a745 !important; }
.text-danger { color: #dc3545 !important; }
.text-warning { color: #b77900 !important; }
.text-info { color: #17a2b8 !important; }

.reviewer-checkbox { cursor: pointer; }
#sendInvitationButton:disabled { cursor: not-allowed; opacity: .65; }

.pagination { margin-bottom: 0; }
.pagination .page-link { padding: .35rem .65rem; font-size: .875rem; color: #007bff !important; background-color: #fff !important; }
.pagination .page-item.active .page-link { color: #fff !important; font-weight: 600; }
.pagination .page-item.disabled .page-link { color: #6c757d !important; }

.card-body a:not(.btn), .table a:not(.btn) { color: #0056b3; }
.card-body a:not(.btn):hover, .table a:not(.btn):hover { color: #003d80; }
</style>



{{-- =============================================================
     REVIEWER SELECTION JAVASCRIPT
============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const checkboxes =
            document.querySelectorAll(
                '.reviewer-checkbox'
            );


        const selectedReviewerCount =
            document.getElementById(
                'selectedReviewerCount'
            );


        const footerSelectedCount =
            document.getElementById(
                'footerSelectedCount'
            );


        const sendButton =
            document.getElementById(
                'sendInvitationButton'
            );


        /*
        |--------------------------------------------------------------------------
        | Update Reviewer Selection
        |--------------------------------------------------------------------------
        */

        function updateReviewerSelection() {

            const selected =
                document.querySelectorAll(
                    '.reviewer-checkbox:checked'
                );


            const count =
                selected.length;


            /*
            |--------------------------------------------------------------------------
            | Update Count
            |--------------------------------------------------------------------------
            */

            if (selectedReviewerCount) {

                selectedReviewerCount.textContent =
                    count +
                    ' reviewer(s) selected.';

            }


            if (footerSelectedCount) {

                footerSelectedCount.textContent =
                    count;

            }


            /*
            |--------------------------------------------------------------------------
            | Enable Submit
            |--------------------------------------------------------------------------
            |
            | Minimum = 2
            | Maximum = 3
            |
            */

            if (sendButton) {

                sendButton.disabled =
                    count < 2 ||
                    count > 3;

            }


            /*
            |--------------------------------------------------------------------------
            | Maximum Reviewer Selection
            |--------------------------------------------------------------------------
            */

            checkboxes.forEach(
                function (checkbox) {

                    if (
                        count >= 3 &&
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

        }


        /*
        |--------------------------------------------------------------------------
        | Checkbox Events
        |--------------------------------------------------------------------------
        */

        checkboxes.forEach(
            function (checkbox) {

                checkbox.addEventListener(
                    'change',
                    updateReviewerSelection
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Initial State
        |--------------------------------------------------------------------------
        */

        updateReviewerSelection();


        /*
        |--------------------------------------------------------------------------
        | Final Submit Validation
        |--------------------------------------------------------------------------
        */

        const reviewerForm =
            document.getElementById(
                'reviewerSelectionForm'
            );


        if (reviewerForm) {

            reviewerForm.addEventListener(
                'submit',
                function (event) {

                    const selected =
                        document.querySelectorAll(
                            '.reviewer-checkbox:checked'
                        );


                    const count =
                        selected.length;


                    if (
                        count < 2 ||
                        count > 3
                    ) {

                        event.preventDefault();

                        alert(
                            'Please select a minimum of 2 and a maximum of 3 reviewers.'
                        );

                    }

                }
            );

        }

    }
);

</script>

@endsection