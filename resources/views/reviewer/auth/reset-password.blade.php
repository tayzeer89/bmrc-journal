@extends('layouts.app')

@section('title', 'Reset Password | BMRC Journal')

@section('content')

<style>

    .reviewer-reset-page {
        min-height: calc(100vh - 70px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 15px;
        background: #f4f7f9;
    }

    .reviewer-reset-card {
        width: 100%;
        max-width: 470px;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e1e7ec;
        border-radius: 14px;
        box-shadow: 0 12px 35px rgba(15, 50, 70, .10);
    }

    .reviewer-reset-header {
        padding: 28px 25px;
        text-align: center;
        background: linear-gradient(
            135deg,
            #0d3b66,
            #145374
        );
        color: #ffffff;
    }

    .reviewer-reset-logo {
        width: 66px;
        height: 66px;
        object-fit: contain;
        padding: 6px;
        margin-bottom: 12px;
        border-radius: 50%;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .12);
    }

    .reviewer-reset-header h3 {
        margin-bottom: 4px;
        font-size: 1.25rem;
        font-weight: 700;
    }

    .reviewer-reset-header p {
        margin: 0;
        font-size: .8rem;
        opacity: .8;
    }

    .reviewer-reset-body {
        padding: 30px;
    }

    .reset-icon {
        width: 54px;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 13px;
        border-radius: 50%;
        background: rgba(13, 59, 102, .08);
        color: #0d3b66;
        font-size: 1.4rem;
    }

    .reset-title {
        text-align: center;
        color: #243447;
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .reset-description {
        margin: 0 auto 22px;
        text-align: center;
        color: #667085;
        font-size: .82rem;
        line-height: 1.6;
    }

    .form-label {
        color: #344054;
        font-size: .84rem;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .input-group-text {
        background: #f8fafc;
        border-color: #d0d5dd;
        color: #667085;
    }

    .form-control {
        min-height: 45px;
        border-color: #d0d5dd;
        font-size: .86rem;
    }

    .form-control:focus {
        border-color: #0d3b66;
        box-shadow: 0 0 0 .2rem rgba(13, 59, 102, .10);
    }

    .password-wrapper {
        position: relative;
    }

    .password-wrapper .form-control {
        padding-right: 45px;
    }

    .password-toggle {
        position: absolute;
        top: 50%;
        right: 8px;
        transform: translateY(-50%);
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border: 0;
        border-radius: 5px;
        background: transparent;
        color: #667085;
        cursor: pointer;
        z-index: 5;
    }

    .password-toggle:hover {
        background: #f1f4f7;
        color: #0d3b66;
    }

    .reset-button {
        min-height: 46px;
        background: #0d3b66;
        border-color: #0d3b66;
        font-size: .86rem;
        font-weight: 600;
        border-radius: 7px;
    }

    .reset-button:hover {
        background: #092f52;
        border-color: #092f52;
    }

    .back-login {
        color: #0d3b66;
        font-size: .82rem;
        font-weight: 600;
        text-decoration: none;
    }

    .back-login:hover {
        text-decoration: underline;
    }

    .password-note {
        color: #667085;
        font-size: .74rem;
        line-height: 1.5;
    }

    @media (max-width: 576px) {

        .reviewer-reset-page {
            padding: 25px 12px;
        }

        .reviewer-reset-body {
            padding: 24px 20px;
        }
    }

</style>


<div class="reviewer-reset-page">

    <div class="reviewer-reset-card">

        {{-- ==========================================
             BMRC HEADER
        =========================================== --}}

        <div class="reviewer-reset-header">

            <img
                src="{{ asset('favicon.png') }}"
                alt="BMRC Logo"
                class="reviewer-reset-logo"
            >

            <h3>
                BMRC Journal
            </h3>

            <p>
                Bangladesh Medical Research Council
            </p>

        </div>


        {{-- ==========================================
             RESET PASSWORD BODY
        =========================================== --}}

        <div class="reviewer-reset-body">

            <div class="reset-icon">
                <i class="bi bi-shield-lock"></i>
            </div>

            <div class="reset-title">
                Reset Reviewer Password
            </div>

            <div class="reset-description">
                Create a new secure password for your BMRC Journal reviewer account.
            </div>


            {{-- ======================================
                 VALIDATION ERRORS
            ======================================= --}}

            @if($errors->any())

                <div class="alert alert-danger small">

                    <div class="fw-semibold mb-1">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Please correct the following:
                    </div>

                    <ul class="mb-0 ps-3">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- ======================================
                 RESET FORM
            ======================================= --}}

            <form
                method="POST"
                action="{{ route('reviewer.password.store') }}"
            >

                @csrf


                {{-- Reset Token --}}

                <input
                    type="hidden"
                    name="token"
                    value="{{ $token }}"
                >


                {{-- Email --}}

                <div class="mb-3">

                    <label
                        for="email"
                        class="form-label"
                    >
                        Email Address
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email', $email) }}"
                            class="form-control @error('email') is-invalid @enderror"
                            required
                            autocomplete="email"
                            readonly
                        >

                    </div>

                    @error('email')

                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- New Password --}}

                <div class="mb-3">

                    <label
                        for="password"
                        class="form-label"
                    >
                        New Password
                        <span class="text-danger">*</span>
                    </label>

                    <div class="password-wrapper">

                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            required
                            autocomplete="new-password"
                            placeholder="Enter new password"
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePasswordField(
                                'password',
                                'passwordIcon'
                            )"
                            aria-label="Show or hide password"
                        >
                            <i
                                class="bi bi-eye"
                                id="passwordIcon"
                            ></i>
                        </button>

                    </div>

                    @error('password')

                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Confirm Password --}}

                <div class="mb-2">

                    <label
                        for="password_confirmation"
                        class="form-label"
                    >
                        Confirm New Password
                        <span class="text-danger">*</span>
                    </label>

                    <div class="password-wrapper">

                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            class="form-control"
                            required
                            autocomplete="new-password"
                            placeholder="Confirm new password"
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePasswordField(
                                'password_confirmation',
                                'confirmPasswordIcon'
                            )"
                            aria-label="Show or hide password"
                        >
                            <i
                                class="bi bi-eye"
                                id="confirmPasswordIcon"
                            ></i>
                        </button>

                    </div>

                </div>


                <div class="password-note mb-4">

                    <i class="bi bi-info-circle me-1"></i>

                    Password must contain at least 8 characters,
                    uppercase and lowercase letters, and a number.

                </div>


                {{-- Submit --}}

                <button
                    type="submit"
                    class="btn reset-button text-white w-100"
                >

                    <i class="bi bi-shield-check me-2"></i>

                    Reset Password

                </button>

            </form>


            {{-- ======================================
                 BACK TO LOGIN
            ======================================= --}}

            <div class="text-center mt-4">

                <a
                    href="{{ route('reviewer.login') }}"
                    class="back-login"
                >

                    <i class="bi bi-arrow-left me-1"></i>

                    Back to Reviewer Login

                </a>

            </div>

        </div>

    </div>

</div>


<script>

    function togglePasswordField(inputId, iconId)
    {
        const input =
            document.getElementById(inputId);

        const icon =
            document.getElementById(iconId);


        if (!input || !icon) {
            return;
        }


        const isPassword =
            input.type === 'password';


        input.type =
            isPassword
                ? 'text'
                : 'password';


        icon.classList.toggle(
            'bi-eye',
            !isPassword
        );


        icon.classList.toggle(
            'bi-eye-slash',
            isPassword
        );
    }

</script>

@endsection