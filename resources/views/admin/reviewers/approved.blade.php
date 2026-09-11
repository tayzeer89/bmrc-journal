@extends('admin.layouts.app')

@section('title', 'Approved Reviewers')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Approved Reviewers
            </h4>

            <p class="text-muted mb-0">
                Reviewers who have been approved and are eligible for reviewer activities.
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

            <a
                href="{{ route('admin.reviewers.index') }}"
                class="btn btn-outline-secondary"
            >
                Reviewer Pool
            </a>

        </div>

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


    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif


    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Approved Reviewer List
            </strong>

            <span class="badge bg-success">
                {{ $reviewers->total() }}
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Reviewer</th>
                            <th>Professional Information</th>
                            <th>Specialization</th>
                            <th>Profile</th>
                            <th>Availability</th>
                            <th>Approved Status</th>
                            <th>Action</th>
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

                                    @if($reviewer->profile?->reviewer_code)
                                        <div class="small text-muted">
                                            {{ $reviewer->profile->reviewer_code }}
                                        </div>
                                    @endif

                                </td>


                                <td>

                                    <div>
                                        {{ $reviewer->profile?->designation ?? '—' }}
                                    </div>

                                    @if($reviewer->profile?->department)
                                        <div class="small text-muted">
                                            {{ $reviewer->profile->department }}
                                        </div>
                                    @endif

                                    @if($reviewer->profile?->institution)
                                        <div class="small text-muted">
                                            {{ $reviewer->profile->institution }}
                                        </div>
                                    @elseif($reviewer->profile?->institution_name)
                                        <div class="small text-muted">
                                            {{ $reviewer->profile->institution_name }}
                                        </div>
                                    @endif

                                </td>


                                <td>
                                    {{ $reviewer->profile?->specialization ?? '—' }}
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


                                    @if($reviewer->profile?->status)

                                        <div class="small text-muted mt-1">
                                            {{
                                                ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $reviewer->profile->status
                                                    )
                                                )
                                            }}
                                        </div>

                                    @endif

                                </td>


                                <td>

                                    @if($reviewer->profile?->available_for_review)

                                        <span class="badge bg-success">
                                            Available
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Not Available
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <span class="badge bg-success">
                                        Approved
                                    </span>

                                    @if($reviewer->activated_at)
                                        <div class="small text-muted mt-1">
                                            {{ $reviewer->activated_at->format('d M Y') }}
                                        </div>
                                    @endif

                                </td>


                                <td>

                                    <div class="d-flex flex-wrap gap-1">

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


                                        @can('reviewer.edit')
                                            <a
                                                href="{{ route(
                                                    'admin.reviewers.edit',
                                                    $reviewer
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>
                                        @endcan


                                        @can('reviewer.suspend')
                                            <form
                                                action="{{ route(
                                                    'admin.reviewers.suspend',
                                                    $reviewer
                                                ) }}"
                                                method="POST"
                                                onsubmit="return confirm(
                                                    'Are you sure you want to suspend this reviewer?'
                                                );"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                >
                                                    Suspend
                                                </button>
                                            </form>
                                        @endcan

                                    </div>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5"
                                >

                                    <h6 class="mb-2">
                                        No Approved Reviewers
                                    </h6>

                                    <p class="text-muted mb-0">
                                        There are currently no approved reviewers.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($reviewers->hasPages())

            <div class="card-footer">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="small text-muted">
                        Showing
                        {{ $reviewers->firstItem() }}
                        to
                        {{ $reviewers->lastItem() }}
                        of
                        {{ $reviewers->total() }}
                        approved reviewers
                    </div>

                    <div>
                        {{ $reviewers->links() }}
                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection
