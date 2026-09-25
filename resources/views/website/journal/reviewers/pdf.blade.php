<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>BMRC Reviewer Directory</title>

    <style>

        /* ================================================================
           PAGE
        ================================================================ */

        @page {
            size: A4 portrait;
            margin: 10mm 9mm 12mm 9mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;

            font-family: DejaVu Sans, sans-serif;

            font-size: 7.5px;
            line-height: 1.25;

            color: #222222;
        }


        /* ================================================================
           HEADER
        ================================================================ */

        .pdf-header {
            text-align: center;

            padding-bottom: 6px;
            margin-bottom: 6px;

            border-bottom: 2px solid #198754;
        }

        .pdf-title {
            margin: 0;

            font-size: 16px;
            font-weight: bold;

            color: #146c43;
        }

        .pdf-subtitle {
            margin-top: 2px;

            font-size: 9px;
            font-weight: bold;

            color: #444444;
        }

        .pdf-description {
            margin-top: 1px;

            font-size: 7px;

            color: #777777;
        }


        /* ================================================================
           SUMMARY
        ================================================================ */

        .summary-table {
            width: 100%;

            border-collapse: collapse;

            margin-bottom: 6px;
        }

        .summary-table td {
            width: 33.333%;

            padding: 4px;

            border: 1px solid #dddddd;

            text-align: center;
        }

        .summary-number {
            font-size: 11px;
            font-weight: bold;

            color: #198754;
        }

        .summary-label {
            font-size: 6px;

            color: #666666;

            text-transform: uppercase;
        }


        /* ================================================================
           FILTER BOX
        ================================================================ */

        .filter-box {
            margin-bottom: 6px;

            padding: 4px 6px;

            border: 1px solid #dddddd;

            background: #f8f8f8;

            color: #555555;

            font-size: 6.5px;
        }

        .filter-label {
            font-weight: bold;

            color: #333333;
        }

        .filter-item {
            margin-right: 8px;
        }


        /* ================================================================
           TWO COLUMN LAYOUT
        ================================================================

           IMPORTANT:

           We DO NOT make the whole directory one large table row.

           Instead, each row contains:
               one speciality on left
               one speciality on right

           DomPDF can then naturally move the next row to the next page.
        ================================================================ */

        .directory-table {
            width: 100%;

            border-collapse: separate;

            border-spacing: 0;

            table-layout: fixed;
        }

        .directory-row {
            page-break-inside: avoid;
        }

        .directory-column {
            width: 50%;

            vertical-align: top;
        }

        .directory-column-left {
            padding-right: 4px;
        }

        .directory-column-right {
            padding-left: 4px;
        }


        /* ================================================================
           SPECIALITY
        ================================================================ */

        .speciality-block {
            width: 100%;

            margin: 0 0 6px 0;
        }

        .speciality-heading {
            padding: 4px 5px;

            margin: 0 0 2px 0;

            background: #198754;

            color: #ffffff;

            font-size: 8.5px;
            font-weight: bold;
        }

        .speciality-count {
            float: right;

            font-size: 5.8px;

            font-weight: normal;
        }


        /* ================================================================
           NATIONAL / INTERNATIONAL
        ================================================================ */

        .reviewer-type {
            margin: 2px 0;

            padding: 2px 4px;

            background: #eef7f2;

            border-left: 2px solid #198754;

            color: #146c43;

            font-size: 6.8px;
            font-weight: bold;
        }

        .reviewer-type.international {
            background: #f1f3f5;

            border-left-color: #6c757d;

            color: #495057;
        }

        .type-count {
            float: right;

            font-size: 5.7px;

            font-weight: normal;
        }


        /* ================================================================
           DESIGNATION
        ================================================================ */

        .designation-group {
            margin: 0 0 3px 0;
        }

        .designation-heading {
            padding: 2px 4px;

            background: #fafafa;

            border-bottom: 1px solid #dddddd;

            color: #555555;

            font-size: 6.5px;
            font-weight: bold;
        }

        .designation-count {
            float: right;

            color: #888888;

            font-size: 5.6px;

            font-weight: normal;
        }


        /* ================================================================
           REVIEWER
        ================================================================ */

        .reviewer {
            padding: 3px 4px;

            border-bottom: 1px dotted #dddddd;

            page-break-inside: avoid;
        }

        .reviewer-name {
            font-size: 7.2px;
            font-weight: bold;

            color: #222222;
        }

        .reviewer-designation {
            margin-top: 1px;

            font-size: 6.1px;

            color: #198754;
        }

        .reviewer-affiliation {
            margin-top: 1px;

            font-size: 5.9px;
            line-height: 1.20;

            color: #666666;
        }

        .reviewer-country {
            margin-top: 1px;

            font-size: 5.7px;

            color: #888888;
        }


        /* ================================================================
           LARGE SPECIALITY
        ================================================================ */

        .large-speciality {
            page-break-inside: auto;
        }


        /* ================================================================
           EMPTY
        ================================================================ */

        .empty-message {
            padding: 15px;

            border: 1px solid #dddddd;

            text-align: center;

            color: #777777;
        }


        /* ================================================================
           FOOTER
        ================================================================ */

        .pdf-footer {
            margin-top: 6px;

            padding-top: 4px;

            border-top: 1px solid #dddddd;

            text-align: center;

            color: #888888;

            font-size: 5.5px;
        }

    </style>

