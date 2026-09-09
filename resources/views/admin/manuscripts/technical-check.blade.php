@extends('admin.layouts.app')

@section('title', 'Technical Check')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Basic Manuscript Information
    |--------------------------------------------------------------------------
    */

    $manuscriptNumber = $manuscript->manuscript_id
        ?? ('#' . $manuscript->id);

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
        'technical_check'      => 'Technical Check',
        'technical_correction' => 'Technical Correction',
        'payment_required'     => 'Payment Required',
        'payment_correction'   => 'Payment Correction',
        'payment_verified'     => 'Payment Verified',
        'editorial_assessment' => 'Editorial Assessment',
        'similarity_check'     => 'Similarity Check',
        'under_review'         => 'Under Review',
        'revision_required'   => 'Revision Required',
        'accepted'             => 'Accepted',
        'rejected'             => 'Rejected',
        'copy_editing'         => 'Copy Editing',
        'proofreading'         => 'Proofreading',
        'production'           => 'Production',
        'published'            => 'Published',
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
        ?? (
            $technicalStatus
                ? ucwords(str_replace('_', ' ', $technicalStatus))
                : 'Not Started'
        );


    /*
    |--------------------------------------------------------------------------
    | Technical Status Badge
    |--------------------------------------------------------------------------
    */

    $technicalStatusClass = match ($technicalStatus) {
        'passed'              => 'bg-success-subtle text-success',
        'in_progress'         => 'bg-primary-subtle text-primary',
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

    $naItems = $technicalCheck
        ? $technicalCheck->items->where('result', 'na')->count()
        : 0;

    $completedItems = $passedItems + $naItems;


    /*
    |--------------------------------------------------------------------------
    | Technical Issues
    |--------------------------------------------------------------------------
    */

    $issues = $technicalCheck
        ? ($technicalCheck->issues ?? collect())
        : collect();

    $openIssues = $issues
        ->where('status', 'open')
        ->count();


    /*
    |--------------------------------------------------------------------------
    | Ready To Complete
    |--------------------------------------------------------------------------
    */

    $readyToComplete =
        $technicalCheck &&
        $totalItems > 0 &&
        $failedItems === 0 &&
        $pendingItems === 0 &&
        $completedItems === $totalItems &&
        $openIssues === 0 &&
        in_array($technicalCheck->status, ['pending', 'in_progress'], true);


    /*
    |--------------------------------------------------------------------------
    | Can Edit Checklist
    |--------------------------------------------------------------------------
    */

    $canEditChecklist =
        $technicalCheck &&
        in_array(
            $technicalCheck->status,
            ['pending', 'in_progress'],
            true
        );


    /*
    |--------------------------------------------------------------------------
    | Workflow State
    |--------------------------------------------------------------------------
    */

    $isTechnicalReview =
        $manuscriptStatus === 'submitted' ||
        $manuscriptStatus === 'technical_check' ||
        $manuscript->current_stage === 'technical_review';

    $isCorrection =
        $manuscriptStatus === 'technical_correction' ||
        $manuscript->current_stage === 'author_correction';

    $isPayment =
        $manuscriptStatus === 'payment_required' ||
        $manuscriptStatus === 'payment_setup' ||
        $manuscript->current_stage === 'payment';

    $isPaymentCorrection =
        $manuscriptStatus === 'payment_correction' ||
        $manuscript->current_stage === 'payment_correction';


    /*
    |--------------------------------------------------------------------------
    | Progress Percentage
    |--------------------------------------------------------------------------
    */

    $progressPercentage = $totalItems > 0
        ? round(($completedItems / $totalItems) * 100)
        : 0;


    /*
    |--------------------------------------------------------------------------
    | Authors
    |--------------------------------------------------------------------------
    */

    $authors = $manuscript->authors ?? collect();


    /*
    |--------------------------------------------------------------------------
    | Article Type
    |--------------------------------------------------------------------------
    */

    $articleTypeName = optional($manuscript->articleType)->name
        ?? $manuscript->article_type
        ?? '—';


    /*
    |--------------------------------------------------------------------------
    | Submitted Files
    |--------------------------------------------------------------------------
    */

    $submittedFiles = $manuscript->files ?? collect();

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
        box-shadow: 0 3px 14px rgba(0,0,0,.04);
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
        box-shadow: 0 3px 14px rgba(0,0,0,.04);
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
       SECTION TITLE
    ============================================================ */

    .section-title-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #eaf2ff;
        color: #0d6efd;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #212529;
    }


    /* ============================================================
       AUTHOR INFORMATION
    ============================================================ */

    .author-name {
        font-size: 14px;
        font-weight: 700;
        color: #212529;
    }

    .author-meta {
        font-size: 12px;
        color: #6c757d;
        line-height: 1.6;
    }

    .contribution-box {
        margin-top: 10px;
        padding: 10px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
    }

    .contribution-title {
        font-size: 11px;
        font-weight: 700;
        color: #0d6efd;
        margin-bottom: 6px;
    }

    .contribution-badge {
        font-size: 10px;
        font-weight: 500;
        margin-bottom: 3px;
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
       CORRECTION
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
       STATISTICS
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
        box-shadow: 0 3px 14px rgba(0,0,0,.03);
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
       CHECKLIST
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
       DECISION
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
       PAYMENT
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
       ISSUES
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
        margin-top: 8px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 7px;
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
       FOOTER
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

/* =====================================================
   SUBMITTED INFORMATION
===================================================== */

.submitted-section {
    width: 100%;
}

.section-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 0 14px;
    margin-bottom: 18px;
    border-bottom: 2px solid #e9ecef;
}

.section-heading-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.section-icon {
    width: 42px;
    height: 42px;
    min-width: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eef4ff;
    color: #0d6efd;
    font-size: 20px;
}

.submitted-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    margin-bottom: 18px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
}

.submitted-card-header {
    min-height: 68px;
    padding: 14px 18px;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.submitted-card-body {
    padding: 18px;
}

.mini-icon {
    width: 38px;
    height: 38px;
    min-width: 38px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
}

.info-box {
    height: 100%;
    padding: 13px 15px;
    border: 1px solid #e9ecef;
    background: #fff;
    border-radius: 9px;
}

.info-label {
    font-size: .76rem;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: .025em;
}

.info-value {
    color: #212529;
    font-size: .92rem;
    font-weight: 500;
    word-break: break-word;
}

.text-field {
    border: 1px solid #e9ecef;
    background: #f8f9fa;
    border-radius: 9px;
    padding: 14px 15px;
}

.text-content {
    color: #343a40;
    font-size: .92rem;
    line-height: 1.7;
    word-break: break-word;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 10px;
    border-radius: 999px;
    font-size: .78rem;
    font-weight: 600;
}

.status-success {
    background: #d1e7dd;
    color: #146c43;
}

.status-danger {
    background: #f8d7da;
    color: #b02a37;
}

.status-secondary {
    background: #e9ecef;
    color: #495057;
}

.empty-submitted {
    padding: 18px;
    border: 1px dashed #ced4da;
    border-radius: 9px;
    color: #6c757d;
    background: #f8f9fa;
    text-align: center;
}


/* =====================================================
   ABSTRACT / HTML CONTENT
===================================================== */

.submitted-content {
    font-size: .95rem;
    line-height: 1.75;
    color: #343a40;
    word-break: break-word;
}

.submitted-content p {
    margin-bottom: .75rem;
}

.submitted-content p:last-child {
    margin-bottom: 0;
}

.submitted-content ul,
.submitted-content ol {
    padding-left: 1.5rem;
}

.submitted-content img {
    max-width: 100%;
    height: auto;
}

.submitted-content table {
    width: 100%;
    max-width: 100%;
    border-collapse: collapse;
}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 767.98px) {

    .section-heading {
        padding-bottom: 12px;
    }

    .section-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        font-size: 17px;
    }

    .submitted-card {
        border-radius: 10px;
        margin-bottom: 14px;
    }

    .submitted-card-header {
        padding: 12px;
        min-height: auto;
        align-items: flex-start;
    }

    .submitted-card-body {
        padding: 12px;
    }

    .mini-icon {
        width: 34px;
        height: 34px;
        min-width: 34px;
        font-size: 15px;
    }

    .info-box {
        padding: 11px 12px;
    }

    .text-field {
        padding: 12px;
    }

    .info-label {
        font-size: .7rem;
    }

    .info-value,
    .text-content {
        font-size: .88rem;
    }

    .submitted-card-header .btn {
        white-space: nowrap;
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





    {{-- =========================================================
         AUTHOR SUBMITTED INFORMATION
    ========================================================== --}}

    <div class="tc-card">

        <div class="tc-card-header">

            <div class="d-flex align-items-center gap-2">

                <span class="section-title-icon">

                    <i class="bi bi-file-earmark-person"></i>

                </span>

                <div>

                    <div class="section-title">
                        Author Submitted Information
                    </div>

                    <div class="tc-card-description">
                        Information submitted by the author for technical evaluation
                    </div>

                </div>

            </div>

        </div>


        <div class="tc-card-body">


            {{-- =====================================================
                 TITLE
            ====================================================== --}}

            <div class="mb-4">

                <div class="small text-muted mb-1">
                    Manuscript Title
                </div>

                <div class="fw-bold fs-5">
                    {{ $manuscript->title ?? 'No title submitted.' }}
                </div>

            </div>


            {{-- =====================================================
                 ABSTRACT
            ====================================================== --}}

            <div class="mb-4">

                <h6 class="fw-bold border-bottom pb-2 mb-3">

                    <i class="bi bi-file-text me-1"></i>
                    Abstract

                </h6>

                <div class="p-3 bg-light border rounded-3"
                     style="line-height:1.8;">

                    @if(!empty($manuscript->abstract))

                          {!! $manuscript->abstract ?? 'No abstract submitted.' !!}

                    @else

                        <span class="text-muted">
                            No abstract submitted.
                        </span>

                    @endif

                </div>

            </div>


            {{-- =====================================================
                 KEYWORDS
            ====================================================== --}}

            <div class="mb-4">

                <h6 class="fw-bold border-bottom pb-2 mb-3">

                    <i class="bi bi-tags me-1"></i>
                    Keywords

                </h6>

                @if(!empty($manuscript->keywords))

                    <div class="d-flex flex-wrap gap-1">

                        @php

                            $keywords = is_array($manuscript->keywords)
                                ? $manuscript->keywords
                                : preg_split(
                                    '/[,;]+/',
                                    $manuscript->keywords
                                );

                        @endphp


                        @foreach($keywords as $keyword)

                            @if(trim($keyword))

                                <span class="badge bg-primary-subtle text-primary">

                                    {{ trim($keyword) }}

                                </span>

                            @endif

                        @endforeach

                    </div>

                @else

                    <span class="text-muted">
                        No keywords submitted.
                    </span>

                @endif

            </div>


{{-- =====================================================
     AUTHORS
====================================================== --}}

<div class="mb-4">

    {{-- Section Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center
                border-bottom pb-2 mb-3 gap-2">

        <h6 class="fw-bold mb-0">
            <i class="bi bi-people me-1 text-primary"></i>
            Authors
            @if($authors->count())
                <span class="badge bg-primary-subtle text-primary ms-1">
                    {{ $authors->count() }}
                </span>
            @endif
        </h6>

    </div>


    @if($authors->count())

        <div class="authors-table-wrapper">

            <div class="table-responsive">

                <table class="table table-bordered align-middle mb-0 authors-table">

                    <thead>
                        <tr>

                            <th class="author-number">
                                #
                            </th>

                            <th class="author-column">
                                <i class="bi bi-person me-1"></i>
                                Author
                            </th>

                            <th class="affiliation-column">
                                <i class="bi bi-building me-1"></i>
                                Affiliation
                            </th>

                            <th class="contact-column">
                                <i class="bi bi-envelope me-1"></i>
                                Contact
                            </th>

                            <th class="role-column">
                                <i class="bi bi-person-check me-1"></i>
                                Role
                            </th>

                        </tr>
                    </thead>


                    <tbody>

                        @foreach($authors->sortBy('author_order') as $index => $author)

                            @php

                                /*
                                |--------------------------------------------------------------------------
                                | Author Name
                                |--------------------------------------------------------------------------
                                */

                                $authorName = $author->full_name
                                    ?: trim(
                                        ($author->title ?? '') . ' ' .
                                        ($author->first_name ?? '') . ' ' .
                                        ($author->middle_name ?? '') . ' ' .
                                        ($author->last_name ?? '')
                                    );

                                $authorName = trim($authorName) ?: '—';


                                /*
                                |--------------------------------------------------------------------------
                                | CRediT Contribution
                                |--------------------------------------------------------------------------
                                */

                                $contribution = $author->contribution;

                                $creditRoles = [

                                    'conceptualization'
                                        => 'Conceptualization',

                                    'methodology'
                                        => 'Methodology',

                                    'software'
                                        => 'Software',

                                    'validation'
                                        => 'Validation',

                                    'formal_analysis'
                                        => 'Formal Analysis',

                                    'investigation'
                                        => 'Investigation',

                                    'resources'
                                        => 'Resources',

                                    'data_curation'
                                        => 'Data Curation',

                                    'writing_original'
                                        => 'Writing – Original Draft',

                                    'writing_review'
                                        => 'Writing – Review & Editing',

                                    'visualization'
                                        => 'Visualization',

                                    'supervision'
                                        => 'Supervision',

                                    'project_administration'
                                        => 'Project Administration',

                                    'funding_acquisition'
                                        => 'Funding Acquisition',

                                ];


                                $selectedRoles = [];


                                if($contribution) {

                                    foreach($creditRoles as $field => $label) {

                                        if((int)($contribution->{$field} ?? 0) === 1) {

                                            $selectedRoles[] = $label;

                                        }

                                    }

                                }

                            @endphp


                            <tr>

                                {{-- =================================================
                                     NUMBER
                                ================================================== --}}

                                <td class="text-center author-number-cell">

                                    <span class="author-number-badge">
                                        {{ $index + 1 }}
                                    </span>

                                </td>


                                {{-- =================================================
                                     AUTHOR
                                ================================================== --}}

                                <td>

                                    <div class="author-main">

                                        <div class="author-name">
                                            {{ $authorName }}
                                        </div>


                                        @if($author->designation)

                                            <div class="author-designation">
                                                {{ $author->designation }}
                                            </div>

                                        @endif


                                        {{-- ORCID --}}

                                        @if($author->orcid)

                                            <div class="author-meta mt-1">

                                                <i class="bi bi-person-badge me-1"></i>

                                                <span>ORCID:</span>

                                                {{ $author->orcid }}

                                            </div>

                                        @endif


                                        {{-- Corresponding Author --}}

                                        @if($author->is_corresponding)

                                            <div class="mt-2">

                                                <span class="corresponding-badge">

                                                    <i class="bi bi-envelope-fill me-1"></i>

                                                    Corresponding Author

                                                </span>

                                            </div>

                                        @endif


                                        {{-- =================================================
                                             CRediT CONTRIBUTION
                                        ================================================== --}}

                                        @if(count($selectedRoles))

                                            <div class="contribution-box mt-3">

                                                <div class="contribution-title">

                                                    <i class="bi bi-person-check me-1"></i>

                                                    Author Contribution

                                                </div>


                                                <div class="d-flex flex-wrap gap-1">

                                                    @foreach($selectedRoles as $role)

                                                        <span class="contribution-badge">

                                                            {{ $role }}

                                                        </span>

                                                    @endforeach

                                                </div>

                                            </div>

                                        @elseif($contribution)

                                            <div class="no-contribution mt-2">

                                                <i class="bi bi-info-circle me-1"></i>

                                                No CRediT contribution selected.

                                            </div>

                                        @endif

                                    </div>

                                </td>


                                {{-- =================================================
                                     AFFILIATION
                                ================================================== --}}

                                <td>

                                    <div class="affiliation-content">

                                        {{-- Institution --}}

                                        @if($author->institution)

                                            <div class="institution-name">

                                                <i class="bi bi-building me-1 text-primary"></i>

                                                {{ $author->institution }}

                                            </div>

                                        @endif


                                        {{-- Department --}}

                                        @if($author->department)

                                            <div class="author-meta mt-1">

                                                <strong>Department:</strong>

                                                {{ $author->department }}

                                            </div>

                                        @endif


                                        {{-- Designation --}}

                                        @if($author->designation)

                                            <div class="author-meta mt-1">

                                                <strong>Designation:</strong>

                                                {{ $author->designation }}

                                            </div>

                                        @endif


                                        {{-- Country --}}

                                        @if($author->country)

                                            <div class="author-meta mt-1">

                                                <strong>Country:</strong>

                                                {{ $author->country }}

                                            </div>

                                        @endif


                                        {{-- =================================================
                                             LINKED AFFILIATIONS
                                        ================================================== --}}

                                        @if(
                                            $author->affiliations &&
                                            $author->affiliations->count()
                                        )

                                            <div class="linked-affiliations mt-3">

                                                <div class="linked-affiliation-title">

                                                    <i class="bi bi-link-45deg me-1"></i>

                                                    Linked Affiliations

                                                </div>


                                                @foreach(
                                                    $author->affiliations
                                                        ->sortBy('pivot.affiliation_order')
                                                    as $affiliation
                                                )

                                                    <div class="linked-affiliation-item">

                                                        <span class="affiliation-number">

                                                            {{ $affiliation->pivot->affiliation_order
                                                                ?? $loop->iteration }}

                                                        </span>


                                                        <span>

                                                            {{ $affiliation->name
                                                                ?? $affiliation->institution
                                                                ?? $affiliation->title
                                                                ?? '—' }}

                                                        </span>

                                                    </div>

                                                @endforeach

                                            </div>

                                        @endif


                                        {{-- No Affiliation --}}

                                        @if(
                                            !$author->institution &&
                                            !$author->department &&
                                            !$author->designation &&
                                            !$author->country &&
                                            !(
                                                $author->affiliations &&
                                                $author->affiliations->count()
                                            )
                                        )

                                            <div class="empty-author-data">

                                                <i class="bi bi-dash-circle me-1"></i>

                                                No affiliation submitted.

                                            </div>

                                        @endif

                                    </div>

                                </td>


                                {{-- =================================================
                                     CONTACT
                                ================================================== --}}

                                <td>

                                    <div class="contact-content">

                                        @if($author->email)

                                            <div class="contact-item">

                                                <div class="contact-icon">

                                                    <i class="bi bi-envelope"></i>

                                                </div>

                                                <div class="contact-text">

                                                    <div class="contact-label">
                                                        Email
                                                    </div>

                                                    <div class="contact-value">
                                                        {{ $author->email }}
                                                    </div>

                                                </div>

                                            </div>

                                        @endif


                                        @if($author->mobile)

                                            <div class="contact-item mt-2">

                                                <div class="contact-icon">

                                                    <i class="bi bi-phone"></i>

                                                </div>

                                                <div class="contact-text">

                                                    <div class="contact-label">
                                                        Mobile
                                                    </div>

                                                    <div class="contact-value">
                                                        {{ $author->mobile }}
                                                    </div>

                                                </div>

                                            </div>

                                        @endif


                                        @if(!$author->email && !$author->mobile)

                                            <div class="empty-author-data">

                                                <i class="bi bi-dash-circle me-1"></i>

                                                No contact information.

                                            </div>

                                        @endif

                                    </div>

                                </td>


                                {{-- =================================================
                                     ROLE
                                ================================================== --}}

                                <td class="text-center">

                                    @if($author->is_corresponding)

                                        <span class="author-role-badge corresponding">

                                            <i class="bi bi-envelope-fill me-1"></i>

                                            Corresponding

                                        </span>

                                    @else

                                        <span class="author-role-badge author">

                                            <i class="bi bi-person me-1"></i>

                                            Author

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @else

        <div class="empty-authors">

            <div class="empty-authors-icon">

                <i class="bi bi-people"></i>

            </div>

            <div>

                <div class="fw-semibold">
                    No author information available
                </div>

                <div class="small text-muted">
                    No author records were submitted with this manuscript.
                </div>

            </div>

        </div>

    @endif

</div>


{{-- =====================================================
     AUTHORS CSS
====================================================== --}}

<style>

    /* -----------------------------------------------------
       AUTHORS TABLE
    ----------------------------------------------------- */

    .authors-table-wrapper {

        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;

    }


    .authors-table {

        font-size: .88rem;
        min-width: 1050px;

    }


    .authors-table thead th {

        background: #f8f9fa;
        color: #495057;
        font-size: .76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
        white-space: nowrap;
        padding: 13px 14px;
        border-bottom: 2px solid #dee2e6;

    }


    .authors-table tbody td {

        padding: 15px 14px;
        vertical-align: top;

    }


    .authors-table tbody tr {

        transition: background .15s ease;

    }


    .authors-table tbody tr:hover {

        background: #fafcff;

    }


    .author-number {

        width: 55px;
        text-align: center;

    }


    .author-number-cell {

        text-align: center;
        vertical-align: top !important;

    }


    .author-number-badge {

        display: inline-flex;
        align-items: center;
        justify-content: center;

        width: 30px;
        height: 30px;

        border-radius: 8px;

        background: #f1f5f9;
        color: #475569;

        font-size: .8rem;
        font-weight: 700;

    }


    .author-column {

        min-width: 300px;

    }


    .affiliation-column {

        min-width: 300px;

    }


    .contact-column {

        min-width: 230px;

    }


    .role-column {

        width: 150px;

    }


    /* -----------------------------------------------------
       AUTHOR NAME
    ----------------------------------------------------- */

    .author-name {

        font-size: .98rem;
        font-weight: 700;
        color: #212529;
        line-height: 1.4;

    }


    .author-designation {

        font-size: .82rem;
        color: #6c757d;
        margin-top: 2px;

    }


    .author-meta {

        font-size: .78rem;
        line-height: 1.5;
        color: #6c757d;
        word-break: break-word;

    }


    .author-meta strong {

        color: #495057;

    }


    /* -----------------------------------------------------
       CORRESPONDING AUTHOR
    ----------------------------------------------------- */

    .corresponding-badge {

        display: inline-flex;
        align-items: center;

        padding: 5px 9px;

        border-radius: 7px;

        background: #e7f1ff;
        color: #0d6efd;

        font-size: .74rem;
        font-weight: 700;

    }


    /* -----------------------------------------------------
       CONTRIBUTION
    ----------------------------------------------------- */

    .contribution-box {

        padding: 10px;

        background: #f8fafc;

        border: 1px solid #e2e8f0;

        border-radius: 8px;

    }


    .contribution-title {

        font-size: .72rem;
        font-weight: 700;
        color: #64748b;

        text-transform: uppercase;
        letter-spacing: .025em;

        margin-bottom: 7px;

    }


    .contribution-badge {

        display: inline-block;

        padding: 4px 7px;

        background: #fff;

        border: 1px solid #dbe2ea;

        border-radius: 5px;

        color: #374151;

        font-size: .72rem;
        font-weight: 500;

    }


    .no-contribution {

        color: #94a3b8;
        font-size: .76rem;

    }


    /* -----------------------------------------------------
       AFFILIATION
    ----------------------------------------------------- */

    .institution-name {

        color: #1f2937;
        font-weight: 600;
        line-height: 1.5;
        word-break: break-word;

    }


    .linked-affiliations {

        padding-top: 10px;

        border-top: 1px dashed #dee2e6;

    }


    .linked-affiliation-title {

        color: #0d6efd;

        font-size: .73rem;
        font-weight: 700;

        text-transform: uppercase;

        margin-bottom: 8px;

    }


    .linked-affiliation-item {

        display: flex;
        align-items: flex-start;
        gap: 7px;

        padding: 7px 8px;

        margin-bottom: 5px;

        background: #f8f9fa;

        border: 1px solid #e9ecef;

        border-radius: 6px;

        font-size: .78rem;
        line-height: 1.5;

    }


    .affiliation-number {

        flex: 0 0 auto;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        width: 21px;
        height: 21px;

        border-radius: 5px;

        background: #e7f1ff;
        color: #0d6efd;

        font-size: .68rem;
        font-weight: 700;

    }


    /* -----------------------------------------------------
       CONTACT
    ----------------------------------------------------- */

    .contact-item {

        display: flex;
        align-items: flex-start;
        gap: 9px;

    }


    .contact-icon {

        display: flex;
        align-items: center;
        justify-content: center;

        width: 30px;
        height: 30px;
        min-width: 30px;

        border-radius: 7px;

        background: #f1f5f9;
        color: #64748b;

    }


    .contact-text {

        min-width: 0;

    }


    .contact-label {

        color: #94a3b8;

        font-size: .68rem;
        font-weight: 700;

        text-transform: uppercase;

    }


    .contact-value {

        color: #374151;

        font-size: .79rem;

        word-break: break-word;
        overflow-wrap: anywhere;

    }


    /* -----------------------------------------------------
       ROLE BADGES
    ----------------------------------------------------- */

    .author-role-badge {

        display: inline-flex;
        align-items: center;
        justify-content: center;

        padding: 6px 9px;

        border-radius: 7px;

        font-size: .72rem;
        font-weight: 700;

        white-space: nowrap;

    }


    .author-role-badge.corresponding {

        background: #e7f1ff;
        color: #0d6efd;

    }


    .author-role-badge.author {

        background: #f1f3f5;
        color: #495057;

    }


    /* -----------------------------------------------------
       EMPTY STATE
    ----------------------------------------------------- */

    .empty-author-data {

        color: #94a3b8;
        font-size: .78rem;

    }


    .empty-authors {

        display: flex;
        align-items: center;
        gap: 14px;

        padding: 18px;

        border: 1px dashed #ced4da;

        border-radius: 10px;

        background: #f8f9fa;

        color: #495057;

    }


    .empty-authors-icon {

        display: flex;
        align-items: center;
        justify-content: center;

        width: 42px;
        height: 42px;

        min-width: 42px;

        border-radius: 9px;

        background: #e9ecef;

        color: #6c757d;

        font-size: 20px;

    }


    /* -----------------------------------------------------
       MOBILE
    ----------------------------------------------------- */

    @media (max-width: 767.98px) {

        .authors-table-wrapper {

            border-radius: 9px;

        }


        /*
         * Keep the table horizontally scrollable rather than
         * squeezing author information into unreadable columns.
         */

        .authors-table {

            min-width: 900px;
            font-size: .82rem;

        }


        .authors-table thead th {

            padding: 10px 11px;
            font-size: .7rem;

        }


        .authors-table tbody td {

            padding: 11px;

        }


        .author-name {

            font-size: .9rem;

        }


        .author-meta {

            font-size: .74rem;

        }


        .contribution-box {

            padding: 8px;

        }


        .contribution-badge {

            font-size: .68rem;
            padding: 3px 6px;

        }


        .linked-affiliation-item {

            font-size: .72rem;

        }


        .contact-value {

            font-size: .74rem;

        }


        .author-role-badge {

            font-size: .68rem;
            padding: 5px 7px;

        }

    }

</style>
{{-- =====================================================
     RESEARCH / DECLARATIONS
====================================================== --}}

@php
    $ethical = $manuscript->ethicalInformation;
    $funding = $manuscript->fundingInformation;
    $conflict = $manuscript->conflictOfInterest;
    $dataAvailability = $manuscript->dataAvailability;
    $acknowledgement = $manuscript->acknowledgement;
@endphp


<div class="submitted-section mb-4">

    {{-- =================================================
         SECTION HEADER
    ================================================== --}}

    <div class="section-heading">

        <div class="section-heading-left">

            <div class="section-icon">
                <i class="bi bi-shield-check"></i>
            </div>

            <div>
                <h5 class="mb-1 fw-bold">
                    Research & Declaration Information
                </h5>

                <div class="text-muted small">
                    Information submitted by the author
                </div>
            </div>

        </div>

    </div>


    {{-- =================================================
         1. ETHICAL INFORMATION
    ================================================== --}}

    <div class="submitted-card">

        <div class="submitted-card-header">

            <div class="d-flex align-items-center gap-2">

                <div class="mini-icon bg-success-subtle text-success">
                    <i class="bi bi-shield-check"></i>
                </div>

                <div>
                    <div class="fw-semibold">
                        Ethical / Research Ethics Information
                    </div>

                    <div class="small text-muted">
                        Research ethics and approval details
                    </div>
                </div>

            </div>


            {{-- Edit --}}
            <a
                href="#"
                class="btn btn-sm btn-outline-primary"
            >
                <i class="bi bi-pencil-square me-1"></i>
                <span class="d-none d-sm-inline">Edit</span>
            </a>

        </div>


        <div class="submitted-card-body">

            @if($ethical)

                <div class="row g-3">

                    {{-- Human Participants --}}
                    <div class="col-12 col-sm-6 col-lg-4">

                        <div class="info-box">

                            <div class="info-label">
                                Human Participants
                            </div>

                            <div class="info-value">

                                @if($ethical->human_participants)

                                    <span class="status-badge status-success">
                                        <i class="bi bi-check-circle"></i>
                                        Yes
                                    </span>

                                @else

                                    <span class="status-badge status-secondary">
                                        No
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Animal Study --}}
                    <div class="col-12 col-sm-6 col-lg-4">

                        <div class="info-box">

                            <div class="info-label">
                                Animal Study
                            </div>

                            <div class="info-value">

                                @if($ethical->animal_study)

                                    <span class="status-badge status-success">
                                        <i class="bi bi-check-circle"></i>
                                        Yes
                                    </span>

                                @else

                                    <span class="status-badge status-secondary">
                                        No
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Ethical Approval --}}
                    <div class="col-12 col-sm-6 col-lg-4">

                        <div class="info-box">

                            <div class="info-label">
                                Ethical Approval Required
                            </div>

                            <div class="info-value">

                                @if($ethical->ethical_approval_required)

                                    <span class="status-badge status-success">
                                        <i class="bi bi-check-circle"></i>
                                        Yes
                                    </span>

                                @else

                                    <span class="status-badge status-secondary">
                                        No
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Ethics Committee --}}
                    @if($ethical->ethics_committee_name)

                        <div class="col-12 col-lg-6">

                            <div class="info-box">

                                <div class="info-label">
                                    Ethics Committee
                                </div>

                                <div class="info-value">
                                    {{ $ethical->ethics_committee_name }}
                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Institution --}}
                    @if($ethical->institution)

                        <div class="col-12 col-lg-6">

                            <div class="info-box">

                                <div class="info-label">
                                    Institution
                                </div>

                                <div class="info-value">
                                    {{ $ethical->institution }}
                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Approval Number --}}
                    @if($ethical->approval_number)

                        <div class="col-12 col-sm-6">

                            <div class="info-box">

                                <div class="info-label">
                                    Approval Number
                                </div>

                                <div class="info-value">
                                    {{ $ethical->approval_number }}
                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Approval Date --}}
                    @if($ethical->approval_date)

                        <div class="col-12 col-sm-6">

                            <div class="info-box">

                                <div class="info-label">
                                    Approval Date
                                </div>

                                <div class="info-value">
                                    {{ $ethical->approval_date->format('d M Y') }}
                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Informed Consent --}}
                    <div class="col-12 col-sm-6">

                        <div class="info-box">

                            <div class="info-label">
                                Informed Consent Obtained
                            </div>

                            <div class="info-value">

                                @if($ethical->informed_consent_obtained === null)

                                    <span class="status-badge status-secondary">
                                        Not Provided
                                    </span>

                                @elseif($ethical->informed_consent_obtained)

                                    <span class="status-badge status-success">
                                        <i class="bi bi-check-circle"></i>
                                        Yes
                                    </span>

                                @else

                                    <span class="status-badge status-danger">
                                        <i class="bi bi-x-circle"></i>
                                        No
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Consent Type --}}
                    @if($ethical->consent_type)

                        <div class="col-12 col-sm-6">

                            <div class="info-box">

                                <div class="info-label">
                                    Consent Type
                                </div>

                                <div class="info-value">
                                    {{ $ethical->consent_type }}
                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Clinical Trial --}}
                    <div class="col-12 col-sm-6">

                        <div class="info-box">

                            <div class="info-label">
                                Clinical Trial
                            </div>

                            <div class="info-value">

                                @if($ethical->clinical_trial)

                                    <span class="status-badge status-success">
                                        <i class="bi bi-check-circle"></i>
                                        Yes
                                    </span>

                                @else

                                    <span class="status-badge status-secondary">
                                        No
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Trial Registration --}}
                    @if($ethical->trial_registration_number)

                        <div class="col-12 col-lg-6">

                            <div class="info-box">

                                <div class="info-label">
                                    Trial Registration Number
                                </div>

                                <div class="info-value">
                                    {{ $ethical->trial_registration_number }}
                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Trial Registry --}}
                    @if($ethical->trial_registry)

                        <div class="col-12 col-lg-6">

                            <div class="info-box">

                                <div class="info-label">
                                    Trial Registry
                                </div>

                                <div class="info-value">
                                    {{ $ethical->trial_registry }}
                                </div>

                            </div>

                        </div>

                    @endif

                </div>

            @else

                <div class="empty-submitted">
                    <i class="bi bi-info-circle"></i>
                    No ethical information was submitted.
                </div>

            @endif

        </div>

    </div>



    {{-- =================================================
         2. FUNDING INFORMATION
    ================================================== --}}

    <div class="submitted-card">

        <div class="submitted-card-header">

            <div class="d-flex align-items-center gap-2">

                <div class="mini-icon bg-primary-subtle text-primary">
                    <i class="bi bi-cash-coin"></i>
                </div>

                <div>
                    <div class="fw-semibold">
                        Funding Information
                    </div>

                    <div class="small text-muted">
                        Funding, grant and sponsor details
                    </div>
                </div>

            </div>


            <a
                href="#"
                class="btn btn-sm btn-outline-primary"
            >
                <i class="bi bi-pencil-square me-1"></i>
                <span class="d-none d-sm-inline">Edit</span>
            </a>

        </div>


        <div class="submitted-card-body">

            @if($funding)

                <div class="row g-3">

                    {{-- Funding Received --}}
                    <div class="col-12 col-sm-6 col-lg-4">

                        <div class="info-box">

                            <div class="info-label">
                                Funding Received
                            </div>

                            <div class="info-value">

                                @if($funding->funding_received)

                                    <span class="status-badge status-success">
                                        <i class="bi bi-check-circle"></i>
                                        Yes
                                    </span>

                                @else

                                    <span class="status-badge status-secondary">
                                        No
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Funding Type --}}
                    @if($funding->funding_type)

                        <div class="col-12 col-sm-6 col-lg-4">

                            <div class="info-box">

                                <div class="info-label">
                                    Funding Type
                                </div>

                                <div class="info-value">
                                    {{ $funding->funding_type }}
                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Organization --}}
                    @if($funding->funding_organization)

                        <div class="col-12 col-sm-6 col-lg-4">

                            <div class="info-box">

                                <div class="info-label">
                                    Funding Organization
                                </div>

                                <div class="info-value">
                                    {{ $funding->funding_organization }}
                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Grant Number --}}
                    @if($funding->grant_number)

                        <div class="col-12 col-sm-6">

                            <div class="info-box">

                                <div class="info-label">
                                    Grant Number
                                </div>

                                <div class="info-value">
                                    {{ $funding->grant_number }}
                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Grant Amount --}}
                    @if($funding->grant_amount !== null)

                        <div class="col-12 col-sm-6">

                            <div class="info-box">

                                <div class="info-label">
                                    Grant Amount
                                </div>

                                <div class="info-value">

                                    {{ number_format((float) $funding->grant_amount, 2) }}

                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Start Date --}}
                    @if($funding->funding_start_date)

                        <div class="col-12 col-sm-6">

                            <div class="info-box">

                                <div class="info-label">
                                    Funding Start Date
                                </div>

                                <div class="info-value">

                                    {{ $funding->funding_start_date->format('d M Y') }}

                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- End Date --}}
                    @if($funding->funding_end_date)

                        <div class="col-12 col-sm-6">

                            <div class="info-box">

                                <div class="info-label">
                                    Funding End Date
                                </div>

                                <div class="info-value">

                                    {{ $funding->funding_end_date->format('d M Y') }}

                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Statement --}}
                    @if($funding->funding_statement)

                        <div class="col-12">

                            <div class="text-field">

                                <div class="info-label">
                                    Funding Statement
                                </div>

                                <div class="text-content">

                                    {!! nl2br(e($funding->funding_statement)) !!}

                                </div>

                            </div>

                        </div>

                    @endif

                </div>

            @else

                <div class="empty-submitted">
                    <i class="bi bi-info-circle"></i>
                    No funding information was submitted.
                </div>

            @endif

        </div>

    </div>



    {{-- =================================================
         3. CONFLICT OF INTEREST
    ================================================== --}}

    <div class="submitted-card">

        <div class="submitted-card-header">

            <div class="d-flex align-items-center gap-2">

                <div class="mini-icon bg-warning-subtle text-warning-emphasis">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>

                <div>
                    <div class="fw-semibold">
                        Conflict of Interest
                    </div>

                    <div class="small text-muted">
                        Conflict declaration and author confirmation
                    </div>
                </div>

            </div>


            <a
                href="#"
                class="btn btn-sm btn-outline-primary"
            >
                <i class="bi bi-pencil-square me-1"></i>
                <span class="d-none d-sm-inline">Edit</span>
            </a>

        </div>


        <div class="submitted-card-body">

            @if($conflict)

                <div class="row g-3">


                    {{-- Conflict Exists --}}
                    <div class="col-12 col-sm-6 col-lg-4">

                        <div class="info-box">

                            <div class="info-label">
                                Conflict Exists
                            </div>

                            <div class="info-value">

                                @if($conflict->conflict_exists)

                                    <span class="status-badge status-danger">
                                        <i class="bi bi-exclamation-circle"></i>
                                        Yes
                                    </span>

                                @else

                                    <span class="status-badge status-success">
                                        <i class="bi bi-check-circle"></i>
                                        No
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- All Authors Agreed --}}
                    <div class="col-12 col-sm-6 col-lg-4">

                        <div class="info-box">

                            <div class="info-label">
                                All Authors Agreed
                            </div>

                            <div class="info-value">

                                @if($conflict->all_authors_agreed)

                                    <span class="status-badge status-success">
                                        <i class="bi bi-check-circle"></i>
                                        Yes
                                    </span>

                                @else

                                    <span class="status-badge status-danger">
                                        <i class="bi bi-x-circle"></i>
                                        No
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Declared At --}}
                    @if($conflict->declared_at)

                        <div class="col-12 col-sm-6 col-lg-4">

                            <div class="info-box">

                                <div class="info-label">
                                    Declared At
                                </div>

                                <div class="info-value">

                                    {{ $conflict->declared_at->format('d M Y, h:i A') }}

                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Conflict Description --}}
                    @if($conflict->conflict_description)

                        <div class="col-12">

                            <div class="text-field">

                                <div class="info-label">
                                    Conflict Description
                                </div>

                                <div class="text-content">

                                    {!! nl2br(e($conflict->conflict_description)) !!}

                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Author Declaration --}}
                    @if($conflict->author_declaration)

                        <div class="col-12">

                            <div class="text-field">

                                <div class="info-label">
                                    Author Declaration
                                </div>

                                <div class="text-content">

                                    {!! nl2br(e($conflict->author_declaration)) !!}

                                </div>

                            </div>

                        </div>

                    @endif


                </div>

            @else

                <div class="empty-submitted">
                    <i class="bi bi-info-circle"></i>
                    No conflict of interest information was submitted.
                </div>

            @endif

        </div>

    </div>



    {{-- =================================================
         4. DATA AVAILABILITY
    ================================================== --}}

    <div class="submitted-card">

        <div class="submitted-card-header">

            <div class="d-flex align-items-center gap-2">

                <div class="mini-icon bg-info-subtle text-info-emphasis">
                    <i class="bi bi-database-check"></i>
                </div>

                <div>
                    <div class="fw-semibold">
                        Data Availability
                    </div>

                    <div class="small text-muted">
                        Data sharing and repository information
                    </div>
                </div>

            </div>


            <a
                href="#"
                class="btn btn-sm btn-outline-primary"
            >
                <i class="bi bi-pencil-square me-1"></i>
                <span class="d-none d-sm-inline">Edit</span>
            </a>

        </div>


        <div class="submitted-card-body">

            @if($dataAvailability)

                <div class="row g-3">


                    {{-- Data Available --}}
                    <div class="col-12 col-sm-6 col-lg-4">

                        <div class="info-box">

                            <div class="info-label">
                                Data Available
                            </div>

                            <div class="info-value">

                                @if($dataAvailability->data_available === null)

                                    <span class="status-badge status-secondary">
                                        Not Provided
                                    </span>

                                @elseif($dataAvailability->data_available)

                                    <span class="status-badge status-success">
                                        <i class="bi bi-check-circle"></i>
                                        Yes
                                    </span>

                                @else

                                    <span class="status-badge status-danger">
                                        <i class="bi bi-x-circle"></i>
                                        No
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Repository --}}
                    @if($dataAvailability->repository)

                        <div class="col-12 col-sm-6 col-lg-4">

                            <div class="info-box">

                                <div class="info-label">
                                    Repository
                                </div>

                                <div class="info-value">
                                    {{ $dataAvailability->repository }}
                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Repository Name --}}
                    @if($dataAvailability->repository_name)

                        <div class="col-12 col-sm-6 col-lg-4">

                            <div class="info-box">

                                <div class="info-label">
                                    Repository Name
                                </div>

                                <div class="info-value">
                                    {{ $dataAvailability->repository_name }}
                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- DOI --}}
                    @if($dataAvailability->doi_url)

                        <div class="col-12">

                            <div class="text-field">

                                <div class="info-label">
                                    DOI / Repository URL
                                </div>

                                <div class="text-content">

                                    <a
                                        href="{{ $dataAvailability->doi_url }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-break"
                                    >
                                        {{ $dataAvailability->doi_url }}

                                        <i class="bi bi-box-arrow-up-right ms-1"></i>
                                    </a>

                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Access Restriction --}}
                    @if($dataAvailability->access_restriction)

                        <div class="col-12 col-lg-6">

                            <div class="info-box">

                                <div class="info-label">
                                    Access Restriction
                                </div>

                                <div class="info-value">
                                    {{ $dataAvailability->access_restriction }}
                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Restriction Reason --}}
                    @if($dataAvailability->restriction_reason)

                        <div class="col-12 col-lg-6">

                            <div class="text-field">

                                <div class="info-label">
                                    Restriction Reason
                                </div>

                                <div class="text-content">

                                    {!! nl2br(e($dataAvailability->restriction_reason)) !!}

                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Statement --}}
                    @if($dataAvailability->statement)

                        <div class="col-12">

                            <div class="text-field">

                                <div class="info-label">
                                    Data Availability Statement
                                </div>

                                <div class="text-content">

                                    {!! nl2br(e($dataAvailability->statement)) !!}

                                </div>

                            </div>

                        </div>

                    @endif

                </div>

            @else

                <div class="empty-submitted">
                    <i class="bi bi-info-circle"></i>
                    No data availability information was submitted.
                </div>

            @endif

        </div>

    </div>



    {{-- =================================================
         5. ACKNOWLEDGEMENTS
    ================================================== --}}

    <div class="submitted-card">

        <div class="submitted-card-header">

            <div class="d-flex align-items-center gap-2">

                <div class="mini-icon bg-secondary-subtle text-secondary">
                    <i class="bi bi-chat-square-heart"></i>
                </div>

                <div>
                    <div class="fw-semibold">
                        Acknowledgements
                    </div>

                    <div class="small text-muted">
                        Author acknowledgement information
                    </div>
                </div>

            </div>


            <a
                href="#"
                class="btn btn-sm btn-outline-primary"
            >
                <i class="bi bi-pencil-square me-1"></i>
                <span class="d-none d-sm-inline">Edit</span>
            </a>

        </div>


        <div class="submitted-card-body">

            @if($acknowledgement)

                <div class="row g-3">

                    {{-- Applicable --}}
                    <div class="col-12 col-sm-6 col-lg-4">

                        <div class="info-box">

                            <div class="info-label">
                                Acknowledgement Applicable
                            </div>

                            <div class="info-value">

                                @if($acknowledgement->applicable)

                                    <span class="status-badge status-success">
                                        <i class="bi bi-check-circle"></i>
                                        Yes
                                    </span>

                                @else

                                    <span class="status-badge status-secondary">
                                        No
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Text --}}
                    <div class="col-12">

                        <div class="text-field">

                            <div class="info-label">
                                Acknowledgement
                            </div>

                            <div class="text-content">

                                @if($acknowledgement->text)

                                    {!! nl2br(e($acknowledgement->text)) !!}

                                @else

                                    <span class="text-muted">
                                        No acknowledgement text was provided.
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            @else

                <div class="empty-submitted">
                    <i class="bi bi-info-circle"></i>
                    No acknowledgement information was submitted.
                </div>

            @endif

        </div>

    </div>

