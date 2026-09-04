@extends('author.layouts.app')

@section('content')

<div class="container-fluid py-4">

{{-- Manuscript Details --}}
<div class="card shadow-sm mb-4">

    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">
            <i class="bi bi-file-earmark-text me-2"></i>
            Manuscript Details
        </h4>
    </div>

    <div class="card-body">

        <table class="table table-bordered align-middle">

            <tr>
                <th width="30%">Manuscript ID</th>
                <td>{{ $manuscript->manuscript_id }}</td>
            </tr>

            <tr>
                <th>Title</th>
                <td>{{ $manuscript->title }}</td>
            </tr>

            <tr>
                <th>Journal</th>
                <td>{{ $manuscript->journal->name ?? 'N/A' }}</td>
            </tr>

            <tr>
                <th>Article Type</th>
                <td>{{ $manuscript->articleType->name ?? 'N/A' }}</td>
            </tr>

            <tr>
                <th>Status</th>
                <td>

                    @if($manuscript->status === 'technical_correction')

                        <span class="badge bg-danger">
                            Technical Correction Required
                        </span>

                    @elseif($manuscript->status === 'payment_required')

                        <span class="badge bg-warning text-dark">
                            Payment Required
                        </span>

                    @elseif($manuscript->status === 'payment_verified')

                        <span class="badge bg-success">
                            Payment Verified
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            {{ ucfirst(str_replace('_', ' ', $manuscript->status)) }}
                        </span>

                    @endif

                </td>
            </tr>

            <tr>
                <th>Completion Progress</th>

                <td>

                    <div class="progress" style="height: 24px;">

                        <div
                            class="progress-bar"
                            role="progressbar"
                            style="width: {{ $manuscript->completion_percentage }}%;"
                        >
                            {{ $manuscript->completion_percentage }}%
                        </div>

                    </div>

                </td>
            </tr>

        </table>

    </div>

</div>

{{-- ================================================================
     TECHNICAL CORRECTION REQUIRED
     ================================================================ --}}

