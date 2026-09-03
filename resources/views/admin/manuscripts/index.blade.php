@extends('admin.layouts.app')

@section('title', 'Manuscripts')

@section('content')

<div class="container-fluid py-4">

{{-- Header --}}
<div class="mb-4">
    <h2 class="fw-bold">
        Manuscripts
    </h2>

    <p class="text-muted mb-0">
        Manage submitted manuscripts
    </p>
</div>


{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>
    </div>
@endif


{{-- Error Message --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>
    </div>
@endif


{{-- Manuscripts Table --}}
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white py-3">

        <h5 class="mb-0 fw-semibold">
            Submitted Manuscripts
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
                        <th>Journal</th>
                        <th>Submitter</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th class="text-end">Action</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($manuscripts as $manuscript)

                        <tr>

                            {{-- Manuscript ID --}}
                            <td>
                                <span class="fw-semibold">
                                    {{ $manuscript->manuscript_no
                                        ?? $manuscript->id }}
                                </span>
                            </td>


                            {{-- Title --}}
                            <td>

                                <div class="fw-semibold">
                                    {{ $manuscript->title ?? 'Untitled Manuscript' }}
                                </div>

                            </td>


                            {{-- Article Type --}}
                            <td>

                                @if($manuscript->articleType)

                                    {{ $manuscript->articleType->name }}

                                @else

                                    <span class="text-muted">
                                        N/A
                                    </span>

                                @endif

                            </td>


                            {{-- Journal --}}
                            <td>

                                @if($manuscript->journal)

                                    {{ $manuscript->journal->name }}

                                @else

                                    <span class="text-muted">
                                        N/A
                                    </span>

                                @endif

                            </td>


                            {{-- Submitter --}}
                            <td>

                                @if($manuscript->submitter)

                                    {{ $manuscript->submitter->name }}

                                @else

                                    <span class="text-muted">
                                        N/A
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @php

                                    $statusClass = match(
                                        $manuscript->status
                                    ) {

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

                                        'under_review'
                                            => 'bg-info text-dark',

                                        'accepted'
                                            => 'bg-success',

                                        'rejected'
                                            => 'bg-danger',

                                        'draft'
                                            => 'bg-secondary',

                                        default
                                            => 'bg-secondary',

                                    };

                                @endphp

                                <span class="badge {{ $statusClass }}">
                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $manuscript->status ?? 'Unknown'
                                        )
                                    ) }}
                                </span>

                            </td>


                            {{-- Submitted Date --}}
                            <td>

                                @if($manuscript->submitted_at)

                                    {{ $manuscript->submitted_at->format('d M Y') }}

                                    <br>

                                    <small class="text-muted">
                                        {{ $manuscript->submitted_at->format('h:i A') }}
                                    </small>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Action --}}
                            <td class="text-end">

                                <a
                                    href="{{ route(
                                        'admin.manuscripts.show',
                                        $manuscript
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    View

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-5">

                                <div class="text-muted">

                                    <div class="fs-1 mb-3">
                                        📄
                                    </div>

                                    <h6>
                                        No Manuscripts Found
                                    </h6>

                                    <p class="mb-0">
                                        No manuscripts are currently available.
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
    @if($manuscripts->hasPages())

        <div class="card-footer bg-white">

            {{ $manuscripts->links() }}

        </div>

    @endif

</div>

</div>

@endsection
