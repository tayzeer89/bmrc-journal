@extends('admin.layouts.app')

@section('title', 'Assign Handling Editor')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-user-edit me-2"></i>
                Handling Editor Assignment
            </h4>

            <p class="text-muted mb-0">
                Review manuscript information and assign a Handling Editor
            </p>
        </div>

        <a
            href="{{ route('eic.editor-assignment.index') }}"
            class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>
            Back to Queue

        </a>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}
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
         MANUSCRIPT INFORMATION
    ========================================================== --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">
                <i class="fas fa-file-alt me-2"></i>
                Manuscript Information
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-3">

                {{-- Manuscript ID --}}
                <div class="col-md-3">

                    <strong>
                        Manuscript ID
                    </strong>

                    <div>
                        {{ $manuscript->manuscript_id ?? 'N/A' }}
                    </div>

                </div>


                {{-- Journal --}}
                <div class="col-md-3">

                    <strong>
                        Journal
                    </strong>

                    <div>
                        {{ $manuscript->journal->name ?? 'N/A' }}
                    </div>

                </div>


                {{-- Article Type --}}
                <div class="col-md-3">

                    <strong>
                        Article Type
                    </strong>

                    <div>
                        {{ $manuscript->articleType->name ?? 'N/A' }}
                    </div>

                </div>


                {{-- Submission Date --}}
                <div class="col-md-3">

                    <strong>
                        Submitted
                    </strong>

                    <div>

                        @if($manuscript->submitted_at)

                            {{ $manuscript->submitted_at->format('d M Y') }}

                        @else

                            N/A

                        @endif

                    </div>

                </div>


                {{-- Title --}}
                <div class="col-12">

                    <hr>

                    <strong>
                        Article Title
                    </strong>

                    <div class="mt-1 fs-5">
                        {{ $manuscript->title }}
                    </div>

                </div>


                {{-- Abstract --}}
                @if($manuscript->abstract)

                    <div class="col-12">

                        <strong>
                            Abstract
                        </strong>

                        <div class="mt-2 text-muted abstract-content">
                            {!! $manuscript->abstract !!}
                        </div>

                    </div>

                @endif


               @if(!empty($manuscript->keywords))

                    <div class="col-12">

                        <strong>
                            Keywords
                        </strong>

                        <div class="mt-2">

                            @php
                                $keywords = is_array($manuscript->keywords)
                                    ? $manuscript->keywords
                                    : array_filter(
                                        array_map(
                                            'trim',
                                            explode(',', (string) $manuscript->keywords)
                                        )
                                    );
                            @endphp

                            @foreach($keywords as $keyword)

                                <span class="badge bg-light text-dark border me-1 mb-1">
                                    {{ is_array($keyword)
                                        ? ($keyword['name'] ?? $keyword['keyword'] ?? implode(', ', $keyword))
                                        : $keyword
                                    }}
                                </span>

                            @endforeach

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
         PRE-ASSIGNMENT VERIFICATION
    ========================================================== --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="fas fa-clipboard-check me-2"></i>
                Pre-Assignment Verification
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-3">

                {{-- =====================================================
                     TECHNICAL CHECK
                ====================================================== --}}
                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <h6>
                            <i class="fas fa-list-check me-1"></i>
                            Technical Check
                        </h6>

                        @if($manuscript->latestTechnicalCheck)

                            <span class="badge bg-success">
                                Completed
                            </span>

                            @if($manuscript->latestTechnicalCheck->completed_at)

                                <div class="small text-muted mt-2">

                                    Completed:

                                    {{
                                        $manuscript
                                            ->latestTechnicalCheck
                                            ->completed_at
                                            ->format('d M Y')
                                    }}

                                </div>

                            @endif

                        @else

                            <span class="badge bg-secondary">
                                No Record
                            </span>

                        @endif

                    </div>

                </div>


                {{-- =====================================================
                     PAYMENT VERIFICATION
                ====================================================== --}}
                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <h6>
                            <i class="fas fa-money-check-alt me-1"></i>
                            Payment Verification
                        </h6>

                        @if($manuscript->latestPayment)

                            @php
                                $paymentStatus =
                                    $manuscript->latestPayment->status
                                    ?? 'N/A';
                            @endphp

                            <div class="mb-2">

                                <span class="badge bg-success">

                                    {{
                                        ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $paymentStatus
                                            )
                                        )
                                    }}

                                </span>

                            </div>


                            @if($manuscript->latestPayment->amount)

                                <div class="small text-muted">

                                    Amount:

                                    {{
                                        number_format(
                                            $manuscript->latestPayment->amount,
                                            2
                                        )
                                    }}

                                </div>

                            @endif

                        @else

                            <span class="badge bg-secondary">
                                No Record
                            </span>

                        @endif

                    </div>

                </div>


                {{-- =====================================================
                     SIMILARITY CHECK
                ====================================================== --}}
                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <h6>
                            <i class="fas fa-percentage me-1"></i>
                            Similarity Check
                        </h6>

                        @if($manuscript->latestSimilarityCheck)

                            @php

                                $similarity =
                                    $manuscript
                                        ->latestSimilarityCheck
                                        ->similarity_percentage
                                    ??
                                    $manuscript
                                        ->latestSimilarityCheck
                                        ->similarity_score
                                    ??
                                    null;

                            @endphp


                            @if(!is_null($similarity))

                                <h4 class="mb-1">
                                    {{ $similarity }}%
                                </h4>


                                @if($similarity < 20)

                                    <span class="badge bg-success">
                                        Acceptable
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        Review Required
                                    </span>

                                @endif

                            @else

                                <span class="badge bg-success">
                                    Checked
                                </span>

                            @endif

                        @else

                            <span class="badge bg-secondary">
                                No Record
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         AUTHORS
    ========================================================== --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    <i class="fas fa-users me-2"></i>

                    Authors

                </h5>

                <span class="badge bg-primary">

                    {{ $manuscript->authors->count() }}

                    Author(s)

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th width="60">#</th>
                            <th>Author</th>
                            <th>Email</th>
                            <th>Institution</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse($manuscript->authors as $author)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    {{
                                        $author->name
                                        ??
                                        trim(
                                            ($author->first_name ?? '')
                                            . ' '
                                            . ($author->last_name ?? '')
                                        )
                                        ?: 'N/A'
                                    }}

                                </td>


                                <td>
                                    {{ $author->email ?? 'N/A' }}
                                </td>


                                <td>
                                    {{ $author->institution ?? 'N/A' }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="text-center text-muted py-4">

                                    No author information available.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
         AUTHOR SUBMITTED FILES
    ========================================================== --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    <i class="fas fa-paperclip me-2"></i>

                    Author Submitted Files

                </h5>


                <span class="badge bg-primary">

                    {{ $manuscript->files->count() }}

                    File(s)

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($manuscript->files->count() > 0)

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th width="60">#</th>
                                <th>Document Type</th>
                                <th>File Name</th>
                                <th>Uploaded</th>
                                <th width="190" class="text-center">
                                    Action
                                </th>
                            </tr>

                        </thead>


                        <tbody>

                            @foreach($manuscript->files as $file)

                                @php

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Document Type
                                    |--------------------------------------------------------------------------
                                    */

                                    $fileType =
                                        $file->file_type
                                        ??
                                        $file->type
                                        ??
                                        $file->document_type
                                        ??
                                        'Document';


                                    /*
                                    |--------------------------------------------------------------------------
                                    | File Name
                                    |--------------------------------------------------------------------------
                                    */

                                    $fileName =
                                        $file->original_name
                                        ??
                                        $file->original_filename
                                        ??
                                        $file->file_name
                                        ??
                                        $file->filename
                                        ??
                                        null;


                                    /*
                                    |--------------------------------------------------------------------------
                                    | File Path
                                    |--------------------------------------------------------------------------
                                    */

                                    $filePath =
                                        $file->file_path
                                        ??
                                        $file->path
                                        ??
                                        $file->storage_path
                                        ??
                                        null;


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Use Path Name if Original Name Missing
                                    |--------------------------------------------------------------------------
                                    */

                                    if (
                                        !$fileName
                                        &&
                                        $filePath
                                    ) {
                                        $fileName =
                                            basename($filePath);
                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | File Extension
                                    |--------------------------------------------------------------------------
                                    */

                                    $extension =
                                        strtolower(
                                            pathinfo(
                                                $fileName ?? '',
                                                PATHINFO_EXTENSION
                                            )
                                        );


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Storage URL
                                    |--------------------------------------------------------------------------
                                    |
                                    | This assumes the files are stored using:
                                    |
                                    | Storage::disk('public')
                                    |
                                    */

                                    $fileUrl =
                                        $filePath
                                        ? asset(
                                            'storage/'
                                            .
                                            ltrim(
                                                $filePath,
                                                '/'
                                            )
                                        )
                                        : null;

                                @endphp


                                <tr>

                                    {{-- Serial --}}
                                    <td>

                                        {{ $loop->iteration }}

                                    </td>


                                    {{-- Document Type --}}
                                    <td>

                                        <span class="badge bg-secondary">

                                            {{
                                                ucwords(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $fileType
                                                    )
                                                )
                                            }}

                                        </span>

                                    </td>


                                    {{-- File Name --}}
                                    <td>

                                        <div class="d-flex align-items-center">


                                            {{-- PDF --}}
                                            @if($extension === 'pdf')

                                                <i class="fas fa-file-pdf text-danger fs-4 me-2"></i>


                                            {{-- Word --}}
                                            @elseif(
                                                in_array(
                                                    $extension,
                                                    [
                                                        'doc',
                                                        'docx'
                                                    ]
                                                )
                                            )

                                                <i class="fas fa-file-word text-primary fs-4 me-2"></i>


                                            {{-- Images --}}
                                            @elseif(
                                                in_array(
                                                    $extension,
                                                    [
                                                        'jpg',
                                                        'jpeg',
                                                        'png',
                                                        'gif'
                                                    ]
                                                )
                                            )

                                                <i class="fas fa-file-image text-success fs-4 me-2"></i>


                                            {{-- Excel --}}
                                            @elseif(
                                                in_array(
                                                    $extension,
                                                    [
                                                        'xls',
                                                        'xlsx'
                                                    ]
                                                )
                                            )

                                                <i class="fas fa-file-excel text-success fs-4 me-2"></i>


                                            {{-- Generic --}}
                                            @else

                                                <i class="fas fa-file text-secondary fs-4 me-2"></i>

                                            @endif


                                            <div>

                                                <strong>

                                                    {{
                                                        $fileName
                                                        ?: 'Unnamed File'
                                                    }}

                                                </strong>


                                                @if(
                                                    isset($file->file_size)
                                                    &&
                                                    $file->file_size
                                                )

                                                    <div class="small text-muted">

                                                        {{
                                                            number_format(
                                                                $file->file_size / 1024,
                                                                2
                                                            )
                                                        }}
                                                        KB

                                                    </div>

                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Uploaded Date --}}
                                    <td>

                                        @if($file->created_at)

                                            {{
                                                $file
                                                    ->created_at
                                                    ->format('d M Y')
                                            }}

                                            <div class="small text-muted">

                                                {{
                                                    $file
                                                        ->created_at
                                                        ->format('h:i A')
                                                }}

                                            </div>

                                        @else

                                            N/A

                                        @endif

                                    </td>


                                    {{-- Action --}}
                                    <td class="text-center">

                                        @if($fileUrl)

                                            {{-- View --}}
                                            <a
                                                href="{{ $fileUrl }}"
                                                target="_blank"
                                                rel="noopener"
                                                class="btn btn-outline-primary btn-sm">

                                                <i class="fas fa-eye me-1"></i>

                                                View

                                            </a>


                                            {{-- Download --}}
                                            <a
                                                href="{{ $fileUrl }}"
                                                download
                                                class="btn btn-outline-success btn-sm">

                                                <i class="fas fa-download me-1"></i>

                                                Download

                                            </a>

                                        @else

                                            <span class="badge bg-secondary">
                                                Unavailable
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

                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>

                    <h6>
                        No Submitted Files
                    </h6>

                    <p class="text-muted mb-0">
                        No manuscript files were found for this submission.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         HANDLING EDITOR ASSIGNMENT
    ========================================================== --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-success text-white">

            <h5 class="mb-0">

                <i class="fas fa-user-check me-2"></i>

                Assign Handling Editor

            </h5>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'eic.editor-assignment.store',
                    $manuscript
                ) }}">

                @csrf


                <div class="row g-3">


                    {{-- =================================================
                         HANDLING EDITOR
                    ================================================== --}}
                    <div class="col-md-6">

                        <label
                            for="editor_id"
                            class="form-label">

                            Handling Editor

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <select
                            name="editor_id"
                            id="editor_id"
                            class="form-select @error('editor_id') is-invalid @enderror"
                            required>

                            <option value="">
                                -- Select Handling Editor --
                            </option>


                            @foreach($editors as $editor)

                                <option
                                    value="{{ $editor->id }}"
                                    {{ old('editor_id') == $editor->id
                                        ? 'selected'
                                        : ''
                                    }}>

                                    {{ $editor->name }}

                                    —

                                    Active:
                                    {{
                                        $editor
                                            ->active_assignment_count
                                        ?? 0
                                    }}

                                </option>

                            @endforeach

                        </select>


                        @error('editor_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror


                        <div class="form-text">

                            Active assignment count is shown beside
                            each Handling Editor.

                        </div>

                    </div>


                    {{-- =================================================
                         DEADLINE
                    ================================================== --}}
                    <div class="col-md-6">

                        <label
                            for="due_date"
                            class="form-label">

                            Assessment Deadline

                        </label>


                        <input
                            type="date"
                            name="due_date"
                            id="due_date"
                            value="{{ old('due_date') }}"
                            min="{{ now()->format('Y-m-d') }}"
                            class="form-control @error('due_date') is-invalid @enderror">


                        @error('due_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         ASSIGNMENT INSTRUCTIONS
                    ================================================== --}}
                    <div class="col-12">

                        <label
                            for="assignment_note"
                            class="form-label">

                            Assignment Instructions / Note

                        </label>


                        <textarea
                            name="assignment_note"
                            id="assignment_note"
                            rows="5"
                            maxlength="5000"
                            class="form-control @error('assignment_note') is-invalid @enderror"
                            placeholder="Enter instructions for the Handling Editor...">{{ old('assignment_note') }}</textarea>


                        @error('assignment_note')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         BUTTONS
                    ================================================== --}}
                    <div class="col-12">

                        <hr>


                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route(
                                    'eic.editor-assignment.index'
                                ) }}"
                                class="btn btn-outline-secondary">

                                <i class="fas fa-times me-1"></i>

                                Cancel

                            </a>


                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="fas fa-user-check me-1"></i>

                                Assign Handling Editor

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         PREVIOUS ASSIGNMENT HISTORY
    ========================================================== --}}
    @if($manuscript->editorAssignments->count())

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">

                    <i class="fas fa-history me-2"></i>

                    Previous Assignment History

                </h5>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>Round</th>
                                <th>Handling Editor</th>
                                <th>Assigned By</th>
                                <th>Assigned</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Reason / Note</th>
                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $manuscript->editorAssignments
                                as $assignment
                            )

                                <tr>

                                    {{-- Round --}}
                                    <td>

                                        {{
                                            $assignment
                                                ->assignment_round
                                        }}

                                    </td>


                                    {{-- Handling Editor --}}
                                    <td>

                                        {{
                                            $assignment
                                                ->editor
                                                ->name
                                            ?? 'N/A'
                                        }}

                                    </td>


                                    {{-- Assigned By --}}
                                    <td>

                                        {{
                                            $assignment
                                                ->assignedBy
                                                ->name
                                            ?? 'N/A'
                                        }}

                                    </td>


                                    {{-- Assigned Date --}}
                                    <td>

                                        @if($assignment->assigned_at)

                                            {{
                                                $assignment
                                                    ->assigned_at
                                                    ->format(
                                                        'd M Y h:i A'
                                                    )
                                            }}

                                        @else

                                            N/A

                                        @endif

                                    </td>


                                    {{-- Due Date --}}
                                    <td>

                                        @if($assignment->due_date)

                                            {{
                                                $assignment
                                                    ->due_date
                                                    ->format('d M Y')
                                            }}

                                        @else

                                            N/A

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @php

                                            $assignmentStatus =
                                                $assignment->status;

                                        @endphp


                                        @if(
                                            $assignmentStatus
                                            ===
                                            'accepted'
                                        )

                                            <span class="badge bg-success">
                                                Accepted
                                            </span>


                                        @elseif(
                                            $assignmentStatus
                                            ===
                                            'declined'
                                        )

                                            <span class="badge bg-danger">
                                                Declined
                                            </span>


                                        @elseif(
                                            $assignmentStatus
                                            ===
                                            'pending'
                                        )

                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>


                                        @elseif(
                                            $assignmentStatus
                                            ===
                                            'completed'
                                        )

                                            <span class="badge bg-primary">
                                                Completed
                                            </span>


                                        @elseif(
                                            $assignmentStatus
                                            ===
                                            'cancelled'
                                        )

                                            <span class="badge bg-secondary">
                                                Cancelled
                                            </span>


                                        @else

                                            <span class="badge bg-secondary">

                                                {{
                                                    ucwords(
                                                        str_replace(
                                                            '_',
                                                            ' ',
                                                            $assignmentStatus
                                                        )
                                                    )
                                                }}

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Note / Decline Reason --}}
                                    <td>

                                        @if($assignment->decline_reason)

                                            <strong class="text-danger">
                                                Decline Reason:
                                            </strong>

                                            <br>

                                            {{
                                                $assignment
                                                    ->decline_reason
                                            }}


                                        @elseif($assignment->assignment_note)

                                            {{
                                                $assignment
                                                    ->assignment_note
                                            }}


                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endif

</div>


{{-- =============================================================
     PAGE STYLE
============================================================= --}}
<style>

    .abstract-content {
        line-height: 1.7;
    }

    .abstract-content p {
        margin-bottom: 8px;
        text-align: justify;
    }

    .abstract-content p:last-child {
        margin-bottom: 0;
    }

    .table th {
        white-space: nowrap;
        vertical-align: middle;
    }

    .table td {
        vertical-align: middle;
    }

</style>

@endsection