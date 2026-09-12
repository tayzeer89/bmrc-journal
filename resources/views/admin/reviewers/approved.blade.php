@extends('admin.layouts.app')

@section('title', 'Approved Reviewers')

@section('content')

<div class="container-fluid">

{{-- ============================================================
    PAGE HEADER
============================================================ --}}

<div class="d-flex flex-column flex-lg-row
            justify-content-between align-items-lg-center
            gap-3 mb-3">

    <div>

        <h5 class="mb-1 fw-semibold">
            <i class="bi bi-patch-check-fill text-success me-2"></i>
            Approved Reviewers
        </h5>

        <p class="text-muted small mb-0">
            Approved reviewers eligible for peer-review activities.
        </p>

    </div>


    <div class="d-flex flex-wrap gap-2">

        @can('reviewer.search')

            <a
                href="{{ route('admin.reviewers.search') }}"
                class="btn btn-sm btn-outline-primary"
            >
                <i class="bi bi-search me-1"></i>
                Search Reviewer
            </a>

        @endcan


        <a
            href="{{ route('admin.reviewers.index') }}"
            class="btn btn-sm btn-outline-secondary"
        >
            <i class="bi bi-people me-1"></i>
            Reviewer Pool
        </a>

    </div>

</div>


{{-- ============================================================
    ALERTS
============================================================ --}}

@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show py-2 small">

        <i class="bi bi-check-circle-fill me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


@if(session('error'))

    <div class="alert alert-danger alert-dismissible fade show py-2 small">

        <i class="bi bi-exclamation-circle-fill me-2"></i>

        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


{{-- ============================================================
    REVIEWER TABLE CARD
============================================================ --}}

