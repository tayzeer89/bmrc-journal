@extends('layouts.app')

@section('title', 'Reviewer Registration | BMRC Journal')

@section('content')

<style>

    :root {
        --reviewer-primary: #0d3b66;
        --reviewer-primary-dark: #092f52;
        --reviewer-primary-soft: #edf5fb;

        --reviewer-success: #198754;

        --reviewer-bg: #f4f7f9;
        --reviewer-card: #ffffff;

        --reviewer-border: #dde5eb;
        --reviewer-border-light: #edf1f4;

        --reviewer-text: #253443;
        --reviewer-muted: #667085;

        --reviewer-danger: #b42318;

        --reviewer-shadow:
            0 12px 32px rgba(13, 59, 102, .075);
    }


    body {
        background: var(--reviewer-bg);
    }


    /* ============================================================
       PAGE
    ============================================================ */

    .reviewer-registration-page {
        min-height: calc(100vh - 70px);

        background:
            linear-gradient(
                to bottom,
                #ffffff 0,
                #ffffff 190px,
                var(--reviewer-bg) 190px,
                var(--reviewer-bg) 100%
            );

        padding: 34px 0 55px;
    }


    .reviewer-registration-container {
        max-width: 1120px;
        margin: 0 auto;
    }


    /* ============================================================
       TOP INTRO
    ============================================================ */

    .registration-intro {
        max-width: 780px;
        margin: 0 auto 24px;
        text-align: center;
    }


    .registration-breadcrumb {
        display: flex;
        align-items: center;
        justify-content: center;

        flex-wrap: wrap;

        gap: 6px;

        margin-bottom: 10px;

        color: #84909b;

        font-size: 10.5px;
    }


    .registration-breadcrumb i {
        font-size: 8px;
        color: #aab3bc;
    }


    .registration-title {
        margin: 0;

        color: var(--reviewer-primary);

        font-family:
            Georgia,
            "Times New Roman",
            serif;

        font-size: 29px;
        font-weight: 700;

        line-height: 1.2;
    }


    .registration-subtitle {
        max-width: 690px;

        margin: 7px auto 0;

        color: var(--reviewer-muted);

        font-size: 12.5px;
        line-height: 1.6;
    }


    /* ============================================================
       CARD
    ============================================================ */

    .reviewer-card {
        overflow: hidden;

        background: var(--reviewer-card);

        border: 1px solid var(--reviewer-border);
        border-radius: 10px;

        box-shadow: var(--reviewer-shadow);
    }


    /* ============================================================
       CARD HEADER
    ============================================================ */

    .reviewer-header {
        position: relative;

        padding: 25px 30px;

        background:
            linear-gradient(
                135deg,
                #0b355d,
                #145374
            );

        color: #ffffff;
    }


    .reviewer-header::after {
        content: "";

        position: absolute;

        left: 30px;
        bottom: 0;

        width: 65px;
        height: 3px;

        background: #d2af74;
    }


    .reviewer-header-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;
    }


    .reviewer-brand {
        display: flex;
        align-items: center;

        gap: 15px;

        min-width: 0;
    }


    .reviewer-logo {
        width: 58px;
        height: 58px;

        flex: 0 0 58px;

        object-fit: contain;

        padding: 6px;

        border-radius: 9px;

        background: #ffffff;
    }


    .reviewer-journal-name {
        margin: 0 0 3px;

        font-family:
            Georgia,
            "Times New Roman",
            serif;

        font-size: 20px;
        font-weight: 700;
    }


    .reviewer-institution {
        color: #d9e3eb;

        font-size: 11.5px;

        margin-bottom: 2px;
    }


    .reviewer-system {
        color: #b9cad7;

        font-size: 10px;
    }


    .reviewer-header-badge {
        display: inline-flex;
        align-items: center;

        gap: 6px;

        padding: 7px 11px;

        border: 1px solid rgba(255,255,255,.18);
        border-radius: 5px;

        background: rgba(255,255,255,.07);

        color: #f1f5f8;

        font-size: 9.5px;
        font-weight: 600;

        white-space: nowrap;
    }


    /* ============================================================
       BODY
    ============================================================ */

    .reviewer-body {
        padding: 30px 32px 34px;
    }


    .application-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;

        gap: 20px;

        padding-bottom: 20px;
        margin-bottom: 8px;

        border-bottom: 1px solid var(--reviewer-border-light);
    }


    .application-heading h4 {
        margin: 0 0 5px;

        color: var(--reviewer-text);

        font-size: 19px;
        font-weight: 700;
    }


    .application-heading p {
        max-width: 700px;

        margin: 0;

        color: var(--reviewer-muted);

        font-size: 11.5px;
        line-height: 1.6;
    }


    .application-status-badge {
        display: inline-flex;
        align-items: center;

        gap: 5px;

        padding: 5px 9px;

        border-radius: 5px;

        background: var(--reviewer-primary-soft);

        color: var(--reviewer-primary);

        font-size: 9.5px;
        font-weight: 600;

        white-space: nowrap;
    }


    /* ============================================================
       SECTION
    ============================================================ */

    .section-title {
        display: flex;
        align-items: center;

        gap: 9px;

        margin-top: 30px;
        margin-bottom: 17px;

        padding-bottom: 9px;

        border-bottom: 1px solid #e9edf1;

        color: var(--reviewer-primary);

        font-size: 13px;
        font-weight: 700;

        letter-spacing: .01em;
    }


    .section-title i {
        width: 26px;
        height: 26px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 5px;

        background: var(--reviewer-primary-soft);

        font-size: 12px;
    }


    /* ============================================================
       FORM LABELS
    ============================================================ */

    .form-label {
        margin-bottom: 6px;

        color: #344054;

        font-size: 11.5px;
        font-weight: 600;
    }


    .required {
        color: var(--reviewer-danger);
    }


    .field-help {
        display: block;

        margin-top: 5px;

        color: #7b8793;

        font-size: 9.8px;
        line-height: 1.45;
    }


    /* ============================================================
       INPUTS
    ============================================================ */

    .form-control,
    .form-select {
        min-height: 43px;

        border: 1px solid #ccd5dd;
        border-radius: 6px;

        background-color: #ffffff;

        color: var(--reviewer-text);

        font-size: 12px;

        box-shadow: none;

        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            background .2s ease;
    }


    .form-control:focus,
    .form-select:focus {
        border-color: #6a93b6;

        box-shadow:
            0 0 0 3px rgba(13, 59, 102, .08);

        background-color: #fdfefe;
    }


    .form-control::placeholder {
        color: #a0a9b2;
    }


    .multiple-select {
        min-height: 170px;
        padding-top: 7px;
        padding-bottom: 7px;
    }


    /* ============================================================
       OTHER FIELDS
    ============================================================ */

    .other-field {
        display: none;
    }


    /* ============================================================
       VALIDATION
    ============================================================ */

    .registration-alert {
        padding: 12px 14px;

        border-radius: 6px;

        font-size: 11px;

        margin-bottom: 20px;
    }


    .registration-alert ul {
        padding-left: 18px;
    }


    /* ============================================================
       CV
    ============================================================ */

    .cv-upload-box {
        padding: 16px;

        background: #fafcfd;

        border: 1px dashed #bfcbd5;
        border-radius: 7px;
    }


    .cv-preview-card {
        display: none;

        margin-top: 16px;
    }


    .cv-preview-card .card {
        border: 1px solid var(--reviewer-border);

        border-radius: 7px;

        overflow: hidden;
    }


    .cv-preview-card .card-header {
        padding: 9px 12px;

        background: #f7f9fb;

        color: var(--reviewer-text);

        font-size: 11px;
    }


    #cvPreview {
        width: 100%;
        height: 560px;

        border: 0;
    }


    /* ============================================================
       PASSWORD
    ============================================================ */

    .password-field-wrapper {
        position: relative;
    }


    .password-input {
        padding-right: 45px;
    }


    .password-toggle-btn {
        position: absolute;

        top: 50%;
        right: 9px;

        transform: translateY(-50%);

        width: 31px;
        height: 31px;

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 0;

        border: 0;
        border-radius: 5px;

        background: transparent;

        color: #687684;

        font-size: 15px;

        cursor: pointer;

        transition:
            color .2s ease,
            background-color .2s ease;
    }


    .password-toggle-btn:hover {
        background: #eef3f7;

        color: var(--reviewer-primary);
    }


    .password-toggle-btn:focus {
        outline: none;

        background: #e8f0f6;

        color: var(--reviewer-primary);
    }


    /* ============================================================
       HELP BOX
    ============================================================ */

    .help-box {
        display: flex;
        align-items: flex-start;

        gap: 11px;

        margin-top: 25px;

        padding: 14px 16px;

        background: #f5f9fc;

        border: 1px solid #d9e6ef;
        border-left: 4px solid var(--reviewer-primary);

        border-radius: 6px;
    }


    .help-box-icon {
        color: var(--reviewer-primary);

        font-size: 15px;

        margin-top: 1px;
    }


    .help-box-title {
        margin-bottom: 3px;

        color: #29465f;

        font-size: 11px;
        font-weight: 700;
    }


    .help-box-text {
        color: #5f6f7c;

        font-size: 10.5px;
        line-height: 1.55;
    }


    /* ============================================================
       SUBMIT
    ============================================================ */

    .reviewer-submit {
        min-height: 47px;

        margin-top: 23px;

        border: 1px solid var(--reviewer-primary);
        border-radius: 6px;

        background:
            linear-gradient(
                135deg,
                #0d3b66,
                #145374
            );

        color: #ffffff;

        font-size: 12px;
        font-weight: 700;

        transition:
            transform .2s ease,
            box-shadow .2s ease,
            background .2s ease;
    }


    .reviewer-submit:hover {
        color: #ffffff;

        background:
            linear-gradient(
                135deg,
                #092f52,
                #0f4868
            );

        transform: translateY(-1px);

        box-shadow:
            0 6px 16px rgba(13, 59, 102, .14);
    }


    /* ============================================================
       LOGIN LINK
    ============================================================ */

    .reviewer-login-link {
        margin-top: 22px;

        text-align: center;

        color: #697683;

        font-size: 11px;
    }


    .reviewer-login-link a {
        color: var(--reviewer-primary);

        font-weight: 600;

        text-decoration: none;
    }


    .reviewer-login-link a:hover {
        text-decoration: underline;
    }


    /* ============================================================
       RESPONSIVE
    ============================================================ */

    @media (max-width: 991px) {

        .reviewer-registration-page {
            padding-top: 25px;
        }

        .reviewer-body {
            padding: 27px 24px 30px;
        }

    }


    @media (max-width: 767px) {

        .reviewer-registration-page {
            background: var(--reviewer-bg);

            padding: 18px 0 35px;
        }


        .registration-intro {
            margin-bottom: 18px;
        }


        .registration-title {
            font-size: 23px;
        }


        .reviewer-header {
            padding: 20px;
        }


        .reviewer-header-inner {
            align-items: flex-start;
        }


        .reviewer-header-badge {
            display: none;
        }


        .reviewer-logo {
            width: 51px;
            height: 51px;

            flex-basis: 51px;
        }


        .reviewer-journal-name {
            font-size: 17px;
        }


        .reviewer-body {
            padding: 23px 18px 26px;
        }


        .application-heading {
            flex-direction: column;
            gap: 10px;
        }


        #cvPreview {
            height: 440px;
        }

    }

</style>

<div class="reviewer-registration-page">

<div class="container">

    <div class="reviewer-registration-container">

        {{-- ====================================================
            INTRO
        ===================================================== --}}

        <div class="registration-intro">

            <div class="registration-breadcrumb">

                <span>
                    BMRC Journal
                </span>

                <i class="bi bi-chevron-right"></i>

                <span>
                    Reviewer
                </span>

                <i class="bi bi-chevron-right"></i>

                <span>
                    Registration
                </span>

            </div>


            <h1 class="registration-title">
                Reviewer Registration
            </h1>


            <p class="registration-subtitle">
                Apply to join the BMRC Journal reviewer pool by providing
                your professional, academic and research information.
            </p>

        </div>


        {{-- ====================================================
            MAIN CARD
        ===================================================== --}}

        <div class="reviewer-card">

            {{-- =================================================
                HEADER
            ================================================== --}}

            <div class="reviewer-header">

                <div class="reviewer-header-inner">

                    <div class="reviewer-brand">

                        <img
                            src="{{ asset('favicon.png') }}"
                            class="reviewer-logo"
                            alt="BMRC Logo"
                        >


                        <div>

                            <h2 class="reviewer-journal-name">
                                BMRC Journal
                            </h2>

                            <div class="reviewer-institution">
                                Bangladesh Medical Research Council
                            </div>

                            <div class="reviewer-system">
                                Online Editorial &amp; Journal Management System
                            </div>

                        </div>

                    </div>


                    <div class="reviewer-header-badge">

                        <i class="bi bi-person-badge"></i>

                        Reviewer Application

                    </div>

                </div>

            </div>


            {{-- =================================================
                BODY
            ================================================== --}}

            <div class="reviewer-body">

                <div class="application-heading">

                    <div>

                        <h4>
                            Reviewer Application Form
                        </h4>

                        <p>
                            Complete the following information to create your
                            reviewer account. Additional profile information
                            can be maintained later from the Reviewer Dashboard.
                        </p>

                    </div>


                    <div class="application-status-badge">

                        <i class="bi bi-shield-check"></i>

                        Secure Registration

                    </div>

                </div>


                {{-- =================================================
                    VALIDATION
                ================================================== --}}

                @if($errors->any())

                    <div class="alert alert-danger registration-alert">

                        <div class="fw-semibold mb-1">

                            <i class="bi bi-exclamation-triangle-fill me-1"></i>

                            Please correct the following information:

                        </div>


                        <ul class="mb-0 mt-2">

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
                    action="{{ route('reviewer.register.submit') }}"
                    enctype="multipart/form-data"
                >

                    @csrf


                    {{-- =================================================
                        PERSONAL INFORMATION
                    ================================================== --}}

                    <div class="section-title">

                        <i class="bi bi-person-vcard"></i>

                        Personal Information

                    </div>


                    <div class="row g-3">

                        <div class="col-md-3">

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
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select
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
                                        @selected(
                                            old('title') === $title
                                        )
                                    >
                                        {{ $title }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-3">

                            <label class="form-label">
                                First Name
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="first_name"
                                value="{{ old('first_name') }}"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-3">

                            <label class="form-label">
                                Middle Name
                            </label>

                            <input
                                type="text"
                                name="middle_name"
                                value="{{ old('middle_name') }}"
                                class="form-control"
                            >

                        </div>


                        <div class="col-md-3">

                            <label class="form-label">
                                Last Name
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="last_name"
                                value="{{ old('last_name') }}"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Email Address
                                <span class="required">*</span>
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control"
                                autocomplete="email"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Mobile Number
                                <span class="required">*</span>
                            </label>

                            <input
                                type="tel"
                                name="mobile"
                                value="{{ old('mobile') }}"
                                class="form-control"
                                required
                            >

                        </div>

                    </div>


                    {{-- =================================================
                        LOCATION
                    ================================================== --}}

                    <div class="section-title">

                        <i class="bi bi-geo-alt"></i>

                        Location

                    </div>


                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Division / State
                                <span class="required">*</span>
                            </label>


                            <select
                                name="division_state"
                                id="division_state"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Division / State
                                </option>


                                @foreach($divisions as $division)

                                    <option
                                        value="{{ $division }}"
                                        @selected(
                                            old('division_state')
                                            === $division
                                        )
                                    >
                                        {{ $division }}
                                    </option>

                                @endforeach


                                <option
                                    value="__other__"
                                    @selected(
                                        old('division_state')
                                        === '__other__'
                                    )
                                >
                                    Other
                                </option>

                            </select>

                        </div>


                        <div
                            class="col-md-6 other-field"
                            id="division_state_other_wrapper"
                        >

                            <label class="form-label">
                                Other Division / State
                            </label>

                            <input
                                type="text"
                                name="division_state_other"
                                id="division_state_other"
                                value="{{ old('division_state_other') }}"
                                class="form-control"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                City / District
                                <span class="required">*</span>
                            </label>


                            <select
                                name="city_district"
                                id="city_district"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select City / District
                                </option>


                                @foreach($districts as $district)

                                    <option
                                        value="{{ $district->value }}"
                                        data-parent="{{ $district->parent_value }}"
                                        @selected(
                                            old('city_district')
                                            === $district->value
                                        )
                                    >
                                        {{ $district->value }}
                                    </option>

                                @endforeach


                                <option
                                    value="__other__"
                                    data-parent=""
                                    @selected(
                                        old('city_district')
                                        === '__other__'
                                    )
                                >
                                    Other
                                </option>

                            </select>

                        </div>


                        <div
                            class="col-md-6 other-field"
                            id="city_district_other_wrapper"
                        >

                            <label class="form-label">
                                Other City / District
                            </label>

                            <input
                                type="text"
                                name="city_district_other"
                                id="city_district_other"
                                value="{{ old('city_district_other') }}"
                                class="form-control"
                            >

                        </div>

                    </div>


                    {{-- =================================================
                        PROFESSIONAL INFORMATION
                    ================================================== --}}

                    <div class="section-title">

                        <i class="bi bi-building"></i>

                        Current Professional Information

                    </div>


                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Institution / Organization
                                <span class="required">*</span>
                            </label>


                            <select
                                name="institution"
                                id="institution"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Institution / Organization
                                </option>


                                @foreach($institutions as $item)

                                    <option
                                        value="{{ $item }}"
                                        @selected(
                                            old('institution')
                                            === $item
                                        )
                                    >
                                        {{ $item }}
                                    </option>

                                @endforeach


                                <option
                                    value="__other__"
                                    @selected(
                                        old('institution')
                                        === '__other__'
                                    )
                                >
                                    Other
                                </option>

                            </select>

                        </div>


                        <div
                            class="col-md-6 other-field"
                            id="institution_other_wrapper"
                        >

                            <label class="form-label">
                                Other Institution / Organization
                            </label>

                            <input
                                type="text"
                                name="institution_other"
                                id="institution_other"
                                value="{{ old('institution_other') }}"
                                class="form-control"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Department
                                <span class="required">*</span>
                            </label>


                            <select
                                name="department"
                                id="department"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Department
                                </option>


                                @foreach($departments as $item)

                                    <option
                                        value="{{ $item }}"
                                        @selected(
                                            old('department')
                                            === $item
                                        )
                                    >
                                        {{ $item }}
                                    </option>

                                @endforeach


                                <option
                                    value="__other__"
                                    @selected(
                                        old('department')
                                        === '__other__'
                                    )
                                >
                                    Other
                                </option>

                            </select>

                        </div>


                        <div
                            class="col-md-6 other-field"
                            id="department_other_wrapper"
                        >

                            <label class="form-label">
                                Other Department
                            </label>

                            <input
                                type="text"
                                name="department_other"
                                id="department_other"
                                value="{{ old('department_other') }}"
                                class="form-control"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Current Designation
                                <span class="required">*</span>
                            </label>


                            <select
                                name="designation"
                                id="designation"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Designation
                                </option>


                                @foreach($designations as $item)

                                    <option
                                        value="{{ $item }}"
                                        @selected(
                                            old('designation')
                                            === $item
                                        )
                                    >
                                        {{ $item }}
                                    </option>

                                @endforeach


                                <option
                                    value="__other__"
                                    @selected(
                                        old('designation')
                                        === '__other__'
                                    )
                                >
                                    Other
                                </option>

                            </select>

                        </div>


                        <div
                            class="col-md-6 other-field"
                            id="designation_other_wrapper"
                        >

                            <label class="form-label">
                                Other Designation
                            </label>

                            <input
                                type="text"
                                name="designation_other"
                                id="designation_other"
                                value="{{ old('designation_other') }}"
                                class="form-control"
                            >

                        </div>

                    </div>


                    {{-- =================================================
                        ACADEMIC INFORMATION
                    ================================================== --}}

                    <div class="section-title">

                        <i class="bi bi-mortarboard"></i>

                        Academic Information

                    </div>


                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Highest Academic Degree
                                <span class="required">*</span>
                            </label>


                            <select
                                name="highest_degree"
                                id="highest_degree"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Highest Degree
                                </option>


                                @foreach($highestDegrees as $item)

                                    <option
                                        value="{{ $item }}"
                                        @selected(
                                            old('highest_degree')
                                            === $item
                                        )
                                    >
                                        {{ $item }}
                                    </option>

                                @endforeach


                                <option
                                    value="__other__"
                                    @selected(
                                        old('highest_degree')
                                        === '__other__'
                                    )
                                >
                                    Other
                                </option>

                            </select>

                        </div>


                        <div
                            class="col-md-6 other-field"
                            id="highest_degree_other_wrapper"
                        >

                            <label class="form-label">
                                Other Degree
                            </label>

                            <input
                                type="text"
                                name="highest_degree_other"
                                id="highest_degree_other"
                                value="{{ old('highest_degree_other') }}"
                                class="form-control"
                            >

                        </div>

                    </div>


                    {{-- =================================================
                        REVIEWER EXPERTISE
                    ================================================== --}}

                    <div class="section-title">

                        <i class="bi bi-journal-medical"></i>

                        Reviewer Expertise

                    </div>


                    <div class="row g-3">

                        {{-- Specialization --}}
                        <div class="col-lg-4">

                            <label class="form-label">
                                Specialization
                                <span class="required">*</span>
                            </label>


                            <select
                                name="specialization[]"
                                id="specialization"
                                class="form-select multiple-select"
                                multiple
                                required
                            >

                                @foreach($specializations as $item)

                                    <option
                                        value="{{ $item }}"
                                        @selected(
                                            in_array(
                                                $item,
                                                old(
                                                    'specialization',
                                                    []
                                                )
                                            )
                                        )
                                    >
                                        {{ $item }}
                                    </option>

                                @endforeach


                                <option
                                    value="__other__"
                                    @selected(
                                        in_array(
                                            '__other__',
                                            old(
                                                'specialization',
                                                []
                                            )
                                        )
                                    )
                                >
                                    Other
                                </option>

                            </select>


                            <small class="field-help">
                                You may select multiple values.
                            </small>

                        </div>


                        {{-- Research Interests --}}
                        <div class="col-lg-4">

                            <label class="form-label">
                                Research Interests
                                <span class="required">*</span>
                            </label>


                            <select
                                name="research_interests[]"
                                id="research_interests"
                                class="form-select multiple-select"
                                multiple
                                required
                            >

                                @foreach($researchInterests as $item)

                                    <option
                                        value="{{ $item }}"
                                        @selected(
                                            in_array(
                                                $item,
                                                old(
                                                    'research_interests',
                                                    []
                                                )
                                            )
                                        )
                                    >
                                        {{ $item }}
                                    </option>

                                @endforeach


                                <option
                                    value="__other__"
                                    @selected(
                                        in_array(
                                            '__other__',
                                            old(
                                                'research_interests',
                                                []
                                            )
                                        )
                                    )
                                >
                                    Other
                                </option>

                            </select>


                            <small class="field-help">
                                Select all areas relevant to your research activity.
                            </small>

                        </div>


                        {{-- Keywords --}}
                        <div class="col-lg-4">

                            <label class="form-label">
                                Research / Review Keywords
                                <span class="required">*</span>
                            </label>


                            <select
                                name="review_keywords[]"
                                id="review_keywords"
                                class="form-select multiple-select"
                                multiple
                                required
                            >

                                @foreach($reviewKeywords as $item)

                                    <option
                                        value="{{ $item }}"
                                        @selected(
                                            in_array(
                                                $item,
                                                old(
                                                    'review_keywords',
                                                    []
                                                )
                                            )
                                        )
                                    >
                                        {{ $item }}
                                    </option>

                                @endforeach


                                <option
                                    value="__other__"
                                    @selected(
                                        in_array(
                                            '__other__',
                                            old(
                                                'review_keywords',
                                                []
                                            )
                                        )
                                    )
                                >
                                    Other
                                </option>

                            </select>


                            <small class="field-help">
                                Used for future manuscript-reviewer matching.
                            </small>

                        </div>


                        {{-- Other Specialization --}}
                        <div
                            class="col-12 other-field"
                            id="specialization_other_wrapper"
                        >

                            <label class="form-label">
                                Other Specialization
                            </label>

                            <input
                                type="text"
                                name="specialization_other"
                                id="specialization_other"
                                value="{{ old('specialization_other') }}"
                                class="form-control"
                                placeholder="Separate multiple values with commas"
                            >

                        </div>


                        {{-- Other Research Interest --}}
                        <div
                            class="col-12 other-field"
                            id="research_interest_other_wrapper"
                        >

                            <label class="form-label">
                                Other Research Interest
                            </label>

                            <input
                                type="text"
                                name="research_interest_other"
                                id="research_interest_other"
                                value="{{ old('research_interest_other') }}"
                                class="form-control"
                                placeholder="Separate multiple values with commas"
                            >

                        </div>


                        {{-- Other Keywords --}}
                        <div
                            class="col-12 other-field"
                            id="review_keyword_other_wrapper"
                        >

                            <label class="form-label">
                                Other Research / Review Keywords
                            </label>

                            <input
                                type="text"
                                name="review_keyword_other"
                                id="review_keyword_other"
                                value="{{ old('review_keyword_other') }}"
                                class="form-control"
                                placeholder="Separate multiple keywords with commas"
                            >

                        </div>

                    </div>


                    {{-- =================================================
                        CV
                    ================================================== --}}

                    <div class="section-title">

                        <i class="bi bi-file-earmark-pdf"></i>

                        Curriculum Vitae

                    </div>


                    <div class="cv-upload-box">

                        <label class="form-label">
                            Upload CV
                            <span class="required">*</span>
                        </label>


                        <input
                            type="file"
                            name="cv_file"
                            id="cv_file"
                            class="form-control"
                            accept=".pdf,application/pdf"
                            required
                        >


                        <small class="field-help">
                            PDF format only. Maximum allowed file size: 5 MB.
                        </small>

                    </div>


                    <div
                        class="cv-preview-card"
                        id="cvPreviewWrapper"
                    >

                        <div class="card">

                            <div class="card-header">

                                <i class="bi bi-file-earmark-pdf me-1"></i>

                                <strong>
                                    CV Preview
                                </strong>

                            </div>

                            <div class="card-body p-0">

                                <iframe
                                    id="cvPreview"
                                    title="CV Preview"
                                ></iframe>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        ACCOUNT SECURITY
                    ================================================== --}}

                    <div class="section-title">

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


                            <div class="password-field-wrapper">

                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control password-input"
                                    required
                                    autocomplete="new-password"
                                >


                                <button
                                    type="button"
                                    class="password-toggle-btn"
                                    onclick="togglePasswordField(
                                        'password',
                                        'passwordIcon'
                                    )"
                                    aria-label="Show or hide password"
                                    title="Show / Hide Password"
                                >

                                    <i
                                        class="bi bi-eye"
                                        id="passwordIcon"
                                    ></i>

                                </button>

                            </div>


                            <small class="field-help">
                                Minimum 8 characters with uppercase,
                                lowercase letters and number.
                            </small>

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


                            <div class="password-field-wrapper">

                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control password-input"
                                    required
                                    autocomplete="new-password"
                                >


                                <button
                                    type="button"
                                    class="password-toggle-btn"
                                    onclick="togglePasswordField(
                                        'password_confirmation',
                                        'confirmPasswordIcon'
                                    )"
                                    aria-label="Show or hide confirm password"
                                    title="Show / Hide Password"
                                >

                                    <i
                                        class="bi bi-eye"
                                        id="confirmPasswordIcon"
                                    ></i>

                                </button>

                            </div>


                            <small class="field-help">
                                Re-enter the same password for confirmation.
                            </small>

                        </div>

                    </div>


                    {{-- =================================================
                        NEXT STEP INFORMATION
                    ================================================== --}}

                    <div class="help-box">

                        <i class="bi bi-info-circle-fill help-box-icon"></i>

                        <div>

                            <div class="help-box-title">
                                What happens after registration?
                            </div>

                            <div class="help-box-text">
                                Your reviewer account will be created and you
                                will be directed to the Reviewer Dashboard.
                                You can then complete any remaining professional
                                profile information and submit the profile for
                                editorial approval.
                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        SUBMIT
                    ================================================== --}}

                    <button
                        type="submit"
                        class="btn reviewer-submit w-100"
                    >

                        <i class="bi bi-person-check-fill me-2"></i>

                        Create Reviewer Account

                    </button>

                </form>


                {{-- =================================================
                    LOGIN
                ================================================== --}}

                <div class="reviewer-login-link">

                    Already registered?

                    <a
                        href="{{ route('reviewer.login') }}"
                    >
                        Sign in to Reviewer Portal
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>


