@extends('reviewer.layouts.app')

@section('title', 'Reviewer Profile | BMRC Journal')


@push('styles')

<style>

    .reviewer-profile-page {
        max-width: 1200px;
        margin: 0 auto;
    }

    .profile-header {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
    }

    .profile-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: #12395b;
        margin-bottom: 5px;
    }

    .profile-header p {
        color: #667085;
        font-size: 13px;
        margin: 0;
    }

    .profile-summary {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .summary-label {
        color: #98a2b3;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    .summary-value {
        font-weight: 700;
        color: #12395b;
        margin-top: 4px;
    }

    .profile-section {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .profile-section-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e8edf2;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .profile-section-header.highlight {
        background: #f4f9fd;
    }

    .profile-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: #edf5fb;
        color: #1d5f91;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 38px;
        font-size: 17px;
    }

    .profile-section-title {
        font-size: 15px;
        font-weight: 700;
        color: #12395b;
    }

    .profile-section-description {
        color: #7b8794;
        font-size: 11px;
    }

    .profile-section-body {
        padding: 22px;
    }

    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: #344054;
    }

    .form-control,
    .form-select {
        border-radius: 6px;
        min-height: 43px;
        font-size: 13px;
        border-color: #cfd8e3;
    }

    textarea.form-control {
        min-height: auto;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1d5f91;
        box-shadow: 0 0 0 3px rgba(29,95,145,.09);
    }

    .required {
        color: #dc3545;
    }

    .field-help {
        color: #8491a1;
        font-size: 10px;
        margin-top: 5px;
    }

    .completion {
        height: 8px;
        border-radius: 10px;
    }

    .declaration-box {
        border: 1px solid #e4e8ed;
        background: #fafbfc;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 12px;
    }

    .sticky-actions {
        position: sticky;
        bottom: 10px;
        z-index: 20;
        background: rgba(255,255,255,.97);
        border: 1px solid #dce3ea;
        border-radius: 10px;
        padding: 14px 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,.08);
    }

    .existing-cv {
        border: 1px solid #cce8d5;
        background: #f4fbf7;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
    }

    .cv-preview {
        width: 100%;
        height: 550px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-top: 15px;
    }

</style>

@endpush


@section('content')

@php

    $statusLabel = match($profile->approval_status) {

        'draft' =>
            'Draft',

        'pending_approval' =>
            'Pending Approval',

        'update_requested' =>
            'Update Requested',

        'approved' =>
            'Approved',

        'rejected' =>
            'Rejected',

        default =>
            ucfirst(
                str_replace(
                    '_',
                    ' ',
                    $profile->approval_status
                )
            )
    };

@endphp


<div class="reviewer-profile-page">


    {{-- Header --}}

    <div class="profile-header">

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

            <div>

                <h1>
                    Reviewer Professional Profile
                </h1>

                <p>
                    Complete your academic, professional,
                    research and peer-review information.
                </p>

            </div>


            <a
                href="{{ route('reviewer.profile.show') }}"
                class="btn btn-outline-primary"
            >

                <i class="bi bi-eye me-1"></i>

                View Profile

            </a>

        </div>

    </div>



    {{-- Summary --}}

    <div class="profile-summary">

        <div class="row g-4">

            <div class="col-md-3">

                <div class="summary-label">
                    Application ID
                </div>

                <div class="summary-value">
                    {{ $profile->application_id ?: '-' }}
                </div>

            </div>


            <div class="col-md-3">

                <div class="summary-label">
                    Reviewer Code
                </div>

                <div class="summary-value">
                    {{ $profile->reviewer_code ?: 'Pending' }}
                </div>

            </div>


            <div class="col-md-3">

                <div class="summary-label">
                    Application Status
                </div>

                <div class="summary-value">
                    {{ $statusLabel }}
                </div>

            </div>


            <div class="col-md-3">

                <div class="summary-label">
                    Completion
                </div>

                <div class="summary-value">
                    {{ $profile->profile_completion_percentage }}%
                </div>

                <div class="progress completion mt-2">

                    <div
                        class="progress-bar"
                        style="width: {{ $profile->profile_completion_percentage }}%"
                    ></div>

                </div>

            </div>

        </div>

    </div>



    @if($profile->isUpdateRequested())

        <div class="alert alert-warning">

            <strong>
                Editorial Update Requested
            </strong>

            @if($profile->profile_update_request)

                <div class="mt-2">
                    {{ $profile->profile_update_request }}
                </div>

            @endif

        </div>

    @endif



    <form
        method="POST"
        action="{{ route('reviewer.application.update') }}"
        enctype="multipart/form-data"
    >

        @csrf

        @method('PATCH')


        {{-- ============================================================
            1. PERSONAL
        ============================================================ --}}

        <div class="profile-section">

            <div class="profile-section-header">

                <div class="profile-icon">
                    <i class="bi bi-person-vcard"></i>
                </div>

                <div>

                    <div class="profile-section-title">
                        1. Personal Information
                    </div>

                    <div class="profile-section-description">
                        Personal identification and correspondence details
                    </div>

                </div>

            </div>


            <div class="profile-section-body">

                <div class="row g-3">

                    <div class="col-md-2">

                        <label class="form-label">
                            Title
                        </label>

                        <select
                            name="title"
                            class="form-select"
                        >

                            <option value="">
                                Select
                            </option>

                            @foreach([
                                'Prof.',
                                'Dr.',
                                'Mr.',
                                'Ms.',
                                'Mrs.'
                            ] as $item)

                                <option
                                    value="{{ $item }}"
                                    @selected(
                                        old(
                                            'title',
                                            $profile->title
                                        ) === $item
                                    )
                                >
                                    {{ $item }}
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
                            value="{{ old('first_name', $profile->first_name) }}"
                            class="form-control @error('first_name') is-invalid @enderror"
                            required
                        >

                        @error('first_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Middle Name
                        </label>

                        <input
                            type="text"
                            name="middle_name"
                            value="{{ old('middle_name', $profile->middle_name) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Last Name
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="last_name"
                            value="{{ old('last_name', $profile->last_name) }}"
                            class="form-control @error('last_name') is-invalid @enderror"
                            required
                        >

                        @error('last_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Display Name
                        </label>

                        <input
                            type="text"
                            name="display_name"
                            value="{{ old('display_name', $profile->display_name) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Gender
                        </label>

                        <select
                            name="gender"
                            class="form-select"
                        >

                            <option value="">
                                Select
                            </option>

                            @foreach([
                                'Male',
                                'Female',
                                'Other',
                                'Prefer not to say'
                            ] as $item)

                                <option
                                    value="{{ $item }}"
                                    @selected(
                                        old(
                                            'gender',
                                            $profile->gender
                                        ) === $item
                                    )
                                >

                                    {{ $item }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Date of Birth
                        </label>

                        <input
                            type="date"
                            name="date_of_birth"
                            value="{{
                                old(
                                    'date_of_birth',
                                    optional($profile->date_of_birth)->format('Y-m-d')
                                )
                            }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Nationality
                        </label>

                        <input
                            type="text"
                            name="nationality"
                            value="{{ old('nationality', $profile->nationality ?: 'Bangladeshi') }}"
                            class="form-control"
                        >

                    </div>

                </div>

            </div>

        </div>



        {{-- ============================================================
            2. CONTACT
        ============================================================ --}}

        <div class="profile-section">

            <div class="profile-section-header">

                <div class="profile-icon">
                    <i class="bi bi-envelope"></i>
                </div>

                <div>

                    <div class="profile-section-title">
                        2. Contact Information
                    </div>

                    <div class="profile-section-description">
                        Contact information for editorial communication
                    </div>

                </div>

            </div>


            <div class="profile-section-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Registered Email
                        </label>

                        <input
                            type="email"
                            value="{{ $reviewer->email }}"
                            class="form-control bg-light"
                            readonly
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Alternative Email
                        </label>

                        <input
                            type="email"
                            name="alternative_email"
                            value="{{ old('alternative_email', $profile->alternative_email) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Mobile
                        </label>

                        <input
                            type="text"
                            name="mobile"
                            value="{{ old('mobile', $profile->mobile) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Alternative Mobile
                        </label>

                        <input
                            type="text"
                            name="alternative_mobile"
                            value="{{ old('alternative_mobile', $profile->alternative_mobile) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Preferred Communication
                        </label>

                        <select
                            name="preferred_communication_method"
                            class="form-select"
                        >

                            <option
                                value="email"
                                @selected(
                                    old(
                                        'preferred_communication_method',
                                        $profile->preferred_communication_method
                                    ) === 'email'
                                )
                            >
                                Email
                            </option>

                            <option
                                value="mobile"
                                @selected(
                                    old(
                                        'preferred_communication_method',
                                        $profile->preferred_communication_method
                                    ) === 'mobile'
                                )
                            >
                                Mobile
                            </option>

                            <option
                                value="both"
                                @selected(
                                    old(
                                        'preferred_communication_method',
                                        $profile->preferred_communication_method
                                    ) === 'both'
                                )
                            >
                                Email and Mobile
                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>



        {{-- ============================================================
            3. LOCATION
        ============================================================ --}}

        <div class="profile-section">

            <div class="profile-section-header">

                <div class="profile-icon">
                    <i class="bi bi-geo-alt"></i>
                </div>

                <div>

                    <div class="profile-section-title">
                        3. Location & Address
                    </div>

                    <div class="profile-section-description">
                        Current location and institutional address
                    </div>

                </div>

            </div>


            <div class="profile-section-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Country
                        </label>

                        <input
                            type="text"
                            name="country"
                            value="{{ old('country', $profile->country ?: 'Bangladesh') }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Division / State
                        </label>

                        <input
                            type="text"
                            name="division_state"
                            value="{{ old('division_state', $profile->division_state) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            City / District
                        </label>

                        <input
                            type="text"
                            name="city_district"
                            value="{{ old('city_district', $profile->city_district) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Postal Code
                        </label>

                        <input
                            type="text"
                            name="postal_code"
                            value="{{ old('postal_code', $profile->postal_code) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Postal Address
                        </label>

                        <textarea
                            name="postal_address"
                            rows="3"
                            class="form-control"
                        >{{ old('postal_address', $profile->postal_address) }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Office / Institutional Address
                        </label>

                        <textarea
                            name="office_address"
                            rows="3"
                            class="form-control"
                        >{{ old('office_address', $profile->office_address) }}</textarea>

                    </div>

                </div>

            </div>

        </div>



        {{-- ============================================================
            4. PROFESSIONAL
        ============================================================ --}}

        <div class="profile-section">

            <div class="profile-section-header">

                <div class="profile-icon">
                    <i class="bi bi-building"></i>
                </div>

                <div>

                    <div class="profile-section-title">
                        4. Professional Information
                    </div>

                    <div class="profile-section-description">
                        Current appointment and professional background
                    </div>

                </div>

            </div>


            <div class="profile-section-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Institution / Organization
                        </label>

                        <input
                            type="text"
                            name="institution"
                            value="{{ old('institution', $profile->institution) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Department
                        </label>

                        <input
                            type="text"
                            name="department"
                            value="{{ old('department', $profile->department) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Current Designation
                        </label>

                        <input
                            type="text"
                            name="designation"
                            value="{{ old('designation', $profile->designation) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Organization Type
                        </label>

                        <input
                            type="text"
                            name="organization_type"
                            value="{{ old('organization_type', $profile->organization_type) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Years of Experience
                        </label>

                        <input
                            type="number"
                            name="years_of_experience"
                            value="{{ old('years_of_experience', $profile->years_of_experience) }}"
                            class="form-control"
                            min="0"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Professional Registration No.
                        </label>

                        <input
                            type="text"
                            name="professional_registration_no"
                            value="{{ old('professional_registration_no', $profile->professional_registration_no) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Professional Experience
                        </label>

                        <textarea
                            name="professional_experience"
                            rows="4"
                            class="form-control"
                        >{{ old('professional_experience', $profile->professional_experience) }}</textarea>

                    </div>

                </div>

            </div>

        </div>



        {{-- ============================================================
            5. QUALIFICATIONS
        ============================================================ --}}

        <div class="profile-section">

            <div class="profile-section-header">

                <div class="profile-icon">
                    <i class="bi bi-mortarboard"></i>
                </div>

                <div>

                    <div class="profile-section-title">
                        5. Academic & Professional Qualifications
                    </div>

                    <div class="profile-section-description">
                        Educational and professional qualifications
                    </div>

                </div>

            </div>


            <div class="profile-section-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Highest Degree
                        </label>

                        <input
                            type="text"
                            name="highest_degree"
                            value="{{ old('highest_degree', $profile->highest_degree) }}"
                            class="form-control"
                            placeholder="MBBS, MPH, MD, MSc, PhD"
                        >

                    </div>


                    <div class="col-md-5">

                        <label class="form-label">
                            Highest Degree Institution
                        </label>

                        <input
                            type="text"
                            name="highest_degree_institution"
                            value="{{ old('highest_degree_institution', $profile->highest_degree_institution) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Year
                        </label>

                        <input
                            type="number"
                            name="year_of_highest_degree"
                            value="{{ old('year_of_highest_degree', $profile->year_of_highest_degree) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Academic Qualifications
                        </label>

                        <textarea
                            name="academic_qualifications"
                            rows="4"
                            class="form-control"
                        >{{ old('academic_qualifications', $profile->academic_qualifications) }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Professional Qualifications
                        </label>

                        <textarea
                            name="professional_qualifications"
                            rows="4"
                            class="form-control"
                        >{{ old('professional_qualifications', $profile->professional_qualifications) }}</textarea>

                    </div>

                </div>

            </div>

        </div>



        {{-- ============================================================
            6. EXPERTISE
        ============================================================ --}}

        <div class="profile-section">

            <div class="profile-section-header highlight">

                <div class="profile-icon">
                    <i class="bi bi-bullseye"></i>
                </div>

                <div>

                    <div class="profile-section-title">
                        6. Reviewer Expertise & Manuscript Matching
                    </div>

                    <div class="profile-section-description">
                        Critical information used for selecting suitable reviewers
                    </div>

                </div>

            </div>


            <div class="profile-section-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Speciality
                        </label>

                        <input
                            type="text"
                            name="speciality"
                            value="{{ old('speciality', $profile->speciality) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Sub-speciality
                        </label>

                        <input
                            type="text"
                            name="sub_speciality"
                            value="{{ old('sub_speciality', $profile->sub_speciality) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Primary Expertise
                        </label>

                        <input
                            type="text"
                            name="primary_expertise"
                            value="{{ old('primary_expertise', $profile->primary_expertise) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Specialization
                        </label>

                        <textarea
                            name="specialization"
                            rows="3"
                            class="form-control"
                        >{{ old('specialization', $profile->specialization) }}</textarea>

                        <div class="field-help">
                            Separate multiple values with commas.
                        </div>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Research Interests
                        </label>

                        <textarea
                            name="research_interests"
                            rows="3"
                            class="form-control"
                        >{{ old('research_interests', $profile->research_interests) }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Areas of Expertise
                        </label>

                        <textarea
                            name="areas_of_expertise"
                            rows="4"
                            class="form-control"
                        >{{ old('areas_of_expertise', $profile->areas_of_expertise) }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Secondary Expertise
                        </label>

                        <textarea
                            name="secondary_expertise"
                            rows="4"
                            class="form-control"
                        >{{ old('secondary_expertise', $profile->secondary_expertise) }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Methodological Expertise
                        </label>

                        <textarea
                            name="methodological_expertise"
                            rows="3"
                            class="form-control"
                        >{{ old('methodological_expertise', $profile->methodological_expertise) }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Research / Review Keywords
                        </label>

                        <textarea
                            name="expertise_keywords"
                            rows="3"
                            class="form-control"
                        >{{ old('expertise_keywords', $profile->expertise_keywords) }}</textarea>

                        <div class="field-help">
                            Example: Epidemiology, RCT, Nutrition, Maternal Health
                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- ============================================================
            7. RESEARCH
        ============================================================ --}}

        <div class="profile-section">

            <div class="profile-section-header">

                <div class="profile-icon">
                    <i class="bi bi-journal-richtext"></i>
                </div>

                <div>

                    <div class="profile-section-title">
                        7. Research & Publication Experience
                    </div>

                    <div class="profile-section-description">
                        Research background and publication experience
                    </div>

                </div>

            </div>


            <div class="profile-section-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Total Publications
                        </label>

                        <input
                            type="number"
                            name="publication_count"
                            value="{{ old('publication_count', $profile->publication_count) }}"
                            class="form-control"
                            min="0"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            First-author Publications
                        </label>

                        <input
                            type="number"
                            name="first_author_publications"
                            value="{{ old('first_author_publications', $profile->first_author_publications) }}"
                            class="form-control"
                            min="0"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Corresponding-author Publications
                        </label>

                        <input
                            type="number"
                            name="corresponding_author_publications"
                            value="{{ old('corresponding_author_publications', $profile->corresponding_author_publications) }}"
                            class="form-control"
                            min="0"
                        >

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Research Experience
                        </label>

                        <textarea
                            name="research_experience"
                            rows="4"
                            class="form-control"
                        >{{ old('research_experience', $profile->research_experience) }}</textarea>

                    </div>

                </div>

            </div>

        </div>



        {{-- ============================================================
            8. REVIEWING EXPERIENCE
        ============================================================ --}}

        <div class="profile-section">

            <div class="profile-section-header">

                <div class="profile-icon">
                    <i class="bi bi-clipboard-check"></i>
                </div>

                <div>

                    <div class="profile-section-title">
                        8. Peer-review Experience
                    </div>

                    <div class="profile-section-description">
                        Previous journal and manuscript review experience
                    </div>

                </div>

            </div>


            <div class="profile-section-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Reviewing Experience
                        </label>

                        <textarea
                            name="reviewing_experience"
                            rows="4"
                            class="form-control"
                        >{{ old('reviewing_experience', $profile->reviewing_experience) }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Previous Journal Experience
                        </label>

                        <textarea
                            name="previous_journal_experience"
                            rows="4"
                            class="form-control"
                        >{{ old('previous_journal_experience', $profile->previous_journal_experience) }}</textarea>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            External Reviews Completed
                        </label>

                        <input
                            type="number"
                            name="external_reviews_completed"
                            value="{{ old('external_reviews_completed', $profile->external_reviews_completed) }}"
                            class="form-control"
                            min="0"
                        >

                    </div>


                    <div class="col-md-8">

                        <label class="form-label">
                            Professional Memberships
                        </label>

                        <input
                            type="text"
                            name="professional_memberships"
                            value="{{ old('professional_memberships', $profile->professional_memberships) }}"
                            class="form-control"
                        >

                    </div>

                </div>

            </div>

        </div>



        {{-- ============================================================
            9. IDENTIFIERS
        ============================================================ --}}

        <div class="profile-section">

            <div class="profile-section-header">

                <div class="profile-icon">
                    <i class="bi bi-person-badge"></i>
                </div>

                <div>

                    <div class="profile-section-title">
                        9. Research Identifiers
                    </div>

                    <div class="profile-section-description">
                        Researcher IDs and academic profiles
                    </div>

                </div>

            </div>


            <div class="profile-section-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            ORCID
                        </label>

                        <input
                            type="text"
                            name="orcid"
                            value="{{ old('orcid', $profile->orcid) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Researcher ID
                        </label>

                        <input
                            type="text"
                            name="researcher_id"
                            value="{{ old('researcher_id', $profile->researcher_id) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Scopus Author ID
                        </label>

                        <input
                            type="text"
                            name="scopus_author_id"
                            value="{{ old('scopus_author_id', $profile->scopus_author_id) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Web of Science ID
                        </label>

                        <input
                            type="text"
                            name="web_of_science_id"
                            value="{{ old('web_of_science_id', $profile->web_of_science_id) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Google Scholar Profile
                        </label>

                        <input
                            type="url"
                            name="google_scholar_profile"
                            value="{{ old('google_scholar_profile', $profile->google_scholar_profile) }}"
                            class="form-control"
                        >

                    </div>

                </div>

            </div>

        </div>



        {{-- ============================================================
            10. CV
        ============================================================ --}}

        <div class="profile-section">

            <div class="profile-section-header">

                <div class="profile-icon">
                    <i class="bi bi-file-earmark-pdf"></i>
                </div>

                <div>

                    <div class="profile-section-title">
                        10. Curriculum Vitae
                    </div>

                    <div class="profile-section-description">
                        Latest reviewer CV — PDF only
                    </div>

                </div>

            </div>


            <div class="profile-section-body">

                <input
                    type="file"
                    name="cv_file"
                    id="cv_file"
                    accept="application/pdf,.pdf"
                    class="form-control"
                >

                <div class="field-help">
                    Maximum file size: 5 MB.
                </div>


                @if($profile->cv_file)

                    <div class="existing-cv">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <strong class="text-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    CV uploaded
                                </strong>

                            </div>


                            <a
                                href="{{ Storage::url($profile->cv_file) }}"
                                target="_blank"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Open CV
                            </a>

                        </div>


                    </div>

                @endif


                <iframe
                    id="newCvPreview"
                    class="cv-preview d-none"
                ></iframe>

            </div>

        </div>



        {{-- ============================================================
            11. AVAILABILITY
        ============================================================ --}}

        <div class="profile-section">

            <div class="profile-section-header">

                <div class="profile-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>

                <div>

                    <div class="profile-section-title">
                        11. Reviewer Availability
                    </div>

                    <div class="profile-section-description">
                        Current availability and review workload preference
                    </div>

                </div>

            </div>


            <div class="profile-section-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Maximum Active Reviews
                        </label>

                        <input
                            type="number"
                            name="maximum_active_reviews"
                            value="{{ old('maximum_active_reviews', $profile->maximum_active_reviews ?: 3) }}"
                            class="form-control"
                            min="1"
                            max="20"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Unavailable From
                        </label>

                        <input
                            type="date"
                            name="unavailable_from"
                            value="{{
                                old(
                                    'unavailable_from',
                                    optional($profile->unavailable_from)->format('Y-m-d')
                                )
                            }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Unavailable Until
                        </label>

                        <input
                            type="date"
                            name="unavailable_until"
                            value="{{
                                old(
                                    'unavailable_until',
                                    optional($profile->unavailable_until)->format('Y-m-d')
                                )
                            }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4">

                        <input
                            type="hidden"
                            name="available_for_review"
                            value="0"
                        >

                        <div class="form-check form-switch">

                            <input
                                type="checkbox"
                                class="form-check-input"
                                name="available_for_review"
                                value="1"
                                id="available_for_review"
                                @checked(
                                    old(
                                        'available_for_review',
                                        $profile->available_for_review
                                    )
                                )
                            >

                            <label
                                class="form-check-label"
                                for="available_for_review"
                            >
                                Available for peer review
                            </label>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <input
                            type="hidden"
                            name="receive_review_invitations"
                            value="0"
                        >

                        <div class="form-check form-switch">

                            <input
                                type="checkbox"
                                class="form-check-input"
                                name="receive_review_invitations"
                                value="1"
                                id="receive_review_invitations"
                                @checked(
                                    old(
                                        'receive_review_invitations',
                                        $profile->receive_review_invitations
                                    )
                                )
                            >

                            <label
                                class="form-check-label"
                                for="receive_review_invitations"
                            >
                                Receive review invitations
                            </label>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <input
                            type="hidden"
                            name="receive_reminders"
                            value="0"
                        >

                        <div class="form-check form-switch">

                            <input
                                type="checkbox"
                                class="form-check-input"
                                name="receive_reminders"
                                value="1"
                                id="receive_reminders"
                                @checked(
                                    old(
                                        'receive_reminders',
                                        $profile->receive_reminders
                                    )
                                )
                            >

                            <label
                                class="form-check-label"
                                for="receive_reminders"
                            >
                                Receive reminders
                            </label>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- ============================================================
            12. DECLARATIONS
        ============================================================ --}}

        <div class="profile-section">

            <div class="profile-section-header highlight">

                <div class="profile-icon">
                    <i class="bi bi-shield-check"></i>
                </div>

                <div>

                    <div class="profile-section-title">
                        12. Reviewer Declarations
                    </div>

                    <div class="profile-section-description">
                        Ethical, conflict-of-interest and confidentiality declarations
                    </div>

                </div>

            </div>


            <div class="profile-section-body">


                <div class="declaration-box">

                    <input
                        type="hidden"
                        name="conflict_of_interest_declaration"
                        value="0"
                    >

                    <div class="form-check">

                        <input
                            type="checkbox"
                            name="conflict_of_interest_declaration"
                            value="1"
                            id="conflict_of_interest_declaration"
                            class="form-check-input"
                            @checked(
                                old(
                                    'conflict_of_interest_declaration',
                                    $profile->conflict_of_interest_declaration
                                )
                            )
                        >

                        <label
                            class="form-check-label"
                            for="conflict_of_interest_declaration"
                        >

                            <strong>
                                Conflict of Interest Declaration
                            </strong>

                            <div class="small text-muted mt-1">
                                I will disclose any relevant conflict before accepting a manuscript.
                            </div>

                        </label>

                    </div>

                </div>


                <div class="declaration-box">

                    <input
                        type="hidden"
                        name="reviewer_ethics_declaration"
                        value="0"
                    >

                    <div class="form-check">

                        <input
                            type="checkbox"
                            name="reviewer_ethics_declaration"
                            value="1"
                            id="reviewer_ethics_declaration"
                            class="form-check-input"
                            @checked(
                                old(
                                    'reviewer_ethics_declaration',
                                    $profile->reviewer_ethics_declaration
                                )
                            )
                        >

                        <label
                            class="form-check-label"
                            for="reviewer_ethics_declaration"
                        >

                            <strong>
                                Reviewer Ethics Declaration
                            </strong>

                            <div class="small text-muted mt-1">
                                I will provide objective, professional and evidence-based peer review.
                            </div>

                        </label>

                    </div>

                </div>


                <div class="declaration-box mb-0">

                    <input
                        type="hidden"
                        name="confidentiality_declaration"
                        value="0"
                    >

                    <div class="form-check">

                        <input
                            type="checkbox"
                            name="confidentiality_declaration"
                            value="1"
                            id="confidentiality_declaration"
                            class="form-check-input"
                            @checked(
                                old(
                                    'confidentiality_declaration',
                                    $profile->confidentiality_declaration
                                )
                            )
                        >

                        <label
                            class="form-check-label"
                            for="confidentiality_declaration"
                        >

                            <strong>
                                Confidentiality Declaration
                            </strong>

                            <div class="small text-muted mt-1">
                                I will keep manuscripts and editorial information confidential.
                            </div>

                        </label>

                    </div>

                </div>

            </div>

        </div>



        {{-- ============================================================
            SAVE
        ============================================================ --}}

        <div class="sticky-actions">

            <div class="d-flex justify-content-between flex-column flex-md-row gap-2">

                <a
                    href="{{ route('reviewer.dashboard') }}"
                    class="btn btn-outline-secondary"
                >

                    <i class="bi bi-arrow-left me-1"></i>

                    Dashboard

                </a>


                <button
                    type="submit"
                    class="btn btn-primary px-4"
                >

                    <i class="bi bi-save me-1"></i>

                    Save Profile Information

                </button>

            </div>

        </div>

    </form>



    {{-- ================================================================
        SUBMIT FOR APPROVAL
    ================================================================= --}}

    @if(
        $profile->isDraft()
        ||
        $profile->isUpdateRequested()
        ||
        $profile->isRejected()
    )

        <div class="profile-section mt-4">

            <div class="profile-section-body">

                <div class="d-flex justify-content-between flex-column flex-lg-row align-items-lg-center gap-3">

                    <div>

                        <strong>
                            Submit Reviewer Application
                        </strong>

                        <div class="small text-muted mt-1">

                            Save all profile information first.
                            Then submit it for BMRC Editorial Office approval.

                        </div>

                    </div>


                    <form
                        method="POST"
                        action="{{ route('reviewer.application.submit') }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-success px-4"
                            onclick="
                                return confirm(
                                    'Are you sure you want to submit your reviewer application for editorial approval?'
                                );
                            "
                        >

                            <i class="bi bi-send-check me-1"></i>

                            @if($profile->isUpdateRequested())

                                Resubmit for Approval

                            @else

                                Submit for Approval

                            @endif

                        </button>

                    </form>

                </div>

            </div>

        </div>

    @endif


</div>

@endsection



@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const cvInput =
            document.getElementById(
                'cv_file'
            );

        const preview =
            document.getElementById(
                'newCvPreview'
            );


        if (!cvInput || !preview) {
            return;
        }


        cvInput.addEventListener(
            'change',
            function () {

                const file =
                    this.files[0];


                if (!file) {

                    preview.classList.add(
                        'd-none'
                    );

                    return;
                }


                if (
                    file.type !==
                    'application/pdf'
                ) {

                    alert(
                        'Please select a PDF file only.'
                    );

                    this.value = '';

                    preview.classList.add(
                        'd-none'
                    );

                    return;
                }


                if (
                    file.size >
                    5 * 1024 * 1024
                ) {

                    alert(
                        'Maximum CV size is 5 MB.'
                    );

                    this.value = '';

                    preview.classList.add(
                        'd-none'
                    );

                    return;
                }


                preview.src =
                    URL.createObjectURL(
                        file
                    );

                preview.classList.remove(
                    'd-none'
                );
            }
        );
    }
);

</script>

@endpush