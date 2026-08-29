@extends('admin.layouts.app')

@section('title', 'Create User')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="fw-bold">
            Create Internal User
        </h2>

        <p class="text-muted">
            Create a new BMRC Journal internal user and assign a role.
        </p>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="card shadow-sm border-0">

        <div class="card-body p-4">

            <form method="POST"
                  action="{{ route('admin.users.store') }}">

                @csrf


                <div class="row g-4">


                    {{-- Name --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Full Name
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="form-control"
                               required>

                    </div>


                    {{-- Email --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Email Address
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control"
                               required>

                    </div>


                    {{-- Password --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Password
                        </label>

                        <input type="password"
                               name="password"
                               class="form-control"
                               required>

                    </div>


                    {{-- Confirm Password --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Confirm Password
                        </label>

                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               required>

                    </div>


                    {{-- User Type --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            User Type
                        </label>

                        <select name="user_type"
                                class="form-select"
                                required>

                            <option value="internal">
                                Internal
                            </option>

                        </select>

                    </div>


                    {{-- Role --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Role
                        </label>

                        <select name="role"
                                class="form-select"
                                required>

                            <option value="">
                                Select Role
                            </option>

                            @foreach($roles as $role)

                                <option value="{{ $role->name }}"
                                    @selected(old('role') === $role->name)>

                                    {{ $role->display_name ?? ucwords(str_replace('_', ' ', $role->name)) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                </div>


                <hr class="my-4">


                <div class="d-flex flex-column flex-sm-row
                            justify-content-end gap-2">

                    <a href="{{ route('admin.users.index') }}"
                       class="btn btn-light border">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        Create User

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>

@endsection