</head>


<body>


{{-- =====================================================================
     HEADER
===================================================================== --}}

<div class="pdf-header">

    <div class="pdf-title">
        Reviewer Directory
    </div>

    <div class="pdf-subtitle">
        Bangladesh Medical Research Council Bulletin
    </div>

    <div class="pdf-description">
        Bangladesh Medical Research Council (BMRC)
    </div>

</div>



{{-- =====================================================================
     SUMMARY
===================================================================== --}}

<table class="summary-table">

    <tr>

        <td>

            <div class="summary-number">
                {{ $totalReviewers }}
            </div>

            <div class="summary-label">
                Total Reviewers
            </div>

        </td>


        <td>

            <div class="summary-number">
                {{ $nationalCount }}
            </div>

            <div class="summary-label">
                National
            </div>

        </td>


        <td>

            <div class="summary-number">
                {{ $internationalCount }}
            </div>

            <div class="summary-label">
                International
            </div>

        </td>

    </tr>

</table>



{{-- =====================================================================
     FILTERS
===================================================================== --}}

@if(
    !empty($filters['search'])
    ||
    !empty($filters['speciality'])
    ||
    !empty($filters['reviewer_type'])
)

    <div class="filter-box">

        <span class="filter-label">
            Applied Filters:
        </span>


        @if(!empty($filters['search']))

            <span class="filter-item">

                Search:

                <strong>
                    {{ $filters['search'] }}
                </strong>

            </span>

        @endif


        @if(!empty($filters['speciality']))

            <span class="filter-item">

                Speciality:

                <strong>
                    {{ $filters['speciality'] }}
                </strong>

            </span>

        @endif


        @if(!empty($filters['reviewer_type']))

            <span class="filter-item">

                Reviewer Type:

                <strong>
                    {{ ucfirst($filters['reviewer_type']) }}
                </strong>

            </span>

        @endif

    </div>

@endif



{{-- =====================================================================
     PREPARE DATA
===================================================================== --}}

