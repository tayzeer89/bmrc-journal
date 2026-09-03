@extends('admin.layouts.app')

@section('title', 'Manuscript Details')

@section('content')

<div class="container-fluid py-4">

{{-- ============================================================
    HEADER
============================================================= --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Manuscript Details
        </h2>

        <p class="text-muted mb-0">
            Complete journal submission record
        </p>
    </div>

    <div class="mt-3 mt-md-0">

        <a href="{{ route('admin.manuscripts.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Back to Manuscripts
        </a>

    </div>

</div>


{{-- ============================================================
    SESSION MESSAGES
============================================================= --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">

        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>
@endif


@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">

        {{ session('error') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>
@endif


{{-- ============================================================
    BASIC MANUSCRIPT INFORMATION
============================================================= --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white py-3">

        <h5 class="mb-0 fw-semibold">
            Manuscript Information
        </h5>

    </div>

    <div class="card-body">

        <div class="row g-4">

            {{-- Manuscript Number --}}
            <div class="col-md-4">

                <label class="text-muted small">
                    Manuscript Number
                </label>

                <div class="fw-semibold">
                    {{ $manuscript->manuscript_no ?? $manuscript->id }}
                </div>

            </div>


            {{-- Article Type --}}
            <div class="col-md-4">

                <label class="text-muted small">
                    Article Type
                </label>

                <div class="fw-semibold">

                    {{ $manuscript->articleType?->name ?? 'N/A' }}

                </div>

            </div>


            {{-- Journal --}}
            <div class="col-md-4">

                <label class="text-muted small">
                    Journal
                </label>

                <div class="fw-semibold">

                    {{ $manuscript->journal?->name ?? 'N/A' }}

                </div>

            </div>


            {{-- Title --}}
            <div class="col-12">

                <label class="text-muted small">
                    Manuscript Title
                </label>

                <div class="fs-5 fw-semibold">

                    {{ $manuscript->title ?? 'N/A' }}

                </div>

            </div>


            {{-- Status --}}
            <div class="col-md-4">

                <label class="text-muted small">
                    Current Status
                </label>

                @php
                    $status = $manuscript->status ?? 'unknown';

                    $statusClass = match ($status) {

                        'draft'
                            => 'bg-secondary',

                        'submitted'
                            => 'bg-primary',

                        'technical_check'
                            => 'bg-warning text-dark',

                        'technical_check_passed'
                            => 'bg-success',

                        'technical_correction'
                            => 'bg-danger',

                        'similarity_check'
                            => 'bg-info text-dark',

                        'editorial_assessment'
                            => 'bg-primary',

                        'under_review'
                            => 'bg-info text-dark',

                        'revision_required'
                            => 'bg-warning text-dark',

                        'accepted'
                            => 'bg-success',

                        'rejected'
                            => 'bg-danger',

                        default
                            => 'bg-secondary',
                    };
                @endphp

                <div>
                    <span class="badge {{ $statusClass }} px-3 py-2">

                        {{ ucwords(str_replace('_', ' ', $status)) }}

                    </span>
                </div>

            </div>


            {{-- Current Stage --}}
            <div class="col-md-4">

                <label class="text-muted small">
                    Current Stage
                </label>

                <div class="fw-semibold">

                    {{ $manuscript->current_stage
                        ? ucwords(str_replace('_', ' ', $manuscript->current_stage))
                        : 'N/A' }}

                </div>

            </div>


            {{-- Submitted Date --}}
            <div class="col-md-4">

                <label class="text-muted small">
                    Submission Date
                </label>

                <div class="fw-semibold">

                    @if($manuscript->submitted_at)

                        {{ $manuscript->submitted_at->format('d M Y, h:i A') }}

                    @elseif($manuscript->created_at)

                        {{ $manuscript->created_at->format('d M Y, h:i A') }}

                    @else

                        N/A

                    @endif

                </div>

            </div>


            {{-- Submitter --}}
            <div class="col-md-6">

                <label class="text-muted small">
                    Submitted By
                </label>

                <div class="fw-semibold">

                    {{ $manuscript->submitter?->name ?? 'N/A' }}

                </div>

                @if($manuscript->submitter?->email)

                    <small class="text-muted">
                        {{ $manuscript->submitter->email }}
                    </small>

                @endif

            </div>


            {{-- Created --}}
            <div class="col-md-3">

                <label class="text-muted small">
                    Created
                </label>

                <div>

                    {{ $manuscript->created_at
                        ? $manuscript->created_at->format('d M Y, h:i A')
                        : 'N/A' }}

                </div>

            </div>


            {{-- Updated --}}
            <div class="col-md-3">

                <label class="text-muted small">
                    Last Updated
                </label>

                <div>

                    {{ $manuscript->updated_at
                        ? $manuscript->updated_at->format('d M Y, h:i A')
                        : 'N/A' }}

                </div>

            </div>

        </div>

    </div>

</div>



{{-- ============================================================
    MANUSCRIPT DETAILS
============================================================= --}}
@if($manuscript->details)

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-semibold">
                Submission Details
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- Running Title --}}
                @if(!empty($manuscript->details->running_title))

                    <div class="col-md-6">

                        <label class="text-muted small">
                            Running Title
                        </label>

                        <div class="fw-semibold">
                            {{ $manuscript->details->running_title }}
                        </div>

                    </div>

                @endif


                {{-- Short Title --}}
                @if(!empty($manuscript->details->short_title))

                    <div class="col-md-6">

                        <label class="text-muted small">
                            Short Title
                        </label>

                        <div class="fw-semibold">
                            {{ $manuscript->details->short_title }}
                        </div>

                    </div>

                @endif

                            {{-- Abstract --}}
                @if(!empty($manuscript->details?->abstract))

                    <div class="col-12">

                        <label class="text-muted small">
                            Abstract
                        </label>

                        <div class="border rounded p-3 bg-light abstract-content">
                            {!! $manuscript->details->abstract !!}
                        </div>

                    </div>

                @elseif(!empty($manuscript->abstract))

                    <div class="col-12">

                        <label class="text-muted small">
                            Abstract
                        </label>

                        <div class="border rounded p-3 bg-light abstract-content">
                            {!! $manuscript->abstract !!}
                        </div>

                    </div>

                @endif


                {{-- Keywords --}}
                @if(!empty($manuscript->details->keywords))

                    <div class="col-12">

                        <label class="text-muted small">
                            Keywords
                        </label>

                        <div>

                            @php
                                $keywords = $manuscript->details->keywords;

                                if (is_string($keywords)) {
                                    $keywords = preg_split(
                                        '/[,;]+/',
                                        $keywords
                                    );
                                }
                            @endphp

                            @foreach($keywords ?? [] as $keyword)

                                @if(trim($keyword) !== '')

                                    <span class="badge bg-light text-dark border me-1 mb-1">
                                        {{ trim($keyword) }}
                                    </span>

                                @endif

                            @endforeach

                        </div>

                    </div>

                @endif


                {{-- Word Count --}}
                @if(!empty($manuscript->details->word_count))

                    <div class="col-md-4">

                        <label class="text-muted small">
                            Word Count
                        </label>

                        <div class="fw-semibold">
                            {{ number_format($manuscript->details->word_count) }}
                        </div>

                    </div>

                @endif


                {{-- Page Count --}}
                @if(!empty($manuscript->details->page_count))

                    <div class="col-md-4">

                        <label class="text-muted small">
                            Page Count
                        </label>

                        <div class="fw-semibold">
                            {{ $manuscript->details->page_count }}
                        </div>

                    </div>

                @endif


                {{-- Language --}}
                @if(!empty($manuscript->details->language))

                    <div class="col-md-4">

                        <label class="text-muted small">
                            Language
                        </label>

                        <div class="fw-semibold">
                            {{ $manuscript->details->language }}
                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

@endif


{{-- ============================================================
AUTHOR CONTRIBUTION / CRediT
============================================================= --}}
@if($manuscript->authors && $manuscript->authors->count())

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-semibold">
            Author Contributions
        </h5>

        <small class="text-muted">
            CRediT contribution statements
        </small>
    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-bordered align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Author</th>
                        <th>Contributions</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($manuscript->authors as $index => $author)

                        @php
                            $contribution = null;

                            /*
                             * Adjust this relationship name if your
                             * ManuscriptAuthor model uses another name.
                             */
                            if ($author->relationLoaded('contribution')) {
                                $contribution = $author->contribution;
                            } elseif (method_exists($author, 'contribution')) {
                                $contribution = $author->contribution;
                            }
                        @endphp

                        <tr>

                            {{-- Serial --}}
                            <td>
                                {{ $index + 1 }}
                            </td>

                            {{-- Author --}}
                            <td style="min-width: 180px;">

                                <div class="fw-semibold">
                                    {{ $author->name
                                        ?? $author->full_name
                                        ?? trim(
                                            ($author->first_name ?? '') . ' ' .
                                            ($author->middle_name ?? '') . ' ' .
                                            ($author->last_name ?? '')
                                        )
                                        ?: 'Author ' . ($index + 1) }}
                                </div>

                                @if(!empty($author->email))

                                    <small class="text-muted">
                                        {{ $author->email }}
                                    </small>

                                @endif

                            </td>

                            {{-- Contributions --}}
                            <td>

                                @if($contribution)

                                    @php

                                        $roles = [
                                            'conceptualization' =>
                                                'Conceptualization',

                                            'methodology' =>
                                                'Methodology',

                                            'software' =>
                                                'Software',

                                            'validation' =>
                                                'Validation',

                                            'formal_analysis' =>
                                                'Formal Analysis',

                                            'investigation' =>
                                                'Investigation',

                                            'resources' =>
                                                'Resources',

                                            'data_curation' =>
                                                'Data Curation',

                                            'writing_original_draft' =>
                                                'Writing – Original Draft',

                                            'writing_review_editing' =>
                                                'Writing – Review & Editing',

                                            'visualization' =>
                                                'Visualization',

                                            'supervision' =>
                                                'Supervision',

                                            'project_administration' =>
                                                'Project Administration',

                                            'funding_acquisition' =>
                                                'Funding Acquisition',
                                        ];

                                        $activeRoles = [];

                                        foreach ($roles as $field => $label) {

                                            if ((int) ($contribution->{$field} ?? 0) === 1) {
                                                $activeRoles[] = $label;
                                            }

                                        }

                                    @endphp


                                    @if(count($activeRoles))

                                        <div class="d-flex flex-wrap gap-2">

                                            @foreach($activeRoles as $role)

                                                <span class="badge bg-light text-dark border px-3 py-2">
                                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                                    {{ $role }}
                                                </span>

                                            @endforeach

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            No contributions specified.
                                        </span>

                                    @endif

                                @else

                                    <span class="text-muted">
                                        No contribution information found.
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

{{-- ============================================================
    CORRESPONDING AUTHOR
============================================================= --}}
@php
    $correspondingAuthor = null;

    if ($manuscript->authors) {

        $correspondingAuthor = $manuscript->authors->first(
            fn ($author) =>
                ($author->is_corresponding ?? false) ||
                ($author->corresponding ?? false)
        );

    }
@endphp

@if($correspondingAuthor)

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-semibold">
                Corresponding Author
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <label class="text-muted small">
                        Name
                    </label>

                    <div class="fw-semibold">

                        {{ $correspondingAuthor->name
                            ?? $correspondingAuthor->full_name
                            ?? 'N/A' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Email
                    </label>

                    <div class="fw-semibold">

                        {{ $correspondingAuthor->email ?? 'N/A' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Phone
                    </label>

                    <div class="fw-semibold">

                        {{ $correspondingAuthor->phone
                            ?? $correspondingAuthor->mobile
                            ?? 'N/A' }}

                    </div>

                </div>

            </div>

        </div>

    </div>

@endif



{{-- ============================================================
    SUBMITTED FILES
============================================================= --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white py-3">

        <h5 class="mb-0 fw-semibold">
            Submitted Files
        </h5>

    </div>

    <div class="card-body p-0">

        @if($manuscript->files && $manuscript->files->count())

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                File Type
                            </th>

                            <th>
                                File Name
                            </th>

                            <th>
                                File Size
                            </th>

                            <th>
                                Uploaded
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($manuscript->files as $file)

                            <tr>

                                {{-- File Type --}}
                                <td>

                                    <span class="badge bg-light text-dark border">

                                        {{ ucwords(
                                            str_replace(
                                                ['_', '-'],
                                                ' ',
                                                $file->file_type
                                                    ?? $file->type
                                                    ?? 'File'
                                            )
                                        ) }}

                                    </span>

                                </td>


                                {{-- File Name --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{ $file->original_name
                                            ?? $file->file_name
                                            ?? $file->name
                                            ?? 'Unnamed file' }}

                                    </div>

                                    @if(!empty($file->mime_type))

                                        <small class="text-muted">
                                            {{ $file->mime_type }}
                                        </small>

                                    @endif

                                </td>


                                {{-- Size --}}
                                <td>

                                    @php
                                        $fileSize = $file->file_size
                                            ?? $file->size
                                            ?? null;
                                    @endphp

                                    @if($fileSize)

                                        @if($fileSize > 1048576)

                                            {{ number_format($fileSize / 1048576, 2) }} MB

                                        @elseif($fileSize > 1024)

                                            {{ number_format($fileSize / 1024, 2) }} KB

                                        @else

                                            {{ number_format($fileSize) }} bytes

                                        @endif

                                    @else

                                        N/A

                                    @endif

                                </td>


                                {{-- Uploaded --}}
                                <td>

                                    {{ $file->created_at
                                        ? $file->created_at->format('d M Y, h:i A')
                                        : 'N/A' }}

                                </td>


                                {{-- Action --}}
                                <td class="text-end">

                                    @php
                                        $filePath =
                                            $file->file_path
                                            ?? $file->path
                                            ?? $file->stored_path
                                            ?? null;
                                    @endphp

                                    @if($filePath)

                                        <a href="{{ asset('storage/' . ltrim($filePath, '/')) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-primary">

                                            <i class="bi bi-eye"></i>
                                            View

                                        </a>

                                        <a href="{{ asset('storage/' . ltrim($filePath, '/')) }}"
                                           download
                                           class="btn btn-sm btn-outline-success">

                                            <i class="bi bi-download"></i>
                                            Download

                                        </a>

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

            <div class="p-4 text-muted">

                <i class="bi bi-file-earmark"></i>
                No files have been submitted.

            </div>

        @endif

    </div>

</div>

{{-- ============================================================
ETHICAL INFORMATION
============================================================= --}}
@if($manuscript->ethicalInformation)

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white py-3">

        <h5 class="mb-0 fw-semibold">
            Ethical Approval / Research Ethics
        </h5>

    </div>

    <div class="card-body">

        <div class="row g-4">

            @foreach($manuscript->ethicalInformation->getAttributes() as $key => $value)

                @if(!in_array($key, [
                    'id',
                    'manuscript_id',
                    'created_at',
                    'updated_at'
                ]) && !is_null($value))

                    <div class="col-md-6">

                        <label class="text-muted small">
                            {{ ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $key
                                )
                            ) }}
                        </label>

                        <div class="fw-semibold">

                            @if(
                                $value === true ||
                                $value === 1 ||
                                $value === '1'
                            )

                                <span class="badge bg-success px-3 py-2">
                                    Yes
                                </span>

                            @elseif(
                                $value === false ||
                                $value === 0 ||
                                $value === '0'
                            )

                                <span class="badge bg-danger px-3 py-2">
                                    No
                                </span>

                            @else

                                <span style="white-space: pre-line;">
                                    {{ $value }}
                                </span>

                            @endif

                        </div>

                    </div>

                @endif

            @endforeach

        </div>

    </div>

</div>


@endif

{{-- ============================================================
CONFLICT OF INTEREST
============================================================= --}}
@if($manuscript->conflictOfInterest)

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white py-3">

        <h5 class="mb-0 fw-semibold">
            Conflict of Interest
        </h5>

    </div>

    <div class="card-body">

        <div class="row g-4">

            @foreach($manuscript->conflictOfInterest->getAttributes() as $key => $value)

                @if(!in_array($key, [
                    'id',
                    'manuscript_id',
                    'created_at',
                    'updated_at'
                ]) && !is_null($value))

                    <div class="col-md-6">

                        <label class="text-muted small">
                            {{ ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $key
                                )
                            ) }}
                        </label>

                        <div class="fw-semibold">

                            @if(
                                $value === true ||
                                $value === 1 ||
                                $value === '1'
                            )

                                <span class="badge bg-success px-3 py-2">
                                    Yes
                                </span>

                            @elseif(
                                $value === false ||
                                $value === 0 ||
                                $value === '0'
                            )

                                <span class="badge bg-danger px-3 py-2">
                                    No
                                </span>

                            @else

                                <span style="white-space: pre-line;">
                                    {{ $value }}
                                </span>

                            @endif

                        </div>

                    </div>

                @endif

            @endforeach

        </div>

    </div>

</div>

@endif
{{-- ============================================================
DATA AVAILABILITY
============================================================= --}}
@if($manuscript->dataAvailability)

<div class="card border-0 shadow-sm mb-4">

<div class="card-header bg-white py-3">

    <h5 class="mb-0 fw-semibold">
        Data Availability Statement
    </h5>

</div>

<div class="card-body">

    <div class="row g-4">

        @foreach($manuscript->dataAvailability->getAttributes() as $key => $value)

            @if(!in_array($key, [
                'id',
                'manuscript_id',
                'created_at',
                'updated_at'
            ]) && !is_null($value))

                <div class="col-md-6">

                    <label class="text-muted small">
                        {{ ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $key
                            )
                        ) }}
                    </label>

                    <div class="fw-semibold">

                        @if(
                            $value === true ||
                            $value === 1 ||
                            $value === '1'
                        )

                            <span class="badge bg-success px-3 py-2">
                                Yes
                            </span>

                        @elseif(
                            $value === false ||
                            $value === 0 ||
                            $value === '0'
                        )

                            <span class="badge bg-danger px-3 py-2">
                                No
                            </span>

                        @else

                            <span style="white-space: pre-line;">
                                {{ $value }}
                            </span>

                        @endif

                    </div>

                </div>

            @endif

        @endforeach

    </div>

</div>

</div>

@endif

{{-- ============================================================
ACKNOWLEDGEMENT
============================================================= --}}
@if($manuscript->acknowledgement)

<div class="card border-0 shadow-sm mb-4">

<div class="card-header bg-white py-3">

    <h5 class="mb-0 fw-semibold">
        Acknowledgement
    </h5>

</div>

<div class="card-body">

    <div class="row g-4">

        @foreach($manuscript->acknowledgement->getAttributes() as $key => $value)

            @if(!in_array($key, [
                'id',
                'manuscript_id',
                'created_at',
                'updated_at'
            ]) && !is_null($value))

                <div class="col-md-6">

                    <label class="text-muted small">
                        {{ ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $key
                            )
                        ) }}
                    </label>

                    <div class="fw-semibold">

                        @if(
                            $value === true ||
                            $value === 1 ||
                            $value === '1'
                        )

                            <span class="badge bg-success px-3 py-2">
                                Yes
                            </span>

                        @elseif(
                            $value === false ||
                            $value === 0 ||
                            $value === '0'
                        )

                            <span class="badge bg-danger px-3 py-2">
                                No
                            </span>

                        @else

                            <span style="white-space: pre-line;">
                                {{ $value }}
                            </span>

                        @endif

                    </div>

                </div>

            @endif

        @endforeach

    </div>

</div>

</div>

@endif


{{-- ============================================================
    SUBMISSION CHECKLIST
============================================================= --}}
@if($manuscript->checklist)

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-semibold">
                Submission Checklist
            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th style="width:70px;">
                                #
                            </th>

                            <th>
                                Checklist Item
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Remarks
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @if($manuscript->checklist instanceof \Illuminate\Database\Eloquent\Collection)

                            @foreach($manuscript->checklist as $index => $item)

                                <tr>

                                    <td>
                                        {{ $index + 1 }}
                                    </td>

                                    <td>

                                        {{ $item->item
                                            ?? $item->name
                                            ?? $item->check_name
                                            ?? 'Checklist Item' }}

                                    </td>

                                    <td>

                                        @php
                                            $checkStatus =
                                                $item->status
                                                ?? $item->checked
                                                ?? $item->result
                                                ?? null;
                                        @endphp

                                        @if(
                                            $checkStatus === true ||
                                            $checkStatus === 1 ||
                                            $checkStatus === 'yes' ||
                                            $checkStatus === 'checked' ||
                                            $checkStatus === 'pass'
                                        )

                                            <span class="badge bg-success">
                                                Yes
                                            </span>

                                        @elseif(
                                            $checkStatus === false ||
                                            $checkStatus === 0 ||
                                            $checkStatus === 'no'
                                        )

                                            <span class="badge bg-danger">
                                                No
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                {{ $checkStatus ?? 'N/A' }}
                                            </span>

                                        @endif

                                    </td>

                                    <td style="white-space: pre-line;">

                                        {{ $item->remarks
                                            ?? $item->comment
                                            ?? '—' }}

                                    </td>

                                </tr>

                            @endforeach

                        @else

                            @foreach($manuscript->checklist->getAttributes() as $key => $value)

                                @if(!in_array($key, [
                                    'id',
                                    'manuscript_id',
                                    'created_at',
                                    'updated_at'
                                ]) && !is_null($value))

                                    <tr>

                                        <td>—</td>

                                        <td>
                                            {{ ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $key
                                                )
                                            ) }}
                                        </td>

                                        <td>

                                           @if($value === 1 || $value === '1')

                                            <span class="badge bg-success">
                                                Yes
                                            </span>

                                        @elseif($value === 0 || $value === '0')

                                            <span class="badge bg-danger">
                                                No
                                            </span>

                                        @else

                                            {{ $value }}

                                        @endif

                                        </td>

                                        <td>—</td>

                                    </tr>

                                @endif

                            @endforeach

                        @endif

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endif



{{-- ============================================================
    TECHNICAL CHECK HISTORY
============================================================= --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white py-3">

        <h5 class="mb-0 fw-semibold">
            Technical Check History
        </h5>

    </div>

    <div class="card-body p-0">

        @if($manuscript->technicalChecks &&
            $manuscript->technicalChecks->count())

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Check No.
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Assigned To
                            </th>

                            <th>
                                Started
                            </th>

                            <th>
                                Completed
                            </th>

                            <th>
                                Comments
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($manuscript->technicalChecks as $technicalCheck)

                            <tr>

                                <td>

                                    <span class="badge bg-light text-dark border">
                                        #{{ $technicalCheck->check_number }}
                                    </span>

                                </td>

                                <td>

                                    @php
                                        $technicalStatus =
                                            $technicalCheck->status ?? 'unknown';

                                        $technicalClass = match(
                                            $technicalStatus
                                        ) {

                                            'in_progress'
                                                => 'bg-warning text-dark',

                                            'passed'
                                                => 'bg-success',

                                            'correction_required'
                                                => 'bg-danger',

                                            default
                                                => 'bg-secondary',

                                        };
                                    @endphp

                                    <span class="badge {{ $technicalClass }}">

                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $technicalStatus
                                            )
                                        ) }}

                                    </span>

                                </td>

                                <td>

                                    {{ $technicalCheck->assigned_to ?? 'N/A' }}

                                </td>

                                <td>

                                    @if($technicalCheck->started_at)

                                        {{ $technicalCheck->started_at->format('d M Y, h:i A') }}

                                    @else

                                        —

                                    @endif

                                </td>

                                <td>

                                    @if($technicalCheck->completed_at)

                                        {{ $technicalCheck->completed_at->format('d M Y, h:i A') }}

                                    @else

                                        —

                                    @endif

                                </td>

                                <td style="white-space: pre-line;">

                                    {{ $technicalCheck->comments ?? '—' }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="p-4 text-muted">

                No technical check has been recorded for this manuscript.

            </div>

        @endif

    </div>

</div>



{{-- ============================================================
    ACTIONS
============================================================= --}}
<div class="card border-0 shadow-sm mb-5">

    <div class="card-header bg-white py-3">

        <h5 class="mb-0 fw-semibold">
            Editorial Actions
        </h5>

    </div>

    <div class="card-body">

        <div class="d-flex flex-wrap gap-2">

            @can('technical_check.view')

                <a href="{{ route(
                    'admin.manuscripts.technical-review.index'
                ) }}"
                   class="btn btn-outline-primary">

                    Technical Review

                </a>

            @endcan


            @can('technical_check.perform')

                <a href="{{ route(
                    'admin.manuscripts.technical-check',
                    $manuscript
                ) }}"
                   class="btn btn-primary">

                    Open Technical Check

                </a>

            @endcan

        </div>

    </div>

</div>

</div>

@endsection
