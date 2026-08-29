@extends('layouts.app')

@section('title', 'Reviewer Registration | BMRC Journal')

@section('content')

<style>

    /* =========================================
       PAGE
    ========================================= */

    .reviewer-registration-page {
        min-height: calc(100vh - 70px);
        background: #f4f7f9;
        padding: 45px 0;
    }


    /* =========================================
       MAIN CARD
    ========================================= */

    .reviewer-card {
        background: #ffffff;
        border: 1px solid #e2e8ee;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(13, 59, 102, 0.08);
    }


    /* =========================================
       HEADER
    ========================================= */

    .reviewer-header {
        background: linear-gradient(
            135deg,
            #0d3b66,
            #145374
        );

        color: #ffffff;
        padding: 30px 35px;
    }


    .reviewer-logo {
        width: 62px;
        height: 62px;
        object-fit: contain;
        background: #ffffff;
        border-radius: 12px;
        padding: 7px;
        flex-shrink: 0;
    }


    .reviewer-header h3 {
        font-size: 1.35rem;
        font-weight: 700;
        margin-bottom: 4px;
    }


    .reviewer-header p {
        margin: 0;
        font-size: .9rem;
        color: rgba(255,255,255,.82);
    }


    /* =========================================
       BODY
    ========================================= */

    .reviewer-body {
        padding: 35px;
    }


    .intro-title {
        color: #1d2939;
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 6px;
    }


    .intro-text {
        color: #667085;
        font-size: .9rem;
        line-height: 1.7;
    }


    /* =========================================
       SECTION TITLE
    ========================================= */

    .section-title {
        display: flex;
        align-items: center;
        gap: 9px;

        color: #0d3b66;

        font-size: .98rem;
        font-weight: 700;

        border-bottom: 1px solid #eaecf0;

        padding-bottom: 11px;
        margin-bottom: 20px;
    }


    .section-title i {
        font-size: 1.05rem;
    }


    /* =========================================
       FORM
    ========================================= */

    .form-label {
        color: #344054;
        font-size: .88rem;
        font-weight: 600;
        margin-bottom: 7px;
    }


    .required {
        color: #b42318;
    }


    .form-control,
    .form-select {
        min-height: 45px;

        border: 1px solid #d0d5dd;

        border-radius: 7px;

        font-size: .9rem;

        color: #344054;

        transition: all .2s ease;
    }


    .form-control:focus,
    .form-select:focus {

        border-color: #0d3b66;

        box-shadow:
            0 0 0 .2rem rgba(13, 59, 102, .10);
    }


    .form-control::placeholder {
        color: #98a2b3;
    }


    .form-text {
        font-size: .78rem;
        color: #667085;
    }


    /* =========================================
       INPUT ICONS
    ========================================= */

    .input-group-text {
        background: #f8fafc;
        border-color: #d0d5dd;
        color: #667085;
    }


    /* =========================================
       INFORMATION BOX
    ========================================= */

    .application-info {

        background: #f5f9fc;

        border: 1px solid #d9e6ef;

        border-left: 4px solid #0d3b66;

        border-radius: 8px;

        padding: 18px 20px;

        margin-top: 28px;
        margin-bottom: 25px;
    }


    .application-info-title {

        color: #0d3b66;

        font-weight: 700;

        font-size: .92rem;

        margin-bottom: 6px;
    }


    .application-info-text {

        color: #667085;

        font-size: .82rem;

        line-height: 1.7;

        margin-bottom: 0;
    }


    /* =========================================
       SUBMIT BUTTON
    ========================================= */

    .reviewer-submit {

        min-height: 48px;

        background: #0d3b66;

        border: 1px solid #0d3b66;

        border-radius: 7px;

        font-weight: 600;

        font-size: .92rem;

        transition: all .2s ease;
    }


    .reviewer-submit:hover {

        background: #092f52;

        border-color: #092f52;

        transform: translateY(-1px);

        box-shadow:
            0 5px 15px rgba(13, 59, 102, .18);
    }


    /* =========================================
       LOGIN / BACK LINKS
    ========================================= */

    .login-link {

        color: #0d3b66;

        font-weight: 600;

        text-decoration: none;
    }


    .login-link:hover {
        text-decoration: underline;
    }


    .back-link {

        color: #667085;

        text-decoration: none;

        font-size: .82rem;
    }


    .back-link:hover {
        color: #0d3b66;
    }


    /* =========================================
       ALERT
    ========================================= */

    .alert {
        border-radius: 8px;
        font-size: .85rem;
    }


    /* =========================================
       MOBILE
    ========================================= */

    @media (max-width: 767px) {

        .reviewer-registration-page {
            padding: 25px 0;
        }


        .reviewer-header {
            padding: 24px 22px;
        }


        .reviewer-body {
            padding: 24px 20px;
        }


        .reviewer-logo {
            width: 55px;
            height: 55px;
        }


        .reviewer-header h3 {
            font-size: 1.15rem;
        }


        .reviewer-header p {
            font-size: .82rem;
        }

    }


    @media (max-width: 480px) {

        .reviewer-header {
            padding: 22px 18px;
        }


        .reviewer-body {
            padding: 22px 17px;
        }


        .reviewer-logo {
            width: 50px;
            height: 50px;
        }

    }

