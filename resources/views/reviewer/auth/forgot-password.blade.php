@extends('layouts.app')

@section('title', 'Forgot Password | BMRC Journal')

@section('content')

<style>

    .reviewer-auth-page {
        min-height: calc(100vh - 70px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 15px;
        background: #f4f7f9;
    }

    .reviewer-auth-card {
        width: 100%;
        max-width: 440px;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e1e7ec;
        border-radius: 14px;
        box-shadow: 0 12px 35px rgba(15, 50, 70, .10);
    }

    .reviewer-auth-header {
        padding: 28px 25px;
        text-align: center;
        background: linear-gradient(
            135deg,
            #0d3b66,
            #145374
        );
        color: #ffffff;
    }

    .reviewer-auth-logo {
        width: 66px;
        height: 66px;
        object-fit: contain;
        padding: 6px;
        margin-bottom: 12px;
        border-radius: 50%;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,.12);
    }

    .reviewer-auth-header h3 {
        margin-bottom: 4px;
        font-size: 1.25rem;
        font-weight: 700;
    }

    .reviewer-auth-header p {
        margin: 0;
        font-size: .8rem;
        opacity: .8;
    }

    .reviewer-auth-body {
        padding: 30px;
    }

    .auth-icon {
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

    .auth-title {
        text-align: center;
        color: #243447;
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .auth-description {
        max-width: 350px;
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

    .security-note {
        margin-top: 16px;
        text-align: center;
        color: #98a2b3;
        font-size: .72rem;
    }

    @media (max-width: 576px) {

        .reviewer-auth-page {
            padding: 25px 12px;
        }

        .reviewer-auth-body {
            padding: 24px 20px;
        }

    }

</style>


<div class="reviewer-auth-page">

    <div class="reviewer-auth-card">

        {{-- ==========================================
             BMRC HEADER
        =========================================== --}}

        <div class="reviewer-auth-header">

            <img
                src="{{ asset('favicon.png') }}"
                alt="BMRC Logo"
                class="reviewer-auth-logo"
            >

            <h3>
                BMRC Journal
            </h3>

            <p>
                Bangladesh Medical Research Council
            </p>

        </div>


        {{-- ==========================================
             FORGOT PASSWORD BODY
        =========================================== --}}

        <div class="reviewer-auth-body">

            <div class="auth-icon">
                <i class="bi bi-key"></i>
            </div>


            <div class="auth-title">
                Forgot Password?
            </div>


            <div class="auth-description">
                Enter your registered reviewer email address.
                We will send you a secure link to reset your password.
            </div>


            {{-- ======================================
                 SUCCESS MESSAGE
            ======================================= --}}

            @if(session('status'))

                <div
                    class="alert alert-success small"
                    role="alert"
                >

                    <i class="bi bi-check-circle-fill me-2"></i>

                    {{ session('status') }}

                </div>

            @endif


            {{-- ======================================
                 VALIDATION ERRORS
            ======================================= --}}

            @if($errors->any())

                <div
                    class="alert alert-danger small"
                    role="alert"
                >

                    <div class="fw-semibold mb-1">

                        <i class="bi bi-exclamation-triangle me-1"></i>

                        Please check the information below.

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
                 FORGOT PASSWORD FORM
            ======================================= --}}

            <form
                method="POST"
                action="{{ route('reviewer.password.email') }}"
            >

                @csrf


                <div class="mb-3">

                    <label
                        for="email"
                        class="form-label"
                    >
                        Registered Email Address
                        <span class="text-danger">*</span>
                    </label>


                    <div class="input-group">

                        <span class="input-group-text">

                            <i class="bi bi-envelope"></i>

                        </span>


                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Enter your reviewer email"
                            required
                            autofocus
                            autocomplete="email"
                        >

                    </div>


                    @error('email')

                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                <button
                    type="submit"
                    class="btn reset-button text-white w-100"
                >

                    <i class="bi bi-send me-2"></i>

                    Send Password Reset Link

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


            <div class="security-note">

                <i class="bi bi-shield-check me-1"></i>

                Secure password recovery for BMRC Journal reviewers

            </div>

        </div>

    </div>

</div>

@endsection