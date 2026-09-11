@extends('admin.layouts.app')

@section('title', 'Suspended Reviewers')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Suspended Reviewers</h4>

            <p class="text-muted mb-0">
                Reviewers whose accounts are currently suspended.
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
                            <th>Profile Status</th>
                            <th>Account Status</th>
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
                                    {{ ucfirst(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $reviewer->profile?->status ?? 'N/A'
                                        )
                                    ) }}
                                </td>

                                <td>
                                    <span class="badge bg-dark">
                                        Suspended
                                    </span>
                                </td>

                                <td>

                                    <a
                                        href="{{ route('admin.reviewers.show', $reviewer) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>

                                    @can('reviewer.activate')

                                        <form
                                            action="{{ route('admin.reviewers.activate', $reviewer) }}"
                                            method="POST"
                                            class="d-inline"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-success"
                                                onclick="return confirm('Activate this reviewer account?')"
                                            >
                                                Activate
                                            </button>

                                        </form>

                                    @endcan

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="6"
                                    class="text-center text-muted py-4"
                                >
                                    No suspended reviewers found.
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