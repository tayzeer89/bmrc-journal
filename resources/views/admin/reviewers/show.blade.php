@extends('admin.layouts.app')

@section('title', 'Reviewer Details')

@push('styles')

<style>
    .reviewer-profile-page {
        max-width: 1500px;
        margin: 0 auto;
    }

    .reviewer-header {
        background: linear-gradient(135deg, #173f5f, #256b92);
        color: #fff;
        border-radius: 14px;
        padding: 24px 28px;
        margin-bottom: 22px;
        box-shadow: 0 4px 18px rgba(0,0,0,.08);
    }

    .reviewer-header h4 {
        font-weight: 700;
        margin-bottom: 4px;
    }

    .reviewer-header p {
        margin: 0;
        opacity: .8;
        font-size: 13px;
    }

    .reviewer-avatar {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: rgba(255,255,255,.18);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
        flex: 0 0 58px;
    }

    .status-card {
        background: #fff;
        border: 1px solid #e5e9ef;
        border-radius: 12px;
        padding: 18px;
        height: 100%;
    }

    .status-label {
        text-transform: uppercase;
        font-size: 10px;
        letter-spacing: .07em;
        color: #8a94a3;
        font-weight: 700;
    }

    .status-value {
        margin-top: 5px;
        color: #173f5f;
        font-size: 15px;
        font-weight: 700;
    }

    .profile-card {
        border: 1px solid #e4e9ef;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,.03);
        margin-bottom: 22px;
    }

    .profile-card .card-header {
        background: #f8fafc;
        border-bottom: 1px solid #e4e9ef;
        padding: 14px 18px;
    }

    .section-title {
        font-size: 14px;
        font-weight: 700;
        color: #173f5f;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-item {
        margin-bottom: 15px;
    }

    .info-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        color: #8a94a3;
        margin-bottom: 3px;
    }

    .info-value {
        font-size: 13px;
        color: #263238;
        word-break: break-word;
    }

    .info-value.multiline {
        white-space: pre-line;
    }

    .empty-value {
        color: #a7afb8;
    }

    .tag {
        display: inline-block;
        padding: 4px 8px;
        border: 1px solid #d9e1e8;
        background: #f8fafc;
        border-radius: 20px;
        font-size: 11px;
        margin: 2px 3px 2px 0;
    }

    .attachment-box {
        border: 1px solid #dfe6ec;
        background: #f8fafc;
        border-radius: 10px;
        padding: 16px;
    }

    .pdf-preview {
        width: 100%;
        height: 650px;
        border: 1px solid #dfe6ec;
        border-radius: 8px;
        background: #fff;
    }

    .declaration-ok {
        color: #198754;
        font-weight: 600;
    }

    .declaration-no {
        color: #dc3545;
        font-weight: 600;
    }

    .progress {
        height: 8px;
    }
</style>

@endpush


@section('content')

@php
    $profile = $reviewer->profile;

    $profileStatus = $profile?->approval_status ?? 'draft';

    $profileStatusLabel = ucwords(
        str_replace('_', ' ', $profileStatus)
    );

    $accountStatusClass = match($reviewer->status) {
        'approved' => 'success',
        'pending' => 'warning',
        'rejected' => 'danger',
        'suspended' => 'dark',
        default => 'secondary',
    };

    $profileStatusClass = match($profileStatus) {
        'approved' => 'success',
        'pending_approval' => 'info',
        'update_requested' => 'warning',
        'rejected' => 'danger',
        default => 'secondary',
    };
@endphp


