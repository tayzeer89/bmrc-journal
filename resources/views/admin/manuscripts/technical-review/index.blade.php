@extends('admin.layouts.app')

@section('title', 'Technical Review')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="mb-4">
        <h2 class="fw-bold">
            Technical Review
        </h2>

        <p class="text-muted mb-0">
            Manage manuscript technical checks
        </p>
    </div>


    {{-- Messages --}}
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


    {{-- Technical Review Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-semibold">
                Manuscripts for Technical Review
            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Article Type</th>
                            <th>Check No.</th>
                            <th>Status</th>
                            <th>Started</th>
                            <th class="text-end">Action</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse($technicalChecks as $technicalCheck)

                            @php
                                $manuscript = $technicalCheck->manuscript;
                            @endphp

                            <tr>

                                {{-- ID --}}
                                <td>
                                    {{ $manuscript?->manuscript_no
                                        ?? $manuscript?->id
                                        ?? 'N/A' }}
                                </td>


                                {{-- Title --}}
                                <td>

                                    @if($manuscript)

                                        <div class="fw-semibold">
                                            {{ $manuscript->title }}
                                        </div>

                                    @else

                                        <span class="text-muted">
                                            Manuscript not found
                                        </span>

                                    @endif

                                </td>


                                {{-- Article Type --}}
                                <td>

                                    @if($manuscript?->articleType)

                                        {{ $manuscript->articleType->name }}

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Check Number --}}
                                <td>

                                    <span class="badge bg-light text-dark border">
                                        #{{ $technicalCheck->check_number }}
                                    </span>

                                </td>


                                {{-- Status --}}
                                <td>

                                    @php

                                        $statusClass = match(
                                            $technicalCheck->status
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

                                    <span class="badge {{ $statusClass }}">

                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $technicalCheck->status
                                            )
                                        ) }}

                                    </span>

                                </td>


                                {{-- Started --}}
                                <td>

                                    @if($technicalCheck->started_at)

                                        {{ $technicalCheck->started_at->format('d M Y') }}

                                        <br>

                                        <small class="text-muted">
                                            {{ $technicalCheck->started_at->format('h:i A') }}
                                        </small>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td class="text-end">

                                    @if($manuscript)

                                        <a
                                            href="{{ route(
                                                'admin.manuscripts.technical-check',
                                                $manuscript
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary">

                                            Review

                                        </a>

                                    @else

                                        <button
                                            class="btn btn-sm btn-outline-secondary"
                                            disabled>

                                            Review

                                        </button>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <div class="fs-1 mb-3">
                                            📋
                                        </div>

                                        <h6>
                                            No Technical Checks Found
                                        </h6>

                                        <p class="mb-0">
                                            No manuscripts are currently
                                            available for technical review.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($technicalChecks->hasPages())

            <div class="card-footer bg-white">

                {{ $technicalChecks->links() }}

            </div>

        @endif

    </div>

</div>

@endsection