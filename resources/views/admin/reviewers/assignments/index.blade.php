@extends('admin.layouts.app')

@section('title', 'Reviewer Assignments')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Reviewer Assignments</h4>

            <p class="text-muted mb-0">
                View and manage manuscript reviewer assignments.
            </p>
        </div>

        <div class="d-flex gap-2">

            @can('reviewer.view')
                <a
                    href="{{ route('admin.reviewers.index') }}"
                    class="btn btn-outline-secondary"
                >
                    Reviewer Pool
                </a>
            @endcan

            @can('reviewer.search')
                <a
                    href="{{ route('admin.reviewers.search') }}"
                    class="btn btn-outline-primary"
                >
                    Search Reviewer
                </a>
            @endcan

        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <div class="card h-100 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Total Assignments
                    </div>

                    <h3 class="mb-0">
                        {{ $statistics['total'] ?? 0 }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- Assigned --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <div class="card h-100 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Assigned
                    </div>

                    <h3 class="mb-0 text-warning">
                        {{ $statistics['assigned'] ?? 0 }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- Accepted --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <div class="card h-100 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Accepted
                    </div>

                    <h3 class="mb-0 text-success">
                        {{ $statistics['accepted'] ?? 0 }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- Declined --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <div class="card h-100 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Declined
                    </div>

                    <h3 class="mb-0 text-danger">
                        {{ $statistics['declined'] ?? 0 }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- Completed --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <div class="card h-100 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Completed
                    </div>

                    <h3 class="mb-0 text-primary">
                        {{ $statistics['completed'] ?? 0 }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- Removed --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <div class="card h-100 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Removed
                    </div>

                    <h3 class="mb-0 text-secondary">
                        {{ $statistics['removed'] ?? 0 }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FILTER SECTION
    ========================================================== --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <strong>Search & Filter</strong>

        </div>

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.reviewer-assignments.index') }}"
            >

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-lg-5 col-md-6">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Reviewer name, email or manuscript..."
                        >

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            Assignment Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="assigned"
                                @selected(request('status') === 'assigned')
                            >
                                Assigned
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

                            <option
                                value="completed"
                                @selected(request('status') === 'completed')
                            >
                                Completed
                            </option>

                            <option
                                value="removed"
                                @selected(request('status') === 'removed')
                            >
                                Removed
                            </option>

                        </select>

                    </div>


                    {{-- Filter --}}
                    <div class="col-lg-2 col-md-6 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Filter
                        </button>

                    </div>


                    {{-- Reset --}}
                    <div class="col-lg-2 col-md-6 d-flex align-items-end">

                        <a
                            href="{{ route('admin.reviewer-assignments.index') }}"
                            class="btn btn-outline-secondary w-100"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        ASSIGNMENT LIST
    ========================================================== --}}
    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div>

                <strong>Assignment List</strong>

                <div class="small text-muted">
                    Reviewers assigned to manuscripts
                </div>

            </div>


            @if(isset($assignments) && method_exists($assignments, 'total'))

                <span class="badge bg-primary">

                    {{ $assignments->total() }}

                    {{ $assignments->total() === 1 ? 'Assignment' : 'Assignments' }}

                </span>

            @endif

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th style="width: 70px;">
                                #
                            </th>

                            <th>
                                Manuscript
                            </th>

                            <th>
                                Reviewer
                            </th>

                            <th>
                                Assigned Date
                            </th>

                            <th>
                                Due Date
                            </th>

                            <th>
                                Status
                            </th>

                            <th style="width: 220px;">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(($assignments ?? []) as $assignment)

                            <tr>

                                {{-- ID --}}
                                <td>

                                    {{ $assignment->id }}

                                </td>


                                {{-- Manuscript --}}
                                <td>

                                    @if(isset($assignment->manuscript) && $assignment->manuscript)

                                        <div>

                                            <strong>

                                                {{
                                                    $assignment->manuscript->manuscript_number
                                                    ?? $assignment->manuscript->submission_number
                                                    ?? 'Manuscript #'.$assignment->manuscript_id
                                                }}

                                            </strong>

                                        </div>


                                        @if(!empty($assignment->manuscript->title))

                                            <div
                                                class="small text-muted mt-1"
                                                style="max-width: 350px;"
                                            >

                                                {{
                                                    \Illuminate\Support\Str::limit(
                                                        $assignment->manuscript->title,
                                                        70
                                                    )
                                                }}

                                            </div>

                                        @endif

                                    @else

                                        <span>
                                            Manuscript #{{ $assignment->manuscript_id ?? '—' }}
                                        </span>

                                    @endif

                                </td>


                                {{-- Reviewer --}}
                                <td>

                                    @if(isset($assignment->reviewer) && $assignment->reviewer)

                                        <div>

                                            <strong>
                                                {{ $assignment->reviewer->name }}
                                            </strong>

                                        </div>

                                        <div class="small text-muted">

                                            {{ $assignment->reviewer->email }}

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            Reviewer unavailable
                                        </span>

                                    @endif

                                </td>


                                {{-- Assigned Date --}}
                                <td>

                                    @if(!empty($assignment->assigned_at))

                                        {{
                                            \Illuminate\Support\Carbon::parse(
                                                $assignment->assigned_at
                                            )->format('d M Y')
                                        }}

                                    @elseif(!empty($assignment->created_at))

                                        {{
                                            \Illuminate\Support\Carbon::parse(
                                                $assignment->created_at
                                            )->format('d M Y')
                                        }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Due Date --}}
                                <td>

                                    @if(!empty($assignment->due_date))

                                        {{
                                            \Illuminate\Support\Carbon::parse(
                                                $assignment->due_date
                                            )->format('d M Y')
                                        }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @php
                                        $assignmentStatus =
                                            $assignment->status ?? 'assigned';
                                    @endphp


                                    @switch($assignmentStatus)

                                        @case('accepted')

                                            <span class="badge bg-success">
                                                Accepted
                                            </span>

                                            @break


                                        @case('declined')

                                            <span class="badge bg-danger">
                                                Declined
                                            </span>

                                            @break


                                        @case('completed')

                                            <span class="badge bg-primary">
                                                Completed
                                            </span>

                                            @break


                                        @case('removed')

                                            <span class="badge bg-secondary">
                                                Removed
                                            </span>

                                            @break


                                        @case('cancelled')

                                            <span class="badge bg-dark">
                                                Cancelled
                                            </span>

                                            @break


                                        @default

                                            <span class="badge bg-warning text-dark">
                                                Assigned
                                            </span>

                                    @endswitch

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="d-flex flex-wrap gap-1">

                                        {{-- View --}}
                                        @can('reviewer.assign')

                                            <a
                                                href="{{ route(
                                                    'admin.reviewer-assignments.show',
                                                    ['assignment' => $assignment->id]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>

                                        @endcan


                                        {{-- Reassign --}}
                                        @can('reviewer.reassign')

                                            @if(
                                                !in_array(
                                                    $assignmentStatus,
                                                    [
                                                        'completed',
                                                        'removed',
                                                        'cancelled'
                                                    ]
                                                )
                                            )

                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-warning"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#reassignModal{{ $assignment->id }}"
                                                >
                                                    Reassign
                                                </button>

                                            @endif

                                        @endcan


                                        {{-- Remove --}}
                                        @can('reviewer.remove')

                                            @if(
                                                !in_array(
                                                    $assignmentStatus,
                                                    [
                                                        'completed',
                                                        'removed',
                                                        'cancelled'
                                                    ]
                                                )
                                            )

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.reviewer-assignments.remove',
                                                        ['assignment' => $assignment->id]
                                                    ) }}"
                                                    onsubmit="return confirm(
                                                        'Are you sure you want to remove this reviewer assignment?'
                                                    );"
                                                >

                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                    >
                                                        Remove
                                                    </button>

                                                </form>

                                            @endif

                                        @endcan

                                    </div>

                                </td>

                            </tr>


                            {{-- =====================================================
                                REASSIGN REVIEWER MODAL
                            ====================================================== --}}
                            @can('reviewer.reassign')

                                @if(
                                    !in_array(
                                        $assignmentStatus,
                                        [
                                            'completed',
                                            'removed',
                                            'cancelled'
                                        ]
                                    )
                                )

                                    <div
                                        class="modal fade"
                                        id="reassignModal{{ $assignment->id }}"
                                        tabindex="-1"
                                        aria-hidden="true"
                                    >

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.reviewer-assignments.reassign',
                                                        ['assignment' => $assignment->id]
                                                    ) }}"
                                                >

                                                    @csrf
                                                    @method('PATCH')


                                                    <div class="modal-header">

                                                        <h5 class="modal-title">
                                                            Reassign Reviewer
                                                        </h5>

                                                        <button
                                                            type="button"
                                                            class="btn-close"
                                                            data-bs-dismiss="modal"
                                                            aria-label="Close"
                                                        ></button>

                                                    </div>


                                                    <div class="modal-body">

                                                        {{-- Current Reviewer --}}
                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Current Reviewer
                                                            </label>

                                                            <div class="form-control bg-light">

                                                                @if(
                                                                    isset($assignment->reviewer)
                                                                    && $assignment->reviewer
                                                                )

                                                                    <strong>
                                                                        {{ $assignment->reviewer->name }}
                                                                    </strong>

                                                                    <div class="small text-muted">
                                                                        {{ $assignment->reviewer->email }}
                                                                    </div>

                                                                @else

                                                                    —

                                                                @endif

                                                            </div>

                                                        </div>


                                                        {{-- New Reviewer --}}
                                                        <div class="mb-3">

                                                            <label class="form-label">

                                                                New Reviewer ID

                                                                <span class="text-danger">
                                                                    *
                                                                </span>

                                                            </label>

                                                            <input
                                                                type="number"
                                                                name="reviewer_id"
                                                                class="form-control"
                                                                min="1"
                                                                required
                                                            >

                                                            <div class="form-text">

                                                                Enter the ID of the approved reviewer.

                                                            </div>

                                                        </div>


                                                        {{-- Remarks --}}
                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Reassignment Remarks
                                                            </label>

                                                            <textarea
                                                                name="remarks"
                                                                class="form-control"
                                                                rows="4"
                                                                maxlength="3000"
                                                                placeholder="Reason for reassignment..."
                                                            ></textarea>

                                                        </div>

                                                    </div>


                                                    <div class="modal-footer">

                                                        <button
                                                            type="button"
                                                            class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal"
                                                        >
                                                            Cancel
                                                        </button>

                                                        <button
                                                            type="submit"
                                                            class="btn btn-warning"
                                                        >
                                                            Reassign Reviewer
                                                        </button>

                                                    </div>

                                                </form>

                                            </div>

                                        </div>

                                    </div>

                                @endif

                            @endcan

                        @empty

                            {{-- =====================================================
                                EMPTY STATE
                            ====================================================== --}}
                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <div class="mb-2">

                                        <h5>
                                            No Reviewer Assignments Found
                                        </h5>

                                    </div>

                                    <p class="text-muted mb-3">

                                        No reviewer assignments match your current
                                        search or filter.

                                    </p>

                                    @if(request()->filled('search') || request()->filled('status'))

                                        <a
                                            href="{{ route(
                                                'admin.reviewer-assignments.index'
                                            ) }}"
                                            class="btn btn-outline-primary"
                                        >
                                            Clear Filters
                                        </a>

                                    @else

                                        <p class="small text-muted mb-0">

                                            Reviewer assignment should be started
                                            from a specific manuscript.

                                        </p>

                                    @endif

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =========================================================
            PAGINATION
        ========================================================== --}}
        @if(
            isset($assignments)
            && method_exists($assignments, 'hasPages')
            && $assignments->hasPages()
        )

            <div class="card-footer">

                {{ $assignments->links() }}

            </div>

        @endif

    </div>


    {{-- =========================================================
        WORKFLOW INFORMATION
    ========================================================== --}}
    <div class="card shadow-sm mt-4">

        <div class="card-body">

            <h6 class="mb-2">
                Reviewer Assignment Workflow
            </h6>

            <p class="text-muted small mb-0">

                Reviewer assignment is initiated from a specific manuscript.
                The Handling / Associate Editor selects a suitable approved
                reviewer, after which the reviewer invitation and peer-review
                process can proceed.

            </p>

        </div>

    </div>

</div>

@endsection

