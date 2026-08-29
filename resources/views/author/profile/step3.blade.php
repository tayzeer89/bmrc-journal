@extends('author.layouts.app')

@section('title', 'Author Profile - Professional Information')

@section('content')

<div class="container-fluid py-4">

{{-- Page Header --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Author Profile
        </h2>

        <p class="text-muted mb-0">
            Complete your professional information
        </p>
    </div>

    <span class="badge bg-primary px-3 py-2">
        Step 3 of 3
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

        <strong>
            Please correct the following errors:
        </strong>

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


{{-- =========================
     PROFESSIONAL INFORMATION
========================== --}}

<form method="POST"
      action="{{ route('author.profile.step3.update') }}">

    @csrf
    @method('PATCH')


    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="bi bi-briefcase text-primary me-2"></i>

                Professional Information

            </h5>

            <small class="text-muted">
                Provide your current professional and academic information.
            </small>

        </div>


        <div class="card-body p-4">

            <div class="row g-3">


                {{-- Institution --}}
                <div class="col-md-12">

                    <label class="form-label fw-semibold">

                        Institution / Organization

                        <span class="text-danger">*</span>

                    </label>

                    <input type="text"
                           name="institution"
                           class="form-control"
                           value="{{ old('institution', $authorProfile->institution ?? '') }}"
                           placeholder="Current institution / organization"
                           required>

                </div>


                {{-- Department --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Department

                        <span class="text-danger">*</span>

                    </label>

                    <input type="text"
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

                    <input type="text"
                           name="designation"
                           class="form-control"
                           value="{{ old('designation', $authorProfile->designation ?? '') }}"
                           placeholder="Current designation"
                           required>

                </div>


                {{-- Academic Degree --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Academic Degree
                    </label>

                    <input type="text"
                           name="academic_degree"
                           class="form-control"
                           value="{{ old('academic_degree', $authorProfile->academic_degree ?? '') }}"
                           placeholder="e.g. MBBS, MPH, PhD, MD">

                </div>


                {{-- Specialization --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Specialization
                    </label>

                    <input type="text"
                           name="specialization"
                           class="form-control"
                           value="{{ old('specialization', $authorProfile->specialization ?? '') }}"
                           placeholder="Area of specialization">

                </div>


                {{-- Registration --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Professional Registration No.
                    </label>

                    <input type="text"
                           name="professional_registration_no"
                           class="form-control"
                           value="{{ old('professional_registration_no', $authorProfile->professional_registration_no ?? '') }}"
                           placeholder="Professional registration number">

                </div>


                {{-- Research Interest --}}
                <div class="col-md-12">

                    <label class="form-label fw-semibold">
                        Research Interest
                    </label>

                    <textarea name="research_interest"
                              rows="4"
                              class="form-control"
                              placeholder="Describe your major research interests">{{ old('research_interest', $authorProfile->research_interest ?? '') }}</textarea>

                    <div class="form-text">
                        You may enter multiple research areas separated by commas.
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================
         RESEARCH IDENTIFIERS
    ========================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="bi bi-bar-chart-line text-primary me-2"></i>

                Researcher Profiles & Identifiers

            </h5>

            <small class="text-muted">
                These identifiers help BMRC correctly identify your publications.
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

                    <input type="text"
                           name="orcid"
                           class="form-control"
                           value="{{ old('orcid', $authorProfile->orcid ?? '') }}"
                           placeholder="0000-0000-0000-0000">

                    <div class="form-text">
                        Example: 0000-0002-1825-0097
                    </div>

                </div>


                {{-- Researcher ID --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Researcher ID
                    </label>

                    <input type="text"
                           name="researcher_id"
                           class="form-control"
                           value="{{ old('researcher_id', $authorProfile->researcher_id ?? '') }}"
                           placeholder="Researcher ID">

                </div>


                {{-- Scopus --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Scopus Author ID
                    </label>

                    <input type="text"
                           name="scopus_author_id"
                           class="form-control"
                           value="{{ old('scopus_author_id', $authorProfile->scopus_author_id ?? '') }}"
                           placeholder="Scopus Author ID">

                </div>


                {{-- Web of Science --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Web of Science Researcher ID
                    </label>

                    <input type="text"
                           name="web_of_science_id"
                           class="form-control"
                           value="{{ old('web_of_science_id', $authorProfile->web_of_science_id ?? '') }}"
                           placeholder="Web of Science Researcher ID">

                </div>


                {{-- Google Scholar --}}
                <div class="col-md-12">

                    <label class="form-label fw-semibold">
                        Google Scholar Profile
                    </label>

                    <input type="url"
                           name="google_scholar_profile"
                           class="form-control"
                           value="{{ old('google_scholar_profile', $authorProfile->google_scholar_profile ?? '') }}"
                           placeholder="https://scholar.google.com/...">

                </div>

            </div>

        </div>

    </div>


    {{-- =========================
         COMMUNICATION
    ========================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="bi bi-chat-dots text-primary me-2"></i>

                Communication Preferences

            </h5>

        </div>


        <div class="card-body p-4">

            <div class="row g-3">


                {{-- Preferred Communication --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Preferred Communication Method
                    </label>

                    <select name="preferred_communication_method"
                            class="form-select">

                        <option value="">
                            Select Method
                        </option>

                        <option value="Email"
                            {{ old('preferred_communication_method', $authorProfile->preferred_communication_method ?? '') == 'Email' ? 'selected' : '' }}>
                            Email
                        </option>

                        <option value="Mobile"
                            {{ old('preferred_communication_method', $authorProfile->preferred_communication_method ?? '') == 'Mobile' ? 'selected' : '' }}>
                            Mobile
                        </option>

                        <option value="Both"
                            {{ old('preferred_communication_method', $authorProfile->preferred_communication_method ?? '') == 'Both' ? 'selected' : '' }}>
                            Both
                        </option>

                    </select>

                </div>


                {{-- Editorial Communication --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Editorial Communication
                    </label>

                    <div class="form-check form-switch mt-2">

                        <input class="form-check-input"
                               type="checkbox"
                               name="available_for_editorial_communication"
                               value="1"
                               id="editorialCommunication"

                               {{ old(
                                    'available_for_editorial_communication',
                                    $authorProfile->available_for_editorial_communication ?? true
                               ) ? 'checked' : '' }}>

                        <label class="form-check-label"
                               for="editorialCommunication">

                            Available for editorial communication

                        </label>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================
         PROFILE SUMMARY
    ========================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="bi bi-person-check text-success me-2"></i>

                Profile Summary

            </h5>

        </div>


        <div class="card-body p-4">

            <div class="row g-3">


                <div class="col-md-6">

                    <div class="text-muted small">
                        Author ID
                    </div>

                    <div class="fw-semibold">
                        {{ $authorProfile->author_id ?? '-' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Author Name
                    </div>

                    <div class="fw-semibold">

                        {{ $authorProfile->display_name
                            ?? auth()->user()->name }}

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Institution
                    </div>

                    <div class="fw-semibold">
                        {{ $authorProfile->institution ?? '-' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Designation
                    </div>

                    <div class="fw-semibold">
                        {{ $authorProfile->designation ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================
         DECLARATION
    ========================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="bi bi-shield-check text-primary me-2"></i>

                Profile Declaration

            </h5>

        </div>


        <div class="card-body p-4">

            <div class="form-check">

                <input class="form-check-input"
                       type="checkbox"
                       name="profile_declaration"
                       value="1"
                       id="profileDeclaration"
                       required>

                <label class="form-check-label"
                       for="profileDeclaration">

                    I confirm that the information provided in my
                    BMRC Author Profile is accurate and up to date.

                    <span class="text-danger">*</span>

                </label>

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
                <a href="{{ route('author.profile.step2') }}"
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


                    {{-- Complete --}}
                    <button type="submit"
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