</style>


<div class="reviewer-registration-page">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-12 col-md-10 col-lg-8 col-xl-7">


                {{-- =========================================
                     MAIN CARD
                ========================================== --}}

                <div class="reviewer-card">


                    {{-- =====================================
                         HEADER
                    ====================================== --}}

                    <div class="reviewer-header">

                        <div class="d-flex align-items-center gap-3">


                            <img
                                src="{{ asset('favicon.png') }}"
                                alt="BMRC Logo"
                                class="reviewer-logo"
                            >


                            <div>

                                <h3>
                                    BMRC Journal
                                </h3>

                                <p>
                                    Bangladesh Medical Research Council
                                </p>

                                <p class="mt-1">
                                    Online Journal Submission System
                                </p>

                            </div>

                        </div>

                    </div>



                    {{-- =====================================
                         BODY
                    ====================================== --}}

                    <div class="reviewer-body">


                        {{-- Introduction --}}

                        <div class="mb-4">

                            <div class="intro-title">

                                Reviewer Registration

                            </div>


                            <p class="intro-text mb-0">

                                Create your reviewer account and submit
                                your professional profile for consideration
                                by the BMRC Journal Editorial Office.

                            </p>

                        </div>



                        {{-- =====================================
                             VALIDATION ERRORS
                        ====================================== --}}

                        @if ($errors->any())

                            <div class="alert alert-danger">

                                <div class="fw-semibold mb-2">

                                    <i class="bi bi-exclamation-triangle me-1"></i>

                                    Please correct the following errors:

                                </div>


                                <ul class="mb-0 ps-3">

                                    @foreach ($errors->all() as $error)

                                        <li>
                                            {{ $error }}
                                        </li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif



                        {{-- =====================================
                             SUCCESS MESSAGE
                        ====================================== --}}

                        @if(session('success'))

                            <div class="alert alert-success">

                                <i class="bi bi-check-circle me-2"></i>

                                {{ session('success') }}

                            </div>

                        @endif



                        {{-- =====================================
                             REGISTRATION FORM
                        ====================================== --}}

                        <form
                            method="POST"
                            action="{{ route('reviewer.register.submit') }}"
                        >

                            @csrf



                            {{-- =================================
                                 PERSONAL INFORMATION
                            ================================== --}}

                            <div class="section-title">

                                <i class="bi bi-person-vcard"></i>

                                Personal Information

                            </div>


                            <div class="row g-3">


                                {{-- Title --}}

                                <div class="col-md-4">

                                    <label
                                        for="title"
                                        class="form-label"
                                    >

                                        Title

                                        <span class="required">*</span>

                                    </label>


                                    <select
                                        name="title"
                                        id="title"
                                        class="form-select @error('title') is-invalid @enderror"
                                        required
                                    >

                                        <option value="">
                                            Select Title
                                        </option>


                                        @foreach([
                                            'Dr.',
                                            'Prof.',
                                            'Mr.',
                                            'Ms.',
                                            'Mrs.'
                                        ] as $title)

                                            <option
                                                value="{{ $title }}"
                                                @selected(old('title') === $title)
                                            >
                                                {{ $title }}
                                            </option>

                                        @endforeach

                                    </select>


                                    @error('title')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>



                                {{-- First Name --}}

                                <div class="col-md-4">

                                    <label
                                        for="first_name"
                                        class="form-label"
                                    >

                                        First Name

                                        <span class="required">*</span>

                                    </label>


                                    <input
                                        type="text"
                                        name="first_name"
                                        id="first_name"
                                        value="{{ old('first_name') }}"
                                        class="form-control @error('first_name') is-invalid @enderror"
                                        autocomplete="given-name"
                                        placeholder="First name"
                                        required
                                    >


                                    @error('first_name')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>



                                {{-- Last Name --}}

                                <div class="col-md-4">

                                    <label
                                        for="last_name"
                                        class="form-label"
                                    >

                                        Last Name

                                        <span class="required">*</span>

                                    </label>


                                    <input
                                        type="text"
                                        name="last_name"
                                        id="last_name"
                                        value="{{ old('last_name') }}"
                                        class="form-control @error('last_name') is-invalid @enderror"
                                        autocomplete="family-name"
                                        placeholder="Last name"
                                        required
                                    >


                                    @error('last_name')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>



                                {{-- Email --}}

                                <div class="col-12">

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
                                            value="{{ old('email') }}"
                                            class="form-control @error('email') is-invalid @enderror"
                                            autocomplete="email"
                                            placeholder="Enter your professional email address"
                                            required
                                        >

                                    </div>


                                    @error('email')

                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>

                                    @enderror


                                    <div class="form-text mt-1">

                                        This email address will be used for
                                        BMRC Journal correspondence.

                                    </div>

                                </div>



                                {{-- Mobile --}}

                                <div class="col-12">

                                    <label
                                        for="mobile"
                                        class="form-label"
                                    >

                                        Mobile Number

                                        <span class="required">*</span>

                                    </label>


                                    <div class="input-group">

                                        <span class="input-group-text">

                                            <i class="bi bi-phone"></i>

                                        </span>


                                        <input
                                            type="tel"
                                            name="mobile"
                                            id="mobile"
                                            value="{{ old('mobile') }}"
                                            class="form-control @error('mobile') is-invalid @enderror"
                                            autocomplete="tel"
                                            placeholder="01XXXXXXXXX"
                                            required
                                        >

                                    </div>


                                    @error('mobile')

                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                            </div>



                            {{-- =================================
                                 ACCOUNT SECURITY
                            ================================== --}}

                            <div class="section-title mt-5">

                                <i class="bi bi-shield-lock"></i>

                                Account Security

                            </div>


                            <div class="row g-3">


                                {{-- Password --}}

                                <div class="col-md-6">

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
                                            class="form-control @error('password') is-invalid @enderror"
                                            autocomplete="new-password"
                                            placeholder="Create password"
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


                                    @error('password')

                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>

                                    @enderror


                                    <div class="form-text mt-1">

                                        Use at least 8 characters.

                                    </div>

                                </div>



                                {{-- Confirm Password --}}

                                <div class="col-md-6">

                                    <label
                                        for="password_confirmation"
                                        class="form-label"
                                    >

                                        Confirm Password

                                        <span class="required">*</span>

                                    </label>


                                    <div class="input-group">

                                        <span class="input-group-text">

                                            <i class="bi bi-lock-fill"></i>

                                        </span>


                                        <input
                                            type="password"
                                            name="password_confirmation"
                                            id="password_confirmation"
                                            class="form-control"
                                            autocomplete="new-password"
                                            placeholder="Confirm password"
                                            required
                                        >

                                    </div>

                                </div>

                            </div>



                            {{-- =================================
                                 APPLICATION INFORMATION
                            ================================== --}}

                            <div class="application-info">

                                <div class="application-info-title">

                                    <i class="bi bi-info-circle me-2"></i>

                                    Reviewer Application Process

                                </div>


                                <p class="application-info-text">

                                    Registration creates your BMRC Journal
                                    reviewer account. After registration,
                                    please complete your academic,
                                    professional, research and areas of
                                    expertise information. The BMRC Journal
                                    Editorial Office will review your
                                    application before approval.

                                </p>

                            </div>



                            {{-- =================================
                                 SUBMIT BUTTON
                            ================================== --}}

                            <button
                                type="submit"
                                class="btn reviewer-submit text-white w-100"
                            >

                                <i class="bi bi-person-check me-2"></i>

                                Create Reviewer Account

                            </button>


                        </form>



                        {{-- =====================================
                             LOGIN LINK
                        ====================================== --}}

                        <div class="text-center mt-4 pt-4 border-top">

                            <span class="text-muted small">

                                Already registered?

                            </span>


                            <a
                                href="{{ route('reviewer.login') }}"
                                class="login-link ms-1"
                            >

                                Reviewer Login

                            </a>

                        </div>



                        {{-- =====================================
                             BACK LINK
                        ====================================== --}}

                        <div class="text-center mt-3">

                            <a
                                href="{{ url('/') }}"
                                class="back-link"
                            >

                                <i class="bi bi-arrow-left me-1"></i>

                                Back to BMRC Journal

                            </a>

                        </div>


                    </div>

                </div>


                {{-- =========================================
                     PAGE FOOTER
                ========================================== --}}

                <div class="text-center mt-3">

                    <small class="text-muted">

                        BMRC Journal Online Submission System

                    </small>

                </div>


            </div>

        </div>

    </div>

</div>



{{-- =========================================
     PASSWORD TOGGLE
========================================== --}}

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