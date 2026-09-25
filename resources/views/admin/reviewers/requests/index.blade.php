@extends('admin.layouts.app')

@section('title', 'Reviewer Requests')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Reviewer Request List
            </h4>

            <p class="text-muted mb-0">
                Requests submitted for adding new reviewers.
            </p>
        </div>

        @can('reviewer.request')

            <a
                href="{{ route('admin.reviewers.requests.create') }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-circle me-1"></i>
                Request New Reviewer
            </a>

        @endcan

    </div>


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Search / Filter --}}
    <div class="card mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.reviewers.requests.index') }}"
            >

                <div class="row g-3">

                    <div class="col-md-6">

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Search name, email, institution or specialization"
                        >

                    </div>


                    <div class="col-md-3">

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
                                value="completed"
                                @selected(request('status') === 'completed')
                            >
                                Completed
                            </option>

                        </select>

                    </div>


                    <div class="col-md-3">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Search
                        </button>

                        <a
                            href="{{ route('admin.reviewers.requests.index') }}"
                            class="btn btn-outline-secondary"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Request List --}}
    <div class="card">

        <div class="card-header">

            <strong>
                Reviewer Request List
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Reviewer</th>
                            <th>Institution</th>
                            <th>Expertise</th>
                            <th>Requested By</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse($requests as $reviewRequest)

                            <tr>

                                <td>
                                    {{ $reviewRequest->id }}
                                </td>


                                <td>

                                    <strong>
                                        {{ $reviewRequest->name }}
                                    </strong>

                                    <div class="small text-muted">
                                        {{ $reviewRequest->email }}
                                    </div>

                                </td>


                                <td>
                                    {{ $reviewRequest->institution ?? '—' }}
                                </td>


                                <td>
                                    {{ $reviewRequest->specialization ?? '—' }}
                                </td>


                                <td>
                                    {{ $reviewRequest->requester?->name ?? '—' }}
                                </td>


                                <td>
                                    {{
                                        $reviewRequest->created_at
                                            ?->format('d M Y')
                                        ?? '—'
                                    }}
                                </td>


                                <td>

                                    @switch($reviewRequest->status)

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


                                        @case('completed')

                                            <span class="badge bg-primary">
                                                Completed
                                            </span>

                                            @break


                                        @default

                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>

                                    @endswitch

                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.reviewers.requests.show',
                                            $reviewRequest
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5"
                                >

                                    <h6 class="mb-1">
                                        No Reviewer Requests Found
                                    </h6>

                                    <p class="text-muted mb-3">
                                        No reviewer requests have been submitted.
                                    </p>

                                    @can('reviewer.request')

                                        <a
                                            href="{{ route(
                                                'admin.reviewers.requests.create'
                                            ) }}"
                                            class="btn btn-primary btn-sm"
                                        >
                                            Request New Reviewer
                                        </a>

                                    @endcan

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($requests->hasPages())

            <div class="card-footer">

                {{ $requests->links('pagination::bootstrap-4') }}

            </div>

        @endif

    </div>

</div>

@endsection