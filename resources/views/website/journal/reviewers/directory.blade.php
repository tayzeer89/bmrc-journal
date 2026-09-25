@extends('layouts.app')

@section('title', 'Reviewer Directory')

@section('content')

<div class="reviewer-directory-page">

    <div class="container">

        <div class="directory-wrapper">

            {{-- =========================================================
                 PAGE HEADER
            ========================================================== --}}

            <div class="directory-header">

                <div class="directory-title">

                    <h1>
                        Reviewer Directory
                    </h1>

                    <p>
                        Bangladesh Medical Research Council Bulletin
                    </p>

                </div>


                {{-- =====================================================
                     SUMMARY
                ====================================================== --}}

                <div class="directory-summary">

                    <div class="summary-item">

                        <strong>
                            {{ $totalReviewers }}
                        </strong>

                        <span>
                            Total
                        </span>

                    </div>


                    <div class="summary-divider"></div>


                    <div class="summary-item">

                        <strong>
                            {{ $nationalCount }}
                        </strong>

                        <span>
                            National
                        </span>

                    </div>


                    <div class="summary-divider"></div>


                    <div class="summary-item">

                        <strong>
                            {{ $internationalCount }}
                        </strong>

                        <span>
                            International
                        </span>

                    </div>

                </div>

            </div>


            {{-- =========================================================
                 SEARCH / FILTER PANEL
            ========================================================== --}}

            <div class="filter-panel">

                <form
                    method="GET"
                    action="{{ route('journal.reviewers.directory') }}"
                >

                    <div class="filter-grid">

                        {{-- =================================================
                             Search
                        ================================================== --}}

                        <div class="filter-field search-field">

                            <label for="reviewer-search">
                                Search Reviewer
                            </label>


                            <div class="input-with-icon">

                                <i class="bi bi-search"></i>

                                <input
                                    id="reviewer-search"
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Name, institution or speciality"
                                >

                            </div>

                        </div>


                        {{-- =================================================
                             Speciality
                        ================================================== --}}

                        <div class="filter-field">

                            <label for="speciality">
                                Speciality
                            </label>


                            <select
                                id="speciality"
                                name="speciality"
                            >

                                <option value="">
                                    All Specialities
                                </option>


                                @foreach(
                                    $specialities as $speciality
                                )

                                    <option
                                        value="{{ $speciality }}"
                                        @selected(
                                            request('speciality')
                                            === $speciality
                                        )
                                    >
                                        {{ $speciality }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- =================================================
                             Category
                        ================================================== --}}

                        <div class="filter-field">

                            <label for="reviewer-type">
                                Category
                            </label>


                            <select
                                id="reviewer-type"
                                name="reviewer_type"
                            >

                                <option value="">
                                    All Reviewers
                                </option>


                                <option
                                    value="national"
                                    @selected(
                                        request('reviewer_type')
                                        === 'national'
                                    )
                                >
                                    National
                                </option>


                                <option
                                    value="international"
                                    @selected(
                                        request('reviewer_type')
                                        === 'international'
                                    )
                                >
                                    International
                                </option>

                            </select>

                        </div>


                        {{-- =================================================
                             Search Button
                        ================================================== --}}

                        <div class="filter-action">

                            <button
                                type="submit"
                                class="search-button"
                            >

                                <i class="bi bi-search"></i>

                                <span>
                                    Search
                                </span>

                            </button>

                        </div>


                        {{-- =================================================
                             PDF Button
                        ================================================== --}}

                        <div class="filter-action">

                            <a
                                href="{{ route(
                                    'journal.reviewers.pdf',
                                    request()->query()
                                ) }}"
                                class="pdf-button"
                                target="_blank"
                                title="Export current reviewer results to PDF"
                            >

                                <i class="bi bi-file-earmark-pdf"></i>

                                <span>
                                    PDF
                                </span>

                            </a>

                        </div>

                    </div>


                    {{-- =====================================================
                         ACTIVE FILTER INFORMATION
                    ====================================================== --}}

                    @if(
                        request()->filled('search')
                        || request()->filled('speciality')
                        || request()->filled('reviewer_type')
                    )

                        <div class="active-filter">

                            <div class="active-filter-info">

                                <i class="bi bi-funnel"></i>

                                <span>
                                    Filtered results:
                                </span>


                                @if(request()->filled('search'))

                                    <span class="filter-tag">

                                        Search:
                                        {{ request('search') }}

                                    </span>

                                @endif


                                @if(request()->filled('speciality'))

                                    <span class="filter-tag">

                                        {{ request('speciality') }}

                                    </span>

                                @endif


                                @if(
                                    request()->filled(
                                        'reviewer_type'
                                    )
                                )

                                    <span class="filter-tag">

                                        {{
                                            ucfirst(
                                                request(
                                                    'reviewer_type'
                                                )
                                            )
                                        }}

                                    </span>

                                @endif

                            </div>


                            <a
                                href="{{ route(
                                    'journal.reviewers.directory'
                                ) }}"
                                class="clear-filter"
                            >

                                <i class="bi bi-x-circle"></i>

                                Clear Filters

                            </a>

                        </div>

                    @endif

                </form>

            </div>


            {{-- =========================================================
                 RESULT INFORMATION
            ========================================================== --}}

            <div class="result-information">

                <div>

                    <strong>
                        {{ $totalReviewers }}
                    </strong>

                    {{ $totalReviewers === 1
                        ? 'reviewer found'
                        : 'reviewers found'
                    }}

                </div>


                @if(
                    request()->filled('search')
                    || request()->filled('speciality')
                    || request()->filled('reviewer_type')
                )

                    <div class="filtered-label">

                        <i class="bi bi-funnel-fill"></i>

                        Filtered Result

                    </div>

                @endif

            </div>


            {{-- =========================================================
                 DIRECTORY RESULTS
            ========================================================== --}}

            <div class="directory-results">

                @forelse(
                    $reviewersBySpeciality
                    as $speciality => $reviewers
                )

                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | National Reviewers
                        |--------------------------------------------------------------------------
                        */

                        $nationalReviewers =
                            $reviewers->filter(
                                function ($reviewer) {

                                    return strtolower(
                                        trim(
                                            $reviewer
                                                ->profile
                                                ?->country
                                            ?? ''
                                        )
                                    ) === 'bangladesh';

                                }
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | International Reviewers
                        |--------------------------------------------------------------------------
                        */

                        $internationalReviewers =
                            $reviewers->filter(
                                function ($reviewer) {

                                    $country = strtolower(
                                        trim(
                                            $reviewer
                                                ->profile
                                                ?->country
                                            ?? ''
                                        )
                                    );


                                    return
                                        $country !== ''
                                        &&
                                        $country !== 'bangladesh';

                                }
                            );

                    @endphp


                    {{-- =====================================================
                         SPECIALITY
                    ====================================================== --}}

                    <section class="speciality-section">

                        <div class="speciality-header">

                            <div class="speciality-title-area">

                                <div class="speciality-icon">

                                    <i class="bi bi-journal-medical"></i>

                                </div>


                                <div>

                                    <h2>
                                        {{ $speciality }}
                                    </h2>

                                    <p>

                                        {{ $reviewers->count() }}

                                        {{ $reviewers->count() === 1
                                            ? 'reviewer'
                                            : 'reviewers'
                                        }}

                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             NATIONAL REVIEWERS
                        ================================================== --}}

                        @if(
                            $nationalReviewers->isNotEmpty()
                        )

                            <div class="reviewer-category">

                                <div class="category-header">

                                    <div class="category-title">

                                        <span
                                            class="
                                                category-indicator
                                                national
                                            "
                                        ></span>

                                        <h3>
                                            National Reviewers
                                        </h3>

                                    </div>


                                    <span class="category-count">

                                        {{
                                            $nationalReviewers
                                                ->count()
                                        }}

                                    </span>

                                </div>


                                @include(
                                    'website.journal.reviewers.partials.reviewer-group',
                                    [
                                        'reviewers'
                                            => $nationalReviewers
                                    ]
                                )

                            </div>

                        @endif


                        {{-- =================================================
                             INTERNATIONAL REVIEWERS
                        ================================================== --}}

                        @if(
                            $internationalReviewers
                                ->isNotEmpty()
                        )

                            <div class="reviewer-category">

                                <div class="category-header">

                                    <div class="category-title">

                                        <span
                                            class="
                                                category-indicator
                                                international
                                            "
                                        ></span>

                                        <h3>
                                            International Reviewers
                                        </h3>

                                    </div>


                                    <span class="category-count">

                                        {{
                                            $internationalReviewers
                                                ->count()
                                        }}

                                    </span>

                                </div>


                                @include(
                                    'website.journal.reviewers.partials.reviewer-group',
                                    [
                                        'reviewers'
                                            => $internationalReviewers
                                    ]
                                )

                            </div>

                        @endif

                    </section>


                @empty

                    {{-- =====================================================
                         NO RESULTS
                    ====================================================== --}}

                    <div class="no-results">

                        <div class="no-results-icon">

                            <i class="bi bi-people"></i>

                        </div>


                        <h3>
                            No Reviewers Found
                        </h3>


                        <p>

                            No reviewer matches the selected
                            search criteria.

                        </p>


                        <a
                            href="{{ route(
                                'journal.reviewers.directory'
                            ) }}"
                            class="view-all-button"
                        >

                            <i class="bi bi-arrow-counterclockwise"></i>

                            View All Reviewers

                        </a>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>


<style>

/* ================================================================
   PAGE
================================================================ */

.reviewer-directory-page {

    background: #f7f8fa;

    min-height: 100vh;

    padding:
        25px
        0
        45px;

}


.directory-wrapper {

    max-width: 1100px;

    margin:
        0
        auto;

}


/* ================================================================
   HEADER
================================================================ */

.directory-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 25px;


    background: #ffffff;


    border:
        1px
        solid
        #e5e8ea;


    border-top:
        4px
        solid
        #198754;


    border-radius: 7px;


    padding:
        18px
        22px;


    margin-bottom: 14px;


    box-shadow:
        0
        2px
        8px
        rgba(0, 0, 0, .03);

}


.directory-header h1 {

    margin:
        0
        0
        3px;


    color: #22272b;


    font-size: 22px;

    font-weight: 650;

}


.directory-header p {

    margin: 0;


    color: #7c848a;


    font-size: 12.5px;

}


/* ================================================================
   SUMMARY
================================================================ */

.directory-summary {

    display: flex;

    align-items: center;


    background: #f8f9fa;


    border:
        1px
        solid
        #eceeef;


    border-radius: 5px;


    padding:
        7px
        9px;

}


.summary-item {

    min-width: 62px;

    text-align: center;

}


.summary-item strong {

    display: block;


    color: #198754;


    font-size: 16px;

    font-weight: 700;

    line-height: 1.1;

}


.summary-item span {

    display: block;


    margin-top: 2px;


    color: #7a8288;


    font-size: 9px;


    text-transform: uppercase;


    letter-spacing: .3px;

}


.summary-divider {

    width: 1px;

    height: 25px;


    margin:
        0
        6px;


    background: #dfe2e4;

}


/* ================================================================
   FILTER PANEL
================================================================ */

.filter-panel {

    background: #ffffff;


    border:
        1px
        solid
        #e5e8ea;


    border-radius: 7px;


    padding:
        14px
        16px;


    margin-bottom: 13px;


    box-shadow:
        0
        2px
        7px
        rgba(0, 0, 0, .025);

}


/* ================================================================
   FILTER GRID
================================================================ */

.filter-grid {

    display: grid;


    grid-template-columns:

        minmax(240px, 2fr)

        minmax(160px, 1fr)

        minmax(140px, .8fr)

        auto

        auto;


    gap: 9px;


    align-items: end;

}


/* ================================================================
   FILTER FIELDS
================================================================ */

.filter-field label {

    display: block;


    margin-bottom: 4px;


    color: #646c72;


    font-size: 9.5px;

    font-weight: 650;


    text-transform: uppercase;


    letter-spacing: .3px;

}


.filter-field input,
.filter-field select {

    width: 100%;

    height: 35px;


    border:
        1px
        solid
        #dce0e3;


    border-radius: 4px;


    background: #ffffff;


    color: #343a40;


    font-size: 12px;


    padding:
        0
        9px;


    outline: none;


    transition:
        border-color .15s ease,
        box-shadow .15s ease;

}


.filter-field input:focus,
.filter-field select:focus {

    border-color: #75bc94;


    box-shadow:
        0
        0
        0
        3px
        rgba(25, 135, 84, .07);

}


/* ================================================================
   SEARCH INPUT ICON
================================================================ */

.input-with-icon {

    position: relative;

}


.input-with-icon i {

    position: absolute;


    left: 10px;

    top: 50%;


    transform:
        translateY(-50%);


    color: #99a0a6;


    font-size: 11px;

}


