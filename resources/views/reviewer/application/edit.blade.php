@extends('layouts.app')

@section('title', 'Reviewer Application - BMRC Journal')

@section('content')

<style>
    /* ============================================================
       BMRC JOURNAL — PROFESSIONAL REVIEWER APPLICATION
    ============================================================ */

    :root {
        --bmrc-navy: #12395b;
        --bmrc-blue: #1d5f91;
        --bmrc-light-blue: #edf5fb;
        --bmrc-border: #d9e2ec;
        --bmrc-text: #243447;
        --bmrc-muted: #667085;
        --bmrc-bg: #f4f6f8;
    }

    body {
        background: var(--bmrc-bg);
    }

    /* ============================================================
       PAGE
    ============================================================ */

    .reviewer-page {
        min-height: 100vh;
        padding-bottom: 60px;
        background:
            linear-gradient(
                to bottom,
                #ffffff 0,
                #ffffff 245px,
                #f4f6f8 245px,
                #f4f6f8 100%
            );
    }

    /* ============================================================
       MAIN HEADER CONTAINER
    ============================================================ */

    .reviewer-container {
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* ============================================================
       APPLICATION CONTENT — NARROWER / CENTERED
    ============================================================ */

    .reviewer-content {
        max-width: 1000px;
        margin: 0 auto;
    }

    /*
     * Application status is slightly narrower
     * to create more professional whitespace.
     */
    .application-status-wrapper {
        max-width: 900px;
        margin: 0 auto 24px;
    }

    /* ============================================================
       JOURNAL MASTHEAD
    ============================================================ */

    .journal-masthead {
        background: #ffffff;
        border-bottom: 1px solid #dfe5eb;
        margin-bottom: 28px;
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

    .masthead-divider {
        width: 1px;
        height: 62px;
        background: #d8e0e8;
    }

    .institution-name {
        color: #27364a;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: .02em;
        margin-bottom: 4px;
    }

    .journal-name {
        color: var(--bmrc-navy);
        font-family: Georgia, "Times New Roman", serif;
        font-size: 30px;
        font-weight: 700;
        line-height: 1.15;
        margin: 0;
    }

    .journal-type {
        color: #667085;
        font-size: 13px;
        margin-top: 5px;
        letter-spacing: .02em;
    }

    .masthead-right {
        text-align: right;
        flex-shrink: 0;
    }

    .peer-review-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 13px;
        border: 1px solid #c9d9e8;
        background: #f5f9fc;
        color: var(--bmrc-blue);
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .peer-review-label i {
        font-size: 14px;
    }

    .portal-text {
        margin-top: 8px;
        color: #7a8695;
        font-size: 12px;
    }

    /* ============================================================
       PAGE INTRO
    ============================================================ */

    .page-intro {
        margin-bottom: 24px;
    }

    .breadcrumb-line {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        color: #7b8794;
        font-size: 12px;
        margin-bottom: 12px;
    }

    .breadcrumb-line a {
        color: var(--bmrc-blue);
        text-decoration: none;
    }

    .breadcrumb-line a:hover {
        text-decoration: underline;
    }

    .breadcrumb-line i {
        font-size: 10px;
        color: #98a2b3;
    }

    .page-title {
        font-family: Georgia, "Times New Roman", serif;
        color: var(--bmrc-navy);
        font-size: 27px;
        font-weight: 700;
        margin: 0;
    }

    .page-subtitle {
        color: #667085;
        font-size: 14px;
        margin-top: 5px;
        margin-bottom: 0;
    }

    /* ============================================================
       APPLICATION STATUS
    ============================================================ */

    .application-info {
        background: #ffffff;
        border: 1px solid var(--bmrc-border);
        border-radius: 6px;
        padding: 18px 22px;
        box-shadow: 0 2px 7px rgba(16, 24, 40, .04);
    }

    .application-id-label {
        color: #7a8695;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .06em;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .application-id {
        color: var(--bmrc-navy);
        font-family: "Courier New", monospace;
        font-size: 17px;
        font-weight: 700;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        font-size: 11px;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 4px;
        letter-spacing: .02em;
    }

    /* ============================================================
       ALERT
    ============================================================ */

    .journal-alert {
        background: #f8fbfd;
        border: 1px solid #d8e7f2;
        border-left: 4px solid var(--bmrc-blue);
        border-radius: 5px;
        padding: 15px 18px;
        margin-bottom: 24px;
    }

    .journal-alert-icon {
        color: var(--bmrc-blue);
        font-size: 20px;
        margin-right: 12px;
    }

    .journal-alert h6 {
        color: var(--bmrc-navy);
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .journal-alert p {
        color: #667085;
        font-size: 12px;
        line-height: 1.6;
        margin: 0;
    }

    /* ============================================================
       CARDS
    ============================================================ */

    .application-card {
        background: #ffffff;
        border: 1px solid var(--bmrc-border);
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: 0 2px 7px rgba(16, 24, 40, .035);
    }

    .application-card .card-header {
        background: #ffffff;
        border-bottom: 1px solid #e3e8ee;
        padding: 17px 21px;
    }

    .application-card .card-body {
        padding: 22px;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .section-icon {
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
        border: 1px solid #d7e4ee;
        border-radius: 5px;
        background: #f5f9fc;
        color: var(--bmrc-blue);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .section-title {
        color: var(--bmrc-navy);
        font-family: Georgia, "Times New Roman", serif;
        font-size: 17px;
        font-weight: 700;
        line-height: 1.25;
        margin-bottom: 2px;
    }

    .section-description {
        color: #7a8695;
        font-size: 11px;
        line-height: 1.4;
    }

    .important-section {
        border-color: #bdd5e8;
    }

    .important-section .card-header {
        background: #f6fafd;
        border-bottom-color: #d7e6f1;
    }

    .important-section .section-icon {
        background: #eaf4fb;
        border-color: #c8dfef;
    }

    /* ============================================================
       FORM
    ============================================================ */

    .form-label {
        color: #344054;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        min-height: 43px;
        border: 1px solid #cfd8e3;
        border-radius: 4px;
        color: #344054;
        background-color: #ffffff;
        font-size: 13px;
        box-shadow: none;
    }

    textarea.form-control {
        min-height: auto;
        line-height: 1.6;
    }

    .form-control::placeholder {
        color: #98a2b3;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #4b83ad;
        box-shadow: 0 0 0 3px rgba(75, 131, 173, .10);
    }

    .form-control.bg-light {
        background: #f7f8fa !important;
    }

    .form-text {
        color: #7a8695;
        font-size: 11px;
        line-height: 1.5;
    }

    .invalid-feedback {
        font-size: 11px;
    }

    /* ============================================================
       EXISTING FILE
    ============================================================ */

    .existing-file {
        background: #f5fbf7;
        border: 1px solid #cce8d5;
        border-radius: 5px;
        padding: 14px;
    }

    /* ============================================================
       DECLARATION
    ============================================================ */

    .declaration-box {
        background: #f8f9fb;
        border: 1px solid #e1e6eb;
        border-radius: 5px;
        padding: 17px 18px;
        margin-bottom: 18px;
    }

    .declaration-box p {
        color: #475467;
        font-size: 12px;
        line-height: 1.75;
    }

    .form-check-label {
        color: #344054;
        font-size: 13px;
        line-height: 1.5;
    }

    .form-check-input {
        border-color: #b7c3cf;
    }

    .form-check-input:checked {
        background-color: var(--bmrc-blue);
        border-color: var(--bmrc-blue);
    }

    /* ============================================================
       SUBMIT
    ============================================================ */

    .submit-card {
        background: #ffffff;
        border: 1px solid var(--bmrc-border);
        border-radius: 6px;
        box-shadow: 0 2px 7px rgba(16, 24, 40, .035);
        margin-bottom: 20px;
    }

    .btn {
        min-height: 43px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 600;
        padding-left: 18px;
        padding-right: 18px;
    }

    .btn-primary {
        background: var(--bmrc-blue);
        border-color: var(--bmrc-blue);
    }

    .btn-primary:hover,
    .btn-primary:focus {
        background: #174e78;
        border-color: #174e78;
    }

    .btn-outline-secondary {
        color: #475467;
        border-color: #cbd5df;
    }

    .btn-outline-secondary:hover {
        color: #344054;
        background: #f5f7f9;
        border-color: #aebbc8;
    }

    /* ============================================================
       FOOTER
    ============================================================ */

    .journal-footer {
        margin-top: 38px;
        padding-top: 22px;
        border-top: 1px solid #dce2e8;
        text-align: center;
    }

    .footer-journal-name {
        color: var(--bmrc-navy);
        font-family: Georgia, "Times New Roman", serif;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .footer-institution {
        color: #667085;
        font-size: 11px;
        margin-bottom: 3px;
    }

    .footer-copy {
        color: #98a2b3;
        font-size: 10px;
        margin: 0;
    }

    /* ============================================================
       MOBILE
    ============================================================ */

    @media (max-width: 767px) {

        .reviewer-page {
            background:
                linear-gradient(
                    to bottom,
                    #ffffff 0,
                    #ffffff 205px,
                    #f4f6f8 205px,
                    #f4f6f8 100%
                );
        }

        .reviewer-container {
            padding: 0 13px;
        }

        .reviewer-content {
            max-width: 100%;
        }

        .application-status-wrapper {
            max-width: 100%;
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
            font-size: 10px;
        }

        .journal-name {
            font-size: 21px;
        }

        .journal-type {
            font-size: 10px;
        }

        .masthead-right {
            display: none;
        }

        .page-intro {
            margin-top: 22px;
        }

        .page-title {
            font-size: 23px;
        }

        .page-subtitle {
            font-size: 12px;
        }

        .application-info {
            padding: 15px;
        }

        .application-card .card-header {
            padding: 14px 15px;
        }

        .application-card .card-body {
            padding: 16px 15px;
        }

        .section-icon {
            width: 34px;
            height: 34px;
            flex-basis: 34px;
            font-size: 15px;
        }

        .section-title {
            font-size: 15px;
        }

        .section-description {
            font-size: 10px;
        }

        .journal-alert {
            padding: 13px;
        }

        .submit-card .card-body {
            padding: 14px !important;
        }
    }
</style>


<div class="reviewer-page">

    {{-- ============================================================
         PROFESSIONAL JOURNAL MASTHEAD
    ============================================================= --}}

    <header class="journal-masthead">

        <div class="reviewer-container">

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

                    <div class="peer-review-label">

                        <i class="bi bi-shield-check"></i>

                        ONLINE PEER REVIEW

                    </div>

                    <div class="portal-text">
                        Reviewer Management Portal
                    </div>

                </div>

            </div>

        </div>

    </header>


    {{-- ============================================================
         CENTERED APPLICATION CONTENT
    ============================================================= --}}

    <main class="reviewer-container">

        <div class="reviewer-content">


            {{-- ======================================================
                 PAGE INTRO
            ======================================================= --}}

            <div class="page-intro">

                <div class="breadcrumb-line">

                    <a href="{{ route('reviewer.login') }}">
                        Reviewer Portal
                    </a>

                    <i class="bi bi-chevron-right"></i>

                    <span>
                        Reviewer Application
                    </span>

                </div>

                <h2 class="page-title">
                    Reviewer Application
                </h2>

                <p class="page-subtitle">
                    Register your academic and professional expertise for consideration
                    by the BMRC Journal Editorial Office.
                </p>

            </div>


            {{-- ======================================================
                 APPLICATION ID / STATUS
            ======================================================= --}}

            <div class="application-status-wrapper">

                <div class="application-info">

                    <div class="row align-items-center">

                        <div class="col-md-6">

                            <div class="application-id-label">
                                Reviewer Application ID
                            </div>

                            <div class="application-id">
                                {{ $profile->application_id ?? 'Not Generated' }}
                            </div>

                        </div>


                        <div class="col-md-6 text-md-end mt-3 mt-md-0">

                            <div class="application-id-label mb-2">
                                Application Status
                            </div>

                            @php

                                $status = strtolower(
                                    $profile->status ?? 'pending'
                                );

                                $statusClass = match($status) {

                                    'approved' =>
                                        'bg-success',

                                    'rejected' =>
                                        'bg-danger',

                                    'submitted' =>
                                        'bg-primary',

                                    'under review' =>
                                        'bg-info text-dark',

                                    'pending' =>
                                        'bg-warning text-dark',

                                    default =>
                                        'bg-secondary',

                                };

                            @endphp

                            <span class="badge status-badge {{ $statusClass }}">

                                {{ ucfirst($profile->status ?? 'Pending') }}

                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ======================================================
                 INFORMATION ALERT
            ======================================================= --}}

            <div class="journal-alert">

                <div class="d-flex align-items-start">

                    <i class="bi bi-info-circle journal-alert-icon"></i>

                    <div>

                        <h6>
                            About the Reviewer Application
                        </h6>

                        <p>
                            Please provide complete and accurate academic,
                            professional and research information. Submitted
                            applications are reviewed by the BMRC Journal
                            Editorial Office before reviewer approval.
                        </p>

                    </div>

                </div>

            </div>


            {{-- ======================================================
                 VALIDATION ERRORS
            ======================================================= --}}

            @if ($errors->any())

                <div class="alert alert-danger border shadow-sm mb-4">

                    <div class="fw-bold mb-2">

                        <i class="bi bi-exclamation-triangle-fill me-1"></i>

                        Please correct the following errors:

                    </div>

                    <ul class="mb-0 small">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- ======================================================
                 SUCCESS MESSAGE
            ======================================================= --}}

            @if(session('success'))

                <div class="alert alert-success border shadow-sm mb-4">

                    <i class="bi bi-check-circle-fill me-2"></i>

                    {{ session('success') }}

                </div>

            @endif


            {{-- ======================================================
                 APPLICATION FORM
            ======================================================= --}}

            <form
                method="POST"
                action="{{ route('reviewer.application.update') }}"
                enctype="multipart/form-data"
            >

                @csrf

                @method('PATCH')


                {{-- ==================================================
                     PERSONAL INFORMATION
                =================================================== --}}

                <div class="card application-card">

                    <div class="card-header">

                        <div class="section-header">

                            <span class="section-icon">
                                <i class="bi bi-person-vcard"></i>
                            </span>

                            <div>

                                <div class="section-title">
                                    Personal Information
                                </div>

                                <div class="section-description">
                                    Personal identification and demographic information
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-3">

                                <label for="title" class="form-label">
                                    Title
                                </label>

                                <select
                                    name="title"
                                    id="title"
                                    class="form-select @error('title') is-invalid @enderror"
                                >

                                    <option value="">
                                        Select
                                    </option>

                                    @foreach([
                                        'Dr.',
                                        'Prof.',
                                        'Mr.',
                                        'Ms.',
                                        'Mrs.',
                                        'Miss'
                                    ] as $title)

                                        <option
                                            value="{{ $title }}"
                                            {{ old('title', $profile->title ?? '') == $title ? 'selected' : '' }}
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


                            <div class="col-md-3">

                                <label for="first_name" class="form-label">
                                    First Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="first_name"
                                    id="first_name"
                                    value="{{ old('first_name', $profile->first_name ?? '') }}"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    placeholder="First name"
                                    required
                                >

                                @error('first_name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-3">

                                <label for="middle_name" class="form-label">
                                    Middle Name
                                </label>

                                <input
                                    type="text"
                                    name="middle_name"
                                    id="middle_name"
                                    value="{{ old('middle_name', $profile->middle_name ?? '') }}"
                                    class="form-control"
                                    placeholder="Middle name"
                                >

                            </div>


                            <div class="col-md-3">

                                <label for="last_name" class="form-label">
                                    Last Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="last_name"
                                    id="last_name"
                                    value="{{ old('last_name', $profile->last_name ?? '') }}"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    placeholder="Last name"
                                    required
                                >

                                @error('last_name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-12">

                                <label for="display_name" class="form-label">
                                    Display Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="display_name"
                                    id="display_name"
                                    value="{{ old('display_name', $profile->display_name ?? '') }}"
                                    class="form-control @error('display_name') is-invalid @enderror"
                                    placeholder="Name to be used for journal correspondence"
                                    required
                                >

                                @error('display_name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-4">

                                <label for="gender" class="form-label">
                                    Gender
                                </label>

                                <select name="gender" id="gender" class="form-select">

                                    <option value="">
                                        Select Gender
                                    </option>

                                    @foreach([
                                        'Male',
                                        'Female',
                                        'Other',
                                        'Prefer not to say'
                                    ] as $gender)

                                        <option
                                            value="{{ $gender }}"
                                            {{ old('gender', $profile->gender ?? '') == $gender ? 'selected' : '' }}
                                        >
                                            {{ $gender }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="col-md-4">

                                <label for="date_of_birth" class="form-label">
                                    Date of Birth
                                </label>

                                <input
                                    type="date"
                                    name="date_of_birth"
                                    id="date_of_birth"
                                    value="{{ old('date_of_birth', $profile->date_of_birth ?? '') }}"
                                    class="form-control"
                                >

                            </div>


                            <div class="col-md-4">

                                <label for="nationality" class="form-label">
                                    Nationality
                                </label>

                                <input
                                    type="text"
                                    name="nationality"
                                    id="nationality"
                                    value="{{ old('nationality', $profile->nationality ?? 'Bangladeshi') }}"
                                    class="form-control"
                                    placeholder="Nationality"
                                >

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ==================================================
                     CONTACT INFORMATION
                =================================================== --}}

                <div class="card application-card">

                    <div class="card-header">

                        <div class="section-header">

                            <span class="section-icon">
                                <i class="bi bi-envelope"></i>
                            </span>

                            <div>

                                <div class="section-title">
                                    Contact Information
                                </div>

                                <div class="section-description">
                                    Contact details for editorial correspondence
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label
                                    for="registered_email"
                                    class="form-label"
                                >
                                    Registered Email Address
                                </label>

                                <input
                                    type="email"
                                    id="registered_email"
                                    value="{{ auth()->user()->email }}"
                                    class="form-control bg-light"
                                    readonly
                                >

                                <div class="form-text mt-1">

                                    <i class="bi bi-lock-fill me-1"></i>

                                    Registered account email. This cannot be changed here.

                                </div>

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="alternative_email"
                                    class="form-label"
                                >
                                    Alternative Email
                                </label>

                                <input
                                    type="email"
                                    name="alternative_email"
                                    id="alternative_email"
                                    value="{{ old('alternative_email', $profile->alternative_email ?? '') }}"
                                    class="form-control @error('alternative_email') is-invalid @enderror"
                                    placeholder="Optional alternative email"
                                >

                                @error('alternative_email')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label for="mobile" class="form-label">
                                    Mobile Number
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="mobile"
                                    id="mobile"
                                    value="{{ old('mobile', $profile->mobile ?? '') }}"
                                    class="form-control @error('mobile') is-invalid @enderror"
                                    placeholder="01XXXXXXXXX"
                                    required
                                >

                                @error('mobile')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="preferred_communication_method"
                                    class="form-label"
                                >
                                    Preferred Communication Method
                                </label>

                                <select
                                    name="preferred_communication_method"
                                    id="preferred_communication_method"
                                    class="form-select"
                                >

                                    <option value="">
                                        Select
                                    </option>

                                    @foreach([
                                        'Email',
                                        'Phone',
                                        'Email and Phone'
                                    ] as $method)

                                        <option
                                            value="{{ $method }}"
                                            {{ old('preferred_communication_method', $profile->preferred_communication_method ?? '') == $method ? 'selected' : '' }}
                                        >
                                            {{ $method }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ==================================================
                     LOCATION
                =================================================== --}}

                <div class="card application-card">

                    <div class="card-header">

                        <div class="section-header">

                            <span class="section-icon">
                                <i class="bi bi-geo-alt"></i>
                            </span>

                            <div>

                                <div class="section-title">
                                    Location &amp; Address
                                </div>

                                <div class="section-description">
                                    Current country, location and institutional address
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label for="country" class="form-label">
                                    Country
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="country"
                                    id="country"
                                    class="form-select @error('country') is-invalid @enderror"
                                    required
                                >

                                    <option value="">
                                        Loading countries...
                                    </option>

                                </select>

                                @error('country')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label for="division_state" class="form-label">
                                    Division / State
                                </label>

                                <input
                                    type="text"
                                    name="division_state"
                                    id="division_state"
                                    value="{{ old('division_state', $profile->division_state ?? '') }}"
                                    class="form-control"
                                    placeholder="Division / State"
                                >

                            </div>


                            <div class="col-md-6">

                                <label for="city_district" class="form-label">
                                    City / District
                                </label>

                                <input
                                    type="text"
                                    name="city_district"
                                    id="city_district"
                                    value="{{ old('city_district', $profile->city_district ?? '') }}"
                                    class="form-control"
                                    placeholder="City / District"
                                >

                            </div>


                            <div class="col-12">

                                <label for="postal_address" class="form-label">
                                    Postal Address
                                </label>

                                <textarea
                                    name="postal_address"
                                    id="postal_address"
                                    rows="3"
                                    class="form-control"
                                    placeholder="Full postal address"
                                >{{ old('postal_address', $profile->postal_address ?? '') }}</textarea>

                            </div>


                            <div class="col-12">

                                <label for="office_address" class="form-label">
                                    Office / Institutional Address
                                </label>

                                <textarea
                                    name="office_address"
                                    id="office_address"
                                    rows="3"
                                    class="form-control"
                                    placeholder="Institution / office address"
                                >{{ old('office_address', $profile->office_address ?? '') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ==================================================
                     COUNTRY JSON
                =================================================== --}}

                <script>

                    document.addEventListener('DOMContentLoaded', function () {

                        const countrySelect =
                            document.getElementById('country');

                        const selectedCountry =
                            @json(old('country', $profile->country ?? 'Bangladesh'));

                        fetch('{{ asset('data/countries.json') }}', {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json'
                            }
                        })

                        .then(response => {

                            if (!response.ok) {
                                throw new Error(
                                    'HTTP error: ' + response.status
                                );
                            }

                            return response.json();

                        })

                        .then(countries => {

                            countrySelect.innerHTML =
                                '<option value="">Select Country</option>';

                            countries.forEach(country => {

                                const option =
                                    document.createElement('option');

                                const countryName =
                                    country.name ??
                                    country.country ??
                                    '';

                                const countryCode =
                                    country.code ??
                                    country.iso2 ??
                                    '';

                                option.value =
                                    countryName;

                                option.textContent =
                                    countryCode
                                        ? countryName +
                                          ' (' +
                                          countryCode +
                                          ')'
                                        : countryName;

                                if (
                                    countryName ===
                                    selectedCountry
                                ) {
                                    option.selected = true;
                                }

                                countrySelect.appendChild(option);

                            });

                        })

                        .catch(error => {

                            console.error(
                                'Country JSON Error:',
                                error
                            );

                            countrySelect.innerHTML =
                                '<option value="">Unable to load country list</option>';

                        });

                    });

                </script>


                {{-- ==================================================
                     PROFESSIONAL INFORMATION
                =================================================== --}}

                <div class="card application-card">

                    <div class="card-header">

                        <div class="section-header">

                            <span class="section-icon">
                                <i class="bi bi-building"></i>
                            </span>

                            <div>

                                <div class="section-title">
                                    Professional Information
                                </div>

                                <div class="section-description">
                                    Academic and professional background
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-12">

                                <label for="institution" class="form-label">
                                    Institution / Organization
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="institution"
                                    id="institution"
                                    value="{{ old('institution', $profile->institution ?? '') }}"
                                    class="form-control @error('institution') is-invalid @enderror"
                                    placeholder="Current institution / organization"
                                    required
                                >

                                @error('institution')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label for="department" class="form-label">
                                    Department
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="department"
                                    id="department"
                                    value="{{ old('department', $profile->department ?? '') }}"
                                    class="form-control @error('department') is-invalid @enderror"
                                    placeholder="Department / Unit"
                                    required
                                >

                                @error('department')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label for="designation" class="form-label">
                                    Current Designation
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="designation"
                                    id="designation"
                                    value="{{ old('designation', $profile->designation ?? '') }}"
                                    class="form-control @error('designation') is-invalid @enderror"
                                    placeholder="e.g. Professor, Scientist, Researcher"
                                    required
                                >

                                @error('designation')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label for="academic_degree" class="form-label">
                                    Highest Academic Degree
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="academic_degree"
                                    id="academic_degree"
                                    value="{{ old('academic_degree', $profile->academic_degree ?? '') }}"
                                    class="form-control @error('academic_degree') is-invalid @enderror"
                                    placeholder="e.g. MBBS, MPH, MSc, MD, PhD"
                                    required
                                >

                                @error('academic_degree')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label for="specialization" class="form-label">
                                    Specialization
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="specialization"
                                    id="specialization"
                                    value="{{ old('specialization', $profile->specialization ?? '') }}"
                                    class="form-control @error('specialization') is-invalid @enderror"
                                    placeholder="Area of specialization"
                                    required
                                >

                                @error('specialization')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="professional_registration_no"
                                    class="form-label"
                                >
                                    Professional Registration No.
                                </label>

                                <input
                                    type="text"
                                    name="professional_registration_no"
                                    id="professional_registration_no"
                                    value="{{ old('professional_registration_no', $profile->professional_registration_no ?? '') }}"
                                    class="form-control"
                                    placeholder="BMDC / professional registration number"
                                >

                            </div>


                            <div class="col-12">

                                <label for="research_interest" class="form-label">
                                    Research Interest
                                    <span class="text-danger">*</span>
                                </label>

                                <textarea
                                    name="research_interest"
                                    id="research_interest"
                                    rows="4"
                                    class="form-control @error('research_interest') is-invalid @enderror"
                                    placeholder="Describe your major research interests..."
                                    required
                                >{{ old('research_interest', $profile->research_interest ?? '') }}</textarea>

                                @error('research_interest')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ==================================================
                     REVIEWER EXPERTISE
                =================================================== --}}

                <div class="card application-card important-section">

                    <div class="card-header">

                        <div class="section-header">

                            <span class="section-icon">
                                <i class="bi bi-clipboard2-pulse"></i>
                            </span>

                            <div>

                                <div class="section-title">
                                    Reviewer Expertise
                                </div>

                                <div class="section-description">
                                    Information used for manuscript reviewer selection
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-12">

                                <label for="reviewer_expertise" class="form-label">
                                    Reviewer Expertise
                                    <span class="text-danger">*</span>
                                </label>

                                <textarea
                                    name="reviewer_expertise"
                                    id="reviewer_expertise"
                                    rows="6"
                                    class="form-control @error('reviewer_expertise') is-invalid @enderror"
                                    placeholder="Describe the specific subjects, clinical areas, research methodologies, or disciplines in which you are qualified to review manuscripts..."
                                    required
                                >{{ old('reviewer_expertise', $profile->reviewer_expertise ?? '') }}</textarea>

                                <div class="form-text mt-2">

                                    <strong>Examples:</strong>
                                    Epidemiology, Public Health, Clinical Research,
                                    Maternal and Child Health, Nutrition,
                                    Biostatistics, Infectious Diseases.

                                </div>

                                @error('reviewer_expertise')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-12">

                                <label for="keywords" class="form-label">
                                    Research / Review Keywords
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="keywords"
                                    id="keywords"
                                    value="{{ old('keywords', $profile->keywords ?? '') }}"
                                    class="form-control @error('keywords') is-invalid @enderror"
                                    placeholder="Epidemiology, Public Health, RCT, Nutrition, Maternal Health"
                                    required
                                >

                                <div class="form-text">
                                    Separate multiple keywords with commas.
                                </div>

                                @error('keywords')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ==================================================
                     RESEARCH IDENTIFIERS
                =================================================== --}}

                <div class="card application-card">

                    <div class="card-header">

                        <div class="section-header">

                            <span class="section-icon">
                                <i class="bi bi-person-badge"></i>
                            </span>

                            <div>

                                <div class="section-title">
                                    Research Identifiers
                                </div>

                                <div class="section-description">
                                    Researcher identifiers and academic profiles
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label for="orcid" class="form-label">
                                    ORCID iD
                                </label>

                                <input
                                    type="text"
                                    name="orcid"
                                    id="orcid"
                                    value="{{ old('orcid', $profile->orcid ?? '') }}"
                                    class="form-control"
                                    placeholder="0000-0000-0000-0000"
                                >

                            </div>


                            <div class="col-md-6">

                                <label for="researcher_id" class="form-label">
                                    Researcher ID
                                </label>

                                <input
                                    type="text"
                                    name="researcher_id"
                                    id="researcher_id"
                                    value="{{ old('researcher_id', $profile->researcher_id ?? '') }}"
                                    class="form-control"
                                    placeholder="Researcher ID"
                                >

                            </div>


                            <div class="col-md-6">

                                <label for="scopus_author_id" class="form-label">
                                    Scopus Author ID
                                </label>

                                <input
                                    type="text"
                                    name="scopus_author_id"
                                    id="scopus_author_id"
                                    value="{{ old('scopus_author_id', $profile->scopus_author_id ?? '') }}"
                                    class="form-control"
                                    placeholder="Scopus Author ID"
                                >

                            </div>


                            <div class="col-md-6">

                                <label for="web_of_science_id" class="form-label">
                                    Web of Science Researcher ID
                                </label>

                                <input
                                    type="text"
                                    name="web_of_science_id"
                                    id="web_of_science_id"
                                    value="{{ old('web_of_science_id', $profile->web_of_science_id ?? '') }}"
                                    class="form-control"
                                    placeholder="Web of Science Researcher ID"
                                >

                            </div>


                            <div class="col-12">

                                <label for="google_scholar_profile" class="form-label">
                                    Google Scholar Profile URL
                                </label>

                                <input
                                    type="url"
                                    name="google_scholar_profile"
                                    id="google_scholar_profile"
                                    value="{{ old('google_scholar_profile', $profile->google_scholar_profile ?? '') }}"
                                    class="form-control"
                                    placeholder="https://scholar.google.com/..."
                                >

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ==================================================
                     CV
                =================================================== --}}

                <div class="card application-card">

                    <div class="card-header">

                        <div class="section-header">

                            <span class="section-icon">
                                <i class="bi bi-file-earmark-person"></i>
                            </span>

                            <div>

                                <div class="section-title">
                                    Curriculum Vitae (CV)
                                </div>

                                <div class="section-description">
                                    Upload your latest academic or professional CV
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <label for="cv_file" class="form-label">

                            Upload CV

                            @if(empty($profile->cv_file))
                                <span class="text-danger">*</span>
                            @endif

                        </label>


                        <input
                            type="file"
                            name="cv_file"
                            id="cv_file"
                            class="form-control @error('cv_file') is-invalid @enderror"
                            accept=".pdf,.doc,.docx"
                            @if(empty($profile->cv_file)) required @endif
                        >


                        <div class="form-text mt-2">

                            <i class="bi bi-info-circle me-1"></i>

                            Accepted formats: PDF, DOC, DOCX.
                            Maximum file size: 5 MB.

                        </div>


                        @if(!empty($profile->cv_file))

                            <div class="existing-file mt-3">

                                <div class="d-flex align-items-start">

                                    <i class="bi bi-file-earmark-check text-success fs-4 me-2"></i>

                                    <div>

                                        <strong class="text-success">
                                            CV already uploaded
                                        </strong>

                                        <div class="small text-muted mt-1">
                                            You may upload a new CV to replace the current file.
                                        </div>

                                        <a
                                            href="{{ asset('storage/' . $profile->cv_file) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-success mt-2"
                                        >

                                            <i class="bi bi-eye me-1"></i>

                                            View Current CV

                                        </a>

                                    </div>

                                </div>

                            </div>

                        @endif


                        @error('cv_file')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- ==================================================
                     AVAILABILITY
                =================================================== --}}

                <div class="card application-card">

                    <div class="card-header">

                        <div class="section-header">

                            <span class="section-icon">
                                <i class="bi bi-calendar-check"></i>
                            </span>

                            <div>

                                <div class="section-title">
                                    Reviewer Availability
                                </div>

                                <div class="section-description">
                                    Indicate whether you are currently available for peer review
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <input
                            type="hidden"
                            name="available_for_review"
                            value="0"
                        >

                        <div class="form-check form-switch">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="available_for_review"
                                value="1"
                                id="available_for_review"
                                {{ old(
                                    'available_for_review',
                                    $profile->available_for_review ?? true
                                ) ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label fw-semibold"
                                for="available_for_review"
                            >
                                I am currently available to review manuscripts
                            </label>

                        </div>

                        <div class="form-text mt-2">

                            You may change your availability later from your reviewer dashboard.

                        </div>

                    </div>

                </div>


                {{-- ==================================================
                     DECLARATION
                =================================================== --}}

                <div class="card application-card">

                    <div class="card-header">

                        <div class="section-header">

                            <span class="section-icon">
                                <i class="bi bi-shield-check"></i>
                            </span>

                            <div>

                                <div class="section-title">
                                    Reviewer Declaration
                                </div>

                                <div class="section-description">
                                    Declaration of accuracy, confidentiality and professional conduct
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="declaration-box">

                            <p class="mb-3">

                                By submitting this application, I confirm that
                                the information provided is accurate and complete
                                to the best of my knowledge.

                            </p>

                            <p class="mb-3">

                                I understand that reviewer applications are
                                subject to evaluation and approval by the
                                BMRC Journal Editorial Office.

                            </p>

                            <p class="mb-0">

                                I agree to maintain strict confidentiality
                                regarding manuscripts, reviewer identities,
                                editorial decisions and peer-review materials
                                assigned to me.

                            </p>

                        </div>


                        <div class="form-check">

                            <input
                                class="form-check-input @error('declaration') is-invalid @enderror"
                                type="checkbox"
                                name="declaration"
                                value="1"
                                id="declaration"
                                {{ old(
                                    'declaration',
                                    $profile->declaration ?? false
                                ) ? 'checked' : '' }}
                                required
                            >

                            <label
                                class="form-check-label fw-semibold"
                                for="declaration"
                            >

                                I agree to the reviewer declaration and
                                confidentiality requirements.

                                <span class="text-danger">*</span>

                            </label>


                            @error('declaration')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- ==================================================
                     ACTION BUTTONS
                =================================================== --}}

                <div class="submit-card">

                    <div class="card-body p-3 p-md-4">

                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3">

                            <a
                                href="{{ route('reviewer.login') }}"
                                class="btn btn-outline-secondary"
                            >

                                <i class="bi bi-arrow-left me-1"></i>

                                Back to Reviewer Portal

                            </a>


                            <button
                                type="submit"
                                class="btn btn-primary px-4"
                            >

                                <i class="bi bi-send-check me-1"></i>

                                Submit Reviewer Application

                            </button>

                        </div>

                    </div>

                </div>

            </form>


            {{-- ======================================================
                 FOOTER
            ======================================================= --}}

            <footer class="journal-footer">

                <div class="footer-journal-name">
                    BMRC Journal
                </div>

                <div class="footer-institution">
                    Bangladesh Medical Research Council (BMRC)
                </div>

                <p class="footer-copy">
                    BMRC Journal Online Peer Review and Editorial Management System
                    &nbsp;|&nbsp;
                    © {{ date('Y') }} BMRC. All rights reserved.
                </p>

            </footer>

        </div>

    </main>

</div>

@endsection