@extends('author.layouts.app')

@section('title', 'Author Dashboard')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================
        DASHBOARD HEADER
    ========================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Author Dashboard
            </h2>

            <p class="text-muted mb-0">
                Welcome, {{ auth()->user()->name }}
            </p>
        </div>

        <div class="d-flex gap-2 mt-3 mt-md-0">

            <a href="{{ route('author.profile.edit') }}"
               class="btn btn-outline-primary">
                <i class="bi bi-person"></i>
                My Profile
            </a>

            <a href="#"
               class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>
                New Submission
            </a>

        </div>

    </div>


    {{-- =========================
        AUTHOR PROFILE SUMMARY
    ========================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <div class="d-flex align-items-center">

                        <div class="rounded-circle bg-primary text-white
                                    d-flex align-items-center justify-content-center"
                             style="width:65px;height:65px;font-size:25px;">

                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}

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


                <div class="col-md-4 mt-3 mt-md-0">

                    <div class="d-flex justify-content-between mb-1">

                        <span class="small fw-semibold">
                            Profile Completion
                        </span>

                        <span class="small fw-semibold">
                            {{ $profileCompletion ?? 0 }}%
                        </span>

                    </div>

                    <div class="progress" style="height:8px;">

                        <div class="progress-bar"
                             role="progressbar"
                             style="width: {{ $profileCompletion ?? 0 }}%;">

                        </div>

                    </div>

                    @if(($profileCompletion ?? 0) < 100)

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

            </div>

        </div>

    </div>


    {{-- =========================
        STATISTICS
    ========================== --}}
    <div class="row g-3 mb-4">


        {{-- Total Manuscripts --}}
        <div class="col-xl-3 col-md-6">

            <a href="#"
               class="text-decoration-none">

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

            </a>

        </div>


        {{-- Drafts --}}
        <div class="col-xl-3 col-md-6">

            <a href="#"
               class="text-decoration-none">

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

            </a>

        </div>


        {{-- Submitted --}}
        <div class="col-xl-3 col-md-6">

            <a href="#"
               class="text-decoration-none">

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

            </a>

        </div>


        {{-- Under Review --}}
        <div class="col-xl-3 col-md-6">

            <a href="#"
               class="text-decoration-none">

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

            </a>

        </div>


        {{-- Payment Pending --}}
        <div class="col-xl-3 col-md-6">

            <a href="#"
               class="text-decoration-none">

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

            </a>

        </div>


        {{-- Revision --}}
        <div class="col-xl-3 col-md-6">

            <a href="#"
               class="text-decoration-none">

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

            </a>

        </div>


        {{-- Accepted --}}
        <div class="col-xl-3 col-md-6">

            <a href="#"
               class="text-decoration-none">

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

            </a>

        </div>


        {{-- Published --}}
        <div class="col-xl-3 col-md-6">

            <a href="#"
               class="text-decoration-none">

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

            </a>

        </div>

    </div>


    {{-- =========================
        QUICK ACTIONS
    ========================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Quick Actions
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-lg-3 col-md-6">

                    <a href="#"
                       class="btn btn-primary w-100 py-3">

                        <i class="bi bi-plus-circle me-1"></i>

                        New Submission

                    </a>

                </div>


                <div class="col-lg-3 col-md-6">

                    <a href="{{ route('author.profile.edit') }}"
                       class="btn btn-outline-primary w-100 py-3">

                        <i class="bi bi-person me-1"></i>

                        Complete Profile

                    </a>

                </div>


                <div class="col-lg-3 col-md-6">

                    <a href="#"
                       class="btn btn-outline-success w-100 py-3">

                        <i class="bi bi-credit-card me-1"></i>

                        Payments & Invoices

                    </a>

                </div>


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


    {{-- =========================
        PENDING ACTIONS
    ========================== --}}
    @if(isset($pendingActions) && $pendingActions->count())

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    <i class="bi bi-exclamation-circle text-warning"></i>
                    Pending Actions
                </h5>

            </div>

            <div class="card-body">

                @foreach($pendingActions as $action)

                    <div class="alert alert-{{ $action->type ?? 'warning' }}
                                d-flex justify-content-between align-items-center">

                        <div>

                            <strong>
                                {{ $action->title }}
                            </strong>

                            <div class="small">
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


    {{-- =========================
        RECENT MANUSCRIPTS
    ========================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white
                    d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                My Manuscripts
            </h5>

            <a href="#"
               class="btn btn-sm btn-outline-primary">

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

                            <tr>

                                <td>

                                    <strong>
                                        {{ $manuscript->manuscript_id }}
                                    </strong>

                                </td>


                                <td>

                                    <div class="fw-semibold">

                                        {{ Str::limit($manuscript->title, 45) }}

                                    </div>

                                </td>


                                <td>

                                    {{ $manuscript->article_type ?? '-' }}

                                </td>


                                <td>

                                    {{ optional($manuscript->submitted_at)->format('d-M-Y') }}

                                </td>


                                <td>

                                    V{{ $manuscript->version ?? '1.0' }}

                                </td>


                                <td>

                                    @php

                                        $status = $manuscript->status ?? 'draft';

                                        $statusClass = match($status) {

                                            'draft' => 'secondary',

                                            'submitted' => 'info',

                                            'technical_check' => 'warning',

                                            'technical_correction' => 'danger',

                                            'payment_pending' => 'warning',

                                            'under_review' => 'primary',

                                            'major_revision' => 'warning',

                                            'minor_revision' => 'warning',

                                            'accepted' => 'success',

                                            'rejected' => 'danger',

                                            'published' => 'success',

                                            default => 'secondary'

                                        };

                                    @endphp


                                    <span class="badge bg-{{ $statusClass }}">

                                        {{ ucwords(str_replace('_', ' ', $status)) }}

                                    </span>

                                </td>


                                <td>

                                    @php

                                        $paymentStatus =
                                            $manuscript->payment_status
                                            ?? 'Pending';

                                    @endphp


                                    @if(strtolower($paymentStatus) === 'successful' ||
                                        strtolower($paymentStatus) === 'verified')

                                        <span class="badge bg-success">
                                            Verified
                                        </span>

                                    @elseif(strtolower($paymentStatus) === 'processing')

                                        <span class="badge bg-warning text-dark">
                                            Processing
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Pending
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <a href="{{ route('author.manuscripts.show', $manuscript->id) }}"
                                       class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-eye"></i>
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


    {{-- =========================
        TWO COLUMN SECTION
    ========================== --}}
    <div class="row g-4">


        {{-- Notifications --}}
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white
                            d-flex justify-content-between">

                    <h5 class="mb-0">
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

                            <div class="d-flex mb-3">

                                <div class="me-3">

                                    <i class="bi bi-bell-fill text-primary"></i>

                                </div>

                                <div>

                                    <div class="fw-semibold text-dark">

                                        {{ $notification->title }}

                                    </div>

                                    <div class="small text-muted">

                                        {{ Str::limit($notification->message, 80) }}

                                    </div>

                                    <div class="small text-muted">

                                        {{ optional($notification->created_at)->diffForHumans() }}

                                    </div>

                                </div>

                            </div>

                        </a>

                    @empty

                        <div class="text-center text-muted py-4">

                            No new notifications.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- Recent Activity --}}
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white
                            d-flex justify-content-between">

                    <h5 class="mb-0">
                        Recent Activity
                    </h5>

                    <a href="#"
                       class="small">

                        View History

                    </a>

                </div>


                <div class="card-body">

                    @forelse($activities ?? [] as $activity)

                        <div class="d-flex mb-3">

                            <div class="me-3">

                                <i class="bi bi-clock-history text-primary"></i>

                            </div>

                            <div>

                                <div class="fw-semibold">

                                    {{ $activity->activity }}

                                </div>

                                <div class="small text-muted">

                                    {{ $activity->status ?? '' }}

                                    ·

                                    {{ optional($activity->created_at)->diffForHumans() }}

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="text-center text-muted py-4">

                            No recent activity.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>


</div>

@endsection