@php

    /*
    |--------------------------------------------------------------------------
    | Designation Priority
    |--------------------------------------------------------------------------
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
    | Convert Collection to Sequential Array
    |--------------------------------------------------------------------------
    */

    $specialityBlocks = [];


    foreach (
        $reviewersBySpeciality
        as $speciality => $specialityReviewers
    ) {

        $specialityBlocks[] = [

            'speciality' =>
                $speciality,

            'reviewers' =>
                $specialityReviewers,

            'count' =>
                $specialityReviewers->count(),

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | Separate Very Large Specialities
    |--------------------------------------------------------------------------
    |
    | Your "Other / General" contains many reviewers.
    |
    | A very large speciality should not be paired with another very
    | large speciality in the same row.
    |--------------------------------------------------------------------------
    */

    $normalBlocks = [];

    $largeBlocks = [];


    foreach ($specialityBlocks as $block) {

        if ($block['count'] > 14) {

            $largeBlocks[] = $block;

        } else {

            $normalBlocks[] = $block;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Balance Normal Specialities Into Left / Right Columns
    |--------------------------------------------------------------------------
    |
    | Instead of:
    |
    | first half  -> left
    | second half -> right
    |
    | we calculate approximate weight.
    |--------------------------------------------------------------------------
    */

    $leftBlocks = [];

    $rightBlocks = [];

    $leftWeight = 0;

    $rightWeight = 0;


    foreach ($normalBlocks as $block) {

        /*
         * Each speciality has heading overhead.
         */

        $weight =
            $block['count']
            + 2;


        if ($leftWeight <= $rightWeight) {

            $leftBlocks[] = $block;

            $leftWeight += $weight;

        } else {

            $rightBlocks[] = $block;

            $rightWeight += $weight;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Determine Number of Rows
    |--------------------------------------------------------------------------
    */

    $rowCount = max(
        count($leftBlocks),
        count($rightBlocks)
    );

@endphp



{{-- =====================================================================
     DIRECTORY
===================================================================== --}}

@if($totalReviewers === 0)


    <div class="empty-message">

        No approved reviewers were found
        for the selected criteria.

    </div>


@else


    {{-- =================================================================
         NORMAL SPECIALITIES - TWO COLUMNS
    ================================================================== --}}

    @if($rowCount > 0)

        <table class="directory-table">

            <tbody>


                @for(
                    $rowIndex = 0;
                    $rowIndex < $rowCount;
                    $rowIndex++
                )

                    @php

                        $leftBlock =
                            $leftBlocks[$rowIndex]
                            ?? null;


                        $rightBlock =
                            $rightBlocks[$rowIndex]
                            ?? null;

                    @endphp


                    <tr class="directory-row">


                        {{-- =================================================
                             LEFT COLUMN
                        ================================================== --}}

                        <td
                            class="
                                directory-column
                                directory-column-left
                            "
                        >

                            @if($leftBlock)

                                @include(
                                    'website.journal.reviewers.partials.pdf-speciality',
                                    [
                                        'speciality'
                                            => $leftBlock['speciality'],

                                        'specialityReviewers'
                                            => $leftBlock['reviewers'],

                                        'designationOrder'
                                            => $designationOrder,
                                    ]
                                )

                            @endif

                        </td>



                        {{-- =================================================
                             RIGHT COLUMN
                        ================================================== --}}

                        <td
                            class="
                                directory-column
                                directory-column-right
                            "
                        >

                            @if($rightBlock)

                                @include(
                                    'website.journal.reviewers.partials.pdf-speciality',
                                    [
                                        'speciality'
                                            => $rightBlock['speciality'],

                                        'specialityReviewers'
                                            => $rightBlock['reviewers'],

                                        'designationOrder'
                                            => $designationOrder,
                                    ]
                                )

                            @endif

                        </td>


                    </tr>


                @endfor


            </tbody>

        </table>

    @endif



    {{-- =================================================================
         LARGE SPECIALITIES
    ================================================================== --}}

    @foreach($largeBlocks as $largeBlock)


        <table class="directory-table">

            <tbody>

                <tr>


                    {{-- =====================================================
                         LEFT HALF
                    ====================================================== --}}

                    <td
                        class="
                            directory-column
                            directory-column-left
                        "
                    >

                        @php

                            /*
                            |--------------------------------------------------------------------------
                            | Split large speciality reviewers into two halves
                            |--------------------------------------------------------------------------
                            */

                            $largeReviewers =
                                $largeBlock['reviewers']
                                    ->values();


                            $largeTotal =
                                $largeReviewers
                                    ->count();


                            $largeHalf =
                                (int) ceil(
                                    $largeTotal / 2
                                );


                            $largeLeft =
                                $largeReviewers
                                    ->slice(
                                        0,
                                        $largeHalf
                                    )
                                    ->values();


                            $largeRight =
                                $largeReviewers
                                    ->slice(
                                        $largeHalf
                                    )
                                    ->values();

                        @endphp


                        @include(
                            'website.journal.reviewers.partials.pdf-speciality',
                            [
                                'speciality'
                                    => $largeBlock['speciality'],

                                'specialityReviewers'
                                    => $largeLeft,

                                'designationOrder'
                                    => $designationOrder,

                                'showTotal'
                                    => $largeTotal,
                            ]
                        )

                    </td>



                    {{-- =====================================================
                         RIGHT HALF
                    ====================================================== --}}

                    <td
                        class="
                            directory-column
                            directory-column-right
                        "
                    >

                        @if($largeRight->isNotEmpty())

                            @include(
                                'website.journal.reviewers.partials.pdf-speciality',
                                [
                                    'speciality'
                                        => $largeBlock['speciality']
                                        . ' (Continued)',

                                    'specialityReviewers'
                                        => $largeRight,

                                    'designationOrder'
                                        => $designationOrder,

                                    'showTotal'
                                        => $largeTotal,
                                ]
                            )

                        @endif

                    </td>


                </tr>

            </tbody>

        </table>


    @endforeach


@endif



{{-- =====================================================================
     FOOTER
===================================================================== --}}

<div class="pdf-footer">

    BMRC Bulletin Reviewer Directory

    &nbsp; | &nbsp;

    Bangladesh Medical Research Council

    &nbsp; | &nbsp;

    Generated:

    {{ now()->format('d M Y') }}

</div>


</body>

</html>