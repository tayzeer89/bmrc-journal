@extends('admin.layouts.app')

@section('title', 'Technical Check')

@section('page_title', 'Technical Check')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex flex-column flex-md-row
                justify-content-between
                align-items-md-center
                gap-3 mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Technical Check
            </h3>

            <div class="text-muted">

                {{ $manuscript->manuscript_no }}

                @if(isset($technicalCheck))

                    <span class="mx-1">•</span>

                    Check #{{ $technicalCheck->check_number }}

                @endif

            </div>

        </div>


        <div>

            <a href="{{ route('admin.manuscripts.show', $manuscript) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Back to Manuscript

            </a>

        </div>

    </div>


    {{-- =========================================================
         MANUSCRIPT INFORMATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-lg-8">

                    <small class="text-muted">
                        Manuscript Title
                    </small>

                    <h5 class="fw-semibold mt-1 mb-0">

                        {{ $manuscript->title }}

                    </h5>

                </div>


                <div class="col-md-4 col-lg-2">

                    <small class="text-muted">
                        Article Type
                    </small>

                    <div class="fw-semibold mt-1">

                        {{ $manuscript->article_type ?? '—' }}

                    </div>

                </div>


                <div class="col-md-4 col-lg-2">

                    <small class="text-muted">
                        Submitted
                    </small>

                    <div class="fw-semibold mt-1">

                        {{ optional($manuscript->submitted_at)->format('d M Y') ?? '—' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         NO TECHNICAL CHECK
    ========================================================== --}}

    @if(!$technicalCheck)

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <div class="mb-3">

                    <i class="bi bi-clipboard-check"
                       style="font-size: 50px;">
                    </i>

                </div>

                <h5>
                    Technical Check Not Started
                </h5>

                <p class="text-muted">

                    Start the technical check to review
                    this manuscript against the BMRC checklist.

                </p>


                @can('technical_check.perform')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.manuscripts.technical-check.start',
                            $manuscript
                        ) }}">

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="bi bi-play-circle me-1"></i>

                            Start Technical Check

                        </button>

                    </form>

                @endcan

            </div>

        </div>

    @else

        {{-- =====================================================
             STATUS
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-md-6">

                        <small class="text-muted">
                            Technical Check Status
                        </small>

                        <div class="mt-1">

                            @php

                                $badge = match(
                                    $technicalCheck->status
                                ) {

                                    'pending' =>
                                        'bg-secondary',

                                    'in_progress' =>
                                        'bg-primary',

                                    'correction_required' =>
                                        'bg-warning text-dark',

                                    'passed' =>
                                        'bg-success',

                                    'failed' =>
                                        'bg-danger',

                                    default =>
                                        'bg-secondary',

                                };

                            @endphp


                            <span class="badge {{ $badge }} px-3 py-2">

                                {{ ucwords(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $technicalCheck->status
                                    )
                                ) }}

                            </span>

                        </div>

                    </div>


                    <div class="col-md-6 text-md-end mt-3 mt-md-0">

                        @if($technicalCheck->started_at)

                            <small class="text-muted">

                                Started:
                                {{ $technicalCheck->started_at->format('d M Y H:i') }}

                            </small>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             CHECKLIST
        ====================================================== --}}

        <form
            method="POST"
            action="{{ route(
                'admin.manuscripts.technical-check.update',
                [
                    $manuscript,
                    $technicalCheck
                ]
            ) }}">

            @csrf

            @method('PUT')


            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="bi bi-list-check me-2"></i>

                        Technical Checklist

                    </h5>

                </div>


                <div class="card-body p-0">

                    @foreach($technicalCheck->items as $item)

                        <div class="border-bottom p-3">

                            <div class="row align-items-start g-3">

                                <div class="col-md-5">

                                    <div class="fw-semibold">

                                        {{ $loop->iteration }}.
                                        {{ $item->check_name }}

                                    </div>

                                </div>


                                <div class="col-md-3">

                                    <select
                                        name="items[{{ $item->id }}][result]"
                                        class="form-select">

                                        <option value="pass"
                                            @selected($item->result === 'pass')>

                                            Pass

                                        </option>

                                        <option value="fail"
                                            @selected($item->result === 'fail')>

                                            Fail

                                        </option>

                                        <option value="na"
                                            @selected($item->result === 'na')>

                                            Not Applicable

                                        </option>

                                    </select>

                                </div>


                                <div class="col-md-4">

                                    <textarea
                                        name="items[{{ $item->id }}][comment]"
                                        class="form-control"
                                        rows="2"
                                        placeholder="Comment / observation">{{ $item->comment }}</textarea>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>


            {{-- =================================================
                 GENERAL COMMENTS
            ================================================== --}}

            <div class="card border-0 shadow-sm mt-4">

                <div class="card-body">

                    <label class="form-label fw-semibold">

                        Technical Check Comments

                    </label>

                    <textarea
                        name="comments"
                        rows="4"
                        class="form-control"
                        placeholder="Enter overall technical check comments...">{{ $technicalCheck->comments }}</textarea>

                </div>

            </div>


            {{-- =================================================
                 ACTIONS
            ================================================== --}}

            <div class="d-flex flex-column flex-md-row
                        justify-content-between
                        gap-2 mt-4">


                <button
                    type="submit"
                    class="btn btn-outline-primary">

                    <i class="bi bi-save me-1"></i>

                    Save Checklist

                </button>


                <div class="d-flex flex-column flex-md-row gap-2">


                    @can('technical_check.return')

                        <button
                            type="button"
                            class="btn btn-warning"
                            data-bs-toggle="modal"
                            data-bs-target="#returnAuthorModal">

                            <i class="bi bi-arrow-return-left me-1"></i>

                            Return to Author

                        </button>

                    @endcan


                    @can('technical_check.complete')

                        @if($technicalCheck->status !== 'passed')

                            <button
                                type="submit"
                                formaction="{{ route(
                                    'admin.manuscripts.technical-check.pass',
                                    [
                                        $manuscript,
                                        $technicalCheck
                                    ]
                                ) }}"
                                formmethod="POST"
                                class="btn btn-success">

                                <i class="bi bi-check-circle me-1"></i>

                                Pass Technical Check

                            </button>

                        @endif

                    @endcan

                </div>

            </div>

        </form>

    @endif

