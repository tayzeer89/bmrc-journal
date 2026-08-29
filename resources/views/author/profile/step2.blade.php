@extends('author.layouts.app')

@section('title', 'Author Profile - Personal Information')

@section('content')

<div class="container-fluid py-4">

{{-- Page Header --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Author Profile</h2>
        <p class="text-muted mb-0">
            Complete your personal information
        </p>
    </div>

    <span class="badge bg-primary px-3 py-2">
        Step 2 of 3
    </span>
</div>


{{-- Progress --}}
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


{{-- Validation Errors --}}
@if ($errors->any())

    <div class="alert alert-danger">
        <strong>Please correct the following errors:</strong>

        <ul class="mb-0 mt-2">

            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach

        </ul>
    </div>

@endif


{{-- Success Message --}}
@if(session('success'))

    <div class="alert alert-success">
        <i class="bi bi-check-circle me-1"></i>
        {{ session('success') }}
    </div>

@endif


{{-- Personal Information Form --}}
<form method="POST"
      action="{{ route('author.profile.step2.update') }}">

    @csrf
    @method('PATCH')


    {{-- =========================
         PERSONAL INFORMATION
    ========================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="bi bi-person text-primary me-2"></i>

                Personal Information

            </h5>

            <small class="text-muted">
                Provide your personal information.
            </small>

        </div>


        <div class="card-body p-4">

            <div class="row g-3">


                {{-- Date of Birth --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Date of Birth
                    </label>

                    <input type="date"
                           name="date_of_birth"
                           class="form-control"
                           value="{{ old('date_of_birth', $authorProfile->date_of_birth ?? '') }}">

                </div>


                {{-- Gender --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Gender
                    </label>

                    <select name="gender"
                            class="form-select">

                        <option value="">
                            Select Gender
                        </option>

                        <option value="Male"
                            {{ old('gender', $authorProfile->gender ?? '') == 'Male' ? 'selected' : '' }}>
                            Male
                        </option>

                        <option value="Female"
                            {{ old('gender', $authorProfile->gender ?? '') == 'Female' ? 'selected' : '' }}>
                            Female
                        </option>

                        <option value="Other"
                            {{ old('gender', $authorProfile->gender ?? '') == 'Other' ? 'selected' : '' }}>
                            Other
                        </option>

                    </select>

                </div>


                {{-- Nationality --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Nationality
                    </label>

                    <input type="text"
                           name="nationality"
                           class="form-control"
                           value="{{ old('nationality', $authorProfile->nationality ?? 'Bangladeshi') }}"
                           placeholder="Nationality">

                </div>


                {{-- Country --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Country
                    </label>

                    <select name="country"
                            class="form-select">

                        <option value="">
                            Select Country
                        </option>

                        <option value="Bangladesh"
                            {{ old('country', $authorProfile->country ?? '') == 'Bangladesh' ? 'selected' : '' }}>
                            Bangladesh
                        </option>

                        <option value="India"
                            {{ old('country', $authorProfile->country ?? '') == 'India' ? 'selected' : '' }}>
                            India
                        </option>

                        <option value="Pakistan"
                            {{ old('country', $authorProfile->country ?? '') == 'Pakistan' ? 'selected' : '' }}>
                            Pakistan
                        </option>

                        <option value="Nepal"
                            {{ old('country', $authorProfile->country ?? '') == 'Nepal' ? 'selected' : '' }}>
                            Nepal
                        </option>

                        <option value="Other"
                            {{ old('country', $authorProfile->country ?? '') == 'Other' ? 'selected' : '' }}>
                            Other
                        </option>

                    </select>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================
         LOCATION INFORMATION
    ========================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="bi bi-geo-alt text-primary me-2"></i>

                Location Information

            </h5>

        </div>


        <div class="card-body p-4">

            <div class="row g-3">


                {{-- Division --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Division / State
                    </label>

                    <input type="text"
                           name="division_state"
                           class="form-control"
                           value="{{ old('division_state', $authorProfile->division_state ?? '') }}"
                           placeholder="e.g. Dhaka">

                </div>


                {{-- District --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        City / District
                    </label>

                    <input type="text"
                           name="city_district"
                           class="form-control"
                           value="{{ old('city_district', $authorProfile->city_district ?? '') }}"
                           placeholder="e.g. Dhaka">

                </div>


                {{-- Postal Address --}}
                <div class="col-md-12">

                    <label class="form-label fw-semibold">
                        Postal Address
                    </label>

                    <textarea name="postal_address"
                              rows="3"
                              class="form-control"
                              placeholder="Enter your postal address">{{ old('postal_address', $authorProfile->postal_address ?? '') }}</textarea>

                </div>


                {{-- Office Address --}}
                <div class="col-md-12">

                    <label class="form-label fw-semibold">
                        Office Address
                    </label>

                    <textarea name="office_address"
                              rows="3"
                              class="form-control"
                              placeholder="Enter your office address">{{ old('office_address', $authorProfile->office_address ?? '') }}</textarea>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================
         IDENTIFICATION
    ========================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="bi bi-card-text text-primary me-2"></i>

                Identification Information

            </h5>

            <small class="text-muted">
                Identification information is optional.
            </small>

        </div>


        <div class="card-body p-4">

            <div class="row g-3">


                {{-- NID --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        National ID (NID) Number
                    </label>

                    <input type="text"
                           name="nid_number"
                           class="form-control"
                           value="{{ old('nid_number', $authorProfile->nid_number ?? '') }}"
                           placeholder="NID Number">

                </div>


                {{-- Passport --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Passport Number
                    </label>

                    <input type="text"
                           name="passport_number"
                           class="form-control"
                           value="{{ old('passport_number', $authorProfile->passport_number ?? '') }}"
                           placeholder="Passport Number">

                </div>

            </div>

        </div>

    </div>


    {{-- =========================
         ACTION BUTTONS
    ========================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <div class="d-flex flex-wrap justify-content-between gap-2">


                {{-- Previous --}}
                <a href="{{ route('author.profile.step1') }}"
                   class="btn btn-outline-secondary">

                    <i class="bi bi-arrow-left me-1"></i>

                    Previous

                </a>


                <div class="d-flex gap-2">


                    {{-- Save --}}
                    <button type="submit"
                            name="action"
                            value="save"
                            class="btn btn-outline-primary">

                        <i class="bi bi-save me-1"></i>

                        Save

                    </button>


                    {{-- Save Continue --}}
                    <button type="submit"
                            name="action"
                            value="continue"
                            class="btn btn-primary px-4">

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
