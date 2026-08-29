@extends('layouts.app')

@section('title', 'Author Registration | BMRC Journal')

@section('content')

<style>

/* =====================================================
   BMRC JOURNAL REGISTRATION
===================================================== */

:root {
    --bmrc-navy: #123B5D;
    --bmrc-dark: #0B2942;
    --bmrc-teal: #0F766E;
    --bmrc-light: #F4F8FA;
    --bmrc-border: #D9E2E8;
    --bmrc-text: #243746;
    --bmrc-muted: #667785;
}


/* Page */

.registration-page {
    min-height: calc(100vh - 70px);
    background:
        linear-gradient(
            135deg,
            #F7FAFC 0%,
            #EEF5F7 100%
        );

    padding: 50px 0;
}


/* Main Card */

.registration-card {

    border: 1px solid var(--bmrc-border);

    border-radius: 16px;

    background: #ffffff;

    overflow: hidden;

    box-shadow:
        0 10px 35px rgba(18, 59, 93, .08);
}


/* =====================================================
   HEADER
===================================================== */

.registration-header {

    background:
        linear-gradient(
            135deg,
            var(--bmrc-dark),
            var(--bmrc-navy)
        );

    color: #ffffff;

    padding: 28px 32px;

    position: relative;

    overflow: hidden;
}


.registration-header::after {

    content: "";

    position: absolute;

    width: 180px;

    height: 180px;

    border-radius: 50%;

    background: rgba(255,255,255,.05);

    right: -70px;

    top: -80px;
}


.bmrc-logo {

    width: 64px;

    height: 64px;

    background: #ffffff;

    border-radius: 12px;

    padding: 6px;

    object-fit: contain;

    box-shadow:
        0 4px 15px rgba(0,0,0,.15);
}


.registration-header h3 {

    font-size: 1.35rem;

    font-weight: 700;

    margin: 0 0 4px;
}


.registration-header p {

    font-size: .88rem;

    color: rgba(255,255,255,.75);

    margin: 0;
}


/* =====================================================
   BODY
===================================================== */

.registration-body {

    padding: 38px;
}


/* Intro */

.registration-intro {

    padding-bottom: 24px;

    margin-bottom: 30px;

    border-bottom: 1px solid #E8EDF1;
}


.registration-intro h4 {

    color: var(--bmrc-dark);

    font-size: 1.25rem;

    font-weight: 700;

    margin-bottom: 7px;
}


.registration-intro p {

    color: var(--bmrc-muted);

    font-size: .9rem;

    line-height: 1.7;

    margin: 0;
}


/* =====================================================
   SECTION TITLE
===================================================== */

.section-title {

    display: flex;

    align-items: center;

    gap: 10px;

    color: var(--bmrc-dark);

    font-size: .98rem;

    font-weight: 700;

    margin-bottom: 20px;

    padding-bottom: 11px;

    border-bottom: 1px solid #E7EDF1;
}


.section-title i {

    color: var(--bmrc-teal);

    font-size: 1.1rem;
}


/* =====================================================
   FORM
===================================================== */

.form-label {

    color: var(--bmrc-text);

    font-size: .86rem;

    font-weight: 600;

    margin-bottom: 7px;
}


.required {

    color: #C62828;
}


.input-group-text {

    background: #F7F9FA;

    border-color: var(--bmrc-border);

    color: var(--bmrc-muted);

    min-width: 44px;

    justify-content: center;
}


.form-control {

    min-height: 45px;

    border-color: var(--bmrc-border);

    border-radius: 7px;

    color: var(--bmrc-text);

    font-size: .9rem;

    background: #ffffff;
}


.form-control:focus {

    border-color: var(--bmrc-teal);

    box-shadow:
        0 0 0 .2rem rgba(15,118,110,.10);
}


.form-control::placeholder {

    color: #A0ADB6;
}


.form-text {

    color: #7A8994;

    font-size: .76rem;

    margin-top: 6px;
}


/* =====================================================
   PASSWORD BUTTON
===================================================== */

.password-toggle {

    min-width: 45px;

    border-color: var(--bmrc-border);

    color: #667785;

    background: #F7F9FA;
}


.password-toggle:hover {

    background: #EEF3F5;

    color: var(--bmrc-navy);
}


/* =====================================================
   PROFILE STEPS
===================================================== */

.profile-box {

    background: #F7FAFB;

    border: 1px solid #DCE6EA;

    border-radius: 10px;

    padding: 20px;

    margin: 28px 0;
}


.profile-title {

    color: var(--bmrc-dark);

    font-size: .9rem;

    font-weight: 700;

    margin-bottom: 18px;
}


.profile-title i {

    color: var(--bmrc-teal);

    margin-right: 7px;
}


.step {

    display: flex;

    align-items: center;

    gap: 10px;
}


.step-number {

    width: 30px;

    height: 30px;

    min-width: 30px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    background: #E1E8EC;

    color: #667785;

    font-size: .78rem;

    font-weight: 700;
}


.step-number.active {

    background: var(--bmrc-teal);

    color: #ffffff;

    box-shadow:
        0 3px 10px rgba(15,118,110,.20);
}


.step-text {

    font-size: .78rem;

    color: #536571;

    line-height: 1.35;
}


.step-text strong {

    display: block;

    color: var(--bmrc-text);

    font-size: .8rem;
}


/* =====================================================
   NOTICE
===================================================== */

.registration-notice {

    background: #F1F7F7;

    border: 1px solid #D6E9E7;

    border-left: 4px solid var(--bmrc-teal);

    border-radius: 7px;

    padding: 14px 16px;

    color: #52646F;

    font-size: .82rem;

    line-height: 1.6;

    margin-bottom: 25px;
}


.registration-notice i {

    color: var(--bmrc-teal);

    font-size: 1rem;
}


/* =====================================================
   BUTTON
===================================================== */

.registration-btn {

    min-height: 48px;

    background:
        linear-gradient(
            135deg,
            var(--bmrc-navy),
            var(--bmrc-teal)
        );

    border: none;

    border-radius: 7px;

    font-size: .92rem;

    font-weight: 600;

    transition: .2s ease;
}


.registration-btn:hover {

    transform: translateY(-1px);

    box-shadow:
        0 6px 18px rgba(18,59,93,.20);
}


/* =====================================================
   LOGIN
===================================================== */

.login-section {

    border-top: 1px solid #E8EDF1;

    margin-top: 28px;

    padding-top: 22px;

    text-align: center;
}


.login-section span {

    color: var(--bmrc-muted);

    font-size: .82rem;
}


.login-link {

    color: var(--bmrc-navy);

    font-size: .84rem;

    font-weight: 700;

    text-decoration: none;
}


.login-link:hover {

    color: var(--bmrc-teal);

    text-decoration: underline;
}


/* =====================================================
   FOOTER NOTE
===================================================== */

.portal-footer {

    text-align: center;

    color: #81909A;

    font-size: .75rem;

    margin-top: 18px;
}


/* =====================================================
   ALERT
===================================================== */

.alert {

    border-radius: 8px;

    font-size: .84rem;
}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 767px) {

    .registration-page {

        padding: 25px 0;
    }


    .registration-header {

        padding: 23px 20px;
    }


    .registration-body {

        padding: 25px 20px;
    }


    .bmrc-logo {

        width: 56px;

        height: 56px;
    }


    .registration-header h3 {

        font-size: 1.15rem;
    }


    .registration-intro h4 {

        font-size: 1.1rem;
    }


    .step {

        align-items: flex-start;
    }


    .step-number {

        margin-top: 1px;
    }

}


@media (max-width: 575px) {

    .registration-page {

        padding: 15px 0;
    }


    .registration-card {

        border-radius: 10px;
    }


    .registration-body {

        padding: 22px 17px;
    }


    .registration-header {

        padding: 20px 17px;
    }

}

</style>


<div class="registration-page">

<div class="container">

<div class="row justify-content-center">

<div class="col-12 col-md-10 col-lg-8 col-xl-7">


{{-- =====================================================
     MAIN CARD
===================================================== --}}

<div class="registration-card">


{{-- =====================================================
     HEADER
===================================================== --}}

<div class="registration-header">

    <div class="d-flex align-items-center gap-3 position-relative"
         style="z-index:2;">

        <img
            src="{{ asset('favicon.png') }}"
            alt="BMRC Logo"
            class="bmrc-logo"
        >

        <div>

            <h3>
                BMRC Journal
            </h3>

            <p>
                Author Registration Portal
            </p>

        </div>

    </div>

</div>


{{-- =====================================================
     BODY
===================================================== --}}

<div class="registration-body">


{{-- Introduction --}}

<div class="registration-intro">

    <h4>
        Create Your Author Account
    </h4>

    <p>
        Register to submit manuscripts and manage your
        scholarly publications through the BMRC Journal
        Online System.
    </p>

</div>


{{-- =====================================================
     VALIDATION ERRORS
===================================================== --}}

@if ($errors->any())

<div class="alert alert-danger mb-4">

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


{{-- Success --}}

@if(session('success'))

<div class="alert alert-success mb-4">

    <i class="bi bi-check-circle me-1"></i>

    {{ session('success') }}

</div>

@endif


{{-- =====================================================
     FORM
===================================================== --}}

<form method="POST"
      action="{{ route('author.register') }}">

@csrf


{{-- =====================================================
     ACCOUNT INFORMATION
===================================================== --}}

<div class="section-title">

    <i class="bi bi-person-vcard"></i>

    Account Information

</div>


<div class="row">


{{-- First Name --}}

<div class="col-md-6 mb-3">

<label class="form-label">

    First Name

    <span class="required">*</span>

</label>

<div class="input-group">

    <span class="input-group-text">

        <i class="bi bi-person"></i>

    </span>

    <input
        type="text"
        name="first_name"
        value="{{ old('first_name') }}"
        class="form-control @error('first_name') is-invalid @enderror"
        placeholder="Enter first name"
        autocomplete="given-name"
        required
    >

</div>

@error('first_name')

<div class="text-danger small mt-1">
    {{ $message }}
</div>

@enderror

</div>


{{-- Last Name --}}

<div class="col-md-6 mb-3">

<label class="form-label">

    Last Name

    <span class="required">*</span>

</label>

<div class="input-group">

    <span class="input-group-text">

        <i class="bi bi-person"></i>

    </span>

    <input
        type="text"
        name="last_name"
        value="{{ old('last_name') }}"
        class="form-control @error('last_name') is-invalid @enderror"
        placeholder="Enter last name"
        autocomplete="family-name"
        required
    >

</div>

@error('last_name')

<div class="text-danger small mt-1">
    {{ $message }}
</div>

@enderror

</div>


{{-- Email --}}

<div class="col-12 mb-3">

<label class="form-label">

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
        value="{{ old('email') }}"
        class="form-control @error('email') is-invalid @enderror"
        placeholder="name@example.com"
        autocomplete="email"
        required
    >

</div>

@error('email')

<div class="text-danger small mt-1">
    {{ $message }}
</div>

@enderror

<div class="form-text">

    Used for account authentication and official journal correspondence.

</div>

</div>


{{-- Mobile --}}

<div class="col-12 mb-4">

<label class="form-label">

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
        value="{{ old('mobile') }}"
        class="form-control @error('mobile') is-invalid @enderror"
        placeholder="01XXXXXXXXX"
        autocomplete="tel"
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


{{-- =====================================================
     SECURITY
===================================================== --}}

<div class="section-title">

    <i class="bi bi-shield-lock"></i>

    Account Security

</div>


<div class="row">


{{-- Password --}}

<div class="col-md-6 mb-3">

<label class="form-label">

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
        placeholder="Create password"
        autocomplete="new-password"
        required
    >

    <button
        type="button"
        class="btn password-toggle"
        onclick="togglePassword('password','passwordIcon')"
    >

        <i class="bi bi-eye"
           id="passwordIcon"></i>

    </button>

</div>

@error('password')

<div class="text-danger small mt-1">
    {{ $message }}
</div>

@enderror

<div class="form-text">
    Minimum 8 characters.
</div>

</div>


{{-- Confirm Password --}}

<div class="col-md-6 mb-4">

<label class="form-label">

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
        placeholder="Confirm password"
        autocomplete="new-password"
        required
    >

    <button
        type="button"
        class="btn password-toggle"
        onclick="togglePassword('password_confirmation','confirmIcon')"
    >

        <i class="bi bi-eye"
           id="confirmIcon"></i>

    </button>

</div>

</div>

</div>


{{-- =====================================================
     PROFILE COMPLETION
===================================================== --}}

<div class="profile-box">

<div class="profile-title">

    <i class="bi bi-list-check"></i>

    Author Profile Completion

</div>


<div class="row g-3">


<div class="col-md-4">

<div class="step">

    <span class="step-number active">
        1
    </span>

    <div class="step-text">

        <strong>
            Registration
        </strong>

        Basic account information

    </div>

</div>

</div>


<div class="col-md-4">

<div class="step">

    <span class="step-number">
        2
    </span>

    <div class="step-text">

        <strong>
            Personal Profile
        </strong>

        Personal information

    </div>

</div>

</div>


<div class="col-md-4">

<div class="step">

    <span class="step-number">
        3
    </span>

    <div class="step-text">

        <strong>
            Professional Profile
        </strong>

        Research information

    </div>

</div>

</div>


</div>

</div>


{{-- =====================================================
     INFORMATION NOTICE
===================================================== --}}

<div class="registration-notice">

<div class="d-flex gap-2">

    <i class="bi bi-info-circle-fill mt-1"></i>

    <div>

        <strong>After registration:</strong>

        Your BMRC Author ID will be generated automatically.
        You can then complete your personal, professional
        and research profile from the Author Dashboard.

    </div>

</div>

</div>


{{-- =====================================================
     SUBMIT
===================================================== --}}

<button
    type="submit"
    class="btn registration-btn text-white w-100"
>

    <i class="bi bi-person-plus me-2"></i>

    Create Author Account

</button>


</form>


{{-- =====================================================
     LOGIN
===================================================== --}}

<div class="login-section">

    <span>
        Already have an Author account?
    </span>

    <a
        href="{{ route('author.login') }}"
        class="login-link ms-1"
    >

        Sign in to Author Portal

        <i class="bi bi-arrow-right ms-1"></i>

    </a>

</div>


</div>

</div>


{{-- Portal Footer --}}

<div class="portal-footer">

    <div>
        BMRC Journal Online System
    </div>

    <div class="mt-1">
        Bangladesh Medical Research Council
    </div>

</div>


</div>

</div>

</div>

</div>


{{-- =====================================================
     PASSWORD TOGGLE
===================================================== --}}

<script>

function togglePassword(inputId, iconId)
{

    const input =
        document.getElementById(inputId);

    const icon =
        document.getElementById(iconId);


    if (input.type === "password") {

        input.type = "text";

        icon.classList.remove("bi-eye");

        icon.classList.add("bi-eye-slash");

    }

    else {

        input.type = "password";

        icon.classList.remove("bi-eye-slash");

        icon.classList.add("bi-eye");

    }

}

</script>

@endsection