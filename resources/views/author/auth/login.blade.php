@extends('layouts.app')

@section('title', 'Author Login | BMRC Journal')

@section('content')

<style>

    /* =====================================================
       BMRC AUTHOR LOGIN
    ===================================================== */

    .login-page {
        min-height: calc(100vh - 70px);
        background: #f4f7f9;
        display: flex;
        align-items: center;
        padding: 40px 15px;
    }


    .login-wrapper {
        width: 100%;
        max-width: 440px;
        margin: auto;
    }


    /* =====================================================
       LOGIN CARD
    ===================================================== */

    .login-card {
        background: #ffffff;
        border: 1px solid #e1e7ec;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(15, 50, 70, 0.10);
    }


    /* =====================================================
       CARD HEADER
    ===================================================== */

    .login-header {
        background: #0d3b66;
        color: #ffffff;
        text-align: center;
        padding: 30px 25px 28px;
    }


    .login-logo {
        width: 78px;
        height: 78px;
        object-fit: contain;
        background: #ffffff;
        border-radius: 50%;
        padding: 7px;
        margin-bottom: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,.15);
    }


    .journal-name {
        font-size: 1.55rem;
        font-weight: 700;
        margin-bottom: 3px;
        letter-spacing: .2px;
    }


    .organization-name {
        font-size: .86rem;
        opacity: .92;
        margin-bottom: 3px;
    }


    .system-name {
        font-size: .78rem;
        opacity: .75;
        letter-spacing: .2px;
    }


    /* =====================================================
       AUTHOR PORTAL
    ===================================================== */

    .portal-header {
        text-align: center;
        padding: 25px 25px 10px;
    }


    .portal-icon {
        width: 58px;
        height: 58px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(13, 59, 102, .08);
        color: #0d3b66;
        font-size: 1.55rem;
        margin-bottom: 13px;
    }


    .portal-header h2 {
        color: #243447;
        font-size: 1.35rem;
        font-weight: 700;
        margin-bottom: 6px;
    }


    .portal-header p {
        color: #667085;
        font-size: .88rem;
        line-height: 1.6;
        margin-bottom: 0;
    }


    /* =====================================================
       CARD BODY
    ===================================================== */

    .login-body {
        padding: 20px 30px 30px;
    }


    .form-label {
        color: #344054;
        font-size: .88rem;
        font-weight: 600;
        margin-bottom: 7px;
    }


    .required {
        color: #b42318;
    }


    .input-group-text {
        background: #f8fafc;
        border-color: #d0d5dd;
        color: #667085;
    }


    .form-control {
        min-height: 45px;
        border-color: #d0d5dd;
        font-size: .9rem;
    }


    .form-control:focus {
        border-color: #0d3b66;
        box-shadow: 0 0 0 .2rem rgba(13, 59, 102, .10);
    }


    .input-group .form-control:focus {
        z-index: 2;
    }


    /* =====================================================
       LOGIN BUTTON
    ===================================================== */

    .login-btn {
        min-height: 46px;
        background: #0d3b66;
        border-color: #0d3b66;
        font-weight: 600;
        border-radius: 7px;
    }


    .login-btn:hover {
        background: #092f52;
        border-color: #092f52;
    }


    /* =====================================================
       REMEMBER / FORGOT
    ===================================================== */

    .remember-label {
        color: #667085;
        font-size: .82rem;
    }


    .forgot-link {
        color: #0d3b66;
        font-size: .82rem;
        font-weight: 600;
        text-decoration: none;
    }


    .forgot-link:hover {
        text-decoration: underline;
    }


    /* =====================================================
       CARD FOOTER
    ===================================================== */

    .login-footer {
        background: #f8fafc;
        border-top: 1px solid #eaecf0;
        text-align: center;
        padding: 20px;
    }


    .login-footer p {
        color: #667085;
        font-size: .82rem;
        margin-bottom: 10px;
    }


    .register-btn {
        color: #0d3b66;
        border-color: #0d3b66;
        font-size: .82rem;
        font-weight: 600;
        padding: 7px 18px;
    }


    .register-btn:hover {
        background: #0d3b66;
        color: #ffffff;
    }


    /* =====================================================
       BACK LINK
    ===================================================== */

    .back-link {
        color: #667085;
        font-size: .82rem;
        text-decoration: none;
    }


    .back-link:hover {
        color: #0d3b66;
    }


    /* =====================================================
       SECURITY NOTE
    ===================================================== */

    .security-note {
        text-align: center;
        color: #98a2b3;
        font-size: .72rem;
        margin-top: 15px;
    }


    /* =====================================================
       MOBILE
    ===================================================== */

    @media (max-width: 576px) {

        .login-page {
            min-height: calc(100vh - 60px);
            padding: 25px 12px;
        }

        .login-header {
            padding: 25px 18px;
        }

        .login-logo {
            width: 70px;
            height: 70px;
        }

        .journal-name {
            font-size: 1.35rem;
        }

        .organization-name {
            font-size: .78rem;
        }

        .system-name {
            font-size: .7rem;
        }

        .portal-header {
            padding: 22px 18px 8px;
        }

        .login-body {
            padding: 18px 20px 25px;
        }

    }

</style>


<div class="login-page">

    <div class="login-wrapper">


        {{-- =================================================
             LOGIN CARD
        ================================================== --}}

        <div class="login-card">


            {{-- =============================================
                 BMRC HEADER
            ============================================== --}}

            <div class="login-header">

                <img
                    src="{{ asset('favicon.png') }}"
                    alt="BMRC Logo"
                    class="login-logo"
                >


                <div class="journal-name">
                    BMRC Journal
                </div>


                <div class="organization-name">
                    Bangladesh Medical Research Council
                </div>


                <div class="system-name">
                    Online Journal Submission System
                </div>

            </div>



            {{-- =============================================
                 AUTHOR PORTAL
            ============================================== --}}

            <div class="portal-header">

                <div class="portal-icon">

                    <i class="bi bi-person-badge"></i>

                </div>


                <h2>
                    Author Portal
                </h2>


                <p>
                    Sign in to access your manuscript submission
                    and publication account.
                </p>

            </div>



            {{-- =============================================
                 LOGIN BODY
            ============================================== --}}

            <div class="login-body">


                {{-- Success Message --}}

                @if(session('success'))

                    <div
                        class="alert alert-success alert-dismissible fade show small"
                        role="alert"
                    >

                        <i class="bi bi-check-circle me-2"></i>

                        {{ session('success') }}

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"
                        ></button>

                    </div>

                @endif



                {{-- Validation Errors --}}

                @if($errors->any())

                    <div class="alert alert-danger small">

                        <div class="fw-semibold mb-2">

                            <i class="bi bi-exclamation-triangle me-1"></i>

                            Please check your login details.

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



                {{-- =========================================
                     LOGIN FORM
                ========================================== --}}

                <form
                    method="POST"
                    action="{{ route('author.login.submit') }}"
                >

                    @csrf



                    {{-- Email --}}

                    <div class="mb-3">

                        <label
                            for="email"
                            class="form-label"
                        >

                            Email Address

                            <span class="required">*</span>

                        </label>


                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="bi bi-envelope"></i>

                            </span>


                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="Enter your email address"
                                autocomplete="email"
                                required
                                autofocus
                            >

                        </div>


                        @error('email')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>



                    {{-- Password --}}

                    <div class="mb-3">

                        <label
                            for="password"
                            class="form-label"
                        >

                            Password

                            <span class="required">*</span>

                        </label>


                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="bi bi-lock"></i>

                            </span>


                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >


                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                id="togglePassword"
                                aria-label="Show password"
                            >

                                <i
                                    class="bi bi-eye"
                                    id="passwordIcon"
                                ></i>

                            </button>

                        </div>

                    </div>



                    {{-- Remember / Forgot --}}

                    <div
                        class="d-flex justify-content-between
                               align-items-center mb-4"
                    >

                        <div class="form-check">

                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                                class="form-check-input"
                                id="remember"
                            >


                            <label
                                class="form-check-label remember-label"
                                for="remember"
                            >

                                Remember me

                            </label>

                        </div>


                        <a
                            href="#"
                            class="forgot-link"
                        >

                            Forgot password?

                        </a>

                    </div>



                    {{-- Login Button --}}

                    <button
                        type="submit"
                        class="btn login-btn text-white w-100"
                    >

                        <i class="bi bi-box-arrow-in-right me-2"></i>

                        Sign In to Author Portal

                    </button>

                </form>

            </div>



            {{-- =============================================
                 REGISTER FOOTER
            ============================================== --}}

            <div class="login-footer">

                <p>
                    Don't have an Author account?
                </p>


                <a
                    href="{{ route('author.register') }}"
                    class="btn btn-outline-primary register-btn"
                >

                    <i class="bi bi-person-plus me-1"></i>

                    Register as Author

                </a>

            </div>

        </div>



        {{-- =============================================
             BACK TO JOURNAL
        ============================================== --}}

        <div class="text-center mt-3">

            <a
                href="{{ url('/') }}"
                class="back-link"
            >

                <i class="bi bi-arrow-left me-1"></i>

                Back to BMRC Journal

            </a>

        </div>



        {{-- Security --}}

        <div class="security-note">

            <i class="bi bi-shield-check me-1"></i>

            Secure access to the BMRC Journal Online System

        </div>


    </div>

</div>



{{-- =====================================================
     PASSWORD TOGGLE
====================================================== --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const togglePassword =
        document.getElementById('togglePassword');

    const password =
        document.getElementById('password');

    const passwordIcon =
        document.getElementById('passwordIcon');


    if (togglePassword && password && passwordIcon) {

        togglePassword.addEventListener('click', function () {

            const isPassword =
                password.getAttribute('type') === 'password';


            password.setAttribute(
                'type',
                isPassword ? 'text' : 'password'
            );


            passwordIcon.classList.toggle(
                'bi-eye',
                !isPassword
            );


            passwordIcon.classList.toggle(
                'bi-eye-slash',
                isPassword
            );


            togglePassword.setAttribute(
                'aria-label',
                isPassword
                    ? 'Hide password'
                    : 'Show password'
            );

        });

    }

});

</script>

@endpush

@endsection