</div>

            {{-- =====================================================
                 SUBMITTED FILES
            ====================================================== --}}

            @if($submittedFiles->count())

                <div>

                    <h6 class="fw-bold border-bottom pb-2 mb-3">

                        <i class="bi bi-paperclip me-1"></i>

                        Submitted Files

                    </h6>


                    <div class="list-group">

                        @foreach($submittedFiles as $file)

                            <div class="list-group-item">

                                <div class="d-flex justify-content-between align-items-center gap-3">

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="fs-4 text-primary">

                                            <i class="bi bi-file-earmark"></i>

                                        </div>


                                        <div>

                                            <div class="fw-semibold">

                                                {{ $file->original_name
                                                    ?? $file->name
                                                    ?? 'Submitted File' }}

                                            </div>


                                            <div class="small text-muted">

                                                {{ $file->file_type
                                                    ?? $file->mime_type
                                                    ?? 'File' }}


                                                @if($file->file_size)

                                                    •
                                                    {{ number_format(
                                                        $file->file_size / 1024,
                                                        1
                                                    ) }}
                                                    KB

                                                @endif

                                            </div>

                                        </div>

                                    </div>


                                    @if($file->file_path)

                                        <a href="{{ asset('storage/' . $file->file_path) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-primary">

                                            <i class="bi bi-eye me-1"></i>

                                            View

                                        </a>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            @else

                <div class="alert alert-light border mb-0">

                    <i class="bi bi-info-circle me-1"></i>

                    No submitted files found.

                </div>

            @endif

        </div>

    </div>




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
                        {{ $completedItems }} / {{ $totalItems }}
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
                                                class="form-select form-select-sm result-select js-check-result"
                                                data-item-id="{{ $item->id }}"
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

                                                <option value="na"
                                                    {{ $item->result === 'na' ? 'selected' : '' }}>
                                                    N/A
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
                                                class="form-control form-control-sm js-check-comment"
                                                data-item-id="{{ $item->id }}"
                                                placeholder="Required when result is Fail"
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
                                        class="form-select form-select-sm js-check-result"
                                        data-item-id="{{ $item->id }}"
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

                                        <option value="na"
                                            {{ $item->result === 'na' ? 'selected' : '' }}>
                                            N/A
                                        </option>

                                    </select>

                                </div>


                                <div>

                                    <label class="form-label small fw-semibold">
                                        Comment / Correction Required
                                    </label>

                                    <input
                                        type="text"
                                        name="items[{{ $item->id }}][comment]"
                                        value="{{ old(
                                            'items.' . $item->id . '.comment',
                                            $item->comment
                                        ) }}"
                                        class="form-control form-control-sm js-check-comment"
                                        data-item-id="{{ $item->id }}"
                                        placeholder="Required when result is Fail"
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
     ADD TECHNICAL ISSUE
