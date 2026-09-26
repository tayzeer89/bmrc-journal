@extends('reviewer.layouts.app')

@section('title', 'Active Reviews | BMRC Journal')

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
                Active Reviews
            </h1>

            <p class="mb-0">
                Manuscripts currently assigned to you
                for peer review.
            </p>

        </div>


        @if(isset($reviews) && $reviews->isNotEmpty())

            <span class="badge bg-primary fs-6">

                {{ $reviews->count() }}

                Active
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

            <i class="bi bi-hourglass-split me-1"></i>

            Active Review Assignments

        </div>


        @if(isset($reviews) && $reviews->isNotEmpty())

            <small class="text-muted">

                {{ $reviews->count() }}
                assignment(s)

            </small>

        @endif

    </div>


    <div class="card-body p-0">


        {{-- =====================================================
            NO ACTIVE REVIEWS
        ====================================================== --}}

        @if(!isset($reviews) || $reviews->isEmpty())

            <div class="text-center py-5 px-3">

                <i
                    class="
                        bi
                        bi-journal-text
                        text-muted
                    "
                    style="font-size:45px;"
                ></i>


                <h5 class="mt-3">

                    No Active Reviews

                </h5>


                <p class="text-muted mb-0">

                    You do not currently have any
                    active manuscript reviews.

                </p>

            </div>


        @else


            {{-- =================================================
                ACTIVE REVIEW TABLE
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
                                style="width:60px;"
                                class="text-center"
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
                                style="width:90px;"
                                class="text-center"
                            >
                                Round
                            </th>

                            <th style="width:145px;">
                                Deadline
                            </th>

                            <th style="width:125px;">
                                Status
                            </th>

                            <th
                                style="width:190px;"
                                class="text-end"
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
                                | Review Status
                                |--------------------------------------------------------------------------
                                */

                                $reviewStatus =
                                    strtolower(
                                        (string) (
                                            $review->status
                                            ?? 'draft'
                                        )
                                    );


                                /*
                                |--------------------------------------------------------------------------
                                | Review Deadline
                                |--------------------------------------------------------------------------
                                */

                                $deadline =
                                    $invitation?->review_deadline
                                    ?? $review->review_deadline
                                    ?? null;


                                /*
                                |--------------------------------------------------------------------------
                                | Deadline Status
                                |--------------------------------------------------------------------------
                                */

                                $deadlinePassed = false;

                                $daysRemaining = null;


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
                                                ->endOfDay()
                                                ->isPast();


                                        if (!$deadlinePassed) {

                                            $daysRemaining =
                                                now()
                                                    ->startOfDay()
                                                    ->diffInDays(
                                                        $deadlineDate
                                                            ->copy()
                                                            ->startOfDay(),
                                                        false
                                                    );
                                        }

                                    } catch (\Throwable $e) {

                                        $deadlineDate = null;
                                    }

                                } else {

                                    $deadlineDate = null;
                                }


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

                            @endphp


                            <tr>

                                {{-- =========================================
                                    SERIAL
                                ========================================== --}}

                                <td class="text-center text-muted">

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

                                        {{ $manuscript?->title ?? 'Untitled Manuscript' }}

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

                                        {{ $review->review_round ?? 1 }}

                                    </span>

                                </td>


                                {{-- =========================================
                                    DEADLINE
                                ========================================== --}}

                                <td>

                                    @if($deadlineDate)

                                        <div
                                            class="
                                                fw-semibold
                                                {{ $deadlinePassed
                                                    ? 'text-danger'
                                                    : 'text-dark'
                                                }}
                                            "
                                        >

                                            <i
                                                class="
                                                    bi
                                                    bi-calendar3
                                                    me-1
                                                "
                                            ></i>

                                            {{
                                                $deadlineDate
                                                    ->format(
                                                        'd M Y'
                                                    )
                                            }}

                                        </div>


                                        @if($deadlinePassed)

                                            <small
                                                class="
                                                    text-danger
                                                    fw-semibold
                                                "
                                            >

                                                Deadline passed

                                            </small>


                                        @elseif(
                                            $daysRemaining !== null
                                            &&
                                            $daysRemaining <= 3
                                        )

                                            <small
                                                class="
                                                    text-warning
                                                    fw-semibold
                                                "
                                            >

                                                {{ $daysRemaining }}

                                                {{
                                                    $daysRemaining === 1
                                                        ? 'day'
                                                        : 'days'
                                                }}

                                                remaining

                                            </small>


                                        @elseif(
                                            $daysRemaining !== null
                                        )

                                            <small class="text-muted">

                                                {{ $daysRemaining }}

                                                {{
                                                    $daysRemaining === 1
                                                        ? 'day'
                                                        : 'days'
                                                }}

                                                remaining

                                            </small>

                                        @endif


                                    @else

                                        <span class="text-muted">

                                            Not specified

                                        </span>

                                    @endif

                                </td>


                                {{-- =========================================
                                    STATUS
                                ========================================== --}}

                                <td>

                                    @if($reviewStatus === 'draft')

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


                                    @elseif(
                                        $reviewStatus === 'in_progress'
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
                                        $reviewStatus === 'submitted'
                                    )

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


                                    @else

                                        <span
                                            class="
                                                badge
                                                bg-info
                                                text-dark
                                            "
                                        >

                                            {{
                                                ucwords(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $reviewStatus
                                                    )
                                                )
                                            }}

                                        </span>

                                    @endif

                                </td>


                                {{-- =========================================
                                    ACTION
                                ========================================== --}}

                                <td class="text-end">

                                    @if($reviewStatus === 'submitted')

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

                                            @if(
                                                $reviewStatus === 'draft'
                                                &&
                                                $review->started_at
                                            )

                                                Continue Review

                                            @else

                                                Start Review

                                            @endif

                                        </a>


                                    @elseif($deadlinePassed)

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

                                            Deadline Passed

                                        </button>


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

                                            View Assignment

                                        </a>


                                    @else

                                        <span class="text-muted small">

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

                        Complete each review before its
                        assigned deadline.

                    </small>


                    <small class="text-muted">

                        Total:

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