.input-with-icon input {

    padding-left: 29px;

}


/* ================================================================
   FILTER ACTION
================================================================ */

.filter-action {

    display: flex;

    align-items: flex-end;

}


/* ================================================================
   SEARCH BUTTON
================================================================ */

.search-button {

    height: 35px;


    display: inline-flex;

    align-items: center;

    justify-content: center;


    gap: 5px;


    border:
        1px
        solid
        #198754;


    border-radius: 4px;


    background: #198754;


    color: #ffffff;


    padding:
        0
        14px;


    font-size: 11px;

    font-weight: 600;


    white-space: nowrap;


    cursor: pointer;


    transition:
        background .15s ease;

}


.search-button:hover {

    background: #157347;

    border-color: #157347;

}


/* ================================================================
   PDF BUTTON
================================================================ */

.pdf-button {

    height: 35px;


    display: inline-flex;

    align-items: center;

    justify-content: center;


    gap: 5px;


    padding:
        0
        13px;


    border:
        1px
        solid
        #dc3545;


    border-radius: 4px;


    background: #ffffff;


    color: #dc3545;


    font-size: 11px;

    font-weight: 600;


    text-decoration: none;


    white-space: nowrap;


    transition:
        background .15s ease,
        color .15s ease;

}


.pdf-button:hover {

    background: #dc3545;

    color: #ffffff;

}


/* ================================================================
   ACTIVE FILTER
================================================================ */

.active-filter {

    display: flex;

    align-items: center;

    justify-content: space-between;


    gap: 15px;


    margin-top: 10px;


    padding-top: 9px;


    border-top:
        1px
        solid
        #f0f1f2;


    font-size: 10.5px;

}


.active-filter-info {

    display: flex;

    align-items: center;

    flex-wrap: wrap;


    gap: 5px;


    color: #7e868c;

}


.active-filter-info > i {

    color: #198754;

}


.filter-tag {

    display: inline-block;


    padding:
        2px
        6px;


    background: #f1f7f4;


    border:
        1px
        solid
        #dbece3;


    border-radius: 3px;


    color: #337453;


    font-size: 9.5px;

}


.clear-filter {

    color: #727a80;


    text-decoration: none;


    font-size: 10.5px;

    font-weight: 500;


    white-space: nowrap;

}


.clear-filter:hover {

    color: #dc3545;

}


/* ================================================================
   RESULT INFORMATION
================================================================ */

.result-information {

    display: flex;

    align-items: center;

    justify-content: space-between;


    margin-bottom: 8px;


    padding:
        0
        2px;


    color: #747c82;


    font-size: 10.5px;

}


.result-information strong {

    color: #343a40;

}


.filtered-label {

    color: #198754;


    font-size: 9.5px;

    font-weight: 600;

}


/* ================================================================
   SPECIALITY SECTION
================================================================ */

.speciality-section {

    background: #ffffff;


    border:
        1px
        solid
        #e5e8ea;


    border-radius: 6px;


    margin-bottom: 13px;


    overflow: hidden;


    box-shadow:
        0
        2px
        7px
        rgba(0, 0, 0, .02);

}


/* ================================================================
   SPECIALITY HEADER
================================================================ */

.speciality-header {

    display: flex;

    align-items: center;

    justify-content: space-between;


    padding:
        10px
        15px;


    background: #fafbfb;


    border-bottom:
        1px
        solid
        #e9ecef;

}


.speciality-title-area {

    display: flex;

    align-items: center;


    gap: 8px;

}


.speciality-icon {

    width: 27px;

    height: 27px;


    display: flex;

    align-items: center;

    justify-content: center;


    border-radius: 4px;


    background: #eaf5ef;


    color: #198754;


    font-size: 12px;

}


.speciality-header h2 {

    margin: 0;


    color: #176b45;


    font-size: 14px;

    font-weight: 650;

}


.speciality-header p {

    margin:
        1px
        0
        0;


    color: #92999f;


    font-size: 9.5px;

}


/* ================================================================
   REVIEWER CATEGORY
================================================================ */

.reviewer-category {

    padding:
        11px
        15px
        4px;

}


.reviewer-category
+
.reviewer-category {

    border-top:
        1px
        solid
        #eceff1;

}


/* ================================================================
   CATEGORY HEADER
================================================================ */

.category-header {

    display: flex;

    align-items: center;

    justify-content: space-between;


    margin-bottom: 8px;

}


.category-title {

    display: flex;

    align-items: center;


    gap: 6px;

}


.category-title h3 {

    margin: 0;


    color: #4a5156;


    font-size: 10.5px;

    font-weight: 650;


    text-transform: uppercase;


    letter-spacing: .25px;

}


.category-indicator {

    display: inline-block;


    width: 6px;

    height: 6px;


    border-radius: 50%;

}


.category-indicator.national {

    background: #198754;

}


.category-indicator.international {

    background: #4678a8;

}


.category-count {

    display: inline-flex;


    min-width: 22px;

    height: 18px;


    align-items: center;

    justify-content: center;


    padding:
        0
        5px;


    background: #f2f3f4;


    border:
        1px
        solid
        #e2e4e6;


    border-radius: 9px;


    color: #70787e;


    font-size: 9px;

    font-weight: 600;

}


/* ================================================================
   COMPACT REVIEWER PARTIAL OVERRIDES
================================================================ */

.reviewer-category
.compact-designation-group {

    margin-bottom:
        10px;

}


.reviewer-category
.compact-designation-heading {

    margin-bottom:
        4px;


    font-size:
        10.5px;

}


.reviewer-category
.compact-reviewer-row {

    min-height:
        41px;


    padding:
        5px
        9px;

}


.reviewer-category
.reviewer-name-link {

    font-size:
        11.5px;

}


.reviewer-category
.reviewer-position {

    font-size:
        9.5px;

}


.reviewer-category
.reviewer-institution {

    font-size:
        10px;

}


.reviewer-category
.reviewer-country {

    font-size:
        9.5px;

}


.reviewer-category
.reviewer-action a {

    font-size:
        9.5px;

}


/* ================================================================
   NO RESULTS
================================================================ */

.no-results {

    background: #ffffff;


    border:
        1px
        solid
        #e5e8ea;


    border-radius: 7px;


    text-align: center;


    padding:
        38px
        20px;

}


.no-results-icon {

    width: 40px;

    height: 40px;


    display: flex;

    align-items: center;

    justify-content: center;


    margin:
        0
        auto
        10px;


    background: #edf6f1;


    border-radius: 50%;


    color: #198754;


    font-size: 17px;

}


.no-results h3 {

    margin:
        0
        0
        4px;


    color: #343a40;


    font-size: 14px;

}


.no-results p {

    margin:
        0
        0
        13px;


    color: #858d93;


    font-size: 11px;

}


.view-all-button {

    display: inline-flex;

    align-items: center;

    gap: 5px;


    padding:
        6px
        11px;


    border:
        1px
        solid
        #198754;


    border-radius: 4px;


    color: #198754;


    text-decoration: none;


    font-size: 10.5px;

    font-weight: 600;

}


.view-all-button:hover {

    background: #198754;

    color: #ffffff;

}


/* ================================================================
   TABLET
================================================================ */

@media (max-width: 991.98px) {

    .filter-grid {

        grid-template-columns:
            repeat(
                2,
                minmax(0, 1fr)
            );

    }


    .filter-action {

        width: 100%;

    }


    .search-button,
    .pdf-button {

        width: 100%;

    }

}


/* ================================================================
   MOBILE
================================================================ */

@media (max-width: 767.98px) {

    .reviewer-directory-page {

        padding:
            15px
            0
            30px;

    }


    .directory-header {

        display: block;


        padding:
            15px;

    }


    .directory-header h1 {

        font-size:
            19px;

    }


    .directory-summary {

        margin-top:
            13px;


        width:
            100%;


        justify-content:
            space-around;

    }


    .summary-item {

        flex:
            1;

    }


    .filter-panel {

        padding:
            12px;

    }


    .filter-grid {

        grid-template-columns:
            1fr;

    }


    .search-button,
    .pdf-button {

        width:
            100%;

    }


    .active-filter {

        display:
            block;

    }


    .clear-filter {

        display:
            inline-block;


        margin-top:
            7px;

    }


    .result-information {

        align-items:
            flex-start;


        gap:
            10px;

    }


    .speciality-header {

        padding:
            9px
            12px;

    }


    .reviewer-category {

        padding:
            10px
            12px
            3px;

    }


    .speciality-header h2 {

        font-size:
            13px;

    }

}

</style>

@endsection