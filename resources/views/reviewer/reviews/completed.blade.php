@extends('reviewer.layouts.app')

@section('title', 'Completed Reviews | BMRC Journal')

@section('content')

<div class="page-header">

    <div
        class="
            d-flex
            flex-wrap
            justify-content-between
            align-items-center
            gap-2
        "
    >

        <div>

            <h1>
                Completed Reviews
            </h1>

            <p class="mb-0">
                Reviews that you have completed and submitted.
            </p>

        </div>


        @if(isset($reviews) && $reviews->isNotEmpty())

            <span class="badge bg-success fs-6">

                {{ $reviews->count() }}

                Completed
                {{ $reviews->count() === 1 ? 'Review' : 'Reviews' }}

            </span>

        @endif

    </div>

</div>


<div class="reviewer-card">

    <div
        class="
            card-header
            d-flex
            justify-content-between
            align-items-center
        "
    >

        <div>

            <i class="bi bi-check2-circle me-1"></i>

            Completed Reviews

        </div>


        @if(isset($reviews) && $reviews->isNotEmpty())

            <small class="text-muted">

                {{ $reviews->count() }}
                submitted review(s)

            </small>

        @endif

    </div>


    <div class="card-body p-0">


        {{-- =====================================================
            NO COMPLETED REVIEWS
        ====================================================== --}}

        @if(!isset($reviews) || $reviews->isEmpty())

            <div class="text-center py-5 px-3">

                <i
                    class="
                        bi
                        bi-check-circle
                        text-muted
                    "
                    style="font-size:45px;"
                ></i>


                <h5 class="mt-3">
                    No Completed Reviews
                </h5>


                <p class="text-muted mb-0">
                    Completed review records will appear here
                    after final submission.
                </p>

            </div>


        @else


            {{-- =================================================
                COMPLETED REVIEW TABLE
            ================================================== --}}

            <div class="table-responsive">

                <table
                    class="
                        table
                        table-hover
                        align-middle
                        mb-0
                    "
                >

                    <thead class="table-light">

                        <tr>

                            <th
                                class="text-center"
                                style="width:60px;"
                            >
                                #
                            </th>

                            <th>
                                Manuscript
                            </th>

                            <th style="width:150px;">
                                Article Type
                            </th>

                            <th
                                class="text-center"
                                style="width:90px;"
                            >
                                Round
                            </th>

                            <th style="width:160px;">
                                Submitted On
                            </th>

                            <th style="width:120px;">
                                Status
                            </th>

                            <th
                                class="text-end"
                                style="width:160px;"
                            >
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($reviews as $review)

                            @php

                                /*
                                |--------------------------------------------------------------------------
                                | Invitation
                                |--------------------------------------------------------------------------
                                */

                                $invitation =
                                    $review->invitation
                                    ?? $review->reviewerInvitation
                                    ?? null;


                                /*
                                |--------------------------------------------------------------------------
                                | Manuscript
                                |--------------------------------------------------------------------------
                                */

                                $manuscript =
                                    $invitation?->manuscript
                                    ?? $review->manuscript
                                    ?? null;


                                /*
                                |--------------------------------------------------------------------------
                                | Manuscript ID
                                |--------------------------------------------------------------------------
                                */

                                $manuscriptCode =
                                    $manuscript?->manuscript_id
                                    ?? $manuscript?->id
                                    ?? 'N/A';


                                /*
                                |--------------------------------------------------------------------------
                                | Article Type
                                |--------------------------------------------------------------------------
                                */

                                $articleType =
                                    $manuscript
                                        ?->articleType
                                        ?->name
                                    ?? 'N/A';


                                /*
                                |--------------------------------------------------------------------------
                                | Submitted Date
                                |--------------------------------------------------------------------------
                                */

                                $submittedDate = null;

                                if ($review->submitted_at) {

                                    try {

                                        $submittedDate =
                                            $review->submitted_at
                                            instanceof
                                            \Carbon\CarbonInterface

                                                ? $review->submitted_at

                                                : \Carbon\Carbon::parse(
                                                    $review->submitted_at
                                                );

                                    } catch (\Throwable $e) {

                                        $submittedDate = null;
                                    }
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Overall Evaluation
                                |--------------------------------------------------------------------------
                                */

                                $evaluation =
                                    $review->overall_evaluation
                                    ?? null;


                                $evaluationLabel =
                                    $evaluation

                                        ? ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $evaluation
                                            )
                                        )

                                        : null;

                            @endphp


                            <tr>

                                {{-- =========================================
                                    SERIAL
                                ========================================== --}}

                                <td
                                    class="
                                        text-center
                                        text-muted
                                    "
                                >

                                    {{ $loop->iteration }}

                                </td>


                                {{-- =========================================
                                    MANUSCRIPT
                                ========================================== --}}

                                <td>

                                    <div
                                        class="
                                            fw-semibold
                                            text-dark
                                            mb-1
                                        "
                                    >

                                        {{
                                            $manuscript?->title
                                            ?? 'Untitled Manuscript'
                                        }}

                                    </div>


                                    <div
                                        class="
                                            small
                                            text-muted
                                        "
                                    >

                                        <i
                                            class="
                                                bi
                                                bi-file-earmark-text
                                                me-1
                                            "
                                        ></i>

                                        Manuscript ID:

                                        <strong>

                                            {{ $manuscriptCode }}

                                        </strong>

                                    </div>


                                    @if($evaluationLabel)

                                        <div class="small mt-1">

                                            <span class="text-muted">
                                                Evaluation:
                                            </span>

                                            <span
                                                class="
                                                    fw-semibold
                                                    text-primary
                                                "
                                            >

                                                {{ $evaluationLabel }}

                                            </span>

                                        </div>

                                    @endif

                                </td>


                                {{-- =========================================
                                    ARTICLE TYPE
                                ========================================== --}}

                                <td>

                                    <span
                                        class="
                                            badge
                                            bg-light
                                            text-dark
                                            border
                                        "
                                    >

                                        {{ $articleType }}

                                    </span>

                                </td>


                                {{-- =========================================
                                    REVIEW ROUND
                                ========================================== --}}

                                <td class="text-center">

                                    <span
                                        class="
                                            badge
                                            bg-secondary
                                        "
                                    >

                                        {{
                                            $review->review_round
                                            ?? 1
                                        }}

                                    </span>

                                </td>


                                {{-- =========================================
                                    SUBMITTED DATE
                                ========================================== --}}

                                <td>

                                    @if($submittedDate)

                                        <div class="fw-semibold">

                                            <i
                                                class="
                                                    bi
                                                    bi-calendar-check
                                                    me-1
                                                    text-success
                                                "
                                            ></i>

                                            {{
                                                $submittedDate
                                                    ->format(
                                                        'd M Y'
                                                    )
                                            }}

                                        </div>


                                        <small class="text-muted">

                                            {{
                                                $submittedDate
                                                    ->format(
                                                        'h:i A'
                                                    )
                                            }}

                                        </small>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- =========================================
                                    STATUS
                                ========================================== --}}

                                <td>

                                    <span
                                        class="
                                            badge
                                            bg-success
                                        "
                                    >

                                        <i
                                            class="
                                                bi
                                                bi-check-circle
                                                me-1
                                            "
                                        ></i>

                                        Submitted

                                    </span>

                                </td>


                                {{-- =========================================
                                    ACTION
                                ========================================== --}}

                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'reviewer.peer-reviews.show',
                                            $review->id
                                        ) }}"
                                        class="
                                            btn
                                            btn-sm
                                            btn-outline-success
                                        "
                                    >

                                        <i
                                            class="
                                                bi
                                                bi-eye
                                                me-1
                                            "
                                        ></i>

                                        View Review

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                FOOTER
            ================================================== --}}

            <div
                class="
                    px-3
                    py-3
                    border-top
                    bg-light
                "
            >

                <div
                    class="
                        d-flex
                        flex-wrap
                        justify-content-between
                        align-items-center
                        gap-2
                    "
                >

                    <small class="text-muted">

                        <i
                            class="
                                bi
                                bi-shield-check
                                me-1
                            "
                        ></i>

                        Submitted peer reviews are
                        read-only records.

                    </small>


                    <small class="text-muted">

                        Total Completed:

                        <strong>

                            {{ $reviews->count() }}

                        </strong>

                    </small>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection