@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')

<div class="container-fluid py-4">

{{-- Header --}}
<div class="d-flex flex-column flex-md-row
            justify-content-between
            align-items-md-center
            gap-3 mb-4">

    <div>
        <h2 class="mb-1">
            User Details
        </h2>

        <p class="text-muted mb-0">
            View user account, role and access information.
        </p>
    </div>

    <div class="d-flex gap-2">

        <a href="{{ route('admin.users.edit', $user) }}"
           class="btn btn-primary">

            <i class="bi bi-pencil-square me-1"></i>
            Edit User

        </a>

        <a href="{{ route('admin.users.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Users

        </a>

    </div>

</div>


<div class="row g-4">

    {{-- User Information --}}
    <div class="col-lg-8">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-header bg-white py-3">

                <h5 class="mb-0">
                    Account Information
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-4">

                    {{-- Name --}}
                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            Full Name
                        </div>

                        <div class="fw-semibold">
                            {{ $user->name }}
                        </div>

                    </div>


                    {{-- Email --}}
                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            Email Address
                        </div>

                        <div class="fw-semibold">
                            {{ $user->email }}
                        </div>

                    </div>


                    {{-- Designation --}}
                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            Designation
                        </div>

                        <div class="fw-semibold">

                            {{ $user->designation ?: 'Not specified' }}

                        </div>

                    </div>


                    {{-- Department --}}
                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            Department
                        </div>

                        <div class="fw-semibold">

                            {{ $user->department ?: 'Not specified' }}

                        </div>

                    </div>


                    {{-- User Type --}}
                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            User Type
                        </div>

                        <div>

                            @if ($user->user_type === 'internal')

                                <span class="badge bg-primary">
                                    Internal User
                                </span>

                            @elseif ($user->user_type === 'external')

                                <span class="badge bg-secondary">
                                    External User
                                </span>

                            @else

                                <span class="badge bg-light text-dark">
                                    Not specified
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Account Status --}}
                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            Account Status
                        </div>

                        <div>

                            @if (isset($user->is_active))

                                @if ($user->is_active)

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Inactive
                                    </span>

                                @endif

                            @else

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Registered --}}
                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            Registered
                        </div>

                        <div class="fw-semibold">

                            {{ $user->created_at?->format('d M Y, h:i A') }}

                        </div>

                    </div>


                    {{-- Last Updated --}}
                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            Last Updated
                        </div>

                        <div class="fw-semibold">

                            {{ $user->updated_at?->format('d M Y, h:i A') }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Role Information --}}
    <div class="col-lg-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-header bg-white py-3">

                <h5 class="mb-0">
                    Assigned Role
                </h5>

            </div>


            <div class="card-body">

                @if ($user->roles->count())

                    @foreach ($user->roles as $role)

                        <div class="border rounded p-3 mb-3">

                            <div class="fw-semibold">

                                {{ $role->display_name
                                    ?? ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $role->name
                                        )
                                    )
                                }}

                            </div>

                            <div class="small text-muted mt-1">

                                {{ $role->name }}

                            </div>

                            @if (!empty($role->description))

                                <hr>

                                <div class="small text-muted">

                                    {{ $role->description }}

                                </div>

                            @endif

                        </div>

                    @endforeach

                @else

                    <div class="text-center py-4">

                        <i class="bi bi-person-x fs-1 text-muted"></i>

                        <p class="text-muted mt-3 mb-0">

                            No role has been assigned.

                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>


{{-- Permissions --}}
<div class="card border-0 shadow-sm mt-4">

    <div class="card-header bg-white py-3">

        <div class="d-flex
                    flex-column flex-md-row
                    justify-content-between
                    gap-2">

            <div>

                <h5 class="mb-1">
                    Effective Permissions
                </h5>

                <p class="text-muted small mb-0">

                    Permissions currently available to this user.

                </p>

            </div>

            <div>

                <span class="badge bg-primary">

                    {{ $user->getAllPermissions()->count() }}
                    Permissions

                </span>

            </div>

        </div>

    </div>


    <div class="card-body">

        @php
            $permissions = $user->getAllPermissions()
                ->sortBy('name')
                ->groupBy(function ($permission) {

                    return explode(
                        '.',
                        $permission->name
                    )[0];

                });
        @endphp


        @if ($permissions->count())

            <div class="row g-4">

                @foreach ($permissions as $module => $modulePermissions)

                    <div class="col-md-6 col-xl-4">

                        <div class="border rounded p-3 h-100">

                            <h6 class="text-uppercase
                                       fw-bold
                                       mb-3">

                                {{ str_replace(
                                    '_',
                                    ' ',
                                    $module
                                ) }}

                            </h6>


                            @foreach ($modulePermissions as $permission)

                                <div class="d-flex
                                            align-items-center
                                            gap-2
                                            mb-2">

                                    <i class="bi bi-check-circle-fill
                                              text-success"></i>

                                    <span class="small">

                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                explode(
                                                    '.',
                                                    $permission->name
                                                )[1] ?? $permission->name
                                            )
                                        ) }}

                                    </span>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="text-center py-5">

                <i class="bi bi-shield-x fs-1 text-muted"></i>

                <h6 class="mt-3">
                    No Permissions Assigned
                </h6>

                <p class="text-muted mb-0">

                    This user currently has no effective permissions.

                </p>

            </div>

        @endif

    </div>

</div>


{{-- Danger Zone --}}
<div class="card border-danger border-1 shadow-sm mt-4">

    <div class="card-header bg-white py-3">

        <h5 class="mb-0 text-danger">
            Account Management
        </h5>

    </div>

    <div class="card-body">

        <div class="d-flex
                    flex-column flex-md-row
                    justify-content-between
                    align-items-md-center
                    gap-3">

            <div>

                <h6 class="mb-1">
                    Delete User
                </h6>

                <p class="text-muted small mb-0">

                    Permanently remove this user account
                    from the journal system.

                </p>

            </div>


            <form method="POST"
                  action="{{ route('admin.users.destroy', $user) }}"
                  onsubmit="return confirm(
                      'Are you sure you want to delete this user?'
                  );">

                @csrf

                @method('DELETE')

                <button type="submit"
                        class="btn btn-outline-danger">

                    <i class="bi bi-trash me-1"></i>

                    Delete User

                </button>

            </form>

        </div>

    </div>

</div>

</div>

@endsection
