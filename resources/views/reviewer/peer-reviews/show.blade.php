@extends('reviewer.layouts.app')

@section('title', 'Submitted Peer Review | BMRC Journal')

@section('content')

@php
    $assessmentLabels = [
        'agree' => 'Agree',
        'disagree' => 'Disagree',
        'need_modification' => 'Need Modification',
    ];

    $evaluationOptions = config('peer_review.evaluations', []);

    $status = strtolower((string) ($review->status ?? 'submitted'));

    $submittedAt = $review->submitted_at ?? null;

    $assessmentMap = collect($review->assessments ?? [])
        ->pluck('assessment', 'item_key');

    $suggestionMap = collect($review->suggestions ?? [])
        ->pluck('suggestion', 'section');
@endphp

<style>
    .bmrc-show-wrap {
        max-width: 1180px;
        margin: 0 auto;
    }

    .bmrc-show-header {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .bmrc-show-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #212529;
    }

    .bmrc-manuscript-title {
        color: #495057;
        margin-top: 6px;
        line-height: 1.5;
    }

    .bmrc-meta {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 10px 12px;
        height: 100%;
    }

    .bmrc-meta-label {
        display: block;
        color: #6c757d;
        font-size: .76rem;
        margin-bottom: 3px;
        text-transform: uppercase;
        letter-spacing: .02em;
    }

    .bmrc-meta-value {
        font-weight: 600;
        color: #212529;
        word-break: break-word;
    }

    .bmrc-show-card {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        margin-bottom: 16px;
        overflow: hidden;
    }

    .bmrc-show-card-header {
        padding: 12px 16px;
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        font-weight: 700;
    }

    .bmrc-show-card-body {
        padding: 16px;
    }

    .bmrc-assessment-section-title {
        font-size: .8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
        color: #495057;
        margin: 18px 0 8px;
    }

    .bmrc-assessment-section-title:first-child {
        margin-top: 0;
    }

    .bmrc-assessment-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 175px;
        gap: 15px;
        align-items: center;
        padding: 11px 0;
        border-bottom: 1px solid #edf0f2;
    }

    .bmrc-assessment-row:last-child {
        border-bottom: 0;
    }

    .bmrc-response-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 13px 14px;
        white-space: pre-wrap;
        line-height: 1.65;
        overflow-wrap: anywhere;
    }

    .bmrc-empty {
        color: #6c757d;
        font-style: italic;
    }

    .bmrc-suggestion-block + .bmrc-suggestion-block {
        margin-top: 16px;
    }

    .bmrc-suggestion-title {
        font-weight: 600;
        margin-bottom: 6px;
    }

    @media (max-width: 767.98px) {
        .bmrc-assessment-row {
            grid-template-columns: 1fr;
            gap: 7px;
        }
    }

    @media print {
        .bmrc-no-print {
            display: none !important;
        }

        .bmrc-show-wrap {
            max-width: none;
        }

        .bmrc-show-card,
        .bmrc-show-header {
            break-inside: avoid;
            box-shadow: none !important;
        }
    }
</style>