</div>

@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | Single Select "Other"
        |--------------------------------------------------------------------------
        */

        function setupOtherField(
            selectId,
            wrapperId,
            inputId
        ) {

            const select =
                document.getElementById(
                    selectId
                );

            const wrapper =
                document.getElementById(
                    wrapperId
                );

            const input =
                document.getElementById(
                    inputId
                );


            if (
                !select
                ||
                !wrapper
            ) {
                return;
            }


            function refresh() {

                const show =
                    select.value
                    === '__other__';


                wrapper.style.display =
                    show
                        ? 'block'
                        : 'none';


                if (input) {
                    input.required = show;
                }

            }


            select.addEventListener(
                'change',
                refresh
            );


            refresh();

        }


        setupOtherField(
            'division_state',
            'division_state_other_wrapper',
            'division_state_other'
        );


        setupOtherField(
            'city_district',
            'city_district_other_wrapper',
            'city_district_other'
        );


        setupOtherField(
            'institution',
            'institution_other_wrapper',
            'institution_other'
        );


        setupOtherField(
            'department',
            'department_other_wrapper',
            'department_other'
        );


        setupOtherField(
            'designation',
            'designation_other_wrapper',
            'designation_other'
        );


        setupOtherField(
            'highest_degree',
            'highest_degree_other_wrapper',
            'highest_degree_other'
        );


        /*
        |--------------------------------------------------------------------------
        | Multiple Select "Other"
        |--------------------------------------------------------------------------
        */

        function setupMultipleOther(
            selectId,
            wrapperId,
            inputId
        ) {

            const select =
                document.getElementById(
                    selectId
                );

            const wrapper =
                document.getElementById(
                    wrapperId
                );

            const input =
                inputId
                    ? document.getElementById(inputId)
                    : null;


            if (
                !select
                ||
                !wrapper
            ) {
                return;
            }


            function refresh() {

                const selected =
                    Array.from(
                        select.selectedOptions
                    )
                    .map(
                        option =>
                            option.value
                    );


                const show =
                    selected.includes(
                        '__other__'
                    );


                wrapper.style.display =
                    show
                        ? 'block'
                        : 'none';


                if (input) {
                    input.required = show;
                }

            }


            select.addEventListener(
                'change',
                refresh
            );


            refresh();

        }


        setupMultipleOther(
            'specialization',
            'specialization_other_wrapper',
            'specialization_other'
        );


        setupMultipleOther(
            'research_interests',
            'research_interest_other_wrapper',
            'research_interest_other'
        );


        setupMultipleOther(
            'review_keywords',
            'review_keyword_other_wrapper',
            'review_keyword_other'
        );


        /*
        |--------------------------------------------------------------------------
        | Division → District
        |--------------------------------------------------------------------------
        */

        const divisionSelect =
            document.getElementById(
                'division_state'
            );

        const districtSelect =
            document.getElementById(
                'city_district'
            );


        if (
            divisionSelect
            &&
            districtSelect
        ) {

            const districtOptions =
                Array.from(
                    districtSelect.options
                );


            function filterDistricts() {

                const division =
                    divisionSelect.value;


                districtOptions.forEach(
                    function (option) {

                        if (
                            option.value === ''
                            ||
                            option.value
                                === '__other__'
                        ) {

                            option.hidden = false;

                            return;
                        }


                        const parent =
                            option.dataset.parent;


                        option.hidden =
                            division
                            &&
                            division !== '__other__'
                            &&
                            parent !== division;

                    }
                );


                const selected =
                    districtSelect
                        .selectedOptions[0];


                if (
                    selected
                    &&
                    selected.hidden
                ) {

                    districtSelect.value = '';

                }

            }


            divisionSelect.addEventListener(
                'change',
                filterDistricts
            );


            filterDistricts();

        }


        /*
        |--------------------------------------------------------------------------
        | CV PDF Preview
        |--------------------------------------------------------------------------
        */

        const cvInput =
            document.getElementById(
                'cv_file'
            );

        const cvPreview =
            document.getElementById(
                'cvPreview'
            );

        const cvPreviewWrapper =
            document.getElementById(
                'cvPreviewWrapper'
            );


        let currentPdfUrl = null;


        if (
            cvInput
            &&
            cvPreview
            &&
            cvPreviewWrapper
        ) {

            cvInput.addEventListener(
                'change',
                function () {

                    const file =
                        this.files[0];


                    if (!file) {

                        cvPreviewWrapper
                            .style.display =
                                'none';

                        return;

                    }


                    const isPdf =
                        file.type
                            === 'application/pdf'
                        ||
                        file.name
                            .toLowerCase()
                            .endsWith('.pdf');


                    if (!isPdf) {

                        alert(
                            'Only PDF CV files are allowed.'
                        );


                        this.value = '';


                        cvPreviewWrapper
                            .style.display =
                                'none';


                        return;

                    }


                    if (
                        file.size
                        >
                        5 * 1024 * 1024
                    ) {

                        alert(
                            'CV file must not exceed 5 MB.'
                        );


                        this.value = '';


                        cvPreviewWrapper
                            .style.display =
                                'none';


                        return;

                    }


                    if (currentPdfUrl) {

                        URL.revokeObjectURL(
                            currentPdfUrl
                        );

                    }


                    currentPdfUrl =
                        URL.createObjectURL(
                            file
                        );


                    cvPreview.src =
                        currentPdfUrl;


                    cvPreviewWrapper
                        .style.display =
                            'block';

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Password Show / Hide
        |--------------------------------------------------------------------------
        */

        window.togglePasswordField =
            function (
                inputId,
                iconId
            ) {

                const input =
                    document.getElementById(
                        inputId
                    );

                const icon =
                    document.getElementById(
                        iconId
                    );


                if (!input || !icon) {
                    return;
                }


                if (
                    input.type
                    === 'password'
                ) {

                    input.type =
                        'text';


                    icon.classList.remove(
                        'bi-eye'
                    );


                    icon.classList.add(
                        'bi-eye-slash'
                    );

                } else {

                    input.type =
                        'password';


                    icon.classList.remove(
                        'bi-eye-slash'
                    );


                    icon.classList.add(
                        'bi-eye'
                    );

                }

            };

    }
);

</script>

@endpush

@endsection
