@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Article Types</h2>
            <p class="text-muted mb-0">
                Manage BMRC journal article types
            </p>
        </div>

        <a href="{{ route('admin.article-types.create') }}"
           class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Add Article Type
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th width="70">#</th>
                            <th>Article Type</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th width="100">Order</th>
                            <th width="120">Status</th>
                            <th width="220">Action</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($articleTypes as $articleType)

                        <tr>

                            <td>
                                {{ $articleTypes->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <strong>
                                    {{ $articleType->name }}
                                </strong>
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ $articleType->code }}
                                </span>
                            </td>

                            <td>
                                {{ $articleType->description ?? '-' }}
                            </td>

                            <td>
                                {{ $articleType->sort_order }}
                            </td>

                            <td>

                                @if($articleType->is_active)

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Inactive
                                    </span>

                                @endif

                            </td>

                            <td>

                                <a href="{{ route('admin.article-types.show', $articleType) }}"
                                   class="btn btn-sm btn-info">
                                    View
                                </a>

                                <a href="{{ route('admin.article-types.edit', $articleType) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('admin.article-types.toggle-status', $articleType) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                            class="btn btn-sm btn-secondary">

                                        {{ $articleType->is_active ? 'Disable' : 'Enable' }}

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7"
                                class="text-center py-4">

                                No article types found.

                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $articleTypes->links() }}

            </div>

        </div>

    </div>

</div>

@endsection