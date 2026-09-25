@extends('admin.layouts.app')

@section('title', 'Manuscripts')

@section('content')

<style>
    /* ============================================================
       Manuscript Management
    ============================================================ */

    .manuscript-page {
        max-width: 100%;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .page-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: .25rem;
    }

    .page-subtitle {
        color: #6c757d;
        font-size: .9rem;
        margin-bottom: 0;
    }

    .manuscript-card {
        border: 0;
        border-radius: 12px;
        overflow: hidden;
    }

    .manuscript-card .card-header {
        background: #fff;
        border-bottom: 1px solid #edf0f2;
        padding: 1rem 1.25rem;
    }

    .manuscript-card .card-footer {
        background: #fff;
        border-top: 1px solid #edf0f2;
    }

    /* ============================================================
       Table
    ============================================================ */

    .manuscript-table {
        margin-bottom: 0;
    }

    .manuscript-table thead th {
        background: #f8f9fa;
        color: #495057;
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
        white-space: nowrap;
        border-bottom: 1px solid #dee2e6;
        padding: .9rem .75rem;
    }

    .manuscript-table tbody td {
        padding: .9rem .75rem;
        font-size: .875rem;
        border-color: #f0f1f2;
    }

    .manuscript-table tbody tr {
        transition: background-color .15s ease;
    }

    .manuscript-table tbody tr:hover {
        background-color: #fafbfc;
    }

    .manuscript-id {
        font-size: .8rem;
        font-weight: 700;
        color: #0d6efd;
        white-space: nowrap;
    }

    .manuscript-title {
        min-width: 250px;
        max-width: 380px;
        font-weight: 600;
        color: #212529;
        line-height: 1.4;
    }

    .manuscript-meta {
        color: #6c757d;
        font-size: .78rem;
    }

    /* ============================================================
       Badges
    ============================================================ */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .72rem;
        font-weight: 600;
        padding: .4rem .6rem;
        border-radius: 6px;
        white-space: nowrap;
    }

    .status-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
        opacity: .8;
    }

    /* ============================================================
       Action Buttons
    ============================================================ */

    .action-buttons {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: .35rem;
        flex-wrap: wrap;
    }

    .action-buttons .btn {
        border-radius: 6px;
        font-size: .78rem;
        font-weight: 500;
        white-space: nowrap;
    }

    /* ============================================================
       Mobile Manuscript Cards
    ============================================================ */

    .mobile-manuscript-list {
        display: none;
    }

    .mobile-manuscript {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        background: #fff;
        padding: 1rem;
        margin-bottom: .75rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
    }

    .mobile-manuscript:last-child {
        margin-bottom: 0;
    }

    .mobile-manuscript-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: .75rem;
        margin-bottom: .75rem;
    }

    .mobile-manuscript-id {
        font-size: .75rem;
        font-weight: 700;
        color: #0d6efd;
        margin-bottom: .25rem;
    }

    .mobile-manuscript-title {
        font-size: .95rem;
        font-weight: 650;
        line-height: 1.4;
        color: #212529;
    }

    .mobile-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .65rem;
        padding: .75rem 0;
        border-top: 1px solid #f0f1f2;
        border-bottom: 1px solid #f0f1f2;
    }

    .mobile-info-item {
        min-width: 0;
    }

    .mobile-info-label {
        display: block;
        font-size: .68rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .03em;
        color: #8a929a;
        margin-bottom: .15rem;
    }

    .mobile-info-value {
        display: block;
        font-size: .8rem;
        color: #343a40;
        word-break: break-word;
    }

    .mobile-status-row {
        display: flex;
        align-items: center;
        gap: .4rem;
        flex-wrap: wrap;
        margin-top: .75rem;
    }

    .mobile-actions {
        display: grid;
        grid-template-columns: 1fr;
        gap: .5rem;
        margin-top: .75rem;
    }

    .mobile-actions .btn {
        width: 100%;
        border-radius: 7px;
        font-size: .8rem;
        font-weight: 500;
        padding: .55rem .75rem;
    }

    /* ============================================================
       Empty State
    ============================================================ */

    .empty-state {
        padding: 4rem 1rem;
        text-align: center;
    }

    .empty-icon {
        width: 64px;
        height: 64px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f1f3f5;
        font-size: 1.7rem;
        margin-bottom: 1rem;
    }

    .empty-state h6 {
        font-weight: 700;
        color: #343a40;
    }

    .empty-state p {
        color: #868e96;
        font-size: .85rem;
    }

    /* ============================================================
       Pagination
    ============================================================ */

    .pagination {
        margin-bottom: 0;
    }

    /* ============================================================
       Tablet
    ============================================================ */

    @media (max-width: 1199.98px) {

        .manuscript-table thead th,
        .manuscript-table tbody td {
            padding: .7rem .55rem;
        }

        .manuscript-table {
            font-size: .82rem;
        }

        .manuscript-title {
            min-width: 200px;
        }

    }

    /* ============================================================
       Mobile
    ============================================================ */

    @media (max-width: 767.98px) {

        .container-fluid {
            padding-left: .75rem !important;
            padding-right: .75rem !important;
        }

        .manuscript-page {
            padding-top: .75rem;
        }

        .page-header {
            display: block;
            margin-bottom: 1rem;
        }

        .page-title {
            font-size: 1.3rem;
        }

        .page-subtitle {
            font-size: .8rem;
        }

        .manuscript-card {
            border-radius: 10px;
        }

        .manuscript-card .card-header {
            padding: .9rem 1rem;
        }

        .desktop-manuscript-table {
            display: none;
        }

        .mobile-manuscript-list {
            display: block;
            padding: .75rem;
        }

        .mobile-manuscript-header {
            align-items: flex-start;
        }

        .mobile-manuscript {
            padding: .9rem;
        }

        .card-footer {
            padding: .75rem;
        }

        .pagination {
            justify-content: center;
            flex-wrap: wrap;
        }

        .pagination .page-link {
            font-size: .75rem;
            padding: .35rem .6rem;
        }

    }

    /* ============================================================
       Very Small Screens
    ============================================================ */

    @media (max-width: 380px) {

        .mobile-info {
            grid-template-columns: 1fr;
            gap: .5rem;
        }

        .mobile-status-row {
            align-items: flex-start;
            flex-direction: column;
        }

        .status-badge {
            font-size: .68rem;
        }

    }
</style>

<div class="container-fluid py-4 manuscript-page">

{{-- ============================================================
     Header
============================================================ --}}

<div class="page-header">

    <div>

        <h2 class="page-title">
            Manuscripts
        </h2>

        <p class="page-subtitle">
            Manage submitted manuscripts and their workflow status.
        </p>

    </div>

</div>


{{-- ============================================================
     Flash Messages
============================================================ --}}

@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show"
         role="alert">

        <i class="bi bi-check-circle me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

@endif


@if(session('error'))

    <div class="alert alert-danger alert-dismissible fade show"
         role="alert">

        <i class="bi bi-exclamation-triangle me-2"></i>

        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

@endif


@if(session('warning'))

    <div class="alert alert-warning alert-dismissible fade show"
         role="alert">

        <i class="bi bi-exclamation-circle me-2"></i>

        {{ session('warning') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- ============================================================
     Manuscripts Card
============================================================ --}}

<div class="card manuscript-card shadow-sm">

    <div class="card-header">

        <div class="d-flex align-items-center justify-content-between">

            <div>

                <h5 class="mb-1 fw-semibold">
                    Submitted Manuscripts
                </h5>

                <small class="text-muted">
                    Review and manage manuscript submissions
                </small>

            </div>

            <div class="d-none d-md-block">

                <span class="badge bg-light text-dark border">

                    {{ $manuscripts->total() }}

                    {{ $manuscripts->total() == 1
                        ? 'Manuscript'
                        : 'Manuscripts'
                    }}

                </span>

            </div>

        </div>

    </div>


    {{-- ========================================================
         DESKTOP / TABLET TABLE
    ========================================================= --}}

    <div class="card-body p-0 desktop-manuscript-table">

        <div class="table-responsive">

            <table class="table table-hover align-middle manuscript-table">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Title</th>

                        <th>Article Type</th>

                        <th>Journal</th>

                        <th>Submitter</th>

                        <th>Status</th>

                        <th>Stage</th>

                        <th>Submitted</th>

                        <th class="text-end">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($manuscripts as $manuscript)

                        @php

                            /*
                            |--------------------------------------------------------------------------
                            | Status Badge
                            |--------------------------------------------------------------------------
                            */

                            $statusClass = match($manuscript->status) {

                                'draft'
                                    => 'bg-secondary',

                                'submitted'
                                    => 'bg-primary',

                                'technical_check'
                                    => 'bg-warning text-dark',

                                'technical_correction'
                                    => 'bg-danger',

                                'payment_setup'
                                    => 'bg-primary',

                                'payment_required'
                                    => 'bg-warning text-dark',

                                'payment_correction'
                                    => 'bg-danger',

                                'payment_verified'
                                    => 'bg-success',

                                'editorial_assessment'
                                    => 'bg-info text-dark',

                                'similarity_check'
                                    => 'bg-info text-dark',

                                'under_review'
                                    => 'bg-primary',

                                'revision_required'
                                    => 'bg-warning text-dark',

                                'accepted'
                                    => 'bg-success',

                                'rejected'
                                    => 'bg-danger',

                                'copy_editing'
                                    => 'bg-primary',

                                'proofreading'
                                    => 'bg-primary',

                                'production'
                                    => 'bg-primary',

                                'published'
                                    => 'bg-success',

                                default
                                    => 'bg-secondary',

                            };


                            /*
                            |--------------------------------------------------------------------------
                            | Stage Badge
                            |--------------------------------------------------------------------------
                            */

                            $stageClass = match($manuscript->current_stage) {

                                'submission'
                                    => 'bg-secondary',

                                'technical_review'
                                    => 'bg-warning text-dark',

                                'author_correction'
                                    => 'bg-danger',

                                'payment'
                                    => 'bg-warning text-dark',

                                'payment_correction'
                                    => 'bg-danger',

                                'editorial_assessment'
                                    => 'bg-info text-dark',

                                'similarity_check'
                                    => 'bg-info text-dark',

                                'peer_review'
                                    => 'bg-primary',

                                'revision'
                                    => 'bg-warning text-dark',

                                'decision'
                                    => 'bg-primary',

                                'copy_editing'
                                    => 'bg-primary',

                                'proofreading'
                                    => 'bg-primary',

                                'production'
                                    => 'bg-primary',

                                'publication'
                                    => 'bg-success',

                                default
                                    => 'bg-light text-dark border',

                            };


                            /*
                            |--------------------------------------------------------------------------
                            | Latest Payment
                            |--------------------------------------------------------------------------
                            */

                            $payment = $manuscript->latestPayment ?? null;

                        @endphp


                        <tr>

                            {{-- ====================================================
                                 ID
                            ===================================================== --}}

                            <td>

                                <span class="manuscript-id">

                                    {{ $manuscript->manuscript_id
                                        ?? $manuscript->id }}

                                </span>

                            </td>


                            {{-- ====================================================
                                 Title
                            ===================================================== --}}

                            <td>

                                <div class="manuscript-title">

                                    {{ $manuscript->title
                                        ?? 'Untitled Manuscript' }}

                                </div>

                            </td>


                            {{-- ====================================================
                                 Article Type
                            ===================================================== --}}

                            <td>

                                @if($manuscript->articleType)

                                    {{ $manuscript->articleType->name }}

                                @else

                                    <span class="text-muted">
                                        N/A
                                    </span>

                                @endif

                            </td>


                            {{-- ====================================================
                                 Journal
                            ===================================================== --}}

                            <td>

                                @if($manuscript->journal)

                                    {{ $manuscript->journal->name }}

                                @else

                                    <span class="text-muted">
                                        N/A
                                    </span>

                                @endif

                            </td>


                            {{-- ====================================================
                                 Submitter
                            ===================================================== --}}

                            <td>

                                @if($manuscript->submitter)

                                    {{ $manuscript->submitter->name }}

                                @else

                                    <span class="text-muted">
                                        N/A
                                    </span>

                                @endif

                            </td>


                            {{-- ====================================================
                                 Status
                            ===================================================== --}}

                            <td>

                                <span class="badge status-badge {{ $statusClass }}">

                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $manuscript->status ?? 'Unknown'
                                        )
                                    ) }}

                                </span>

                            </td>


                            {{-- ====================================================
                                 Stage
                            ===================================================== --}}

                            <td>

                                @if($manuscript->current_stage)

                                    <span class="badge status-badge {{ $stageClass }}">

                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $manuscript->current_stage
                                            )
                                        ) }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- ====================================================
                                 Submitted
                            ===================================================== --}}

                            <td class="text-nowrap">

                                @if($manuscript->submitted_at)

                                    <div>
                                        {{ $manuscript->submitted_at->format('d M Y') }}
                                    </div>

                                    <small class="manuscript-meta">

                                        {{ $manuscript->submitted_at->format('h:i A') }}

                                    </small>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- ====================================================
                                 Actions
                            ===================================================== --}}

                            <td>

                                <div class="action-buttons">

                                    {{-- View --}}

                                    <a
                                        href="{{ route(
                                            'admin.manuscripts.show',
                                            $manuscript
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-eye me-1"></i>
                                        View

                                    </a>


                                    {{-- =================================================
                                         Technical Check
                                    ================================================== --}}

                                    @if(
                                        $manuscript->current_stage === 'technical_review'
                                        ||
                                        $manuscript->status === 'technical_check'
                                    )

                                        @can('technical_check.view')

                                            <a
                                                href="{{ route(
                                                    'admin.manuscripts.technical-check',
                                                    $manuscript
                                                ) }}"
                                                class="btn btn-sm btn-outline-warning">

                                                <i class="bi bi-clipboard-check me-1"></i>

                                                Technical Check

                                            </a>

                                        @endcan

                                    @endif


                                    {{-- =================================================
                                         Awaiting Author Correction
                                    ================================================== --}}

                                    @if(
                                        $manuscript->current_stage === 'author_correction'
                                        ||
                                        $manuscript->status === 'technical_correction'
                                    )

                                        <span
                                            class="btn btn-sm btn-outline-danger disabled">

                                            <i class="bi bi-clock-history me-1"></i>

                                            Awaiting Correction

                                        </span>

                                    @endif


                                    {{-- =================================================
                                         Assign Payment
                                    ================================================== --}}

                                    @if($manuscript->status === 'payment_setup')

                                        @if($payment)

                                            @can('payment.view')

                                                <a
                                                    href="{{ route(
                                                        'admin.payments.show',
                                                        $payment
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-success">

                                                    <i class="bi bi-receipt me-1"></i>

                                                    View Payment

                                                </a>

                                            @endcan

                                        @else

                                            @can('payment.create')

                                                <a
                                                    href="{{ route(
                                                        'admin.manuscripts.payment.create',
                                                        $manuscript
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-success">

                                                    <i class="bi bi-credit-card me-1"></i>

                                                    Assign Payment

                                                </a>

                                            @endcan

                                        @endif

                                    @endif


                                    {{-- =================================================
                                         Payment Already Sent / In Progress
                                    ================================================== --}}

                                    @if(
                                        $manuscript->status === 'payment_required'
                                        ||
                                        $manuscript->status === 'payment_correction'
                                    )

                                        @if($payment)

                                            @can('payment.view')

                                                <a
                                                    href="{{ route(
                                                        'admin.payments.show',
                                                        $payment
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-warning">

                                                    <i class="bi bi-credit-card me-1"></i>

                                                    View Payment

                                                </a>

                                            @endcan

                                        @else

                                            <span
                                                class="btn btn-sm btn-outline-warning disabled">

                                                <i class="bi bi-credit-card me-1"></i>

                                                Payment

                                            </span>

                                        @endif

                                    @endif


                                    {{-- =================================================
                                         Payment Verified
                                    ================================================== --}}

                                    @if($manuscript->status === 'payment_verified')

                                        @if($payment)

                                            @can('payment.view')

                                                <a
                                                    href="{{ route(
                                                        'admin.payments.show',
                                                        $payment
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-success">

                                                    <i class="bi bi-check-circle me-1"></i>

                                                    View Payment

                                                </a>

                                            @endcan

                                        @endif

                                    @endif

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="9">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </div>

                                    <h6>
                                        No Manuscripts Found
                                    </h6>

                                    <p class="mb-0">
                                        No manuscripts are currently available.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- ========================================================
         MOBILE CARDS
    ========================================================= --}}

    <div class="mobile-manuscript-list">

        @forelse($manuscripts as $manuscript)

            @php

                /*
                |--------------------------------------------------------------------------
                | Status Badge
                |--------------------------------------------------------------------------
                */

                $statusClass = match($manuscript->status) {

                    'draft'
                        => 'bg-secondary',

                    'submitted'
                        => 'bg-primary',

                    'technical_check'
                        => 'bg-warning text-dark',

                    'technical_correction'
                        => 'bg-danger',

                    'payment_setup'
                        => 'bg-primary',

                    'payment_required'
                        => 'bg-warning text-dark',

                    'payment_correction'
                        => 'bg-danger',

                    'payment_verified'
                        => 'bg-success',

                    'editorial_assessment'
                        => 'bg-info text-dark',

                    'similarity_check'
                        => 'bg-info text-dark',

                    'under_review'
                        => 'bg-primary',

                    'revision_required'
                        => 'bg-warning text-dark',

                    'accepted'
                        => 'bg-success',

                    'rejected'
                        => 'bg-danger',

                    'copy_editing'
                        => 'bg-primary',

                    'proofreading'
                        => 'bg-primary',

                    'production'
                        => 'bg-primary',

                    'published'
                        => 'bg-success',

                    default
                        => 'bg-secondary',

                };


                /*
                |--------------------------------------------------------------------------
                | Stage Badge
                |--------------------------------------------------------------------------
                */

                $stageClass = match($manuscript->current_stage) {

                    'submission'
                        => 'bg-secondary',

                    'technical_review'
                        => 'bg-warning text-dark',

                    'author_correction'
                        => 'bg-danger',

                    'payment'
                        => 'bg-warning text-dark',

                    'payment_correction'
                        => 'bg-danger',

                    'editorial_assessment'
                        => 'bg-info text-dark',

                    'similarity_check'
                        => 'bg-info text-dark',

                    'peer_review'
                        => 'bg-primary',

                    'revision'
                        => 'bg-warning text-dark',

                    'decision'
                        => 'bg-primary',

                    'copy_editing'
                        => 'bg-primary',

                    'proofreading'
                        => 'bg-primary',

                    'production'
                        => 'bg-primary',

                    'publication'
                        => 'bg-success',

                    default
                        => 'bg-light text-dark border',

                };


                /*
                |--------------------------------------------------------------------------
                | Latest Payment
                |--------------------------------------------------------------------------
                */

                $payment = $manuscript->latestPayment ?? null;

            @endphp


            <div class="mobile-manuscript">

                {{-- ========================================================
                     Header
                ========================================================= --}}

                <div class="mobile-manuscript-header">

                    <div class="flex-grow-1">

                        <div class="mobile-manuscript-id">

                            {{ $manuscript->manuscript_id
                                ?? $manuscript->id }}

                        </div>

                        <div class="mobile-manuscript-title">

                            {{ $manuscript->title
                                ?? 'Untitled Manuscript' }}

                        </div>

                    </div>

                </div>


                {{-- ========================================================
                     Information
                ========================================================= --}}

                <div class="mobile-info">

                    <div class="mobile-info-item">

                        <span class="mobile-info-label">
                            Article Type
                        </span>

                        <span class="mobile-info-value">

                            {{ $manuscript->articleType?->name ?? 'N/A' }}

                        </span>

                    </div>


                    <div class="mobile-info-item">

                        <span class="mobile-info-label">
                            Journal
                        </span>

                        <span class="mobile-info-value">

                            {{ $manuscript->journal?->name ?? 'N/A' }}

                        </span>

                    </div>


                    <div class="mobile-info-item">

                        <span class="mobile-info-label">
                            Submitter
                        </span>

                        <span class="mobile-info-value">

                            {{ $manuscript->submitter?->name ?? 'N/A' }}

                        </span>

                    </div>


                    <div class="mobile-info-item">

                        <span class="mobile-info-label">
                            Submitted
                        </span>

                        <span class="mobile-info-value">

                            @if($manuscript->submitted_at)

                                {{ $manuscript->submitted_at->format('d M Y') }}

                            @else

                                —

                            @endif

                        </span>

                    </div>

                </div>


                {{-- ========================================================
                     Status
                ========================================================= --}}

                <div class="mobile-status-row">

                    <span class="badge status-badge {{ $statusClass }}">

                        {{ ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $manuscript->status ?? 'Unknown'
                            )
                        ) }}

                    </span>


                    @if($manuscript->current_stage)

                        <span class="badge status-badge {{ $stageClass }}">

                            {{ ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $manuscript->current_stage
                                )
                            ) }}

                        </span>

                    @endif

                </div>


                {{-- ========================================================
                     Actions
                ========================================================= --}}

                <div class="mobile-actions">

                    {{-- View Manuscript --}}

                    <a
                        href="{{ route(
                            'admin.manuscripts.show',
                            $manuscript
                        ) }}"
                        class="btn btn-outline-primary">

                        <i class="bi bi-eye me-1"></i>

                        View Manuscript

                    </a>


                    {{-- Technical Check --}}

                    @if(
                        $manuscript->current_stage === 'technical_review'
                        ||
                        $manuscript->status === 'technical_check'
                    )

                        @can('technical_check.view')

                            <a
                                href="{{ route(
                                    'admin.manuscripts.technical-check',
                                    $manuscript
                                ) }}"
                                class="btn btn-outline-warning">

                                <i class="bi bi-clipboard-check me-1"></i>

                                Open Technical Check

                            </a>

                        @endcan

                    @endif


                    {{-- Awaiting Author Correction --}}

                    @if(
                        $manuscript->current_stage === 'author_correction'
                        ||
                        $manuscript->status === 'technical_correction'
                    )

                        <button
                            type="button"
                            class="btn btn-outline-danger"
                            disabled>

                            <i class="bi bi-clock-history me-1"></i>

                            Awaiting Author Correction

                        </button>

                    @endif


                    {{-- =================================================
                         Assign / View Payment
                    ================================================== --}}

                    @if($manuscript->status === 'payment_setup')

                        @if($payment)

                            @can('payment.view')

                                <a
                                    href="{{ route(
                                        'admin.payments.show',
                                        $payment
                                    ) }}"
                                    class="btn btn-outline-success">

                                    <i class="bi bi-receipt me-1"></i>

                                    View Payment

                                </a>

                            @endcan

                        @else

                            @can('payment.create')

                                <a
                                    href="{{ route(
                                        'admin.manuscripts.payment.create',
                                        $manuscript
                                    ) }}"
                                    class="btn btn-outline-success">

                                    <i class="bi bi-credit-card me-1"></i>

                                    Assign Payment

                                </a>

                            @endcan

                        @endif

                    @endif


                    {{-- Payment Required --}}

                    @if(
                        $manuscript->status === 'payment_required'
                        ||
                        $manuscript->status === 'payment_correction'
                    )

                        @if($payment)

                            @can('payment.view')

                                <a
                                    href="{{ route(
                                        'admin.payments.show',
                                        $payment
                                    ) }}"
                                    class="btn btn-outline-warning">

                                    <i class="bi bi-credit-card me-1"></i>

                                    View Payment

                                </a>

                            @endcan

                        @else

                            <button
                                type="button"
                                class="btn btn-outline-warning"
                                disabled>

                                <i class="bi bi-credit-card me-1"></i>

                                Payment

                            </button>

                        @endif

                    @endif


                    {{-- Payment Verified --}}

                    @if($manuscript->status === 'payment_verified')

                        @if($payment)

                            @can('payment.view')

                                <a
                                    href="{{ route(
                                        'admin.payments.show',
                                        $payment
                                    ) }}"
                                    class="btn btn-outline-success">

                                    <i class="bi bi-check-circle me-1"></i>

                                    View Payment

                                </a>

                            @endcan

                        @endif

                    @endif

                </div>

            </div>

        @empty

            <div class="empty-state">

                <div class="empty-icon">

                    <i class="bi bi-file-earmark-text"></i>

                </div>

                <h6>
                    No Manuscripts Found
                </h6>

                <p class="mb-0">
                    No manuscripts are currently available.
                </p>

            </div>

        @endforelse

    </div>


    {{-- ========================================================
         Pagination
    ========================================================= --}}

    @if($manuscripts->hasPages())

        <div class="card-footer">

            {{ $manuscripts->links('pagination::bootstrap-4') }}

        </div>

    @endif

</div>

</div>

@endsection
