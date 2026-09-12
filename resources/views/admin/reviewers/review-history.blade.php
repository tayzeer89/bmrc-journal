@extends('admin.layouts.app')

@section('title', 'Reviewer Review History')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Reviewer Review History</h4>

            <p class="text-muted mb-0">
                View reviewer assignment and completed review history.
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
                            <th>Total Reviews</th>
                            <th>Completed Reviews</th>
                            <th>Profile Status</th>
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
                                    {{ $reviewer->total_reviews }}
                                </td>

                                <td>
                                    {{ $reviewer->completed_reviews }}
                                </td>

                                <td>
                                    {{ ucfirst(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $reviewer->profile?->status ?? 'N/A'
                                        )
                                    ) }}
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
                                    colspan="6"
                                    class="text-center text-muted py-4"
                                >
                                    No reviewer history found.
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