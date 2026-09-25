@extends('admin.layouts.app')

@section('title', 'My Assignments')
@section('page_title', 'Handling Editor Assignments')

@section('content')

<div class="container-fluid px-0">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                My Assignments
            </h4>

            <div class="text-muted">
                Manuscripts assigned for editorial assessment
            </div>
        </div>

    </div>


    {{-- =====================================================
         SUMMARY
    ====================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Pending
                    </div>

                    <div class="fs-3 fw-bold text-warning">
                        {{ $pendingCount }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Accepted
                    </div>

                    <div class="fs-3 fw-bold text-success">
                        {{ $acceptedCount }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Declined
                    </div>

                    <div class="fs-3 fw-bold text-danger">
                        {{ $declinedCount }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         SEARCH / FILTER
    ====================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('handling-editor.assignments.index') }}"
            >

                <div class="row g-2">

                    <div class="col-md-6">

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Search manuscript ID or title"
                        >

                    </div>


                    <div class="col-md-3">

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Statuses
                            </option>

                            <option
                                value="pending"
                                @selected(request('status') === 'pending')
                            >
                                Pending
                            </option>

                            <option
                                value="accepted"
                                @selected(request('status') === 'accepted')
                            >
                                Accepted
                            </option>

                            <option
                                value="declined"
                                @selected(request('status') === 'declined')
                            >
                                Declined
                            </option>

                        </select>

                    </div>


                    <div class="col-md-3">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-search"></i>
                            Search
                        </button>

                        <a
                            href="{{ route('handling-editor.assignments.index') }}"
                            class="btn btn-outline-secondary"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
         ASSIGNMENTS
    ====================================================== --}}

    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <strong>
                Assigned Manuscripts
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Manuscript</th>
                            <th>Article Type</th>
                            <th>Assigned</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th width="100">Action</th>
                        </tr>

                    </thead>


                    <tbody>

                    @forelse($assignments as $assignment)

                        @php

                            $statusClass = match(
                                $assignment->status
                            ) {
                                'pending'   => 'warning',
                                'accepted'  => 'success',
                                'declined'  => 'danger',
                                'completed' => 'primary',
                                'cancelled' => 'secondary',
                                'reassigned'=> 'info',
                                default     => 'secondary',
                            };

                            $overdue =
                                $assignment->due_date
                                && $assignment->due_date->isPast()
                                && in_array(
                                    $assignment->status,
                                    ['pending', 'accepted']
                                );

                        @endphp


                        <tr>

                            <td>
                                {{
                                    $assignments->firstItem()
                                    + $loop->index
                                }}
                            </td>


                            <td>

                                <strong>
                                    {{
                                        $assignment
                                            ->manuscript
                                            ->manuscript_id
                                        ?? '#'.$assignment
                                            ->manuscript_id
                                    }}
                                </strong>

                                <div class="small mt-1">
                                    {{
                                        \Illuminate\Support\Str::limit(
                                            $assignment
                                                ->manuscript
                                                ->title
                                                ?? 'N/A',
                                            80
                                        )
                                    }}
                                </div>

                            </td>


                            <td>

                                {{
                                    $assignment
                                        ->manuscript
                                        ->articleType
                                        ->name
                                    ?? 'N/A'
                                }}

                            </td>


                            <td>

                                {{
                                    $assignment->assigned_at
                                        ? $assignment
                                            ->assigned_at
                                            ->format('d M Y')
                                        : 'N/A'
                                }}

                            </td>


                            <td>

                                @if($assignment->due_date)

                                    {{
                                        $assignment
                                            ->due_date
                                            ->format('d M Y')
                                    }}

                                    @if($overdue)

                                        <div>
                                            <span class="badge bg-danger">
                                                Overdue
                                            </span>
                                        </div>

                                    @endif

                                @else

                                    N/A

                                @endif

                            </td>


                            <td>

                                <span
                                    class="badge bg-{{ $statusClass }}"
                                >
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

                            </td>


                            <td>

                                <a
                                    href="{{ route(
                                        'handling-editor.assignments.show',
                                        $assignment->id
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    <i class="bi bi-eye"></i>
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5 text-muted"
                            >

                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>

                                No manuscript assignments found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($assignments->hasPages())

            <div class="card-footer bg-white">

                {{ $assignments->links() }}

            </div>

        @endif

    </div>

</div>

@endsection