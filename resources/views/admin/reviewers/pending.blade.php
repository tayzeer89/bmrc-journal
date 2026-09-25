@extends('admin.layouts.app')

@section('title', 'Pending Reviewers')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Pending Reviewers
            </h4>

            <p class="text-muted mb-0">
                Reviewers waiting for profile completion or editorial approval.
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

                            <th>#</th>
                            <th>Reviewer</th>
                            <th>Email</th>
                            <th>Profile</th>
                            <th>Status</th>
                            <th>Created</th>
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
                                    {{ $reviewer->name }}
                                </td>

                                <td>
                                    {{ $reviewer->email }}
                                </td>

                                <td>

                                    @if($reviewer->profile?->profile_completed)

                                        <span class="badge bg-success">
                                            Completed
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            Incomplete
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <span class="badge bg-warning text-dark">
                                        {{ ucfirst($reviewer->status) }}
                                    </span>

                                </td>

                                <td>
                                    {{ $reviewer->created_at?->format('d M Y') }}
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
                                    No pending reviewers found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            <div class="mt-3">

                {{ $reviewers->links('pagination::bootstrap-4') }}

            </div>

        </div>

    </div>

</div>

@endsection