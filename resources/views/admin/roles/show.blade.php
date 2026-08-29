@extends('admin.layouts.app')

@section('title', 'Role Details')

@section('content')

<div class="container-fluid py-4">

<div class="mb-4">

    <h2 class="fw-bold">
        Role Details
    </h2>

</div>


<div class="card shadow-sm border-0 mb-4">

    <div class="card-body">

        <h4 class="fw-bold">

            {{ $role->display_name ?? ucwords(str_replace('_', ' ', $role->name)) }}

        </h4>

        <p class="text-muted">
            {{ $role->description ?? 'No description available.' }}
        </p>


        <div class="row g-3">

            <div class="col-md-4">

                <strong>Role Name</strong>

                <div>
                    {{ $role->name }}
                </div>

            </div>


            <div class="col-md-4">

                <strong>User Type</strong>

                <div>
                    {{ ucfirst($role->user_type ?? 'internal') }}
                </div>

            </div>


            <div class="col-md-4">

                <strong>Status</strong>

                <div>

                    @if($role->is_active ?? true)

                        <span class="badge bg-success">
                            Active
                        </span>

                    @else

                        <span class="badge bg-danger">
                            Inactive
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>


<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <h5 class="mb-0 fw-bold">
            Assigned Permissions
        </h5>

    </div>


    <div class="card-body">

        <div class="row">

            @forelse($role->permissions as $permission)

                <div class="col-md-4 col-lg-3 mb-3">

                    <span class="badge bg-primary">

                        {{ ucwords(str_replace('.', ' ', $permission->name)) }}

                    </span>

                </div>

            @empty

                <div class="col-12">

                    <p class="text-muted mb-0">
                        No permissions assigned.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>


<div class="mt-4">

    <a href="{{ route('admin.roles.edit', $role) }}"
       class="btn btn-primary">

        Edit Permissions

    </a>

    <a href="{{ route('admin.roles.index') }}"
       class="btn btn-secondary">

        Back

    </a>

</div>


</div>

@endsection
