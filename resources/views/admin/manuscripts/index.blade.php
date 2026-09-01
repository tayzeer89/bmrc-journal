@extends('admin.layouts.app')

@section('title', 'Manuscripts')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">

        <h2 class="fw-bold">
            Manuscripts
        </h2>

        <p class="text-muted mb-0">
            Manage submitted manuscripts
        </p>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>ID</th>

                            <th>Title</th>

                            <th>Article Type</th>

                            <th>Status</th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($manuscripts as $manuscript)

                            <tr>

                                <td>

                                    {{ $manuscript->manuscript_no ?? $manuscript->id }}

                                </td>


                                <td>

                                    <div class="fw-semibold">

                                        {{ $manuscript->title }}

                                    </div>

                                </td>


                                <td>

                                    {{ $manuscript->article_type ?? '—' }}

                                </td>


                                <td>

                                    <span class="badge bg-secondary">

                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $manuscript->status
                                            )
                                        ) }}

                                    </span>

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.manuscripts.show',
                                            $manuscript
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary">

                                        View

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center py-4 text-muted">

                                    No manuscripts found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($manuscripts->hasPages())

            <div class="card-footer bg-white">

                {{ $manuscripts->links() }}

            </div>

        @endif

    </div>

</div>

@endsection