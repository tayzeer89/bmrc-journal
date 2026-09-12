@extends('layouts.app')

@section('title', 'Change Temporary Password | BMRC Journal')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-7 col-lg-5">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-primary text-white">

                    <h5 class="mb-0">
                        <i class="bi bi-shield-lock me-2"></i>
                        Change Temporary Password
                    </h5>

                </div>

                <div class="card-body p-4">

                    <div class="alert alert-warning">

                        <i class="bi bi-exclamation-triangle me-2"></i>

                        <strong>Password change required.</strong>

                        For security reasons, you must change your
                        temporary password before accessing your reviewer account.

                    </div>

                    @if($errors->any())

                        <div class="alert alert-danger">

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <form
                        method="POST"
                        action="{{ route('reviewer.password.update') }}"
                    >

                        @csrf
                        @method('PATCH')


                        <div class="mb-3">

                            <label
                                for="current_password"
                                class="form-label"
                            >
                                Current Temporary Password
                            </label>

                            <input
                                type="password"
                                name="current_password"
                                id="current_password"
                                class="form-control
                                    @error('current_password')
                                        is-invalid
                                    @enderror"
                                required
                                autofocus
                                autocomplete="current-password"
                            >

                            @error('current_password')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="mb-3">

                            <label
                                for="password"
                                class="form-label"
                            >
                                New Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control
                                    @error('password')
                                        is-invalid
                                    @enderror"
                                required
                                autocomplete="new-password"
                            >

                            @error('password')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <div class="form-text">
                                Minimum 8 characters with uppercase,
                                lowercase and number.
                            </div>

                        </div>


                        <div class="mb-4">

                            <label
                                for="password_confirmation"
                                class="form-label"
                            >
                                Confirm New Password
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control"
                                required
                                autocomplete="new-password"
                            >

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            <i class="bi bi-key me-2"></i>
                            Set New Password
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection