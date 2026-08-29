@extends('layouts.app')

@section('title', 'Administrator Login - BMRC Journal')

@section('content')

<style>
    /* ============================================================
       BMRC JOURNAL
       SYSTEM ADMINISTRATOR LOGIN
       International Journal Administration Portal
    ============================================================ */

    :root {
        --admin-navy: #172536;
        --admin-navy-dark: #101b28;
        --admin-slate: #344454;
        --admin-gold: #b08d57;
        --admin-gold-light: #f7f1e7;
        --admin-bg: #f3f5f7;
        --admin-border: #d9dee5;
        --admin-text: #273444;
        --admin-muted: #6b7785;
        --admin-white: #ffffff;
    }

    body {
        background: var(--admin-bg);
    }

    /* ============================================================
       PAGE
    ============================================================ */

    .admin-login-page {
        min-height: 100vh;

        background:
            linear-gradient(
                to bottom,
                #ffffff 0,
                #ffffff 235px,
                var(--admin-bg) 235px,
                var(--admin-bg) 100%
            );

        padding-bottom: 55px;
    }

    .admin-container {
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* ============================================================
       JOURNAL MASTHEAD
    ============================================================ */

    .journal-masthead {
        background: var(--admin-white);
        border-bottom: 1px solid #dfe4e9;
        margin-bottom: 35px;
    }

    .masthead-inner {
        min-height: 128px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 30px;
        padding: 22px 0;
    }

    .masthead-brand {
        display: flex;
        align-items: center;

        gap: 20px;

        min-width: 0;
    }

    /* ============================================================
       LOGO
    ============================================================ */

    .bmrc-logo-wrap {
        width: 78px;
        height: 78px;

        flex: 0 0 78px;

        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bmrc-logo {
        width: 100%;
        height: 100%;

        object-fit: contain;
    }

    /* ============================================================
       MASTHEAD DIVIDER
    ============================================================ */

    .masthead-divider {
        width: 1px;
        height: 62px;

        background: #d7dce2;
    }

    /* ============================================================
       JOURNAL INFORMATION
    ============================================================ */

    .institution-name {
        color: #3f4d5c;

        font-size: 13px;
        font-weight: 700;

        letter-spacing: .055em;

        margin-bottom: 5px;
    }

    .journal-name {
        color: var(--admin-navy);

        font-family:
            Georgia,
            "Times New Roman",
            serif;

        font-size: 30px;
        font-weight: 700;

        line-height: 1.15;

        margin: 0;
    }

    .journal-type {
        color: var(--admin-muted);

        font-size: 12px;

        margin-top: 6px;

        letter-spacing: .025em;
    }

    /* ============================================================
       ADMIN MASTHEAD LABEL
    ============================================================ */

    .masthead-right {
        text-align: right;

        flex-shrink: 0;
    }

    .admin-label {
        display: inline-flex;

        align-items: center;

        gap: 8px;

        padding: 9px 14px;

        background: var(--admin-navy);

        color: #ffffff;

        border-radius: 4px;

        font-size: 10px;
        font-weight: 700;

        letter-spacing: .10em;

        text-transform: uppercase;
    }

    .admin-label i {
        color: #d7b779;

        font-size: 14px;
    }

    .admin-label-subtitle {
        color: #7a8591;

        font-size: 11px;

        margin-top: 8px;
    }

    /* ============================================================
       MAIN CONTENT
    ============================================================ */

    .admin-content {
        max-width: 850px;

        margin: 0 auto;

        padding: 0 45px;
    }

    /* ============================================================
       INTRO
    ============================================================ */

    .admin-intro {
        text-align: center;

        margin-bottom: 25px;
    }

    .breadcrumb-line {
        display: flex;

        align-items: center;
        justify-content: center;

        flex-wrap: wrap;

        gap: 7px;

        color: #7d8792;

        font-size: 11px;

        margin-bottom: 13px;
    }

    .breadcrumb-line i {
        color: #a1a9b2;

        font-size: 9px;
    }

    .breadcrumb-line span {
        color: #6f7a86;
    }

    .admin-page-title {
        color: var(--admin-navy);

        font-family:
            Georgia,
            "Times New Roman",
            serif;

        font-size: 29px;

        font-weight: 700;

        margin: 0;
    }

    .admin-page-subtitle {
        max-width: 650px;

        margin: 7px auto 0;

        color: var(--admin-muted);

        font-size: 13px;

        line-height: 1.65;
    }

    /* ============================================================
       LOGIN CARD
    ============================================================ */

    .admin-login-card {
        background: #ffffff;

        border: 1px solid var(--admin-border);

        border-radius: 7px;

        overflow: hidden;

        box-shadow:
            0 6px 22px rgba(20, 30, 40, .07);
    }

    /* ============================================================
       CARD HEADER
    ============================================================ */

    .admin-card-header {
        background:
            linear-gradient(
                135deg,
                var(--admin-navy-dark),
                var(--admin-navy)
            );

        padding: 25px 28px;

        text-align: center;

        position: relative;
    }

    .admin-card-header::after {
        content: "";

        position: absolute;

        bottom: 0;
        left: 50%;

        transform: translateX(-50%);

        width: 70px;
        height: 3px;

        background: var(--admin-gold);
    }

    .admin-icon {
        width: 54px;
        height: 54px;

        margin: 0 auto 11px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: rgba(255,255,255,.09);

        border: 1px solid rgba(255,255,255,.20);

        color: #e1c48e;

        font-size: 23px;
    }

    .admin-card-title {
        color: #ffffff;

        font-family:
            Georgia,
            "Times New Roman",
            serif;

        font-size: 20px;

        font-weight: 700;

        margin-bottom: 4px;
    }

    .admin-card-description {
        color: #c4ccd4;

        font-size: 11px;

        margin: 0;
    }

    /* ============================================================
       CARD BODY
    ============================================================ */

    .admin-card-body {
        padding: 31px 34px 29px;
    }

    /* ============================================================
       ALERTS
    ============================================================ */

    .admin-alert {
        border-radius: 5px;

        font-size: 12px;

        margin-bottom: 20px;
    }

    /* ============================================================
       FORM
    ============================================================ */

    .form-group {
        margin-bottom: 19px;
    }

    .form-label {
        color: #344252;

        font-size: 13px;

        font-weight: 600;

        margin-bottom: 7px;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;

        left: 14px;
        top: 50%;

        transform: translateY(-50%);

        color: #8b96a2;

        font-size: 15px;

        pointer-events: none;

        z-index: 2;
    }

    .admin-form-control {
        width: 100%;

        min-height: 46px;

        padding-left: 42px;

        border: 1px solid #cbd2da;

        border-radius: 4px;

        background: #ffffff;

        color: #2d3947;

        font-size: 13px;

        box-shadow: none;

        transition:
            border-color .2s ease,
            box-shadow .2s ease;
    }

    .admin-form-control::placeholder {
        color: #9aa4af;
    }

    .admin-form-control:focus {
        border-color: var(--admin-gold);

        box-shadow:
            0 0 0 3px rgba(176, 141, 87, .12);

        outline: none;
    }

    .invalid-feedback {
        font-size: 11px;
    }

    /* ============================================================
       LOGIN OPTIONS
    ============================================================ */

    .login-options {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

        margin-top: 4px;

        margin-bottom: 23px;
    }

    .remember-label {
        display: inline-flex;

        align-items: center;

        gap: 8px;

        color: #687481;

        font-size: 12px;

        cursor: pointer;
    }

    .remember-checkbox {
        width: 15px;
        height: 15px;

        border-color: #b7c0ca;
    }

    .remember-checkbox:checked {
        background-color: var(--admin-navy);

        border-color: var(--admin-navy);
    }

    .forgot-link {
        color: var(--admin-slate);

        font-size: 12px;

        font-weight: 600;

        text-decoration: none;
    }

    .forgot-link:hover {
        color: var(--admin-gold);

        text-decoration: underline;
    }

    /* ============================================================
       LOGIN BUTTON
    ============================================================ */

    .btn-admin-login {
        width: 100%;

        min-height: 47px;

        border: 1px solid var(--admin-navy);

        border-radius: 4px;

        background:
            linear-gradient(
                135deg,
                #1b2b3c,
                #25394d
            );

        color: #ffffff;

        font-size: 13px;

        font-weight: 700;

        letter-spacing: .015em;

        transition:
            background .2s ease,
            border-color .2s ease,
            transform .2s ease,
            box-shadow .2s ease;
    }

    .btn-admin-login:hover {
        background:
            linear-gradient(
                135deg,
                #25394d,
                #31485e
            );

        border-color: #31485e;

        color: #ffffff;

        transform: translateY(-1px);

        box-shadow:
            0 5px 12px rgba(23, 37, 54, .16);
    }

    .btn-admin-login:focus {
        color: #ffffff;

        box-shadow:
            0 0 0 3px rgba(176, 141, 87, .18);
    }

    /* ============================================================
       ADMINISTRATOR NOTICE
    ============================================================ */

    .admin-notice {
        display: flex;

        align-items: flex-start;

        gap: 10px;

        margin-top: 20px;

        padding: 13px 14px;

        background: var(--admin-gold-light);

        border: 1px solid #eadfcf;

        border-left: 3px solid var(--admin-gold);

        border-radius: 4px;
    }

    .admin-notice-icon {
        color: #9a783f;

        font-size: 15px;

        margin-top: 1px;
    }

    .admin-notice-text {
        color: #665b4c;

        font-size: 11px;

        line-height: 1.55;

        margin: 0;
    }

    /* ============================================================
       SECURITY INFORMATION
    ============================================================ */

    .security-notice {
        display: flex;

        align-items: flex-start;

        gap: 9px;

        margin-top: 18px;

        color: #7b8691;

        font-size: 10.5px;

        line-height: 1.6;
    }

    .security-notice i {
        color: #687481;

        font-size: 14px;

        margin-top: 1px;
    }

    /* ============================================================
       FOOTER
    ============================================================ */

    .journal-footer {
        max-width: 850px;

        margin: 38px auto 0;

        padding:
            22px 20px 0;

        border-top: 1px solid #dce1e6;

        text-align: center;
    }

    .footer-journal-name {
        color: var(--admin-navy);

        font-family:
            Georgia,
            "Times New Roman",
            serif;

        font-size: 14px;

        font-weight: 700;

        margin-bottom: 4px;
    }

    .footer-institution {
        color: #687481;

        font-size: 11px;

        margin-bottom: 3px;
    }

    .footer-system {
        color: #89939e;

        font-size: 10px;

        margin-bottom: 3px;
    }

    .footer-copy {
        color: #a0a8b1;

        font-size: 10px;

        margin: 0;
    }

    /* ============================================================
       MOBILE
    ============================================================ */

    @media (max-width: 767px) {

        .admin-login-page {
            background:
                linear-gradient(
                    to bottom,
                    #ffffff 0,
                    #ffffff 205px,
                    var(--admin-bg) 205px,
                    var(--admin-bg) 100%
                );
        }

        .admin-container {
            padding: 0 13px;
        }

        .journal-masthead {
            margin-bottom: 25px;
        }

        .masthead-inner {
            min-height: auto;

            padding: 16px 0;

            gap: 15px;
        }

        .masthead-brand {
            gap: 12px;
        }

        .bmrc-logo-wrap {
            width: 55px;
            height: 55px;

            flex-basis: 55px;
        }

        .masthead-divider {
            height: 48px;
        }

        .institution-name {
            font-size: 9px;

            letter-spacing: .035em;
        }

        .journal-name {
            font-size: 21px;
        }

        .journal-type {
            font-size: 9px;
        }

        .masthead-right {
            display: none;
        }

        .admin-content {
            padding: 0 8px;
        }

        .admin-page-title {
            font-size: 24px;
        }

        .admin-page-subtitle {
            font-size: 12px;
        }

        .admin-card-header {
            padding: 21px 18px;
        }

        .admin-card-body {
            padding: 24px 18px;
        }

        .login-options {
            align-items: flex-start;
        }

        .journal-footer {
            padding-left: 8px;
            padding-right: 8px;
        }
    }

    @media (max-width: 400px) {

        .login-options {
            flex-direction: column;

            align-items: flex-start;

            gap: 9px;
        }

    }
</style>


<div class="admin-login-page">

    {{-- ============================================================
         JOURNAL MASTHEAD
    ============================================================= --}}

    <header class="journal-masthead">

        <div class="admin-container">

            <div class="masthead-inner">

                {{-- JOURNAL BRAND --}}

                <div class="masthead-brand">

                    <div class="bmrc-logo-wrap">

                        <img
                            src="{{ asset('favicon.png') }}"
                            alt="Bangladesh Medical Research Council"
                            class="bmrc-logo"
                        >

                    </div>


                    <div class="masthead-divider"></div>


                    <div>

                        <div class="institution-name">
                            BANGLADESH MEDICAL RESEARCH COUNCIL
                        </div>

                        <h1 class="journal-name">
                            BMRC Journal
                        </h1>

                        <div class="journal-type">
                            Medical &amp; Health Sciences Research Journal
                        </div>

                    </div>

                </div>


                {{-- ADMINISTRATION LABEL --}}

                <div class="masthead-right">

                    <div class="admin-label">

                        <i class="bi bi-shield-lock-fill"></i>

                        JOURNAL ADMINISTRATION

                    </div>

                    <div class="admin-label-subtitle">
                        Secure Administrative Portal
                    </div>

                </div>

            </div>

        </div>

    </header>


    {{-- ============================================================
         MAIN CONTENT
    ============================================================= --}}

    <main class="admin-container">

        <div class="admin-content">


            {{-- ====================================================
                 PAGE INTRO
            ===================================================== --}}

            <div class="admin-intro">

                <div class="breadcrumb-line">

                    <span>
                        BMRC Journal
                    </span>

                    <i class="bi bi-chevron-right"></i>

                    <span>
                        Administration
                    </span>

                    <i class="bi bi-chevron-right"></i>

                    <span>
                        Secure Login
                    </span>

                </div>


                <h2 class="admin-page-title">
                    System Administrator
                </h2>


                <p class="admin-page-subtitle">

                    Secure access to the BMRC Journal
                    editorial and administrative management system.

                </p>

            </div>


            {{-- ====================================================
                 LOGIN CARD
            ===================================================== --}}

            <div class="admin-login-card">


                {{-- CARD HEADER --}}

                <div class="admin-card-header">

                    <div class="admin-icon">

                        <i class="bi bi-shield-lock"></i>

                    </div>


                    <div class="admin-card-title">
                        Journal Administrator Login
                    </div>


                    <p class="admin-card-description">

                        Authorized personnel only

                    </p>

                </div>


                {{-- CARD BODY --}}

                <div class="admin-card-body">


                    {{-- SESSION STATUS --}}

                    @if (session('status'))

                        <div class="alert alert-success admin-alert">

                            <i class="bi bi-check-circle-fill me-2"></i>

                            {{ session('status') }}

                        </div>

                    @endif


                    {{-- LOGIN ERROR --}}

                    @if ($errors->any())

                        <div class="alert alert-danger admin-alert">

                            <div class="fw-semibold mb-1">

                                <i class="bi bi-exclamation-triangle-fill me-1"></i>

                                Authentication failed

                            </div>

                            <div>

                                The administrator email or password
                                is incorrect. Please verify your
                                credentials and try again.

                            </div>

                        </div>

                    @endif


                    {{-- =================================================
                         LOGIN FORM
                    ================================================== --}}

                    <form
                        method="POST"
                        action="{{ route('login') }}"
                    >

                        @csrf


                        {{-- EMAIL --}}

                        <div class="form-group">

                            <label
                                for="email"
                                class="form-label"
                            >

                                Administrator Email Address

                                <span class="text-danger">*</span>

                            </label>


                            <div class="input-wrapper">

                                <i class="bi bi-person-badge input-icon"></i>


                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="admin-form-control @error('email') is-invalid @enderror"
                                    placeholder="Enter administrator email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                >

                            </div>


                            @error('email')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- PASSWORD --}}

                        <div class="form-group">

                            <label
                                for="password"
                                class="form-label"
                            >

                                Password

                                <span class="text-danger">*</span>

                            </label>


                            <div class="input-wrapper">

                                <i class="bi bi-key input-icon"></i>


                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    class="admin-form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter administrator password"
                                    required
                                    autocomplete="current-password"
                                >

                            </div>


                            @error('password')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- =================================================
                             REMEMBER + PASSWORD
                        ================================================== --}}

                        <div class="login-options">


                            <label
                                for="remember_me"
                                class="remember-label"
                            >

                                <input
                                    id="remember_me"
                                    type="checkbox"
                                    class="form-check-input remember-checkbox"
                                    name="remember"
                                >

                                <span>
                                    Keep me signed in
                                </span>

                            </label>


                            @if (Route::has('password.request'))

                                <a
                                    href="{{ route('password.request') }}"
                                    class="forgot-link"
                                >

                                    Forgot password?

                                </a>

                            @endif


                        </div>


                        {{-- =================================================
                             LOGIN BUTTON
                        ================================================== --}}

                        <button
                            type="submit"
                            class="btn btn-admin-login"
                        >

                            <i class="bi bi-box-arrow-in-right me-2"></i>

                            Sign In to Administration Portal

                        </button>


                    </form>


                    {{-- =================================================
                         ADMINISTRATOR NOTICE
                    ================================================== --}}

                    <div class="admin-notice">

                        <i
                            class="bi bi-info-circle-fill admin-notice-icon"
                        ></i>


                        <p class="admin-notice-text">

                            This area is restricted to authorized
                            BMRC Journal administrators and editorial
                            management personnel. Unauthorized access
                            is prohibited.

                        </p>

                    </div>


                    {{-- =================================================
                         SECURITY NOTICE
                    ================================================== --}}

                    <div class="security-notice">

                        <i class="bi bi-shield-check"></i>

                        <div>

                            For security purposes, never share your
                            administrator credentials. Always sign out
                            after completing administrative activities,
                            particularly when using a shared computer.

                        </div>

                    </div>


                </div>

            </div>


            {{-- ====================================================
                 FOOTER
            ===================================================== --}}

            <footer class="journal-footer">

                <div class="footer-journal-name">
                    BMRC Journal
                </div>


                <div class="footer-institution">

                    Bangladesh Medical Research Council (BMRC)

                </div>


                <div class="footer-system">

                    Online Editorial &amp; Journal Management System

                </div>


                <p class="footer-copy">

                    © {{ date('Y') }} BMRC.
                    All rights reserved.

                </p>

            </footer>


        </div>

    </main>

</div>

@endsection