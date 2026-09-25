{{-- =====================================================================
     REVIEWER GROUP PARTIAL

     Structure:
     Designation
        └── Reviewer
        └── Reviewer

     Example:
     Professor
        Prof. Dr. A. H. M. Mostafa Kamal
        Prof. Dr. Afia Shahnaj

     IMPORTANT:
     This file prevents duplicate titles such as:
     Prof. Dr. Prof. Dr. A. H. M. Mostafa Kamal
===================================================================== --}}


@php

    /*
    |--------------------------------------------------------------------------
    | Designation Priority
    |--------------------------------------------------------------------------
    |
    | Lower number = displayed first
    |
    */

    $designationOrder = [

        'Professor' => 1,

        'Associate Professor' => 2,

        'Assistant Professor' => 3,

        'Senior Consultant' => 4,

        'Consultant' => 5,

        'Senior Lecturer' => 6,

        'Lecturer' => 7,

        'Researcher' => 8,

        'Medical Officer' => 9,

        'Scientific Officer' => 10,

    ];


    /*
    |--------------------------------------------------------------------------
    | Sort Reviewers
    |--------------------------------------------------------------------------
    */

    $sortedReviewers = $reviewers->sortBy(
        function ($reviewer) use ($designationOrder) {

            $designation = trim(
                $reviewer
                    ->profile
                    ?->designation
                ?? ''
            );


            $rank =
                $designationOrder[$designation]
                ?? 99;


            /*
            |--------------------------------------------------------------------------
            | Name for secondary sorting
            |--------------------------------------------------------------------------
            */

            $name = trim(
                $reviewer
                    ->profile
                    ?->display_name
                ?: $reviewer->name
            );


            return sprintf(
                '%02d-%s',
                $rank,
                strtolower($name)
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Group By Designation
    |--------------------------------------------------------------------------
    */

    $reviewersByDesignation =
        $sortedReviewers
            ->groupBy(
                function ($reviewer) {

                    $designation = trim(
                        $reviewer
                            ->profile
                            ?->designation
                        ?? ''
                    );


                    return $designation !== ''
                        ? $designation
                        : 'Other';

                }
            );


    /*
    |--------------------------------------------------------------------------
    | Sort Designation Groups
    |--------------------------------------------------------------------------
    */

    $reviewersByDesignation =
        $reviewersByDesignation
            ->sortBy(
                function (
                    $group,
                    $designation
                ) use (
                    $designationOrder
                ) {

                    return
                        $designationOrder[$designation]
                        ?? 99;

                }
            );

@endphp



{{-- =====================================================================
     DESIGNATION GROUPS
===================================================================== --}}

@foreach(
    $reviewersByDesignation
    as $designation => $designationReviewers
)

    <div class="compact-designation-group">


        {{-- =============================================================
             DESIGNATION HEADING
        ============================================================== --}}

        <div class="compact-designation-heading">

            <div class="designation-heading-left">

                <i class="bi bi-person-badge"></i>

                <span>
                    {{ $designation }}
                </span>

            </div>


            <span class="designation-reviewer-count">

                {{ $designationReviewers->count() }}

            </span>

        </div>



        {{-- =============================================================
             REVIEWERS
        ============================================================== --}}

        <div class="compact-reviewer-list">


            @foreach(
                $designationReviewers
                as $reviewer
            )

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | Reviewer Profile
                    |--------------------------------------------------------------------------
                    */

                    $profile =
                        $reviewer->profile;


                    /*
                    |--------------------------------------------------------------------------
                    | Original Display Name
                    |--------------------------------------------------------------------------
                    */

                    $displayName = trim(
                        $profile?->display_name
                        ?: $reviewer->name
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Reviewer Title
                    |--------------------------------------------------------------------------
                    |
                    | Example:
                    |
                    | Prof. Dr.
                    | Dr.
                    | Mr.
                    | Ms.
                    |
                    */

                    $title = trim(
                        $profile?->title
                        ?? ''
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Normalize Spaces
                    |--------------------------------------------------------------------------
                    */

                    $displayName = preg_replace(
                        '/\s+/',
                        ' ',
                        $displayName
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | FIX DUPLICATE TITLE
                    |--------------------------------------------------------------------------
                    |
                    | CASE 1
                    |
                    | title:
                    | Prof. Dr.
                    |
                    | display_name:
                    | A. H. M. Mostafa Kamal
                    |
                    | Result:
                    | Prof. Dr. A. H. M. Mostafa Kamal
                    |
                    |
                    | CASE 2
                    |
                    | title:
                    | Prof. Dr.
                    |
                    | display_name:
                    | Prof. Dr. A. H. M. Mostafa Kamal
                    |
                    | Result:
                    | Prof. Dr. A. H. M. Mostafa Kamal
                    |
                    |
                    | CASE 3
                    |
                    | display_name:
                    | Prof. Dr. Prof. Dr. A. H. M. Mostafa Kamal
                    |
                    | Result:
                    | Prof. Dr. A. H. M. Mostafa Kamal
                    |
                    */


                    if ($title !== '') {

                        /*
                        |--------------------------------------------------------------------------
                        | Escape title for Regex
                        |--------------------------------------------------------------------------
                        */

                        $escapedTitle =
                            preg_quote(
                                $title,
                                '/'
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | Remove ALL repeated copies of title from beginning
                        |--------------------------------------------------------------------------
                        |
                        | Example:
                        |
                        | Prof. Dr. Prof. Dr. Name
                        |
                        | becomes:
                        |
                        | Name
                        |
                        */

                        $nameWithoutTitle =
                            preg_replace(
                                '/^(?:'
                                . $escapedTitle
                                . '\s*)+/i',
                                '',
                                $displayName
                            );


                        $nameWithoutTitle =
                            trim(
                                $nameWithoutTitle
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | Add exactly ONE title
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $nameWithoutTitle
                            !== ''
                        ) {

                            $displayName =
                                $title
                                . ' '
                                . $nameWithoutTitle;

                        } else {

                            $displayName =
                                $title;

                        }

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Final Space Cleanup
                    |--------------------------------------------------------------------------
                    */

                    $displayName = preg_replace(
                        '/\s+/',
                        ' ',
                        trim($displayName)
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Institution
                    |--------------------------------------------------------------------------
                    */

                    $institution = trim(
                        $profile?->institution
                        ?? ''
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Department
                    |--------------------------------------------------------------------------
                    */

                    $department = trim(
                        $profile?->department
                        ?? ''
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Country
                    |--------------------------------------------------------------------------
                    */

                    $country = trim(
                        $profile?->country
                        ?? ''
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Reviewer Type
                    |--------------------------------------------------------------------------
                    */

                    $reviewerType =
                        strtolower($country)
                        === 'bangladesh'
                            ? 'National'
                            : 'International';

                @endphp



                {{-- =====================================================
                     REVIEWER ROW
                ====================================================== --}}

                <div class="compact-reviewer-row">


                    {{-- =================================================
                         LEFT: NAME / INFORMATION
                    ================================================== --}}

                    <div class="reviewer-main-info">


                        {{-- Reviewer Name --}}

                        <div class="reviewer-name">

                            <a
                                href="{{ route(
                                    'journal.reviewers.show',
                                    $reviewer
                                ) }}"
                                class="reviewer-name-link"
                            >

                                {{ $displayName }}

                            </a>

                        </div>


                        {{-- Designation --}}

                        @if(
                            !empty(
                                $profile?->designation
                            )
                        )

                            <div class="reviewer-position">

                                {{
                                    $profile
                                        ->designation
                                }}

                            </div>

                        @endif


                        {{-- Department / Institution --}}

                        @if(
                            $department !== ''
                            ||
                            $institution !== ''
                        )

                            <div class="reviewer-institution">

                                <i class="bi bi-building"></i>


                                @if(
                                    $department !== ''
                                )

                                    <span>
                                        {{ $department }}
                                    </span>

                                @endif


                                @if(
                                    $department !== ''
                                    &&
                                    $institution !== ''
                                )

                                    <span class="institution-separator">
                                        ,
                                    </span>

                                @endif


                                @if(
                                    $institution !== ''
                                )

                                    <span>
                                        {{ $institution }}
                                    </span>

                                @endif

                            </div>

                        @endif

                    </div>



                    {{-- =================================================
                         RIGHT: COUNTRY + VIEW
                    ================================================== --}}

                    <div class="reviewer-right-info">


                        {{-- Country --}}

                        @if(
                            $country !== ''
                        )

                            <div class="reviewer-country">

                                <i class="bi bi-geo-alt"></i>

                                <span>
                                    {{ $country }}
                                </span>

                            </div>

                        @endif


                        {{-- View Button --}}

                        <div class="reviewer-action">

                            <a
                                href="{{ route(
                                    'journal.reviewers.show',
                                    $reviewer
                                ) }}"
                                title="View Reviewer Profile"
                            >

                                View

                                <i class="bi bi-chevron-right"></i>

                            </a>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

@endforeach



{{-- =====================================================================
     STYLES
===================================================================== --}}

<style>

/* ================================================================
   DESIGNATION GROUP
================================================================ */

.compact-designation-group {

    margin-bottom: 11px;

}


.compact-designation-group:last-child {

    margin-bottom: 4px;

}


/* ================================================================
   DESIGNATION HEADER
================================================================ */

.compact-designation-heading {

    display: flex;

    align-items: center;

    justify-content: space-between;


    min-height: 27px;


    padding:
        4px
        8px;


    margin-bottom:
        4px;


    background:
        #f5f7f6;


    border-left:
        3px
        solid
        #198754;


    border-radius:
        2px;


    color:
        #495057;


    font-size:
        10.5px;


    font-weight:
        650;

}


.designation-heading-left {

    display: flex;

    align-items: center;

    gap: 5px;

}


.designation-heading-left i {

    color:
        #198754;


    font-size:
        10px;

}


.designation-reviewer-count {

    display:
        inline-flex;


    align-items:
        center;


    justify-content:
        center;


    min-width:
        20px;


    height:
        17px;


    padding:
        0
        5px;


    background:
        #ffffff;


    border:
        1px
        solid
        #dfe4e1;


    border-radius:
        9px;


    color:
        #6d757a;


    font-size:
        8.5px;

}


/* ================================================================
   REVIEWER LIST
================================================================ */

.compact-reviewer-list {

    border:
        1px
        solid
        #edf0ee;


    border-radius:
        4px;


    overflow:
        hidden;

}


/* ================================================================
   REVIEWER ROW
================================================================ */

.compact-reviewer-row {

    display:
        flex;


    align-items:
        center;


    justify-content:
        space-between;


    gap:
        15px;


    min-height:
        45px;


    padding:
        6px
        10px;


    background:
        #ffffff;


    border-bottom:
        1px
        solid
        #edf0ee;


    transition:
        background
        .15s ease;

}


.compact-reviewer-row:last-child {

    border-bottom:
        none;

}


.compact-reviewer-row:hover {

    background:
        #fbfcfb;

}


/* ================================================================
   MAIN REVIEWER INFO
================================================================ */

.reviewer-main-info {

    min-width:
        0;


    flex:
        1;

}


/* ================================================================
   REVIEWER NAME
================================================================ */

.reviewer-name {

    margin-bottom:
        1px;

}


.reviewer-name-link {

    color:
        #263238;


    font-size:
        11.5px;


    font-weight:
        650;


    text-decoration:
        none;


    line-height:
        1.25;

}


.reviewer-name-link:hover {

    color:
        #198754;


    text-decoration:
        underline;

}


/* ================================================================
   POSITION
================================================================ */

.reviewer-position {

    color:
        #198754;


    font-size:
        9.5px;


    font-weight:
        500;


    margin-top:
        1px;

}


/* ================================================================
   INSTITUTION
================================================================ */

.reviewer-institution {

    display:
        flex;


    align-items:
        center;


    flex-wrap:
        wrap;


    gap:
        3px;


    margin-top:
        2px;


    color:
        #7b8389;


    font-size:
        9.5px;


    line-height:
        1.25;

}


.reviewer-institution i {

    color:
        #a0a7ac;


    font-size:
        8.5px;


    margin-right:
        1px;

}


.institution-separator {

    margin-right:
        1px;

}


/* ================================================================
   RIGHT INFORMATION
================================================================ */

.reviewer-right-info {

    display:
        flex;


    align-items:
        center;


    gap:
        14px;


    flex-shrink:
        0;

}


/* ================================================================
   COUNTRY
================================================================ */

.reviewer-country {

    display:
        flex;


    align-items:
        center;


    gap:
        3px;


    min-width:
        85px;


    color:
        #747c82;


    font-size:
        9.5px;

}


.reviewer-country i {

    color:
        #999fa4;


    font-size:
        9px;

}


/* ================================================================
   VIEW ACTION
================================================================ */

.reviewer-action {

    min-width:
        42px;


    text-align:
        right;

}


.reviewer-action a {

    display:
        inline-flex;


    align-items:
        center;


    gap:
        2px;


    color:
        #198754;


    font-size:
        9.5px;


    font-weight:
        600;


    text-decoration:
        none;

}


.reviewer-action a:hover {

    color:
        #11633c;


    text-decoration:
        underline;

}


.reviewer-action i {

    font-size:
        8px;

}


/* ================================================================
   TABLET
================================================================ */

@media (
    max-width: 767.98px
) {

    .compact-reviewer-row {

        align-items:
            flex-start;


        gap:
            8px;


        padding:
            7px
            8px;

    }


    .reviewer-right-info {

        display:
            block;


        text-align:
            right;

    }


    .reviewer-country {

        min-width:
            auto;


        justify-content:
            flex-end;


        margin-bottom:
            3px;

    }


    .reviewer-action {

        min-width:
            auto;

    }

}


/* ================================================================
   SMALL MOBILE
================================================================ */

@media (
    max-width: 575.98px
) {

    .compact-reviewer-row {

        display:
            block;

    }


    .reviewer-right-info {

        display:
            flex;


        align-items:
            center;


        justify-content:
            space-between;


        margin-top:
            5px;


        padding-top:
            4px;


        border-top:
            1px
            dashed
            #eeeeee;

    }


    .reviewer-country {

        margin-bottom:
            0;


        justify-content:
            flex-start;

    }

}

</style>