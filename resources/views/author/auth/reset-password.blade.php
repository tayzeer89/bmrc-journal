@extends('layouts.app')

@section('title', 'Reset Password | BMRC Journal')

@section('content')

<style>

    .reset-page {
        min-height: calc(100vh - 70px);
        background: #f4f7f9;
        display: flex;
        align-items: center;
        padding: 40px 15px;
    }

    .reset-wrapper {
        width: 100%;
        max-width: 460px;
        margin: auto;
    }

    .reset-card {
        background: #ffffff;
        border: 1px solid #e1e7ec;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(15, 50, 70, .10);
    }

    .reset-header {
        background: #0d3b66;
        color: white;
        text-align: center;
        padding: 30px 25px;
    }

    .reset-logo {
        width: 75px;
        height: 75px;
        object-fit: contain;
        background: white;
        border-radius: 50%;
        padding: 7px;
        margin-bottom: 14px;
    }

    .reset-body {
        padding: 30px;
    }

    .reset-title {
        color: #243447;
        font-size: 1.3rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 7px;
    }

    .reset-description {
        color: #667085;
        font-size: .84rem;
        line-height: 1.6;
        text-align: center;
        margin-bottom: 24px;
    }

    .form-label {
        color: #344054;
        font-size: .86rem;
        font-weight: 600;
    }

    .form-control {
        min-height: 45px;
    }

    .reset-btn {
        min-height: 46px;
        background: #0d3b66;
        border-color: #0d3b66;
        font-weight: 600;
    }

    .reset-btn:hover {
        background: #092f52;
        border-color: #092f52;
    }

</style>


<div class="reset-page">

    <div class="reset-wrapper">

        <div class="reset-card">

            <div class="reset-header">

                <img
                    src="{{ asset('favicon.png') }}"
                    alt="BMRC Logo"
                    class="reset-logo"
                >

                <div class="fw-bold fs-4">
                    BMRC Journal
                </div>

                <div class="small opacity-75">
                    Author Password Recovery
                </div>

            </div>


            <div class="reset-body">

                <div class="reset-title">
                    Create New Password
                </div>

                <div class="reset-description">
                    Create a secure new password for your
                    BMRC Journal Author account.
                </div>


                @if($errors->any())

                    <div class="alert alert-danger small">

                        <ul class="mb-0 ps-3">

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                <form
                    method="POST"
                    action="{{ route('author.password.store') }}"
                >

                    @csrf


                    <input
                        type="hidden"
                        name="token"
                        value="{{ $token }}"
                    >


                    <div class="mb-3">

                        <label
                            for="email"
                            class="form-label"
                        >
                            Email Address
                        </label>


                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email', $email) }}"
                            class="form-control"
                            required
                            readonly
                        >

                    </div>


                    <div class="mb-3">

                        <label
                            for="password"
                            class="form-label"
                        >
                            New Password
                        </label>


                        <div class="input-group">

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                required
                                autocomplete="new-password"
                            >


                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                onclick="togglePassword(
                                    'password',
                                    'passwordIcon'
                                )"
                            >

                                <i
                                    class="bi bi-eye"
                                    id="passwordIcon"
                                ></i>

                            </button>

                        </div>

                    </div>


                    <div class="mb-2">

                        <label
                            for="password_confirmation"
                            class="form-label"
                        >
                            Confirm New Password
                        </label>


                        <div class="input-group">

                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control"
                                required
                                autocomplete="new-password"
                            >


                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                onclick="togglePassword(
                                    'password_confirmation',
                                    'confirmationIcon'
                                )"
                            >

                                <i
                                    class="bi bi-eye"
                                    id="confirmationIcon"
                                ></i>

                            </button>

                        </div>

                    </div>


                    <div class="small text-muted mb-4">

                        <i class="bi bi-info-circle me-1"></i>

                        Use at least 8 characters with uppercase,
                        lowercase and numbers.

                    </div>


                    <button
                        type="submit"
                        class="btn reset-btn text-white w-100"
                    >

                        <i class="bi bi-shield-check me-2"></i>

                        Reset Password

                    </button>

                </form>


                <div class="text-center mt-4">

                    <a
                        href="{{ route('author.login') }}"
                        class="text-decoration-none"
                    >

                        <i class="bi bi-arrow-left me-1"></i>

                        Back to Author Login

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

function togglePassword(inputId, iconId)
{
    const input =
        document.getElementById(inputId);

    const icon =
        document.getElementById(iconId);


    if (!input || !icon) {
        return;
    }


    const hidden =
        input.type === 'password';


    input.type =
        hidden
            ? 'text'
            : 'password';


    icon.classList.toggle(
        'bi-eye',
        !hidden
    );


    icon.classList.toggle(
        'bi-eye-slash',
        hidden
    );
}

</script>

@endsection