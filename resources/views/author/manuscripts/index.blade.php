@extends('author.layouts.app')

@section('content')

<style>
    .dashboard-card {
        border: 0;
        border-radius: 12px;
        overflow: hidden;
    }

    .dashboard-card .card-header {
        background: #fff;
        border-bottom: 1px solid #edf0f2;
        padding: 1rem 1.25rem;
    }

    .dashboard-card .card-body {
        padding: 1.25rem;
    }

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
        padding: .85rem .75rem;
    }

    .manuscript-table tbody td {
        padding: .85rem .75rem;
        vertical-align: middle;
        font-size: .875rem;
    }

    .manuscript-id {
        font-weight: 700;
        color: #0d6efd;
        white-space: nowrap;
    }

    .manuscript-title {
        font-weight: 600;
        color: #212529;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: .4rem .6rem;
        border-radius: 6px;
        font-size: .72rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .progress {
        height: 20px;
        min-width: 100px;
        border-radius: 6px;
        background: #e9ecef;
        overflow: hidden;
    }

    .progress-bar {
        font-size: .7rem;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        gap: .35rem;
        flex-wrap: wrap;
    }

    .action-buttons .btn {
        font-size: .76rem;
        border-radius: 6px;
        white-space: nowrap;
    }

    /* ============================================================
       Mobile
    ============================================================ */

    .mobile-manuscript-list {
        display: none;
    }

    .mobile-manuscript {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: .75rem;
        background: #fff;
    }

    .mobile-manuscript:last-child {
        margin-bottom: 0;
    }

    .mobile-manuscript-id {
        font-size: .75rem;
        font-weight: 700;
        color: #0d6efd;
        margin-bottom: .25rem;
    }

    .mobile-manuscript-title {
        font-size: .95rem;
        font-weight: 600;
        line-height: 1.4;
        margin-bottom: .75rem;
    }

    .mobile-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .65rem;
        padding: .75rem 0;
        border-top: 1px solid #f0f1f2;
        border-bottom: 1px solid #f0f1f2;
    }

    .mobile-info-label {
        display: block;
        font-size: .68rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #8a929a;
        margin-bottom: .15rem;
    }

    .mobile-info-value {
        display: block;
        font-size: .8rem;
        color: #343a40;
    }

    .mobile-progress {
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
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: .75rem;
    }

    @media (max-width: 767.98px) {

        .dashboard-card .card-body {
            padding: .75rem;
        }

        .desktop-manuscript-table {
            display: none;
        }

        .mobile-manuscript-list {
            display: block;
        }

        .mobile-info {
            grid-template-columns: 1fr 1fr;
        }

    }

    @media (max-width: 380px) {

        .mobile-info {
            grid-template-columns: 1fr;
        }

    }
</style>

<div class="container-fluid py-4">

{{-- ============================================================
     Manuscripts Card
============================================================= --}}

