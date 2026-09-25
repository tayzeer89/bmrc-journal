@extends('admin.layouts.app')

@section('title', 'Update Requested Reviewers')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Update Requested Reviewers
            </h4>

            <p class="text-muted mb-0">
                Reviewer applications returned for correction or additional information.
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
                            <th>Application Status</th>
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

                                    <span class="badge bg-info text-dark">
                                        Update Requested
                                    </span>

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
                                    colspan="5"
                                    class="text-center text-muted py-4"
                                >
                                    No reviewer update requests found.
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