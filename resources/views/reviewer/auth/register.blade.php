@extends('layouts.app')

@section('title', 'Reviewer Registration | BMRC Journal')

@section('content')

<style>

    .reviewer-registration-page {
        min-height: calc(100vh - 70px);
        background: #f4f7f9;
        padding: 45px 0;
    }

    .reviewer-card {
        background: #ffffff;
        border: 1px solid #e2e8ee;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(13, 59, 102, 0.08);
    }

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
    }

    .reviewer-body {
        padding: 35px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 9px;

        color: #0d3b66;

        font-size: 1rem;
        font-weight: 700;

        border-bottom:
            1px solid #eaecf0;

        padding-bottom: 11px;
        margin-bottom: 20px;
        margin-top: 35px;
    }

    .section-title:first-of-type {
        margin-top: 0;
    }

    .form-label {
        color: #344054;
        font-size: .88rem;
        font-weight: 600;
    }

    .required {
        color: #b42318;
    }

    .form-control,
    .form-select {
        min-height: 45px;
        border: 1px solid #d0d5dd;
        border-radius: 7px;
    }

    .multiple-select {
        min-height: 180px;
    }

    .other-field {
        display: none;
    }

    .cv-preview-card {
        display: none;
        margin-top: 18px;
    }

    #cvPreview {
        width: 100%;
        height: 600px;
        border: 0;
    }

    .reviewer-submit {
        min-height: 50px;
        background: #0d3b66;
        border-color: #0d3b66;
        font-weight: 600;
    }

    .reviewer-submit:hover {
        background: #092f52;
        border-color: #092f52;
    }

    .help-box {
        background: #f5f9fc;
        border: 1px solid #d9e6ef;
        border-left: 4px solid #0d3b66;
        border-radius: 8px;
        padding: 16px 18px;
    }

</style>


<div class="reviewer-registration-page">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-12 col-xl-10">

                <div class="reviewer-card">

                    {{-- Header --}}

                    <div class="reviewer-header">

                        <div class="d-flex align-items-center gap-3">

                            <img
                                src="{{ asset('favicon.png') }}"
                                class="reviewer-logo"
                                alt="BMRC Logo"
                            >

                            <div>

                                <h3 class="mb-1">
                                    BMRC Journal Reviewer Registration
                                </h3>

                                <div>
                                    Bangladesh Medical Research Council
                                </div>

                                <small class="opacity-75">
                                    Online Journal Submission System
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="reviewer-body">

                        <div class="mb-4">

                            <h4>
                                Reviewer Application
                            </h4>

                            <p class="text-muted">

                                Register as a BMRC Journal reviewer.
                                You may complete additional professional
                                information from your dashboard after registration.

                            </p>

                        </div>


                        {{-- Validation --}}

                        @if($errors->any())

                            <div class="alert alert-danger">

                                <strong>
                                    Please correct the following:
                                </strong>

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


                            {{-- ======================================
                                 PERSONAL
                            ======================================= --}}

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

                                        <span class="required">
                                            *
                                        </span>
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
                                                    old('title')
                                                    === $title
                                                )
                                            >
                                                {{ $title }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                <div class="col-md-3">

                                    <label
                                        class="form-label"
                                    >
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


                            {{-- ======================================
                                 LOCATION
                            ======================================= --}}

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


                            {{-- ======================================
                                 PROFESSIONAL
                            ======================================= --}}

                            <div class="section-title">

                                <i class="bi bi-building"></i>

                                Current Professional Information

                            </div>


                            <div class="row g-3">

                                {{-- Institution --}}

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


                                {{-- Department --}}

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


                                {{-- Designation --}}

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


                            {{-- ======================================
                                 ACADEMIC
                            ======================================= --}}

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


                            {{-- ======================================
                                 EXPERTISE
                            ======================================= --}}

                            <div class="section-title">

                                <i class="bi bi-journal-medical"></i>

                                Reviewer Expertise

                            </div>


                            <div class="row g-4">

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

                                    <small class="text-muted">
                                        You may select multiple values.
                                    </small>

                                </div>


                                {{-- Research interests --}}

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
                                        >
                                            Other
                                        </option>

                                    </select>

                                    <small class="text-muted">
                                        Select all relevant areas.
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

                                        <option value="__other__">
                                            Other
                                        </option>

                                    </select>

                                    <small class="text-muted">
                                        Used later for manuscript-reviewer matching.
                                    </small>

                                </div>


                                {{-- Other specialization --}}

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
                                        value="{{ old('specialization_other') }}"
                                        class="form-control"
                                        placeholder="Separate multiple values with commas"
                                    >

                                </div>


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
                                        value="{{ old('research_interest_other') }}"
                                        class="form-control"
                                        placeholder="Separate multiple values with commas"
                                    >

                                </div>


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
                                        value="{{ old('review_keyword_other') }}"
                                        class="form-control"
                                        placeholder="Separate multiple keywords with commas"
                                    >

                                </div>

                            </div>


                            {{-- ======================================
                                 CV
                            ======================================= --}}

                            <div class="section-title">

                                <i class="bi bi-file-earmark-pdf"></i>

                                Curriculum Vitae

                            </div>


                            <div class="row">

                                <div class="col-12">

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

                                    <small class="text-muted">
                                        PDF only. Maximum 5 MB.
                                    </small>

                                </div>


                                <div
                                    class="col-12 cv-preview-card"
                                    id="cvPreviewWrapper"
                                >

                                    <div class="card mt-3">

                                        <div class="card-header">

                                            <strong>
                                                CV Preview
                                            </strong>

                                        </div>

                                        <div class="card-body p-0">

                                            <iframe
                                                id="cvPreview"
                                            ></iframe>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- ======================================
                                 SECURITY
                            ======================================= --}}

                            <div class="section-title">

                                <i class="bi bi-shield-lock"></i>

                                Account Security

                            </div>


                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Password
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-control"
                                        required
                                    >

                                    <small class="text-muted">
                                        Minimum 8 characters with upper/lowercase
                                        letters and number.
                                    </small>

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label">
                                        Confirm Password
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        class="form-control"
                                        required
                                    >

                                </div>

                            </div>


                            <div class="help-box mt-4">

                                <strong>
                                    What happens next?
                                </strong>

                                <div class="mt-1">

                                    Your account will be created and you will
                                    automatically enter the Reviewer Dashboard.
                                    You can then complete the remaining reviewer
                                    information and submit the profile for
                                    editorial approval.

                                </div>

                            </div>


                            <button
                                type="submit"
                                class="btn reviewer-submit text-white w-100 mt-4"
                            >

                                <i class="bi bi-person-check me-2"></i>

                                Create Reviewer Account

                            </button>

                        </form>


                        <div class="text-center mt-4">

                            Already registered?

                            <a
                                href="{{ route('reviewer.login') }}"
                            >
                                Reviewer Login
                            </a>

                        </div>

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
            wrapperId
        ) {

            const select =
                document.getElementById(
                    selectId
                );

            const wrapper =
                document.getElementById(
                    wrapperId
                );

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

                wrapper.style.display =
                    selected.includes(
                        '__other__'
                    )
                        ? 'block'
                        : 'none';

            }


            select.addEventListener(
                'change',
                refresh
            );

            refresh();
        }


        setupMultipleOther(
            'specialization',
            'specialization_other_wrapper'
        );

        setupMultipleOther(
            'research_interests',
            'research_interest_other_wrapper'
        );

        setupMultipleOther(
            'review_keywords',
            'review_keyword_other_wrapper'
        );


        /*
        |--------------------------------------------------------------------------
        | Division → District filtering
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
                            option.hidden =
                                false;

                            return;
                        }

                        const parent =
                            option.dataset.parent;

                        option.hidden =
                            division
                            &&
                            division
                                !== '__other__'
                            &&
                            parent
                                !== division;
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
                    districtSelect.value =
                        '';
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
        | PDF CV Preview
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

    }
);

</script>

@endpush

@endsection