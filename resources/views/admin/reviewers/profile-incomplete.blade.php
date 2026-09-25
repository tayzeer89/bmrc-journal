@extends('admin.layouts.app')

@section('title', 'Profile Incomplete Reviewers')

@section('content')

<div class="container-fluid">

    {{-- ================================================================
        HEADER
    ================================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Profile Incomplete Reviewers
            </h4>

            <p class="text-muted mb-0">
                Reviewers who have not completed their reviewer profile.
            </p>

        </div>


        <a
            href="{{ route('admin.reviewers.index') }}"
            class="btn btn-outline-secondary"
        >
            Back to Reviewer Pool
        </a>

    </div>


    {{-- ================================================================
        FLASH MESSAGES
    ================================================================= --}}

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


    {{-- ================================================================
        REVIEWER LIST
    ================================================================= --}}

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Incomplete Reviewer Profiles
            </strong>


            <span class="badge bg-warning text-dark">
                {{ $reviewers->total() }}
            </span>

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
                                Email
                            </th>

                            <th>
                                Profile Status
                            </th>

                            <th>
                                Account Status
                            </th>

                            <th>
                                Created At
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

                                </td>


                                <td>
                                    {{ $reviewer->email }}
                                </td>


                                <td>

                                    @if(
                                        $reviewer->profile
                                        &&
                                        $reviewer->profile->profile_completed
                                    )

                                        <span class="badge bg-success">
                                            Complete
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            Incomplete
                                        </span>

                                    @endif


                                    @if(
                                        $reviewer->profile
                                        &&
                                        $reviewer->profile->status
                                    )

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

                                    <div class="d-flex gap-1">

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

                                    </div>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <h6 class="mb-2">
                                        No Incomplete Profiles
                                    </h6>

                                    <p class="text-muted mb-0">
                                        All reviewer profiles are complete.
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

                        reviewers

                    </div>


                    <div>
                        {{ $reviewers->links('pagination::bootstrap-4') }}
                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection