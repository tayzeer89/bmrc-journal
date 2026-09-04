@extends('admin.layouts.app')

@section('title', 'Technical Check')

@php
    /*
    |--------------------------------------------------------------------------
    | Basic Manuscript Information
    |--------------------------------------------------------------------------
    */

    $manuscriptNumber = $manuscript->manuscript_id ?? $manuscript->id;

    /*
    |--------------------------------------------------------------------------
    | Technical Check / Items
    |--------------------------------------------------------------------------
    */

    $items = $technicalCheck
        ? (($technicalCheck->items ?? collect())
            ->sortBy('sort_order')
            ->values())
        : collect();

    $totalItems = $items->count();

    $passedItems = $items
        ->where('result', 'pass')
        ->count();

    $failedItems = $items
        ->where('result', 'fail')
        ->count();

    $pendingItems = $items
        ->where('result', 'pending')
        ->count();

    $completedItems = $totalItems - $pendingItems;

    $completionPercent = $totalItems > 0
        ? round(($completedItems / $totalItems) * 100)
        : 0;

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    $checkStatus = $technicalCheck?->status;

    $manuscriptStatus = $manuscript->status;

    $currentStage = $manuscript->current_stage;

    /*
    |--------------------------------------------------------------------------
    | Status Labels
    |--------------------------------------------------------------------------
    */

    $checkStatusLabels = [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'passed' => 'Passed',
        'correction_required' => 'Correction Required',
        'failed' => 'Failed',
    ];

    $checkStatusClasses = [
        'pending' => 'bg-secondary-subtle text-secondary',
        'in_progress' => 'bg-primary-subtle text-primary',
        'passed' => 'bg-success-subtle text-success',
        'correction_required' => 'bg-warning-subtle text-warning-emphasis',
        'failed' => 'bg-danger-subtle text-danger',
    ];

    $checkStatusLabel = $checkStatusLabels[$checkStatus] ?? 'Not Started';

    $checkStatusClass = $checkStatusClasses[$checkStatus]
        ?? 'bg-light text-secondary';

    /*
    |--------------------------------------------------------------------------
    | Manuscript Status Labels
    |--------------------------------------------------------------------------
    */

    $statusLabels = [
        'draft' => 'Draft',
        'submitted' => 'Submitted',
        'technical_check' => 'Technical Check',
        'technical_correction' => 'Technical Correction',
        'payment_required' => 'Payment Required',
        'payment_correction' => 'Payment Correction',
        'payment_verified' => 'Payment Verified',
        'editorial_assessment' => 'Editorial Assessment',
        'similarity_check' => 'Similarity Check',
        'under_review' => 'Under Review',
        'revision_required' => 'Revision Required',
        'accepted' => 'Accepted',
        'rejected' => 'Rejected',
        'copy_editing' => 'Copy Editing',
        'proofreading' => 'Proofreading',
        'production' => 'Production',
        'published' => 'Published',
    ];

    $stageLabels = [
        'submission' => 'Submission',
        'technical_review' => 'Technical Review',
        'author_correction' => 'Author Correction',
        'payment' => 'Payment',
        'payment_correction' => 'Payment Correction',
        'editorial_assessment' => 'Editorial Assessment',
        'similarity_check' => 'Similarity Check',
        'peer_review' => 'Peer Review',
        'revision' => 'Revision',
        'decision' => 'Decision',
        'copy_editing' => 'Copy Editing',
        'proofreading' => 'Proofreading',
        'production' => 'Production',
        'publication' => 'Publication',
    ];

    $manuscriptStatusLabel =
        $statusLabels[$manuscriptStatus]
        ?? ucwords(str_replace('_', ' ', $manuscriptStatus ?? 'Unknown'));

    $currentStageLabel =
        $stageLabels[$currentStage]
        ?? ucwords(str_replace('_', ' ', $currentStage ?? 'Unknown'));

    /*
    |--------------------------------------------------------------------------
    | Workflow Position
    |
    | Main workflow:
    |
    | Submission
    |     ↓
    | Technical Review
    |     ↓
    | Technical Check
    |     ↓
    | Payment
    |     ↓
    | Editorial Assessment
    |
    | Correction is a conditional branch:
    |
    | Technical Check
    |     ↓
    | Author Correction
    |     ↓
    | Resubmission
    |     ↓
    | New Technical Check
    |--------------------------------------------------------------------------
    */

    if (
        in_array($manuscriptStatus, [
            'payment_required',
            'payment_correction',
            'payment_verified',
        ]) ||
        $currentStage === 'payment'
    ) {
        $workflowPosition = 3;
    } elseif (
        in_array($manuscriptStatus, [
            'editorial_assessment',
            'similarity_check',
            'under_review',
            'revision_required',
            'accepted',
            'rejected',
            'copy_editing',
            'proofreading',
            'production',
            'published',
        ]) ||
        $currentStage === 'editorial_assessment'
    ) {
        $workflowPosition = 4;
    } elseif (
        in_array($manuscriptStatus, [
            'technical_check',
        ])
    ) {
        $workflowPosition = 2;
    } elseif (
        in_array($manuscriptStatus, [
            'technical_correction',
        ]) ||
        $currentStage === 'author_correction'
    ) {
        /*
        | Correction is a branch from Technical Check.
        | Keep the main workflow visually at Technical Check.
        */
        $workflowPosition = 2;
    } elseif (
        in_array($manuscriptStatus, [
            'submitted',
        ]) ||
        $currentStage === 'technical_review'
    ) {
        $workflowPosition = 1;
    } else {
        $workflowPosition = 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Is Payment Stage?
    |--------------------------------------------------------------------------
    */

    $isPaymentStage =
        $manuscriptStatus === 'payment_required'
        || $manuscriptStatus === 'payment_correction'
        || $manuscriptStatus === 'payment_verified'
        || $currentStage === 'payment';

    /*
    |--------------------------------------------------------------------------
    | Is Author Correction Stage?
    |--------------------------------------------------------------------------
    */

    $isCorrectionStage =
        $manuscriptStatus === 'technical_correction'
        || $currentStage === 'author_correction';

    /*
    |--------------------------------------------------------------------------
    | Is Technical Check Active?
    |--------------------------------------------------------------------------
    */

    $isTechnicalCheckInProgress =
        $technicalCheck
        && $technicalCheck->status === 'in_progress';

    /*
    |--------------------------------------------------------------------------
    | Can Start?
    |--------------------------------------------------------------------------
    */

    $canStartTechnicalCheck =
        !$technicalCheck
        && !$isPaymentStage
        && !$isCorrectionStage
        && (
            $manuscriptStatus === 'submitted'
            || $currentStage === 'technical_review'
            || $manuscriptStatus === 'technical_check'
        );

    /*
    |--------------------------------------------------------------------------
    | Can Edit Checklist?
    |--------------------------------------------------------------------------
    */

    $canEditChecklist =
        $technicalCheck
        && $technicalCheck->status === 'in_progress';

    /*
    |--------------------------------------------------------------------------
    | Ready To Complete?
    |--------------------------------------------------------------------------
    */

    $readyToComplete =
        $technicalCheck
        && $technicalCheck->status === 'in_progress'
        && $totalItems > 0
        && $pendingItems === 0
        && $failedItems === 0;

    /*
    |--------------------------------------------------------------------------
    | Technical Issues
    |--------------------------------------------------------------------------
    */

    $issues = collect();

    if ($technicalCheck) {
        try {
            $issues = $technicalCheck->issues ?? collect();
        } catch (\Throwable $e) {
            $issues = collect();
        }
    }
@endphp

@section('content')

<style>
    /*
    |--------------------------------------------------------------------------
    | Page
    |--------------------------------------------------------------------------
    */

    .technical-check-page {
        max-width: 1600px;
        margin: 0 auto;
    }

    /*
    |--------------------------------------------------------------------------
    | Cards
    |--------------------------------------------------------------------------
    */

    .tc-card {
        border: 0;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.055);
        overflow: hidden;
    }

    .tc-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #edf0f3;
        background: #ffffff;
    }

    .tc-card-body {
        padding: 22px;
    }

    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */

    .page-title {
        font-size: 1.45rem;
        font-weight: 700;
        color: #202124;
        margin-bottom: 4px;
        overflow-wrap: anywhere;
    }

    .page-subtitle {
        color: #6c757d;
        font-size: 0.9rem;
        overflow-wrap: anywhere;
    }

    .manuscript-number {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 11px;
        border-radius: 8px;
        background: #f4f6f8;
        color: #495057;
        font-size: 0.82rem;
        font-weight: 600;
    }

    /*
    |--------------------------------------------------------------------------
    | Section Title
    |--------------------------------------------------------------------------
    */

    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: #212529;
    }

    .section-title-icon {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #f1f4f7;
        color: #495057;
        flex-shrink: 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Workflow
    |--------------------------------------------------------------------------
    */

    .workflow-wrapper {
        position: relative;
        padding: 8px 4px 4px;
    }

    .workflow-scroll {
        overflow-x: auto;
        overflow-y: hidden;
        padding: 5px 3px 12px;
        scrollbar-width: thin;
    }

    .workflow-steps {
        min-width: 760px;
        display: flex;
        align-items: flex-start;
        position: relative;
    }

    .workflow-step {
        flex: 1;
        position: relative;
        text-align: center;
        min-width: 140px;
    }

    .workflow-step:not(:last-child)::after {
        content: "";
        position: absolute;
        top: 21px;
        left: calc(50% + 22px);
        right: calc(-50% + 22px);
        height: 3px;
        background: #e9ecef;
        z-index: 1;
    }

    .workflow-step.completed:not(:last-child)::after {
        background: #198754;
    }

    .workflow-circle {
        width: 44px;
        height: 44px;
        margin: 0 auto 10px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f3f5;
        border: 3px solid #e9ecef;
        color: #6c757d;
        position: relative;
        z-index: 2;
        font-size: 1rem;
        transition: all 0.2s ease;
    }

    .workflow-step.completed .workflow-circle {
        background: #198754;
        border-color: #198754;
        color: #ffffff;
    }

    .workflow-step.current .workflow-circle {
        background: #0d6efd;
        border-color: #b6d4fe;
        color: #ffffff;
        box-shadow: 0 0 0 5px rgba(13, 110, 253, 0.10);
    }

    .workflow-step.correction .workflow-circle {
        background: #ffc107;
        border-color: #ffe69c;
        color: #212529;
    }

    .workflow-label {
        font-size: 0.82rem;
        font-weight: 700;
        color: #6c757d;
        white-space: nowrap;
    }

    .workflow-step.completed .workflow-label {
        color: #198754;
    }

    .workflow-step.current .workflow-label {
        color: #0d6efd;
    }

    .workflow-caption {
        font-size: 0.7rem;
        color: #9aa0a6;
        margin-top: 3px;
        white-space: nowrap;
    }

    /*
    |--------------------------------------------------------------------------
    | Correction Branch
    |--------------------------------------------------------------------------
    */

    .workflow-branch {
        margin-top: 15px;
        padding: 14px 16px;
        border-radius: 11px;
        background: #fffaf0;
        border: 1px dashed #e7c46a;
    }

    .workflow-branch-title {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #856404;
        font-weight: 700;
        font-size: 0.84rem;
        margin-bottom: 6px;
    }

    .workflow-branch-text {
        color: #6c5a26;
        font-size: 0.78rem;
        line-height: 1.6;
        margin: 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Passed -> Payment Transition
    |--------------------------------------------------------------------------
    */

    .transition-card {
        border-radius: 14px;
        padding: 20px;
        border: 1px solid #b7dfc9;
        background: linear-gradient(
            135deg,
            #f1fbf5 0%,
            #ffffff 100%
        );
    }

    .transition-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #198754;
        color: #ffffff;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .transition-title {
        color: #146c43;
        font-size: 1rem;
        font-weight: 800;
        margin-bottom: 3px;
    }

    .transition-text {
        color: #4f6358;
        font-size: 0.83rem;
        margin: 0;
        line-height: 1.55;
    }

    .transition-flow {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 16px;
        flex-wrap: wrap;
    }

    .transition-node {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 13px;
        border-radius: 9px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .transition-node.passed {
        background: #d1e7dd;
        color: #0f5132;
    }

    .transition-node.payment {
        background: #fff3cd;
        color: #664d03;
        border: 1px solid #ffe69c;
    }

    .transition-node.next {
        background: #f1f3f5;
        color: #6c757d;
    }

    .transition-arrow {
        color: #6c757d;
        font-size: 1rem;
    }

    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .summary-item {
        padding: 14px;
        border-radius: 10px;
        background: #f8f9fa;
        min-width: 0;
    }

    .summary-label {
        display: block;
        color: #6c757d;
        font-size: 0.72rem;
        font-weight: 600;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .summary-value {
        color: #212529;
        font-size: 0.88rem;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    /*
    |--------------------------------------------------------------------------
    | Progress
    |--------------------------------------------------------------------------
    */

    .progress-container {
        margin-top: 20px;
    }

    .progress-label {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 7px;
        font-size: 0.78rem;
        color: #6c757d;
        font-weight: 600;
    }

    .progress {
        height: 9px;
        border-radius: 20px;
        background: #e9ecef;
    }

    .progress-bar {
        border-radius: 20px;
    }

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }

    .stat-box {
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        background: #f8f9fa;
    }

    .stat-number {
        display: block;
        font-size: 1.35rem;
        line-height: 1.2;
        font-weight: 800;
        color: #212529;
    }

    .stat-label {
        display: block;
        margin-top: 4px;
        color: #6c757d;
        font-size: 0.73rem;
        font-weight: 600;
    }

    /*
    |--------------------------------------------------------------------------
    | Checklist Table
    |--------------------------------------------------------------------------
    */

    .checklist-table {
        margin-bottom: 0;
    }

    .checklist-table thead th {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        color: #495057;
        font-size: 0.74rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        font-weight: 700;
        padding: 12px;
        vertical-align: middle;
    }

    .checklist-table tbody td {
        padding: 12px;
        vertical-align: top;
        border-color: #edf0f2;
    }

    .check-number {
        width: 38px;
        height: 30px;
        border-radius: 7px;
        background: #f1f3f5;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: #495057;
    }

    .check-name {
        font-size: 0.84rem;
        font-weight: 650;
        color: #212529;
        line-height: 1.45;
    }

    .check-key {
        font-size: 0.7rem;
        color: #9aa0a6;
        margin-top: 3px;
        overflow-wrap: anywhere;
    }

    .result-select {
        min-width: 125px;
        font-size: 0.8rem;
    }

    .item-comment {
        min-width: 180px;
        font-size: 0.78rem;
        resize: vertical;
    }

    /*
    |--------------------------------------------------------------------------
    | Mobile Checklist
    |--------------------------------------------------------------------------
    */

    .mobile-check-item {
        border: 1px solid #e9ecef;
        border-radius: 11px;
        padding: 14px;
        margin-bottom: 10px;
        background: #ffffff;
    }

    .mobile-check-item:last-child {
        margin-bottom: 0;
    }

    .mobile-check-header {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .mobile-check-content {
        flex: 1;
        min-width: 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Empty State
    |--------------------------------------------------------------------------
    */

    .empty-state {
        text-align: center;
        padding: 45px 20px;
    }

    .empty-icon {
        width: 62px;
        height: 62px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f1f3f5;
        color: #6c757d;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .empty-title {
        font-size: 1rem;
        font-weight: 700;
        color: #343a40;
        margin-bottom: 5px;
    }

    .empty-text {
        max-width: 600px;
        margin: 0 auto 18px;
        color: #6c757d;
        font-size: 0.83rem;
        line-height: 1.6;
    }

    /*
    |--------------------------------------------------------------------------
    | Alert
    |--------------------------------------------------------------------------
    */

    .tc-alert {
        border: 0;
        border-radius: 10px;
        padding: 13px 15px;
        font-size: 0.82rem;
    }

    /*
    |--------------------------------------------------------------------------
    | Decision Area
    |--------------------------------------------------------------------------
    */

    .decision-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 18px;
        background: #ffffff;
    }

    .decision-card h6 {
        font-size: 0.9rem;
        font-weight: 750;
        margin-bottom: 5px;
    }

    .decision-card p {
        color: #6c757d;
        font-size: 0.78rem;
        margin-bottom: 15px;
        line-height: 1.55;
    }

    .return-box {
        border-color: #ffc107;
        background: #fffdf5;
    }

    /*
    |--------------------------------------------------------------------------
    | Issue
    |--------------------------------------------------------------------------
    */

    .issue-item {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 10px;
    }

    .issue-item:last-child {
        margin-bottom: 0;
    }

    .issue-title {
        font-size: 0.83rem;
        font-weight: 700;
        color: #212529;
    }

    .issue-description {
        font-size: 0.78rem;
        color: #6c757d;
        line-height: 1.55;
        margin-top: 7px;
    }

    /*
    |--------------------------------------------------------------------------
    | Buttons
    |--------------------------------------------------------------------------
    */

    .btn {
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /*
    |--------------------------------------------------------------------------
    | Mobile
    |--------------------------------------------------------------------------
    */

    @media (max-width: 991.98px) {
        .summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .stat-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 767.98px) {
        .technical-check-page {
            width: 100%;
        }

        .tc-card-header,
        .tc-card-body {
            padding: 16px;
        }

        .page-title {
            font-size: 1.2rem;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            width: 100%;
        }

        .summary-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .summary-item {
            padding: 11px;
        }

        .summary-value {
            font-size: 0.8rem;
        }

        .stat-grid {
            gap: 8px;
        }

        .stat-box {
            padding: 12px 8px;
        }

        .stat-number {
            font-size: 1.15rem;
        }

        .workflow-steps {
            min-width: 720px;
        }

        .transition-flow {
            align-items: stretch;
            flex-direction: column;
        }

        .transition-node {
            width: 100%;
            justify-content: center;
        }

        .transition-arrow {
            text-align: center;
            transform: rotate(90deg);
        }

        .desktop-checklist {
            display: none;
        }

        .mobile-checklist {
            display: block !important;
        }

        .decision-actions .btn {
            width: 100%;
        }
    }

    @media (min-width: 768px) {
        .mobile-checklist {
            display: none !important;
        }
    }
</style>

<div class="technical-check-page">

    {{-- ============================================================
         PAGE HEADER
    ============================================================= --}}

    <div class="d-flex flex-column flex-md-row justify-content-between
                align-items-start align-items-md-center gap-3 mb-4">

        <div class="min-w-0">

            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">

                <span class="manuscript-number">
                    <i class="bi bi-file-earmark-text"></i>
                    {{ $manuscriptNumber }}
                </span>

                <span class="badge rounded-pill {{ $checkStatusClass }}">
                    {{ $checkStatusLabel }}
                </span>

                <span class="badge rounded-pill bg-light text-dark border">
                    {{ $currentStageLabel }}
                </span>

            </div>

            <h1 class="page-title">
                Technical Check
            </h1>

            <div class="page-subtitle">
                {{ $manuscript->title ?? 'Untitled Manuscript' }}
            </div>

        </div>

        <div class="header-actions d-flex flex-wrap gap-2">

            <a href="{{ route('admin.manuscripts.technical-review.index') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Technical Review
            </a>

            <a href="{{ route('admin.manuscripts.show', $manuscript) }}"
               class="btn btn-primary">
                <i class="bi bi-file-earmark-text me-1"></i>
                View Manuscript
            </a>

        </div>

    </div>


    {{-- ============================================================
         FLASH MESSAGES
    ============================================================= --}}

    @if(session('success'))
        <div class="alert alert-success tc-alert d-flex align-items-start gap-2 mb-4">
            <i class="bi bi-check-circle-fill mt-1"></i>
            <div>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning tc-alert d-flex align-items-start gap-2 mb-4">
            <i class="bi bi-exclamation-triangle-fill mt-1"></i>
            <div>
                {{ session('warning') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger tc-alert d-flex align-items-start gap-2 mb-4">
            <i class="bi bi-x-circle-fill mt-1"></i>
            <div>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger tc-alert mb-4">

            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                Please correct the following:
            </div>

            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    {{-- ============================================================
         WORKFLOW BAR
    ============================================================= --}}

    <div class="tc-card mb-4">

        <div class="tc-card-header">

            <h5 class="section-title">
                <span class="section-title-icon">
                    <i class="bi bi-diagram-3"></i>
                </span>

                Manuscript Workflow
            </h5>

        </div>

        <div class="tc-card-body">

            <div class="workflow-wrapper">

                <div class="workflow-scroll">

                    <div class="workflow-steps">

                        {{-- Submission --}}
                        <div class="workflow-step
                            {{ $workflowPosition > 0 ? 'completed' : '' }}
                            {{ $workflowPosition === 0 ? 'current' : '' }}">

                            <div class="workflow-circle">
                                @if($workflowPosition > 0)
                                    <i class="bi bi-check-lg"></i>
                                @else
                                    <i class="bi bi-send"></i>
                                @endif
                            </div>

                            <div class="workflow-label">
                                Submission
                            </div>

                            <div class="workflow-caption">
                                Manuscript submitted
                            </div>

                        </div>


                        {{-- Technical Review --}}
                        <div class="workflow-step
                            {{ $workflowPosition > 1 ? 'completed' : '' }}
                            {{ $workflowPosition === 1 ? 'current' : '' }}">

                            <div class="workflow-circle">
                                @if($workflowPosition > 1)
                                    <i class="bi bi-check-lg"></i>
                                @else
                                    <i class="bi bi-clipboard-check"></i>
                                @endif
                            </div>

                            <div class="workflow-label">
                                Technical Review
                            </div>

                            <div class="workflow-caption">
                                Queue & assignment
                            </div>

                        </div>


                        {{-- Technical Check --}}
                        <div class="workflow-step
                            {{ $workflowPosition > 2 ? 'completed' : '' }}
                            {{ $workflowPosition === 2 ? 'current' : '' }}
                            {{ $isCorrectionStage ? 'correction' : '' }}">

                            <div class="workflow-circle">

                                @if($workflowPosition > 2)
                                    <i class="bi bi-check-lg"></i>

                                @elseif($isCorrectionStage)
                                    <i class="bi bi-arrow-repeat"></i>

                                @else
                                    <i class="bi bi-check2-square"></i>
                                @endif

                            </div>

                            <div class="workflow-label">
                                Technical Check
                            </div>

                            <div class="workflow-caption">
                                Checklist verification
                            </div>

                        </div>


                        {{-- Payment --}}
                        <div class="workflow-step
                            {{ $workflowPosition > 3 ? 'completed' : '' }}
                            {{ $workflowPosition === 3 ? 'current' : '' }}">

                            <div class="workflow-circle">

                                @if($workflowPosition > 3)
                                    <i class="bi bi-check-lg"></i>
                                @else
                                    <i class="bi bi-credit-card"></i>
                                @endif

                            </div>

                            <div class="workflow-label">
                                Payment
                            </div>

                            <div class="workflow-caption">
                                Fee & verification
                            </div>

                        </div>


                        {{-- Editorial Assessment --}}
                        <div class="workflow-step
                            {{ $workflowPosition > 4 ? 'completed' : '' }}
                            {{ $workflowPosition === 4 ? 'current' : '' }}">

                            <div class="workflow-circle">

                                @if($workflowPosition > 4)
                                    <i class="bi bi-check-lg"></i>
                                @else
                                    <i class="bi bi-journal-check"></i>
                                @endif

                            </div>

                            <div class="workflow-label">
                                Editorial Assessment
                            </div>

                            <div class="workflow-caption">
                                Editorial evaluation
                            </div>

                        </div>

                    </div>

                </div>


                {{-- Conditional Correction Branch --}}
                <div class="workflow-branch">

                    <div class="workflow-branch-title">
                        <i class="bi bi-arrow-return-right"></i>
                        Correction path — only when technical corrections are required
                    </div>

                    <p class="workflow-branch-text">

                        <strong>Technical Check</strong>
                        → Return to Author
                        → <strong>Author Correction</strong>
                        → Author Resubmits
                        → <strong>New Technical Check</strong>

                        <br>

                        Each resubmission creates a new technical check number,
                        preserving the previous check as a historical record.

                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         PASSED → PAYMENT TRANSITION
    ============================================================= --}}

    @if(
        $technicalCheck?->status === 'passed'
        || $isPaymentStage
    )

        <div class="transition-card mb-4">

            <div class="d-flex align-items-start gap-3">

                <div class="transition-icon">
                    <i class="bi bi-check-lg"></i>
                </div>

                <div class="flex-grow-1">

                    @if($isPaymentStage)

                        <div class="transition-title">
                            Technical Check Passed → Payment
                        </div>

                        <p class="transition-text">
                            This manuscript has successfully passed the technical
                            check and has moved to the payment stage.
                            The next operational step is payment processing and
                            verification.
                        </p>

                    @else

                        <div class="transition-title">
                            Technical Check Passed
                        </div>

                        <p class="transition-text">
                            All technical requirements have been successfully
                            completed.
                        </p>

                    @endif

                </div>

            </div>


            <div class="transition-flow">

                <div class="transition-node passed">
                    <i class="bi bi-check-circle-fill"></i>
                    Technical Check Passed
                </div>

                <div class="transition-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>

                <div class="transition-node payment">
                    <i class="bi bi-credit-card-fill"></i>
                    Payment Required
                </div>

                <div class="transition-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>

                <div class="transition-node next">
                    <i class="bi bi-journal-check"></i>
                    Editorial Assessment
                </div>

            </div>

        </div>

    @endif


    {{-- ============================================================
         AUTHOR CORRECTION STATUS
    ============================================================= --}}

    @if($isCorrectionStage)

        <div class="alert alert-warning tc-alert mb-4">

            <div class="d-flex align-items-start gap-2">

                <i class="bi bi-arrow-return-left mt-1"></i>

                <div>

                    <div class="fw-bold mb-1">
                        Awaiting Author Correction
                    </div>

                    <div>
                        This manuscript has been returned to the author for
                        technical correction. After the author resubmits the
                        corrected manuscript, a <strong>new technical check</strong>
                        should be created.
                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- ============================================================
         TECHNICAL CHECK SUMMARY
    ============================================================= --}}

    @if($technicalCheck)

        <div class="tc-card mb-4">

            <div class="tc-card-header">

                <div class="d-flex flex-column flex-md-row
                            justify-content-between gap-3">

                    <h5 class="section-title">

                        <span class="section-title-icon">
                            <i class="bi bi-clipboard-data"></i>
                        </span>

                        Technical Check #{{ $technicalCheck->check_number }}

                    </h5>

                    <span class="badge rounded-pill {{ $checkStatusClass }}">
                        {{ $checkStatusLabel }}
                    </span>

                </div>

            </div>

            <div class="tc-card-body">

                <div class="summary-grid">

                    <div class="summary-item">
                        <span class="summary-label">
                            Check Number
                        </span>

                        <span class="summary-value">
                            #{{ $technicalCheck->check_number }}
                        </span>
                    </div>


                    <div class="summary-item">
                        <span class="summary-label">
                            Overall Result
                        </span>

                        <span class="summary-value">

                            @if($technicalCheck->overall_result === 'pass')

                                <span class="text-success">
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                    Pass
                                </span>

                            @elseif($technicalCheck->overall_result === 'fail')

                                <span class="text-danger">
                                    <i class="bi bi-x-circle-fill me-1"></i>
                                    Fail
                                </span>

                            @else

                                <span class="text-secondary">
                                    Pending
                                </span>

                            @endif

                        </span>
                    </div>


                    <div class="summary-item">

                        <span class="summary-label">
                            Assigned To
                        </span>

                        <span class="summary-value">

                            {{ $technicalCheck->assignedUser?->name
                                ?? 'Not assigned' }}

                        </span>

                    </div>


                    <div class="summary-item">

                        <span class="summary-label">
                            Started By
                        </span>

                        <span class="summary-value">

                            {{ $technicalCheck->startedBy?->name
                                ?? '—' }}

                        </span>

                    </div>


                    <div class="summary-item">

                        <span class="summary-label">
                            Started At
                        </span>

                        <span class="summary-value">

                            {{ $technicalCheck->started_at
                                ? $technicalCheck->started_at->format('d M Y, h:i A')
                                : '—' }}

                        </span>

                    </div>


                    <div class="summary-item">

                        <span class="summary-label">
                            Completed By
                        </span>

                        <span class="summary-value">

                            {{ $technicalCheck->completedBy?->name
                                ?? '—' }}

                        </span>

                    </div>


                    <div class="summary-item">

                        <span class="summary-label">
                            Completed At
                        </span>

                        <span class="summary-value">

                            {{ $technicalCheck->completed_at
                                ? $technicalCheck->completed_at->format('d M Y, h:i A')
                                : '—' }}

                        </span>

                    </div>


                    <div class="summary-item">

                        <span class="summary-label">
                            Manuscript Status
                        </span>

                        <span class="summary-value">

                            {{ $manuscriptStatusLabel }}

                        </span>

                    </div>

                </div>


                {{-- Progress --}}
                @if($totalItems > 0)

                    <div class="progress-container">

                        <div class="progress-label">

                            <span>
                                Checklist completion
                            </span>

                            <span>
                                {{ $completedItems }}/{{ $totalItems }}
                                ({{ $completionPercent }}%)
                            </span>

                        </div>

                        <div class="progress">

                            <div
                                class="progress-bar bg-success"
                                role="progressbar"
                                style="width: {{ $completionPercent }}%"
                                aria-valuenow="{{ $completionPercent }}"
                                aria-valuemin="0"
                                aria-valuemax="100">
                            </div>

                        </div>

                    </div>

                @endif


                {{-- Statistics --}}
                @if($totalItems > 0)

                    <div class="stat-grid mt-4">

                        <div class="stat-box">

                            <span class="stat-number">
                                {{ $totalItems }}
                            </span>

                            <span class="stat-label">
                                Total Items
                            </span>

                        </div>


                        <div class="stat-box">

                            <span class="stat-number text-success">
                                {{ $passedItems }}
                            </span>

                            <span class="stat-label">
                                Passed
                            </span>

                        </div>


                        <div class="stat-box">

                            <span class="stat-number text-danger">
                                {{ $failedItems }}
                            </span>

                            <span class="stat-label">
                                Failed
                            </span>

                        </div>


                        <div class="stat-box">

                            <span class="stat-number text-warning">
                                {{ $pendingItems }}
                            </span>

                            <span class="stat-label">
                                Pending
                            </span>

                        </div>

                    </div>

                @endif


                {{-- Existing comments --}}
                @if($technicalCheck->comments)

                    <div class="mt-4">

                        <div class="fw-bold small mb-2">
                            <i class="bi bi-chat-left-text me-1"></i>
                            Technical Check Comments
                        </div>

                        <div class="alert alert-light border mb-0"
                             style="font-size: .82rem; line-height: 1.6;">
                            {!! nl2br(e($technicalCheck->comments)) !!}
                        </div>

                    </div>

                @endif

            </div>

        </div>

    @endif


    {{-- ============================================================
         NO TECHNICAL CHECK
    ============================================================= --}}

    @if(!$technicalCheck)

        <div class="tc-card mb-4">

            <div class="tc-card-body">

                <div class="empty-state">

                    <div class="empty-icon">
                        <i class="bi bi-clipboard-x"></i>
                    </div>

                    @if($isCorrectionStage)

                        <div class="empty-title">
                            Awaiting Author Resubmission
                        </div>

                        <p class="empty-text">
                            The previous technical check was returned for
                            correction. A new technical check should be started
                            after the author resubmits the corrected manuscript.
                        </p>

                    @elseif($isPaymentStage)

                        <div class="empty-title">
                            Technical Check Completed
                        </div>

                        <p class="empty-text">
                            The technical check has already been passed and this
                            manuscript is now in the payment stage.
                        </p>

                    @elseif($canStartTechnicalCheck)

                        <div class="empty-title">
                            No Technical Check Started
                        </div>

                        <p class="empty-text">
                            This manuscript is ready for technical verification.
                            Start a new technical check to generate the checklist.
                        </p>

                        @can('technical_check.perform')

                        <form method="POST"
                            action="{{ route(
                                'admin.manuscripts.technical-check.complete',
                                $technicalCheck
                            ) }}">

                            @csrf

                            <button type="submit"
                                    class="btn btn-success px-4"
                                    {{ !$readyToComplete ? 'disabled' : '' }}>

                                <i class="bi bi-check-circle-fill me-1"></i>
                                Complete & Forward to Payment
                                <i class="bi bi-arrow-right ms-1"></i>

                            </button>

                        </form>

                        @endcan

                    @else

                        <div class="empty-title">
                            Technical Check Not Available
                        </div>

                        <p class="empty-text">
                            The manuscript is currently in
                            <strong>{{ $manuscriptStatusLabel }}</strong>.
                            No active technical check is available at this stage.
                        </p>

                    @endif

                </div>

            </div>

        </div>

    @endif


    {{-- ============================================================
         CHECKLIST
    ============================================================= --}}

    @if($technicalCheck && $totalItems > 0)

        <div class="tc-card mb-4">

            <div class="tc-card-header">

                <div class="d-flex flex-column flex-md-row
                            justify-content-between align-items-start
                            align-items-md-center gap-2">

                    <h5 class="section-title">

                        <span class="section-title-icon">
                            <i class="bi bi-list-check"></i>
                        </span>

                        Technical Checklist

                    </h5>

                    @if($canEditChecklist)

                        <span class="badge bg-primary-subtle text-primary">
                            <i class="bi bi-pencil-square me-1"></i>
                            Editing Enabled
                        </span>

                    @elseif($technicalCheck->status === 'passed')

                        <span class="badge bg-success-subtle text-success">
                            <i class="bi bi-lock-fill me-1"></i>
                            Completed
                        </span>

                    @endif

                </div>

            </div>


            <div class="tc-card-body p-0">

                {{-- ====================================================
                     DESKTOP TABLE
                ===================================================== --}}

                <form method="POST"
                      action="{{ route(
                          'admin.manuscripts.technical-check.update',
                          $technicalCheck
                      ) }}">

                    @csrf
                    @method('PUT')

                    <div class="table-responsive desktop-checklist">

                        <table class="table checklist-table align-middle">

                            <thead>

                                <tr>

                                    <th style="width: 65px;">
                                        #
                                    </th>

                                    <th>
                                        Technical Requirement
                                    </th>

                                    <th style="width: 155px;">
                                        Result
                                    </th>

                                    <th style="width: 30%;">
                                        Comments
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($items as $index => $item)

                                    <tr>

                                        <td>

                                            <span class="check-number">
                                                {{ $index + 1 }}
                                            </span>

                                        </td>

                                        <td>

                                            <div class="check-name">
                                                {{ $item->check_name }}
                                            </div>

                                            @if($item->check_key)

                                                <div class="check-key">
                                                    {{ $item->check_key }}
                                                </div>

                                            @endif

                                        </td>

                                        <td>

                                            @if($canEditChecklist)

                                                <select
                                                    name="items[{{ $item->id }}][result]"
                                                    class="form-select result-select">

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

                                            @else

                                                @if($item->result === 'pass')

                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="bi bi-check-circle me-1"></i>
                                                        Pass
                                                    </span>

                                                @elseif($item->result === 'fail')

                                                    <span class="badge bg-danger-subtle text-danger">
                                                        <i class="bi bi-x-circle me-1"></i>
                                                        Fail
                                                    </span>

                                                @else

                                                    <span class="badge bg-warning-subtle text-warning-emphasis">
                                                        <i class="bi bi-clock me-1"></i>
                                                        Pending
                                                    </span>

                                                @endif

                                            @endif

                                        </td>

                                        <td>

                                            @if($canEditChecklist)

                                                <textarea
                                                    name="items[{{ $item->id }}][comments]"
                                                    class="form-control item-comment"
                                                    rows="2"
                                                    placeholder="Optional comment...">{{ old(
                                                        "items.{$item->id}.comments",
                                                        $item->comments ?? ''
                                                    ) }}</textarea>

                                            @elseif($item->comments)

                                                <div class="small text-muted">
                                                    {!! nl2br(e($item->comments)) !!}
                                                </div>

                                            @else

                                                <span class="text-muted small">
                                                    —
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- =================================================
                         MOBILE CHECKLIST
                    ================================================== --}}

                    <div class="mobile-checklist p-3">

                        @foreach($items as $index => $item)

                            <div class="mobile-check-item">

                                <div class="mobile-check-header">

                                    <span class="check-number">
                                        {{ $index + 1 }}
                                    </span>

                                    <div class="mobile-check-content">

                                        <div class="check-name">
                                            {{ $item->check_name }}
                                        </div>

                                        @if($item->check_key)

                                            <div class="check-key">
                                                {{ $item->check_key }}
                                            </div>

                                        @endif

                                    </div>

                                </div>


                                <div class="mt-3">

                                    <label class="form-label small fw-semibold">
                                        Result
                                    </label>

                                    @if($canEditChecklist)

                                        <select
                                            name="items[{{ $item->id }}][result]"
                                            class="form-select form-select-sm">

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

                                    @else

                                        @if($item->result === 'pass')

                                            <span class="badge bg-success-subtle text-success">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Pass
                                            </span>

                                        @elseif($item->result === 'fail')

                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Fail
                                            </span>

                                        @else

                                            <span class="badge bg-warning-subtle text-warning-emphasis">
                                                <i class="bi bi-clock me-1"></i>
                                                Pending
                                            </span>

                                        @endif

                                    @endif

                                </div>


                                <div class="mt-3">

                                    <label class="form-label small fw-semibold">
                                        Comments
                                    </label>

                                    @if($canEditChecklist)

                                        <textarea
                                            name="items[{{ $item->id }}][comments]"
                                            class="form-control form-control-sm"
                                            rows="3"
                                            placeholder="Optional comment...">{{ old(
                                                "items.{$item->id}.comments",
                                                $item->comments ?? ''
                                            ) }}</textarea>

                                    @elseif($item->comments)

                                        <div class="small text-muted">
                                            {!! nl2br(e($item->comments)) !!}
                                        </div>

                                    @else

                                        <span class="text-muted small">
                                            No comment
                                        </span>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>


                    {{-- Save --}}
                    @if($canEditChecklist)

                        @can('technical_check.perform')

                            <div class="p-3 border-top bg-light">

                                <div class="d-flex flex-column flex-md-row
                                            justify-content-between
                                            align-items-start
                                            align-items-md-center gap-3">

                                    <div class="small text-muted">

                                        <i class="bi bi-info-circle me-1"></i>

                                        Save the checklist before completing
                                        or returning the manuscript.

                                    </div>

                                    <button type="submit"
                                            class="btn btn-primary">

                                        <i class="bi bi-save me-1"></i>
                                        Save Checklist

                                    </button>

                                </div>

                            </div>

                        @endcan

                    @endif

                </form>

            </div>

        </div>

    @elseif($technicalCheck)

        {{-- Technical check exists but checklist is empty --}}

        <div class="tc-card mb-4">

            <div class="tc-card-body">

                <div class="empty-state">

                    <div class="empty-icon">
                        <i class="bi bi-list-check"></i>
                    </div>

                    <div class="empty-title">
                        No Checklist Items Found
                    </div>

                    <p class="empty-text">
                        This technical check exists, but no checklist items
                        are currently associated with it.
                    </p>

                </div>

            </div>

        </div>

    @endif


    {{-- ============================================================
         DECISION / ACTION AREA
    ============================================================= --}}

    @if($technicalCheck)

        {{-- ----------------------------------------------------------
             IN PROGRESS
        ----------------------------------------------------------- --}}

        @if($technicalCheck->status === 'in_progress')

            <div class="tc-card mb-4">

                <div class="tc-card-header">

                    <h5 class="section-title">

                        <span class="section-title-icon">
                            <i class="bi bi-signpost-split"></i>
                        </span>

                        Technical Check Decision

                    </h5>

                </div>

                <div class="tc-card-body">

                    <div class="row g-3">

                        {{-- Complete --}}
                        <div class="col-lg-6">

                            <div class="decision-card h-100">

                                <h6 class="text-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Complete & Pass
                                </h6>

                                <p>
                                    Complete the technical check when every
                                    checklist item has been reviewed and all
                                    items have passed.
                                </p>

                                @if($failedItems > 0)

                                    <div class="alert alert-danger tc-alert mb-3">
                                        <i class="bi bi-x-circle me-1"></i>
                                        {{ $failedItems }}
                                        item(s) have failed.
                                        The check cannot be passed.
                                    </div>

                                @elseif($pendingItems > 0)

                                    <div class="alert alert-warning tc-alert mb-3">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $pendingItems }}
                                        item(s) are still pending.
                                    </div>

                                @elseif($totalItems === 0)

                                    <div class="alert alert-warning tc-alert mb-3">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        No checklist items are available.
                                    </div>

                                @else

                                   <div class="alert alert-success tc-alert mb-3">

                                        <div class="d-flex align-items-start gap-2">

                                            <i class="bi bi-check-circle-fill mt-1"></i>

                                            <div>

                                                <strong>Technical check is ready to pass.</strong>

                                                <div class="mt-1">
                                                    All {{ $totalItems }} checklist items have passed.
                                                    Click <strong>Complete & Forward to Payment</strong>
                                                    to move this manuscript to the Payment stage.
                                                </div>

                                            </div>

                                        </div>

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
                                                class="btn btn-success"
                                                {{ !$readyToComplete ? 'disabled' : '' }}>

                                            <i class="bi bi-check2-circle me-1"></i>
                                            Complete Technical Check
                                        </button>

                                    </form>

                                @endcan

                            </div>

                        </div>


                        {{-- Return to Author --}}
                        <div class="col-lg-6">

                            <div class="decision-card return-box h-100">

                                <h6 class="text-warning-emphasis">
                                    <i class="bi bi-arrow-return-left me-1"></i>
                                    Return to Author for Correction
                                </h6>

                                <p>
                                    Use this option when the manuscript contains
                                    technical problems that must be corrected
                                    by the author before it can proceed.
                                </p>

                                @can('technical_check.return')

                                    <form method="POST"
                                          action="{{ route(
                                              'admin.manuscripts.technical-check.return',
                                              $technicalCheck
                                          ) }}">

                                        @csrf

                                        <div class="mb-3">

                                            <label class="form-label small fw-semibold">
                                                Correction Instructions
                                                <span class="text-danger">*</span>
                                            </label>

                                            <textarea
                                                name="comments"
                                                class="form-control"
                                                rows="4"
                                                required
                                                maxlength="5000"
                                                placeholder="Clearly explain what the author needs to correct...">{{ old('comments') }}</textarea>

                                            <div class="form-text">
                                                These comments will guide the
                                                author during correction.
                                            </div>

                                        </div>

                                        <button type="submit"
                                                class="btn btn-warning">

                                            <i class="bi bi-arrow-return-left me-1"></i>
                                            Return to Author

                                        </button>

                                    </form>

                                @endcan

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @endif


        {{-- ----------------------------------------------------------
             PASSED
        ----------------------------------------------------------- --}}

        @if($technicalCheck->status === 'passed')

            <div class="transition-card mb-4">

                <div class="d-flex align-items-start gap-3">

                    <div class="transition-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>

                    <div>

                        <div class="transition-title">
                            Technical Check Completed Successfully
                        </div>

                        <p class="transition-text">
                            Technical Check
                            <strong>#{{ $technicalCheck->check_number }}</strong>
                            has been passed. The manuscript's next workflow
                            stage is <strong>Payment Required</strong>.
                        </p>

                    </div>

                </div>

                <div class="transition-flow">

                    <div class="transition-node passed">
                        <i class="bi bi-check-circle-fill"></i>
                        Passed
                    </div>

                    <div class="transition-arrow">
                        <i class="bi bi-arrow-right"></i>
                    </div>

                    <div class="transition-node payment">
                        <i class="bi bi-credit-card-fill"></i>
                        Payment Required
                    </div>

                </div>

            </div>

        @endif


        {{-- ----------------------------------------------------------
             CORRECTION REQUIRED
        ----------------------------------------------------------- --}}

        @if($technicalCheck->status === 'correction_required')

            <div class="tc-card mb-4">

                <div class="tc-card-body">

                    <div class="alert alert-warning tc-alert mb-0">

                        <div class="d-flex align-items-start gap-2">

                            <i class="bi bi-arrow-return-left mt-1"></i>

                            <div>

                                <div class="fw-bold mb-1">
                                    Technical Correction Required
                                </div>

                                <div>
                                    Technical Check
                                    <strong>
                                        #{{ $technicalCheck->check_number }}
                                    </strong>
                                    was returned to the author.

                                    After the author corrects and resubmits
                                    the manuscript, the system should create
                                    <strong>
                                        Technical Check
                                        #{{ $technicalCheck->check_number + 1 }}
                                    </strong>.
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @endif

    @endif


    {{-- ============================================================
         TECHNICAL ISSUES
    ============================================================= --}}

    @if($technicalCheck && $issues->count() > 0)

        <div class="tc-card mb-4">

            <div class="tc-card-header">

                <h5 class="section-title">

                    <span class="section-title-icon">
                        <i class="bi bi-exclamation-diamond"></i>
                    </span>

                    Technical Issues

                    <span class="badge bg-light text-dark border ms-1">
                        {{ $issues->count() }}
                    </span>

                </h5>

            </div>

            <div class="tc-card-body">

                @foreach($issues as $issue)

                    <div class="issue-item">

                        <div class="d-flex flex-wrap
                                    justify-content-between
                                    align-items-start gap-2">

                            <div class="issue-title">

                                {{ ucwords(str_replace(
                                    '_',
                                    ' ',
                                    $issue->category ?? 'Other'
                                )) }}

                            </div>


                            <div class="d-flex flex-wrap gap-1">

                                @if($issue->severity)

                                    @php
                                        $severityClass = match($issue->severity) {
                                            'critical' => 'bg-danger-subtle text-danger',
                                            'major' => 'bg-warning-subtle text-warning-emphasis',
                                            default => 'bg-secondary-subtle text-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $severityClass }}">
                                        {{ ucfirst($issue->severity) }}
                                    </span>

                                @endif


                                @if($issue->status)

                                    @php
                                        $issueStatusClass = match($issue->status) {
                                            'resolved' => 'bg-success-subtle text-success',
                                            'not_applicable' => 'bg-secondary-subtle text-secondary',
                                            default => 'bg-danger-subtle text-danger',
                                        };
                                    @endphp

                                    <span class="badge {{ $issueStatusClass }}">
                                        {{ ucwords(str_replace(
                                            '_',
                                            ' ',
                                            $issue->status
                                        )) }}
                                    </span>

                                @endif

                            </div>

                        </div>


                        @if($issue->description)

                            <div class="issue-description">

                                <strong>Description:</strong>

                                {!! nl2br(e($issue->description)) !!}

                            </div>

                        @endif


                        @if($issue->required_action)

                            <div class="issue-description">

                                <strong>Required Action:</strong>

                                {!! nl2br(e($issue->required_action)) !!}

                            </div>

                        @endif

                    </div>

                @endforeach

            </div>

        </div>

    @endif


    {{-- ============================================================
         WORKFLOW EXPLANATION
    ============================================================= --}}

    <div class="tc-card mb-4">

        <div class="tc-card-header">

            <h5 class="section-title">

                <span class="section-title-icon">
                    <i class="bi bi-info-circle"></i>
                </span>

                Technical Review Workflow

            </h5>

        </div>

        <div class="tc-card-body">

            <div class="row g-3">

                <div class="col-md-6 col-xl-3">

                    <div class="p-3 bg-light rounded-3 h-100">

                        <div class="fw-bold small mb-2">
                            <i class="bi bi-1-circle me-1"></i>
                            In Progress
                        </div>

                        <div class="small text-muted"
                             style="line-height:1.6;">

                            Review all checklist items and mark each item
                            as Pass, Fail, or Pending.

                        </div>

                    </div>

                </div>


                <div class="col-md-6 col-xl-3">

                    <div class="p-3 bg-light rounded-3 h-100">

                        <div class="fw-bold small mb-2">
                            <i class="bi bi-2-circle me-1"></i>
                            Correction
                        </div>

                        <div class="small text-muted"
                             style="line-height:1.6;">

                            If technical problems exist, return the manuscript
                            to the author with clear correction instructions.

                        </div>

                    </div>

                </div>


                <div class="col-md-6 col-xl-3">

                    <div class="p-3 bg-light rounded-3 h-100">

                        <div class="fw-bold small mb-2">
                            <i class="bi bi-3-circle me-1"></i>
                            Passed
                        </div>

                        <div class="small text-muted"
                             style="line-height:1.6;">

                            When all checklist items pass, complete the
                            technical check.

                        </div>

                    </div>

                </div>


                <div class="col-md-6 col-xl-3">

                    <div class="p-3 bg-light rounded-3 h-100">

                        <div class="fw-bold small mb-2">
                            <i class="bi bi-4-circle me-1"></i>
                            Payment
                        </div>

                        <div class="small text-muted"
                             style="line-height:1.6;">

                            After passing technical review, the manuscript
                            moves to Payment Required.

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         FOOTER ACTIONS
    ============================================================= --}}

    <div class="d-flex flex-column flex-md-row
                justify-content-between align-items-stretch
                align-items-md-center gap-2 pb-4">

        <a href="{{ route('admin.manuscripts.technical-review.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Technical Review

        </a>

        <a href="{{ route('admin.manuscripts.show', $manuscript) }}"
           class="btn btn-primary">

            <i class="bi bi-file-earmark-text me-1"></i>
            View Manuscript Details

        </a>

    </div>

</div>

@endsection