<div class="container-fluid reviewer-profile-page">


    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="reviewer-header">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div class="d-flex align-items-center gap-3">

                <div class="reviewer-avatar">
                    <i class="bi bi-person-badge"></i>
                </div>

                <div>

                    <h4>
                        {{ $reviewer->name }}
                    </h4>

                    <p>
                        {{ $reviewer->email }}
                    </p>

                    @if($profile?->reviewer_code)
                        <small>
                            Reviewer Code:
                            {{ $profile->reviewer_code }}
                        </small>
                    @endif

                </div>

            </div>


            <div class="d-flex gap-2 flex-wrap">

                <a
                    href="{{ route('admin.reviewers.index') }}"
                    class="btn btn-light"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Back
                </a>


                @can('reviewer.edit')

                    <a
                        href="{{ route('admin.reviewers.edit', $reviewer) }}"
                        class="btn btn-warning"
                    >
                        <i class="bi bi-pencil-square me-1"></i>
                        Edit Reviewer
                    </a>

                @endcan

            </div>

        </div>

    </div>



    {{-- =========================================================
        SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-lg-3 col-md-6">

            <div class="status-card">

                <div class="status-label">
                    Account Status
                </div>

                <div class="status-value">

                    <span class="badge bg-{{ $accountStatusClass }}">
                        {{ ucfirst($reviewer->status) }}
                    </span>

                </div>

            </div>

        </div>


        <div class="col-lg-3 col-md-6">

            <div class="status-card">

                <div class="status-label">
                    Profile Status
                </div>

                <div class="status-value">

                    <span class="badge bg-{{ $profileStatusClass }}">
                        {{ $profileStatusLabel }}
                    </span>

                </div>

            </div>

        </div>


        <div class="col-lg-3 col-md-6">

            <div class="status-card">

                <div class="status-label">
                    Profile Completion
                </div>

                <div class="status-value">

                    {{ $profile?->profile_completion_percentage ?? 0 }}%

                </div>

                <div class="progress mt-2">

                    <div
                        class="progress-bar"
                        style="width: {{ $profile?->profile_completion_percentage ?? 0 }}%"
                    ></div>

                </div>

            </div>

        </div>


        <div class="col-lg-3 col-md-6">

            <div class="status-card">

                <div class="status-label">
                    Reviewer Availability
                </div>

                <div class="status-value">

                    @if($profile?->available_for_review)

                        <span class="badge bg-success">
                            Available
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            Not Available
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        ACCOUNT INFORMATION
    ========================================================== --}}

    <div class="card profile-card">

        <div class="card-header">

            <div class="section-title">

                <i class="bi bi-shield-lock"></i>

                Account Information

            </div>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Full Name
                    </div>

                    <div class="info-value">
                        {{ $reviewer->name }}
                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Login Email
                    </div>

                    <div class="info-value">
                        {{ $reviewer->email }}
                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Email Verified
                    </div>

                    <div class="info-value">

                        @if($reviewer->email_verified_at)

                            <span class="text-success">
                                <i class="bi bi-check-circle"></i>
                                Yes
                            </span>

                        @else

                            <span class="text-muted">
                                No
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Created Source
                    </div>

                    <div class="info-value">

                        {{
                            ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $reviewer->created_source ?? 'N/A'
                                )
                            )
                        }}

                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Account Created
                    </div>

                    <div class="info-value">

                        {{ $reviewer->created_at?->format('d M Y, h:i A') ?? '—' }}

                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Last Login
                    </div>

                    <div class="info-value">

                        {{ $reviewer->last_login_at?->format('d M Y, h:i A') ?? 'Never' }}

                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Password Changed
                    </div>

                    <div class="info-value">

                        {{ $reviewer->password_changed_at?->format('d M Y, h:i A') ?? '—' }}

                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Activated
                    </div>

                    <div class="info-value">

                        {{ $reviewer->activated_at?->format('d M Y, h:i A') ?? '—' }}

                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Temporary Password Change Required
                    </div>

                    <div class="info-value">

                        {{ $reviewer->must_change_password ? 'Yes' : 'No' }}

                    </div>

                </div>

            </div>

        </div>

    </div>



    @if($profile)


    {{-- =========================================================
        PROFILE IDENTIFICATION
    ========================================================== --}}

    <div class="card profile-card">

        <div class="card-header">

            <div class="section-title">
                <i class="bi bi-person-vcard"></i>
                Reviewer Profile Identification
            </div>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Application ID
                    </div>

                    <div class="info-value">
                        {{ $profile->application_id ?: '—' }}
                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Reviewer Code
                    </div>

                    <div class="info-value">
                        {{ $profile->reviewer_code ?: '—' }}
                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Profile Completed
                    </div>

                    <div class="info-value">

                        @if($profile->profile_completed)

                            <span class="badge bg-success">
                                Completed
                            </span>

                        @else

                            <span class="badge bg-warning text-dark">
                                Incomplete
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        PERSONAL INFORMATION
    ========================================================== --}}

    <div class="card profile-card">

        <div class="card-header">
            <div class="section-title">
                <i class="bi bi-person"></i>
                Personal Information
            </div>
        </div>

        <div class="card-body">

            <div class="row">

                @foreach([
                    'Title' => $profile->title,
                    'First Name' => $profile->first_name,
                    'Middle Name' => $profile->middle_name,
                    'Last Name' => $profile->last_name,
                    'Display Name' => $profile->display_name,
                    'Gender' => $profile->gender,
                    'Date of Birth' => $profile->date_of_birth?->format('d M Y'),
                    'Nationality' => $profile->nationality,
                ] as $label => $value)

                    <div class="col-lg-3 col-md-4 col-sm-6 info-item">

                        <div class="info-label">
                            {{ $label }}
                        </div>

                        <div class="info-value">
                            {{ filled($value) ? $value : '—' }}
                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>



    {{-- =========================================================
        CONTACT & LOCATION
    ========================================================== --}}

    <div class="card profile-card">

        <div class="card-header">

            <div class="section-title">
                <i class="bi bi-geo-alt"></i>
                Contact & Location
            </div>

        </div>

        <div class="card-body">

            <div class="row">

                @foreach([
                    'Alternative Email' => $profile->alternative_email,
                    'Mobile' => $profile->mobile,
                    'Alternative Mobile' => $profile->alternative_mobile,
                    'Country' => $profile->country,
                    'Division / State' => $profile->division_state,
                    'City / District' => $profile->city_district,
                    'Postal Code' => $profile->postal_code,
                ] as $label => $value)

                    <div class="col-lg-4 col-md-6 info-item">

                        <div class="info-label">
                            {{ $label }}
                        </div>

                        <div class="info-value">
                            {{ filled($value) ? $value : '—' }}
                        </div>

                    </div>

                @endforeach


                <div class="col-md-6 info-item">

                    <div class="info-label">
                        Postal Address
                    </div>

                    <div class="info-value multiline">
                        {{ $profile->postal_address ?: '—' }}
                    </div>

                </div>


                <div class="col-md-6 info-item">

                    <div class="info-label">
                        Office Address
                    </div>

                    <div class="info-value multiline">
                        {{ $profile->office_address ?: '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        PROFESSIONAL INFORMATION
    ========================================================== --}}

    <div class="card profile-card">

        <div class="card-header">

            <div class="section-title">
                <i class="bi bi-building"></i>
                Professional Information
            </div>

        </div>

        <div class="card-body">

            <div class="row">

                @foreach([
                    'Institution / Organization' => $profile->institution,
                    'Department' => $profile->department,
                    'Designation' => $profile->designation,
                    'Organization Type' => $profile->organization_type,
                    'Years of Experience' => $profile->years_of_experience,
                    'Professional Registration No.' => $profile->professional_registration_no,
                ] as $label => $value)

                    <div class="col-lg-4 col-md-6 info-item">

                        <div class="info-label">
                            {{ $label }}
                        </div>

                        <div class="info-value">
                            {{ filled($value) ? $value : '—' }}
                        </div>

                    </div>

                @endforeach


                <div class="col-12 info-item">

                    <div class="info-label">
                        Professional Experience
                    </div>

                    <div class="info-value multiline">
                        {{ $profile->professional_experience ?: '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        QUALIFICATIONS
    ========================================================== --}}

    <div class="card profile-card">

        <div class="card-header">

            <div class="section-title">
                <i class="bi bi-mortarboard"></i>
                Academic & Professional Qualifications
            </div>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Highest Degree
                    </div>

                    <div class="info-value">
                        {{ $profile->highest_degree ?: '—' }}
                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Highest Degree Institution
                    </div>

                    <div class="info-value">
                        {{ $profile->highest_degree_institution ?: '—' }}
                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Year of Highest Degree
                    </div>

                    <div class="info-value">
                        {{ $profile->year_of_highest_degree ?: '—' }}
                    </div>

                </div>


                <div class="col-md-6 info-item">

                    <div class="info-label">
                        Academic Qualifications
                    </div>

                    <div class="info-value multiline">
                        {{ $profile->academic_qualifications ?: '—' }}
                    </div>

                </div>


                <div class="col-md-6 info-item">

                    <div class="info-label">
                        Professional Qualifications
                    </div>

                    <div class="info-value multiline">
                        {{ $profile->professional_qualifications ?: '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        SPECIALIZATION & EXPERTISE
    ========================================================== --}}

    <div class="card profile-card">

        <div class="card-header">

            <div class="section-title">
                <i class="bi bi-bullseye"></i>
                Specialization & Reviewer Expertise
            </div>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Speciality
                    </div>

                    <div class="info-value">
                        {{ $profile->speciality ?: '—' }}
                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Sub-speciality
                    </div>

                    <div class="info-value">
                        {{ $profile->sub_speciality ?: '—' }}
                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Primary Expertise
                    </div>

                    <div class="info-value">
                        {{ $profile->primary_expertise ?: '—' }}
                    </div>

                </div>


                <div class="col-md-6 info-item">

                    <div class="info-label">
                        Specialization
                    </div>

                    <div>

                        @forelse($profile->specializationList() as $item)

                            <span class="tag">
                                {{ $item }}
                            </span>

                        @empty

                            <span class="empty-value">
                                —
                            </span>

                        @endforelse

                    </div>

                </div>


                <div class="col-md-6 info-item">

                    <div class="info-label">
                        Research Interests
                    </div>

                    <div>

                        @forelse($profile->researchInterestList() as $item)

                            <span class="tag">
                                {{ $item }}
                            </span>

                        @empty

                            <span class="empty-value">
                                —
                            </span>

                        @endforelse

                    </div>

                </div>


                <div class="col-md-6 info-item">

                    <div class="info-label">
                        Areas of Expertise
                    </div>

                    <div class="info-value multiline">
                        {{ $profile->areas_of_expertise ?: '—' }}
                    </div>

                </div>


                <div class="col-md-6 info-item">

                    <div class="info-label">
                        Secondary Expertise
                    </div>

                    <div class="info-value multiline">
                        {{ $profile->secondary_expertise ?: '—' }}
                    </div>

                </div>


                <div class="col-md-6 info-item">

                    <div class="info-label">
                        Methodological Expertise
                    </div>

                    <div class="info-value multiline">
                        {{ $profile->methodological_expertise ?: '—' }}
                    </div>

                </div>


                <div class="col-md-6 info-item">

                    <div class="info-label">
                        Expertise / Review Keywords
                    </div>

                    <div>

                        @forelse($profile->expertiseKeywordList() as $item)

                            <span class="tag">
                                {{ $item }}
                            </span>

                        @empty

                            <span class="empty-value">
                                —
                            </span>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        RESEARCH & PUBLICATIONS
    ========================================================== --}}

    <div class="card profile-card">

        <div class="card-header">

            <div class="section-title">
                <i class="bi bi-journal-richtext"></i>
                Research & Publication Experience
            </div>

        </div>

        <div class="card-body">

            <div class="row">

                @foreach([
                    'Total Publications' => $profile->publication_count,
                    'First-author Publications' => $profile->first_author_publications,
                    'Corresponding-author Publications' => $profile->corresponding_author_publications,
                ] as $label => $value)

                    <div class="col-md-4 info-item">

                        <div class="info-label">
                            {{ $label }}
                        </div>

                        <div class="info-value">
                            {{ $value ?? 0 }}
                        </div>

                    </div>

                @endforeach


                <div class="col-12 info-item">

                    <div class="info-label">
                        Research Experience
                    </div>

                    <div class="info-value multiline">
                        {{ $profile->research_experience ?: '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        RESEARCH IDENTIFIERS
    ========================================================== --}}

    <div class="card profile-card">

        <div class="card-header">

            <div class="section-title">
                <i class="bi bi-person-badge"></i>
                Research Identifiers
            </div>

        </div>

        <div class="card-body">

            <div class="row">

                @foreach([
                    'ORCID' => $profile->orcid,
                    'Researcher ID' => $profile->researcher_id,
                    'Scopus Author ID' => $profile->scopus_author_id,
                    'Web of Science ID' => $profile->web_of_science_id,
                ] as $label => $value)

                    <div class="col-md-3 info-item">

                        <div class="info-label">
                            {{ $label }}
                        </div>

                        <div class="info-value">
                            {{ $value ?: '—' }}
                        </div>

                    </div>

                @endforeach


                <div class="col-md-6 info-item">

                    <div class="info-label">
                        Google Scholar Profile
                    </div>

                    <div class="info-value">

                        @if($profile->google_scholar_profile)

                            <a
                                href="{{ $profile->google_scholar_profile }}"
                                target="_blank"
                                rel="noopener"
                            >
                                <i class="bi bi-box-arrow-up-right me-1"></i>

                                Open Google Scholar
                            </a>

                        @else

                            —

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        REVIEWING EXPERIENCE
    ========================================================== --}}

    <div class="card profile-card">

        <div class="card-header">

            <div class="section-title">
                <i class="bi bi-clipboard-check"></i>
                Reviewing Experience
            </div>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 info-item">

                    <div class="info-label">
                        Reviewing Experience
                    </div>

                    <div class="info-value multiline">
                        {{ $profile->reviewing_experience ?: '—' }}
                    </div>

                </div>


                <div class="col-md-6 info-item">

                    <div class="info-label">
                        Previous Journal Experience
                    </div>

                    <div class="info-value multiline">
                        {{ $profile->previous_journal_experience ?: '—' }}
                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        External Reviews Completed
                    </div>

                    <div class="info-value">
                        {{ $profile->external_reviews_completed ?? 0 }}
                    </div>

                </div>


                <div class="col-md-8 info-item">

                    <div class="info-label">
                        Professional Memberships
                    </div>

                    <div class="info-value multiline">
                        {{ $profile->professional_memberships ?: '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        AVAILABILITY & COMMUNICATION
    ========================================================== --}}

    <div class="card profile-card">

        <div class="card-header">

            <div class="section-title">
                <i class="bi bi-calendar-check"></i>
                Availability & Communication
            </div>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 info-item">

                    <div class="info-label">
                        Available For Review
                    </div>

                    <div class="info-value">

                        {{ $profile->available_for_review ? 'Yes' : 'No' }}

                    </div>

                </div>


                <div class="col-md-3 info-item">

                    <div class="info-label">
                        Maximum Active Reviews
                    </div>

                    <div class="info-value">
                        {{ $profile->maximum_active_reviews }}
                    </div>

                </div>


                <div class="col-md-3 info-item">

                    <div class="info-label">
                        Unavailable From
                    </div>

                    <div class="info-value">
                        {{ $profile->unavailable_from?->format('d M Y') ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3 info-item">

                    <div class="info-label">
                        Unavailable Until
                    </div>

                    <div class="info-value">
                        {{ $profile->unavailable_until?->format('d M Y') ?? '—' }}
                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Preferred Communication
                    </div>

                    <div class="info-value">

                        {{
                            match($profile->preferred_communication_method) {
                                'email' => 'Email',
                                'mobile' => 'Mobile',
                                'both' => 'Email & Mobile',
                                default => '—'
                            }
                        }}

                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Receive Review Invitations
                    </div>

                    <div class="info-value">
                        {{ $profile->receive_review_invitations ? 'Yes' : 'No' }}
                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Receive Reminders
                    </div>

                    <div class="info-value">
                        {{ $profile->receive_reminders ? 'Yes' : 'No' }}
                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        DECLARATIONS
    ========================================================== --}}

    <div class="card profile-card">

        <div class="card-header">

            <div class="section-title">
                <i class="bi bi-shield-check"></i>
                Reviewer Declarations
            </div>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Conflict of Interest Declaration
                    </div>

                    <div class="info-value">

                        @if($profile->conflict_of_interest_declaration)

                            <span class="declaration-ok">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                Declared
                            </span>

                        @else

                            <span class="declaration-no">
                                <i class="bi bi-x-circle-fill me-1"></i>
                                Not Declared
                            </span>

                        @endif

                    </div>

                    <small class="text-muted">

                        {{
                            $profile->conflict_of_interest_declared_at
                            ? $profile->conflict_of_interest_declared_at->format('d M Y, h:i A')
                            : ''
                        }}

                    </small>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Reviewer Ethics Declaration
                    </div>

                    <div class="info-value">

                        @if($profile->reviewer_ethics_declaration)

                            <span class="declaration-ok">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                Declared
                            </span>

                        @else

                            <span class="declaration-no">
                                <i class="bi bi-x-circle-fill me-1"></i>
                                Not Declared
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Confidentiality Declaration
                    </div>

                    <div class="info-value">

                        @if($profile->confidentiality_declaration)

                            <span class="declaration-ok">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                Declared
                            </span>

                        @else

                            <span class="declaration-no">
                                <i class="bi bi-x-circle-fill me-1"></i>
                                Not Declared
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        ATTACHMENT
    ========================================================== --}}

    <div class="card profile-card">

        <div class="card-header">

            <div class="section-title">
                <i class="bi bi-paperclip"></i>
                Attachment Information
            </div>

        </div>

        <div class="card-body">

            @if($profile->cv_file)

                @php
                    $cvExists = Storage::disk('public')->exists($profile->cv_file);

                    $cvSize = $cvExists
                        ? Storage::disk('public')->size($profile->cv_file)
                        : null;

                    $cvModified = $cvExists
                        ? Storage::disk('public')->lastModified($profile->cv_file)
                        : null;

                    $cvName = basename($profile->cv_file);
                @endphp


                <div class="attachment-box mb-4">

                    <div class="row align-items-center g-3">

                        <div class="col-lg-1 col-md-2 text-center">

                            <i
                                class="bi bi-file-earmark-pdf text-danger"
                                style="font-size:42px;"
                            ></i>

                        </div>


                        <div class="col-lg-7 col-md-6">

                            <div class="fw-bold">
                                Curriculum Vitae
                            </div>

                            <div class="small text-muted mt-1">

                                File:
                                {{ $cvName }}

                            </div>

                            <div class="small text-muted">

                                Type:
                                PDF Document

                            </div>


                            @if($cvSize)

                                <div class="small text-muted">

                                    Size:
                                    {{ number_format($cvSize / 1024, 2) }}
                                    KB

                                </div>

                            @endif


                            @if($cvModified)

                                <div class="small text-muted">

                                    Last Modified:

                                    {{
                                        \Carbon\Carbon::createFromTimestamp($cvModified)
                                            ->format('d M Y, h:i A')
                                    }}

                                </div>

                            @endif

                        </div>


                        <div class="col-lg-4 col-md-4 text-md-end">

                            @if($cvExists)

                                <a
                                    href="{{ Storage::url($profile->cv_file) }}"
                                    target="_blank"
                                    class="btn btn-outline-primary"
                                >
                                    <i class="bi bi-eye me-1"></i>
                                    View CV
                                </a>

                                <a
                                    href="{{ Storage::url($profile->cv_file) }}"
                                    download
                                    class="btn btn-outline-success"
                                >
                                    <i class="bi bi-download me-1"></i>
                                    Download
                                </a>

                            @else

                                <span class="badge bg-danger">
                                    File Missing
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            @else

                <div class="alert alert-warning mb-0">

                    <i class="bi bi-exclamation-circle me-1"></i>

                    No CV attachment has been uploaded by this reviewer.

                </div>

            @endif

        </div>

    </div>



    {{-- =========================================================
        APPROVAL & AUDIT
    ========================================================== --}}

    <div class="card profile-card">

        <div class="card-header">

            <div class="section-title">
                <i class="bi bi-clock-history"></i>
                Approval & Audit Information
            </div>

        </div>

        <div class="card-body">

            <div class="row">

                @foreach([
                    'Submitted for Approval' =>
                        $profile->submitted_for_approval_at?->format('d M Y, h:i A'),

                    'Approved At' =>
                        $profile->approved_at?->format('d M Y, h:i A'),

                    'Rejected At' =>
                        $profile->rejected_at?->format('d M Y, h:i A'),

                    'Update Requested At' =>
                        $profile->update_requested_at?->format('d M Y, h:i A'),

                    'Profile Completed At' =>
                        $profile->profile_completed_at?->format('d M Y, h:i A'),

                    'Last Profile Updated' =>
                        $profile->last_profile_updated_at?->format('d M Y, h:i A'),
                ] as $label => $value)

                    <div class="col-lg-4 col-md-6 info-item">

                        <div class="info-label">
                            {{ $label }}
                        </div>

                        <div class="info-value">
                            {{ $value ?: '—' }}
                        </div>

                    </div>

                @endforeach


                <div class="col-md-6 info-item">

                    <div class="info-label">
                        Approval Note
                    </div>

                    <div class="info-value multiline">
                        {{ $profile->approval_note ?: '—' }}
                    </div>

                </div>


                <div class="col-md-6 info-item">

                    <div class="info-label">
                        Rejection Reason
                    </div>

                    <div class="info-value multiline">
                        {{ $profile->rejection_reason ?: '—' }}
                    </div>

                </div>


                <div class="col-md-6 info-item">

                    <div class="info-label">
                        Profile Update Request
                    </div>

                    <div class="info-value multiline">
                        {{ $profile->profile_update_request ?: '—' }}
                    </div>

                </div>


                <div class="col-md-6 info-item">

                    <div class="info-label">
                        Internal Note
                    </div>

                    <div class="info-value multiline">
                        {{ $profile->internal_note ?: '—' }}
                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Requires Reverification
                    </div>

                    <div class="info-value">

                        {{ $profile->requires_reverification ? 'Yes' : 'No' }}

                    </div>

                </div>


                <div class="col-md-4 info-item">

                    <div class="info-label">
                        Approved By
                    </div>

                    <div class="info-value">

                        {{ $profile->approvedBy?->name ?? '—' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    @else

        <div class="alert alert-warning">

            <i class="bi bi-exclamation-triangle me-1"></i>

            Reviewer profile has not been created yet.

        </div>

    @endif



    {{-- =========================================================
        ADMINISTRATIVE ACTIONS
    ========================================================== --}}

    <div class="card profile-card">

        <div class="card-header">

            <div class="section-title">
                <i class="bi bi-sliders"></i>
                Administrative Actions
            </div>

        </div>


        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">


                @can('reviewer.approve')

                    @if(
                        $reviewer->status === 'pending'
                        &&
                        $profile?->profile_completed
                        &&
                        $profile?->approval_status === 'pending_approval'
                    )

                        <form
                            method="POST"
                            action="{{ route('admin.reviewers.approve', $reviewer) }}"
                        >

                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="btn btn-success"
                                onclick="return confirm('Approve this reviewer?')"
                            >

                                <i class="bi bi-check-circle me-1"></i>

                                Approve Reviewer

                            </button>

                        </form>

                    @endif

                @endcan



                @can('reviewer.request_update')

                    @if(
                        $profile
                        &&
                        $profile->approval_status === 'pending_approval'
                    )

                        <button
                            type="button"
                            class="btn btn-warning"
                            data-bs-toggle="modal"
                            data-bs-target="#requestUpdateModal"
                        >

                            <i class="bi bi-arrow-repeat me-1"></i>

                            Request Update

                        </button>

                    @endif

                @endcan



                @can('reviewer.reject')

                    @if(
                        $profile
                        &&
                        $profile->approval_status === 'pending_approval'
                    )

                        <button
                            type="button"
                            class="btn btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectReviewerModal"
                        >

                            <i class="bi bi-x-circle me-1"></i>

                            Reject

                        </button>

                    @endif

                @endcan



                @can('reviewer.suspend')

                    @if($reviewer->status === 'approved')

                        <form
                            method="POST"
                            action="{{ route('admin.reviewers.suspend', $reviewer) }}"
                        >

                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="btn btn-dark"
                                onclick="return confirm('Suspend this reviewer account?')"
                            >

                                <i class="bi bi-pause-circle me-1"></i>

                                Suspend

                            </button>

                        </form>

                    @endif

                @endcan



                @can('reviewer.activate')

                    @if($reviewer->status === 'suspended')

                        <form
                            method="POST"
                            action="{{ route('admin.reviewers.activate', $reviewer) }}"
                        >

                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="btn btn-success"
                                onclick="return confirm('Activate this reviewer account?')"
                            >

                                <i class="bi bi-play-circle me-1"></i>

                                Activate

                            </button>

                        </form>

                    @endif

                @endcan

            </div>

        </div>

    </div>


</div>



{{-- ===============================================================
    REQUEST UPDATE MODAL
================================================================ --}}

@can('reviewer.request_update')

<div
    class="modal fade"
    id="requestUpdateModal"
    tabindex="-1"
>

    <div class="modal-dialog">

        <form
            method="POST"
            action="{{ route('admin.reviewers.request-update', $reviewer) }}"
            class="modal-content"
        >

            @csrf
            @method('PATCH')


            <div class="modal-header">

                <h5 class="modal-title">
                    Request Profile Update
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <label class="form-label">
                    Required Correction / Update
                </label>

                <textarea
                    name="profile_update_request"
                    rows="5"
                    class="form-control"
                    required
                ></textarea>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="btn btn-warning"
                >
                    Send Update Request
                </button>

            </div>

        </form>

    </div>

</div>

@endcan



{{-- ===============================================================
    REJECT MODAL
================================================================ --}}

@can('reviewer.reject')

<div
    class="modal fade"
    id="rejectReviewerModal"
    tabindex="-1"
>

    <div class="modal-dialog">

        <form
            method="POST"
            action="{{ route('admin.reviewers.reject', $reviewer) }}"
            class="modal-content"
        >

            @csrf
            @method('PATCH')


            <div class="modal-header">

                <h5 class="modal-title">
                    Reject Reviewer Application
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <label class="form-label">
                    Rejection Reason
                </label>

                <textarea
                    name="rejection_reason"
                    rows="5"
                    class="form-control"
                    required
                ></textarea>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="btn btn-danger"
                >
                    Reject Application
                </button>

            </div>

        </form>

    </div>

</div>

@endcan

@endsection