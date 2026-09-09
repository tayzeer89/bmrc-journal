@extends('author.layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- ================================================================
         MANUSCRIPT DETAILS
    ================================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="bi bi-file-earmark-text me-2"></i>
                Manuscript Details
            </h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered align-middle mb-0">

                <tr>
                    <th width="30%">Manuscript ID</th>
                    <td>
                        {{ $manuscript->manuscript_id ?? 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <th>Title</th>
                    <td>
                        {{ $manuscript->title ?? 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <th>Journal</th>
                    <td>
                        {{ $manuscript->journal->name ?? 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <th>Article Type</th>
                    <td>
                        {{ $manuscript->articleType->name ?? 'N/A' }}
                    </td>
                </tr>

                {{-- STATUS --}}
                <tr>
                    <th>Status</th>

                    <td>

                        @if($manuscript->status === 'technical_correction')

                            <span class="badge bg-danger">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Technical Correction Required
                            </span>

                        @elseif($manuscript->status === 'technical_check')

                            <span class="badge bg-primary">
                                <i class="bi bi-clipboard-check me-1"></i>
                                Technical Review
                            </span>

                        @elseif($manuscript->status === 'payment_required')

                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-credit-card me-1"></i>
                                Payment Required
                            </span>

                        @elseif($manuscript->status === 'payment_verified')

                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>
                                Payment Verified
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                {{
                                    ucfirst(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $manuscript->status ?? 'Unknown'
                                        )
                                    )
                                }}
                            </span>

                        @endif

                    </td>
                </tr>

                {{-- COMPLETION --}}
                <tr>
                    <th>Completion Progress</th>

                    <td>

                        @php
                            $completion = $manuscript->completion_percentage ?? 0;
                        @endphp

                        <div class="progress" style="height: 24px;">

                            <div
                                class="progress-bar"
                                role="progressbar"
                                style="width: {{ $completion }}%;"
                                aria-valuenow="{{ $completion }}"
                                aria-valuemin="0"
                                aria-valuemax="100"
                            >
                                {{ $completion }}%
                            </div>

                        </div>

                    </td>
                </tr>

            </table>

        </div>

    </div>


    {{-- ================================================================
         TECHNICAL CORRECTION SECTION

         IMPORTANT:
         Only show this section when the MANUSCRIPT itself is currently
         in technical_correction status.

         Do NOT check old technicalCheck status/result here.
    ================================================================= --}}

    @if($showTechnicalCorrection)

        @php

            /*
            |--------------------------------------------------------------------------
            | Technical Check Safety
            |--------------------------------------------------------------------------
            */

            $currentTechnicalCheck = $technicalCheck;


            /*
            |--------------------------------------------------------------------------
            | Technical Issues
            |--------------------------------------------------------------------------
            */

            $technicalIssues = $currentTechnicalCheck
                ? ($currentTechnicalCheck->issues ?? collect())
                : collect();


            $openTechnicalIssues = $technicalIssues
                ->where('status', 'open')
                ->values();


            /*
            |--------------------------------------------------------------------------
            | Failed Checklist Items
            |--------------------------------------------------------------------------
            */

            $failedTechnicalItems = $currentTechnicalCheck
                ? (
                    $currentTechnicalCheck->items
                        ? $currentTechnicalCheck->items
                            ->where('result', 'fail')
                            ->sortBy('sort_order')
                            ->values()
                        : collect()
                )
                : collect();

        @endphp


        {{-- ============================================================
             MAIN TECHNICAL CORRECTION CARD
        ============================================================= --}}

        <div class="card border-danger shadow-sm mb-4">


            {{-- HEADER --}}
            <div class="card-header bg-danger text-white">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Technical Corrections Required
                    </h5>

                    @if($currentTechnicalCheck)

                        <span class="badge bg-light text-danger">

                            Technical Check
                            #{{ $currentTechnicalCheck->check_number }}

                        </span>

                    @endif

                </div>

            </div>


            <div class="card-body">


                {{-- ====================================================
                     IMPORTANT NOTICE
                ===================================================== --}}

                <div class="alert alert-danger">

                    <div class="d-flex">

                        <div class="me-3">
                            <i class="bi bi-exclamation-octagon fs-3"></i>
                        </div>

                        <div>

                            <h6 class="fw-bold mb-1">
                                Technical Correction Required
                            </h6>

                            <div>

                                Your manuscript has been returned for
                                technical correction.

                                Please address all technical issues
                                and failed checklist items below.

                                After making the corrections, upload the
                                corrected files and submit the manuscript
                                for another technical review.

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                     TECHNICAL CHECK RESULT
                ===================================================== --}}

                @if($currentTechnicalCheck)

                    <div class="card border-danger mb-4">

                        <div class="card-header bg-danger-subtle">

                            <strong>
                                <i class="bi bi-clipboard-x me-2"></i>
                                Technical Check Result
                            </strong>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">


                                {{-- RESULT --}}
                                <div class="col-md-4">

                                    <div class="small text-muted mb-1">
                                        Result
                                    </div>


                                    @if(
                                        $currentTechnicalCheck->overall_result
                                        === 'correction_required'
                                    )

                                        <span class="badge bg-danger fs-6 px-3 py-2">

                                            <i class="bi bi-exclamation-triangle me-1"></i>

                                            Correction Required

                                        </span>


                                    @elseif(
                                        $currentTechnicalCheck->overall_result
                                        === 'passed'
                                    )

                                        <span class="badge bg-success fs-6 px-3 py-2">

                                            <i class="bi bi-check-circle me-1"></i>

                                            Passed

                                        </span>


                                    @elseif(
                                        $currentTechnicalCheck->overall_result
                                        === 'failed'
                                    )

                                        <span class="badge bg-danger fs-6 px-3 py-2">

                                            <i class="bi bi-x-circle me-1"></i>

                                            Failed

                                        </span>


                                    @else

                                        <span class="badge bg-secondary fs-6 px-3 py-2">

                                            {{
                                                ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $currentTechnicalCheck->overall_result
                                                        ?? 'Pending'
                                                    )
                                                )
                                            }}

                                        </span>

                                    @endif

                                </div>


                                {{-- CHECK STATUS --}}
                                <div class="col-md-4">

                                    <div class="small text-muted mb-1">
                                        Check Status
                                    </div>


                                    @if(
                                        $currentTechnicalCheck->status
                                        === 'correction_required'
                                    )

                                        <span class="badge bg-danger">
                                            Correction Required
                                        </span>


                                    @elseif(
                                        $currentTechnicalCheck->status
                                        === 'passed'
                                    )

                                        <span class="badge bg-success">
                                            Passed
                                        </span>


                                    @elseif(
                                        $currentTechnicalCheck->status
                                        === 'failed'
                                    )

                                        <span class="badge bg-danger">
                                            Failed
                                        </span>


                                    @elseif(
                                        $currentTechnicalCheck->status
                                        === 'in_progress'
                                    )

                                        <span class="badge bg-warning text-dark">
                                            In Progress
                                        </span>


                                    @else

                                        <span class="badge bg-secondary">

                                            {{
                                                ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $currentTechnicalCheck->status
                                                        ?? 'Pending'
                                                    )
                                                )
                                            }}

                                        </span>

                                    @endif

                                </div>


                                {{-- CHECK NUMBER --}}
                                <div class="col-md-4">

                                    <div class="small text-muted mb-1">
                                        Check Number
                                    </div>

                                    <div class="fw-semibold">

                                        #{{ $currentTechnicalCheck->check_number }}

                                    </div>

                                </div>


                                {{-- CHECKED DATE --}}
                                @if($currentTechnicalCheck->completed_at)

                                    <div class="col-md-4">

                                        <div class="small text-muted mb-1">
                                            Checked On
                                        </div>

                                        <div class="fw-semibold">

                                            {{
                                                $currentTechnicalCheck
                                                    ->completed_at
                                                    ->format('d M Y, h:i A')
                                            }}

                                        </div>

                                    </div>

                                @endif


                                {{-- CHECKED BY --}}
                                @if($currentTechnicalCheck->completedBy)

                                    <div class="col-md-4">

                                        <div class="small text-muted mb-1">
                                            Checked By
                                        </div>

                                        <div class="fw-semibold">

                                            {{
                                                $currentTechnicalCheck
                                                    ->completedBy
                                                    ->name
                                            }}

                                        </div>

                                    </div>

                                @endif

                            </div>


                            {{-- OVERALL COMMENT --}}
                            @if($currentTechnicalCheck->comments)

                                <hr>

                                <div>

                                    <div class="small text-muted mb-1">

                                        Overall Technical Reviewer Comment

                                    </div>

                                    <div class="p-3 bg-light border rounded">

                                        {!! nl2br(e($currentTechnicalCheck->comments)) !!}

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                @endif


                {{-- ====================================================
                     TECHNICAL ISSUES
                ===================================================== --}}

                @if($technicalIssues->count() > 0)

                    <div class="mb-4">


                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

                            <h5 class="fw-bold mb-0">

                                <i class="bi bi-exclamation-octagon me-2 text-danger"></i>

                                Technical Issues

                            </h5>


                            <span class="badge bg-danger">

                                {{ $openTechnicalIssues->count() }}

                                Open

                                {{
                                    $openTechnicalIssues->count() == 1
                                        ? 'Issue'
                                        : 'Issues'
                                }}

                            </span>

                        </div>


                        @foreach($technicalIssues as $index => $issue)

                            <div class="card border-danger mb-3">


                                <div class="card-header bg-danger-subtle">

                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">


                                        <strong>

                                            <i class="bi bi-exclamation-circle me-1"></i>

                                            Technical Issue {{ $index + 1 }}

                                        </strong>


                                        <div class="d-flex gap-2 flex-wrap">


                                            {{-- SEVERITY --}}
                                            @if($issue->severity === 'critical')

                                                <span class="badge bg-danger">
                                                    Critical
                                                </span>

                                            @elseif($issue->severity === 'major')

                                                <span class="badge bg-warning text-dark">
                                                    Major
                                                </span>

                                            @else

                                                <span class="badge bg-info text-dark">
                                                    Minor
                                                </span>

                                            @endif


                                            {{-- STATUS --}}
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

                                                    {{
                                                        ucfirst(
                                                            $issue->status
                                                            ?? 'Unknown'
                                                        )
                                                    }}

                                                </span>

                                            @endif

                                        </div>

                                    </div>

                                </div>


                                <div class="card-body">


                                    {{-- CATEGORY --}}
                                    <div class="mb-3">

                                        <div class="small text-muted mb-1">
                                            Category
                                        </div>

                                        <div class="fw-semibold">

                                            {{
                                                ucwords(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $issue->category
                                                        ?? 'Technical'
                                                    )
                                                )
                                            }}

                                        </div>

                                    </div>


                                    {{-- RELATED CHECKLIST --}}
                                    @if($issue->technicalCheckItem)

                                        <div class="mb-3">

                                            <div class="small text-muted mb-1">
                                                Related Checklist Item
                                            </div>

                                            <div class="fw-semibold">

                                                <i class="bi bi-list-check me-1"></i>

                                                {{
                                                    $issue
                                                        ->technicalCheckItem
                                                        ->check_name
                                                }}

                                            </div>

                                        </div>

                                    @endif


                                    {{-- RELATED FILE --}}
                                    @if($issue->manuscriptFile)

                                        <div class="mb-3">

                                            <div class="small text-muted mb-1">
                                                Related Manuscript File
                                            </div>

                                            <div>

                                                <i class="bi bi-file-earmark-text me-1"></i>

                                                {{
                                                    $issue
                                                        ->manuscriptFile
                                                        ->original_name
                                                }}

                                            </div>

                                        </div>

                                    @endif


                                    {{-- PROBLEM --}}
                                    <div class="alert alert-danger mb-3">

                                        <div class="fw-bold mb-2">

                                            <i class="bi bi-x-circle me-1"></i>

                                            Problem Identified

                                        </div>

                                        <div>

                                            {!! nl2br(e($issue->description)) !!}

                                        </div>

                                    </div>


                                    {{-- REQUIRED ACTION --}}
                                    @if($issue->required_action)

                                        <div class="alert alert-warning mb-0">

                                            <div class="fw-bold mb-2">

                                                <i class="bi bi-tools me-1"></i>

                                                Required Correction / Action

                                            </div>

                                            <div>

                                                {!! nl2br(e($issue->required_action)) !!}

                                            </div>

                                        </div>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>


                @else

                    <div class="alert alert-warning">

                        <i class="bi bi-info-circle me-2"></i>

                        No specific technical issues were recorded.

                        Please review the failed checklist items below.

                    </div>

                @endif


                {{-- ====================================================
                     FAILED CHECKLIST ITEMS
                ===================================================== --}}

                @if($failedTechnicalItems->count() > 0)

                    <div class="mb-4">

                        <h5 class="fw-bold mb-3">

                            <i class="bi bi-list-check me-2 text-warning"></i>

                            Failed Technical Checklist Items

                        </h5>


                        @foreach($failedTechnicalItems as $index => $item)

                            <div class="card border-warning mb-3">


                                <div class="card-header bg-warning-subtle">

                                    <strong>

                                        {{ $index + 1 }}.

                                        {{ $item->check_name }}

                                    </strong>

                                </div>


                                <div class="card-body">


                                    {{-- IMPORTANT:
                                         Database field is "comment"
                                         NOT "comments"
                                    --}}

                                    @if($item->comment)

                                        <div class="alert alert-warning mb-0">

                                            <div class="fw-bold mb-2">

                                                <i class="bi bi-chat-left-text me-1"></i>

                                                Reviewer Comment

                                            </div>

                                            <div>

                                                {!! nl2br(e($item->comment)) !!}

                                            </div>

                                        </div>


                                    @else

                                        <div class="text-muted">

                                            <i class="bi bi-info-circle me-1"></i>

                                            Correction is required for this
                                            checklist item.

                                        </div>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                @endif


                {{-- ====================================================
                     AUTHOR CORRECTION FORM
                ===================================================== --}}

                <div class="card border-primary mb-4">


                    <div class="card-header bg-primary text-white">

                        <h5 class="mb-0">

                            <i class="bi bi-pencil-square me-2"></i>

                            Submit Technical Corrections

                        </h5>

                    </div>


                    <div class="card-body">


                        {{-- INFORMATION --}}
                        <div class="alert alert-info">

                            <i class="bi bi-info-circle me-2"></i>

                            Please address all technical issues before
                            submitting your manuscript for re-checking.

                            You may upload corrected versions of the
                            affected files below.

                        </div>


                        {{-- =================================================
                             FORM
                        ================================================== --}}

                        <form
                            method="POST"
                            action="{{
                                route(
                                    'author.manuscripts.technical-correction.submit',
                                    $manuscript
                                )
                            }}"
                            enctype="multipart/form-data"
                        >

                            @csrf


                            {{-- =================================================
                                 AUTHOR RESPONSE
                            ================================================== --}}

                            <div class="mb-4">

                                <label
                                    for="response"
                                    class="form-label fw-bold"
                                >

                                    <i class="bi bi-reply me-1"></i>

                                    Author's Response to Technical Corrections

                                    <span class="text-danger">*</span>

                                </label>


                                <textarea
                                    name="response"
                                    id="response"
                                    rows="6"
                                    class="form-control @error('response') is-invalid @enderror"
                                    placeholder="Please explain how you have addressed the technical corrections..."
                                    required
                                >{{ old('response') }}</textarea>


                                @error('response')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror


                                <div class="form-text">

                                    Explain the corrections you have made
                                    and identify any files that were replaced.

                                </div>

                            </div>


                            {{-- =================================================
                                 CORRECTED FILES
                            ================================================== --}}

                            <div class="mb-4">

                                <h6 class="fw-bold mb-3">

                                    <i class="bi bi-upload me-2"></i>

                                    Upload Corrected Files

                                </h6>


                                <div class="alert alert-info">

                                    <i class="bi bi-info-circle me-2"></i>

                                    Upload a new version only for files that
                                    required correction.

                                    The original submitted files will remain
                                    preserved in the manuscript history.

                                </div>


                                @if($manuscript->files->count())


                                    <div class="table-responsive">

                                        <table class="table table-bordered align-middle">


                                            <thead class="table-light">

                                                <tr>

                                                    <th>
                                                        File
                                                    </th>

                                                    <th>
                                                        Type
                                                    </th>

                                                    <th>
                                                        Current Version
                                                    </th>

                                                    <th>
                                                        Status
                                                    </th>

                                                    <th style="min-width: 300px;">
                                                        Upload Corrected Version
                                                    </th>

                                                </tr>

                                            </thead>


                                            <tbody>


                                                @foreach(
                                                    $manuscript->files
                                                        ->where('status', 'active')
                                                        ->sortByDesc('id')
                                                    as $file
                                                )

                                                    <tr>


                                                        {{-- FILE --}}
                                                        <td>

                                                            <i class="bi bi-file-earmark-text me-1"></i>

                                                            {{ $file->original_name }}

                                                        </td>


                                                        {{-- TYPE --}}
                                                        <td>

                                                            {{ $file->file_type }}

                                                        </td>


                                                        {{-- VERSION --}}
                                                        <td>

                                                            <span class="badge bg-secondary">

                                                                V{{ $file->version_number }}

                                                            </span>

                                                        </td>


                                                        {{-- STATUS --}}
                                                        <td>

                                                            <span class="badge bg-success">

                                                                Active

                                                            </span>

                                                        </td>


                                                        {{-- UPLOAD --}}
                                                        <td>

                                                            <input
                                                                type="file"
                                                                name="files[{{ $file->id }}]"
                                                                class="form-control @error('files.' . $file->id) is-invalid @enderror"
                                                                accept=".pdf,.doc,.docx,.rtf,.txt"
                                                            >


                                                            @error('files.' . $file->id)

                                                                <div class="invalid-feedback">

                                                                    {{ $message }}

                                                                </div>

                                                            @enderror


                                                            <small class="text-muted">

                                                                Leave empty if this
                                                                file does not require
                                                                correction.

                                                            </small>

                                                        </td>

                                                    </tr>

                                                @endforeach

                                            </tbody>

                                        </table>

                                    </div>


                                @else

                                    <div class="alert alert-warning">

                                        <i class="bi bi-exclamation-triangle me-2"></i>

                                        No active manuscript files were found.

                                    </div>

                                @endif

                            </div>


                            {{-- =================================================
                                 CONFIRMATION
                            ================================================== --}}

                            <div class="card border-primary mb-4">

                                <div class="card-body">

                                    <div class="form-check">

                                        <input
                                            type="checkbox"
                                            name="confirmation"
                                            value="1"
                                            id="confirmation"
                                            class="form-check-input @error('confirmation') is-invalid @enderror"
                                            {{ old('confirmation') ? 'checked' : '' }}
                                            required
                                        >


                                        <label
                                            class="form-check-label"
                                            for="confirmation"
                                        >

                                            I confirm that I have addressed
                                            all technical corrections and
                                            that the uploaded files are the
                                            corrected versions of my
                                            manuscript files.

                                        </label>


                                        @error('confirmation')

                                            <div class="invalid-feedback d-block">

                                                {{ $message }}

                                            </div>

                                        @enderror

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                 SUBMIT BUTTON
                            ================================================== --}}

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">


                                <div class="text-muted small">

                                    <i class="bi bi-arrow-repeat me-1"></i>

                                    After submission, the manuscript will be
                                    sent back for technical re-checking.

                                </div>


                                <button
                                    type="submit"
                                    class="btn btn-danger btn-lg"
                                    onclick="return confirm(
                                        'Are you sure you want to submit your technical corrections for re-checking?'
                                    )"
                                >

                                    <i class="bi bi-send me-2"></i>

                                    Submit Technical Corrections for Re-Checking

                                </button>


                            </div>


                        </form>

                    </div>

                </div>


            </div>

        </div>

    @endif


    {{-- ================================================================
         AUTHORS
    ================================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="bi bi-people me-2"></i>

                Authors

            </h5>

        </div>


        <div class="card-body">

            @if($manuscript->authors->count())

                <div class="table-responsive">

                    <table class="table table-sm table-bordered align-middle mb-0">


                        <thead class="table-light">

                            <tr>

                                <th>
                                    Author
                                </th>

                                <th>
                                    Institution
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($manuscript->authors as $author)

                                <tr>

                                    <td>
                                        {{ $author->full_name }}
                                    </td>

                                    <td>
                                        {{ $author->institution }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else

                <div class="text-muted">

                    No authors found.

                </div>

            @endif

        </div>

    </div>


    {{-- ================================================================
         UPLOADED FILES
    ================================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="bi bi-paperclip me-2"></i>

                Uploaded Files

            </h5>

        </div>


        <div class="card-body">

            @if($manuscript->files->count())

                <div class="table-responsive">

                    <table class="table table-bordered align-middle mb-0">


                        <thead class="table-light">

                            <tr>

                                <th style="width: 30%;">
                                    File Type
                                </th>

                                <th>
                                    File
                                </th>

                                <th style="width: 120px;">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($manuscript->files as $file)

                                <tr>


                                    <td>

                                        {{ $file->file_type }}

                                    </td>


                                    <td>

                                        @if($file->original_name)

                                            {{ $file->original_name }}

                                        @elseif(isset($file->file_name))

                                            {{ $file->file_name }}

                                        @else

                                            {{ basename($file->file_path) }}

                                        @endif

                                    </td>


                                    <td>

                                        <a
                                            href="{{ Storage::url($file->file_path) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-primary"
                                        >

                                            <i class="bi bi-eye me-1"></i>

                                            View

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else

                <div class="text-muted">

                    No files uploaded.

                </div>

            @endif

        </div>

    </div>


    {{-- ================================================================
         BACK BUTTON
    ================================================================= --}}

    <a
        href="{{ route('author.manuscripts.index') }}"
        class="btn btn-secondary"
    >

        <i class="bi bi-arrow-left me-1"></i>

        Back to My Manuscripts

    </a>


</div>

@endsection
