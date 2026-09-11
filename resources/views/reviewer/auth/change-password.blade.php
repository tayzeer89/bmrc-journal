@extends('layouts.app')

@section('title', 'Change Password | BMRC Journal')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-7 col-lg-5">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-primary text-white">

                    <h5 class="mb-0">
                        Change Temporary Password
                    </h5>

                </div>

                <div class="card-body p-4">

                    <div class="alert alert-warning">

                        <i class="bi bi-exclamation-triangle me-2"></i>

                        For security reasons, you must change your temporary password before continuing.

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
                                Current Password
                            </label>

                            <input
                                type="password"
                                name="current_password"
                                id="current_password"
                                class="form-control"
                                required
                                autofocus
                            >

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
                                class="form-control"
                                required
                            >

                            <small class="text-muted">

                                Minimum 8 characters with uppercase,
                                lowercase and number.

                            </small>

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
                            >

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >

                            <i class="bi bi-key me-2"></i>

                            Change Password

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection