@extends('admin.layouts.app')

@section('title', 'Editorial Assessment')

@section('content')

<div class="container-fluid py-4">

    @php
        $technicalCheck = $manuscript->latestTechnicalCheck ?? null;
        $payment        = $manuscript->latestPayment ?? null;
        $similarity     = $manuscript->latestSimilarityCheck ?? null;
        $assignment     = $manuscript->currentEditorAssignment ?? null;
        $assessment     = $manuscript->latestEditorialAssessment ?? null;

        $keywords = [];

        if (!empty($manuscript->keywords)) {
            $keywords = is_array($manuscript->keywords)
                ? $manuscript->keywords
                : array_filter(
                    array_map(
                        'trim',
                        explode(',', (string) $manuscript->keywords)
                    )
                );
        }
    @endphp


    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

        <div>

            <h4 class="mb-1">
                <i class="bi bi-clipboard-check me-1"></i>
                Editorial Assessment
            </h4>

            <div class="text-muted">

                Manuscript:
                <strong>
                    {{ $manuscript->manuscript_id }}
                </strong>

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('handling-editor.assignments.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-inbox"></i>
                My Assignments
            </a>

            <a
                href="{{ route('handling-editor.assessment.index') }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-arrow-left"></i>
                Assessment List
            </a>

        </div>

    </div>


    {{-- =========================================================
         MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


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

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Manuscript ID
                    </div>

                    <div class="fw-semibold">
                        {{ $manuscript->manuscript_id }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Journal
                    </div>

                    <div class="fw-semibold">
                        {{ $manuscript->journal->name ?? 'N/A' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Article Type
                    </div>

                    <div class="fw-semibold">
                        {{ $manuscript->articleType->name ?? 'N/A' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Current Status
                    </div>

                    <span class="badge bg-info text-dark">

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


                <div class="col-12">

                    <div class="text-muted small">
                        Article Title
                    </div>

                    <div class="fw-semibold fs-5">
                        {{ $manuscript->title }}
                    </div>

                </div>


                @if(count($keywords))

                    <div class="col-12">

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

                            <span class="badge bg-light text-dark border me-1 mb-1">
                                {{ $keywordText }}
                            </span>

                        @endforeach

                    </div>

                @endif


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
         2. AUTHORS
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <strong>
                <i class="bi bi-people me-1"></i>
                2. Authors
            </strong>

            <span class="badge bg-secondary">
                {{ $manuscript->authors->count() }}
            </span>

        </div>


        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th width="50">#</th>
                        <th>Author</th>
                        <th>Email</th>
                        <th>Institution</th>
                        <th>Department</th>
                        <th>ORCID</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($manuscript->authors as $author)

                        @php

                            $authorName = trim(
                                implode(
                                    ' ',
                                    array_filter([
                                        $author->title ?? null,
                                        $author->first_name ?? null,
                                        $author->middle_name ?? null,
                                        $author->last_name ?? null,
                                    ])
                                )
                            );

                            $institution = $author->institution ?? null;

                            if (is_array($institution)) {
                                $institution = implode(', ', $institution);
                            }

                        @endphp


                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>


                            <td>

                                <strong>
                                    {{ $authorName ?: 'N/A' }}
                                </strong>

                                @if(
                                    ($author->is_corresponding ?? false)
                                    || ($author->corresponding_author ?? false)
                                )

                                    <div class="mt-1">

                                        <span class="badge bg-primary">
                                            Corresponding Author
                                        </span>

                                    </div>

                                @endif

                            </td>


                            <td>
                                {{ $author->email ?? 'N/A' }}
                            </td>


                            <td>
                                {{ $institution ?: 'N/A' }}
                            </td>


                            <td>
                                {{ $author->department ?? 'N/A' }}
                            </td>


                            <td>

                                @if(!empty($author->orcid))

                                    {{ $author->orcid }}

                                @else

                                    <span class="text-muted">
                                        N/A
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >
                                No author information found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>



    {{-- =========================================================
         3. AUTHOR SUBMITTED FILES
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <strong>
                <i class="bi bi-paperclip me-1"></i>
                3. Author Submitted Files
            </strong>

            <span class="badge bg-secondary">
                {{ $manuscript->files->count() }}
            </span>

        </div>


        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th width="50">#</th>
                        <th>Document Type</th>
                        <th>File Name</th>
                        <th width="180">Action</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($manuscript->files as $file)

                        @php

                            $fileType =
                                $file->file_type
                                ?? $file->type
                                ?? $file->document_type
                                ?? 'Document';

                            $fileName =
                                $file->original_name
                                ?? $file->original_filename
                                ?? $file->file_name
                                ?? $file->filename
                                ?? basename(
                                    $file->file_path
                                    ?? $file->path
                                    ?? 'File'
                                );

                            $filePath =
                                $file->file_path
                                ?? $file->path
                                ?? $file->storage_path
                                ?? null;

                        @endphp


                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>


                            <td>

                                <span class="badge bg-light text-dark border">
                                    {{ ucwords(str_replace('_', ' ', $fileType)) }}
                                </span>

                            </td>


                            <td>
                                {{ $fileName }}
                            </td>


                            <td>

                                @if($filePath)

                                    <div class="btn-group btn-group-sm">

                                        <a
                                            href="{{ asset(
                                                'storage/' .
                                                ltrim($filePath, '/')
                                            ) }}"
                                            target="_blank"
                                            class="btn btn-outline-primary"
                                        >
                                            <i class="bi bi-eye"></i>
                                            View
                                        </a>


                                        <a
                                            href="{{ asset(
                                                'storage/' .
                                                ltrim($filePath, '/')
                                            ) }}"
                                            download
                                            class="btn btn-outline-success"
                                        >
                                            <i class="bi bi-download"></i>
                                        </a>

                                    </div>

                                @else

                                    <span class="text-muted">
                                        File unavailable
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="text-center text-muted py-4"
                            >
                                No submitted files found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>



    {{-- =========================================================
         4. PRE-EDITORIAL VERIFICATION
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                <i class="bi bi-shield-check me-1"></i>
                4. Pre-Editorial Verification
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- Technical Check --}}
                <div class="col-lg-4">

                    <div class="border rounded p-3 h-100">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <strong>
                                Technical Check
                            </strong>

                            @if($technicalCheck)

                                <span class="badge bg-success">
                                    Available
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    N/A
                                </span>

                            @endif

                        </div>


                        @if($technicalCheck)

                            <div class="small">

                                <div class="mb-2">

                                    <span class="text-muted">
                                        Status:
                                    </span>

                                    <strong>
                                        {{
                                            ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $technicalCheck->status
                                                        ?? 'Completed'
                                                )
                                            )
                                        }}
                                    </strong>

                                </div>


                                @if(!empty($technicalCheck->comments))

                                    <div>

                                        <span class="text-muted">
                                            Comments:
                                        </span>

                                        <div>
                                            {{ $technicalCheck->comments }}
                                        </div>

                                    </div>

                                @endif

                            </div>

                        @else

                            <span class="text-muted small">
                                No technical check record found.
                            </span>

                        @endif

                    </div>

                </div>



                {{-- Payment --}}
                <div class="col-lg-4">

                    <div class="border rounded p-3 h-100">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <strong>
                                Payment
                            </strong>

                            @if($payment)

                                <span class="badge bg-success">
                                    Available
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    N/A
                                </span>

                            @endif

                        </div>


                        @if($payment)

                            <div class="small">

                                <div class="mb-2">

                                    <span class="text-muted">
                                        Status:
                                    </span>

                                    <strong>
                                        {{
                                            ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $payment->status ?? 'N/A'
                                                )
                                            )
                                        }}
                                    </strong>

                                </div>


                                @if(isset($payment->amount))

                                    <div>

                                        <span class="text-muted">
                                            Amount:
                                        </span>

                                        <strong>
                                            {{ $payment->amount }}
                                        </strong>

                                    </div>

                                @endif

                            </div>

                        @else

                            <span class="text-muted small">
                                No payment information found.
                            </span>

                        @endif

                    </div>

                </div>



                {{-- Similarity --}}
                <div class="col-lg-4">

                    <div class="border rounded p-3 h-100">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <strong>
                                Similarity Check
                            </strong>

                            @if($similarity)

                                <span class="badge bg-success">
                                    Available
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    N/A
                                </span>

                            @endif

                        </div>


                        @if($similarity)

                            <div class="small">

                                @php
                                    $similarityPercentage =
                                        $similarity->similarity_percentage
                                        ?? $similarity->percentage
                                        ?? $similarity->similarity_score
                                        ?? null;
                                @endphp


                                @if($similarityPercentage !== null)

                                    <div class="mb-2">

                                        <span class="text-muted">
                                            Similarity:
                                        </span>

                                        <strong>
                                            {{ $similarityPercentage }}%
                                        </strong>

                                    </div>

                                @endif


                                <div>

                                    <span class="text-muted">
                                        Status:
                                    </span>

                                    <strong>
                                        {{
                                            ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $similarity->status ?? 'Completed'
                                                )
                                            )
                                        }}
                                    </strong>

                                </div>

                            </div>

                        @else

                            <span class="text-muted small">
                                No similarity report found.
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
         5. HANDLING EDITOR ASSIGNMENT
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                <i class="bi bi-person-check me-1"></i>
                5. Handling Editor Assignment
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Handling Editor
                    </div>

                    <div class="fw-semibold">
                        {{ $manuscript->handlingEditor->name ?? 'N/A' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Assignment Status
                    </div>

                    <div>

                        @if($assignment)

                            <span class="badge bg-success">

                                {{
                                    ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $assignment->status
                                        )
                                    )
                                }}

                            </span>

                        @else

                            <span class="text-muted">
                                N/A
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Assigned Date
                    </div>

                    <div class="fw-semibold">

                        {{
                            $assignment?->assigned_at
                                ?->format('d M Y h:i A')
                            ?? 'N/A'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Assessment Deadline
                    </div>

                    <div class="fw-semibold">

                        {{
                            $assignment?->due_date
                                ?->format('d M Y')
                            ?? 'N/A'
                        }}

                    </div>

                </div>


                @if(!empty($assignment?->assignment_note))

                    <div class="col-12">

                        <div class="text-muted small">
                            Assignment Instructions from Editor-in-Chief
                        </div>

                        <div class="border rounded bg-light p-3 mt-2">
                            {{ $assignment->assignment_note }}
                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>



    {{-- =========================================================
         6. EDITORIAL ASSESSMENT FORM
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-success text-white">

            <strong>
                <i class="bi bi-clipboard-data me-1"></i>
                6. Scientific & Editorial Assessment
            </strong>

        </div>


        <div class="card-body">

            @if(Route::has('handling-editor.assessment.store'))

                <form
                    method="POST"
                    action="{{ route(
                        'handling-editor.assessment.store',
                        $manuscript->id
                    ) }}"
                >

                    @csrf

            @endif


            {{-- Scope --}}
            <div class="mb-4">

                <label class="form-label fw-semibold">
                    1. Scope / Relevance
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="scope_status"
                    class="form-select"
                    required
                >

                    <option value="">
                        -- Select --
                    </option>

                    <option
                        value="within_scope"
                        @selected(
                            old(
                                'scope_status',
                                $assessment->scope_status ?? ''
                            ) === 'within_scope'
                        )
                    >
                        Within Scope
                    </option>

                    <option
                        value="partially_within_scope"
                        @selected(
                            old(
                                'scope_status',
                                $assessment->scope_status ?? ''
                            ) === 'partially_within_scope'
                        )
                    >
                        Partially Within Scope
                    </option>

                    <option
                        value="out_of_scope"
                        @selected(
                            old(
                                'scope_status',
                                $assessment->scope_status ?? ''
                            ) === 'out_of_scope'
                        )
                    >
                        Out of Scope
                    </option>

                </select>

            </div>



            {{-- Scientific Quality --}}
            <div class="mb-4">

                <label class="form-label fw-semibold">
                    2. Scientific Quality
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="scientific_quality"
                    class="form-select"
                    required
                >

                    <option value="">
                        -- Select --
                    </option>

                    @foreach([
                        'excellent' => 'Excellent',
                        'good'      => 'Good',
                        'fair'      => 'Fair',
                        'poor'      => 'Poor',
                    ] as $value => $label)

                        <option
                            value="{{ $value }}"
                            @selected(
                                old(
                                    'scientific_quality',
                                    $assessment->scientific_quality ?? ''
                                ) === $value
                            )
                        >
                            {{ $label }}
                        </option>

                    @endforeach

                </select>

            </div>



            {{-- Methodology --}}
            <div class="mb-4">

                <label class="form-label fw-semibold">
                    3. Methodology
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="methodology_status"
                    class="form-select"
                    required
                >

                    <option value="">
                        -- Select --
                    </option>

                    <option
                        value="appropriate"
                        @selected(
                            old(
                                'methodology_status',
                                $assessment->methodology_status ?? ''
                            ) === 'appropriate'
                        )
                    >
                        Appropriate
                    </option>

                    <option
                        value="needs_clarification"
                        @selected(
                            old(
                                'methodology_status',
                                $assessment->methodology_status ?? ''
                            ) === 'needs_clarification'
                        )
                    >
                        Needs Clarification
                    </option>

                    <option
                        value="major_concern"
                        @selected(
                            old(
                                'methodology_status',
                                $assessment->methodology_status ?? ''
                            ) === 'major_concern'
                        )
                    >
                        Major Concern
                    </option>

                    <option
                        value="unacceptable"
                        @selected(
                            old(
                                'methodology_status',
                                $assessment->methodology_status ?? ''
                            ) === 'unacceptable'
                        )
                    >
                        Unacceptable
                    </option>

                </select>

            </div>



            {{-- Novelty --}}
            <div class="mb-4">

                <label class="form-label fw-semibold">
                    4. Novelty / Originality
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="novelty_status"
                    class="form-select"
                    required
                >

                    <option value="">
                        -- Select --
                    </option>

                    @foreach([
                        'high'     => 'High',
                        'moderate' => 'Moderate',
                        'low'      => 'Low',
                        'none'     => 'No Significant Novelty',
                    ] as $value => $label)

                        <option
                            value="{{ $value }}"
                            @selected(
                                old(
                                    'novelty_status',
                                    $assessment->novelty_status ?? ''
                                ) === $value
                            )
                        >
                            {{ $label }}
                        </option>

                    @endforeach

                </select>

            </div>



            {{-- Reporting Quality --}}
            <div class="mb-4">

                <label class="form-label fw-semibold">
                    5. Reporting Quality
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="reporting_quality"
                    class="form-select"
                    required
                >

                    <option value="">
                        -- Select --
                    </option>

                    @foreach([
                        'excellent' => 'Excellent',
                        'good'      => 'Good',
                        'fair'      => 'Fair',
                        'poor'      => 'Poor',
                    ] as $value => $label)

                        <option
                            value="{{ $value }}"
                            @selected(
                                old(
                                    'reporting_quality',
                                    $assessment->reporting_quality ?? ''
                                ) === $value
                            )
                        >
                            {{ $label }}
                        </option>

                    @endforeach

                </select>

            </div>



            {{-- Ethical Concern --}}
            <div class="mb-4">

                <label class="form-label fw-semibold d-block">
                    6. Ethical Concern
                </label>

                <div class="form-check form-check-inline">

                    <input
                        type="radio"
                        name="ethical_concern"
                        value="0"
                        id="ethical_no"
                        class="form-check-input"
                        @checked(
                            (string) old(
                                'ethical_concern',
                                isset($assessment)
                                    ? (int) $assessment->ethical_concern
                                    : 0
                            ) === '0'
                        )
                    >

                    <label
                        class="form-check-label"
                        for="ethical_no"
                    >
                        No
                    </label>

                </div>


                <div class="form-check form-check-inline">

                    <input
                        type="radio"
                        name="ethical_concern"
                        value="1"
                        id="ethical_yes"
                        class="form-check-input"
                        @checked(
                            (string) old(
                                'ethical_concern',
                                isset($assessment)
                                    ? (int) $assessment->ethical_concern
                                    : 0
                            ) === '1'
                        )
                    >

                    <label
                        class="form-check-label"
                        for="ethical_yes"
                    >
                        Yes
                    </label>

                </div>


                <textarea
                    name="ethical_comment"
                    class="form-control mt-2"
                    rows="3"
                    placeholder="Describe any ethical concern..."
                >{{ old(
                    'ethical_comment',
                    $assessment->ethical_comment ?? ''
                ) }}</textarea>

            </div>



            {{-- Conflict of Interest --}}
            <div class="mb-4">

                <label class="form-label fw-semibold d-block">
                    7. Conflict of Interest
                </label>

                <div class="form-check form-check-inline">

                    <input
                        type="radio"
                        name="conflict_of_interest"
                        value="0"
                        id="coi_no"
                        class="form-check-input"
                        @checked(
                            (string) old(
                                'conflict_of_interest',
                                isset($assessment)
                                    ? (int) $assessment->conflict_of_interest
                                    : 0
                            ) === '0'
                        )
                    >

                    <label
                        class="form-check-label"
                        for="coi_no"
                    >
                        No
                    </label>

                </div>


                <div class="form-check form-check-inline">

                    <input
                        type="radio"
                        name="conflict_of_interest"
                        value="1"
                        id="coi_yes"
                        class="form-check-input"
                        @checked(
                            (string) old(
                                'conflict_of_interest',
                                isset($assessment)
                                    ? (int) $assessment->conflict_of_interest
                                    : 0
                            ) === '1'
                        )
                    >

                    <label
                        class="form-check-label"
                        for="coi_yes"
                    >
                        Yes
                    </label>

                </div>


                <textarea
                    name="conflict_comment"
                    class="form-control mt-2"
                    rows="3"
                    placeholder="Describe conflict of interest, if any..."
                >{{ old(
                    'conflict_comment',
                    $assessment->conflict_comment ?? ''
                ) }}</textarea>

            </div>



            {{-- Comments --}}
            <div class="mb-4">

                <label class="form-label fw-semibold">
                    8. Editorial Assessment Comments
                </label>

                <textarea
                    name="comments"
                    class="form-control"
                    rows="5"
                    placeholder="Write the Handling Editor's assessment comments..."
                >{{ old(
                    'comments',
                    $assessment->comments ?? ''
                ) }}</textarea>

            </div>



            {{-- Outcome --}}
            <div class="mb-4">

                <label class="form-label fw-semibold">
                    9. Recommended Next Action
                    <span class="text-danger">*</span>
                </label>


                <div class="border rounded p-3">


                    <div class="form-check mb-3">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="outcome"
                            value="send_for_review"
                            id="send_for_review"
                            required
                            @checked(
                                old(
                                    'outcome',
                                    $assessment->outcome ?? ''
                                ) === 'send_for_review'
                            )
                        >

                        <label
                            class="form-check-label"
                            for="send_for_review"
                        >

                            <strong>
                                Send for Peer Review
                            </strong>

                            <div class="small text-muted">
                                Manuscript will move to Reviewer Selection.
                            </div>

                        </label>

                    </div>



                    <div class="form-check mb-3">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="outcome"
                            value="return_for_clarification"
                            id="return_for_clarification"
                            required
                            @checked(
                                old(
                                    'outcome',
                                    $assessment->outcome ?? ''
                                ) === 'return_for_clarification'
                            )
                        >

                        <label
                            class="form-check-label"
                            for="return_for_clarification"
                        >

                            <strong>
                                Return for Clarification / Correction
                            </strong>

                            <div class="small text-muted">
                                Author correction is required before peer review.
                            </div>

                        </label>

                    </div>



                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="outcome"
                            value="recommend_rejection"
                            id="recommend_rejection"
                            required
                            @checked(
                                old(
                                    'outcome',
                                    $assessment->outcome ?? ''
                                ) === 'recommend_rejection'
                            )
                        >

                        <label
                            class="form-check-label"
                            for="recommend_rejection"
                        >

                            <strong class="text-danger">
                                Recommend Rejection
                            </strong>

                            <div class="small text-muted">
                                Send rejection recommendation for Editor-in-Chief review.
                            </div>

                        </label>

                    </div>

                </div>

            </div>



            {{-- Submit --}}
            @if(Route::has('handling-editor.assessment.store'))

                <div class="border-top pt-3 d-flex justify-content-end">

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        <i class="bi bi-check2-circle me-1"></i>
                        Complete Editorial Assessment
                    </button>

                </div>

                </form>

            @else

                <div class="alert alert-warning mb-0">

                    <i class="bi bi-exclamation-triangle me-1"></i>

                    Assessment submission route has not yet been created.

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         WORKFLOW
    ========================================================== --}}

    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <strong>
                <i class="bi bi-diagram-3 me-1"></i>
                Next Workflow
            </strong>

        </div>

        <div class="card-body">

            <div class="row text-center g-3">

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <i class="bi bi-people fs-3 text-primary"></i>

                        <h6 class="mt-2">
                            Send for Peer Review
                        </h6>

                        <small class="text-muted">
                            Move manuscript to Reviewer Selection.
                        </small>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <i class="bi bi-pencil-square fs-3 text-warning"></i>

                        <h6 class="mt-2">
                            Author Clarification
                        </h6>

                        <small class="text-muted">
                            Return manuscript for required correction.
                        </small>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <i class="bi bi-x-circle fs-3 text-danger"></i>

                        <h6 class="mt-2">
                            Recommend Rejection
                        </h6>

                        <small class="text-muted">
                            Forward recommendation to Editor-in-Chief.
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection