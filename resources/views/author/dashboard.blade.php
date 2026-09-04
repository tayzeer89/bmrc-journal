@extends('author.layouts.app')

@section('title', 'Author Dashboard')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        DASHBOARD HEADER
    ========================================================== --}}

    <div class="dashboard-header mb-4">

        <div>

            <div class="dashboard-label">
                BMRC JOURNAL MANAGEMENT SYSTEM
            </div>

            <h2 class="dashboard-title">
                Author Dashboard
            </h2>

            <p class="dashboard-subtitle mb-0">
                Welcome, {{ auth()->user()->name }}
            </p>

        </div>


        <div class="dashboard-user">

            <div class="user-avatar">

                {{ strtoupper(
                    substr(
                        auth()->user()->name ?? 'A',
                        0,
                        1
                    )
                ) }}

            </div>

            <div>

                <strong>
                    {{ auth()->user()->name }}
                </strong>

                <small>
                    {{ auth()->user()->designation ?? 'Author' }}
                </small>

            </div>

        </div>

    </div>


    {{-- =========================================================
        AUTHOR PROFILE SUMMARY
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                {{-- Profile Information --}}

                <div class="col-md-8">

                    <div class="d-flex align-items-center">

                        <div class="author-avatar">

                            {{ strtoupper(
                                substr(
                                    auth()->user()->name ?? 'A',
                                    0,
                                    1
                                )
                            ) }}

                        </div>

                        <div class="ms-3">

                            <h5 class="mb-1">

                                {{ auth()->user()->name }}

                            </h5>


                            <div class="text-muted small">

                                Author ID:

                                <strong>

                                    {{ $authorProfile->author_id ?? 'Not Generated' }}

                                </strong>

                            </div>


                            <div class="text-muted small">

                                Email:

                                {{ auth()->user()->email }}

                            </div>

                        </div>

                    </div>

                </div>


            {{-- PROFILE COMPLETION --}}

            <div class="col-md-4 mt-3 mt-md-0">

                <div class="d-flex justify-content-between align-items-center mb-1">

                    <span class="small fw-semibold">
                        Profile Completion
                    </span>

                    <span class="small fw-semibold">
                        {{ $profileCompletion }}%
                    </span>

                </div>

                <div class="progress" style="height: 8px;">

                    <div class="progress-bar"
                        role="progressbar"
                        style="width: {{ $profileCompletion }}%;"
                        aria-valuenow="{{ $profileCompletion }}"
                        aria-valuemin="0"
                        aria-valuemax="100">
                    </div>

                </div>

                @if($profileCompletion < 100)

                    <a href="{{ route('author.profile.edit') }}"
                    class="small text-primary text-decoration-none">

                        Complete your profile →

                    </a>

                @else

                    <span class="small text-success">
                        Profile completed
                    </span>

                @endif

            </div>



    {{-- =========================================================
        TECHNICAL REVIEW / CORRECTION NOTICES
    ========================================================== --}}

    @php

        /*
        |--------------------------------------------------------------------------
        | Find manuscripts requiring technical attention
        |--------------------------------------------------------------------------
        */

        $technicalCorrectionManuscripts = collect($manuscripts ?? [])
            ->filter(function ($manuscript) {

                return $manuscript->status === 'technical_correction';

            });


        $technicalReviewManuscripts = collect($manuscripts ?? [])
            ->filter(function ($manuscript) {

                return $manuscript->status === 'technical_check';

            });


        $paymentSetupManuscripts = collect($manuscripts ?? [])
            ->filter(function ($manuscript) {

                return $manuscript->status === 'payment_setup';

            });

    @endphp


    {{-- =========================================================
        TECHNICAL CORRECTION REQUIRED
    ========================================================== --}}

    @if($technicalCorrectionManuscripts->count())

        <div class="card border-0 shadow-sm border-start border-danger border-4 mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0 text-danger">

                    <i class="bi bi-exclamation-triangle-fill me-2"></i>

                    Technical Correction Required

                </h5>

            </div>


            <div class="card-body">

                @foreach($technicalCorrectionManuscripts as $manuscript)

                    <div class="technical-alert technical-alert-danger mb-3">

                        <div class="d-flex flex-column flex-md-row
                                    justify-content-between
                                    align-items-md-center gap-3">

                            <div>

                                <div class="fw-bold">

                                    {{ $manuscript->manuscript_id }}

                                </div>


                                <div class="mt-1">

                                    {{ Str::limit(
                                        $manuscript->title ?? 'Untitled Manuscript',
                                        100
                                    ) }}

                                </div>


                                <div class="small mt-2">

                                    <i class="bi bi-info-circle me-1"></i>

                                    The technical review team has requested
                                    corrections to this manuscript.

                                </div>

                            </div>


                            <div>

                                <a href="{{ route(
                                    'author.manuscripts.show',
                                    $manuscript->id
                                ) }}"
                                   class="btn btn-danger">

                                    <i class="bi bi-pencil-square me-1"></i>

                                    Correct Manuscript

                                </a>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    @endif


    {{-- =========================================================
        TECHNICAL REVIEW IN PROGRESS
    ========================================================== --}}

    @if($technicalReviewManuscripts->count())

        <div class="card border-0 shadow-sm border-start border-warning border-4 mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0 text-warning">

                    <i class="bi bi-clipboard-check me-2"></i>

                    Technical Review

                </h5>

            </div>


            <div class="card-body">

                @foreach($technicalReviewManuscripts as $manuscript)

                    <div class="technical-alert technical-alert-warning mb-3">

                        <div class="d-flex flex-column flex-md-row
                                    justify-content-between
                                    align-items-md-center gap-3">

                            <div>

                                <div class="fw-bold">

                                    {{ $manuscript->manuscript_id }}

                                </div>


                                <div class="mt-1">

                                    {{ Str::limit(
                                        $manuscript->title ?? 'Untitled Manuscript',
                                        100
                                    ) }}

                                </div>


                                <div class="small mt-2">

                                    <i class="bi bi-hourglass-split me-1"></i>

                                    Your manuscript is currently under
                                    technical review.

                                </div>

                            </div>


                            <div>

                                <a href="{{ route(
                                    'author.manuscripts.show',
                                    $manuscript->id
                                ) }}"
                                   class="btn btn-warning">

                                    <i class="bi bi-eye me-1"></i>

                                    View Manuscript

                                </a>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    @endif


    {{-- =========================================================
        PAYMENT SETUP
    ========================================================== --}}

    @if($paymentSetupManuscripts->count())

        <div class="card border-0 shadow-sm border-start border-info border-4 mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0 text-info">

                    <i class="bi bi-credit-card me-2"></i>

                    Payment Required

                </h5>

            </div>


            <div class="card-body">

                @foreach($paymentSetupManuscripts as $manuscript)

                    <div class="technical-alert technical-alert-info">

                        <div class="d-flex flex-column flex-md-row
                                    justify-content-between
                                    align-items-md-center gap-3">

                            <div>

                                <div class="fw-bold">

                                    {{ $manuscript->manuscript_id }}

                                </div>


                                <div class="mt-1">

                                    {{ Str::limit(
                                        $manuscript->title ?? 'Untitled Manuscript',
                                        100
                                    ) }}

                                </div>


                                <div class="small mt-2">

                                    <i class="bi bi-check-circle me-1"></i>

                                    Technical review has been completed.
                                    Payment setup is now required.

                                </div>

                            </div>


                            <div>

                                <a href="{{ route(
                                    'author.manuscripts.show',
                                    $manuscript->id
                                ) }}"
                                   class="btn btn-info text-white">

                                    <i class="bi bi-credit-card me-1"></i>

                                    View Payment

                                </a>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    @endif


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}

    <div class="row g-3 mb-4">


        {{-- Total Manuscripts --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Total Manuscripts
                            </div>

                            <h3 class="fw-bold mb-0">

                                {{ $statistics['total'] ?? 0 }}

                            </h3>

                        </div>

                        <i class="bi bi-journal-text fs-1 text-primary"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- Drafts --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Drafts
                            </div>

                            <h3 class="fw-bold mb-0">

                                {{ $statistics['drafts'] ?? 0 }}

                            </h3>

                        </div>

                        <i class="bi bi-file-earmark fs-1 text-secondary"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- Submitted --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Submitted
                            </div>

                            <h3 class="fw-bold mb-0">

                                {{ $statistics['submitted'] ?? 0 }}

                            </h3>

                        </div>

                        <i class="bi bi-send fs-1 text-info"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- Under Review --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Under Review
                            </div>

                            <h3 class="fw-bold mb-0">

                                {{ $statistics['under_review'] ?? 0 }}

                            </h3>

                        </div>

                        <i class="bi bi-search fs-1 text-warning"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- Payment Pending --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Payment Pending
                            </div>

                            <h3 class="fw-bold mb-0">

                                {{ $statistics['payment_pending'] ?? 0 }}

                            </h3>

                        </div>

                        <i class="bi bi-credit-card fs-1 text-danger"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- Technical Correction --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Technical Correction
                            </div>

                            <h3 class="fw-bold mb-0">

                                {{ $statistics['technical_correction'] ?? 0 }}

                            </h3>

                        </div>

                        <i class="bi bi-exclamation-triangle fs-1 text-danger"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- Revision --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Revision Required
                            </div>

                            <h3 class="fw-bold mb-0">

                                {{ $statistics['revision'] ?? 0 }}

                            </h3>

                        </div>

                        <i class="bi bi-arrow-repeat fs-1 text-warning"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- Accepted --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Accepted
                            </div>

                            <h3 class="fw-bold mb-0">

                                {{ $statistics['accepted'] ?? 0 }}

                            </h3>

                        </div>

                        <i class="bi bi-check-circle fs-1 text-success"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- Published --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Published
                            </div>

                            <h3 class="fw-bold mb-0">

                                {{ $statistics['published'] ?? 0 }}

                            </h3>

                        </div>

                        <i class="bi bi-book fs-1 text-success"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        QUICK ACTIONS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">

                <i class="bi bi-lightning-charge me-2"></i>

                Quick Actions

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- New Submission --}}

                <div class="col-lg-3 col-md-6">

                    <a href="#"
                       class="btn btn-primary w-100 py-3">

                        <i class="bi bi-plus-circle me-1"></i>

                        New Submission

                    </a>

                </div>


                {{-- Profile --}}

                <div class="col-lg-3 col-md-6">

                    <a href="{{ route('author.profile.edit') }}"
                       class="btn btn-outline-primary w-100 py-3">

                        <i class="bi bi-person me-1"></i>

                        My Profile

                    </a>

                </div>

                    {{-- Payments & Invoices --}}

                    <div class="col-lg-3 col-md-6">

                        <a href="{{ route('author.payments.index') }}"
                        class="btn btn-outline-success w-100 py-3">

                            <i class="bi bi-credit-card me-1"></i>

                            Payments & Invoices

                        </a>

                    </div>


                {{-- Notifications --}}

                <div class="col-lg-3 col-md-6">

                    <a href="#"
                       class="btn btn-outline-warning w-100 py-3">

                        <i class="bi bi-bell me-1"></i>

                        Notifications

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PENDING ACTIONS
    ========================================================== --}}

    @if(isset($pendingActions) && $pendingActions->count())

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">

                    <i class="bi bi-exclamation-circle text-warning me-2"></i>

                    Pending Actions

                </h5>

            </div>


            <div class="card-body">

                @foreach($pendingActions as $action)

                    <div class="alert alert-{{ $action->type ?? 'warning' }}
                                d-flex flex-column flex-md-row
                                justify-content-between
                                align-items-md-center
                                gap-3">

                        <div>

                            <strong>
                                {{ $action->title }}
                            </strong>

                            <div class="small mt-1">
                                {{ $action->description }}
                            </div>

                        </div>


                        @if(isset($action->url))

                            <a href="{{ $action->url }}"
                               class="btn btn-sm btn-outline-dark">

                                Take Action

                            </a>

                        @endif

                    </div>

                @endforeach

            </div>

        </div>

    @endif


    {{-- =========================================================
        RECENT MANUSCRIPTS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white
                    d-flex flex-wrap
                    justify-content-between
                    align-items-center">

            <h5 class="mb-0">

                <i class="bi bi-journals me-2"></i>

                My Manuscripts

            </h5>


            <a href="{{ route('author.manuscripts.index') }}"
               class="btn btn-sm btn-outline-primary mt-2 mt-md-0">

                View All

            </a>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Manuscript ID</th>

                            <th>Title</th>

                            <th>Article Type</th>

                            <th>Submission Date</th>

                            <th>Version</th>

                            <th>Status</th>

                            <th>Payment</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($manuscripts ?? [] as $manuscript)

                            @php

                                $status = $manuscript->status ?? 'draft';

                                $statusClass = match($status) {

                                    'draft' => 'secondary',

                                    'submitted' => 'info',

                                    'technical_check' => 'warning',

                                    'technical_correction' => 'danger',

                                    'payment_setup' => 'info',

                                    'payment_pending' => 'warning',

                                    'under_review' => 'primary',

                                    'major_revision' => 'warning',

                                    'minor_revision' => 'warning',

                                    'accepted' => 'success',

                                    'rejected' => 'danger',

                                    'published' => 'success',

                                    default => 'secondary',

                                };

                            @endphp


                            <tr>


                                {{-- Manuscript ID --}}

                                <td>

                                    <strong>

                                        {{ $manuscript->manuscript_id }}

                                    </strong>

                                </td>


                                {{-- Title --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ Str::limit(
                                            $manuscript->title ?? 'Untitled',
                                            45
                                        ) }}

                                    </div>

                                </td>


                                {{-- Article Type --}}

                                <td>

                                    {{ $manuscript->articleType?->name ?? '—' }}
                                    
                                </td>


                                {{-- Submission Date --}}

                                <td>

                                    {{ optional(
                                        $manuscript->submitted_at
                                    )->format('d-M-Y') }}

                                </td>


                                {{-- Version --}}

                                <td>

                                    V{{ $manuscript->version ?? '1.0' }}

                                </td>


                                {{-- Status --}}

                                <td>

                                    <span class="badge bg-{{ $statusClass }}">

                                        @switch($status)

                                            @case('technical_check')

                                                <i class="bi bi-clipboard-check me-1"></i>

                                                Technical Review

                                                @break


                                            @case('technical_correction')

                                                <i class="bi bi-exclamation-triangle me-1"></i>

                                                Technical Correction

                                                @break


                                            @case('payment_setup')

                                                <i class="bi bi-credit-card me-1"></i>

                                                Payment Setup

                                                @break


                                            @case('payment_pending')

                                                <i class="bi bi-clock me-1"></i>

                                                Payment Pending

                                                @break


                                            @default

                                                {{ ucwords(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $status
                                                    )
                                                ) }}

                                        @endswitch

                                    </span>

                                </td>


                
                                    {{-- PAYMENT STATUS --}}
                                    <td>
                                        @php
                                            /*
                                            |--------------------------------------------------------------------------
                                            | Get Latest Payment
                                            |--------------------------------------------------------------------------
                                            | Payment information is stored in the payments table,
                                            | not directly in the manuscripts table.
                                            */
                                            $payment = $manuscript->payments
                                                ->sortByDesc('id')
                                                ->first();

                                            $paymentStatus = strtolower(
                                                trim($payment->payment_status ?? '')
                                            );

                                            $verificationStatus = strtolower(
                                                trim($payment->verification_status ?? '')
                                            );
                                        @endphp

                                        @if(
                                            $paymentStatus === 'paid' &&
                                            $verificationStatus === 'verified'
                                        )
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Paid & Verified
                                            </span>

                                        @elseif($paymentStatus === 'paid')
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-clock me-1"></i>
                                                Paid — Verification Pending
                                            </span>

                                        @elseif(
                                            in_array($paymentStatus, [
                                                'processing',
                                                'pending',
                                            ], true)
                                        )
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-hourglass-split me-1"></i>
                                                Pending
                                            </span>

                                        @elseif($paymentStatus === 'failed')
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Payment Failed
                                            </span>

                                        @elseif(!$payment)
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-dash-circle me-1"></i>
                                                Not Generated
                                            </span>

                                        @else
                                            <span class="badge bg-secondary">
                                                {{ ucfirst($payment->payment_status) }}
                                            </span>
                                        @endif
                                    </td>



                                {{-- Action --}}

                                <td>

                                    <a href="{{ route(
                                        'author.manuscripts.show',
                                        $manuscript->id
                                    ) }}"
                                       class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-eye me-1"></i>

                                        View

                                    </a>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="8"
                                    class="text-center py-5 text-muted">

                                    <i class="bi bi-journal-x fs-1 d-block mb-2"></i>

                                    No manuscripts found.


                                    <div class="mt-3">

                                        <a href="#"
                                           class="btn btn-primary">

                                            <i class="bi bi-plus-circle me-1"></i>

                                            Start New Submission

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
        NOTIFICATIONS + RECENT ACTIVITY
    ========================================================== --}}

    <div class="row g-4">


        {{-- =====================================================
            NOTIFICATIONS
        ====================================================== --}}

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white
                            d-flex justify-content-between">

                    <h5 class="mb-0">

                        <i class="bi bi-bell me-2"></i>

                        Notifications

                    </h5>


                    <a href="#"
                       class="small">

                        View All

                    </a>

                </div>


                <div class="card-body">

                    @forelse($notifications ?? [] as $notification)

                        <a href="{{ $notification->url ?? '#' }}"
                           class="text-decoration-none">

                            <div class="notification-item">

                                <div class="notification-icon">

                                    <i class="bi bi-bell-fill"></i>

                                </div>


                                <div>

                                    <div class="fw-semibold text-dark">

                                        {{ $notification->title }}

                                    </div>


                                    <div class="small text-muted">

                                        {{ Str::limit(
                                            $notification->message,
                                            100
                                        ) }}

                                    </div>


                                    <div class="small text-muted mt-1">

                                        {{ optional(
                                            $notification->created_at
                                        )->diffForHumans() }}

                                    </div>

                                </div>

                            </div>

                        </a>

                    @empty

                        <div class="text-center text-muted py-4">

                            <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>

                            No new notifications.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- =====================================================
            RECENT ACTIVITY
        ====================================================== --}}

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white
                            d-flex justify-content-between">

                    <h5 class="mb-0">

                        <i class="bi bi-clock-history me-2"></i>

                        Recent Activity

                    </h5>


                    <a href="#"
                       class="small">

                        View History

                    </a>

                </div>


                <div class="card-body">

                    @forelse($activities ?? [] as $activity)

                        <div class="activity-item">

                            <div class="activity-icon">

                                <i class="bi bi-clock-history"></i>

                            </div>


                            <div>

                                <div class="fw-semibold">

                                    {{ $activity->activity }}

                                </div>


                                <div class="small text-muted">

                                    {{ $activity->status ?? '' }}

                                    @if($activity->status)

                                        ·

                                    @endif

                                    {{ optional(
                                        $activity->created_at
                                    )->diffForHumans() }}

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="text-center text-muted py-4">

                            <i class="bi bi-clock-history fs-3 d-block mb-2"></i>

                            No recent activity.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================
    CUSTOM CSS
