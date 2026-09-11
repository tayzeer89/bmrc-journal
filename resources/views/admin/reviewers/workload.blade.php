@extends('admin.layouts.app')

@section('title', 'Reviewer Workload')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Reviewer Workload
            </h4>

            <p class="text-muted mb-0">
                Monitor reviewer assignments, active reviews and completed reviews.
            </p>
        </div>

        <a
            href="{{ route('admin.reviewers.index') }}"
            class="btn btn-outline-secondary"
        >
            Back to Reviewers
        </a>

    </div>


    <div class="card">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>
                            <th>Reviewer</th>
                            <th>Email</th>
                            <th>Total Assignments</th>
                            <th>Active Reviews</th>
                            <th>Completed</th>
                            <th>Availability</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($reviewers as $reviewer)

                            <tr>

                                <td>
                                    {{ $reviewer->name }}
                                </td>

                                <td>
                                    {{ $reviewer->email }}
                                </td>

                                <td>
                                    {{ $reviewer->total_assignments }}
                                </td>

                                <td>
                                    <span class="badge bg-warning text-dark">
                                        {{ $reviewer->active_assignments }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-success">
                                        {{ $reviewer->completed_assignments }}
                                    </span>
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

                                    <a
                                        href="{{ route('admin.reviewers.show', $reviewer) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center text-muted py-4"
                                >
                                    No reviewer workload information found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            <div class="mt-3">

                {{ $reviewers->links() }}

            </div>

        </div>

    </div>

</div>

@endsection