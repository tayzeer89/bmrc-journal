@extends('author.layouts.app')

@section('title', 'Professional Information')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | STEP 1 COMPLETION
    |--------------------------------------------------------------------------
    */

    $step1Complete =
        filled($authorProfile?->title) &&
        filled($authorProfile?->first_name) &&
        filled($authorProfile?->last_name) &&
        filled($authorProfile?->display_name) &&
        filled($authorProfile?->mobile) &&
        filled($authorProfile?->country) &&
        filled($authorProfile?->institution) &&
        filled($authorProfile?->department) &&
        filled($authorProfile?->designation);


    /*
    |--------------------------------------------------------------------------
    | STEP 2 COMPLETION
    |--------------------------------------------------------------------------
    */

    $step2Complete =
        filled($authorProfile?->gender) &&
        filled($authorProfile?->date_of_birth) &&
        filled($authorProfile?->nationality) &&
        filled($authorProfile?->division_state) &&
        filled($authorProfile?->city_district) &&
        filled($authorProfile?->postal_address);


    /*
    |--------------------------------------------------------------------------
    | STEP 3 COMPLETION
    |--------------------------------------------------------------------------
    */

    $step3Complete =
        ((int) ($authorProfile?->profile_completed ?? 0)) === 100;


    /*
    |--------------------------------------------------------------------------
    | PROFILE COMPLETION
    |--------------------------------------------------------------------------
    */

    if ($step3Complete) {
        $completion = 100;
    } elseif ($step2Complete) {
        $completion = 66;
    } elseif ($step1Complete) {
        $completion = 33;
    } else {
        $completion = 0;
    }

@endphp


