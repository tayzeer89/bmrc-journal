@extends('layouts.app')

@section(
    'title',
    $reviewer->profile?->display_name ?: $reviewer->name
)

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | Reviewer Profile
    |--------------------------------------------------------------------------
    */

    $profile = $reviewer->profile;


    /*
    |--------------------------------------------------------------------------
    | Base Display Name
    |--------------------------------------------------------------------------
    */

    $displayName = trim(
        $profile?->display_name
        ?: $reviewer->name
    );

    $title = trim(
        $profile?->title ?? ''
    );


    /*
    |--------------------------------------------------------------------------
    | Normalize Name
    |--------------------------------------------------------------------------
    */

    $displayName = preg_replace(
        '/\s+/',
        ' ',
        $displayName
    );


    /*
    |--------------------------------------------------------------------------
    | Remove Common Duplicate Titles
    |--------------------------------------------------------------------------
    */

    $displayName = preg_replace(
        '/^(?:Prof\.\s*Dr\.\s*){2,}/i',
        'Prof. Dr. ',
        $displayName
    );

    $displayName = preg_replace(
        '/^(?:Professor\s+Dr\.?\s*){2,}/i',
        'Professor Dr. ',
        $displayName
    );

    $displayName = preg_replace(
        '/^(?:Dr\.\s*){2,}/i',
        'Dr. ',
        $displayName
    );

    $displayName = preg_replace(
        '/^(?:Prof\.\s*){2,}/i',
        'Prof. ',
        $displayName
    );

    $displayName = preg_replace(
        '/^(?:Professor\s+){2,}/i',
        'Professor ',
        $displayName
    );

    $displayName = preg_replace(
        '/^(?:Mr\.\s*){2,}/i',
        'Mr. ',
        $displayName
    );

    $displayName = preg_replace(
        '/^(?:Mrs\.\s*){2,}/i',
        'Mrs. ',
        $displayName
    );

    $displayName = preg_replace(
        '/^(?:Ms\.\s*){2,}/i',
        'Ms. ',
        $displayName
    );


    /*
    |--------------------------------------------------------------------------
    | Build Final Display Name
    |--------------------------------------------------------------------------
    */

    $finalDisplayName = $displayName;

    if ($title !== '') {

        $escapedTitle = preg_quote(
            $title,
            '/'
        );

        if (
            !preg_match(
                '/^' . $escapedTitle . '(?:\s|$)/i',
                $displayName
            )
        ) {

            $finalDisplayName =
                $title . ' ' . $displayName;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Final Cleanup
    |--------------------------------------------------------------------------
    */

    $finalDisplayName = preg_replace(
        '/^(?:Prof\.\s*Dr\.\s*){2,}/i',
        'Prof. Dr. ',
        $finalDisplayName
    );

    $finalDisplayName = preg_replace(
        '/^(?:Dr\.\s*){2,}/i',
        'Dr. ',
        $finalDisplayName
    );

    $finalDisplayName = preg_replace(
        '/^(?:Prof\.\s*){2,}/i',
        'Prof. ',
        $finalDisplayName
    );

    $finalDisplayName = preg_replace(
        '/\s+/',
        ' ',
        trim($finalDisplayName)
    );


    /*
    |--------------------------------------------------------------------------
    | Full Name
    |--------------------------------------------------------------------------
    */

    $fullName = trim(
        ($profile?->first_name ?? '') . ' ' .
        ($profile?->middle_name ?? '') . ' ' .
        ($profile?->last_name ?? '')
    );


    /*
    |--------------------------------------------------------------------------
    | Reviewer Type
    |--------------------------------------------------------------------------
    */

    $countryNormalized = strtolower(
        trim(
            $profile?->country ?? ''
        )
    );

    $reviewerType =
        $countryNormalized === 'bangladesh'
            ? 'National'
            : 'International';

@endphp


<div class="reviewer-profile-page">

    <div class="container">

        <div class="reviewer-profile-wrapper">


            {{-- =========================================================
                 BREADCRUMB / BACK LINK
            ========================================================== --}}

            <div class="profile-navigation">

                <a
                    href="{{ route('journal.reviewers.directory') }}"
                    class="back-link"
                >
                    <i class="bi bi-arrow-left"></i>
                    Reviewer Directory
                </a>

            </div>



            {{-- =========================================================
                 MAIN PROFILE CARD
            ========================================================== --}}

            <div class="profile-card">


                {{-- =====================================================
                     PROFILE HEADER
                ====================================================== --}}

                <div class="profile-header">

                    <div class="profile-header-main">

                        <div class="profile-name-area">


                            {{-- Reviewer Type --}}

                            <div class="profile-category">

                                @if($reviewerType === 'National')

                                    <span class="category-badge national">
                                        National Reviewer
                                    </span>

                                @else

                                    <span class="category-badge international">
                                        International Reviewer
                                    </span>

                                @endif


                                @if(!empty($profile?->reviewer_code))

                                    <span class="reviewer-code">
                                        {{ $profile->reviewer_code }}
                                    </span>

                                @endif

                            </div>



                            {{-- Reviewer Name --}}

                            <h1 class="reviewer-name">
                                {{ $finalDisplayName }}
                            </h1>



                            {{-- Designation --}}

                            @if(!empty($profile?->designation))

                                <div class="reviewer-designation">
                                    {{ $profile->designation }}
                                </div>

                            @endif



                            {{-- Affiliation --}}

                            @if(
                                !empty($profile?->department)
                                ||
                                !empty($profile?->institution)
                            )

                                <div class="reviewer-affiliation">

                                    @if(!empty($profile?->department))

                                        <span>
                                            {{ $profile->department }}
                                        </span>

                                    @endif


                                    @if(
                                        !empty($profile?->department)
                                        &&
                                        !empty($profile?->institution)
                                    )

                                        <span class="separator">
                                            •
                                        </span>

                                    @endif


                                    @if(!empty($profile?->institution))

                                        <span>
                                            {{ $profile->institution }}
                                        </span>

                                    @endif

                                </div>

                            @endif



                            {{-- Country --}}

                            @if(!empty($profile?->country))

                                <div class="reviewer-location">

                                    <i class="bi bi-geo-alt"></i>

                                    {{ $profile->country }}

                                </div>

                            @endif


                        </div>

                    </div>

                </div>



                {{-- =====================================================
                     MAIN CONTENT
                ====================================================== --}}

                <div class="profile-content">


                    {{-- =================================================
                         PROFESSIONAL INFORMATION
                    ================================================== --}}

                    <section class="profile-section">

                        <div class="section-heading">

                            <div class="section-icon">
                                <i class="bi bi-person-badge"></i>
                            </div>

                            <div>

                                <h2>
                                    Professional Information
                                </h2>

                                <p>
                                    Current academic and professional affiliation
                                </p>

                            </div>

                        </div>


                        <div class="information-grid">


                            @if(!empty($profile?->designation))

                                <div class="info-item">

                                    <div class="info-label">
                                        Designation
                                    </div>

                                    <div class="info-value">
                                        {{ $profile->designation }}
                                    </div>

                                </div>

                            @endif



                            @if(!empty($profile?->department))

                                <div class="info-item">

                                    <div class="info-label">
                                        Department
                                    </div>

                                    <div class="info-value">
                                        {{ $profile->department }}
                                    </div>

                                </div>

                            @endif



                            @if(!empty($profile?->institution))

                                <div class="info-item">

                                    <div class="info-label">
                                        Institution
                                    </div>

                                    <div class="info-value">
                                        {{ $profile->institution }}
                                    </div>

                                </div>

                            @endif



                            @if(!empty($profile?->organization_type))

                                <div class="info-item">

                                    <div class="info-label">
                                        Organization Type
                                    </div>

                                    <div class="info-value">
                                        {{ $profile->organization_type }}
                                    </div>

                                </div>

                            @endif



                            @if(!empty($profile?->years_of_experience))

                                <div class="info-item">

                                    <div class="info-label">
                                        Professional Experience
                                    </div>

                                    <div class="info-value">

                                        {{ $profile->years_of_experience }}

                                        {{
                                            $profile->years_of_experience == 1
                                                ? 'Year'
                                                : 'Years'
                                        }}

                                    </div>

                                </div>

                            @endif



                            @if(!empty($profile?->country))

                                <div class="info-item">

                                    <div class="info-label">
                                        Country
                                    </div>

                                    <div class="info-value">
                                        {{ $profile->country }}
                                    </div>

                                </div>

                            @endif


                        </div>

                    </section>



                    {{-- =================================================
                         SPECIALITY & EXPERTISE
                    ================================================== --}}

                    @if(
                        !empty($profile?->speciality)
                        ||
                        !empty($profile?->sub_speciality)
                        ||
                        !empty($profile?->specialization)
                        ||
                        !empty($profile?->primary_expertise)
                        ||
                        !empty($profile?->areas_of_expertise)
                    )

                        <section class="profile-section">

                            <div class="section-heading">

                                <div class="section-icon">
                                    <i class="bi bi-journal-medical"></i>
                                </div>

                                <div>

                                    <h2>
                                        Speciality & Expertise
                                    </h2>

                                    <p>
                                        Areas of professional and research expertise
                                    </p>

                                </div>

                            </div>


                            <div class="information-grid">


                                @if(!empty($profile?->speciality))

                                    <div class="info-item">

                                        <div class="info-label">
                                            Speciality
                                        </div>

                                        <div class="info-value highlight-value">
                                            {{ $profile->speciality }}
                                        </div>

                                    </div>

                                @endif



                                @if(!empty($profile?->sub_speciality))

                                    <div class="info-item">

                                        <div class="info-label">
                                            Sub-speciality
                                        </div>

                                        <div class="info-value">
                                            {{ $profile->sub_speciality }}
                                        </div>

                                    </div>

                                @endif



                                @if(!empty($profile?->primary_expertise))

                                    <div class="info-item">

                                        <div class="info-label">
                                            Primary Expertise
                                        </div>

                                        <div class="info-value">
                                            {{ $profile->primary_expertise }}
                                        </div>

                                    </div>

                                @endif



                                @if(!empty($profile?->specialization))

                                    <div class="info-item">

                                        <div class="info-label">
                                            Specialization
                                        </div>

                                        <div class="info-value">
                                            {{ $profile->specialization }}
                                        </div>

                                    </div>

                                @endif


                            </div>



                            @if(!empty($profile?->areas_of_expertise))

                                <div class="text-information">

                                    <div class="info-label">
                                        Areas of Expertise
                                    </div>

                                    <div class="info-text">
                                        {{ $profile->areas_of_expertise }}
                                    </div>

                                </div>

                            @endif


                        </section>

                    @endif



                    {{-- =================================================
                         ACADEMIC QUALIFICATIONS
                    ================================================== --}}

                    @if(
                        !empty($profile?->highest_degree)
                        ||
                        !empty($profile?->highest_degree_institution)
                        ||
                        !empty($profile?->academic_qualifications)
                        ||
                        !empty($profile?->professional_qualifications)
                    )

                        <section class="profile-section">

                            <div class="section-heading">

                                <div class="section-icon">
                                    <i class="bi bi-mortarboard"></i>
                                </div>

                                <div>

                                    <h2>
                                        Academic Qualifications
                                    </h2>

                                    <p>
                                        Educational and professional qualifications
                                    </p>

                                </div>

                            </div>


                            <div class="information-grid">


                                @if(!empty($profile?->highest_degree))

                                    <div class="info-item">

                                        <div class="info-label">
                                            Highest Degree
                                        </div>

                                        <div class="info-value">
                                            {{ $profile->highest_degree }}
                                        </div>

                                    </div>

                                @endif



                                @if(!empty($profile?->highest_degree_institution))

                                    <div class="info-item">

                                        <div class="info-label">
                                            Degree Institution
                                        </div>

                                        <div class="info-value">
                                            {{ $profile->highest_degree_institution }}
                                        </div>

                                    </div>

                                @endif



                                @if(!empty($profile?->year_of_highest_degree))

                                    <div class="info-item">

                                        <div class="info-label">
                                            Year of Highest Degree
                                        </div>

                                        <div class="info-value">
                                            {{ $profile->year_of_highest_degree }}
                                        </div>

                                    </div>

                                @endif


                            </div>



                            @if(!empty($profile?->academic_qualifications))

                                <div class="text-information">

                                    <div class="info-label">
                                        Academic Qualifications
                                    </div>

                                    <div class="info-text">
                                        {{ $profile->academic_qualifications }}
                                    </div>

                                </div>

                            @endif



                            @if(!empty($profile?->professional_qualifications))

                                <div class="text-information">

                                    <div class="info-label">
                                        Professional Qualifications
                                    </div>

                                    <div class="info-text">
                                        {{ $profile->professional_qualifications }}
                                    </div>

                                </div>

                            @endif


                        </section>

                    @endif



                    {{-- =================================================
                         RESEARCH PROFILE
                    ================================================== --}}

                    @if(
                        !empty($profile?->research_interests)
                        ||
                        !is_null($profile?->publication_count)
                        ||
                        !empty($profile?->orcid)
                        ||
                        !empty($profile?->scopus_author_id)
                        ||
                        !empty($profile?->researcher_id)
                        ||
                        !empty($profile?->web_of_science_id)
                    )

                        <section class="profile-section">

                            <div class="section-heading">

                                <div class="section-icon">
                                    <i class="bi bi-bar-chart-line"></i>
                                </div>

                                <div>

                                    <h2>
                                        Research Profile
                                    </h2>

                                    <p>
                                        Research interests, publications and identifiers
                                    </p>

                                </div>

                            </div>



                            {{-- Publication Statistics --}}

                            @if(
                                !is_null($profile?->publication_count)
                                ||
                                !is_null($profile?->first_author_publications)
                                ||
                                !is_null($profile?->corresponding_author_publications)
                            )

                                <div class="research-statistics">


                                    @if(!is_null($profile?->publication_count))

                                        <div class="stat-item">

                                            <strong>
                                                {{ $profile->publication_count }}
                                            </strong>

                                            <span>
                                                Publications
                                            </span>

                                        </div>

                                    @endif



                                    @if(!is_null($profile?->first_author_publications))

                                        <div class="stat-item">

                                            <strong>
                                                {{ $profile->first_author_publications }}
                                            </strong>

                                            <span>
                                                First Author
                                            </span>

                                        </div>

                                    @endif



                                    @if(!is_null($profile?->corresponding_author_publications))

                                        <div class="stat-item">

                                            <strong>
                                                {{ $profile->corresponding_author_publications }}
                                            </strong>

                                            <span>
                                                Corresponding Author
                                            </span>

                                        </div>

                                    @endif


                                </div>

                            @endif



                            @if(!empty($profile?->research_interests))

                                <div class="text-information">

                                    <div class="info-label">
                                        Research Interests
                                    </div>

                                    <div class="info-text">
                                        {{ $profile->research_interests }}
                                    </div>

                                </div>

                            @endif



                            <div class="information-grid identifier-grid">


                                @if(!empty($profile?->orcid))

                                    <div class="info-item">

                                        <div class="info-label">
                                            ORCID
                                        </div>

                                        <div class="info-value">
                                            {{ $profile->orcid }}
                                        </div>

                                    </div>

                                @endif



                                @if(!empty($profile?->scopus_author_id))

                                    <div class="info-item">

                                        <div class="info-label">
                                            Scopus Author ID
                                        </div>

                                        <div class="info-value">
                                            {{ $profile->scopus_author_id }}
                                        </div>

                                    </div>

                                @endif



                                @if(!empty($profile?->researcher_id))

                                    <div class="info-item">

                                        <div class="info-label">
                                            Researcher ID
                                        </div>

                                        <div class="info-value">
                                            {{ $profile->researcher_id }}
                                        </div>

                                    </div>

                                @endif



                                @if(!empty($profile?->web_of_science_id))

                                    <div class="info-item">

                                        <div class="info-label">
                                            Web of Science ID
                                        </div>

                                        <div class="info-value">
                                            {{ $profile->web_of_science_id }}
                                        </div>

                                    </div>

                                @endif


                            </div>

                        </section>

                    @endif



                    {{-- =================================================
                         REVIEWING EXPERIENCE
                    ================================================== --}}

                    @if(
                        !empty($profile?->reviewing_experience)
                        ||
                        !empty($profile?->previous_journal_experience)
                        ||
                        !is_null($profile?->external_reviews_completed)
                    )

                        <section class="profile-section">

                            <div class="section-heading">

                                <div class="section-icon">
                                    <i class="bi bi-clipboard-check"></i>
                                </div>

                                <div>

                                    <h2>
                                        Reviewing Experience
                                    </h2>

                                    <p>
                                        Academic peer-review experience
                                    </p>

                                </div>

                            </div>



                            @if(!is_null($profile?->external_reviews_completed))

                                <div class="review-count">

                                    <strong>
                                        {{ $profile->external_reviews_completed }}
                                    </strong>

                                    <span>
                                        External Reviews Completed
                                    </span>

                                </div>

                            @endif



                            @if(!empty($profile?->reviewing_experience))

                                <div class="text-information">

                                    <div class="info-label">
                                        Reviewing Experience
                                    </div>

                                    <div class="info-text">
                                        {{ $profile->reviewing_experience }}
                                    </div>

                                </div>

                            @endif



                            @if(!empty($profile?->previous_journal_experience))

                                <div class="text-information">

                                    <div class="info-label">
                                        Previous Journal Experience
                                    </div>

                                    <div class="info-text">
                                        {{ $profile->previous_journal_experience }}
                                    </div>

                                </div>

                            @endif


                        </section>

                    @endif



                    {{-- =================================================
                         FOOTER
                    ================================================== --}}

                    <div class="profile-footer">

                        <a
                            href="{{ route('journal.reviewers.directory') }}"
                            class="btn-back-directory"
                        >
                            <i class="bi bi-arrow-left"></i>
                            Back to Reviewer Directory
                        </a>

                    </div>


                </div>

            </div>

        </div>

    </div>

</div>



<style>

/* ================================================================
   PAGE
================================================================ */

.reviewer-profile-page {
    background: #f7f8fa;
    min-height: 100vh;
    padding: 30px 0 50px;
}

.reviewer-profile-wrapper {
    max-width: 900px;
    margin: 0 auto;
}


/* ================================================================
   NAVIGATION
================================================================ */

.profile-navigation {
    margin-bottom: 14px;
}

.back-link {
    color: #52606d;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
}

.back-link:hover {
    color: #198754;
}


/* ================================================================
   MAIN CARD
================================================================ */

.profile-card {
    background: #ffffff;
    border: 1px solid #e7eaed;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 3px 14px rgba(0, 0, 0, .045);
}


/* ================================================================
   HEADER
================================================================ */

.profile-header {
    padding: 28px 32px 24px;
    border-top: 4px solid #198754;
    border-bottom: 1px solid #e9ecef;
    background: #ffffff;
}

.profile-category {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
    margin-bottom: 10px;
}

.category-badge {
    display: inline-block;
    padding: 4px 9px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .25px;
    text-transform: uppercase;
}

.category-badge.national {
    background: #eaf6ef;
    color: #157347;
}

.category-badge.international {
    background: #eef4fb;
    color: #315d8a;
}

.reviewer-code {
    color: #6c757d;
    font-size: 12px;
}

.reviewer-name {
    margin: 0 0 5px;
    color: #212529;
    font-size: 25px;
    line-height: 1.25;
    font-weight: 650;
}

.reviewer-designation {
    color: #198754;
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 7px;
}

.reviewer-affiliation {
    color: #495057;
    font-size: 14px;
    line-height: 1.5;
}

.reviewer-affiliation .separator {
    margin: 0 5px;
    color: #adb5bd;
}

.reviewer-location {
    color: #6c757d;
    font-size: 13px;
    margin-top: 5px;
}


/* ================================================================
   CONTENT
================================================================ */

.profile-content {
    padding: 0 32px;
}

.profile-section {
    padding: 25px 0;
    border-bottom: 1px solid #eceff1;
}

.section-heading {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    margin-bottom: 18px;
}

.section-icon {
    width: 32px;
    height: 32px;
    min-width: 32px;
    border-radius: 5px;
    background: #edf7f1;
    color: #198754;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 15px;
}

.section-heading h2 {
    margin: 0;
    color: #252b31;
    font-size: 16px;
    font-weight: 650;
}

.section-heading p {
    margin: 2px 0 0;
    color: #8a9299;
    font-size: 12px;
}


/* ================================================================
   INFORMATION GRID
================================================================ */

.information-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    column-gap: 40px;
    row-gap: 0;
}

.info-item {
    padding: 9px 0;
    border-bottom: 1px dotted #e3e6e8;
}

.info-label {
    color: #7a838b;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .35px;
    margin-bottom: 3px;
}

.info-value {
    color: #30363b;
    font-size: 13.5px;
    line-height: 1.5;
    word-break: break-word;
}

.highlight-value {
    color: #157347;
    font-weight: 600;
}


/* ================================================================
   LONG TEXT
================================================================ */

.text-information {
    margin-top: 15px;
}

.info-text {
    color: #495057;
    font-size: 13.5px;
    line-height: 1.65;
    margin-top: 5px;
}


/* ================================================================
   RESEARCH STATISTICS
================================================================ */

.research-statistics {
    display: flex;
    gap: 12px;
    margin-bottom: 18px;
}

.stat-item {
    min-width: 115px;
    padding: 10px 14px;
    background: #f8f9fa;
    border: 1px solid #eceff1;
    border-radius: 5px;
}

.stat-item strong {
    display: block;
    color: #198754;
    font-size: 18px;
    line-height: 1.2;
}

.stat-item span {
    display: block;
    color: #6c757d;
    font-size: 11px;
    margin-top: 3px;
}


/* ================================================================
   REVIEW COUNT
================================================================ */

.review-count {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 5px;

    padding: 8px 12px;
    margin-bottom: 12px;
}

.review-count strong {
    color: #198754;
    font-size: 17px;
}

.review-count span {
    color: #6c757d;
    font-size: 12px;
}


/* ================================================================
   FOOTER
================================================================ */

.profile-footer {
    padding: 22px 0 28px;
}

.btn-back-directory {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    border: 1px solid #ced4da;
    border-radius: 5px;

    padding: 7px 13px;

    color: #495057;
    background: #ffffff;

    text-decoration: none;

    font-size: 13px;
    font-weight: 500;
}

.btn-back-directory:hover {
    border-color: #198754;
    color: #198754;
    background: #f8fcfa;
}


/* ================================================================
   RESPONSIVE
================================================================ */

@media (max-width: 767.98px) {

    .reviewer-profile-page {
        padding-top: 18px;
    }

    .profile-header {
        padding: 22px 20px;
    }

    .profile-content {
        padding: 0 20px;
    }

    .reviewer-name {
        font-size: 21px;
    }

    .information-grid {
        grid-template-columns: 1fr;
    }

    .research-statistics {
        flex-wrap: wrap;
    }

    .stat-item {
        flex: 1;
        min-width: 100px;
    }

}

</style>

@endsection