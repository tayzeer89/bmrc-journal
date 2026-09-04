{{-- 
|--------------------------------------------------------------------------
| Author Profile - Step 1: Minimum Information
|--------------------------------------------------------------------------
| File:
| resources/views/author/profile/step1.blade.php
|
| Purpose:
| Collect the author's minimum required information.
|--------------------------------------------------------------------------
--}}

@extends('author.layouts.app')

@section('title', 'Minimum Information')

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
                Minimum Information
            </h4>

            <p class="text-muted mb-0">
                Step 1 of 3 — Complete your minimum information.
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
                        Step 1 of 3
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
                    class="progress-bar"
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
                     STEP 1 - CURRENT
                ===================================================== --}}

                <div class="col-4">

                    <div class="mb-2">

                        <span
                            class="badge rounded-pill bg-primary px-3 py-2">

                            <i class="bi bi-pencil me-1"></i>
                            Step 1

                        </span>

                    </div>

                    <small class="fw-semibold text-primary">
                        Minimum Information
                    </small>

                </div>


                {{-- ====================================================
                     STEP 2
                ===================================================== --}}

                <div class="col-4">

                    <div class="mb-2">

                        @if($step2Complete)

                            <span
                                class="badge rounded-pill bg-success px-3 py-2">

                                <i class="bi bi-check-lg me-1"></i>
                                Step 2

                            </span>

                        @else

                            <span
                                class="badge rounded-pill bg-secondary px-3 py-2">

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
                     STEP 3
                ===================================================== --}}

                <div class="col-4">

                    <div class="mb-2">

                        @if($step3Complete)

                            <span
                                class="badge rounded-pill bg-success px-3 py-2">

                                <i class="bi bi-check-lg me-1"></i>
                                Step 3

                            </span>

                        @else

                            <span
                                class="badge rounded-pill bg-secondary px-3 py-2">

                                Step 3

                            </span>

                        @endif

                    </div>

                    <small
                        class="{{ $step3Complete ? 'fw-semibold text-success' : 'text-muted' }}">

                        Professional Information

                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         VALIDATION ERRORS
    ================================================================= --}}

    @if($errors->any())

        <div
            class="alert alert-danger alert-dismissible fade show shadow-sm">

            <div class="fw-bold mb-2">

                <i class="bi bi-exclamation-triangle me-1"></i>

                Please correct the following errors:

            </div>

            <ul class="mb-0 ps-4">

                @foreach($errors->all() as $error)

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

        <div
            class="alert alert-success alert-dismissible fade show shadow-sm">

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
         MINIMUM INFORMATION FORM
    ================================================================= --}}

    <form
        action="{{ route('author.profile.step1.update') }}"
        method="POST">

        @csrf
        @method('PATCH')


        {{-- ============================================================
             AUTHOR INFORMATION
        ============================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-person me-2 text-primary"></i>

                    Author Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- =================================================
                         AUTHOR ID
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="author_id"
                            class="form-label fw-semibold">

                            Author ID

                        </label>

                        <input
                            type="text"
                            name="author_id"
                            id="author_id"
                            class="form-control"
                            value="{{ old('author_id', $authorProfile?->author_id ?? auth()->user()->id) }}"
                            readonly>

                    </div>


                    {{-- =================================================
                         TITLE
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="title"
                            class="form-label fw-semibold">

                            Title
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="title"
                            id="title"
                            class="form-select @error('title') is-invalid @enderror"
                            required>

                            <option value="">
                                -- Select Title --
                            </option>

                            <option
                                value="Dr."
                                {{ old('title', $authorProfile?->title) === 'Dr.' ? 'selected' : '' }}>

                                Dr.

                            </option>

                            <option
                                value="Prof."
                                {{ old('title', $authorProfile?->title) === 'Prof.' ? 'selected' : '' }}>

                                Prof.

                            </option>

                            <option
                                value="Mr."
                                {{ old('title', $authorProfile?->title) === 'Mr.' ? 'selected' : '' }}>

                                Mr.

                            </option>

                            <option
                                value="Ms."
                                {{ old('title', $authorProfile?->title) === 'Ms.' ? 'selected' : '' }}>

                                Ms.

                            </option>

                            <option
                                value="Mrs."
                                {{ old('title', $authorProfile?->title) === 'Mrs.' ? 'selected' : '' }}>

                                Mrs.

                            </option>

                        </select>

                        @error('title')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         FIRST NAME
                    ================================================== --}}

                    <div class="col-md-4">

                        <label
                            for="first_name"
                            class="form-label fw-semibold">

                            First Name
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="first_name"
                            id="first_name"
                            class="form-control @error('first_name') is-invalid @enderror"
                            value="{{ old('first_name', $authorProfile?->first_name) }}"
                            placeholder="Enter first name"
                            required>

                        @error('first_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         MIDDLE NAME
                    ================================================== --}}

                    <div class="col-md-4">

                        <label
                            for="middle_name"
                            class="form-label fw-semibold">

                            Middle Name
                            <span class="text-muted fw-normal">
                                (Optional)
                            </span>

                        </label>

                        <input
                            type="text"
                            name="middle_name"
                            id="middle_name"
                            class="form-control @error('middle_name') is-invalid @enderror"
                            value="{{ old('middle_name', $authorProfile?->middle_name) }}"
                            placeholder="Enter middle name">

                        @error('middle_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         LAST NAME
                    ================================================== --}}

                    <div class="col-md-4">

                        <label
                            for="last_name"
                            class="form-label fw-semibold">

                            Last Name
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="last_name"
                            id="last_name"
                            class="form-control @error('last_name') is-invalid @enderror"
                            value="{{ old('last_name', $authorProfile?->last_name) }}"
                            placeholder="Enter last name"
                            required>

                        @error('last_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         DISPLAY NAME
                    ================================================== --}}

                    <div class="col-12">

                        <label
                            for="display_name"
                            class="form-label fw-semibold">

                            Display Name
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="display_name"
                            id="display_name"
                            class="form-control @error('display_name') is-invalid @enderror"
                            value="{{ old('display_name', $authorProfile?->display_name) }}"
                            placeholder="Name as it should appear in publications"
                            required>

                        @error('display_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
             CONTACT INFORMATION
        ============================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-telephone me-2 text-primary"></i>

                    Contact Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- =================================================
                         EMAIL
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="email"
                            class="form-label fw-semibold">

                            Email Address
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $authorProfile?->email ?? auth()->user()->email) }}"
                            placeholder="name@example.com"
                            required>

                        @error('email')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         ALTERNATIVE EMAIL
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="alternative_email"
                            class="form-label fw-semibold">

                            Alternative Email
                            <span class="text-muted fw-normal">
                                (Optional)
                            </span>

                        </label>

                        <input
                            type="email"
                            name="alternative_email"
                            id="alternative_email"
                            class="form-control @error('alternative_email') is-invalid @enderror"
                            value="{{ old('alternative_email', $authorProfile?->alternative_email) }}"
                            placeholder="Alternative email address">

                        @error('alternative_email')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         MOBILE
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="mobile"
                            class="form-label fw-semibold">

                            Mobile Number
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="mobile"
                            id="mobile"
                            class="form-control @error('mobile') is-invalid @enderror"
                            value="{{ old('mobile', $authorProfile?->mobile) }}"
                            placeholder="+8801XXXXXXXXX"
                            required>

                        @error('mobile')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         COUNTRY
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="country"
                            class="form-label fw-semibold">

                            Country
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="country"
                            id="country"
                            class="form-control @error('country') is-invalid @enderror"
                            value="{{ old('country', $authorProfile?->country) }}"
                            placeholder="e.g. Bangladesh"
                            required>

                        @error('country')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
             ORGANIZATION INFORMATION
        ============================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-building me-2 text-primary"></i>

                    Organization Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- =================================================
                         INSTITUTION
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="institution"
                            class="form-label fw-semibold">

                            Institution
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="institution"
                            id="institution"
                            class="form-control @error('institution') is-invalid @enderror"
                            value="{{ old('institution', $authorProfile?->institution) }}"
                            placeholder="Enter institution name"
                            required>

                        @error('institution')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         DEPARTMENT
                    ================================================== --}}

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
                            value="{{ old('department', $authorProfile?->department) }}"
                            placeholder="Enter department"
                            required>

                        @error('department')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         DESIGNATION
                    ================================================== --}}

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
                            value="{{ old('designation', $authorProfile?->designation) }}"
                            placeholder="e.g. Scientific Officer"
                            required>

                        @error('designation')

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

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-fingerprint me-2 text-primary"></i>

                    Research Identifiers

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- =================================================
                         ORCID
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="orcid"
                            class="form-label fw-semibold">

                            ORCID
                            <span class="text-muted fw-normal">
                                (Optional)
                            </span>

                        </label>

                        <input
                            type="text"
                            name="orcid"
                            id="orcid"
                            class="form-control @error('orcid') is-invalid @enderror"
                            value="{{ old('orcid', $authorProfile?->orcid) }}"
                            placeholder="0000-0000-0000-0000">

                        @error('orcid')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         RESEARCHER ID
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="researcher_id"
                            class="form-label fw-semibold">

                            Researcher ID
                            <span class="text-muted fw-normal">
                                (Optional)
                            </span>

                        </label>

                        <input
                            type="text"
                            name="researcher_id"
                            id="researcher_id"
                            class="form-control @error('researcher_id') is-invalid @enderror"
                            value="{{ old('researcher_id', $authorProfile?->researcher_id) }}"
                            placeholder="Enter Researcher ID">

                        @error('researcher_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
             FORM ACTIONS
        ============================================================= --}}

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex flex-wrap justify-content-between gap-2">


                    {{-- =================================================
                         BACK TO DASHBOARD
                    ================================================== --}}

                    <a
                        href="{{ route('author.dashboard') }}"
                        class="btn btn-outline-secondary">

                        <i class="bi bi-arrow-left me-1"></i>

                        Dashboard

                    </a>


                    <div class="d-flex gap-2">


                        {{-- =================================================
                             SAVE
                        ================================================== --}}

                        <button
                            type="submit"
                            name="action"
                            value="save"
                            class="btn btn-outline-primary">

                            <i class="bi bi-save me-1"></i>

                            Save

                        </button>


                        {{-- =================================================
                             SAVE & CONTINUE
                        ================================================== --}}

                        <button
                            type="submit"
                            name="action"
                            value="continue"
                            class="btn btn-primary">

                            Save & Continue

                            <i class="bi bi-arrow-right ms-1"></i>

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection
