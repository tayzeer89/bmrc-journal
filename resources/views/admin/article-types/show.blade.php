@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>{{ $articleType->name }}</h2>

            <p class="text-muted">
                Article Type Details
            </p>
        </div>

        <div>

            <a href="{{ route('admin.article-types.edit', $articleType) }}"
               class="btn btn-warning">

                Edit

            </a>

            <a href="{{ route('admin.article-types.index') }}"
               class="btn btn-secondary">

                Back

            </a>

        </div>

    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th width="250">Name</th>
                    <td>{{ $articleType->name }}</td>
                </tr>

                <tr>
                    <th>Code</th>
                    <td>{{ $articleType->code }}</td>
                </tr>

                <tr>
                    <th>Description</th>
                    <td>
                        {{ $articleType->description ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <th>Sort Order</th>
                    <td>
                        {{ $articleType->sort_order }}
                    </td>
                </tr>

                <tr>
                    <th>Status</th>
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
                </tr>

                <tr>
                    <th>Created</th>
                    <td>
                        {{ $articleType->created_at->format('d M Y H:i') }}
                    </td>
                </tr>

                <tr>
                    <th>Last Updated</th>
                    <td>
                        {{ $articleType->updated_at->format('d M Y H:i') }}
                    </td>
                </tr>

            </table>

        </div>

    </div>

</div>

@endsection