<div class="container-fluid py-3">
    <div class="bmrc-show-wrap">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                <i class="bi bi-info-circle-fill me-2"></i>
                {{ session('info') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="bmrc-show-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div class="flex-grow-1">
                    <div class="bmrc-show-title">
                        <i class="bi bi-check2-circle me-2 text-success"></i>
                        Submitted Peer Review
                    </div>

                    <div class="bmrc-manuscript-title">
                        {{ $blindManuscript['title'] ?? 'Untitled Manuscript' }}
                    </div>
                </div>

                <div class="d-flex gap-2 bmrc-no-print">
                    <a
                        href="{{ route('reviewer.invitations.show', $invitation->id) }}"
                        class="btn btn-sm btn-outline-secondary"
                    >
                        <i class="bi bi-arrow-left me-1"></i>
                        Back
                    </a>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-primary"
                        onclick="window.print()"
                    >
                        <i class="bi bi-printer me-1"></i>
                        Print
                    </button>
                </div>
            </div>

            <div class="row g-2 mt-3">
                <div class="col-md-3 col-sm-6">
                    <div class="bmrc-meta">
                        <span class="bmrc-meta-label">Manuscript ID</span>
                        <span class="bmrc-meta-value">
                            {{ $blindManuscript['manuscript_id'] ?? 'N/A' }}
                        </span>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="bmrc-meta">
                        <span class="bmrc-meta-label">Journal</span>
                        <span class="bmrc-meta-value">
                            {{ $blindManuscript['journal'] ?? 'BMRC Bulletin' }}
                        </span>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="bmrc-meta">
                        <span class="bmrc-meta-label">Article Type</span>
                        <span class="bmrc-meta-value">
                            {{ $blindManuscript['article_type'] ?? 'N/A' }}
                        </span>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="bmrc-meta">
                        <span class="bmrc-meta-label">Review Round</span>
                        <span class="bmrc-meta-value">
                            {{ $peerReview->review_round ?? $review->review_round ?? 1 }}
                        </span>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="bmrc-meta">
                        <span class="bmrc-meta-label">Status</span>
                        <span class="badge bg-success">
                            {{ ucfirst($status) }}
                        </span>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="bmrc-meta">
                        <span class="bmrc-meta-label">Submitted</span>
                        <span class="bmrc-meta-value">
                            @if($submittedAt)
                                {{ $submittedAt instanceof \Carbon\CarbonInterface
                                    ? $submittedAt->format('d M Y, h:i A')
                                    : \Carbon\Carbon::parse($submittedAt)->format('d M Y, h:i A') }}
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Blind Review Notice --}}
        <div class="alert alert-warning">
            <i class="bi bi-shield-lock-fill me-2"></i>
            <strong>Blind Peer Review:</strong>
            This page contains reviewer-safe manuscript information only.
            Review content remains confidential.
        </div>

        {{-- Reviewer Declaration --}}
        <div class="bmrc-show-card">
            <div class="bmrc-show-card-header">
                <i class="bi bi-shield-check me-2"></i>
                Reviewer Declaration
            </div>

            <div class="bmrc-show-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Conflict of Interest</strong>
                        <div class="mt-1">
                            @if($review->conflict_of_interest)
                                <span class="badge bg-warning text-dark">Yes</span>
                            @else
                                <span class="badge bg-success">No</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <strong>Confidentiality Confirmed</strong>
                        <div class="mt-1">
                            @if($review->confidentiality_confirmed)
                                <span class="badge bg-success">Confirmed</span>
                            @else
                                <span class="badge bg-secondary">Not Confirmed</span>
                            @endif
                        </div>
                    </div>

                    @if($review->conflict_of_interest && !empty($review->conflict_details))
                        <div class="col-12">
                            <strong>Conflict Details</strong>
                            <div class="bmrc-response-box mt-2">{{ $review->conflict_details }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Scientific Assessment --}}
        <div class="bmrc-show-card">
            <div class="bmrc-show-card-header">
                <i class="bi bi-list-check me-2"></i>
                Scientific Assessment
            </div>

            <div class="bmrc-show-card-body">
                @forelse($reviewSections as $sectionKey => $section)
                    <div class="bmrc-assessment-section-title">
                        {{ $section['label'] ?? ucfirst(str_replace('_', ' ', $sectionKey)) }}
                    </div>

                    @foreach(($section['items'] ?? []) as $itemKey => $itemLabel)
                        @php
                            $value = $assessmentMap->get($itemKey);
                        @endphp

                        <div class="bmrc-assessment-row">
                            <div>
                                {{ $itemLabel }}
                            </div>

                            <div>
                                @if($value === 'agree')
                                    <span class="badge bg-success">Agree</span>
                                @elseif($value === 'disagree')
                                    <span class="badge bg-danger">Disagree</span>
                                @elseif($value === 'need_modification')
                                    <span class="badge bg-warning text-dark">
                                        Need Modification
                                    </span>
                                @elseif($value)
                                    <span class="badge bg-secondary">
                                        {{ $assessmentLabels[$value] ?? ucfirst(str_replace('_', ' ', $value)) }}
                                    </span>
                                @else
                                    <span class="text-muted">Not answered</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @empty
                    <div class="bmrc-empty">
                        Review assessment sections are not configured.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Modification / Improvement Suggestions --}}
        <div class="bmrc-show-card">
            <div class="bmrc-show-card-header">
                <i class="bi bi-pencil-square me-2"></i>
                Modification and Improvement: Specific Suggestions
            </div>

            <div class="bmrc-show-card-body">
                @php
                    $hasSuggestion = $suggestionMap
                        ->filter(fn ($value) => filled($value))
                        ->isNotEmpty();
                @endphp

                @if($hasSuggestion)
                    @foreach($reviewSections as $sectionKey => $section)
                        @php
                            $suggestion = $suggestionMap->get($sectionKey);
                        @endphp

                        @if(filled($suggestion))
                            <div class="bmrc-suggestion-block">
                                <div class="bmrc-suggestion-title">
                                    {{ $section['label'] ?? ucfirst(str_replace('_', ' ', $sectionKey)) }}
                                </div>

                                <div class="bmrc-response-box">{{ $suggestion }}</div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="bmrc-empty">
                        No specific modification or improvement suggestions were provided.
                    </div>
                @endif
            </div>
        </div>

        {{-- Comments to Authors --}}
        <div class="bmrc-show-card">
            <div class="bmrc-show-card-header">
                <i class="bi bi-chat-left-text me-2"></i>
                Comments to the Author(s)
            </div>

            <div class="bmrc-show-card-body">
                @if(filled($review->comments_to_author))
                    <div class="bmrc-response-box">{{ $review->comments_to_author }}</div>
                @else
                    <div class="bmrc-empty">
                        No comments to the author(s) were provided.
                    </div>
                @endif
            </div>
        </div>

        {{-- Confidential Comments --}}
        <div class="bmrc-show-card">
            <div class="bmrc-show-card-header">
                <i class="bi bi-lock me-2"></i>
                Confidential Comments to the Editor
            </div>

            <div class="bmrc-show-card-body">
                <div class="alert alert-light border small">
                    <i class="bi bi-lock-fill me-1"></i>
                    These comments are confidential and are not intended for the author(s).
                </div>

                @if(filled($review->confidential_comments_to_editor))
                    <div class="bmrc-response-box">
                        {{ $review->confidential_comments_to_editor }}
                    </div>
                @else
                    <div class="bmrc-empty">
                        No confidential comments were provided.
                    </div>
                @endif
            </div>
        </div>

        {{-- Overall Evaluation --}}
        <div class="bmrc-show-card">
            <div class="bmrc-show-card-header">
                <i class="bi bi-check2-square me-2"></i>
                Overall Evaluation
            </div>

            <div class="bmrc-show-card-body">
                @php
                    $evaluationValue = $review->overall_evaluation;
                    $evaluationLabel = $evaluationOptions[$evaluationValue]
                        ?? ($evaluationValue
                            ? ucfirst(str_replace('_', ' ', $evaluationValue))
                            : null);
                @endphp

                @if($evaluationLabel)
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary fs-6">
                            {{ $evaluationLabel }}
                        </span>
                    </div>
                @else
                    <div class="bmrc-empty">
                        No overall evaluation was recorded.
                    </div>
                @endif
            </div>
        </div>

        {{-- Final Declaration --}}
        <div class="bmrc-show-card">
            <div class="bmrc-show-card-header">
                <i class="bi bi-person-check me-2"></i>
                Final Reviewer Declaration
            </div>

            <div class="bmrc-show-card-body">
                @if($review->reviewer_declaration)
                    <div class="alert alert-success mb-0">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        The reviewer confirmed that the review was completed
                        independently and represents their scientific assessment
                        of the manuscript.
                    </div>
                @else
                    <div class="alert alert-secondary mb-0">
                        Final reviewer declaration was not recorded.
                    </div>
                @endif
            </div>
        </div>

        {{-- Footer Actions --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4 bmrc-no-print">
            <a
                href="{{ route('reviewer.invitations.show', $invitation->id) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Invitation
            </a>

            <button
                type="button"
                class="btn btn-outline-primary"
                onclick="window.print()"
            >
                <i class="bi bi-printer me-1"></i>
                Print Review
            </button>
        </div>

    </div>
</div>

@endsection