<div class="container-fluid py-4">

    {{-- ================================================================
         PAGE HEADER
    ================================================================= --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                <i class="bi bi-person-vcard me-2"></i>
                Professional Information
            </h4>

            <p class="text-muted mb-0">
                Step 3 of 3 — Complete your professional information.
            </p>

        </div>

        <a
            href="{{ route('author.dashboard') }}"
            class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Dashboard

        </a>

    </div>


    {{-- ================================================================
         PROFILE PROGRESS
    ================================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            {{-- Progress Header --}}

            <div class="d-flex justify-content-between align-items-center mb-2">

                <div>

                    <span class="fw-semibold">
                        Profile Completion
                    </span>

                    <small class="text-muted ms-2">
                        Step 3 of 3
                    </small>

                </div>

                <span class="fw-bold text-primary">
                    {{ $completion }}%
                </span>

            </div>


            {{-- Progress Bar --}}

            <div
                class="progress"
                style="height: 10px;">

                <div
                    class="progress-bar {{ $completion == 100 ? 'bg-success' : '' }}"
                    role="progressbar"
                    style="width: {{ $completion }}%;"
                    aria-valuenow="{{ $completion }}"
                    aria-valuemin="0"
                    aria-valuemax="100">
                </div>

            </div>


            {{-- ========================================================
                 STEP INDICATORS
            ========================================================= --}}

            <div class="row text-center mt-4">


                {{-- ====================================================
                     STEP 1
                ===================================================== --}}

                <div class="col-4">

                    <div class="mb-2">

                        @if($step1Complete)

                            <span class="badge rounded-pill bg-success px-3 py-2">

                                <i class="bi bi-check-lg me-1"></i>
                                Step 1

                            </span>

                        @else

                            <span class="badge rounded-pill bg-secondary px-3 py-2">
                                Step 1
                            </span>

                        @endif

                    </div>

                    <small
                        class="{{ $step1Complete ? 'fw-semibold text-success' : 'text-muted' }}">

                        Minimum Information

                    </small>

                </div>


                {{-- ====================================================
                     STEP 2
                ===================================================== --}}

                <div class="col-4">

                    <div class="mb-2">

                        @if($step2Complete)

                            <span class="badge rounded-pill bg-success px-3 py-2">

                                <i class="bi bi-check-lg me-1"></i>
                                Step 2

                            </span>

                        @else

                            <span class="badge rounded-pill bg-secondary px-3 py-2">
                                Step 2
                            </span>

                        @endif

                    </div>

                    <small
                        class="{{ $step2Complete ? 'fw-semibold text-success' : 'text-muted' }}">

                        Personal Information

                    </small>

                </div>


                {{-- ====================================================
                     STEP 3 - CURRENT
                ===================================================== --}}

                <div class="col-4">

                    <div class="mb-2">

                        <span class="badge rounded-pill bg-primary px-3 py-2">

                            @if($step3Complete)

                                <i class="bi bi-check-lg me-1"></i>

                            @else

                                <i class="bi bi-pencil me-1"></i>

                            @endif

                            Step 3

                        </span>

                    </div>

                    <small class="fw-semibold text-primary">

                        Professional Information

                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         VALIDATION ERRORS
    ================================================================= --}}

    @if ($errors->any())

        <div class="alert alert-danger alert-dismissible fade show shadow-sm">

            <div class="fw-bold mb-2">

                <i class="bi bi-exclamation-triangle me-1"></i>

                Please correct the following errors:

            </div>

            <ul class="mb-0 ps-4">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ================================================================
         SUCCESS MESSAGE
    ================================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show shadow-sm">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ================================================================
         STEP 3 FORM
    ================================================================= --}}

    <form
        method="POST"
        action="{{ route('author.profile.step3.update') }}">

        @csrf
        @method('PATCH')


        {{-- ============================================================
             PROFESSIONAL INFORMATION
        ============================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom">

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-briefcase text-primary me-2"></i>

                    Professional Information

                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3">


                    {{-- Institution --}}

                    <div class="col-md-12">

                        <label
                            for="institution"
                            class="form-label fw-semibold">

                            Institution / Organization
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="institution"
                            id="institution"
                            class="form-control @error('institution') is-invalid @enderror"
                            value="{{ old('institution', $authorProfile?->institution ?? '') }}"
                            placeholder="Current institution / organization"
                            required>

                        @error('institution')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Department --}}

                    <div class="col-md-6">

                        <label
                            for="department"
                            class="form-label fw-semibold">

                            Department
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="department"
                            id="department"
                            class="form-control @error('department') is-invalid @enderror"
                            value="{{ old('department', $authorProfile?->department ?? '') }}"
                            placeholder="Department / Unit"
                            required>

                        @error('department')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Designation --}}

                    <div class="col-md-6">

                        <label
                            for="designation"
                            class="form-label fw-semibold">

                            Designation
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="designation"
                            id="designation"
                            class="form-control @error('designation') is-invalid @enderror"
                            value="{{ old('designation', $authorProfile?->designation ?? '') }}"
                            placeholder="Current designation"
                            required>

                        @error('designation')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Academic Degree --}}

                    <div class="col-md-6">

                        <label
                            for="academic_degree"
                            class="form-label fw-semibold">

                            Academic Degree

                            <span class="text-muted fw-normal">
                                (Optional)
                            </span>

                        </label>

                        <input
                            type="text"
                            name="academic_degree"
                            id="academic_degree"
                            class="form-control @error('academic_degree') is-invalid @enderror"
                            value="{{ old('academic_degree', $authorProfile?->academic_degree ?? '') }}"
                            placeholder="e.g. MBBS, MPH, PhD, MD">

                        @error('academic_degree')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Specialization --}}

                    <div class="col-md-6">

                        <label
                            for="specialization"
                            class="form-label fw-semibold">

                            Specialization

                            <span class="text-muted fw-normal">
                                (Optional)
                            </span>

                        </label>

                        <input
                            type="text"
                            name="specialization"
                            id="specialization"
                            class="form-control @error('specialization') is-invalid @enderror"
                            value="{{ old('specialization', $authorProfile?->specialization ?? '') }}"
                            placeholder="Area of specialization">

                        @error('specialization')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Professional Registration --}}

                    <div class="col-md-6">

                        <label
                            for="professional_registration_no"
                            class="form-label fw-semibold">

                            Professional Registration No.

                            <span class="text-muted fw-normal">
                                (Optional)
                            </span>

                        </label>

                        <input
                            type="text"
                            name="professional_registration_no"
                            id="professional_registration_no"
                            class="form-control @error('professional_registration_no') is-invalid @enderror"
                            value="{{ old('professional_registration_no', $authorProfile?->professional_registration_no ?? '') }}"
                            placeholder="Professional registration number">

                        @error('professional_registration_no')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Research Interest --}}

                    <div class="col-md-12">

                        <label
                            for="research_interest"
                            class="form-label fw-semibold">

                            Research Interest

                            <span class="text-muted fw-normal">
                                (Optional)
                            </span>

                        </label>

                        <textarea
                            name="research_interest"
                            id="research_interest"
                            rows="4"
                            class="form-control @error('research_interest') is-invalid @enderror"
                            placeholder="Describe your major research interests">{{ old('research_interest', $authorProfile?->research_interest ?? '') }}</textarea>

                        <div class="form-text">
                            You may enter multiple research areas separated by commas.
                        </div>

                        @error('research_interest')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
             RESEARCH IDENTIFIERS
        ============================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom">

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-bar-chart-line text-primary me-2"></i>

                    Researcher Profiles & Identifiers

                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3">


                    {{-- ORCID --}}

                    <div class="col-md-6">

                        <label
                            for="orcid"
                            class="form-label fw-semibold">

                            ORCID iD

                            <span class="text-muted fw-normal">
                                (Recommended)
                            </span>

                        </label>

                        <input
                            type="text"
                            name="orcid"
                            id="orcid"
                            class="form-control @error('orcid') is-invalid @enderror"
                            value="{{ old('orcid', $authorProfile?->orcid ?? '') }}"
                            placeholder="0000-0000-0000-0000">

                        <div class="form-text">
                            Example: 0000-0002-1825-0097
                        </div>

                        @error('orcid')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Researcher ID --}}

                    <div class="col-md-6">

                        <label
                            for="researcher_id"
                            class="form-label fw-semibold">

                            Researcher ID

                        </label>

                        <input
                            type="text"
                            name="researcher_id"
                            id="researcher_id"
                            class="form-control @error('researcher_id') is-invalid @enderror"
                            value="{{ old('researcher_id', $authorProfile?->researcher_id ?? '') }}"
                            placeholder="Researcher ID">

                        @error('researcher_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Scopus --}}

                    <div class="col-md-6">

                        <label
                            for="scopus_author_id"
                            class="form-label fw-semibold">

                            Scopus Author ID

                        </label>

                        <input
                            type="text"
                            name="scopus_author_id"
                            id="scopus_author_id"
                            class="form-control @error('scopus_author_id') is-invalid @enderror"
                            value="{{ old('scopus_author_id', $authorProfile?->scopus_author_id ?? '') }}"
                            placeholder="Scopus Author ID">

                        @error('scopus_author_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Web of Science --}}

                    <div class="col-md-6">

                        <label
                            for="web_of_science_id"
                            class="form-label fw-semibold">

                            Web of Science Researcher ID

                        </label>

                        <input
                            type="text"
                            name="web_of_science_id"
                            id="web_of_science_id"
                            class="form-control @error('web_of_science_id') is-invalid @enderror"
                            value="{{ old('web_of_science_id', $authorProfile?->web_of_science_id ?? '') }}"
                            placeholder="Web of Science Researcher ID">

                        @error('web_of_science_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Google Scholar --}}

                    <div class="col-md-12">

                        <label
                            for="google_scholar_profile"
                            class="form-label fw-semibold">

                            Google Scholar Profile

                        </label>

                        <input
                            type="url"
                            name="google_scholar_profile"
                            id="google_scholar_profile"
                            class="form-control @error('google_scholar_profile') is-invalid @enderror"
                            value="{{ old('google_scholar_profile', $authorProfile?->google_scholar_profile ?? '') }}"
                            placeholder="https://scholar.google.com/...">

                        @error('google_scholar_profile')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
             COMMUNICATION PREFERENCES
        ============================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom">

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-chat-dots text-primary me-2"></i>

                    Communication Preferences

                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3">


                    {{-- Preferred Communication Method --}}

                    <div class="col-md-6">

                        <label
                            for="preferred_communication_method"
                            class="form-label fw-semibold">

                            Preferred Communication Method

                        </label>

                        <select
                            name="preferred_communication_method"
                            id="preferred_communication_method"
                            class="form-select @error('preferred_communication_method') is-invalid @enderror">

                            <option value="">
                                Select Method
                            </option>

                            <option
                                value="Email"
                                {{ old('preferred_communication_method', $authorProfile?->preferred_communication_method ?? '') === 'Email' ? 'selected' : '' }}>

                                Email

                            </option>

                            <option
                                value="Mobile"
                                {{ old('preferred_communication_method', $authorProfile?->preferred_communication_method ?? '') === 'Mobile' ? 'selected' : '' }}>

                                Mobile

                            </option>

                            <option
                                value="Both"
                                {{ old('preferred_communication_method', $authorProfile?->preferred_communication_method ?? '') === 'Both' ? 'selected' : '' }}>

                                Both

                            </option>

                        </select>

                        @error('preferred_communication_method')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Editorial Communication --}}

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Editorial Communication
                        </label>

                        <div class="form-check form-switch mt-2">

                            <input
                                type="hidden"
                                name="available_for_editorial_communication"
                                value="0">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="available_for_editorial_communication"
                                value="1"
                                id="editorialCommunication"
                                {{ old(
                                    'available_for_editorial_communication',
                                    $authorProfile?->available_for_editorial_communication ?? true
                                ) ? 'checked' : '' }}>

                            <label
                                class="form-check-label"
                                for="editorialCommunication">

                                Available for editorial communication

                            </label>

                        </div>

                        @error('available_for_editorial_communication')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
             PROFILE SUMMARY
        ============================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom">

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-person-check text-success me-2"></i>

                    Profile Summary

                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3">


                    {{-- Author ID --}}

                    <div class="col-md-6">

                        <div class="text-muted small">
                            Author ID
                        </div>

                        <div class="fw-semibold">
                            {{ $authorProfile?->author_id ?? '-' }}
                        </div>

                    </div>


                    {{-- Author Name --}}

                    <div class="col-md-6">

                        <div class="text-muted small">
                            Author Name
                        </div>

                        <div class="fw-semibold">
                            {{ $authorProfile?->display_name ?? auth()->user()->name }}
                        </div>

                    </div>


                    {{-- Institution --}}

                    <div class="col-md-6">

                        <div class="text-muted small">
                            Institution
                        </div>

                        <div class="fw-semibold">
                            {{ $authorProfile?->institution ?? '-' }}
                        </div>

                    </div>


                    {{-- Designation --}}

                    <div class="col-md-6">

                        <div class="text-muted small">
                            Designation
                        </div>

                        <div class="fw-semibold">
                            {{ $authorProfile?->designation ?? '-' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
             PROFILE DECLARATION
        ============================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom">

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-shield-check text-primary me-2"></i>

                    Profile Declaration

                </h5>

            </div>

            <div class="card-body">

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="profile_declaration"
                        value="1"
                        id="profileDeclaration"
                        {{ old('profile_declaration') ? 'checked' : '' }}>

                    <label
                        class="form-check-label"
                        for="profileDeclaration">

                        I confirm that the information provided in my
                        BMRC Author Profile is accurate and up to date.

                        <span class="text-danger">*</span>

                    </label>

                </div>

                <div class="form-text mt-2">

                    The declaration is required when you select
                    <strong>Save & Complete Profile</strong>.

                </div>

            </div>

        </div>


        {{-- ============================================================
             ACTION BUTTONS
        ============================================================= --}}

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex flex-wrap justify-content-between gap-2">


                    {{-- Previous --}}

                    <a
                        href="{{ route('author.profile.step2') }}"
                        class="btn btn-outline-secondary">

                        <i class="bi bi-arrow-left me-1"></i>

                        Previous

                    </a>


                    <div class="d-flex gap-2 flex-wrap">


                        {{-- Save --}}

                        <button
                            type="submit"
                            name="action"
                            value="save"
                            class="btn btn-outline-primary">

                            <i class="bi bi-save me-1"></i>

                            Save

                        </button>


                        {{-- Save & Complete --}}

                        <button
                            type="submit"
                            name="action"
                            value="complete"
                            class="btn btn-success px-4">

                            <i class="bi bi-check-circle me-1"></i>

                            Save & Complete Profile

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection