@extends('admin.layouts.app')

@section('title', 'Assignment Details')
@section('page_title', 'Handling Editor Assignment')

@section('content')

@php
    $manuscript = $assignment->manuscript;

    $statusClass = match($assignment->status) {
        'pending'    => 'warning',
        'accepted'   => 'success',
        'declined'   => 'danger',
        'completed'  => 'primary',
        'cancelled'  => 'secondary',
        'reassigned' => 'info',
        default      => 'secondary',
    };

    /*
     * Keywords may be stored as JSON/array or text.
     */
    $keywords = [];

    if (!empty($manuscript->keywords)) {
        if (is_array($manuscript->keywords)) {
            $keywords = $manuscript->keywords;
        } else {
            $keywords = array_filter(
                array_map(
                    'trim',
                    explode(',', (string) $manuscript->keywords)
                )
            );
        }
    }
@endphp


<div class="container-fluid px-0">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Assignment Details
            </h4>

            <div class="text-muted">
                {{ $manuscript->manuscript_id ?? '#'.$manuscript->id }}
            </div>
        </div>

        <a
            href="{{ route('handling-editor.assignments.index') }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left"></i>
            Back to My Assignments
        </a>

    </div>


    {{-- =========================================================
         ASSIGNMENT INFORMATION
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-light">
            <strong>
                <i class="bi bi-person-check me-1"></i>
                Assignment Information
            </strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Assignment Status
                    </small>

                    <span class="badge bg-{{ $statusClass }}">
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
                </div>


                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Assignment Round
                    </small>

                    <strong>
                        {{ $assignment->assignment_round ?? 1 }}
                    </strong>
                </div>


                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Assigned Date
                    </small>

                    {{
                        $assignment->assigned_at
                            ? $assignment->assigned_at->format('d M Y h:i A')
                            : 'N/A'
                    }}
                </div>


                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Assessment Deadline
                    </small>

                    {{
                        $assignment->due_date
                            ? $assignment->due_date->format('d M Y')
                            : 'N/A'
                    }}
                </div>


                <div class="col-md-6">
                    <small class="text-muted d-block">
                        Assigned By
                    </small>

                    <strong>
                        {{ $assignment->assignedBy->name ?? 'N/A' }}
                    </strong>
                </div>


                <div class="col-md-6">
                    <small class="text-muted d-block">
                        Handling Editor
                    </small>

                    <strong>
                        {{ $assignment->editor->name ?? 'N/A' }}
                    </strong>

                    @if($assignment->editor?->email)
                        <div class="small text-muted">
                            {{ $assignment->editor->email }}
                        </div>
                    @endif
                </div>


                @if($assignment->assignment_note)

                    <div class="col-12">
                        <hr>

                        <small class="text-muted d-block mb-2">
                            Instructions from Editor-in-Chief
                        </small>

                        <div class="alert alert-info mb-0">
                            {!! nl2br(e($assignment->assignment_note)) !!}
                        </div>
                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
         MANUSCRIPT INFORMATION
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-light">
            <strong>
                <i class="bi bi-file-earmark-text me-1"></i>
                Manuscript Information
            </strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Manuscript ID
                    </small>

                    <strong>
                        {{ $manuscript->manuscript_id ?? $manuscript->id }}
                    </strong>
                </div>


                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Journal
                    </small>

                    {{ $manuscript->journal->name ?? 'N/A' }}
                </div>


                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Article Type
                    </small>

                    {{ $manuscript->articleType->name ?? 'N/A' }}
                </div>


                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Current Status
                    </small>

                    <span class="badge bg-primary">
                        {{
                            ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $manuscript->status ?? 'N/A'
                                )
                            )
                        }}
                    </span>
                </div>


                <div class="col-12">

                    <small class="text-muted d-block">
                        Article Title
                    </small>

                    <h5 class="mt-1 mb-0">
                        {{ $manuscript->title }}
                    </h5>

                </div>


                @if(count($keywords))

                    <div class="col-12">

                        <small class="text-muted d-block mb-2">
                            Keywords
                        </small>

                        @foreach($keywords as $keyword)

                            @php
                                $keywordText = is_array($keyword)
                                    ? ($keyword['name']
                                        ?? $keyword['keyword']
                                        ?? implode(', ', $keyword))
                                    : $keyword;
                            @endphp

                            <span class="badge bg-light text-dark border me-1 mb-1">
                                {{ $keywordText }}
                            </span>

                        @endforeach

                    </div>

                @endif


                @if($manuscript->abstract)

                    <div class="col-12">

                        <small class="text-muted d-block mb-2">
                            Abstract
                        </small>

                        <div class="border rounded p-3 bg-light">
                            {!! $manuscript->abstract !!}
                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
         AUTHORS
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-light">

            <strong>
                <i class="bi bi-people me-1"></i>
                Authors
            </strong>

        </div>


        <div class="card-body p-0">

            @if($manuscript->authors->count())

                <div class="table-responsive">

                    <table class="table table-bordered align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th width="60">#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Institution</th>
                            </tr>

                        </thead>

                        <tbody>

                        @foreach($manuscript->authors as $author)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>

                                    <strong>
                                        {{
                                            trim(
                                                ($author->title ?? '')
                                                .' '.
                                                ($author->first_name ?? '')
                                                .' '.
                                                ($author->last_name ?? '')
                                            )
                                        }}
                                    </strong>

                                </td>

                                <td>
                                    {{ $author->email ?? 'N/A' }}
                                </td>

                                <td>

                                    @php
                                        $institution =
                                            $author->institution ?? null;
                                    @endphp

                                    @if(is_array($institution))

                                        {{ implode(', ', $institution) }}

                                    @else

                                        {{ $institution ?? 'N/A' }}

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="p-4 text-center text-muted">
                    No author information available.
                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         AUTHOR SUBMITTED FILES / ATTACHMENTS
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-light d-flex justify-content-between">

            <strong>
                <i class="bi bi-paperclip me-1"></i>
                Author Submitted Files
            </strong>

            <span class="badge bg-secondary">
                {{ $manuscript->files->count() }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($manuscript->files->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th width="60">#</th>
                                <th>Document Type</th>
                                <th>File Name</th>
                                <th width="180">Action</th>
                            </tr>

                        </thead>


                        <tbody>

                        @foreach($manuscript->files as $file)

                            @php

                                /*
                                 * Supports several common column names.
                                 * Once your ManuscriptFile schema is confirmed,
                                 * these can be simplified.
                                 */

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
                                        {{
                                            ucwords(
                                                str_replace(
                                                    ['_', '-'],
                                                    ' ',
                                                    $fileType
                                                )
                                            )
                                        }}
                                    </span>

                                </td>


                                <td>

                                    <i class="bi bi-file-earmark me-1"></i>

                                    {{ $fileName }}

                                </td>


                                <td>

                                    @if($filePath)

                                        <div class="d-flex gap-1">

                                            <a
                                                href="{{ asset(
                                                    'storage/' .
                                                    ltrim(
                                                        $filePath,
                                                        '/'
                                                    )
                                                ) }}"
                                                target="_blank"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                <i class="bi bi-eye"></i>
                                                View
                                            </a>


                                            <a
                                                href="{{ asset(
                                                    'storage/' .
                                                    ltrim(
                                                        $filePath,
                                                        '/'
                                                    )
                                                ) }}"
                                                download
                                                class="btn btn-sm btn-outline-success"
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

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5 text-muted">

                    <i class="bi bi-paperclip fs-2 d-block mb-2"></i>

                    No submitted files found for this manuscript.

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         PRE-ASSIGNMENT VERIFICATION
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-light">

            <strong>
                <i class="bi bi-check2-square me-1"></i>
                Pre-Assignment Verification
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- TECHNICAL CHECK --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block mb-2">
                            Technical Check
                        </small>

                        @if($manuscript->latestTechnicalCheck)

                            <span class="badge bg-success">
                                Available
                            </span>

                            @if(
                                $manuscript
                                    ->latestTechnicalCheck
                                    ->status
                            )

                                <div class="mt-2 small">

                                    Status:

                                    <strong>
                                        {{
                                            ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $manuscript
                                                        ->latestTechnicalCheck
                                                        ->status
                                                )
                                            )
                                        }}
                                    </strong>

                                </div>

                            @endif

                        @else

                            <span class="badge bg-secondary">
                                Not Available
                            </span>

                        @endif

                    </div>

                </div>


                {{-- PAYMENT --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block mb-2">
                            Payment Verification
                        </small>

                        @if($manuscript->latestPayment)

                            <span class="badge bg-success">
                                Available
                            </span>

                            @if($manuscript->latestPayment->status)

                                <div class="mt-2 small">

                                    Status:

                                    <strong>
                                        {{
                                            ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $manuscript
                                                        ->latestPayment
                                                        ->status
                                                )
                                            )
                                        }}
                                    </strong>

                                </div>

                            @endif

                        @else

                            <span class="badge bg-secondary">
                                Not Available
                            </span>

                        @endif

                    </div>

                </div>


                {{-- SIMILARITY --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block mb-2">
                            Similarity Check
                        </small>

                        @if($manuscript->latestSimilarityCheck)

                            <span class="badge bg-success">
                                Available
                            </span>


                            @if(
                                isset(
                                    $manuscript
                                        ->latestSimilarityCheck
                                        ->similarity_percentage
                                )
                            )

                                <div class="mt-2">

                                    <strong class="fs-5">
                                        {{
                                            $manuscript
                                                ->latestSimilarityCheck
                                                ->similarity_percentage
                                        }}%
                                    </strong>

                                </div>

                            @endif


                            @if(
                                $manuscript
                                    ->latestSimilarityCheck
                                    ->status
                            )

                                <div class="small">

                                    Status:

                                    <strong>
                                        {{
                                            ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $manuscript
                                                        ->latestSimilarityCheck
                                                        ->status
                                                )
                                            )
                                        }}
                                    </strong>

                                </div>

                            @endif

                        @else

                            <span class="badge bg-secondary">
                                Not Available
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         ASSIGNMENT RESPONSE
    ========================================================== --}}

    @if($assignment->status === 'pending')

        <div class="card shadow-sm border-warning mb-4">

            <div class="card-header bg-warning-subtle">

                <strong>
                    <i class="bi bi-question-circle me-1"></i>
                    Assignment Response
                </strong>

            </div>


            <div class="card-body">

                <p class="mb-3">

                    Review the manuscript information, submitted
                    files and Editor-in-Chief instructions before
                    accepting or declining this assignment.

                </p>


                <div class="d-flex gap-2 flex-wrap">

                    <form
                        method="POST"
                        action="{{ route(
                            'handling-editor.assignments.accept',
                            $assignment->id
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-success"
                            onclick="return confirm(
                                'Are you sure you want to accept this assignment?'
                            )"
                        >
                            <i class="bi bi-check-circle"></i>
                            Accept Assignment
                        </button>

                    </form>


                    <button
                        type="button"
                        class="btn btn-outline-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#declineModal"
                    >
                        <i class="bi bi-x-circle"></i>
                        Decline Assignment
                    </button>

                </div>

            </div>

        </div>

    @elseif($assignment->status === 'accepted')

        <div class="alert alert-success">

            <i class="bi bi-check-circle-fill me-2"></i>

            <strong>Assignment Accepted.</strong>

            This manuscript is ready for Editorial Assessment.

        </div>

    @elseif($assignment->status === 'declined')

        <div class="alert alert-danger">

            <strong>
                Assignment Declined
            </strong>

            @if($assignment->decline_reason)

                <hr>

                <strong>Reason:</strong>

                {{ $assignment->decline_reason }}

            @endif

        </div>

    @endif

</div>


{{-- =========================================================
     DECLINE ASSIGNMENT MODAL
========================================================== --}}

@if($assignment->status === 'pending')

<div
    class="modal fade"
    id="declineModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route(
                    'handling-editor.assignments.decline',
                    $assignment->id
                ) }}"
            >

                @csrf


                <div class="modal-header">

                    <h5 class="modal-title">
                        Decline Assignment
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="alert alert-warning">

                        The manuscript will be returned to the
                        Editor-in-Chief for reassignment.

                    </div>


                    <label
                        for="decline_reason"
                        class="form-label"
                    >
                        Reason for Declining
                        <span class="text-danger">*</span>
                    </label>


                    <textarea
                        name="decline_reason"
                        id="decline_reason"
                        class="form-control"
                        rows="5"
                        minlength="10"
                        maxlength="2000"
                        required
                    >{{ old('decline_reason') }}</textarea>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        <i class="bi bi-x-circle"></i>
                        Confirm Decline
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif

@endsection