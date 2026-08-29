@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')

<div class="container-fluid py-4">

{{-- Page Header --}}
<div class="d-flex flex-column flex-md-row
            justify-content-between
            align-items-md-center
            gap-3 mb-4">

    <div>
        <h2 class="mb-1">
            Edit User
        </h2>

        <p class="text-muted mb-0">
            Update user information, role and system access.
        </p>
    </div>

    <div>
        <a href="{{ route('admin.users.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Users
        </a>
    </div>

</div>


{{-- Validation Errors --}}
@if ($errors->any())

    <div class="alert alert-danger">

        <strong>Please correct the following errors:</strong>

        <ul class="mb-0 mt-2">

            @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


{{-- Edit User Card --}}
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white py-3">

        <h5 class="mb-0">
            User Information
        </h5>

    </div>


    <div class="card-body p-4">

        <form method="POST"
              action="{{ route('admin.users.update', $user) }}">

            @csrf

            @method('PUT')


            {{-- Basic Information --}}
            <div class="row g-4">


                {{-- Name --}}
                <div class="col-md-6">

                    <label for="name"
                           class="form-label fw-semibold">

                        Full Name
                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $user->name) }}"
                        required
                    >

                    @error('name')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Email --}}
                <div class="col-md-6">

                    <label for="email"
                           class="form-label fw-semibold">

                        Email Address
                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', $user->email) }}"
                        required
                    >

                    @error('email')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Designation --}}
                <div class="col-md-6">

                    <label for="designation"
                           class="form-label fw-semibold">

                        Designation

                    </label>

                    <input
                        type="text"
                        id="designation"
                        name="designation"
                        class="form-control"
                        value="{{ old('designation', $user->designation) }}"
                        placeholder="e.g. Editorial Officer"
                    >

                    @error('designation')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Department --}}
                <div class="col-md-6">

                    <label for="department"
                           class="form-label fw-semibold">

                        Department

                    </label>

                    <input
                        type="text"
                        id="department"
                        name="department"
                        class="form-control"
                        value="{{ old('department', $user->department) }}"
                        placeholder="e.g. Editorial Department"
                    >

                    @error('department')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- User Type --}}
                <div class="col-md-6">

                    <label for="user_type"
                           class="form-label fw-semibold">

                        User Type
                        <span class="text-danger">*</span>

                    </label>

                    <select
                        id="user_type"
                        name="user_type"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Select User Type
                        </option>

                        <option value="internal"
                            {{ old('user_type', $user->user_type) == 'internal' ? 'selected' : '' }}>
                            Internal User
                        </option>

                        <option value="external"
                            {{ old('user_type', $user->user_type) == 'external' ? 'selected' : '' }}>
                            External User
                        </option>

                    </select>

                    @error('user_type')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Password --}}
                <div class="col-md-6">

                    <label for="password"
                           class="form-label fw-semibold">

                        New Password

                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        autocomplete="new-password"
                        placeholder="Leave blank to keep current password"
                    >

                    <div class="form-text">
                        Leave blank if you do not want to change the password.
                    </div>

                    @error('password')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


            </div>


            {{-- Role Assignment --}}
            <hr class="my-4">

            <div class="mb-3">

                <h5 class="mb-1">
                    Role Assignment
                </h5>

                <p class="text-muted small mb-3">
                    Select the role assigned to this user.
                    Permissions are controlled through the selected role.
                </p>


                <div class="row g-3">

                    @foreach ($roles as $role)

                        <div class="col-md-6 col-lg-4">

                            <div class="border rounded p-3">

                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="role"
                                        id="role_{{ $role->id }}"
                                        value="{{ $role->name }}"
                                        {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                    >

                                    <label
                                        class="form-check-label fw-semibold"
                                        for="role_{{ $role->id }}"
                                    >

                                        {{ $role->display_name ?? ucwords(str_replace('_', ' ', $role->name)) }}

                                    </label>

                                </div>

                                @if (!empty($role->description))

                                    <div class="small text-muted mt-2">

                                        {{ $role->description }}

                                    </div>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

                @error('role')
                    <div class="text-danger small mt-2">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Buttons --}}
            <hr class="my-4">

            <div class="d-flex
                        flex-column flex-sm-row
                        justify-content-end
                        gap-2">

                <a href="{{ route('admin.users.index') }}"
                   class="btn btn-outline-secondary">

                    Cancel

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-check-circle me-1"></i>

                    Update User

                </button>

            </div>


        </form>

    </div>

</div>

</div>

@endsection