<div class="card shadow-sm dashboard-card">

    <div class="card-header">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h4 class="mb-1">
                    My Manuscripts
                </h4>

                <small class="text-muted">
                    Track your manuscript submission and payment status.
                </small>

            </div>

            <span class="badge bg-light text-dark border">

                {{ $manuscripts->total() }}

                {{ $manuscripts->total() == 1
                    ? 'Manuscript'
                    : 'Manuscripts'
                }}

            </span>

        </div>

    </div>


    {{-- ========================================================
         DESKTOP TABLE
    ========================================================= --}}

    <div class="card-body p-0 desktop-manuscript-table">

        <div class="table-responsive">

            <table class="table table-hover manuscript-table">

                <thead>

                    <tr>

                        <th>
                            Manuscript ID
                        </th>

                        <th>
                            Title
                        </th>

                        <th>
                            Journal
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Progress
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($manuscripts as $manuscript)

                        @php

                            $payment = $manuscript->latestPayment ?? null;

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

                                'copy_editing',
                                'proofreading',
                                'production'
                                    => 'bg-primary',

                                'published'
                                    => 'bg-success',

                                default
                                    => 'bg-secondary',

                            };

                        @endphp


                        <tr>

                            {{-- =================================================
                                 Manuscript ID
                            ================================================== --}}

                            <td>

                                <span class="manuscript-id">

                                    {{ $manuscript->manuscript_id }}

                                </span>

                            </td>


                            {{-- =================================================
                                 Title
                            ================================================== --}}

                            <td>

                                <div class="manuscript-title">

                                    {{ Str::limit(
                                        $manuscript->title,
                                        50
                                    ) }}

                                </div>

                            </td>


                            {{-- =================================================
                                 Journal
                            ================================================== --}}

                            <td>

                                {{ $manuscript->journal->name ?? 'N/A' }}

                            </td>


                            {{-- =================================================
                                 Status
                            ================================================== --}}

                            <td>

                                <span class="badge status-badge {{ $statusClass }}">

                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $manuscript->status
                                        )
                                    ) }}

                                </span>

                            </td>


                            {{-- =================================================
                                 Progress
                            ================================================== --}}

                            <td>

                                <div class="progress">

                                    <div
                                        class="progress-bar"
                                        role="progressbar"
                                        style="width: {{ $manuscript->completion_percentage }}%;">

                                        {{ $manuscript->completion_percentage }}%

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                 Actions
                            ================================================== --}}

                            <td>

                                <div class="action-buttons">


                                    {{-- View Manuscript --}}

                                    <a
                                        href="{{ route(
                                            'author.manuscripts.show',
                                            $manuscript->id
                                        ) }}"
                                        class="btn btn-sm btn-outline-info">

                                        <i class="bi bi-eye me-1"></i>

                                        View

                                    </a>


                                    {{-- =================================================
                                         PAYMENT REQUIRED
                                    ================================================== --}}

                                    @if(
                                        $manuscript->status === 'payment_required'
                                        &&
                                        $payment
                                    )

                                        <a
                                            href="{{ route(
                                                'author.payments.show',
                                                $payment
                                            ) }}"
                                            class="btn btn-sm btn-success">

                                            <i class="bi bi-credit-card me-1"></i>

                                            Pay Now

                                        </a>

                                    @endif


                                    {{-- =================================================
                                         PAYMENT CORRECTION
                                    ================================================== --}}

                                    @if(
                                        $manuscript->status === 'payment_correction'
                                        &&
                                        $payment
                                    )

                                        <a
                                            href="{{ route(
                                                'author.payments.show',
                                                $payment
                                            ) }}"
                                            class="btn btn-sm btn-warning">

                                            <i class="bi bi-pencil-square me-1"></i>

                                            Update Payment

                                        </a>

                                    @endif


                                    {{-- =================================================
                                         PAYMENT VERIFIED
                                    ================================================== --}}

                                    @if(
                                        $manuscript->status === 'payment_verified'
                                        &&
                                        $payment
                                    )

                                        <a
                                            href="{{ route(
                                                'author.payments.show',
                                                $payment
                                            ) }}"
                                            class="btn btn-sm btn-outline-success">

                                            <i class="bi bi-check-circle me-1"></i>

                                            Paid / Verified

                                        </a>

                                    @endif

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="6">

                                <div class="empty-state">

                                    <i class="bi bi-file-earmark-text"></i>

                                    <h6>
                                        No Manuscripts Found
                                    </h6>

                                    <p class="mb-0">
                                        You have not submitted any manuscripts yet.
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
         MOBILE MANUSCRIPT CARDS
    ========================================================= --}}

    <div class="mobile-manuscript-list">

        <div class="p-2">

            @forelse($manuscripts as $manuscript)

                @php

                    $payment = $manuscript->latestPayment ?? null;

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

                        'copy_editing',
                        'proofreading',
                        'production'
                            => 'bg-primary',

                        'published'
                            => 'bg-success',

                        default
                            => 'bg-secondary',

                    };

                @endphp


                <div class="mobile-manuscript">


                    {{-- =================================================
                         Header
                    ================================================== --}}

                    <div class="mobile-manuscript-id">

                        {{ $manuscript->manuscript_id }}

                    </div>


                    <div class="mobile-manuscript-title">

                        {{ $manuscript->title }}

                    </div>


                    {{-- =================================================
                         Information
                    ================================================== --}}

                    <div class="mobile-info">

                        <div>

                            <span class="mobile-info-label">
                                Journal
                            </span>

                            <span class="mobile-info-value">

                                {{ $manuscript->journal->name ?? 'N/A' }}

                            </span>

                        </div>


                        <div>

                            <span class="mobile-info-label">
                                Status
                            </span>

                            <span>

                                <span class="badge status-badge {{ $statusClass }}">

                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $manuscript->status
                                        )
                                    ) }}

                                </span>

                            </span>

                        </div>

                    </div>


                    {{-- =================================================
                         Progress
                    ================================================== --}}

                    <div class="mobile-progress">

                        <div class="d-flex justify-content-between mb-1">

                            <small class="text-muted">
                                Progress
                            </small>

                            <small class="fw-semibold">

                                {{ $manuscript->completion_percentage }}%

                            </small>

                        </div>


                        <div class="progress">

                            <div
                                class="progress-bar"
                                role="progressbar"
                                style="width: {{ $manuscript->completion_percentage }}%;">

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         Actions
                    ================================================== --}}

                    <div class="mobile-actions">


                        {{-- View --}}

                        <a
                            href="{{ route(
                                'author.manuscripts.show',
                                $manuscript->id
                            ) }}"
                            class="btn btn-outline-info">

                            <i class="bi bi-eye me-1"></i>

                            View Manuscript

                        </a>


                        {{-- Payment Required --}}

                        @if(
                            $manuscript->status === 'payment_required'
                            &&
                            $payment
                        )

                            <a
                                href="{{ route(
                                    'author.payments.show',
                                    $payment
                                ) }}"
                                class="btn btn-success">

                                <i class="bi bi-credit-card me-1"></i>

                                Pay Now

                            </a>

                        @endif


                        {{-- Payment Correction --}}

                        @if(
                            $manuscript->status === 'payment_correction'
                            &&
                            $payment
                        )

                            <a
                                href="{{ route(
                                    'author.payments.show',
                                    $payment
                                ) }}"
                                class="btn btn-warning">

                                <i class="bi bi-pencil-square me-1"></i>

                                Update Payment

                            </a>

                        @endif


                        {{-- Payment Verified --}}

                        @if(
                            $manuscript->status === 'payment_verified'
                            &&
                            $payment
                        )

                            <a
                                href="{{ route(
                                    'author.payments.show',
                                    $payment
                                ) }}"
                                class="btn btn-outline-success">

                                <i class="bi bi-check-circle me-1"></i>

                                Paid / Verified

                            </a>

                        @endif

                    </div>

                </div>

            @empty

                <div class="empty-state">

                    <i class="bi bi-file-earmark-text"></i>

                    <h6>
                        No Manuscripts Found
                    </h6>

                    <p class="mb-0">
                        You have not submitted any manuscripts yet.
                    </p>

                </div>

            @endforelse

        </div>

    </div>


    {{-- ========================================================
         Pagination
    ========================================================= --}}

    @if($manuscripts->hasPages())

        <div class="card-footer bg-white">

            {{ $manuscripts->links() }}

        </div>

    @endif

</div>

</div>

@endsection
