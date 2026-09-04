@extends('admin.layouts.app')

@section('title', 'Technical Check')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Basic Manuscript Information
    |--------------------------------------------------------------------------
    */

    $manuscriptNumber = $manuscript->manuscript_id ?? ('#' . $manuscript->id);

    $technicalCheckNumber = $technicalCheck
        ? $technicalCheck->check_number
        : null;


    /*
    |--------------------------------------------------------------------------
    | Status Labels
    |--------------------------------------------------------------------------
    */

    $manuscriptStatus = $manuscript->status ?? 'unknown';

    $statusLabels = [
        'draft'                => 'Draft',
        'submitted'            => 'Submitted',
        'technical_check'     => 'Technical Check',
        'technical_correction'=> 'Technical Correction',
        'payment_required'     => 'Payment Required',
        'payment_correction'  => 'Payment Correction',
        'payment_verified'    => 'Payment Verified',
        'editorial_assessment' => 'Editorial Assessment',
        'similarity_check'    => 'Similarity Check',
        'under_review'        => 'Under Review',
        'revision_required'  => 'Revision Required',
        'accepted'             => 'Accepted',
        'rejected'             => 'Rejected',
        'copy_editing'        => 'Copy Editing',
        'proofreading'        => 'Proofreading',
        'production'          => 'Production',
        'published'           => 'Published',
    ];

    $statusLabel = $statusLabels[$manuscriptStatus]
        ?? ucwords(str_replace('_', ' ', $manuscriptStatus));


    /*
    |--------------------------------------------------------------------------
    | Technical Check Status
    |--------------------------------------------------------------------------
    */

    $technicalStatus = $technicalCheck->status ?? null;

    $technicalStatusLabels = [
        'pending'             => 'Pending',
        'in_progress'         => 'In Progress',
        'correction_required' => 'Correction Required',
        'passed'              => 'Passed',
        'failed'              => 'Failed',
    ];

    $technicalStatusLabel = $technicalStatusLabels[$technicalStatus]
        ?? ($technicalStatus
            ? ucwords(str_replace('_', ' ', $technicalStatus))
            : 'Not Started');


    /*
    |--------------------------------------------------------------------------
    | Technical Status Badge
    |--------------------------------------------------------------------------
    */

    $technicalStatusClass = match ($technicalStatus) {
        'passed'              => 'bg-success-subtle text-success',
        'in_progress'        => 'bg-primary-subtle text-primary',
        'correction_required' => 'bg-warning-subtle text-warning-emphasis',
        'failed'              => 'bg-danger-subtle text-danger',
        'pending'             => 'bg-secondary-subtle text-secondary',
        default               => 'bg-secondary-subtle text-secondary',
    };


    /*
    |--------------------------------------------------------------------------
    | Checklist Statistics
    |--------------------------------------------------------------------------
    */

    $totalItems = $technicalCheck
        ? $technicalCheck->items->count()
        : 0;

    $passedItems = $technicalCheck
        ? $technicalCheck->items->where('result', 'pass')->count()
        : 0;

    $failedItems = $technicalCheck
        ? $technicalCheck->items->where('result', 'fail')->count()
        : 0;

    $pendingItems = $technicalCheck
        ? $technicalCheck->items->where('result', 'pending')->count()
        : 0;


    /*
    |--------------------------------------------------------------------------
    | Ready to Complete
    |--------------------------------------------------------------------------
    */

    $readyToComplete =
        $technicalCheck &&
        $totalItems > 0 &&
        $failedItems === 0 &&
        $pendingItems === 0 &&
        $passedItems === $totalItems &&
        $technicalCheck->status !== 'passed';


    /*
    |--------------------------------------------------------------------------
    | Can Edit Checklist
    |--------------------------------------------------------------------------
    */

    $canEditChecklist =
        $technicalCheck &&
        in_array(
            $technicalCheck->status,
            ['pending', 'in_progress']
        );


    /*
    |--------------------------------------------------------------------------
    | Workflow State
    |--------------------------------------------------------------------------
    */

    $isTechnicalReview =
        $manuscriptStatus === 'submitted' ||
        $manuscript->current_stage === 'technical_review';

    $isCorrection =
        $manuscriptStatus === 'technical_correction' ||
        $manuscript->current_stage === 'author_correction';

    $isPayment =
        $manuscriptStatus === 'payment_required' ||
        $manuscript->current_stage === 'payment';

    $isPaymentCorrection =
        $manuscriptStatus === 'payment_correction' ||
        $manuscript->current_stage === 'payment_correction';


    /*
    |--------------------------------------------------------------------------
    | Issues
    |--------------------------------------------------------------------------
    */

    $issues = $technicalCheck
        ? $technicalCheck->issues
        : collect();

    $openIssues = $issues->where('status', 'open')->count();


    /*
    |--------------------------------------------------------------------------
    | Progress Percentage
    |--------------------------------------------------------------------------
    */

    $progressPercentage = $totalItems > 0
        ? round(($passedItems / $totalItems) * 100)
        : 0;