@if(
    $manuscript->status === 'technical_correction' &&
    isset($technicalCheck) &&
    $technicalCheck
)

    <div class="card border-danger shadow-sm mb-4">

        <div class="card-header bg-danger text-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    <i class="bi bi-exclamation-triangle me-2"></i>

                    Technical Corrections Required

                </h5>

                <span class="badge bg-light text-danger">

                    Technical Check
                    #{{ $technicalCheck->check_number }}

                </span>

            </div>

        </div>


        <div class="card-body">


            {{-- ========================================================
                 REVIEW RESULT
                 ======================================================== --}}

            <div class="alert alert-danger">

                <strong>

                    Technical Check Result:

                </strong>

                Failed

                @if($technicalCheck->completedBy)

                    <br>

                    <small>

                        Checked by:
                        {{ $technicalCheck->completedBy->name }}

                    </small>

                @endif

                @if($technicalCheck->completed_at)

                    <br>

                    <small>

                        Checked on:
                        {{ $technicalCheck->completed_at->format('d M Y, h:i A') }}

                    </small>

                @endif

            </div>


            {{-- ========================================================
                 FAILED CHECKLIST ITEMS
                 ======================================================== --}}

            @if(isset($failedTechnicalItems) && $failedTechnicalItems->count())

                <h6 class="fw-bold mb-3">

                    <i class="bi bi-list-check me-2"></i>

                    Corrections Required

                </h6>


                @foreach($failedTechnicalItems as $index => $item)

                    <div class="card border-warning mb-3">

                        <div class="card-header bg-warning-subtle">

                            <strong>

                                {{ $index + 1 }}.

                                {{ $item->check_name }}

                            </strong>

                        </div>


                        <div class="card-body">

                            @if($item->comments)

                                <div class="alert alert-warning mb-0">

                                    <strong>

                                        Reviewer Comment:

                                    </strong>

                                    <div class="mt-2">

                                        {!! nl2br(e($item->comments)) !!}

                                    </div>

                                </div>

                            @else

                                <div class="text-muted">

                                    Correction is required for this item.

                                </div>

                            @endif

                        </div>

                    </div>

                @endforeach

            @endif


            {{-- ========================================================
                 OVERALL REVIEWER COMMENT
                 ======================================================== --}}

            @if($technicalCheck->comments)

                <div class="card border-secondary mb-4">

                    <div class="card-header">

                        <strong>

                            <i class="bi bi-chat-left-text me-2"></i>

                            Overall Technical Reviewer Comment

                        </strong>

                    </div>

                    <div class="card-body">

                        {!! nl2br(e($technicalCheck->comments)) !!}

                    </div>

                </div>

            @endif


            {{-- ========================================================
                 AUTHOR CORRECTION FORM
                 ======================================================== --}}

            <form
                method="POST"
                action="{{ route(
                    'author.manuscripts.technical-correction.submit',
                    $manuscript
                ) }}"
                enctype="multipart/form-data"
            >

                @csrf


                {{-- ====================================================
                     AUTHOR RESPONSE
                     ==================================================== --}}

                <div class="mb-4">

                    <label
                        for="response"
                        class="form-label fw-bold"
                    >

                        <i class="bi bi-reply me-1"></i>

                        Author's Response to Technical Corrections

                    </label>


                    <textarea
                        name="response"
                        id="response"
                        rows="6"
                        class="form-control @error('response') is-invalid @enderror"
                        placeholder="Please explain how you have addressed the technical corrections..."
                    >{{ old('response') }}</textarea>


                    @error('response')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror


                    <div class="form-text">

                        Please explain the corrections you have made.

                    </div>

                </div>


                {{-- ====================================================
                     FILE REPLACEMENT
                     ==================================================== --}}

                <div class="mb-4">

                    <h6 class="fw-bold">

                        <i class="bi bi-upload me-2"></i>

                        Upload Corrected Files

                    </h6>


                    <div class="alert alert-info">

                        <i class="bi bi-info-circle me-2"></i>

                        Upload a corrected version only for files that
                        required modification.

                        Original submitted files will be preserved.

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
                                            Version
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                        <th style="width: 35%;">
                                            Upload Correction
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach(
                                        $manuscript->files
                                            ->where('status', 'active')
                                            ->sortByDesc('id')
                                    as $file)

                                        <tr>

                                            <td>

                                                <i class="bi bi-file-earmark-text me-1"></i>

                                                {{ $file->original_name }}

                                            </td>


                                            <td>

                                                {{ $file->file_type }}

                                            </td>


                                            <td>

                                                <span class="badge bg-secondary">

                                                    V{{ $file->version_number }}

                                                </span>

                                            </td>


                                            <td>

                                                <span class="badge bg-success">

                                                    Active

                                                </span>

                                            </td>


                                            <td>

                                                <input
                                                    type="file"
                                                    name="files[{{ $file->id }}]"
                                                    class="form-control"
                                                    accept=".pdf,.doc,.docx,.rtf,.txt"
                                                >


                                                <small class="text-muted">

                                                    Leave empty if no correction
                                                    is required for this file.

                                                </small>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="alert alert-warning">

                            No active manuscript files were found.

                        </div>

                    @endif

                </div>


                {{-- ====================================================
                     CONFIRMATION
                     ==================================================== --}}

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
                            >

                            <label
                                class="form-check-label"
                                for="confirmation"
                            >
                                I confirm that I have addressed the
                                technical corrections and that the
                                uploaded files are the corrected versions
                                of my manuscript files.
                            </label>

                            @error('confirmation')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                     SUBMIT
                     ==================================================== --}}

                <div class="d-flex justify-content-end">

                    <button
                        type="submit"
                        class="btn btn-danger btn-lg"
                        onclick="return confirm(
                            'Are you sure you want to submit your technical corrections?'
                        )"
                    >

                        <i class="bi bi-send me-2"></i>

                        Submit Technical Corrections

                    </button>

                </div>

            </form>

        </div>

    </div>

@endif

{{-- ========================================================= --}}
{{-- AUTHORS --}}
{{-- ========================================================= --}}

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

                <table class="table table-sm table-bordered align-middle">

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


{{-- ========================================================= --}}
{{-- UPLOADED FILES --}}
{{-- ========================================================= --}}

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

                <table class="table table-bordered align-middle">

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

                                    @if(isset($file->file_name))

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


{{-- Back Button --}}
<a
    href="{{ route('author.manuscripts.index') }}"
    class="btn btn-secondary"
>

    <i class="bi bi-arrow-left me-1"></i>

    Back to My Manuscripts

</a>

</div>

@endsection
