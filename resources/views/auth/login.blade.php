@extends('layouts.app')

@section('title', 'Administrator Login - BMRC Journal')

@section('content')

<style>

    :root {
        --bmrc-navy: #14263a;
        --bmrc-navy-dark: #0e1b2a;
        --bmrc-slate: #405064;
        --bmrc-gold: #b8945f;
        --bmrc-gold-soft: #f7f1e8;
        --bmrc-bg: #f4f6f8;
        --bmrc-border: #dfe4e9;
        --bmrc-text: #2b3642;
        --bmrc-muted: #73808c;
        --bmrc-white: #ffffff;
        --bmrc-success: #198754;
    }


    body {
        background: var(--bmrc-bg);
    }


    .admin-login-page {
        min-height: 100vh;
        background:
            linear-gradient(
                to bottom,
                #ffffff 0,
                #ffffff 205px,
                var(--bmrc-bg) 205px,
                var(--bmrc-bg) 100%
            );
        padding-bottom: 48px;
    }


    .admin-container {
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 22px;
    }


    /* ============================================================
       MASTHEAD
    ============================================================ */

    .journal-masthead {
        background: #ffffff;
        border-bottom: 1px solid #e2e6ea;
        box-shadow: 0 2px 8px rgba(18, 34, 52, .025);
        margin-bottom: 30px;
    }


    .masthead-inner {
        min-height: 112px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 24px;

        padding: 18px 0;
    }


    .masthead-brand {
        display: flex;
        align-items: center;
        min-width: 0;
        gap: 18px;
    }


    .bmrc-logo-wrap {
        width: 68px;
        height: 68px;
        flex: 0 0 68px;

        display: flex;
        align-items: center;
        justify-content: center;
    }


    .bmrc-logo {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }


    .masthead-divider {
        width: 1px;
        height: 55px;
        background: #d9dee4;
        flex-shrink: 0;
    }


    .institution-name {
        color: #536171;

        font-size: 11px;
        font-weight: 700;

        letter-spacing: .075em;

        margin-bottom: 4px;
    }


    .journal-name {
        margin: 0;

        color: var(--bmrc-navy);

        font-family: Georgia, "Times New Roman", serif;

        font-size: 28px;
        font-weight: 700;
        line-height: 1.1;
    }


    .journal-type {
        margin-top: 5px;

        color: var(--bmrc-muted);

        font-size: 11px;
        letter-spacing: .02em;
    }


    .masthead-right {
        text-align: right;
        flex-shrink: 0;
    }


    .admin-label {
        display: inline-flex;
        align-items: center;

        gap: 7px;

        padding: 8px 13px;

        border-radius: 5px;

        background: var(--bmrc-navy);

        color: #ffffff;

        font-size: 9.5px;
        font-weight: 700;

        letter-spacing: .11em;

        text-transform: uppercase;
    }


    .admin-label i {
        color: #dfc08b;
        font-size: 13px;
    }


    .admin-label-subtitle {
        margin-top: 6px;

        color: #87919b;

        font-size: 10.5px;
    }


    /* ============================================================
       MAIN CONTENT
    ============================================================ */

    .admin-content {
        max-width: 900px;
        margin: 0 auto;
    }


    .admin-intro {
        text-align: center;
        margin-bottom: 22px;
    }


    .breadcrumb-line {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;

        gap: 6px;

        margin-bottom: 11px;

        color: #818b95;

        font-size: 10.5px;
    }


    .breadcrumb-line i {
        font-size: 8px;
        color: #aeb5bd;
    }


    .admin-page-title {
        margin: 0;

        color: var(--bmrc-navy);

        font-family: Georgia, "Times New Roman", serif;

        font-size: 28px;
        font-weight: 700;
    }


    .admin-page-subtitle {
        max-width: 610px;

        margin: 6px auto 0;

        color: var(--bmrc-muted);

        font-size: 12px;
        line-height: 1.55;
    }


    /* ============================================================
       LOGIN PANEL
    ============================================================ */

    .admin-login-card {
        display: grid;
        grid-template-columns: 290px minmax(0, 1fr);

        overflow: hidden;

        background: #ffffff;

        border: 1px solid var(--bmrc-border);
        border-radius: 10px;

        box-shadow:
            0 14px 38px rgba(18, 34, 52, .075);
    }


    /* ============================================================
       LEFT SECURITY PANEL
    ============================================================ */

    .admin-security-panel {
        position: relative;

        display: flex;
        flex-direction: column;
        justify-content: space-between;

        min-height: 500px;

        padding: 34px 28px;

        background:
            linear-gradient(
                155deg,
                var(--bmrc-navy-dark),
                var(--bmrc-navy)
            );

        color: #ffffff;
    }


    .admin-security-panel::before {
        content: "";

        position: absolute;
        top: 0;
        right: 0;

        width: 3px;
        height: 100%;

        background:
            linear-gradient(
                to bottom,
                transparent,
                var(--bmrc-gold),
                transparent
            );

        opacity: .85;
    }


    .security-brand-icon {
        width: 54px;
        height: 54px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: rgba(255, 255, 255, .08);
        border: 1px solid rgba(255, 255, 255, .15);

        color: #e2c38e;

        font-size: 23px;

        margin-bottom: 20px;
    }


    .security-panel-title {
        margin-bottom: 9px;

        font-family: Georgia, "Times New Roman", serif;

        font-size: 21px;
        font-weight: 700;

        line-height: 1.25;
    }


    .security-panel-text {
        margin: 0;

        color: #c5ced7;

        font-size: 11px;
        line-height: 1.65;
    }


    .security-divider {
        height: 1px;

        margin: 24px 0;

        background: rgba(255,255,255,.11);
    }


    .security-feature {
        display: flex;
        align-items: flex-start;

        gap: 10px;

        margin-bottom: 15px;
    }


    .security-feature:last-child {
        margin-bottom: 0;
    }


    .security-feature-icon {
        width: 29px;
        height: 29px;

        flex: 0 0 29px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 6px;

        background: rgba(255,255,255,.07);

        color: #dfbf88;

        font-size: 12px;
    }


    .security-feature-title {
        margin-bottom: 2px;

        color: #f4f6f8;

        font-size: 10.5px;
        font-weight: 600;
    }


    .security-feature-text {
        color: #aeb9c4;

        font-size: 9.5px;
        line-height: 1.45;
    }


    .security-panel-footer {
        padding-top: 24px;

        border-top: 1px solid rgba(255,255,255,.11);

        color: #9ea9b4;

        font-size: 9.5px;
        line-height: 1.55;
    }


    /* ============================================================
       LOGIN FORM SIDE
    ============================================================ */

    .admin-form-panel {
        padding: 38px 42px 34px;
    }


    .form-panel-heading {
        margin-bottom: 26px;
    }


    .form-panel-heading h3 {
        margin: 0 0 5px;

        color: var(--bmrc-navy);

        font-size: 20px;
        font-weight: 700;
    }


    .form-panel-heading p {
        margin: 0;

        color: var(--bmrc-muted);

        font-size: 11.5px;
        line-height: 1.5;
    }


    /* ============================================================
       ALERTS
    ============================================================ */

    .admin-alert {
        border-radius: 6px;

        padding: 10px 12px;

        font-size: 11px;

        margin-bottom: 18px;
    }


    /* ============================================================
       FORM
    ============================================================ */

    .form-group {
        margin-bottom: 18px;
    }


    .form-label {
        margin-bottom: 6px;

        color: #364454;

        font-size: 11.5px;
        font-weight: 600;
    }


    .input-wrapper {
        position: relative;
    }


    .input-icon {
        position: absolute;

        left: 13px;
        top: 50%;

        transform: translateY(-50%);

        z-index: 2;

        color: #8c97a3;

        font-size: 14px;

        pointer-events: none;
    }


    .admin-form-control {
        width: 100%;
        min-height: 45px;

        padding:
            9px 40px
            9px 39px;

        border: 1px solid #ccd3da;
        border-radius: 6px;

        background: #ffffff;

        color: #2d3946;

        font-size: 12.5px;

        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            background .2s ease;
    }


    .admin-form-control::placeholder {
        color: #a0a9b2;
    }


    .admin-form-control:focus {
        outline: none;

        border-color: var(--bmrc-gold);

        box-shadow:
            0 0 0 3px rgba(184, 148, 95, .12);

        background: #fffdf9;
    }


    .password-toggle {
        position: absolute;

        right: 11px;
        top: 50%;

        transform: translateY(-50%);

        width: 29px;
        height: 29px;

        border: 0;

        background: transparent;

        color: #808b96;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 4px;
    }


    .password-toggle:hover {
        color: var(--bmrc-navy);
        background: #f1f3f5;
    }


    .invalid-feedback {
        font-size: 10px;
    }


    /* ============================================================
       OPTIONS
    ============================================================ */

    .login-options {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 12px;

        margin-top: 2px;
        margin-bottom: 21px;
    }


    .remember-label {
        display: inline-flex;
        align-items: center;

        gap: 7px;

        color: #687481;

        font-size: 11px;

        cursor: pointer;
    }


    .remember-checkbox {
        width: 14px;
        height: 14px;
    }


    .remember-checkbox:checked {
        background-color: var(--bmrc-navy);
        border-color: var(--bmrc-navy);
    }


    .forgot-link {
        color: var(--bmrc-slate);

        font-size: 11px;
        font-weight: 600;

        text-decoration: none;
    }


    .forgot-link:hover {
        color: var(--bmrc-gold);
    }


    /* ============================================================
       BUTTON
    ============================================================ */

    .btn-admin-login {
        width: 100%;
        min-height: 46px;

        border: 1px solid var(--bmrc-navy);
        border-radius: 6px;

        background:
            linear-gradient(
                135deg,
                #172a3f,
                #223b54
            );

        color: #ffffff;

        font-size: 12px;
        font-weight: 700;

        letter-spacing: .015em;

        transition:
            transform .2s ease,
            box-shadow .2s ease,
            background .2s ease;
    }


    .btn-admin-login:hover {
        color: #ffffff;

        transform: translateY(-1px);

        background:
            linear-gradient(
                135deg,
                #20384f,
                #2a4863
            );

        box-shadow:
            0 6px 16px rgba(20, 38, 58, .15);
    }


    /* ============================================================
       NOTICE
    ============================================================ */

    .admin-notice {
        display: flex;
        align-items: flex-start;

        gap: 9px;

        margin-top: 18px;
        padding: 11px 12px;

        border-radius: 5px;

        border: 1px solid #eadfcf;
        border-left: 3px solid var(--bmrc-gold);

        background: var(--bmrc-gold-soft);
    }


    .admin-notice-icon {
        color: #9c7a42;

        font-size: 13px;

        margin-top: 1px;
    }


    .admin-notice-text {
        margin: 0;

        color: #6e604e;

        font-size: 10px;
        line-height: 1.55;
    }


    .security-note {
        display: flex;
        align-items: flex-start;

        gap: 8px;

        margin-top: 15px;

        color: #808a94;

        font-size: 9.5px;
        line-height: 1.55;
    }


    .security-note i {
        margin-top: 1px;

        color: #687481;

        font-size: 12px;
    }


    /* ============================================================
       FOOTER
    ============================================================ */

    .journal-footer {
        max-width: 900px;

        margin: 31px auto 0;

        padding-top: 18px;

        border-top: 1px solid #dde2e7;

        text-align: center;
    }


    .footer-journal-name {
        color: var(--bmrc-navy);

        font-family: Georgia, "Times New Roman", serif;

        font-size: 13px;
        font-weight: 700;

        margin-bottom: 3px;
    }


    .footer-institution {
        color: #6f7a84;

        font-size: 10.5px;

        margin-bottom: 2px;
    }


    .footer-system {
        color: #929ba4;

        font-size: 9.5px;

        margin-bottom: 2px;
    }


    .footer-copy {
        color: #a2aab3;

        font-size: 9.5px;

        margin: 0;
    }


    /* ============================================================
       RESPONSIVE
    ============================================================ */

    @media (max-width: 991px) {

        .admin-login-card {
            grid-template-columns: 1fr;
        }

        .admin-security-panel {
            min-height: auto;
        }

        .security-panel-footer {
            margin-top: 20px;
        }

    }


    @media (max-width: 767px) {

        .admin-login-page {
            background:
                linear-gradient(
                    to bottom,
                    #ffffff 0,
                    #ffffff 180px,
                    var(--bmrc-bg) 180px,
                    var(--bmrc-bg) 100%
                );
        }


        .admin-container {
            padding: 0 14px;
        }


        .journal-masthead {
            margin-bottom: 24px;
        }


        .masthead-inner {
            min-height: auto;
            padding: 14px 0;
            gap: 12px;
        }


        .masthead-brand {
            gap: 11px;
        }


        .bmrc-logo-wrap {
            width: 54px;
            height: 54px;

            flex-basis: 54px;
        }


        .masthead-divider {
            height: 43px;
        }


        .institution-name {
            font-size: 8px;
        }


        .journal-name {
            font-size: 20px;
        }


        .journal-type {
            font-size: 8.5px;
        }


        .masthead-right {
            display: none;
        }


        .admin-page-title {
            font-size: 24px;
        }


        .admin-page-subtitle {
            font-size: 11px;
        }


        .admin-security-panel {
            padding: 26px 22px;
        }


        .admin-form-panel {
            padding: 28px 22px;
        }

    }


    @media (max-width: 420px) {

        .login-options {
            flex-direction: column;
            align-items: flex-start;
            gap: 9px;
        }

    }

</style>

<div class="admin-login-page">

{{-- ============================================================
    HEADER
============================================================ --}}

<header class="journal-masthead">

    <div class="admin-container">

        <div class="masthead-inner">

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


            <div class="masthead-right">

                <div class="admin-label">

                    <i class="bi bi-shield-lock-fill"></i>

                    Journal Administration

                </div>

                <div class="admin-label-subtitle">
                    Secure Administrative Portal
                </div>

            </div>

        </div>

    </div>

</header>


{{-- ============================================================
    MAIN
============================================================ --}}

<main class="admin-container">

    <div class="admin-content">

        {{-- ====================================================
            INTRO
        ===================================================== --}}

        <div class="admin-intro">

            <div class="breadcrumb-line">

                <span>BMRC Journal</span>

                <i class="bi bi-chevron-right"></i>

                <span>Administration</span>

                <i class="bi bi-chevron-right"></i>

                <span>Secure Login</span>

            </div>


            <h2 class="admin-page-title">
                System Administrator
            </h2>


            <p class="admin-page-subtitle">
                Secure access to the BMRC Journal editorial and
                administrative management system.
            </p>

        </div>


        {{-- ====================================================
            LOGIN PANEL
        ===================================================== --}}

        <div class="admin-login-card">

            {{-- ================================================
                LEFT SECURITY PANEL
            ================================================= --}}

            <aside class="admin-security-panel">

                <div>

                    <div class="security-brand-icon">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>


                    <div class="security-panel-title">
                        Journal Administration Portal
                    </div>


                    <p class="security-panel-text">
                        Restricted access for authorized BMRC Journal
                        administrative and editorial personnel.
                    </p>


                    <div class="security-divider"></div>


                    <div class="security-feature">

                        <div class="security-feature-icon">
                            <i class="bi bi-person-check"></i>
                        </div>

                        <div>

                            <div class="security-feature-title">
                                Authorized Personnel
                            </div>

                            <div class="security-feature-text">
                                Access is limited to approved administrative users.
                            </div>

                        </div>

                    </div>


                    <div class="security-feature">

                        <div class="security-feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>

                        <div>

                            <div class="security-feature-title">
                                Secure Access
                            </div>

                            <div class="security-feature-text">
                                Account credentials are required for all administrative activities.
                            </div>

                        </div>

                    </div>


                    <div class="security-feature">

                        <div class="security-feature-icon">
                            <i class="bi bi-journal-check"></i>
                        </div>

                        <div>

                            <div class="security-feature-title">
                                Journal Management
                            </div>

                            <div class="security-feature-text">
                                Manage editorial, reviewer, submission and administrative workflows.
                            </div>

                        </div>

                    </div>

                </div>


                <div class="security-panel-footer">

                    <i class="bi bi-lock-fill me-1"></i>

                    Bangladesh Medical Research Council<br>

                    Online Editorial &amp; Journal Management System

                </div>

            </aside>


            {{-- ================================================
                LOGIN FORM
            ================================================= --}}

            <section class="admin-form-panel">

                <div class="form-panel-heading">

                    <h3>
                        Administrator Sign In
                    </h3>

                    <p>
                        Enter your authorized administrator credentials
                        to continue to the management portal.
                    </p>

                </div>


                {{-- SESSION STATUS --}}

                @if (session('status'))

                    <div class="alert alert-success admin-alert">

                        <i class="bi bi-check-circle-fill me-2"></i>

                        {{ session('status') }}

                    </div>

                @endif


                {{-- AUTHENTICATION ERROR --}}

                @if ($errors->any())

                    <div class="alert alert-danger admin-alert">

                        <div class="fw-semibold mb-1">

                            <i class="bi bi-exclamation-triangle-fill me-1"></i>

                            Authentication failed

                        </div>

                        <div>
                            The administrator email or password is incorrect.
                            Please verify your credentials and try again.
                        </div>

                    </div>

                @endif


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
                                class="admin-form-control
                                    @error('email')
                                        is-invalid
                                    @enderror"
                                placeholder="Enter administrator email address"
                                required
                                autofocus
                                autocomplete="username"
                            >

                        </div>


                        @error('email')

                            <div class="invalid-feedback d-block">
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
                                class="admin-form-control
                                    @error('password')
                                        is-invalid
                                    @enderror"
                                placeholder="Enter administrator password"
                                required
                                autocomplete="current-password"
                            >


                            <button
                                type="button"
                                class="password-toggle"
                                onclick="togglePassword()"
                                aria-label="Show or hide password"
                            >
                                <i
                                    class="bi bi-eye"
                                    id="passwordToggleIcon"
                                ></i>
                            </button>

                        </div>


                        @error('password')

                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- OPTIONS --}}

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


                    {{-- LOGIN BUTTON --}}

                    <button
                        type="submit"
                        class="btn btn-admin-login"
                    >

                        <i class="bi bi-box-arrow-in-right me-2"></i>

                        Sign In to Administration Portal

                    </button>

                </form>


                {{-- NOTICE --}}

                <div class="admin-notice">

                    <i class="bi bi-info-circle-fill admin-notice-icon"></i>

                    <p class="admin-notice-text">
                        This portal is restricted to authorized BMRC Journal
                        administrators and editorial management personnel.
                        Unauthorized access is prohibited.
                    </p>

                </div>


                {{-- SECURITY NOTE --}}

                <div class="security-note">

                    <i class="bi bi-shield-check"></i>

                    <div>
                        Never share your administrator credentials.
                        Always sign out after completing administrative
                        activities, especially on shared devices.
                    </div>

                </div>

            </section>

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
                © {{ date('Y') }} BMRC. All rights reserved.
            </p>

        </footer>

    </div>

</main>


</div>

<script>

    function togglePassword()
    {
        const passwordInput =
            document.getElementById('password');

        const icon =
            document.getElementById('passwordToggleIcon');


        if (passwordInput.type === 'password') {

            passwordInput.type = 'text';

            icon.classList.remove('bi-eye');

            icon.classList.add('bi-eye-slash');

        } else {

            passwordInput.type = 'password';

            icon.classList.remove('bi-eye-slash');

            icon.classList.add('bi-eye');

        }
    }

</script>

@endsection
