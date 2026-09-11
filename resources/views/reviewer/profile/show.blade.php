@extends('reviewer.layouts.app')

@section('title', 'My Reviewer Profile | BMRC Journal')


@section('content')

<div class="page-header">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>

            <h1>
                My Reviewer Profile
            </h1>

            <p>
                Academic, professional and peer-review information registered with BMRC Journal.
            </p>

        </div>


        @if(
            $profile->isDraft()
            ||
            $profile->isUpdateRequested()
            ||
            $profile->isRejected()
        )

            <a
                href="{{ route('reviewer.application.edit') }}"
                class="btn btn-primary"
            >

                <i class="bi bi-pencil-square me-1"></i>

                Update Information

            </a>

        @endif

    </div>

</div>



{{-- ================================================================
    SUMMARY
================================================================ --}}

<div class="reviewer-card mb-4">

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-3">

                <small class="text-muted d-block">
                    Application ID
                </small>

                <strong>
                    {{ $profile->application_id ?: '-' }}
                </strong>

            </div>


            <div class="col-md-3">

                <small class="text-muted d-block">
                    Reviewer Code
                </small>

                <strong>
                    {{ $profile->reviewer_code ?: '-' }}
                </strong>

            </div>


            <div class="col-md-3">

                <small class="text-muted d-block">
                    Application Status
                </small>

                <strong>

                    {{
                        ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $profile->approval_status
                            )
                        )
                    }}

                </strong>

            </div>


            <div class="col-md-3">

                <small class="text-muted d-block">
                    Completion
                </small>

                <strong>
                    {{ $profile->profile_completion_percentage }}%
                </strong>

            </div>

        </div>

    </div>

</div>



{{-- PERSONAL --}}