====================================================== --}}

@can('technical_check.perform')

    @if(
        $technicalCheck &&
        in_array(
            $technicalCheck->status,
            ['pending', 'in_progress'],
            true
        )
    )

        <div class="tc-card mb-4">

            <div class="tc-card-header">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div>
                        <div class="tc-card-title">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Add Technical Issue
                        </div>

                        <div class="tc-card-description">
                            Record a technical problem that requires
                            correction by the author.
                        </div>
                    </div>

                    <span class="badge bg-danger">
                        Technical Issue
                    </span>

                </div>

            </div>


            <div class="tc-card-body">

                <form
                    method="POST"
                    action="{{ route(
                        'admin.manuscripts.technical-check.issue',
                        $technicalCheck
                    ) }}"
                >

                    @csrf

                    <div class="row g-3">

                        {{-- ==========================================
                             CHECKLIST ITEM
                        =========================================== --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Checklist Item
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="technical_check_item_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    — Select failed checklist item —
                                </option>

                                @foreach(
                                    $technicalCheck->items
                                        ->where('result', 'fail')
                                        ->sortBy('sort_order')
                                    as $item
                                )

                                    <option
                                        value="{{ $item->id }}"
                                        {{ old('technical_check_item_id') == $item->id ? 'selected' : '' }}
                                    >
                                        {{ $item->sort_order }}.
                                        {{ $item->check_name }}
                                        — Failed
                                    </option>

                                @endforeach

                            </select>

                            <div class="form-text">
                                Only failed checklist items can have a Technical Issue.
                                First mark the item as <strong>Fail</strong>, enter a short
                                correction comment, and save the checklist.
                            </div>

                            @if($technicalCheck->items->where('result', 'fail')->count() === 0)
                                <div class="alert alert-info small mt-2 mb-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    No failed checklist item is available. Mark an item as
                                    <strong>Fail</strong> and save the checklist first.
                                </div>
                            @endif

                        </div>


                        {{-- ==========================================
                             MANUSCRIPT FILE
                        =========================================== --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Manuscript File
                            </label>

                            <select
                                name="manuscript_file_id"
                                class="form-select"
                            >

                                <option value="">
                                    — Select file if applicable —
                                </option>

                                @foreach($manuscript->files as $file)

                                    <option
                                        value="{{ $file->id }}"
                                        {{ old('manuscript_file_id') == $file->id ? 'selected' : '' }}
                                    >
                                        {{ $file->original_name }}
                                    </option>

                                @endforeach

                            </select>

                            <div class="form-text">
                                Select a file when the issue relates
                                to a specific uploaded file.
                            </div>

                        </div>


                        {{-- ==========================================
                             CATEGORY
                        =========================================== --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Category
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="category"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    — Select category —
                                </option>

                                <option value="formatting" {{ old('category') === 'formatting' ? 'selected' : '' }}>Formatting</option>
                                <option value="file" {{ old('category') === 'file' ? 'selected' : '' }}>File</option>
                                <option value="article_type" {{ old('category') === 'article_type' ? 'selected' : '' }}>Article Type</option>
                                <option value="title" {{ old('category') === 'title' ? 'selected' : '' }}>Title</option>
                                <option value="author_information" {{ old('category') === 'author_information' ? 'selected' : '' }}>Author Information</option>
                                <option value="abstract" {{ old('category') === 'abstract' ? 'selected' : '' }}>Abstract</option>
                                <option value="keywords" {{ old('category') === 'keywords' ? 'selected' : '' }}>Keywords</option>
                                <option value="figures" {{ old('category') === 'figures' ? 'selected' : '' }}>Figures</option>
                                <option value="tables" {{ old('category') === 'tables' ? 'selected' : '' }}>Tables</option>
                                <option value="supplementary_files" {{ old('category') === 'supplementary_files' ? 'selected' : '' }}>Supplementary Files</option>
                                <option value="references" {{ old('category') === 'references' ? 'selected' : '' }}>References</option>
                                <option value="word_count" {{ old('category') === 'word_count' ? 'selected' : '' }}>Word Count</option>
                                <option value="ethics" {{ old('category') === 'ethics' ? 'selected' : '' }}>Ethics</option>
                                <option value="consent" {{ old('category') === 'consent' ? 'selected' : '' }}>Informed Consent</option>
                                <option value="conflict_of_interest" {{ old('category') === 'conflict_of_interest' ? 'selected' : '' }}>Conflict of Interest</option>
                                <option value="funding" {{ old('category') === 'funding' ? 'selected' : '' }}>Funding Information</option>
                                <option value="author_contribution" {{ old('category') === 'author_contribution' ? 'selected' : '' }}>Author Contribution</option>
                                <option value="data_availability" {{ old('category') === 'data_availability' ? 'selected' : '' }}>Data Availability</option>
                                <option value="trial_registration" {{ old('category') === 'trial_registration' ? 'selected' : '' }}>Trial Registration</option>
                                <option value="blinding" {{ old('category') === 'blinding' ? 'selected' : '' }}>Blinding</option>
                                <option value="submission_completeness" {{ old('category') === 'submission_completeness' ? 'selected' : '' }}>Submission Completeness</option>
                                <option value="declarations" {{ old('category') === 'declarations' ? 'selected' : '' }}>Declarations</option>
                                <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>Other</option>

                            </select>

                        </div>


                        {{-- ==========================================
                             SEVERITY
                        =========================================== --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Severity
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="severity"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    — Select severity —
                                </option>

                                <option value="minor" {{ old('severity') === 'minor' ? 'selected' : '' }}>Minor</option>
                                <option value="major" {{ old('severity') === 'major' ? 'selected' : '' }}>Major</option>
                                <option value="critical" {{ old('severity') === 'critical' ? 'selected' : '' }}>Critical</option>

                            </select>

                        </div>


                        {{-- ==========================================
                             DESCRIPTION
                        =========================================== --}}

                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Problem Identified
                                <span class="text-danger">*</span>
                            </label>

                            <textarea
                                name="description"
                                rows="4"
                                class="form-control"
                                maxlength="5000"
                                required
                                placeholder="Describe the technical problem found in the manuscript..."
                            >{{ old('description') }}</textarea>

                        </div>


                        {{-- ==========================================
                             REQUIRED ACTION
                        =========================================== --}}

                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Required Correction / Action
                            </label>

                            <textarea
                                name="required_action"
                                rows="3"
                                class="form-control"
                                maxlength="5000"
                                placeholder="Explain what the author needs to correct..."
                            >{{ old('required_action') }}</textarea>

                        </div>


                        {{-- ==========================================
                             BUTTON
                        =========================================== --}}

                        <div class="col-12">

                            <div class="d-flex justify-content-end">

                                <button
                                    type="submit"
                                    class="btn btn-danger"
                                >
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Add Technical Issue
                                </button>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    @endif

@endcan


{{-- =====================================================
     TECHNICAL ISSUES
====================================================== --}}

@can('technical_check.view')

<div class="tc-card mb-4">

    <div class="tc-card-header">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div>

                <div class="tc-card-title">

                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>

                    Technical Issues

                </div>

                <div class="tc-card-description">

                    Technical problems identified during this technical check.

                </div>

            </div>

                @if($openIssues > 0)

                <span class="badge bg-danger">
                    {{ $openIssues }}
                    Open Issue{{ $openIssues > 1 ? 's' : '' }}
                </span>

                @else

                <span class="badge bg-success">
                    No Open Issues
                </span>

                @endif

        </div>

    </div>


    <div class="tc-card-body">

        @if($issues->count() > 0)

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="55">#</th>

                            <th>Checklist Item</th>

                            <th>Category</th>

                            <th>Severity</th>

                            <th>Problem Identified</th>

                            <th>Required Action</th>

                            <th>Status</th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($issues as $index => $issue)

                            <tr>

                                <td class="text-center fw-semibold">

                                    {{ $index + 1 }}

                                </td>


                                <td>

                                    @if($issue->technicalCheckItem)

                                        <strong>

                                            {{ $issue->technicalCheckItem->sort_order }}.

                                            {{ $issue->technicalCheckItem->check_name }}

                                        </strong>

                                    @else

                                        <span class="text-muted">

                                            General Technical Issue

                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <span class="badge bg-secondary">

                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $issue->category
                                            )
                                        ) }}

                                    </span>


                                    @if($issue->manuscriptFile)

                                        <div class="small text-muted mt-2">

                                            <i class="bi bi-file-earmark me-1"></i>

                                            {{ $issue->manuscriptFile->original_name }}

                                        </div>

                                    @endif

                                </td>


                                <td>

                                    @if($issue->severity === 'critical')

                                        <span class="badge bg-danger">

                                            Critical

                                        </span>

                                    @elseif($issue->severity === 'major')

                                        <span class="badge bg-warning text-dark">

                                            Major

                                        </span>

                                    @elseif($issue->severity === 'minor')

                                        <span class="badge bg-info text-dark">

                                            Minor

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            {{ ucfirst($issue->severity) }}

                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <div style="white-space: pre-line;">

                                        {{ $issue->description }}

                                    </div>

                                </td>


                                <td>

                                    @if($issue->required_action)

                                        <div style="white-space: pre-line;">

                                            {{ $issue->required_action }}

                                        </div>

                                    @else

                                        <span class="text-muted">

                                            No specific action provided.

                                        </span>

                                    @endif

                                </td>


                                <td class="text-center">

                                    @if($issue->status === 'open')

                                        <span class="badge bg-danger">

                                            Open

                                        </span>

                                    @elseif($issue->status === 'resolved')

                                        <span class="badge bg-success">

                                            Resolved

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            {{ ucfirst($issue->status) }}

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="text-center py-5">

                <i
                    class="bi bi-check-circle-fill text-success"
                    style="font-size: 3rem;"
                ></i>

                <h6 class="fw-semibold mt-3">

                    No Technical Issues

                </h6>

                <p class="text-muted mb-0">

                    No technical issues have been recorded for this technical check.

                </p>

            </div>

        @endif

    </div>

</div>

@endcan


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

                            All {{ $totalItems }} checklist items are complete
                            (Pass or N/A), with no open technical issues.

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

                                                {{ $failedItems }} checklist item(s) failed.
                                                Add a correction comment for every failed item,
                                                then return the manuscript to the author.

                                            </div>

                                        @elseif($pendingItems > 0)

                                            <div class="alert alert-warning py-2 px-3 small mb-3">

                                                <i class="bi bi-clock me-1"></i>

                                                {{ $pendingItems }} checklist item(s) are still pending.

                                            </div>

                                        @elseif($openIssues > 0)

                                            <div class="alert alert-warning py-2 px-3 small mb-3">

                                                <i class="bi bi-exclamation-triangle me-1"></i>

                                                {{ $openIssues }} open technical issue(s) must be resolved
                                                before the technical check can be completed.

                                            </div>

                                        @else

                                            <div class="alert alert-success py-2 px-3 small mb-3">

                                                <i class="bi bi-check-circle me-1"></i>

                                                All checklist items are complete (Pass or N/A),
                                                and there are no open technical issues.
                                                The manuscript can now be forwarded to Payment.

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


                                        @if($failedItems === 0)

                                            <div class="alert alert-light border py-2 px-3 small mb-3">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Mark at least one checklist item as <strong>Fail</strong>
                                                and provide its correction comment before returning to the author.
                                            </div>

                                        @endif

                                        @can('technical_check.return')

                                            <button type="button"
                                                    class="btn btn-outline-warning"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#returnAuthorModal"
                                                    {{ $failedItems === 0 ? 'disabled' : '' }}>

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


                                @if($technicalCheck && $failedItems > 0)

                                    <div class="mb-3">
                                        <div class="fw-semibold mb-2">
                                            Failed Checklist Items
                                        </div>

                                        <div class="list-group">
                                            @foreach($technicalCheck->items->where('result', 'fail')->sortBy('sort_order') as $failedItem)
                                                <div class="list-group-item">
                                                    <div class="fw-semibold">
                                                        {{ $failedItem->sort_order }}. {{ $failedItem->check_name }}
                                                    </div>
                                                    <div class="small text-muted mt-1">
                                                        {{ $failedItem->comment ?: 'No correction comment provided.' }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="form-text mt-2">
                                            For each failed item, the system will use the linked open Technical Issue. If no linked issue exists but a checklist correction comment is available, the controller will create the Technical Issue automatically when the manuscript is returned.
                                        </div>
                                    </div>

                                @endif


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


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    /*
    |--------------------------------------------------------------------------
    | Failed checklist items
    |--------------------------------------------------------------------------
    | The current controller requires an item to be saved as Fail before a
    | manual Technical Issue can be attached to it. Therefore, when an officer
    | first chooses Fail, require a short correction comment for that save.
    */

    const resultSelects = document.querySelectorAll('.js-check-result');

    function syncCommentRequirement(select) {
        const itemId = select.dataset.itemId;
        const form = select.closest('form');

        if (!form || !itemId) {
            return;
        }

        const comment = form.querySelector(
            '.js-check-comment[data-item-id="' + itemId + '"]'
        );

        if (!comment) {
            return;
        }

        if (select.value === 'fail') {
            comment.setAttribute('required', 'required');
            comment.placeholder = 'Required: briefly describe the correction needed';
        } else {
            comment.removeAttribute('required');
            comment.placeholder = 'Optional comment';
        }
    }

    resultSelects.forEach(function (select) {
        syncCommentRequirement(select);

        select.addEventListener('change', function () {
            syncCommentRequirement(select);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | File category
    |--------------------------------------------------------------------------
    | Keep the UI consistent with controller validation: when category = file,
    | the manuscript file field becomes required.
    */

    const issueForms = document.querySelectorAll(
        'form[action*="technical-check"][action*="issue"]'
    );

    issueForms.forEach(function (form) {
        const category = form.querySelector('[name="category"]');
        const manuscriptFile = form.querySelector('[name="manuscript_file_id"]');

        if (!category || !manuscriptFile) {
            return;
        }

        function syncFileRequirement() {
            if (category.value === 'file') {
                manuscriptFile.setAttribute('required', 'required');
            } else {
                manuscriptFile.removeAttribute('required');
            }
        }

        syncFileRequirement();
        category.addEventListener('change', syncFileRequirement);
    });
});
</script>
@endpush

@endsection