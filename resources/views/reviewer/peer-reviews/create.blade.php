@extends('reviewer.layouts.app')

@section('title', 'Peer Review Workspace | BMRC Journal')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Reviewer-safe File Types
    |--------------------------------------------------------------------------
    */

    $safeFileTypes = [
        'main manuscript',
        'main_manuscript',
        'manuscript',
        'article',
        'article_file',
        'manuscript_file',
        'main_file',

        'table',
        'tables',

        'figure',
        'figures',

        'supplementary',
        'supplementary file',
        'supplementary_file',
        'supplementary files',
        'supplementary_files',
    ];

    $mainManuscriptTypes = [
        'main manuscript',
        'main_manuscript',
        'manuscript',
        'article',
        'article_file',
        'manuscript_file',
        'main_file',
    ];

    /*
    |--------------------------------------------------------------------------
    | Get Manuscript Files
    |--------------------------------------------------------------------------
    |
    | Prefer $reviewerFiles from controller.
    | Otherwise try manuscript -> files relationship.
    |
    */

    if (isset($reviewerFiles)) {

        $reviewerFiles = collect($reviewerFiles);

    } else {

        $manuscriptModel = $invitation->manuscript ?? null;

        $allFiles = collect();

        if ($manuscriptModel) {

            if (method_exists($manuscriptModel, 'files')) {

                $allFiles = $manuscriptModel
                    ->files()
                    ->get();

            } elseif (isset($manuscriptModel->files)) {

                $allFiles = collect(
                    $manuscriptModel->files
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Reviewer-safe Files
        |--------------------------------------------------------------------------
        */

        $reviewerFiles = $allFiles
            ->filter(function ($file) use ($safeFileTypes) {

                $fileType = strtolower(
                    trim(
                        (string) (
                            $file->file_type ?? ''
                        )
                    )
                );

                $fileStatus = strtolower(
                    trim(
                        (string) (
                            $file->status ?? ''
                        )
                    )
                );

                $blockedStatuses = [
                    'deleted',
                    'replaced',
                    'inactive',
                    'archived',
                    'removed',
                ];

                return
                    in_array(
                        $fileType,
                        $safeFileTypes,
                        true
                    )
                    &&
                    !in_array(
                        $fileStatus,
                        $blockedStatuses,
                        true
                    );
            })
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Main Manuscript
    |--------------------------------------------------------------------------
    */

    $mainManuscript = $reviewerFiles
        ->first(function ($file) use ($mainManuscriptTypes) {

            $fileType = strtolower(
                trim(
                    (string) (
                        $file->file_type ?? ''
                    )
                )
            );

            return in_array(
                $fileType,
                $mainManuscriptTypes,
                true
            );
        });

    /*
    |--------------------------------------------------------------------------
    | Scientific Information
    |--------------------------------------------------------------------------
    */

    $scientificFields = [
        'background'  => 'Background',
        'objectives'  => 'Objectives',
        'methods'     => 'Methods',
        'results'     => 'Results',
        'conclusions' => 'Conclusions',
    ];

    /*
    |--------------------------------------------------------------------------
    | Assessment Options
    |--------------------------------------------------------------------------
    */

    $assessmentOptions = [
        'agree' => 'Agree',
        'disagree' => 'Disagree',
        'need_modification' => 'Need Modification',
    ];

    /*
    |--------------------------------------------------------------------------
    | Conflict Value
    |--------------------------------------------------------------------------
    */

    $currentConflict = old(
        'conflict_of_interest',
        $peerReview->conflict_of_interest === null
            ? '0'
            : (
                $peerReview->conflict_of_interest
                    ? '1'
                    : '0'
            )
    );

    /*
    |--------------------------------------------------------------------------
    | Evaluation Options
    |--------------------------------------------------------------------------
    */

    $evaluationOptions = config(
        'peer_review.evaluations',
        []
    );

    /*
    |--------------------------------------------------------------------------
    | Scientific Information Available?
    |--------------------------------------------------------------------------
    */

    $hasScientificInfo = false;

    foreach ($scientificFields as $field => $label) {

        if (!empty($blindManuscript[$field])) {

            $hasScientificInfo = true;

            break;
        }
    }

@endphp


<style>

    /*
    |--------------------------------------------------------------------------
    | Main Workspace
    |--------------------------------------------------------------------------
    */

    .bmrc-review-workspace {
        width: 100%;
    }

    /*
    |--------------------------------------------------------------------------
    | Top Header
    |--------------------------------------------------------------------------
    */

    .bmrc-workspace-header {
        background: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 12px;
    }

    .bmrc-workspace-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #212529;
    }

    .bmrc-manuscript-title {
        margin-top: 5px;
        color: #495057;
        font-size: .92rem;
        line-height: 1.5;
    }

    .bmrc-meta-item {
        min-width: 85px;
        font-size: .82rem;
        color: #6c757d;
    }

    .bmrc-meta-item strong {
        display: block;
        margin-top: 2px;
    }

    /*
    |--------------------------------------------------------------------------
    | Blind Review Notice
    |--------------------------------------------------------------------------
    */

    .bmrc-blind-notice {
        padding: 8px 12px;
        margin-bottom: 12px;
        font-size: .82rem;
    }

    /*
    |--------------------------------------------------------------------------
    | Split Screen
    |--------------------------------------------------------------------------
    */

    .bmrc-split-workspace {
        display: grid;

        grid-template-columns:
            minmax(0, 58fr)
            minmax(420px, 42fr);

        gap: 12px;

        height: calc(100vh - 190px);

        min-height: 720px;
    }

    /*
    |--------------------------------------------------------------------------
    | Left + Right Panels
    |--------------------------------------------------------------------------
    */

    .bmrc-reader-panel,
    .bmrc-form-panel {
        background: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        overflow: hidden;
        min-width: 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Left Reader
    |--------------------------------------------------------------------------
    */

    .bmrc-reader-panel {
        display: flex;
        flex-direction: column;
    }

    .bmrc-reader-toolbar {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 10px 12px;
        flex-shrink: 0;
    }

    .bmrc-reader-heading {
        font-size: .95rem;
        font-weight: 700;
    }

    .bmrc-reader-frame {
        position: relative;
        flex: 1;
        min-height: 0;
        background: #e9ecef;
    }

    .bmrc-reader-frame iframe {
        display: block;
        width: 100%;
        height: 100%;
        border: 0;
        background: #ffffff;
    }

    /*
    |--------------------------------------------------------------------------
    | Empty Reader
    |--------------------------------------------------------------------------
    */

    .bmrc-empty-preview {
        height: 100%;
        min-height: 500px;

        display: flex;
        justify-content: center;
        align-items: center;

        text-align: center;
        padding: 30px;

        color: #6c757d;
    }

    /*
    |--------------------------------------------------------------------------
    | File Buttons
    |--------------------------------------------------------------------------
    */

    .bmrc-file-buttons {
        margin-top: 9px;
    }

    .bmrc-file-btn {
        font-size: .78rem;
    }

    .bmrc-file-btn.active {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
        color: #ffffff;
    }

    /*
    |--------------------------------------------------------------------------
    | Right Review Panel
    |--------------------------------------------------------------------------
    */

    .bmrc-form-panel {
        display: flex;
        flex-direction: column;
    }

    .bmrc-form-header {
        flex-shrink: 0;

        padding: 11px 14px;

        background: #ffffff;

        border-bottom: 1px solid #dee2e6;
    }

    .bmrc-form-scroll {
        flex: 1;
        min-height: 0;

        overflow-y: auto;

        padding: 14px;

        scroll-behavior: smooth;
    }

    /*
    |--------------------------------------------------------------------------
    | Form Sections
    |--------------------------------------------------------------------------
    */

    .bmrc-section {
        background: #ffffff;

        border: 1px solid #dee2e6;
        border-radius: 8px;

        margin-bottom: 14px;

        overflow: hidden;
    }

    .bmrc-section-header {
        padding: 10px 13px;

        background: #f8f9fa;

        border-bottom: 1px solid #dee2e6;

        font-size: .92rem;
        font-weight: 700;
    }

    .bmrc-section-body {
        padding: 14px;
    }

    /*
    |--------------------------------------------------------------------------
    | Assessment
    |--------------------------------------------------------------------------
    */

    .bmrc-assessment-group-title {
        margin-top: 16px;
        margin-bottom: 6px;

        font-size: .78rem;
        font-weight: 700;

        text-transform: uppercase;

        color: #495057;

        letter-spacing: .03em;
    }

    .bmrc-assessment-group-title:first-child {
        margin-top: 0;
    }

    .bmrc-assessment-item {
        padding: 11px 0;

        border-bottom: 1px solid #edf0f2;
    }

    .bmrc-assessment-item:last-child {
        border-bottom: 0;
    }

    .bmrc-assessment-label {
        font-size: .9rem;
        margin-bottom: 8px;
        line-height: 1.45;
    }

    /*
    |--------------------------------------------------------------------------
    | Textareas
    |--------------------------------------------------------------------------
    */

    .bmrc-form-panel textarea {
        font-size: .9rem;
        line-height: 1.5;
    }

    /*
    |--------------------------------------------------------------------------
    | Sticky Action Bar
    |--------------------------------------------------------------------------
    */

    .bmrc-form-actions {
        flex-shrink: 0;

        padding: 10px 14px;

        border-top: 1px solid #dee2e6;

        background: #ffffff;

        box-shadow:
            0 -3px 8px
            rgba(0, 0, 0, .04);
    }

    /*
    |--------------------------------------------------------------------------
    | Mobile Tabs
    |--------------------------------------------------------------------------
    */

    .bmrc-mobile-tabs {
        display: none;
    }

    /*
    |--------------------------------------------------------------------------
    | Responsive
    |--------------------------------------------------------------------------
    */

    @media (max-width: 1199.98px) {

        .bmrc-split-workspace {
            display: block;

            height: auto;

            min-height: 0;
        }

        .bmrc-mobile-tabs {
            display: flex;

            margin-bottom: 10px;
        }

        .bmrc-reader-panel,
        .bmrc-form-panel {
            height: calc(100vh - 225px);

            min-height: 650px;
        }

        .bmrc-workspace-mobile-hidden {
            display: none !important;
        }
    }

    @media (max-width: 767.98px) {

        .bmrc-workspace-header {
            padding: 12px;
        }

        .bmrc-meta-wrapper {
            margin-top: 8px;
        }

        .bmrc-reader-panel,
        .bmrc-form-panel {
            height: calc(100vh - 210px);

            min-height: 540px;
        }

        .bmrc-form-scroll {
            padding: 10px;
        }

        .bmrc-section-body {
            padding: 12px;
        }
    }

</style>


<div class="container-fluid py-3 bmrc-review-workspace">


    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="bmrc-workspace-header">

        <div
            class="
                d-flex
                flex-wrap
                justify-content-between
                align-items-start
                gap-3
            "
        >

            <div class="flex-grow-1">

                <div class="bmrc-workspace-title">

                    <i class="bi bi-journal-check me-2"></i>

                    BMRC Peer Review Workspace

                </div>


                <div class="bmrc-manuscript-title">

                    {{ $blindManuscript['title'] }}

                </div>

            </div>


            <div
                class="
                    d-flex
                    flex-wrap
                    gap-3
                    bmrc-meta-wrapper
                "
            >

                <div class="bmrc-meta-item">

                    Manuscript

                    <strong class="text-primary">

                        {{
                            $blindManuscript[
                                'manuscript_id'
                            ]
                        }}

                    </strong>

                </div>


                <div class="bmrc-meta-item">

                    Article Type

                    <strong class="text-dark">

                        {{
                            $blindManuscript[
                                'article_type'
                            ]
                            ?? 'N/A'
                        }}

                    </strong>

                </div>


                <div class="bmrc-meta-item">

                    Round

                    <strong class="text-dark">

                        {{ $peerReview->review_round }}

                    </strong>

                </div>


                <div class="bmrc-meta-item">

                    Deadline

                    <strong class="text-danger">

                        {{
                            $invitation
                                ->review_deadline
                                ?->format('d M Y')
                            ?? 'Not specified'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        BLIND REVIEW NOTICE
    ========================================================== --}}

    <div
        class="
            alert
            alert-warning
            bmrc-blind-notice
        "
    >

        <i
            class="
                bi
                bi-shield-lock-fill
                me-1
            "
        ></i>

        <strong>
            Blind Peer Review:
        </strong>

        Author-identifying information is hidden.
        Manuscript materials and review content
        must be treated as confidential.

    </div>


    {{-- =========================================================
        MOBILE SWITCH
    ========================================================== --}}

    <div
        class="
            bmrc-mobile-tabs
            btn-group
            w-100
        "
        role="group"
    >

        <button
            type="button"
            class="
                btn
                btn-primary
                bmrc-mobile-switch
            "
            data-target="reader"
        >

            <i
                class="
                    bi
                    bi-file-earmark-text
                    me-1
                "
            ></i>

            Manuscript

        </button>


        <button
            type="button"
            class="
                btn
                btn-outline-primary
                bmrc-mobile-switch
            "
            data-target="form"
        >

            <i
                class="
                    bi
                    bi-ui-checks-grid
                    me-1
                "
            ></i>

            Review Form

        </button>

    </div>


    {{-- =========================================================
        SPLIT WORKSPACE
    ========================================================== --}}

    <div class="bmrc-split-workspace">


        {{-- =====================================================
            LEFT PANEL
        ====================================================== --}}

        <div
            class="bmrc-reader-panel"
            id="bmrcReaderPanel"
        >

            {{-- ===============================================
                READER TOOLBAR
            ================================================ --}}

            <div class="bmrc-reader-toolbar">

                <div
                    class="
                        d-flex
                        flex-wrap
                        justify-content-between
                        align-items-center
                        gap-2
                    "
                >

                    <div class="bmrc-reader-heading">

                        <i
                            class="
                                bi
                                bi-file-earmark-pdf
                                me-1
                            "
                        ></i>

                        Manuscript Reader

                    </div>


                    @if($mainManuscript)

                        <div class="d-flex gap-2">

                            {{-- Open in New Tab --}}

                            <a
                                href="{{ route(
                                    'reviewer.peer-reviews.files.view',
                                    [
                                        'invitation' =>
                                            $invitation->id,

                                        'file' =>
                                            $mainManuscript->id,
                                    ]
                                ) }}"
                                target="_blank"
                                class="
                                    btn
                                    btn-sm
                                    btn-outline-primary
                                "
                                title="Open in new tab"
                            >

                                <i
                                    class="
                                        bi
                                        bi-box-arrow-up-right
                                    "
                                ></i>

                            </a>


                            {{-- Download --}}

                            <a
                                href="{{ route(
                                    'reviewer.peer-reviews.files.download',
                                    [
                                        'invitation' =>
                                            $invitation->id,

                                        'file' =>
                                            $mainManuscript->id,
                                    ]
                                ) }}"
                                class="
                                    btn
                                    btn-sm
                                    btn-outline-success
                                "
                                title="Download manuscript"
                            >

                                <i class="bi bi-download"></i>

                            </a>

                        </div>

                    @endif

                </div>


                {{-- ===========================================
                    FILE SELECTOR
                ============================================ --}}

                @if($reviewerFiles->count() > 0)

                    <div
                        class="
                            bmrc-file-buttons
                            d-flex
                            flex-wrap
                            gap-2
                        "
                    >

                        @foreach($reviewerFiles as $file)

                            @php
                                $currentFileType = strtolower(
                                    trim(
                                        (string) (
                                            $file->file_type
                                            ?? ''
                                        )
                                    )
                                );

                                if (
                                    in_array(
                                        $currentFileType,
                                        $mainManuscriptTypes,
                                        true
                                    )
                                ) {

                                    $fileLabel =
                                        'Main Manuscript';

                                    $fileIcon =
                                        'bi-file-earmark-text';

                                } elseif (
                                    in_array(
                                        $currentFileType,
                                        [
                                            'table',
                                            'tables',
                                        ],
                                        true
                                    )
                                ) {

                                    $fileLabel =
                                        'Table';

                                    $fileIcon =
                                        'bi-table';

                                } elseif (
                                    in_array(
                                        $currentFileType,
                                        [
                                            'figure',
                                            'figures',
                                        ],
                                        true
                                    )
                                ) {

                                    $fileLabel =
                                        'Figure';

                                    $fileIcon =
                                        'bi-image';

                                } else {

                                    $fileLabel =
                                        'Supplementary';

                                    $fileIcon =
                                        'bi-paperclip';
                                }

                                $previewUrl = route(
                                    'reviewer.peer-reviews.files.preview-content',
                                    [
                                        'invitation' =>
                                            $invitation->id,

                                        'file' =>
                                            $file->id,
                                    ]
                                );
                            @endphp


                            <button
                                type="button"
                                class="
                                    btn
                                    btn-sm
                                    btn-outline-secondary
                                    bmrc-file-btn
                                    {{
                                        $mainManuscript
                                        &&
                                        $mainManuscript->id
                                            === $file->id
                                            ? 'active'
                                            : ''
                                    }}
                                "
                                data-preview-url="{{ $previewUrl }}"
                                data-file-id="{{ $file->id }}"
                            >

                                <i
                                    class="
                                        bi
                                        {{ $fileIcon }}
                                        me-1
                                    "
                                ></i>

                                {{ $fileLabel }}

                            </button>

                        @endforeach

                    </div>

                @else

                    <div
                        class="
                            small
                            text-muted
                            mt-2
                        "
                    >

                        No reviewer-safe attachments
                        are available.

                    </div>

                @endif

            </div>


            {{-- ===============================================
                DOCUMENT FRAME
            ================================================ --}}

            <div class="bmrc-reader-frame">

                @if($mainManuscript)

                    <iframe
                        id="bmrcManuscriptFrame"
                        src="{{ route(
                            'reviewer.peer-reviews.files.preview-content',
                            [
                                'invitation' =>
                                    $invitation->id,

                                'file' =>
                                    $mainManuscript->id,
                            ]
                        ) }}"
                        title="BMRC Manuscript Reader"
                    ></iframe>

                @else

                    <div class="bmrc-empty-preview">

                        <div>

                            <i
                                class="
                                    bi
                                    bi-file-earmark-x
                                    display-5
                                    d-block
                                    mb-3
                                "
                            ></i>

                            <strong>
                                Main manuscript unavailable
                            </strong>

                            <div class="mt-2">

                                No reviewer-safe main
                                manuscript is currently
                                available.

                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </div>


        {{-- =====================================================
            RIGHT PANEL
        ====================================================== --}}

        <div
            class="bmrc-form-panel"
            id="bmrcFormPanel"
        >


            {{-- ===============================================
                FORM HEADER
            ================================================ --}}

            <div class="bmrc-form-header">

                <div
                    class="
                        d-flex
                        justify-content-between
                        align-items-center
                        gap-2
                    "
                >

                    <strong>

                        <i
                            class="
                                bi
                                bi-ui-checks-grid
                                me-1
                            "
                        ></i>

                        Peer Review Form

                    </strong>


                    <span
                        class="
                            small
                            text-muted
                        "
                        id="bmrcSaveStatus"
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

                </div>

            </div>


            {{-- ===============================================
                FORM
            ================================================ --}}

            <form
                method="POST"
                action="{{ route(
                    'reviewer.peer-reviews.draft',
                    $peerReview->id
                ) }}"
                id="peerReviewForm"
                class="
                    d-flex
                    flex-column
                    flex-grow-1
                    overflow-hidden
                "
            >

                @csrf

                <input
                    type="hidden"
                    name="_method"
                    value="PUT"
                    id="formMethod"
                >

                <input
                    type="hidden"
                    name="preview_after_save"
                    value="0"
                    id="previewAfterSave"
                >


                {{-- ===========================================
                    SCROLLABLE FORM
                ============================================ --}}

                <div
                    class="bmrc-form-scroll"
                    id="bmrcReviewFormScroll"
                >


                    {{-- =======================================
                        VALIDATION ERRORS
                    ======================================== --}}

                    @if($errors->any())

                        <div class="alert alert-danger">

                            <strong>

                                <i
                                    class="
                                        bi
                                        bi-exclamation-triangle
                                        me-1
                                    "
                                ></i>

                                Please correct the following:

                            </strong>


                            <ul class="mb-0 mt-2">

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    {{-- =======================================
                        MANUSCRIPT INFORMATION
                    ======================================== --}}

                    <div class="bmrc-section">

                        <div class="bmrc-section-header">

                            <i
                                class="
                                    bi
                                    bi-info-circle
                                    me-1
                                "
                            ></i>

                            Manuscript Information

                        </div>


                        <div class="bmrc-section-body">

                            <div class="row g-3 small">

                                <div class="col-6">

                                    <span
                                        class="
                                            text-muted
                                            d-block
                                        "
                                    >
                                        Manuscript ID
                                    </span>

                                    <strong>

                                        {{
                                            $blindManuscript[
                                                'manuscript_id'
                                            ]
                                        }}

                                    </strong>

                                </div>


                                <div class="col-6">

                                    <span
                                        class="
                                            text-muted
                                            d-block
                                        "
                                    >
                                        Journal
                                    </span>

                                    <strong>

                                        {{
                                            $blindManuscript[
                                                'journal'
                                            ]
                                        }}

                                    </strong>

                                </div>

                            </div>


                            {{-- Abstract --}}

                            @if(!empty($blindManuscript['abstract']))

                                <div class="mt-3">

                                    <button
                                        type="button"
                                        class="
                                            btn
                                            btn-sm
                                            btn-outline-secondary
                                        "
                                        data-bs-toggle="collapse"
                                        data-bs-target="#bmrcAbstract"
                                        aria-expanded="false"
                                        aria-controls="bmrcAbstract"
                                    >

                                        <i
                                            class="
                                                bi
                                                bi-card-text
                                                me-1
                                            "
                                        ></i>

                                        Abstract

                                    </button>


                                    <div
                                        class="collapse mt-3"
                                        id="bmrcAbstract"
                                    >

                                        <div
                                            class="
                                                border
                                                rounded
                                                bg-light
                                                p-3
                                                small
                                            "
                                            style="
                                                line-height:1.7;
                                                text-align:justify;
                                            "
                                        >

                                            {!!
                                                $blindManuscript[
                                                    'abstract'
                                                ]
                                            !!}

                                        </div>

                                    </div>

                                </div>

                            @endif


                            {{-- Scientific Information --}}

                            @if($hasScientificInfo)

                                <div class="mt-2">

                                    <button
                                        type="button"
                                        class="
                                            btn
                                            btn-sm
                                            btn-outline-secondary
                                        "
                                        data-bs-toggle="collapse"
                                        data-bs-target="#bmrcScientificInfo"
                                        aria-expanded="false"
                                        aria-controls="bmrcScientificInfo"
                                    >

                                        <i
                                            class="
                                                bi
                                                bi-journal-text
                                                me-1
                                            "
                                        ></i>

                                        Scientific Information

                                    </button>


                                    <div
                                        class="collapse mt-3"
                                        id="bmrcScientificInfo"
                                    >

                                        @foreach($scientificFields as $field => $label)

                                            @if(!empty($blindManuscript[$field]))

                                                <div class="mb-3">

                                                    <strong
                                                        class="
                                                            d-block
                                                            small
                                                            mb-1
                                                        "
                                                    >

                                                        {{ $label }}

                                                    </strong>


                                                    <div
                                                        class="
                                                            small
                                                            text-muted
                                                        "
                                                        style="
                                                            line-height:1.7;
                                                        "
                                                    >

                                                        {!!
                                                            $blindManuscript[
                                                                $field
                                                            ]
                                                        !!}

                                                    </div>

                                                </div>

                                            @endif

                                        @endforeach

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- =======================================
                        REVIEWER DECLARATION
                    ======================================== --}}

                    <div class="bmrc-section">

                        <div class="bmrc-section-header">

                            <i
                                class="
                                    bi
                                    bi-shield-check
                                    me-1
                                "
                            ></i>

                            Reviewer Declaration

                        </div>


                        <div class="bmrc-section-body">

                            <div class="mb-3">

                                <label
                                    class="
                                        form-label
                                        fw-semibold
                                    "
                                >

                                    Do you have any conflict
                                    of interest related to
                                    this manuscript?

                                </label>


                                <div>

                                    <div
                                        class="
                                            form-check
                                            form-check-inline
                                        "
                                    >

                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="conflict_of_interest"
                                            id="conflict_no"
                                            value="0"
                                            {{
                                                (string)
                                                $currentConflict === '0'
                                                    ? 'checked'
                                                    : ''
                                            }}
                                        >

                                        <label
                                            class="form-check-label"
                                            for="conflict_no"
                                        >
                                            No
                                        </label>

                                    </div>


                                    <div
                                        class="
                                            form-check
                                            form-check-inline
                                        "
                                    >

                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="conflict_of_interest"
                                            id="conflict_yes"
                                            value="1"
                                            {{
                                                (string)
                                                $currentConflict === '1'
                                                    ? 'checked'
                                                    : ''
                                            }}
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

                            <div
                                class="mb-3"
                                id="conflictDetailsWrapper"
                            >

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
                                >{{ old(
                                    'conflict_details',
                                    $peerReview->conflict_details
                                ) }}</textarea>

                            </div>


                            {{-- Confidentiality --}}

                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="confidentiality_confirmed"
                                    value="1"
                                    id="confidentiality_confirmed"
                                    {{
                                        old(
                                            'confidentiality_confirmed',
                                            $peerReview
                                                ->confidentiality_confirmed
                                        )
                                            ? 'checked'
                                            : ''
                                    }}
                                >

                                <label
                                    class="form-check-label"
                                    for="confidentiality_confirmed"
                                >

                                    I agree to maintain the
                                    confidentiality of this
                                    manuscript and its contents.

                                </label>

                            </div>

                        </div>

                    </div>


                    {{-- =======================================
                        SCIENTIFIC ASSESSMENT
                    ======================================== --}}

                    <div class="bmrc-section">

                        <div class="bmrc-section-header">

                            <i
                                class="
                                    bi
                                    bi-list-check
                                    me-1
                                "
                            ></i>

                            Scientific Assessment

                        </div>


                        <div class="bmrc-section-body">

                            <p
                                class="
                                    text-muted
                                    small
                                    mb-3
                                "
                            >

                                Please assess each item by
                                selecting Agree, Disagree,
                                or Need Modification.

                            </p>


                            @foreach($reviewSections as $sectionKey => $section)

                                <div
                                    class="
                                        bmrc-assessment-group-title
                                    "
                                >

                                    {{ $section['label'] }}

                                </div>


                                @foreach($section['items'] as $itemKey => $itemLabel)

                                    @php
                                        $assessmentValue = old(
                                            'assessment.' . $itemKey,
                                            $existingAssessments[
                                                $itemKey
                                            ] ?? null
                                        );
                                    @endphp


                                    <div
                                        class="
                                            bmrc-assessment-item
                                        "
                                    >

                                        <div
                                            class="
                                                bmrc-assessment-label
                                            "
                                        >

                                            {{ $itemLabel }}

                                        </div>


                                        <div
                                            class="
                                                d-flex
                                                flex-wrap
                                                gap-3
                                            "
                                        >

                                            @foreach($assessmentOptions as $option => $optionLabel)

                                                <div
                                                    class="
                                                        form-check
                                                    "
                                                >

                                                    <input
                                                        class="
                                                            form-check-input
                                                        "
                                                        type="radio"
                                                        name="assessment[{{ $itemKey }}]"
                                                        id="assessment_{{ $itemKey }}_{{ $option }}"
                                                        value="{{ $option }}"
                                                        {{
                                                            $assessmentValue
                                                            === $option
                                                                ? 'checked'
                                                                : ''
                                                        }}
                                                    >

                                                    <label
                                                        class="
                                                            form-check-label
                                                            small
                                                        "
                                                        for="assessment_{{ $itemKey }}_{{ $option }}"
                                                    >

                                                        {{ $optionLabel }}

                                                    </label>

                                                </div>

                                            @endforeach

                                        </div>

                                    </div>

                                @endforeach

                            @endforeach

                        </div>

                    </div>


                    {{-- =======================================
                        MODIFICATION / IMPROVEMENT
                    ======================================== --}}

                    <div class="bmrc-section">

                        <div class="bmrc-section-header">

                            <i
                                class="
                                    bi
                                    bi-pencil-square
                                    me-1
                                "
                            ></i>

                            Modification and Improvement:
                            Specific Suggestions

                        </div>


                        <div class="bmrc-section-body">

                            <p
                                class="
                                    text-muted
                                    small
                                    mb-3
                                "
                            >

                                Provide specific suggestions
                                where modification or
                                improvement is required.

                            </p>


                            @foreach($reviewSections as $sectionKey => $section)

                                <div class="mb-3">

                                    <label
                                        for="suggestion_{{ $sectionKey }}"
                                        class="
                                            form-label
                                            fw-semibold
                                        "
                                    >

                                        {{ $section['label'] }}

                                    </label>


                                    <textarea
                                        name="suggestions[{{ $sectionKey }}]"
                                        id="suggestion_{{ $sectionKey }}"
                                        class="form-control"
                                        rows="3"
                                        maxlength="10000"
                                        placeholder="Enter specific suggestions for {{ strtolower($section['label']) }}..."
                                    >{{ old(
                                        'suggestions.' .
                                        $sectionKey,
                                        $existingSuggestions[
                                            $sectionKey
                                        ] ?? ''
                                    ) }}</textarea>

                                </div>

                            @endforeach

                        </div>

                    </div>


                    {{-- =======================================
                        COMMENTS TO AUTHORS
                    ======================================== --}}

                    <div class="bmrc-section">

                        <div class="bmrc-section-header">

                            <i
                                class="
                                    bi
                                    bi-chat-left-text
                                    me-1
                                "
                            ></i>

                            Comments to the Author(s)

                        </div>


                        <div class="bmrc-section-body">

                            <div
                                class="
                                    alert
                                    alert-info
                                    small
                                "
                            >

                                <i
                                    class="
                                        bi
                                        bi-info-circle
                                        me-1
                                    "
                                ></i>

                                These comments may be
                                communicated to the author(s).
                                Do not include your name,
                                institution, email address,
                                or other identifying information.

                            </div>


                            <textarea
                                name="comments_to_author"
                                class="form-control"
                                rows="9"
                                maxlength="20000"
                                placeholder="Enter comments intended for the author(s)..."
                            >{{ old(
                                'comments_to_author',
                                $peerReview
                                    ->comments_to_author
                            ) }}</textarea>

                        </div>

                    </div>


                    {{-- =======================================
                        CONFIDENTIAL COMMENTS
                    ======================================== --}}

                    <div class="bmrc-section">

                        <div class="bmrc-section-header">

                            <i
                                class="
                                    bi
                                    bi-lock
                                    me-1
                                "
                            ></i>

                            Confidential Comments to the Editor

                        </div>


                        <div class="bmrc-section-body">

                            <p
                                class="
                                    text-muted
                                    small
                                "
                            >

                                These comments are for the
                                Editorial Office / Editor only
                                and will not be shown to the
                                author.

                            </p>


                            <textarea
                                name="confidential_comments_to_editor"
                                class="form-control"
                                rows="6"
                                maxlength="20000"
                                placeholder="Enter confidential comments..."
                            >{{ old(
                                'confidential_comments_to_editor',
                                $peerReview
                                    ->confidential_comments_to_editor
                            ) }}</textarea>

                        </div>

                    </div>


                    {{-- =======================================
                        OVERALL EVALUATION
                    ======================================== --}}

                    <div class="bmrc-section">

                        <div class="bmrc-section-header">

                            <i
                                class="
                                    bi
                                    bi-check2-square
                                    me-1
                                "
                            ></i>

                            Overall Evaluation

                        </div>


                        <div class="bmrc-section-body">

                            @if(!empty($evaluationOptions))

                                @foreach($evaluationOptions as $value => $label)

                                    <div
                                        class="
                                            form-check
                                            mb-3
                                        "
                                    >

                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="overall_evaluation"
                                            value="{{ $value }}"
                                            id="evaluation_{{ $value }}"
                                            {{
                                                old(
                                                    'overall_evaluation',
                                                    $peerReview
                                                        ->overall_evaluation
                                                ) === $value
                                                    ? 'checked'
                                                    : ''
                                            }}
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

                                <div
                                    class="
                                        alert
                                        alert-warning
                                        mb-0
                                    "
                                >

                                    <i
                                        class="
                                            bi
                                            bi-exclamation-triangle
                                            me-1
                                        "
                                    ></i>

                                    Overall evaluation options
                                    are not configured.

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- =======================================
                        FINAL DECLARATION
                    ======================================== --}}

                    <div class="bmrc-section">

                        <div class="bmrc-section-header">

                            <i
                                class="
                                    bi
                                    bi-person-check
                                    me-1
                                "
                            ></i>

                            Final Reviewer Declaration

                        </div>


                        <div class="bmrc-section-body">

                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="reviewer_declaration"
                                    value="1"
                                    id="reviewer_declaration"
                                    {{
                                        old(
                                            'reviewer_declaration',
                                            $peerReview
                                                ->reviewer_declaration
                                        )
                                            ? 'checked'
                                            : ''
                                    }}
                                >

                                <label
                                    class="form-check-label"
                                    for="reviewer_declaration"
                                >

                                    I confirm that I have
                                    completed this review
                                    independently and that this
                                    review represents my
                                    scientific assessment of
                                    the manuscript.

                                </label>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ===========================================
                    STICKY ACTION BAR
                ============================================ --}}

                <div class="bmrc-form-actions">

                    <div
                        class="
                            d-flex
                            flex-wrap
                            justify-content-between
                            align-items-center
                            gap-2
                        "
                    >

                        {{-- Back --}}

                        <a
                            href="{{ route(
                                'reviewer.invitations.show',
                                $invitation->id
                            ) }}"
                            class="
                                btn
                                btn-sm
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

                            Back

                        </a>


                        <div
                            class="
                                d-flex
                                flex-wrap
                                gap-2
                            "
                        >

                            {{-- Save Draft --}}

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary"
                                id="saveDraftBtn"
                            >
                                <i class="bi bi-save me-1"></i>
                                Save Draft
                            </button>


                            {{-- Preview --}}

                            <button
                                type="button"
                                class="btn btn-sm btn-info"
                                id="previewReviewBtn"
                            >
                                <i class="bi bi-eye me-1"></i>
                                Preview
                            </button>


                            {{-- Final Submit --}}

                            <button
                                type="button"
                                class="btn btn-sm btn-success"
                                id="submitReviewBtn"
                            >
                                <i class="bi bi-send-check me-1"></i>
                                Submit Review
                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
    JAVASCRIPT
========================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | Manuscript File Switcher
        |--------------------------------------------------------------------------
        */

        const manuscriptFrame =
            document.getElementById(
                'bmrcManuscriptFrame'
            );

        const fileButtons =
            document.querySelectorAll(
                '.bmrc-file-btn'
            );


        fileButtons.forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const previewUrl =
                        this.getAttribute(
                            'data-preview-url'
                        );

                    if (
                        manuscriptFrame
                        &&
                        previewUrl
                    ) {

                        manuscriptFrame.src =
                            previewUrl;
                    }


                    fileButtons.forEach(
                        function (item) {

                            item.classList.remove(
                                'active'
                            );
                        }
                    );


                    this.classList.add(
                        'active'
                    );
                }
            );
        });


        /*
        |--------------------------------------------------------------------------
        | Mobile Reader / Form Switch
        |--------------------------------------------------------------------------
        */

        const mobileSwitches =
            document.querySelectorAll(
                '.bmrc-mobile-switch'
            );

        const readerPanel =
            document.getElementById(
                'bmrcReaderPanel'
            );

        const formPanel =
            document.getElementById(
                'bmrcFormPanel'
            );


        function setMobileWorkspace(target)
        {
            /*
            | Desktop:
            | always display both panels.
            */

            if (window.innerWidth >= 1200) {

                if (readerPanel) {

                    readerPanel.classList.remove(
                        'bmrc-workspace-mobile-hidden'
                    );
                }

                if (formPanel) {

                    formPanel.classList.remove(
                        'bmrc-workspace-mobile-hidden'
                    );
                }

                return;
            }


            /*
            | Mobile / Tablet
            */

            if (target === 'form') {

                if (readerPanel) {

                    readerPanel.classList.add(
                        'bmrc-workspace-mobile-hidden'
                    );
                }

                if (formPanel) {

                    formPanel.classList.remove(
                        'bmrc-workspace-mobile-hidden'
                    );
                }

            } else {

                if (formPanel) {

                    formPanel.classList.add(
                        'bmrc-workspace-mobile-hidden'
                    );
                }

                if (readerPanel) {

                    readerPanel.classList.remove(
                        'bmrc-workspace-mobile-hidden'
                    );
                }
            }


            /*
            | Active mobile button
            */

            mobileSwitches.forEach(
                function (button) {

                    const active =
                        button.getAttribute(
                            'data-target'
                        ) === target;


                    if (active) {

                        button.classList.add(
                            'btn-primary'
                        );

                        button.classList.remove(
                            'btn-outline-primary'
                        );

                    } else {

                        button.classList.remove(
                            'btn-primary'
                        );

                        button.classList.add(
                            'btn-outline-primary'
                        );
                    }
                }
            );
        }


        mobileSwitches.forEach(
            function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        setMobileWorkspace(
                            this.getAttribute(
                                'data-target'
                            )
                        );
                    }
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Window Resize
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            'resize',
            function () {

                if (
                    window.innerWidth >= 1200
                ) {

                    setMobileWorkspace(
                        'reader'
                    );
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Initial Mobile State
        |--------------------------------------------------------------------------
        */

        if (
            window.innerWidth < 1200
        ) {

            setMobileWorkspace(
                'reader'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Conflict Details
        |--------------------------------------------------------------------------
        */

        const conflictYes =
            document.getElementById(
                'conflict_yes'
            );

        const conflictNo =
            document.getElementById(
                'conflict_no'
            );

        const conflictDetailsWrapper =
            document.getElementById(
                'conflictDetailsWrapper'
            );


        function updateConflictDetails()
        {
            if (!conflictDetailsWrapper) {

                return;
            }


            if (
                conflictYes
                &&
                conflictYes.checked
            ) {

                conflictDetailsWrapper.style.display =
                    '';

            } else {

                conflictDetailsWrapper.style.display =
                    'none';
            }
        }


        if (conflictYes) {

            conflictYes.addEventListener(
                'change',
                updateConflictDetails
            );
        }


        if (conflictNo) {

            conflictNo.addEventListener(
                'change',
                updateConflictDetails
            );
        }


        updateConflictDetails();


        /*
        |--------------------------------------------------------------------------
        | Unsaved Changes Indicator
        |--------------------------------------------------------------------------
        */

        const reviewForm =
            document.getElementById(
                'peerReviewForm'
            );

        const saveStatus =
            document.getElementById(
                'bmrcSaveStatus'
            );


        function markUnsaved()
        {
            if (!saveStatus) {

                return;
            }

            saveStatus.innerHTML =
                '<i class="bi bi-exclamation-circle me-1"></i>' +
                'Unsaved changes';

            saveStatus.classList.remove(
                'text-muted'
            );

            saveStatus.classList.add(
                'text-warning'
            );
        }


        if (reviewForm) {

            reviewForm.addEventListener(
                'input',
                markUnsaved
            );

            reviewForm.addEventListener(
                'change',
                markUnsaved
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Review Form Actions
        |--------------------------------------------------------------------------
        */

        const form =
            document.getElementById('peerReviewForm');

        const methodInput =
            document.getElementById('formMethod');

        const previewInput =
            document.getElementById('previewAfterSave');

        const saveDraftBtn =
            document.getElementById('saveDraftBtn');

        const previewReviewBtn =
            document.getElementById('previewReviewBtn');

        const submitReviewBtn =
            document.getElementById('submitReviewBtn');


        const draftUrl =
            @json(
                route(
                    'reviewer.peer-reviews.draft',
                    $peerReview->id
                )
            );

        const submitUrl =
            @json(
                route(
                    'reviewer.peer-reviews.submit',
                    $peerReview->id
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Save Draft
        |--------------------------------------------------------------------------
        */

        if (saveDraftBtn && form) {

            saveDraftBtn.addEventListener(
                'click',
                function () {

                    form.action = draftUrl;
                    form.method = 'POST';

                    methodInput.disabled = false;
                    methodInput.value = 'PUT';

                    previewInput.value = '0';

                    form.submit();
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Preview
        |--------------------------------------------------------------------------
        */

        if (previewReviewBtn && form) {

            previewReviewBtn.addEventListener(
                'click',
                function () {

                    form.action = draftUrl;
                    form.method = 'POST';

                    methodInput.disabled = false;
                    methodInput.value = 'PUT';

                    previewInput.value = '1';

                    form.submit();
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Final Submit
        |--------------------------------------------------------------------------
        */

        if (submitReviewBtn && form) {

            submitReviewBtn.addEventListener(
                'click',
                function () {

                    const confirmed = confirm(
                        'Are you sure you want to submit ' +
                        'the final peer review? ' +
                        'After submission you will not ' +
                        'be able to edit it.'
                    );

                    if (!confirmed) {
                        return;
                    }

                    form.action = submitUrl;
                    form.method = 'POST';

                    /*
                     * Critical:
                     * Disable Laravel's PUT method override.
                     */
                    if (methodInput) {
                        methodInput.disabled = true;
                    }

                    if (previewInput) {
                        previewInput.value = '0';
                    }

                    form.submit();
                }
            );
        }

    }
);





</script>

@endsection