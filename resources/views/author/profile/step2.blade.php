{{--
|--------------------------------------------------------------------------
| Author Profile - Step 2: Personal Information
|--------------------------------------------------------------------------
| File:
| resources/views/author/profile/step2.blade.php
|
| Purpose:
| Collect the author's personal and contact information.
|--------------------------------------------------------------------------
--}}

@extends('author.layouts.app')

@section('title', 'Personal Information')

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

                Personal Information

            </h4>

            <p class="text-muted mb-0">

                Step 2 of 3 — Complete your personal information.

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

                        Step 2 of 3

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
                     STEP 1
                ===================================================== --}}

                <div class="col-4">

                    <div class="mb-2">

                        @if($step1Complete)

                            <span
                                class="badge rounded-pill bg-success px-3 py-2">

                                <i class="bi bi-check-lg me-1"></i>

                                Step 1

                            </span>

                        @else

                            <span
                                class="badge rounded-pill bg-secondary px-3 py-2">

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
                     STEP 2 - CURRENT
                ===================================================== --}}

                <div class="col-4">

                    <div class="mb-2">

                        <span
                            class="badge rounded-pill bg-primary px-3 py-2">

                            <i class="bi bi-pencil me-1"></i>

                            Step 2

                        </span>

                    </div>


                    <small class="fw-semibold text-primary">

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
         PERSONAL INFORMATION FORM
    ================================================================= --}}

    <form
        action="{{ route('author.profile.step2.update') }}"
        method="POST">

        @csrf

        @method('PATCH')


        {{-- ============================================================
             PERSONAL DETAILS
        ============================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-person me-2 text-primary"></i>

                    Personal Details

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- =================================================
                         DATE OF BIRTH
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="date_of_birth"
                            class="form-label fw-semibold">

                            Date of Birth

                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="date"
                            name="date_of_birth"
                            id="date_of_birth"
                            class="form-control @error('date_of_birth') is-invalid @enderror"
                            value="{{ old('date_of_birth', optional($authorProfile?->date_of_birth)->format('Y-m-d')) }}"
                            max="{{ now()->format('Y-m-d') }}"
                            required>


                        @error('date_of_birth')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         GENDER
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="gender"
                            class="form-label fw-semibold">

                            Gender

                            <span class="text-danger">*</span>

                        </label>


                        <select
                            name="gender"
                            id="gender"
                            class="form-select @error('gender') is-invalid @enderror"
                            required>

                            <option value="">

                                -- Select Gender --

                            </option>


                            <option
                                value="male"
                                {{ old('gender', $authorProfile?->gender) === 'male' ? 'selected' : '' }}>

                                Male

                            </option>


                            <option
                                value="female"
                                {{ old('gender', $authorProfile?->gender) === 'female' ? 'selected' : '' }}>

                                Female

                            </option>


                            <option
                                value="other"
                                {{ old('gender', $authorProfile?->gender) === 'other' ? 'selected' : '' }}>

                                Other

                            </option>


                            <option
                                value="prefer_not_to_say"
                                {{ old('gender', $authorProfile?->gender) === 'prefer_not_to_say' ? 'selected' : '' }}>

                                Prefer not to say

                            </option>

                        </select>


                        @error('gender')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         NATIONALITY
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="nationality"
                            class="form-label fw-semibold">

                            Nationality

                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="text"
                            name="nationality"
                            id="nationality"
                            class="form-control @error('nationality') is-invalid @enderror"
                            value="{{ old('nationality', $authorProfile?->nationality) }}"
                            placeholder="e.g. Bangladeshi"
                            required>


                        @error('nationality')

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
             LOCATION INFORMATION
        ============================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-geo-alt me-2 text-primary"></i>

                    Location Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- =================================================
                         DIVISION / STATE
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="division_state"
                            class="form-label fw-semibold">

                            Division / State

                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="text"
                            name="division_state"
                            id="division_state"
                            class="form-control @error('division_state') is-invalid @enderror"
                            value="{{ old('division_state', $authorProfile?->division_state) }}"
                            placeholder="e.g. Dhaka"
                            required>


                        @error('division_state')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         CITY / DISTRICT
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="city_district"
                            class="form-label fw-semibold">

                            City / District

                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="text"
                            name="city_district"
                            id="city_district"
                            class="form-control @error('city_district') is-invalid @enderror"
                            value="{{ old('city_district', $authorProfile?->city_district) }}"
                            placeholder="e.g. Dhaka"
                            required>


                        @error('city_district')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         POSTAL ADDRESS
                    ================================================== --}}

                    <div class="col-12">

                        <label
                            for="postal_address"
                            class="form-label fw-semibold">

                            Postal Address

                            <span class="text-danger">*</span>

                        </label>


                        <textarea
                            name="postal_address"
                            id="postal_address"
                            rows="3"
                            class="form-control @error('postal_address') is-invalid @enderror"
                            placeholder="Enter your complete postal address"
                            required>{{ old('postal_address', $authorProfile?->postal_address) }}</textarea>


                        @error('postal_address')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         OFFICE ADDRESS
                    ================================================== --}}

                    <div class="col-12">

                        <label
                            for="office_address"
                            class="form-label fw-semibold">

                            Office Address

                            <span class="text-muted fw-normal">

                                (Optional)

                            </span>

                        </label>


                        <textarea
                            name="office_address"
                            id="office_address"
                            rows="3"
                            class="form-control @error('office_address') is-invalid @enderror"
                            placeholder="Enter your office / institutional address">{{ old('office_address', $authorProfile?->office_address) }}</textarea>


                        @error('office_address')

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
                         BACK TO STEP 1
                    ================================================== --}}

                    <a
                        href="{{ route('author.profile.step1') }}"
                        class="btn btn-outline-secondary">

                        <i class="bi bi-arrow-left me-1"></i>

                        Previous

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
