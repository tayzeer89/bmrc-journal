@extends('admin.layouts.app')

@section('title', 'Reviewer Pool')

@section('content')

<div class="container-fluid">

    {{-- ================================================================
        HEADER
    ================================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Reviewer Pool
            </h4>

            <p class="text-muted mb-0">
                View and manage all registered reviewers.
            </p>

        </div>


        <div class="d-flex gap-2">

            @can('reviewer.search')

                <a
                    href="{{ route('admin.reviewers.search') }}"
                    class="btn btn-outline-primary"
                >
                    Search Reviewer
                </a>

            @endcan


            @can('reviewer.create')

                <a
                    href="{{ route('admin.reviewers.create') }}"
                    class="btn btn-primary"
                >
                    Add Reviewer
                </a>

            @endcan

        </div>

    </div>


    {{-- ================================================================
        STATISTICS
    ================================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-lg-2 col-md-4 col-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Reviewers
                    </div>

                    <h3 class="mb-0">
                        {{ $statistics['total'] }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-lg-2 col-md-4 col-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Pending
                    </div>

                    <h3 class="mb-0">
                        {{ $statistics['pending'] }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-lg-2 col-md-4 col-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Approved
                    </div>

                    <h3 class="mb-0">
                        {{ $statistics['approved'] }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-lg-2 col-md-4 col-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Rejected
                    </div>

                    <h3 class="mb-0">
                        {{ $statistics['rejected'] }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-lg-2 col-md-4 col-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Suspended
                    </div>

                    <h3 class="mb-0">
                        {{ $statistics['suspended'] }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
        SIMPLE FILTER
    ================================================================= --}}

    <div class="card mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.reviewers.index') }}"
            >

                <div class="row g-3">

                    <div class="col-lg-5">

                        <label class="form-label">
                            Reviewer
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Search by name or email"
                        >

                    </div>


                    <div class="col-lg-3">

                        <label class="form-label">
                            Account Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="pending"
                                @selected(request('status') === 'pending')
                            >
                                Pending
                            </option>

                            <option
                                value="approved"
                                @selected(request('status') === 'approved')
                            >
                                Approved
                            </option>

                            <option
                                value="rejected"
                                @selected(request('status') === 'rejected')
                            >
                                Rejected
                            </option>

                            <option
                                value="suspended"
                                @selected(request('status') === 'suspended')
                            >
                                Suspended
                            </option>

                        </select>

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label">
                            Profile
                        </label>

                        <select
                            name="profile"
                            class="form-select"
                        >

                            <option value="">
                                All
                            </option>

                            <option
                                value="complete"
                                @selected(request('profile') === 'complete')
                            >
                                Complete
                            </option>

                            <option
                                value="incomplete"
                                @selected(request('profile') === 'incomplete')
                            >
                                Incomplete
                            </option>

                        </select>

                    </div>


                    <div class="col-lg-2 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Filter
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ================================================================
        REVIEWER TABLE
    ================================================================= --}}

    <div class="card">

        <div class="card-header">

            <strong>
                All Reviewers
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>
                                Reviewer
                            </th>

                            <th>
                                Professional Information
                            </th>

                            <th>
                                Profile
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Created
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($reviewers as $reviewer)

                            <tr>

                                <td>
                                    {{ $reviewer->id }}
                                </td>


                                <td>

                                    <strong>
                                        {{ $reviewer->name }}
                                    </strong>

                                    <div class="small text-muted">
                                        {{ $reviewer->email }}
                                    </div>

                                </td>


                                <td>

                                    <div>
                                        {{ $reviewer->profile?->designation ?? '—' }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ $reviewer->profile?->department ?? '' }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ $reviewer->profile?->institution ?? '' }}
                                    </div>

                                </td>


                                <td>

                                    @if($reviewer->profile?->profile_completed)

                                        <span class="badge bg-success">
                                            Complete
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            Incomplete
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @switch($reviewer->status)

                                        @case('approved')

                                            <span class="badge bg-success">
                                                Approved
                                            </span>

                                            @break


                                        @case('rejected')

                                            <span class="badge bg-danger">
                                                Rejected
                                            </span>

                                            @break


                                        @case('suspended')

                                            <span class="badge bg-dark">
                                                Suspended
                                            </span>

                                            @break


                                        @default

                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>

                                    @endswitch

                                </td>


                                <td>

                                    {{ $reviewer->created_at?->format('d M Y') }}

                                </td>


                                <td>

                                    @can('reviewer.view')

                                        <a
                                            href="{{ route(
                                                'admin.reviewers.show',
                                                $reviewer
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>

                                    @endcan

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5 text-muted"
                                >
                                    No reviewers found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($reviewers->hasPages())

            <div class="card-footer">

                {{ $reviewers->links('pagination::bootstrap-4') }}

            </div>

        @endif

    </div>

</div>

@endsection