</div>


{{-- =============================================================
     RETURN TO AUTHOR MODAL
============================================================== --}}
@if($technicalCheck)

    <div class="modal fade"
         id="returnAuthorModal"
         tabindex="-1"
         aria-labelledby="returnAuthorModalLabel"
         aria-hidden="true">

        <div class="modal-dialog">

            <form
                method="POST"
                action="{{ route(
                    'admin.manuscripts.technical-check.return',
                    [
                        'manuscript' => $manuscript->id,
                        'technicalCheck' => $technicalCheck->id,
                    ]
                ) }}">

                @csrf

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title"
                            id="returnAuthorModalLabel">

                            Return Manuscript to Author

                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                        </button>

                    </div>


                    <div class="modal-body">

                        <div class="alert alert-warning">

                            <i class="bi bi-exclamation-triangle me-2"></i>

                            The manuscript will be returned to the
                            author for technical correction.

                        </div>


                        <div class="mb-3">

                            <label
                                class="form-label fw-semibold">

                                Correction Required
                                <span class="text-danger">*</span>

                            </label>

                            <textarea
                                name="comments"
                                rows="5"
                                class="form-control"
                                required
                                placeholder="Explain what the author needs to correct..."></textarea>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                            Cancel

                        </button>


                        <button
                            type="submit"
                            class="btn btn-warning">

                            <i class="bi bi-arrow-return-left me-1"></i>

                            Return to Author

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

@endif
@endsection