<div class="card border-0 shadow-sm reviewer-card">

    {{-- Card Header --}}

    <div class="card-header bg-white border-bottom px-3 py-2">

        <div class="d-flex justify-content-between align-items-center">

            <div class="fw-semibold small">
                <i class="bi bi-people-fill text-primary me-2"></i>
                Approved Reviewer List
            </div>


            <span class="badge rounded-pill bg-success-subtle
                         text-success border border-success-subtle">

                {{ $reviewers->total() }}

            </span>

        </div>

    </div>


    {{-- ========================================================
        TABLE
    ======================================================== --}}

    <div class="card-body p-0">

        <div class="reviewer-table-container">

            <table class="table table-hover align-middle mb-0 reviewer-table">

                <colgroup>

                    <col class="column-id">

                    <col class="column-reviewer">

                    <col class="column-professional">

                    <col class="column-specialization">

                    <col class="column-profile">

                    <col class="column-availability">

                    <col class="column-approved">

                    <col class="column-action">

                </colgroup>


                <thead>

                    <tr>

                        <th class="text-center">
                            #
                        </th>

                        <th>
                            Reviewer
                        </th>

                        <th>
                            Professional Information
                        </th>

                        <th>
                            Specialization
                        </th>

                        <th class="text-center">
                            Profile
                        </th>

                        <th class="text-center">
                            Availability
                        </th>

                        <th class="text-center">
                            Approved
                        </th>

                        <th class="text-center">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($reviewers as $reviewer)

                        <tr>

                            {{-- =========================================
                                SERIAL
                            ========================================== --}}

                            <td class="text-center text-muted">

                                {{
                                    $reviewers->firstItem()
                                    + $loop->index
                                }}

                            </td>


                            {{-- =========================================
                                REVIEWER
                            ========================================== --}}

                            <td>

                                <div class="reviewer-info">

                                    <div class="reviewer-avatar">

                                        {{
                                            strtoupper(
                                                mb_substr(
                                                    $reviewer->name,
                                                    0,
                                                    1
                                                )
                                            )
                                        }}

                                    </div>


                                    <div class="reviewer-details">

                                        <div
                                            class="reviewer-name"
                                            title="{{ $reviewer->name }}"
                                        >
                                            {{ $reviewer->name }}
                                        </div>


                                        <div
                                            class="reviewer-email"
                                            title="{{ $reviewer->email }}"
                                        >
                                            <i class="bi bi-envelope me-1"></i>

                                            {{ $reviewer->email }}
                                        </div>


                                        @if($reviewer->profile?->reviewer_code)

                                            <div class="reviewer-code">

                                                <i class="bi bi-hash"></i>

                                                {{ $reviewer->profile->reviewer_code }}

                                            </div>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- =========================================
                                PROFESSIONAL INFORMATION
                            ========================================== --}}

                            <td>

                                <div class="professional-info">

                                    <div
                                        class="professional-title"
                                        title="{{ $reviewer->profile?->designation }}"
                                    >

                                        {{ $reviewer->profile?->designation ?? '—' }}

                                    </div>


                                    @if($reviewer->profile?->department)

                                        <div
                                            class="professional-secondary"
                                            title="{{ $reviewer->profile->department }}"
                                        >
                                            <i class="bi bi-diagram-3 me-1"></i>

                                            {{ $reviewer->profile->department }}
                                        </div>

                                    @endif


                                    @if($reviewer->profile?->institution)

                                        <div
                                            class="professional-secondary"
                                            title="{{ $reviewer->profile->institution }}"
                                        >
                                            <i class="bi bi-building me-1"></i>

                                            {{ $reviewer->profile->institution }}
                                        </div>

                                    @elseif($reviewer->profile?->institution_name)

                                        <div
                                            class="professional-secondary"
                                            title="{{ $reviewer->profile->institution_name }}"
                                        >
                                            <i class="bi bi-building me-1"></i>

                                            {{ $reviewer->profile->institution_name }}
                                        </div>

                                    @endif

                                </div>

                            </td>


                            {{-- =========================================
                                SPECIALIZATION
                            ========================================== --}}

                            <td>

                                @php

                                    $specializations = collect(
                                        preg_split(
                                            '/[,;|]+/',
                                            $reviewer->profile?->specialization ?? ''
                                        )
                                    )
                                    ->map(
                                        fn ($item) => trim($item)
                                    )
                                    ->filter()
                                    ->unique()
                                    ->values();

                                @endphp


                                <div class="specialization-list">

                                    @forelse($specializations as $specialization)

                                        <div
                                            class="specialization-item"
                                            title="{{ $specialization }}"
                                        >

                                            <i class="bi bi-tag-fill"></i>

                                            <span>
                                                {{ $specialization }}
                                            </span>

                                        </div>

                                    @empty

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endforelse

                                </div>

                            </td>


                            {{-- =========================================
                                PROFILE
                            ========================================== --}}

                            <td class="text-center">

                                @if($reviewer->profile?->profile_completed)

                                    <span
                                        class="compact-status
                                               status-success"
                                        title="Profile Complete"
                                        data-bs-toggle="tooltip"
                                    >
                                        <i class="bi bi-check-circle-fill"></i>

                                        <span>
                                            Complete
                                        </span>
                                    </span>

                                @else

                                    <span
                                        class="compact-status
                                               status-warning"
                                        title="Profile Incomplete"
                                        data-bs-toggle="tooltip"
                                    >
                                        <i class="bi bi-exclamation-circle-fill"></i>

                                        <span>
                                            Incomplete
                                        </span>
                                    </span>

                                @endif

                            </td>


                            {{-- =========================================
                                AVAILABILITY
                            ========================================== --}}

                            <td class="text-center">

                                @if($reviewer->profile?->available_for_review)

                                    <span
                                        class="compact-status
                                               status-success"
                                        title="Available for Review"
                                        data-bs-toggle="tooltip"
                                    >
                                        <i class="bi bi-circle-fill status-dot"></i>

                                        <span>
                                            Available
                                        </span>
                                    </span>

                                @else

                                    <span
                                        class="compact-status
                                               status-secondary"
                                        title="Not Available for Review"
                                        data-bs-toggle="tooltip"
                                    >
                                        <i class="bi bi-circle-fill status-dot"></i>

                                        <span>
                                            Unavailable
                                        </span>
                                    </span>

                                @endif

                            </td>


                            {{-- =========================================
                                APPROVED
                            ========================================== --}}

                            <td class="text-center">

                                <div class="approval-status">

                                    <span
                                        class="approval-icon"
                                        title="Approved Reviewer"
                                        data-bs-toggle="tooltip"
                                    >
                                        <i class="bi bi-check-lg"></i>
                                    </span>


                                    @if($reviewer->activated_at)

                                        <div class="approval-date">

                                            {{
                                                $reviewer
                                                    ->activated_at
                                                    ->format('d M Y')
                                            }}

                                        </div>

                                    @endif

                                </div>

                            </td>


                            {{-- =========================================
                                ACTION
                            ========================================== --}}

                            <td class="text-center">

                                <div class="reviewer-actions">

                                    {{-- View --}}
                                    @can('reviewer.view')

                                        <a
                                            href="{{ route(
                                                'admin.reviewers.show',
                                                $reviewer
                                            ) }}"
                                            class="action-button action-view"
                                            title="View Reviewer"
                                            data-bs-toggle="tooltip"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>

                                    @endcan


                                    {{-- Edit --}}
                                    @can('reviewer.edit')

                                        <a
                                            href="{{ route(
                                                'admin.reviewers.edit',
                                                $reviewer
                                            ) }}"
                                            class="action-button action-edit"
                                            title="Edit Reviewer"
                                            data-bs-toggle="tooltip"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                    @endcan


                                    {{-- Suspend --}}
                                    @can('reviewer.suspend')

                                        <form
                                            action="{{ route(
                                                'admin.reviewers.suspend',
                                                $reviewer
                                            ) }}"
                                            method="POST"
                                            class="m-0 p-0 d-inline-flex"
                                            onsubmit="return confirm(
                                                'Are you sure you want to suspend this reviewer?'
                                            );"
                                        >

                                            @csrf
                                            @method('PATCH')


                                            <button
                                                type="submit"
                                                class="action-button action-suspend"
                                                title="Suspend Reviewer"
                                                data-bs-toggle="tooltip"
                                            >
                                                <i class="bi bi-person-x"></i>
                                            </button>

                                        </form>

                                    @endcan

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-5"
                            >

                                <div class="empty-state-icon">

                                    <i class="bi bi-person-check"></i>

                                </div>


                                <h6 class="fw-semibold mb-1">
                                    No Approved Reviewers
                                </h6>


                                <p class="small text-muted mb-0">
                                    There are currently no approved reviewers.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- ========================================================
        PAGINATION
    ======================================================== --}}

    @if($reviewers->hasPages())

        <div class="card-footer bg-white border-top px-3 py-2">

            <div class="d-flex flex-column flex-md-row
                        justify-content-between align-items-md-center
                        gap-2">

                <div class="pagination-info">

                    Showing

                    <strong>
                        {{ $reviewers->firstItem() }}
                    </strong>

                    to

                    <strong>
                        {{ $reviewers->lastItem() }}
                    </strong>

                    of

                    <strong>
                        {{ $reviewers->total() }}
                    </strong>

                    approved reviewers

                </div>


                <div>

                    {{ $reviewers->links('pagination::bootstrap-4') }}

                </div>

            </div>

        </div>

    @endif

</div>

</div>

{{-- ================================================================
PAGE STYLE
================================================================ --}}

<style>

    /*
    |--------------------------------------------------------------------------
    | Card
    |--------------------------------------------------------------------------
    */

    .reviewer-card {
        border-radius: 8px;
        overflow: hidden;
    }


    /*
    |--------------------------------------------------------------------------
    | Table Container
    |--------------------------------------------------------------------------
    */

    .reviewer-table-container {
        width: 100%;
        overflow: visible;
    }


    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    .reviewer-table {
        width: 100%;
        table-layout: fixed;
        font-size: 12px;
        color: #343a40;
    }


    /*
    |--------------------------------------------------------------------------
    | Column Distribution
    |--------------------------------------------------------------------------
    */

    .reviewer-table .column-id {
        width: 4%;
    }

    .reviewer-table .column-reviewer {
        width: 20%;
    }

    .reviewer-table .column-professional {
        width: 20%;
    }

    .reviewer-table .column-specialization {
        width: 16%;
    }

    .reviewer-table .column-profile {
        width: 10%;
    }

    .reviewer-table .column-availability {
        width: 11%;
    }

    .reviewer-table .column-approved {
        width: 9%;
    }

    .reviewer-table .column-action {
        width: 10%;
    }


    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */

    .reviewer-table thead th {
        padding: 9px 7px;

        background: #f8f9fa;

        border-bottom: 1px solid #dee2e6;

        color: #495057;

        font-size: 11px;
        font-weight: 600;

        letter-spacing: 0.015em;

        vertical-align: middle;

        white-space: normal;
    }


    /*
    |--------------------------------------------------------------------------
    | Body Cells
    |--------------------------------------------------------------------------
    */

    .reviewer-table tbody td {
        padding: 9px 7px;

        border-bottom: 1px solid #edf0f2;

        vertical-align: middle;

        line-height: 1.35;

        overflow-wrap: anywhere;
    }


    .reviewer-table tbody tr:last-child td {
        border-bottom: 0;
    }


    .reviewer-table tbody tr:hover {
        background-color: #fafbfc;
    }


    /*
    |--------------------------------------------------------------------------
    | Reviewer
    |--------------------------------------------------------------------------
    */

    .reviewer-info {
        display: flex;
        align-items: flex-start;
        gap: 8px;

        min-width: 0;
    }


    .reviewer-avatar {
        width: 30px;
        height: 30px;

        flex: 0 0 30px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #e8f0fe;
        color: #0d6efd;

        font-size: 11px;
        font-weight: 700;
    }


    .reviewer-details {
        min-width: 0;
        width: 100%;
    }


    .reviewer-name {
        color: #212529;

        font-size: 12px;
        font-weight: 600;

        line-height: 1.3;

        overflow-wrap: anywhere;
    }


    .reviewer-email {
        margin-top: 2px;

        color: #6c757d;

        font-size: 10.5px;

        line-height: 1.3;

        overflow-wrap: anywhere;
    }


    .reviewer-code {
        margin-top: 2px;

        color: #6c757d;

        font-size: 10px;
    }


    /*
    |--------------------------------------------------------------------------
    | Professional Information
    |--------------------------------------------------------------------------
    */

    .professional-title {
        color: #343a40;

        font-size: 11.5px;
        font-weight: 500;

        line-height: 1.3;

        overflow-wrap: anywhere;
    }


    .professional-secondary {
        margin-top: 3px;

        color: #6c757d;

        font-size: 10.5px;

        line-height: 1.3;

        overflow-wrap: anywhere;
    }


    /*
    |--------------------------------------------------------------------------
    | Specialization
    |--------------------------------------------------------------------------
    */

    .specialization-list {
        display: flex;
        flex-direction: column;
        align-items: flex-start;

        gap: 3px;

        width: 100%;
    }


    .specialization-item {
        display: inline-flex;
        align-items: flex-start;

        max-width: 100%;

        padding: 3px 6px;

        border: 1px solid #dee2e6;
        border-radius: 4px;

        background: #f8f9fa;

        color: #495057;

        font-size: 10.5px;
        font-weight: 400;

        line-height: 1.25;

        overflow-wrap: anywhere;
    }


    .specialization-item i {
        margin-right: 4px;
        margin-top: 1px;

        color: #0d6efd;

        font-size: 8px;

        flex-shrink: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Compact Status
    |--------------------------------------------------------------------------
    */

    .compact-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 4px;

        max-width: 100%;

        padding: 3px 6px;

        border-radius: 4px;

        font-size: 10px;
        font-weight: 500;

        line-height: 1.2;

        white-space: nowrap;
    }


    .status-success {
        color: #146c43;
        background: #eaf7ef;
        border: 1px solid #ccebd8;
    }


    .status-warning {
        color: #856404;
        background: #fff8e1;
        border: 1px solid #ffe69c;
    }


    .status-secondary {
        color: #5c636a;
        background: #f1f3f5;
        border: 1px solid #dee2e6;
    }


    .status-dot {
        font-size: 5px;
    }


    /*
    |--------------------------------------------------------------------------
    | Approval
    |--------------------------------------------------------------------------
    */

    .approval-status {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;

        gap: 3px;
    }


    .approval-icon {
        width: 27px;
        height: 27px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #eaf7ef;
        color: #198754;

        border: 1px solid #ccebd8;

        font-size: 13px;
    }


    .approval-date {
        color: #6c757d;

        font-size: 9.5px;

        white-space: nowrap;
    }


    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    .reviewer-actions {
        display: flex;
        align-items: center;
        justify-content: center;

        gap: 4px;

        flex-wrap: nowrap;
    }


    .action-button {
        width: 28px;
        height: 28px;

        padding: 0;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        flex: 0 0 28px;

        border: 1px solid transparent;
        border-radius: 5px;

        background: transparent;

        font-size: 12px;

        text-decoration: none;

        transition:
            background-color .15s ease,
            border-color .15s ease,
            color .15s ease;
    }


    .action-view {
        color: #0d6efd;
        border-color: #b6d4fe;
    }


    .action-view:hover {
        color: #fff;
        background: #0d6efd;
        border-color: #0d6efd;
    }


    .action-edit {
        color: #6c757d;
        border-color: #ced4da;
    }


    .action-edit:hover {
        color: #fff;
        background: #6c757d;
        border-color: #6c757d;
    }


    .action-suspend {
        color: #dc3545;
        border-color: #f1aeb5;
    }


    .action-suspend:hover {
        color: #fff;
        background: #dc3545;
        border-color: #dc3545;
    }


    /*
    |--------------------------------------------------------------------------
    | Empty State
    |--------------------------------------------------------------------------
    */

    .empty-state-icon {
        width: 48px;
        height: 48px;

        margin: 0 auto 10px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #f1f3f5;
        color: #adb5bd;

        font-size: 20px;
    }


    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    .pagination-info {
        color: #6c757d;
        font-size: 11px;
    }


    /*
    |--------------------------------------------------------------------------
    | Medium Desktop
    |--------------------------------------------------------------------------
    */

    @media (max-width: 1400px) {

        .reviewer-table {
            font-size: 11px;
        }

        .reviewer-table thead th {
            padding: 8px 5px;
            font-size: 10px;
        }

        .reviewer-table tbody td {
            padding: 8px 5px;
        }

        .reviewer-name {
            font-size: 11px;
        }

        .reviewer-email,
        .professional-secondary,
        .specialization-item {
            font-size: 9.5px;
        }

        .professional-title {
            font-size: 10.5px;
        }

        .compact-status {
            padding: 3px 4px;
            font-size: 9px;
        }

        .action-button {
            width: 26px;
            height: 26px;
            flex-basis: 26px;
            font-size: 11px;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | Smaller Dashboard Width
    |--------------------------------------------------------------------------
    */

    @media (max-width: 1200px) {

        .reviewer-table .column-id {
            width: 3%;
        }

        .reviewer-table .column-reviewer {
            width: 20%;
        }

        .reviewer-table .column-professional {
            width: 20%;
        }

        .reviewer-table .column-specialization {
            width: 16%;
        }

        .reviewer-table .column-profile {
            width: 10%;
        }

        .reviewer-table .column-availability {
            width: 11%;
        }

        .reviewer-table .column-approved {
            width: 10%;
        }

        .reviewer-table .column-action {
            width: 10%;
        }

    }

</style>

@endsection

{{-- ================================================================
TOOLTIPS
================================================================ --}}

@push('scripts')

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const tooltipElements =
            document.querySelectorAll(
                '[data-bs-toggle="tooltip"]'
            );


        tooltipElements.forEach(function (element) {

            new bootstrap.Tooltip(element);

        });

    });

</script>

@endpush