<div class="reviewer-card mb-4">

    <div class="card-header fw-bold">
        Personal Information
    </div>

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-4">
                <small class="text-muted d-block">Name</small>
                {{ $profile->display_name ?: $reviewer->name }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Gender</small>
                {{ $profile->gender ?: '-' }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Nationality</small>
                {{ $profile->nationality ?: '-' }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Date of Birth</small>

                {{
                    $profile->date_of_birth
                    ? $profile->date_of_birth->format('d M Y')
                    : '-'
                }}

            </div>

        </div>

    </div>

</div>



{{-- CONTACT --}}

<div class="reviewer-card mb-4">

    <div class="card-header fw-bold">
        Contact Information
    </div>

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-4">
                <small class="text-muted d-block">Email</small>
                {{ $reviewer->email }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Alternative Email</small>
                {{ $profile->alternative_email ?: '-' }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Mobile</small>
                {{ $profile->mobile ?: '-' }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Alternative Mobile</small>
                {{ $profile->alternative_mobile ?: '-' }}
            </div>

            <div class="col-md-4">

                <small class="text-muted d-block">
                    Communication Preference
                </small>

                {{
                    match(
                        $profile->preferred_communication_method
                    ) {
                        'email' => 'Email',
                        'mobile' => 'Mobile',
                        'both' => 'Email and Mobile',
                        default => '-'
                    }
                }}

            </div>

        </div>

    </div>

</div>



{{-- LOCATION --}}

<div class="reviewer-card mb-4">

    <div class="card-header fw-bold">
        Location & Address
    </div>

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-4">
                <small class="text-muted d-block">Country</small>
                {{ $profile->country ?: '-' }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Division / State</small>
                {{ $profile->division_state ?: '-' }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">City / District</small>
                {{ $profile->city_district ?: '-' }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Postal Code</small>
                {{ $profile->postal_code ?: '-' }}
            </div>

            <div class="col-md-6">
                <small class="text-muted d-block">Postal Address</small>
                {{ $profile->postal_address ?: '-' }}
            </div>

            <div class="col-md-6">
                <small class="text-muted d-block">Office Address</small>
                {{ $profile->office_address ?: '-' }}
            </div>

        </div>

    </div>

</div>



{{-- PROFESSIONAL --}}

<div class="reviewer-card mb-4">

    <div class="card-header fw-bold">
        Professional Information
    </div>

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-4">
                <small class="text-muted d-block">Institution</small>
                {{ $profile->institution ?: '-' }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Department</small>
                {{ $profile->department ?: '-' }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Designation</small>
                {{ $profile->designation ?: '-' }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Organization Type</small>
                {{ $profile->organization_type ?: '-' }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Experience</small>
                {{ $profile->years_of_experience ?? '-' }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Registration No.</small>
                {{ $profile->professional_registration_no ?: '-' }}
            </div>

            <div class="col-12">
                <small class="text-muted d-block">Professional Experience</small>
                {!! nl2br(e($profile->professional_experience ?: '-')) !!}
            </div>

        </div>

    </div>

</div>



{{-- QUALIFICATIONS --}}

<div class="reviewer-card mb-4">

    <div class="card-header fw-bold">
        Qualifications
    </div>

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-4">
                <small class="text-muted d-block">Highest Degree</small>
                {{ $profile->highest_degree ?: '-' }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Institution</small>
                {{ $profile->highest_degree_institution ?: '-' }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Year</small>
                {{ $profile->year_of_highest_degree ?: '-' }}
            </div>

            <div class="col-md-6">
                <small class="text-muted d-block">Academic Qualifications</small>
                {!! nl2br(e($profile->academic_qualifications ?: '-')) !!}
            </div>

            <div class="col-md-6">
                <small class="text-muted d-block">Professional Qualifications</small>
                {!! nl2br(e($profile->professional_qualifications ?: '-')) !!}
            </div>

        </div>

    </div>

</div>



{{-- EXPERTISE --}}

<div class="reviewer-card mb-4">

    <div class="card-header fw-bold">
        Reviewer Expertise
    </div>

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-4">
                <small class="text-muted d-block">Speciality</small>
                {{ $profile->speciality ?: '-' }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Sub-speciality</small>
                {{ $profile->sub_speciality ?: '-' }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Primary Expertise</small>
                {{ $profile->primary_expertise ?: '-' }}
            </div>

            <div class="col-md-6">

                <small class="text-muted d-block">
                    Specialization
                </small>

                @forelse($profile->specializationList() as $item)

                    <span class="badge bg-primary-subtle text-primary border me-1 mb-1">
                        {{ $item }}
                    </span>

                @empty
                    -
                @endforelse

            </div>


            <div class="col-md-6">

                <small class="text-muted d-block">
                    Research Interests
                </small>

                @forelse($profile->researchInterestList() as $item)

                    <span class="badge bg-info-subtle text-dark border me-1 mb-1">
                        {{ $item }}
                    </span>

                @empty
                    -
                @endforelse

            </div>


            <div class="col-md-6">
                <small class="text-muted d-block">Areas of Expertise</small>
                {!! nl2br(e($profile->areas_of_expertise ?: '-')) !!}
            </div>


            <div class="col-md-6">
                <small class="text-muted d-block">Secondary Expertise</small>
                {!! nl2br(e($profile->secondary_expertise ?: '-')) !!}
            </div>


            <div class="col-md-6">
                <small class="text-muted d-block">Methodological Expertise</small>
                {!! nl2br(e($profile->methodological_expertise ?: '-')) !!}
            </div>


            <div class="col-md-6">

                <small class="text-muted d-block">
                    Review Keywords
                </small>

                @forelse($profile->expertiseKeywordList() as $item)

                    <span class="badge bg-secondary me-1 mb-1">
                        {{ $item }}
                    </span>

                @empty
                    -
                @endforelse

            </div>

        </div>

    </div>

</div>



{{-- RESEARCH --}}

<div class="reviewer-card mb-4">

    <div class="card-header fw-bold">
        Research & Publication Experience
    </div>

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-3">
                <small class="text-muted d-block">Publications</small>
                {{ $profile->publication_count ?? 0 }}
            </div>

            <div class="col-md-3">
                <small class="text-muted d-block">First Author</small>
                {{ $profile->first_author_publications ?? 0 }}
            </div>

            <div class="col-md-3">
                <small class="text-muted d-block">Corresponding Author</small>
                {{ $profile->corresponding_author_publications ?? 0 }}
            </div>

            <div class="col-md-3">
                <small class="text-muted d-block">External Reviews</small>
                {{ $profile->external_reviews_completed ?? 0 }}
            </div>

            <div class="col-12">
                <small class="text-muted d-block">Research Experience</small>
                {!! nl2br(e($profile->research_experience ?: '-')) !!}
            </div>

        </div>

    </div>

</div>



{{-- REVIEW EXPERIENCE --}}

<div class="reviewer-card mb-4">

    <div class="card-header fw-bold">
        Peer-review Experience
    </div>

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-6">
                <small class="text-muted d-block">Reviewing Experience</small>
                {!! nl2br(e($profile->reviewing_experience ?: '-')) !!}
            </div>

            <div class="col-md-6">
                <small class="text-muted d-block">Previous Journal Experience</small>
                {!! nl2br(e($profile->previous_journal_experience ?: '-')) !!}
            </div>

            <div class="col-12">
                <small class="text-muted d-block">Professional Memberships</small>
                {{ $profile->professional_memberships ?: '-' }}
            </div>

        </div>

    </div>

</div>



{{-- IDENTIFIERS --}}

<div class="reviewer-card mb-4">

    <div class="card-header fw-bold">
        Research Identifiers
    </div>

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-4">
                <small class="text-muted d-block">ORCID</small>
                {{ $profile->orcid ?: '-' }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Researcher ID</small>
                {{ $profile->researcher_id ?: '-' }}
            </div>

            <div class="col-md-4">
                <small class="text-muted d-block">Scopus Author ID</small>
                {{ $profile->scopus_author_id ?: '-' }}
            </div>

            <div class="col-md-6">
                <small class="text-muted d-block">Web of Science ID</small>
                {{ $profile->web_of_science_id ?: '-' }}
            </div>

            <div class="col-md-6">

                <small class="text-muted d-block">
                    Google Scholar
                </small>

                @if($profile->google_scholar_profile)

                    <a
                        href="{{ $profile->google_scholar_profile }}"
                        target="_blank"
                    >
                        View Google Scholar Profile
                    </a>

                @else
                    -
                @endif

            </div>

        </div>

    </div>

</div>



{{-- AVAILABILITY --}}

<div class="reviewer-card mb-4">

    <div class="card-header fw-bold">
        Reviewer Availability
    </div>

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-3">

                <small class="text-muted d-block">
                    Availability
                </small>

                @if($profile->available_for_review)

                    <span class="badge bg-success">
                        Available
                    </span>

                @else

                    <span class="badge bg-secondary">
                        Not Available
                    </span>

                @endif

            </div>


            <div class="col-md-3">

                <small class="text-muted d-block">
                    Maximum Active Reviews
                </small>

                {{ $profile->maximum_active_reviews }}

            </div>


            <div class="col-md-3">

                <small class="text-muted d-block">
                    Receive Invitations
                </small>

                {{ $profile->receive_review_invitations ? 'Yes' : 'No' }}

            </div>


            <div class="col-md-3">

                <small class="text-muted d-block">
                    Receive Reminders
                </small>

                {{ $profile->receive_reminders ? 'Yes' : 'No' }}

            </div>

        </div>

    </div>

</div>



{{-- DECLARATIONS --}}

<div class="reviewer-card mb-4">

    <div class="card-header fw-bold">
        Reviewer Declarations
    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-4">

                @if($profile->conflict_of_interest_declaration)

                    <span class="text-success">
                        <i class="bi bi-check-circle-fill"></i>
                        Conflict of Interest
                    </span>

                @else

                    <span class="text-danger">
                        Not Declared
                    </span>

                @endif

            </div>


            <div class="col-md-4">

                @if($profile->reviewer_ethics_declaration)

                    <span class="text-success">
                        <i class="bi bi-check-circle-fill"></i>
                        Reviewer Ethics
                    </span>

                @else

                    <span class="text-danger">
                        Not Declared
                    </span>

                @endif

            </div>


            <div class="col-md-4">

                @if($profile->confidentiality_declaration)

                    <span class="text-success">
                        <i class="bi bi-check-circle-fill"></i>
                        Confidentiality
                    </span>

                @else

                    <span class="text-danger">
                        Not Declared
                    </span>

                @endif

            </div>

        </div>

    </div>

</div>



{{-- CV --}}

@if($profile->cv_file)

    <div class="reviewer-card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Curriculum Vitae
            </strong>

            <a
                href="{{ Storage::url($profile->cv_file) }}"
                target="_blank"
                class="btn btn-sm btn-outline-primary"
            >
                Open PDF
            </a>

        </div>


    </div>

@endif

@endsection