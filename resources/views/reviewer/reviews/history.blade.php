@extends('reviewer.layouts.app')

@section('title', 'Review History | BMRC Journal')

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
                Review History
            </h1>

            <p class="mb-0">
                Your complete BMRC manuscript reviewing history.
            </p>

        </div>


        @if(isset($reviews) && $reviews->isNotEmpty())

            <span class="badge bg-secondary fs-6">

                {{ $reviews->count() }}

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
            flex-wrap
            justify-content-between
            align-items-center
            gap-2
        "
    >

        <div>

            <i class="bi bi-clock-history me-1"></i>

            Review History

        </div>


        @if(isset($reviews) && $reviews->isNotEmpty())

            <small class="text-muted">

                Total:
                <strong>
                    {{ $reviews->count() }}
                </strong>

            </small>

        @endif

    </div>


    <div class="card-body p-0">


        {{-- =====================================================
            EMPTY STATE
        ====================================================== --}}

        @if(!isset($reviews) || $reviews->isEmpty())

            <div class="text-center py-5 px-3">

                <i
                    class="
                        bi
                        bi-clock-history
                        text-muted
                    "
                    style="font-size:45px;"
                ></i>

                <h5 class="mt-3">
                    No Review History
                </h5>

                <p class="text-muted mb-0">

                    Your review history will appear here
                    after you begin reviewing manuscripts.

                </p>

            </div>


        @else


            {{-- =================================================
                SUMMARY
            ================================================== --}}

            @php

                $totalReviews =
                    $reviews->count();

                $draftReviews =
                    $reviews->where(
                        'status',
                        'draft'
                    )->count();

                $inProgressReviews =
                    $reviews->where(
                        'status',
                        'in_progress'
                    )->count();

                $submittedReviews =
                    $reviews->where(
                        'status',
                        'submitted'
                    )->count();

            @endphp


            <div
                class="
                    p-3
                    border-bottom
                    bg-light
                "
            >

                <div class="row g-2">

                    {{-- Total --}}

                    <div
                        class="
                            col-xl-3
                            col-md-6
                        "
                    >

                        <div
                            class="
                                border
                                rounded
                                bg-white
                                p-3
                                h-100
                            "
                        >

                            <div
                                class="
                                    small
                                    text-muted
                                "
                            >
                                Total Reviews
                            </div>

                            <div
                                class="
                                    fs-4
                                    fw-bold
                                    text-dark
                                "
                            >
                                {{ $totalReviews }}
                            </div>

                        </div>

                    </div>


                    {{-- Draft --}}

                    <div
                        class="
                            col-xl-3
                            col-md-6
                        "
                    >

                        <div
                            class="
                                border
                                rounded
                                bg-white
                                p-3
                                h-100
                            "
                        >

                            <div
                                class="
                                    small
                                    text-muted
                                "
                            >
                                Draft
                            </div>

                            <div
                                class="
                                    fs-4
                                    fw-bold
                                    text-warning
                                "
                            >
                                {{ $draftReviews }}
                            </div>

                        </div>

                    </div>


                    {{-- In Progress --}}

                    <div
                        class="
                            col-xl-3
                            col-md-6
                        "
                    >

                        <div
                            class="
                                border
                                rounded
                                bg-white
                                p-3
                                h-100
                            "
                        >

                            <div
                                class="
                                    small
                                    text-muted
                                "
                            >
                                In Progress
                            </div>

                            <div
                                class="
                                    fs-4
                                    fw-bold
                                    text-primary
                                "
                            >
                                {{ $inProgressReviews }}
                            </div>

                        </div>

                    </div>


                    {{-- Submitted --}}

                    <div
                        class="
                            col-xl-3
                            col-md-6
                        "
                    >

                        <div
                            class="
                                border
                                rounded
                                bg-white
                                p-3
                                h-100
                            "
                        >

                            <div
                                class="
                                    small
                                    text-muted
                                "
                            >
                                Completed
                            </div>

                            <div
                                class="
                                    fs-4
                                    fw-bold
                                    text-success
                                "
                            >
                                {{ $submittedReviews }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                HISTORY TABLE
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
                                style="width:55px;"
                            >
                                #
                            </th>

                            <th>
                                Manuscript
                            </th>

                            <th style="width:140px;">
                                Article Type
                            </th>

                            <th
                                class="text-center"
                                style="width:80px;"
                            >
                                Round
                            </th>

                            <th style="width:145px;">
                                Started
                            </th>

                            <th style="width:145px;">
                                Submitted
                            </th>

                            <th style="width:130px;">
                                Status
                            </th>

                            <th
                                class="text-end"
                                style="width:175px;"
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
                                | Status
                                |--------------------------------------------------------------------------
                                */

                                $status =
                                    strtolower(
                                        (string) (
                                            $review->status
                                            ?? 'draft'
                                        )
                                    );


                                /*
                                |--------------------------------------------------------------------------
                                | Started Date
                                |--------------------------------------------------------------------------
                                */

                                $startedDate = null;

                                if ($review->started_at) {

                                    try {

                                        $startedDate =
                                            $review->started_at
                                            instanceof
                                            \Carbon\CarbonInterface

                                                ? $review->started_at

                                                : \Carbon\Carbon::parse(
                                                    $review->started_at
                                                );

                                    } catch (\Throwable $e) {

                                        $startedDate = null;
                                    }
                                }


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
                                | Review Deadline
                                |--------------------------------------------------------------------------
                                */

                                $deadline =
                                    $invitation?->review_deadline
                                    ?? null;


                                $deadlinePassed = false;


                                if ($deadline) {

                                    try {

                                        $deadlineDate =
                                            $deadline
                                            instanceof
                                            \Carbon\CarbonInterface

                                                ? $deadline

                                                : \Carbon\Carbon::parse(
                                                    $deadline
                                                );


                                        $deadlinePassed =
                                            $deadlineDate
                                                ->copy()
                                                ->endOfDay()
                                                ->isPast();

                                    } catch (\Throwable $e) {

                                        $deadlineDate = null;
                                    }

                                } else {

                                    $deadlineDate = null;
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Evaluation
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

                                {{-- Serial --}}

                                <td
                                    class="
                                        text-center
                                        text-muted
                                    "
                                >

                                    {{ $loop->iteration }}

                                </td>


                                {{-- Manuscript --}}

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

                                        <div
                                            class="
                                                small
                                                mt-1
                                            "
                                        >

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


                                {{-- Article Type --}}

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


                                {{-- Round --}}

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


                                {{-- Started --}}

                                <td>

                                    @if($startedDate)

                                        <div class="small">

                                            <i
                                                class="
                                                    bi
                                                    bi-calendar3
                                                    me-1
                                                "
                                            ></i>

                                            {{
                                                $startedDate
                                                    ->format(
                                                        'd M Y'
                                                    )
                                            }}

                                        </div>

                                        <small class="text-muted">

                                            {{
                                                $startedDate
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


                                {{-- Submitted --}}

                                <td>

                                    @if($submittedDate)

                                        <div
                                            class="
                                                small
                                                fw-semibold
                                                text-success
                                            "
                                        >

                                            <i
                                                class="
                                                    bi
                                                    bi-calendar-check
                                                    me-1
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
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}

                                <td>

                                    @if($status === 'submitted')

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

                                            Completed

                                        </span>


                                    @elseif(
                                        $status === 'in_progress'
                                    )

                                        <span
                                            class="
                                                badge
                                                bg-primary
                                            "
                                        >

                                            <i
                                                class="
                                                    bi
                                                    bi-hourglass-split
                                                    me-1
                                                "
                                            ></i>

                                            In Progress

                                        </span>


                                    @elseif(
                                        $status === 'draft'
                                    )

                                        <span
                                            class="
                                                badge
                                                bg-warning
                                                text-dark
                                            "
                                        >

                                            <i
                                                class="
                                                    bi
                                                    bi-pencil
                                                    me-1
                                                "
                                            ></i>

                                            Draft

                                        </span>


                                    @else

                                        <span
                                            class="
                                                badge
                                                bg-secondary
                                            "
                                        >

                                            {{
                                                ucwords(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $status
                                                    )
                                                )
                                            }}

                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}

                                <td class="text-end">

                                    {{-- Submitted Review --}}

                                    @if($status === 'submitted')

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


                                    {{-- Active Review --}}

                                    @elseif(
                                        $invitation
                                        &&
                                        !$deadlinePassed
                                    )

                                        <a
                                            href="{{ route(
                                                'reviewer.peer-reviews.create',
                                                $invitation->id
                                            ) }}"
                                            class="
                                                btn
                                                btn-sm
                                                btn-primary
                                            "
                                        >

                                            <i
                                                class="
                                                    bi
                                                    bi-pencil-square
                                                    me-1
                                                "
                                            ></i>

                                            Continue Review

                                        </a>


                                    {{-- Deadline Passed --}}

                                    @elseif(
                                        $deadlinePassed
                                    )

                                        <button
                                            type="button"
                                            class="
                                                btn
                                                btn-sm
                                                btn-outline-danger
                                            "
                                            disabled
                                        >

                                            <i
                                                class="
                                                    bi
                                                    bi-clock-history
                                                    me-1
                                                "
                                            ></i>

                                            Expired

                                        </button>


                                    {{-- Invitation Available --}}

                                    @elseif($invitation)

                                        <a
                                            href="{{ route(
                                                'reviewer.invitations.show',
                                                $invitation->id
                                            ) }}"
                                            class="
                                                btn
                                                btn-sm
                                                btn-outline-primary
                                            "
                                        >

                                            <i
                                                class="
                                                    bi
                                                    bi-eye
                                                    me-1
                                                "
                                            ></i>

                                            View

                                        </a>


                                    @else

                                        <span
                                            class="
                                                text-muted
                                                small
                                            "
                                        >

                                            Unavailable

                                        </span>

                                    @endif

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
                                bi-info-circle
                                me-1
                            "
                        ></i>

                        This page contains your complete
                        BMRC peer-review activity.

                    </small>


                    <small class="text-muted">

                        Completed:

                        <strong class="text-success">
                            {{ $submittedReviews }}
                        </strong>

                        &nbsp;|&nbsp;

                        Active:

                        <strong class="text-primary">

                            {{
                                $draftReviews
                                +
                                $inProgressReviews
                            }}

                        </strong>

                    </small>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection