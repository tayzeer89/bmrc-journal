@extends('reviewer.layouts.app')

@section('title', 'Peer Review | BMRC Journal')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="page-header mb-4">
        <h1>
            <i class="bi bi-journal-check me-2"></i>
            BMRC Peer Review Form
        </h1>

        <p class="text-muted mb-0">
            Complete the scientific assessment of the manuscript.
        </p>
    </div>


    {{-- =========================================================
        BLIND REVIEW NOTICE
    ========================================================== --}}

    <div class="alert alert-warning">
        <div class="d-flex">

            <i
                class="bi bi-shield-lock-fill me-3"
                style="font-size:26px;"
            ></i>

            <div>
                <strong>Blind Peer Review</strong>

                <div class="mt-1">
                    Author names, affiliations, contact details,
                    ORCID information, corresponding author details
                    and other identifying information are intentionally
                    hidden from this reviewer workspace.
                </div>
            </div>

        </div>
    </div>


    {{-- =========================================================
        MANUSCRIPT INFORMATION
    ========================================================== --}}

    <div class="reviewer-card mb-4">

        <div class="card-header">
            <i class="bi bi-file-earmark-text me-2"></i>
            Manuscript Information
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">
                    <small class="text-muted d-block">
                        Manuscript ID
                    </small>

                    <strong class="text-primary">
                        {{ $blindManuscript['manuscript_id'] }}
                    </strong>
                </div>


                <div class="col-md-4">
                    <small class="text-muted d-block">
                        Journal
                    </small>

                    <strong>
                        {{ $blindManuscript['journal'] }}
                    </strong>
                </div>


                <div class="col-md-4">
                    <small class="text-muted d-block">
                        Article Type
                    </small>

                    <strong>
                        {{ $blindManuscript['article_type'] ?? 'N/A' }}
                    </strong>
                </div>


                <div class="col-12">
                    <small class="text-muted d-block mb-1">
                        Article Title
                    </small>

                    <h5>
                        {{ $blindManuscript['title'] }}
                    </h5>
                </div>


                <div class="col-md-6">
                    <small class="text-muted d-block">
                        Review Round
                    </small>

                    <strong>
                        {{ $peerReview->review_round }}
                    </strong>
                </div>


                <div class="col-md-6">
                    <small class="text-muted d-block">
                        Review Deadline
                    </small>

                    <strong>
                        {{ $invitation->review_deadline?->format('d M Y') ?? 'Not specified' }}
                    </strong>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        ABSTRACT
    ========================================================== --}}

    @if(!empty($blindManuscript['abstract']))

        <div class="reviewer-card mb-4">

            <div class="card-header">
                <i class="bi bi-card-text me-2"></i>
                Abstract
            </div>

            <div class="card-body">

                <div style="line-height:1.8; text-align:justify;">
                    {!! $blindManuscript['abstract'] !!}
                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        SCIENTIFIC INFORMATION
    ========================================================== --}}

    @php
        $scientificFields = [
            'background'  => 'Background',
            'objectives'  => 'Objectives',
            'methods'     => 'Methods',
            'results'     => 'Results',
            'conclusions' => 'Conclusions',
        ];
    @endphp


    @foreach($scientificFields as $field => $label)

        @if(!empty($blindManuscript[$field]))

            <div class="reviewer-card mb-4">

                <div class="card-header">
                    {{ $label }}
                </div>

                <div class="card-body">

                    <div style="line-height:1.8;">
                        {!! $blindManuscript[$field] !!}
                    </div>

                </div>

            </div>

        @endif

    @endforeach


    {{-- =========================================================
        REVIEW FORM
    ========================================================== --}}

    <form
        method="POST"
        action="{{ route('reviewer.peer-reviews.draft', $peerReview->id) }}"
        id="peerReviewForm"
    >

        @csrf
        @method('PUT')


        {{-- =====================================================
            REVIEWER DECLARATION
        ====================================================== --}}

        <div class="reviewer-card mb-4">

            <div class="card-header">
                <i class="bi bi-shield-check me-2"></i>
                Reviewer Declaration
            </div>

            <div class="card-body">


                {{-- Conflict of Interest --}}

                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Do you have any conflict of interest
                        related to this manuscript?
                    </label>

                    @php
                        $currentConflict = old(
                            'conflict_of_interest',
                            $peerReview->conflict_of_interest === null
                                ? '0'
                                : ($peerReview->conflict_of_interest ? '1' : '0')
                        );
                    @endphp

                    <div>

                        <div class="form-check form-check-inline">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="conflict_of_interest"
                                id="conflict_no"
                                value="0"
                                {{ (string) $currentConflict === '0' ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label"
                                for="conflict_no"
                            >
                                No
                            </label>

                        </div>


                        <div class="form-check form-check-inline">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="conflict_of_interest"
                                id="conflict_yes"
                                value="1"
                                {{ (string) $currentConflict === '1' ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label"
                                for="conflict_yes"
                            >
                                Yes
                            </label>

                        </div>

                    </div>

                </div>


                {{-- Conflict Details --}}

                <div class="mb-4">

                    <label
                        for="conflict_details"
                        class="form-label"
                    >
                        Conflict Details
                    </label>

                    <textarea
                        name="conflict_details"
                        id="conflict_details"
                        class="form-control"
                        rows="3"
                        maxlength="5000"
                        placeholder="If yes, please describe the conflict..."
                    >{{ old('conflict_details', $peerReview->conflict_details) }}</textarea>

                </div>


                {{-- Confidentiality --}}

                <div class="form-check mb-3">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="confidentiality_confirmed"
                        value="1"
                        id="confidentiality_confirmed"
                        {{ old(
                            'confidentiality_confirmed',
                            $peerReview->confidentiality_confirmed
                        ) ? 'checked' : '' }}
                    >

                    <label
                        class="form-check-label"
                        for="confidentiality_confirmed"
                    >
                        I agree to maintain the confidentiality
                        of this manuscript and its contents.
                    </label>

                </div>

            </div>

        </div>


        {{-- =====================================================
            BMRC SCIENTIFIC ASSESSMENT
        ====================================================== --}}

        <div class="reviewer-card mb-4">

            <div class="card-header">
                <i class="bi bi-list-check me-2"></i>
                Scientific Assessment
            </div>

            <div class="card-body">

                <p class="text-muted mb-0">
                    Please assess each item by selecting
                    Agree, Disagree or Need Modification.
                </p>

            </div>


            <div class="table-responsive">

                <table class="table table-bordered align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th style="width:16%;">
                                Section
                            </th>

                            <th>
                                Assessment Item
                            </th>

                            <th
                                class="text-center"
                                style="width:11%;"
                            >
                                Agree
                            </th>

                            <th
                                class="text-center"
                                style="width:11%;"
                            >
                                Disagree
                            </th>

                            <th
                                class="text-center"
                                style="width:16%;"
                            >
                                Need Modification
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($reviewSections as $sectionKey => $section)

                            @php
                                $rowCount = count($section['items']);
                            @endphp


                            @foreach($section['items'] as $itemKey => $itemLabel)

                                <tr>

                                    @if($loop->first)

                                        <td
                                            rowspan="{{ $rowCount }}"
                                            class="fw-semibold bg-light"
                                        >
                                            {{ $section['label'] }}
                                        </td>

                                    @endif


                                    <td>
                                        {{ $itemLabel }}
                                    </td>


                                    @foreach(['agree', 'disagree', 'need_modification'] as $option)

                                        @php
                                            $assessmentValue = old(
                                                'assessment.' . $itemKey,
                                                $existingAssessments[$itemKey] ?? null
                                            );
                                        @endphp

                                        <td class="text-center">

                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="assessment[{{ $itemKey }}]"
                                                value="{{ $option }}"
                                                {{ $assessmentValue === $option ? 'checked' : '' }}
                                            >

                                        </td>

                                    @endforeach

                                </tr>

                            @endforeach

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
            MODIFICATION / IMPROVEMENT
        ====================================================== --}}

        <div class="reviewer-card mb-4">

            <div class="card-header">
                <i class="bi bi-pencil-square me-2"></i>
                Modification and Improvement: Specific Suggestion
            </div>

            <div class="card-body">

                @foreach($reviewSections as $sectionKey => $section)

                    <div class="mb-4">

                        <label
                            for="suggestion_{{ $sectionKey }}"
                            class="form-label fw-semibold"
                        >
                            {{ $section['label'] }}
                        </label>

                        <textarea
                            name="suggestions[{{ $sectionKey }}]"
                            id="suggestion_{{ $sectionKey }}"
                            class="form-control"
                            rows="4"
                            maxlength="10000"
                            placeholder="Enter specific suggestions for {{ strtolower($section['label']) }}..."
                        >{{ old(
                            'suggestions.' . $sectionKey,
                            $existingSuggestions[$sectionKey] ?? ''
                        ) }}</textarea>

                    </div>

                @endforeach

            </div>

        </div>


        {{-- =====================================================
            COMMENTS TO AUTHOR
        ====================================================== --}}

        <div class="reviewer-card mb-4">

            <div class="card-header">
                <i class="bi bi-chat-left-text me-2"></i>
                Comments to the Author(s)
            </div>

            <div class="card-body">

                <div class="alert alert-info">

                    <i class="bi bi-info-circle me-1"></i>

                    These comments may be communicated to
                    the author(s). Do not include your name,
                    institution, email address, or other
                    identifying information.

                </div>

                <textarea
                    name="comments_to_author"
                    class="form-control"
                    rows="8"
                    maxlength="20000"
                    placeholder="Enter comments intended for the author(s)..."
                >{{ old(
                    'comments_to_author',
                    $peerReview->comments_to_author
                ) }}</textarea>

            </div>

        </div>


        {{-- =====================================================
            CONFIDENTIAL COMMENTS
        ====================================================== --}}

        <div class="reviewer-card mb-4">

            <div class="card-header">
                <i class="bi bi-lock me-2"></i>
                Confidential Comments to the Editor
            </div>

            <div class="card-body">

                <p class="text-muted">
                    These comments are for the Editorial
                    Office / Editor only and will not be
                    shown to the author.
                </p>

                <textarea
                    name="confidential_comments_to_editor"
                    class="form-control"
                    rows="6"
                    maxlength="20000"
                    placeholder="Enter confidential comments..."
                >{{ old(
                    'confidential_comments_to_editor',
                    $peerReview->confidential_comments_to_editor
                ) }}</textarea>

            </div>

        </div>


        {{-- =====================================================
            OVERALL EVALUATION
        ====================================================== --}}

        <div class="reviewer-card mb-4">

            <div class="card-header">
                <i class="bi bi-check2-square me-2"></i>
                Overall Evaluation
            </div>

            <div class="card-body">

                @php
                    $evaluationOptions = config(
                        'peer_review.evaluations',
                        []
                    );
                @endphp


                @if(!empty($evaluationOptions))

                    @foreach($evaluationOptions as $value => $label)

                        <div class="form-check mb-3">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="overall_evaluation"
                                value="{{ $value }}"
                                id="evaluation_{{ $value }}"
                                {{ old(
                                    'overall_evaluation',
                                    $peerReview->overall_evaluation
                                ) === $value ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label"
                                for="evaluation_{{ $value }}"
                            >
                                {{ $label }}
                            </label>

                        </div>

                    @endforeach

                @else

                    <div class="alert alert-warning mb-0">

                        <i class="bi bi-exclamation-triangle me-1"></i>

                        Overall evaluation options are not configured.

                    </div>

                @endif

            </div>

        </div>


        {{-- =====================================================
            FINAL DECLARATION
        ====================================================== --}}

        <div class="reviewer-card mb-4">

            <div class="card-header">
                <i class="bi bi-person-check me-2"></i>
                Reviewer Declaration
            </div>

            <div class="card-body">

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="reviewer_declaration"
                        value="1"
                        id="reviewer_declaration"
                        {{ old(
                            'reviewer_declaration',
                            $peerReview->reviewer_declaration
                        ) ? 'checked' : '' }}
                    >

                    <label
                        class="form-check-label"
                        for="reviewer_declaration"
                    >
                        I confirm that I have completed this
                        review independently and that this
                        review represents my scientific
                        assessment of the manuscript.
                    </label>

                </div>

            </div>

        </div>


        {{-- =====================================================
            ACTION BUTTONS
        ====================================================== --}}

        <div
            class="
                d-flex
                flex-wrap
                gap-2
                justify-content-between
                mb-5
            "
        >

            <a
                href="{{ route(
                    'reviewer.invitations.show',
                    $invitation->id
                ) }}"
                class="btn btn-outline-secondary"
            >

                <i class="bi bi-arrow-left me-1"></i>
                Back

            </a>


            <div class="d-flex flex-wrap gap-2">

                {{-- Save Draft --}}

                <button
                    type="submit"
                    class="btn btn-outline-primary"
                >
                    <i class="bi bi-save me-1"></i>
                    Save Draft
                </button>


                {{-- Preview --}}

                <a
                    href="{{ route(
                        'reviewer.peer-reviews.preview',
                        $peerReview->id
                    ) }}"
                    class="btn btn-info"
                >
                    <i class="bi bi-eye me-1"></i>
                    Preview
                </a>


                {{-- Final Submit --}}

                <button
                    type="submit"
                    formaction="{{ route(
                        'reviewer.peer-reviews.submit',
                        $peerReview->id
                    ) }}"
                    formmethod="POST"
                    class="btn btn-success"
                    onclick="return confirm(
                        'Are you sure you want to submit the final peer review? After submission you will not be able to edit it.'
                    );"
                >
                    <i class="bi bi-send-check me-1"></i>
                    Submit Final Review
                </button>

            </div>

        </div>

    </form>

</div>

@endsection