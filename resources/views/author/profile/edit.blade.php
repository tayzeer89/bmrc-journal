@extends('author.layouts.app')

@section('title', 'Author Profile - Minimum Information')

@section('content')

<div class="container-fluid py-4">

{{-- =========================================================
     PAGE HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Author Profile
        </h2>

        <p class="text-muted mb-0">
            Complete your minimum information
        </p>
    </div>

    <div>
        <span class="badge bg-primary px-3 py-2">
            Step 1 of 3
        </span>
    </div>

</div>


{{-- =========================================================
     PROFILE PROGRESS
========================================================== --}}
@php
    $step1Complete =
        !empty($authorProfile->title) &&
        !empty($authorProfile->first_name) &&
        !empty($authorProfile->last_name) &&
        !empty($authorProfile->display_name) &&
        !empty($authorProfile->mobile) &&
        !empty($authorProfile->country) &&
        !empty($authorProfile->institution) &&
        !empty($authorProfile->department) &&
        !empty($authorProfile->designation);

    $step2Complete =
        !empty($authorProfile->gender) &&
        !empty($authorProfile->date_of_birth) &&
        !empty($authorProfile->nationality) &&
        !empty($authorProfile->division_state) &&
        !empty($authorProfile->city_district) &&
        !empty($authorProfile->postal_address);

    $step3Complete =
        !empty($authorProfile->institution) &&
        !empty($authorProfile->department) &&
        !empty($authorProfile->designation) &&
        $authorProfile->profile_completed;

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

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">

        <div class="d-flex justify-content-between mb-2">
            <span class="fw-semibold">
                Profile Completion
            </span>

            <span class="text-primary fw-semibold">
                {{ $completion }}%
            </span>
        </div>

        <div class="progress" style="height: 8px;">
            <div class="progress-bar
                {{ $completion == 100 ? 'bg-success' : '' }}"
                role="progressbar"
                style="width: {{ $completion }}%;"
                aria-valuenow="{{ $completion }}"
                aria-valuemin="0"
                aria-valuemax="100">
            </div>
        </div>

        <div class="d-flex flex-wrap justify-content-between mt-3 small gap-2">

            {{-- Step 1 --}}
            <span class="{{ $step1Complete ? 'text-success fw-semibold' : 'text-primary fw-semibold' }}">

                @if($step1Complete)
                    <i class="bi bi-check-circle-fill me-1"></i>
                @else
                    <i class="bi bi-circle-fill me-1"></i>
                @endif

                1. Minimum Information
            </span>

            {{-- Step 2 --}}
            <span class="{{ $step2Complete ? 'text-success fw-semibold' : 'text-muted' }}">

                @if($step2Complete)
                    <i class="bi bi-check-circle-fill me-1"></i>
                @else
                    <i class="bi bi-circle me-1"></i>
                @endif

                2. Personal Information
            </span>

            {{-- Step 3 --}}
            <span class="{{ $step3Complete ? 'text-success fw-semibold' : 'text-muted' }}">

                @if($step3Complete)
                    <i class="bi bi-check-circle-fill me-1"></i>
                @else
                    <i class="bi bi-circle me-1"></i>
                @endif

                3. Professional Information
            </span>

        </div>

    </div>
</div>



{{-- =========================================================
     MAIN FORM
========================================================== --}}
<form
    method="POST"
    action="{{ route('author.profile.step1.update') }}">

    @csrf

    @method('PATCH')


    {{-- =====================================================
         BASIC INFORMATION
    ====================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="bi bi-person-vcard text-primary me-2"></i>

                Minimum Information

            </h5>

            <small class="text-muted">

                Basic information required for your BMRC Author account.

            </small>

        </div>


        <div class="card-body p-4">

            <div class="row g-3">


                {{-- Author ID --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Author ID
                    </label>

                    <input
                        type="text"
                        class="form-control bg-light"
                        value="{{ $authorProfile->author_id ?? 'Not Generated' }}"
                        readonly>

                    <div class="form-text">
                        Automatically generated by BMRC system.
                    </div>

                </div>


                {{-- Title --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Title

                        <span class="text-danger">*</span>

                    </label>

                    <select
                        name="title"
                        class="form-select"
                        required>

                        <option value="">
                            Select Title
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
                                {{ old('title', $authorProfile->title ?? '') == $title ? 'selected' : '' }}>

                                {{ $title }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- First Name --}}
                <div class="col-md-4">

                    <label class="form-label fw-semibold">

                        First Name

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        name="first_name"
                        class="form-control"
                        value="{{ old('first_name', $authorProfile->first_name ?? '') }}"
                        required>

                </div>


                {{-- Middle Name --}}
                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Middle Name
                    </label>

                    <input
                        type="text"
                        name="middle_name"
                        class="form-control"
                        value="{{ old('middle_name', $authorProfile->middle_name ?? '') }}">

                </div>


                {{-- Last Name --}}
                <div class="col-md-4">

                    <label class="form-label fw-semibold">

                        Last Name

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        name="last_name"
                        class="form-control"
                        value="{{ old('last_name', $authorProfile->last_name ?? '') }}"
                        required>

                </div>


                {{-- Display Name --}}
                <div class="col-md-12">

                    <label class="form-label fw-semibold">

                        Display Name

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        name="display_name"
                        class="form-control"
                        value="{{ old(
                            'display_name',
                            $authorProfile->display_name ??
                            trim(
                                ($authorProfile->title ?? '') . ' ' .
                                ($authorProfile->first_name ?? '') . ' ' .
                                ($authorProfile->middle_name ?? '') . ' ' .
                                ($authorProfile->last_name ?? '')
                            )
                        ) }}"
                        placeholder="Name to appear in journal correspondence"
                        required>

                    <div class="form-text">
                        Example: Dr. S M Sayadat Amin
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         CONTACT INFORMATION
    ====================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="bi bi-envelope text-primary me-2"></i>

                Contact Information

            </h5>

        </div>


        <div class="card-body p-4">

            <div class="row g-3">


                {{-- Email --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Email Address

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', auth()->user()->email ?? '') }}"
                        required>

                </div>


                {{-- Alternative Email --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Alternative Email
                    </label>

                    <input
                        type="email"
                        name="alternative_email"
                        class="form-control"
                        value="{{ old('alternative_email', $authorProfile->alternative_email ?? '') }}"
                        placeholder="Optional">

                </div>


                {{-- Mobile --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Mobile Number

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        name="mobile"
                        class="form-control"
                        value="{{ old('mobile', $authorProfile->mobile ?? '') }}"
                        placeholder="01XXXXXXXXX"
                        required>

                </div>


                {{-- Country --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Country

                        <span class="text-danger">*</span>

                    </label>

                    <select
                        name="country"
                        class="form-select"
                        required>

                        <option value="">
                            Select Country
                        </option>

                        @foreach([
                            'Bangladesh',
                            'India',
                            'Pakistan',
                            'Nepal',
                            'Other'
                        ] as $country)

                            <option
                                value="{{ $country }}"
                                {{ old('country', $authorProfile->country ?? '') == $country ? 'selected' : '' }}>

                                {{ $country }}

                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         ORGANIZATION INFORMATION
    ====================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="bi bi-building text-primary me-2"></i>

                Organization Information

            </h5>

        </div>


        <div class="card-body p-4">

            <div class="row g-3">


                {{-- Institution --}}
                <div class="col-md-12">

                    <label class="form-label fw-semibold">

                        Organization / Institution

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        name="institution"
                        class="form-control"
                        value="{{ old('institution', $authorProfile->institution ?? '') }}"
                        placeholder="Current organization / institution"
                        required>

                </div>


                {{-- Department --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Department

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        name="department"
                        class="form-control"
                        value="{{ old('department', $authorProfile->department ?? '') }}"
                        placeholder="Department / Unit"
                        required>

                </div>


                {{-- Designation --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Designation

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        name="designation"
                        class="form-control"
                        value="{{ old('designation', $authorProfile->designation ?? '') }}"
                        placeholder="Current professional designation"
                        required>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         RESEARCH IDENTIFIERS
    ====================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="bi bi-person-badge text-primary me-2"></i>

                Research Identifiers

            </h5>

            <small class="text-muted">

                ORCID is recommended but not mandatory.

            </small>

        </div>


        <div class="card-body p-4">

            <div class="row g-3">


                {{-- ORCID --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        ORCID iD

                        <span class="text-muted">
                            (Recommended)
                        </span>

                    </label>

                    <input
                        type="text"
                        name="orcid"
                        class="form-control"
                        value="{{ old('orcid', $authorProfile->orcid ?? '') }}"
                        placeholder="0000-0000-0000-0000">

                </div>


                {{-- Researcher ID --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Researcher ID
                    </label>

                    <input
                        type="text"
                        name="researcher_id"
                        class="form-control"
                        value="{{ old('researcher_id', $authorProfile->researcher_id ?? '') }}"
                        placeholder="Researcher ID">

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         ACTION BUTTONS
    ====================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center">

                <a
                    href="{{ route('author.dashboard') }}"
                    class="btn btn-outline-secondary">

                    <i class="bi bi-arrow-left me-1"></i>

                    Back to Dashboard

                </a>


                <button
                    type="submit"
                    class="btn btn-primary px-4">

                    Save & Continue

                    <i class="bi bi-arrow-right ms-1"></i>

                </button>

            </div>

        </div>

    </div>

</form>


</div>

@endsection