============================================================= --}}

<style>

/* =========================================================
   GENERAL
========================================================= */

.dashboard-header {

    background:
        linear-gradient(
            135deg,
            #172033 0%,
            #243b55 100%
        );

    color: #ffffff;

    border-radius: 18px;

    padding: 30px 35px;

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 20px;

    box-shadow:
        0 10px 30px rgba(23, 32, 51, .15);

}


.dashboard-label {

    font-size: 11px;

    letter-spacing: 2px;

    font-weight: 700;

    color: rgba(255,255,255,.65);

}


.dashboard-title {

    margin: 8px 0 5px;

    font-size: 29px;

    font-weight: 700;

}


.dashboard-subtitle {

    color: rgba(255,255,255,.72);

}


/* =========================================================
   DASHBOARD USER
========================================================= */

.dashboard-user {

    display: flex;

    align-items: center;

    gap: 12px;

    background:
        rgba(255,255,255,.08);

    padding: 10px 15px;

    border-radius: 12px;

}


.dashboard-user strong {

    display: block;

    font-size: 14px;

}


.dashboard-user small {

    display: block;

    margin-top: 3px;

    color:
        rgba(255,255,255,.65);

}


.user-avatar {

    width: 44px;

    height: 44px;

    border-radius: 50%;

    background: #ffffff;

    color: #243b55;

    display: flex;

    align-items: center;

    justify-content: center;

    font-weight: 700;

    font-size: 17px;

}


.author-avatar {

    width: 65px;

    height: 65px;

    border-radius: 50%;

    background: #0d6efd;

    color: #ffffff;

    display: flex;

    align-items: center;

    justify-content: center;

    font-weight: 700;

    font-size: 25px;

    flex-shrink: 0;

}


/* =========================================================
   TECHNICAL ALERTS
========================================================= */

.technical-alert {

    border-radius: 12px;

    padding: 18px 20px;

}


.technical-alert-danger {

    background: #fff5f5;

    border: 1px solid #f5c2c7;

}


.technical-alert-warning {

    background: #fff9e6;

    border: 1px solid #ffe69c;

}


.technical-alert-info {

    background: #eef8ff;

    border: 1px solid #b6dfff;

}


/* =========================================================
   CARDS
========================================================= */

.card {

    transition:
        transform .2s ease,
        box-shadow .2s ease;

}


.card:hover {

    box-shadow:
        0 8px 24px rgba(0,0,0,.07) !important;

}


/* =========================================================
   STATISTICS
========================================================= */

.row .card {

    border-radius: 12px;

}


/* =========================================================
   NOTIFICATIONS
========================================================= */

.notification-item {

    display: flex;

    gap: 12px;

    padding: 10px 0;

}


.notification-icon {

    width: 38px;

    height: 38px;

    border-radius: 10px;

    background: #eef4ff;

    color: #0d6efd;

    display: flex;

    align-items: center;

    justify-content: center;

    flex-shrink: 0;

}


/* =========================================================
   ACTIVITY
========================================================= */

.activity-item {

    display: flex;

    gap: 12px;

    padding: 10px 0;

}


.activity-icon {

    width: 38px;

    height: 38px;

    border-radius: 10px;

    background: #f1f3f5;

    color: #6c757d;

    display: flex;

    align-items: center;

    justify-content: center;

    flex-shrink: 0;

}


/* =========================================================
   TABLE
========================================================= */

.table th {

    font-size: 12px;

    text-transform: uppercase;

    letter-spacing: .4px;

    white-space: nowrap;

}


.table td {

    font-size: 13px;

}


/* =========================================================
   BADGES
========================================================= */

.badge {

    font-weight: 600;

    padding: 6px 9px;

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 768px) {

    .dashboard-header {

        padding: 25px;

        flex-direction: column;

        align-items: flex-start;

    }


    .dashboard-title {

        font-size: 23px;

    }


    .dashboard-user {

        width: 100%;

    }


    .author-avatar {

        width: 55px;

        height: 55px;

        font-size: 21px;

    }


    .technical-alert {

        padding: 15px;

    }

}


/* =========================================================
   SMALL MOBILE
========================================================= */

@media (max-width: 576px) {

    .container-fluid {

        padding-left: 12px;

        padding-right: 12px;

    }


    .dashboard-header {

        border-radius: 14px;

    }


    .dashboard-title {

        font-size: 21px;

    }


    .table {

        min-width: 950px;

    }

}

</style>

@endsection