@endphp


<style>

    /* ============================================================
       PAGE
    ============================================================ */

    .technical-check-page {
        max-width: 1600px;
        margin: 0 auto;
        padding-bottom: 40px;
    }


    /* ============================================================
       HEADER
    ============================================================ */

    .tc-header {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        padding: 22px 24px;
        margin-bottom: 20px;
        box-shadow: 0 3px 14px rgba(0, 0, 0, .04);
    }

    .tc-title {
        font-size: 22px;
        font-weight: 700;
        color: #212529;
        margin-bottom: 4px;
    }

    .tc-subtitle {
        color: #6c757d;
        font-size: 13px;
    }

    .tc-header-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }


    /* ============================================================
       CARDS
    ============================================================ */

    .tc-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        box-shadow: 0 3px 14px rgba(0, 0, 0, .04);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .tc-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #edf0f2;
        background: #fafbfc;
    }

    .tc-card-body {
        padding: 20px;
    }

    .tc-card-title {
        font-size: 15px;
        font-weight: 700;
        margin: 0;
        color: #212529;
    }

    .tc-card-description {
        color: #6c757d;
        font-size: 12px;
        margin-top: 3px;
    }


    /* ============================================================
       WORKFLOW
    ============================================================ */

    .workflow-wrapper {
        padding: 6px 4px;
    }

    .workflow {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        position: relative;
    }

    .workflow::before {
        content: "";
        position: absolute;
        top: 18px;
        left: 8%;
        right: 8%;
        height: 2px;
        background: #dee2e6;
        z-index: 0;
    }

    .workflow-step {
        position: relative;
        z-index: 1;
        text-align: center;
        flex: 1;
    }

    .workflow-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #ced4da;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        color: #6c757d;
        margin-bottom: 7px;
    }

    .workflow-step.active .workflow-icon {
        border-color: #0d6efd;
        background: #0d6efd;
        color: #fff;
    }

    .workflow-step.completed .workflow-icon {
        border-color: #198754;
        background: #198754;
        color: #fff;
    }

    .workflow-label {
        font-size: 11px;
        font-weight: 600;
        color: #6c757d;
    }

    .workflow-step.active .workflow-label,
    .workflow-step.completed .workflow-label {
        color: #212529;
    }


    /* ============================================================
       CORRECTION BRANCH
    ============================================================ */

    .correction-box {
        background: #fff8e6;
        border: 1px solid #ffe69c;
        border-radius: 12px;
        padding: 16px;
    }

    .correction-title {
        font-size: 13px;
        font-weight: 700;
        color: #664d03;
        margin-bottom: 6px;
    }

    .correction-text {
        font-size: 12px;
        color: #664d03;
        margin-bottom: 0;
    }


    /* ============================================================
       SUMMARY
    ============================================================ */

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }

    .summary-item {
        background: #f8f9fa;
        border: 1px solid #edf0f2;
        border-radius: 10px;
        padding: 13px;
    }

    .summary-label {
        color: #6c757d;
        font-size: 11px;
        margin-bottom: 4px;
    }

    .summary-value {
        color: #212529;
        font-size: 13px;
        font-weight: 700;
        word-break: break-word;
    }


    /* ============================================================
       STAT CARDS
    ============================================================ */

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 3px 14px rgba(0, 0, 0, .03);
    }

    .stat-label {
        color: #6c757d;
        font-size: 11px;
        margin-bottom: 6px;
    }

    .stat-number {
        font-size: 24px;
        line-height: 1;
        font-weight: 700;
        color: #212529;
    }


    /* ============================================================
       PROGRESS
    ============================================================ */

    .tc-progress {
        height: 8px;
        border-radius: 20px;
        background: #e9ecef;
        overflow: hidden;
    }

    .tc-progress-bar {
        height: 100%;
        background: #198754;
        border-radius: 20px;
        transition: width .25s ease;
    }


    /* ============================================================
       CHECKLIST TABLE
    ============================================================ */

    .checklist-table {
        margin-bottom: 0;
    }

    .checklist-table th {
        background: #f8f9fa;
        color: #495057;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .02em;
        white-space: nowrap;
    }

    .checklist-table td {
        vertical-align: middle;
        font-size: 13px;
    }

    .check-name {
        font-weight: 600;
        color: #212529;
    }

    .check-key {
        color: #6c757d;
        font-size: 11px;
        font-family: monospace;
    }

    .result-select {
        min-width: 125px;
    }

    .comment-input {
        min-width: 200px;
    }


    /* ============================================================
       MOBILE CHECKLIST
    ============================================================ */

    .checklist-mobile {
        display: none;
    }

    .mobile-check-item {
        border: 1px solid #e9ecef;
        border-radius: 11px;
        padding: 14px;
        margin-bottom: 10px;
        background: #fff;
    }

    .mobile-check-name {
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .mobile-check-key {
        color: #6c757d;
        font-size: 10px;
        margin-bottom: 10px;
    }


    /* ============================================================
       DECISION AREA
    ============================================================ */

    .decision-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 18px;
        height: 100%;
    }

    .decision-card.success {
        border-color: #badbcc;
        background: #f4fbf7;
    }

    .decision-card.warning {
        border-color: #ffe69c;
        background: #fffaf0;
    }

    .decision-card.danger {
        border-color: #f1aeb5;
        background: #fff5f5;
    }

    .decision-title {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .decision-description {
        color: #6c757d;
        font-size: 12px;
        margin-bottom: 15px;
    }


    /* ============================================================
       PAYMENT TRANSITION
    ============================================================ */

    .payment-transition {
        border: 1px solid #badbcc;
        background: #f4fbf7;
        border-radius: 14px;
        padding: 20px;
    }

    .payment-transition-icon {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: #198754;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .payment-transition-title {
        font-size: 15px;
        font-weight: 700;
        color: #146c43;
        margin-bottom: 4px;
    }

    .payment-transition-text {
        font-size: 12px;
        color: #495057;
        margin-bottom: 0;
    }


    /* ============================================================
       ISSUE CARD
    ============================================================ */

    .issue-card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 10px;
    }

    .issue-description {
        font-size: 13px;
        color: #212529;
    }

    .issue-action {
        font-size: 12px;
        color: #6c757d;
        margin-top: 5px;
    }


    /* ============================================================
       EMPTY
    ============================================================ */

    .tc-empty {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }

    .tc-empty i {
        font-size: 34px;
        margin-bottom: 10px;
    }


    /* ============================================================
       ALERT
    ============================================================ */

    .tc-alert {
        border-radius: 11px;
        border-width: 1px;
        font-size: 13px;
    }


    /* ============================================================
       FOOTER WORKFLOW
    ============================================================ */

    .workflow-note {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 15px 18px;
        color: #6c757d;
        font-size: 12px;
    }


    /* ============================================================
       RESPONSIVE
    ============================================================ */

    @media (max-width: 991.98px) {

        .summary-grid,
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .workflow-label {
            font-size: 10px;
        }
    }


    @media (max-width: 767.98px) {

        .technical-check-page {
            padding-left: 8px;
            padding-right: 8px;
        }

        .tc-header {
            padding: 16px;
        }

        .tc-title {
            font-size: 19px;
        }

        .tc-header-actions {
            width: 100%;
        }

        .tc-header-actions .btn {
            flex: 1;
        }

        .summary-grid,
        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }

        .workflow-wrapper {
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .workflow {
            min-width: 600px;
        }

        .workflow::before {
            left: 6%;
            right: 6%;
        }

        .checklist-desktop {
            display: none;
        }

        .checklist-mobile {
            display: block;
        }

        .decision-actions {
            width: 100%;
        }

        .decision-actions form,
        .decision-actions button {
            width: 100%;
        }

        .payment-transition {
            padding: 16px;
        }
    }


    @media (max-width: 480px) {

        .summary-grid,
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .tc-card-body {
            padding: 15px;
        }

        .tc-card-header {
            padding: 14px 15px;
        }
    }

</style>


<div class="technical-check-page">


    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="tc-header">

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

            <div>

                <div class="d-flex align-items-center gap-2 flex-wrap">

                    <div class="tc-title">
                        Technical Check
                    </div>

                    @if($technicalCheck)

                        <span class="badge rounded-pill {{ $technicalStatusClass }}">
                            {{ $technicalStatusLabel }}
                        </span>

                    @endif

                </div>

                <div class="tc-subtitle">

                    {{ $manuscriptNumber }}

                    <span class="mx-1">•</span>

                    {{ $manuscript->title }}

                </div>

            </div>


            <div class="tc-header-actions">

                <a href="{{ route('admin.manuscripts.technical-review.index') }}"
                   class="btn btn-outline-secondary btn-sm">

                    <i class="bi bi-arrow-left me-1"></i>
                    Technical Review

                </a>


                <a href="{{ route('admin.manuscripts.show', $manuscript) }}"
                   class="btn btn-outline-primary btn-sm">

                    <i class="bi bi-file-earmark-text me-1"></i>
                    View Manuscript

                </a>

            </div>

        </div>

    </div>



    {{-- =========================================================
         FLASH MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success tc-alert d-flex align-items-start gap-2">

            <i class="bi bi-check-circle-fill mt-1"></i>

            <div>
                {{ session('success') }}
            </div>

        </div>

    @endif


    @if(session('warning'))

        <div class="alert alert-warning tc-alert d-flex align-items-start gap-2">

            <i class="bi bi-exclamation-triangle-fill mt-1"></i>

            <div>
                {{ session('warning') }}
            </div>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger tc-alert d-flex align-items-start gap-2">

            <i class="bi bi-x-circle-fill mt-1"></i>

            <div>
                {{ session('error') }}
            </div>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger tc-alert">

            <div class="fw-bold mb-2">
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif



    {{-- =========================================================
         WORKFLOW
    ========================================================== --}}

    <div class="tc-card">

        <div class="tc-card-header">

            <div class="tc-card-title">
                <i class="bi bi-diagram-3 me-2"></i>
                Manuscript Workflow
            </div>

            <div class="tc-card-description">
                Current manuscript processing workflow
            </div>

        </div>

        <div class="tc-card-body workflow-wrapper">

            <div class="workflow">


                {{-- Submission --}}

                <div class="workflow-step completed">

                    <div class="workflow-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>

                    <div class="workflow-label">
                        Submission
                    </div>

                </div>


                {{-- Technical Review --}}

                <div class="workflow-step
                    {{ $isTechnicalReview ? 'active' : '' }}
                    {{ $technicalCheck ? 'completed' : '' }}">

                    <div class="workflow-icon">

                        @if($technicalCheck)

                            <i class="bi bi-check-lg"></i>

                        @else

                            <i class="bi bi-search"></i>

                        @endif

                    </div>

                    <div class="workflow-label">
                        Technical Review
                    </div>

                </div>


                {{-- Technical Check --}}

                <div class="workflow-step
                    {{ $technicalCheck && $technicalCheck->status === 'in_progress' ? 'active' : '' }}
                    {{ $technicalCheck && $technicalCheck->status === 'passed' ? 'completed' : '' }}">

                    <div class="workflow-icon">

                        @if($technicalCheck && $technicalCheck->status === 'passed')

                            <i class="bi bi-check-circle"></i>

                        @else

                            <i class="bi bi-list-check"></i>

                        @endif

                    </div>

                    <div class="workflow-label">
                        Technical Check
                    </div>

                </div>


                {{-- Payment --}}

                <div class="workflow-step
                    {{ $isPayment || $isPaymentCorrection ? 'active' : '' }}">

                    <div class="workflow-icon">

                        <i class="bi bi-credit-card"></i>

                    </div>

                    <div class="workflow-label">
                        Payment
                    </div>

                </div>


                {{-- Editorial Assessment --}}

                <div class="workflow-step">

                    <div class="workflow-icon">

                        <i class="bi bi-person-check"></i>

                    </div>

                    <div class="workflow-label">
                        Editorial Assessment
                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
         CORRECTION WORKFLOW
    ========================================================== --}}

    @if($isCorrection)

        <div class="tc-card">

            <div class="tc-card-body">

                <div class="correction-box">

                    <div class="d-flex align-items-start gap-3">

                        <i class="bi bi-arrow-counterclockwise fs-4"></i>

                        <div>

                            <div class="correction-title">
                                Technical Correction Required
                            </div>

                            <p class="correction-text">

                                This manuscript was returned to the author
                                because technical corrections are required.

                                After the author resubmits the corrected
                                manuscript, a <strong>new Technical Check</strong>
                                will be created.

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif



    {{-- =========================================================
         SUMMARY
    ========================================================== --}}

    <div class="tc-card">

        <div class="tc-card-header">

            <div class="tc-card-title">
                <i class="bi bi-info-circle me-2"></i>
                Manuscript Information
            </div>

        </div>

        <div class="tc-card-body">

            <div class="summary-grid">

                <div class="summary-item">

                    <div class="summary-label">
                        Manuscript ID
                    </div>

                    <div class="summary-value">
                        {{ $manuscriptNumber }}
                    </div>

                </div>


                <div class="summary-item">

                    <div class="summary-label">
                        Current Status
                    </div>

                    <div class="summary-value">
                        {{ $statusLabel }}
                    </div>

                </div>


                <div class="summary-item">

                    <div class="summary-label">
                        Current Stage
                    </div>

                    <div class="summary-value">
                        {{ ucwords(str_replace('_', ' ', $manuscript->current_stage ?? 'N/A')) }}
                    </div>

                </div>


                <div class="summary-item">

                    <div class="summary-label">
                        Submission Version
                    </div>

                    <div class="summary-value">
                        {{ $manuscript->submission_version ?? 1 }}
                    </div>

                </div>


                <div class="summary-item">

                    <div class="summary-label">
                        Technical Check
                    </div>

                    <div class="summary-value">

                        @if($technicalCheck)

                            Check #{{ $technicalCheckNumber }}

                        @else

                            Not Started

                        @endif

                    </div>

                </div>


                <div class="summary-item">

                    <div class="summary-label">
                        Assigned To
                    </div>

                    <div class="summary-value">

                        @if($technicalCheck?->assignedUser)

                            {{ $technicalCheck->assignedUser->name }}

                        @else

                            Not Assigned

                        @endif

                    </div>

                </div>


                <div class="summary-item">

                    <div class="summary-label">
                        Started By
                    </div>

                    <div class="summary-value">

                        @if($technicalCheck?->startedBy)

                            {{ $technicalCheck->startedBy->name }}

                        @else

                            —

                        @endif

                    </div>

                </div>


                <div class="summary-item">

                    <div class="summary-label">
                        Started At
                    </div>

                    <div class="summary-value">

                        @if($technicalCheck?->started_at)

                            {{ $technicalCheck->started_at->format('d M Y, h:i A') }}

                        @else

                            —

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
         NO TECHNICAL CHECK
    ========================================================== --}}

    @if(
    !$technicalCheck ||
    in_array($technicalCheck->status, ['correction_required', 'failed'], true)
    )

        <div class="tc-card">

            <div class="tc-empty">

                <i class="bi bi-clipboard-check d-block"></i>

                <h5 class="fw-bold mb-2">
                    Technical Check Not Started
                </h5>

                <p class="mb-3">
                    This manuscript is waiting for a technical check.
                </p>


                @can('technical_check.perform')

                    <form method="POST"
                          action="{{ route(
                              'admin.manuscripts.technical-check.start',
                              $manuscript
                          ) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-primary px-4">

                            <i class="bi bi-play-circle me-1"></i>

                            Start Technical Check

                        </button>

                    </form>

                @endcan

            </div>

        </div>


    @else


        {{-- =====================================================
             STATISTICS
        ====================================================== --}}

        <div class="stats-grid">

            <div class="stat-card">

                <div class="stat-label">
                    Total Checklist Items
                </div>

                <div class="stat-number">
                    {{ $totalItems }}
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-label">
                    Passed
                </div>

                <div class="stat-number text-success">
                    {{ $passedItems }}
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-label">
                    Failed
                </div>

                <div class="stat-number text-danger">
                    {{ $failedItems }}
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-label">
                    Pending
                </div>

                <div class="stat-number text-warning">
                    {{ $pendingItems }}
                </div>

            </div>

        </div>



        {{-- =====================================================
             PROGRESS
        ====================================================== --}}

        <div class="tc-card">

            <div class="tc-card-body">

                <div class="d-flex justify-content-between align-items-center mb-2">

                    <div class="fw-semibold small">
                        Technical Check Progress
                    </div>

                    <div class="small text-muted">
                        {{ $passedItems }} / {{ $totalItems }}
                        ({{ $progressPercentage }}%)
                    </div>

                </div>

                <div class="tc-progress">

                    <div class="tc-progress-bar"
                         style="width: {{ $progressPercentage }}%;">
                    </div>

                </div>

            </div>

        </div>



        {{-- =====================================================
             CHECKLIST
        ====================================================== --}}

        <div class="tc-card">

            <div class="tc-card-header">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div>

                        <div class="tc-card-title">

                            <i class="bi bi-list-check me-2"></i>

                            Technical Checklist

                        </div>

                        <div class="tc-card-description">

                            Review every technical requirement before
                            completing the technical check.

                        </div>

                    </div>


                    <span class="badge rounded-pill
                        {{ $technicalStatusClass }}">

                        {{ $technicalStatusLabel }}

                    </span>

                </div>

            </div>


            <div class="tc-card-body">


                {{-- =================================================
                     DESKTOP CHECKLIST
                ================================================== --}}

                <div class="table-responsive checklist-desktop">

                    <form method="POST"
                          action="{{ route(
                              'admin.manuscripts.technical-check.update',
                              $technicalCheck
                          ) }}">

                        @csrf
                        @method('PUT')


                        <table class="table table-hover checklist-table align-middle">

                            <thead>

                                <tr>

                                    <th style="width: 55px;">
                                        #
                                    </th>

                                    <th>
                                        Requirement
                                    </th>

                                    <th style="width: 150px;">
                                        Result
                                    </th>

                                    <th style="width: 260px;">
                                        Comments
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($technicalCheck->items->sortBy('sort_order') as $item)

                                    <tr>

                                        <td class="text-muted">

                                            {{ $item->sort_order }}

                                        </td>


                                        <td>

                                            <div class="check-name">

                                                {{ $item->check_name }}

                                            </div>

                                            <div class="check-key">

                                                {{ $item->check_key }}

                                            </div>

                                        </td>


                                        <td>

                                            <select
                                                name="items[{{ $item->id }}][result]"
                                                class="form-select form-select-sm result-select"
                                                {{ !$canEditChecklist ? 'disabled' : '' }}>

                                                <option value="pending"
                                                    {{ $item->result === 'pending' ? 'selected' : '' }}>
                                                    Pending
                                                </option>

                                                <option value="pass"
                                                    {{ $item->result === 'pass' ? 'selected' : '' }}>
                                                    Pass
                                                </option>

                                                <option value="fail"
                                                    {{ $item->result === 'fail' ? 'selected' : '' }}>
                                                    Fail
                                                </option>

                                            </select>

                                        </td>


                                        <td>

                                            <input
                                                type="text"
                                                name="items[{{ $item->id }}][comment]"
                                                value="{{ old(
                                                    'items.' . $item->id . '.comment',
                                                    $item->comment
                                                ) }}"
                                                class="form-control form-control-sm"
                                                placeholder="Optional comment"
                                                {{ !$canEditChecklist ? 'disabled' : '' }}>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="4"
                                            class="text-center text-muted py-4">

                                            No checklist items found.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>


                        @if($canEditChecklist && $totalItems > 0)

                            <div class="d-flex justify-content-end mt-3">

                                <button type="submit"
                                        class="btn btn-primary">

                                    <i class="bi bi-save me-1"></i>

                                    Save Checklist

                                </button>

                            </div>

                        @endif

                    </form>

                </div>



                {{-- =================================================
                     MOBILE CHECKLIST
                ================================================== --}}

                <div class="checklist-mobile">

                    <form method="POST"
                          action="{{ route(
                              'admin.manuscripts.technical-check.update',
                              $technicalCheck
                          ) }}">

                        @csrf
                        @method('PUT')


                        @forelse($technicalCheck->items->sortBy('sort_order') as $item)

                            <div class="mobile-check-item">

                                <div class="mobile-check-name">

                                    {{ $item->sort_order }}.
                                    {{ $item->check_name }}

                                </div>

                                <div class="mobile-check-key">

                                    {{ $item->check_key }}

                                </div>


                                <div class="mb-2">

                                    <label class="form-label small fw-semibold">
                                        Result
                                    </label>

                                    <select
                                        name="items[{{ $item->id }}][result]"
                                        class="form-select form-select-sm"
                                        {{ !$canEditChecklist ? 'disabled' : '' }}>

                                        <option value="pending"
                                            {{ $item->result === 'pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>

                                        <option value="pass"
                                            {{ $item->result === 'pass' ? 'selected' : '' }}>
                                            Pass
                                        </option>

                                        <option value="fail"
                                            {{ $item->result === 'fail' ? 'selected' : '' }}>
                                            Fail
                                        </option>

                                    </select>

                                </div>


                                <div>

                                    <label class="form-label small fw-semibold">
                                        Comments
                                    </label>

                                    <input
                                        type="text"
                                        name="items[{{ $item->id }}][comments]"
                                        value="{{ old(
                                            'items.' . $item->id . '.comments',
                                            $item->comments
                                        ) }}"
                                        class="form-control form-control-sm"
                                        placeholder="Optional comment"
                                        {{ !$canEditChecklist ? 'disabled' : '' }}>

                                </div>

                            </div>

                        @empty

                            <div class="tc-empty">

                                <i class="bi bi-list-check d-block"></i>

                                No checklist items found.

                            </div>

                        @endforelse


                        @if($canEditChecklist && $totalItems > 0)

                            <button type="submit"
                                    class="btn btn-primary w-100">

                                <i class="bi bi-save me-1"></i>

                                Save Checklist

                            </button>

                        @endif

                    </form>

                </div>

            </div>

        </div>



        {{-- =====================================================
             READY TO PASS
        ====================================================== --}}

        @if($readyToComplete)

            <div class="alert alert-success tc-alert mb-4">

                <div class="d-flex align-items-start gap-2">

                    <i class="bi bi-check-circle-fill mt-1"></i>

                    <div>

                        <strong>
                            Technical check is ready to pass.
                        </strong>

                        <div class="mt-1">

                            All {{ $totalItems }} checklist items
                            have passed.

                            Click
                            <strong>
                                Complete & Forward to Payment
                            </strong>
                            to move this manuscript to the
                            <strong>Payment</strong> stage.

                        </div>

                    </div>

                </div>

            </div>

        @endif



        {{-- =====================================================
             DECISION
        ====================================================== --}}

        @if($technicalCheck->status !== 'passed')

            <div class="tc-card">

                <div class="tc-card-header">

                    <div class="tc-card-title">

                        <i class="bi bi-signpost-split me-2"></i>

                        Technical Check Decision

                    </div>

                    <div class="tc-card-description">

                        Select the appropriate action after reviewing
                        the checklist.

                    </div>

                </div>


                <div class="tc-card-body">

                    <div class="row g-3">


                        {{-- =========================================
                             PASS / PAYMENT
                        ========================================== --}}

                        <div class="col-lg-7">

                            <div class="decision-card success">

                                <div class="d-flex align-items-start gap-3">

                                    <div>

                                        <i class="bi bi-check-circle-fill fs-3 text-success"></i>

                                    </div>

                                    <div class="flex-grow-1">

                                        <div class="decision-title text-success">

                                            Pass Technical Check

                                        </div>

                                        <div class="decision-description">

                                            When all technical checklist
                                            items pass, completing this
                                            check will automatically move
                                            the manuscript to
                                            <strong>Payment Required</strong>.

                                        </div>


                                        @if($failedItems > 0)

                                            <div class="alert alert-danger py-2 px-3 small mb-3">

                                                <i class="bi bi-x-circle me-1"></i>

                                                {{ $failedItems }}
                                                checklist item(s) failed.

                                            </div>

                                        @elseif($pendingItems > 0)

                                            <div class="alert alert-warning py-2 px-3 small mb-3">

                                                <i class="bi bi-clock me-1"></i>

                                                {{ $pendingItems }}
                                                checklist item(s) are still
                                                pending.

                                            </div>

                                        @else

                                            <div class="alert alert-success py-2 px-3 small mb-3">

                                                <i class="bi bi-check-circle me-1"></i>

                                                All checklist items have passed.
                                                The manuscript can now be
                                                forwarded to Payment.

                                            </div>

                                        @endif


                                        @can('technical_check.complete')

                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.manuscripts.technical-check.complete',
                                                      $technicalCheck
                                                  ) }}">

                                                @csrf

                                                <button type="submit"
                                                        class="btn btn-success px-4"
                                                        {{ !$readyToComplete ? 'disabled' : '' }}
                                                        onclick="return confirm(
                                                            'Are you sure you want to complete this technical check and forward the manuscript to Payment?'
                                                        );">

                                                    <i class="bi bi-check-circle-fill me-1"></i>

                                                    Complete & Forward to Payment

                                                    <i class="bi bi-arrow-right ms-1"></i>

                                                </button>

                                            </form>

                                        @endcan

                                    </div>

                                </div>

                            </div>

                        </div>



                        {{-- =========================================
                             RETURN
                        ========================================== --}}

                        <div class="col-lg-5">

                            <div class="decision-card warning">

                                <div class="d-flex align-items-start gap-3">

                                    <div>

                                        <i class="bi bi-arrow-counterclockwise fs-3 text-warning"></i>

                                    </div>

                                    <div class="flex-grow-1">

                                        <div class="decision-title">

                                            Return to Author

                                        </div>

                                        <div class="decision-description">

                                            If technical corrections are
                                            required, return the manuscript
                                            to the author.

                                            The author will correct the
                                            manuscript and resubmit it for
                                            a new technical check.

                                        </div>


                                        @can('technical_check.return')

                                            <button type="button"
                                                    class="btn btn-outline-warning"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#returnAuthorModal">

                                                <i class="bi bi-arrow-counterclockwise me-1"></i>

                                                Return to Author

                                            </button>

                                        @endcan

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @endif



        {{-- =====================================================
             PASSED → PAYMENT
        ====================================================== --}}

        @if($technicalCheck->status === 'passed')

            <div class="tc-card">

                <div class="tc-card-body">

                    <div class="payment-transition">

                        <div class="d-flex align-items-start gap-3">

                            <div class="payment-transition-icon">

                                <i class="bi bi-check-lg"></i>

                            </div>

                            <div class="flex-grow-1">

                                <div class="payment-transition-title">

                                    Technical Check Passed

                                </div>

                                <p class="payment-transition-text">

                                    This technical check has been completed
                                    successfully.

                                    The manuscript has been forwarded to
                                    the <strong>Payment</strong> stage.

                                </p>


                                <div class="mt-3">

                                    <span class="badge bg-success">

                                        <i class="bi bi-check-circle me-1"></i>

                                        Payment Required

                                    </span>

                                    <span class="text-muted small ms-2">

                                        Next stage:
                                        <strong>Payment</strong>

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @endif



        {{-- =====================================================
             TECHNICAL CHECK COMMENTS
        ====================================================== --}}

        @if($technicalCheck->comments)

            <div class="tc-card">

                <div class="tc-card-header">

                    <div class="tc-card-title">

                        <i class="bi bi-chat-left-text me-2"></i>

                        Technical Check Comments

                    </div>

                </div>

                <div class="tc-card-body">

                    <div class="alert alert-light border mb-0">

                        {{ $technicalCheck->comments }}

                    </div>

                </div>

            </div>

        @endif



        {{-- =====================================================
             TECHNICAL ISSUES
        ====================================================== --}}

        @if($issues->count() > 0)

            <div class="tc-card">

                <div class="tc-card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="tc-card-title">

                                <i class="bi bi-exclamation-triangle me-2"></i>

                                Technical Issues

                            </div>

                            <div class="tc-card-description">

                                Issues recorded during this technical check.

                            </div>

                        </div>


                        <span class="badge bg-warning-subtle text-warning-emphasis">

                            {{ $openIssues }} Open

                        </span>

                    </div>

                </div>


                <div class="tc-card-body">

                    @foreach($issues as $issue)

                        <div class="issue-card">

                            <div class="d-flex justify-content-between align-items-start gap-2">

                                <div>

                                    <div class="fw-semibold small">

                                        {{ ucwords(str_replace('_', ' ', $issue->category)) }}

                                    </div>

                                    <div class="issue-description mt-1">

                                        {{ $issue->description }}

                                    </div>

                                </div>


                                <span class="badge
                                    @if($issue->severity === 'critical')
                                        bg-danger
                                    @elseif($issue->severity === 'major')
                                        bg-warning text-dark
                                    @else
                                        bg-secondary
                                    @endif">

                                    {{ ucfirst($issue->severity) }}

                                </span>

                            </div>


                            @if($issue->required_action)

                                <div class="issue-action">

                                    <strong>Required Action:</strong>

                                    {{ $issue->required_action }}

                                </div>

                            @endif

                        </div>

                    @endforeach

                </div>

            </div>

        @endif



        {{-- =====================================================
             RETURN TO AUTHOR MODAL
        ====================================================== --}}

        @can('technical_check.return')

            <div class="modal fade"
                 id="returnAuthorModal"
                 tabindex="-1"
                 aria-labelledby="returnAuthorModalLabel"
                 aria-hidden="true">

                <div class="modal-dialog modal-dialog-centered">

                    <div class="modal-content border-0 shadow">

                        <form method="POST"
                              action="{{ route(
                                  'admin.manuscripts.technical-check.return',
                                  $technicalCheck
                              ) }}">

                            @csrf


                            <div class="modal-header">

                                <h5 class="modal-title"
                                    id="returnAuthorModalLabel">

                                    <i class="bi bi-arrow-counterclockwise me-2"></i>

                                    Return Manuscript to Author

                                </h5>

                                <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"></button>

                            </div>


                            <div class="modal-body">

                                <div class="alert alert-warning small">

                                    <i class="bi bi-exclamation-triangle me-1"></i>

                                    The manuscript will be moved to
                                    <strong>Technical Correction</strong>.

                                    The author will need to correct the
                                    identified problems and resubmit the
                                    manuscript.

                                </div>


                                <div class="mb-3">

                                    <label class="form-label fw-semibold">

                                        Correction Instructions
                                        <span class="text-danger">*</span>

                                    </label>

                                    <textarea
                                        name="comments"
                                        rows="5"
                                        class="form-control"
                                        required
                                        maxlength="5000"
                                        placeholder="Clearly explain what the author needs to correct...">{{ old('comments') }}</textarea>

                                    <div class="form-text">

                                        These comments will guide the author
                                        during technical correction.

                                    </div>

                                </div>

                            </div>


                            <div class="modal-footer">

                                <button type="button"
                                        class="btn btn-light"
                                        data-bs-dismiss="modal">

                                    Cancel

                                </button>


                                <button type="submit"
                                        class="btn btn-warning">

                                    <i class="bi bi-arrow-counterclockwise me-1"></i>

                                    Return to Author

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        @endcan



        {{-- =====================================================
             WORKFLOW NOTE
        ====================================================== --}}

        <div class="workflow-note">

            <div class="d-flex align-items-start gap-2">

                <i class="bi bi-info-circle mt-1"></i>

                <div>

                    <strong>Workflow:</strong>

                    Technical Review
                    <i class="bi bi-arrow-right mx-1"></i>

                    Technical Check

                    <i class="bi bi-arrow-right mx-1"></i>

                    <strong>Passed</strong>

                    <i class="bi bi-arrow-right mx-1"></i>

                    <strong>Payment</strong>

                    <span class="mx-2">|</span>

                    If correction is required:

                    Technical Check
                    <i class="bi bi-arrow-right mx-1"></i>
                    Author Correction
                    <i class="bi bi-arrow-right mx-1"></i>
                    Resubmission
                    <i class="bi bi-arrow-right mx-1"></i>
                    New Technical Check

                </div>

            </div>

        </div>

    @endif

</div>

@endsection