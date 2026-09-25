@extends('admin.layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-clipboard-check me-2"></i>
                Technical Review
            </h4>

            <p class="text-muted mb-0">
                Technical review queue
            </p>
        </div>

    </div>


    {{-- Flash Messages --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show">
            {{ session('warning') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Technical Review Table --}}

    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Manuscript</th>

                            <th>Article</th>

                            <th>Check</th>

                            <th>Status</th>

                            <th>Stage</th>

                            <th>Assigned To</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($technicalChecks as $technicalCheck)

                        @php

                            $manuscript =
                                $technicalCheck->manuscript;

                            $status =
                                $technicalCheck->status;

                            $stage =
                                $manuscript?->current_stage;

                        @endphp

                        <tr>

                            {{-- Number --}}

                            <td>
                                {{ $technicalChecks->firstItem() + $loop->index }}
                            </td>


                            {{-- Manuscript ID --}}

                            <td>

                                <strong>
                                    {{ $manuscript?->manuscript_id }}
                                </strong>

                                <div class="small text-muted">

                                    Submitted:
                                    {{ optional($manuscript?->submitted_at)->format('d M Y') }}

                                </div>

                            </td>


                            {{-- Article --}}

                            <td>

                                <div class="fw-semibold">

                                    {{ Str::limit(
                                        $manuscript?->title,
                                        60
                                    ) }}

                                </div>

                                <div class="small text-muted">

                                    {{ $manuscript?->articleType?->name }}

                                </div>

                            </td>


                            {{-- Check Number --}}

                            <td>

                                <span class="badge bg-secondary">

                                    Check #{{ $technicalCheck->check_number }}

                                </span>

                            </td>


                            {{-- Technical Check Status --}}

                            <td>

                                @switch($status)

                                    @case('pending')

                                        <span class="badge bg-secondary">
                                            Pending
                                        </span>

                                        @break

                                    @case('in_progress')

                                        <span class="badge bg-primary">
                                            In Progress
                                        </span>

                                        @break

                                    @case('correction_required')

                                        <span class="badge bg-warning text-dark">
                                            Correction Required
                                        </span>

                                        @break

                                    @case('passed')

                                        <span class="badge bg-success">
                                            Passed
                                        </span>

                                        @break

                                    @case('failed')

                                        <span class="badge bg-danger">
                                            Failed
                                        </span>

                                        @break

                                    @default

                                        <span class="badge bg-secondary">
                                            {{ ucfirst($status) }}
                                        </span>

                                @endswitch

                            </td>


                            {{-- Manuscript Current Stage --}}

                            <td>

                                @if($stage)

                                    <span class="badge bg-light text-dark border">

                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $stage
                                            )
                                        ) }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Assigned --}}

                            <td>

                                @if($technicalCheck->assignedUser)

                                    {{ $technicalCheck->assignedUser->name }}

                                @else

                                    <span class="text-muted">
                                        Not Assigned
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}

                            <td>

                                @if($status === 'pending')

                                    <a
                                        href="{{ route(
                                            'admin.manuscripts.technical-check',
                                            $manuscript
                                        ) }}"
                                        class="btn btn-sm btn-primary">

                                        <i class="bi bi-play-circle me-1"></i>
                                        Start

                                    </a>

                                @elseif($status === 'in_progress')

                                    <a
                                        href="{{ route(
                                            'admin.manuscripts.technical-check',
                                            $manuscript
                                        ) }}"
                                        class="btn btn-sm btn-primary">

                                        <i class="bi bi-arrow-right-circle me-1"></i>
                                        Continue

                                    </a>

                                @elseif($status === 'correction_required')

                                    <a
                                        href="{{ route(
                                            'admin.manuscripts.technical-check',
                                            $manuscript
                                        ) }}"
                                        class="btn btn-sm btn-warning">

                                        <i class="bi bi-arrow-repeat me-1"></i>
                                        Review Correction

                                    </a>

                                @else

                                    <a
                                        href="{{ route(
                                            'admin.manuscripts.technical-check',
                                            $manuscript
                                        ) }}"
                                        class="btn btn-sm btn-outline-secondary">

                                        <i class="bi bi-eye me-1"></i>
                                        View

                                    </a>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-5 text-muted">

                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>

                                No technical reviews found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($technicalChecks->hasPages())

            <div class="card-footer bg-white">

                {{ $technicalChecks->links('pagination::bootstrap-4') }}

            </div>

        @endif

    </div>

</div>

@endsection