@extends('reviewer.layouts.app')

@section('title', 'Review Preview | BMRC Journal')

@section('content')

@php

    $evaluationOptions = config(
        'peer_review.evaluations',
        []
    );

    $assessmentOptions = [
        'agree' => 'Agree',
        'disagree' => 'Disagree',
        'need_modification' => 'Need Modification',
    ];

@endphp


<style>

    .review-preview-wrapper {
        max-width: 1100px;
        margin: 0 auto;
    }

    .preview-header {
        background: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .preview-card {
        background: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .preview-card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 12px 16px;
        font-weight: 700;
    }

    .preview-card-body {
        padding: 18px;
    }

    .preview-label {
        color: #6c757d;
        font-size: .82rem;
        margin-bottom: 3px;
    }

    .preview-value {
        font-weight: 600;
    }

    .assessment-row {
        padding: 10px 0;
        border-bottom: 1px solid #eeeeee;
    }

    .assessment-row:last-child {
        border-bottom: 0;
    }

    .assessment-result {
        font-weight: 600;
    }

    .preview-comment {
        white-space: pre-wrap;
        line-height: 1.7;
    }

    .confidential-box {
        border-left: 4px solid #dc3545;
        background: #fff8f8;
        padding: 15px;
        border-radius: 5px;
    }

    .author-box {
        border-left: 4px solid #0d6efd;
        background: #f8fbff;
        padding: 15px;
        border-radius: 5px;
    }

</style>


<div class="container-fluid py-4">

    <div class="review-preview-wrapper">


        {{-- =====================================================
            PAGE HEADER
        ====================================================== --}}

        <div class="preview-header">

            <div
                class="
                    d-flex
                    flex-wrap
                    justify-content-between
                    align-items-start
                    gap-3
                "
            >

                <div>

                    <h4 class="mb-1">

                        <i
                            class="
                                bi
                                bi-eye
                                me-2
                            "
                        ></i>

                        Peer Review Preview

                    </h4>

                    <p class="text-muted mb-0">

                        Review your assessment before
                        final submission.

                    </p>

                </div>


                @if(isset($peerReview))

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
                                bi-pencil-square
                                me-1
                            "
                        ></i>

                        Draft Review

                    </span>

                @endif

            </div>

        </div>


        {{-- =====================================================
            MANUSCRIPT INFORMATION
        ====================================================== --}}

        @if(isset($blindManuscript))

            <div class="preview-card">

                <div class="preview-card-header">

                    <i
                        class="
                            bi
                            bi-file-earmark-text
                            me-2
                        "
                    ></i>

                    Manuscript Information

                </div>


                <div class="preview-card-body">

                    <div class="row g-4">


                        <div class="col-md-4">

                            <div class="preview-label">
                                Manuscript ID
                            </div>

                            <div
                                class="
                                    preview-value
                                    text-primary
                                "
                            >

                                {{
                                    $blindManuscript[
                                        'manuscript_id'
                                    ]
                                    ?? 'N/A'
                                }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="preview-label">
                                Journal
                            </div>

                            <div class="preview-value">

                                {{
                                    $blindManuscript[
                                        'journal'
                                    ]
                                    ?? 'N/A'
                                }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="preview-label">
                                Article Type
                            </div>

                            <div class="preview-value">

                                {{
                                    $blindManuscript[
                                        'article_type'
                                    ]
                                    ?? 'N/A'
                                }}

                            </div>

                        </div>


                        <div class="col-12">

                            <div class="preview-label">
                                Article Title
                            </div>

                            <h5 class="mb-0">

                                {{
                                    $blindManuscript[
                                        'title'
                                    ]
                                    ?? 'N/A'
                                }}

                            </h5>

                        </div>

                    </div>

                </div>

            </div>

        @endif


        {{-- =====================================================
            REVIEWER DECLARATION
        ====================================================== --}}

        <div class="preview-card">

            <div class="preview-card-header">

                <i
                    class="
                        bi
                        bi-shield-check
                        me-2
                    "
                ></i>

                Reviewer Declaration

            </div>


            <div class="preview-card-body">

                <div class="mb-3">

                    <div class="preview-label">
                        Conflict of Interest
                    </div>

                    <div class="preview-value">

                        @if($peerReview->conflict_of_interest)

                            <span class="text-danger">
                                Yes
                            </span>

                        @else

                            <span class="text-success">
                                No
                            </span>

                        @endif

                    </div>

                </div>


                @if(
                    $peerReview->conflict_of_interest
                    &&
                    !empty($peerReview->conflict_details)
                )

                    <div>

                        <div class="preview-label">
                            Conflict Details
                        </div>

                        <div class="preview-comment">

                            {{
                                $peerReview
                                    ->conflict_details
                            }}

                        </div>

                    </div>

                @endif

            </div>

        </div>


        {{-- =====================================================
            SCIENTIFIC ASSESSMENT
        ====================================================== --}}

        <div class="preview-card">

            <div class="preview-card-header">

                <i
                    class="
                        bi
                        bi-list-check
                        me-2
                    "
                ></i>

                Scientific Assessment

            </div>


            <div class="preview-card-body">

                @foreach($reviewSections as $sectionKey => $section)

                    <h6
                        class="
                            fw-bold
                            mt-3
                            mb-2
                        "
                    >

                        {{ $section['label'] }}

                    </h6>


                    @foreach($section['items'] as $itemKey => $itemLabel)

                        @php

                            $value =
                                $existingAssessments[
                                    $itemKey
                                ]
                                ?? null;

                        @endphp


                        <div class="assessment-row">

                            <div class="row g-2">

                                <div class="col-md-8">

                                    {{ $itemLabel }}

                                </div>


                                <div
                                    class="
                                        col-md-4
                                        text-md-end
                                    "
                                >

                                    @if($value)

                                        <span
                                            class="
                                                badge
                                                {{
                                                    $value === 'agree'
                                                        ? 'bg-success'
                                                        : (
                                                            $value === 'disagree'
                                                                ? 'bg-danger'
                                                                : 'bg-warning text-dark'
                                                        )
                                                }}
                                            "
                                        >

                                            {{
                                                $assessmentOptions[
                                                    $value
                                                ]
                                                ?? ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $value
                                                    )
                                                )
                                            }}

                                        </span>

                                    @else

                                        <span
                                            class="
                                                text-muted
                                                small
                                            "
                                        >
                                            Not answered
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                    @endforeach

                @endforeach

            </div>

        </div>


        {{-- =====================================================
            SPECIFIC SUGGESTIONS
        ====================================================== --}}

        <div class="preview-card">

            <div class="preview-card-header">

                <i
                    class="
                        bi
                        bi-pencil-square
                        me-2
                    "
                ></i>

                Modification and Improvement

            </div>


            <div class="preview-card-body">

                @foreach($reviewSections as $sectionKey => $section)

                    @php

                        $suggestion =
                            $existingSuggestions[
                                $sectionKey
                            ]
                            ?? null;

                    @endphp


                    @if(!empty($suggestion))

                        <div class="mb-4">

                            <div
                                class="
                                    fw-semibold
                                    mb-2
                                "
                            >

                                {{ $section['label'] }}

                            </div>


                            <div class="preview-comment">

                                {{ $suggestion }}

                            </div>

                        </div>

                    @endif

                @endforeach


                @if(
                    empty(
                        array_filter(
                            $existingSuggestions
                            ?? []
                        )
                    )
                )

                    <div class="text-muted">

                        No specific modification
                        suggestions have been entered.

                    </div>

                @endif

            </div>

        </div>


        {{-- =====================================================
            COMMENTS TO AUTHORS
        ====================================================== --}}

        <div class="preview-card">

            <div class="preview-card-header">

                <i
                    class="
                        bi
                        bi-chat-left-text
                        me-2
                    "
                ></i>

                Comments to the Author(s)

            </div>


            <div class="preview-card-body">

                <div class="author-box">

                    @if(
                        !empty(
                            $peerReview
                                ->comments_to_author
                        )
                    )

                        <div class="preview-comment">

                            {{
                                $peerReview
                                    ->comments_to_author
                            }}

                        </div>

                    @else

                        <span class="text-muted">

                            No comments entered.

                        </span>

                    @endif

                </div>

            </div>

        </div>


        {{-- =====================================================
            CONFIDENTIAL COMMENTS
        ====================================================== --}}

        <div class="preview-card">

            <div class="preview-card-header">

                <i
                    class="
                        bi
                        bi-lock
                        me-2
                    "
                ></i>

                Confidential Comments to the Editor

            </div>


            <div class="preview-card-body">

                <div class="confidential-box">

                    <div
                        class="
                            small
                            text-danger
                            fw-semibold
                            mb-2
                        "
                    >

                        <i
                            class="
                                bi
                                bi-lock-fill
                                me-1
                            "
                        ></i>

                        Editorial Office / Editor Only

                    </div>


                    @if(
                        !empty(
                            $peerReview
                                ->confidential_comments_to_editor
                        )
                    )

                        <div class="preview-comment">

                            {{
                                $peerReview
                                    ->confidential_comments_to_editor
                            }}

                        </div>

                    @else

                        <span class="text-muted">

                            No confidential comments entered.

                        </span>

                    @endif

                </div>

            </div>

        </div>


        {{-- =====================================================
            OVERALL EVALUATION
        ====================================================== --}}

        <div class="preview-card">

            <div class="preview-card-header">

                <i
                    class="
                        bi
                        bi-check2-square
                        me-2
                    "
                ></i>

                Overall Evaluation

            </div>


            <div class="preview-card-body">

                @if(
                    !empty(
                        $peerReview
                            ->overall_evaluation
                    )
                )

                    <h5 class="mb-0">

                        <span
                            class="
                                badge
                                bg-primary
                            "
                        >

                            {{
                                $evaluationOptions[
                                    $peerReview
                                        ->overall_evaluation
                                ]
                                ?? ucfirst(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $peerReview
                                            ->overall_evaluation
                                    )
                                )
                            }}

                        </span>

                    </h5>

                @else

                    <div
                        class="
                            alert
                            alert-warning
                            mb-0
                        "
                    >

                        Overall evaluation has not
                        been selected.

                    </div>

                @endif

            </div>

        </div>


        {{-- =====================================================
            FINAL DECLARATION STATUS
        ====================================================== --}}

        <div class="preview-card">

            <div class="preview-card-header">

                <i
                    class="
                        bi
                        bi-person-check
                        me-2
                    "
                ></i>

                Final Reviewer Declaration

            </div>


            <div class="preview-card-body">

                @if($peerReview->reviewer_declaration)

                    <div class="text-success">

                        <i
                            class="
                                bi
                                bi-check-circle-fill
                                me-2
                            "
                        ></i>

                        Reviewer declaration confirmed.

                    </div>

                @else

                    <div class="text-danger">

                        <i
                            class="
                                bi
                                bi-exclamation-circle
                                me-2
                            "
                        ></i>

                        Reviewer declaration has not
                        yet been confirmed.

                    </div>

                @endif

            </div>

        </div>


        {{-- =====================================================
            ACTIONS
        ====================================================== --}}

        <div
            class="
                d-flex
                flex-wrap
                justify-content-between
                align-items-center
                gap-2
                mb-5
            "
        >

            <a
                href="{{ url()->previous() }}"
                class="
                    btn
                    btn-outline-secondary
                "
            >

                <i
                    class="
                        bi
                        bi-arrow-left
                        me-1
                    "
                ></i>

                Back to Review Form

            </a>


            <div class="text-muted small">

                <i
                    class="
                        bi
                        bi-shield-lock
                        me-1
                    "
                ></i>

                BMRC Confidential Peer Review

            </div>

        </div>

    </div>

</div>

@endsection