<div class="row g-3">

    {{-- =====================================================
         PAGE TITLE
    ====================================================== --}}

    <div class="col-md-6">

        <label
            for="title"
            class="form-label"
        >
            Page Title

            <span class="text-danger">*</span>
        </label>


        <input
            type="text"
            name="title"
            id="title"
            value="{{ old(
                'title',
                $journalPage->title ?? ''
            ) }}"
            class="form-control
                   @error('title')
                       is-invalid
                   @enderror"
            required
        >


        @error('title')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- =====================================================
         MENU GROUP
    ====================================================== --}}

    <div class="col-md-6">

        <label
            for="menu_group"
            class="form-label"
        >
            Menu Group

            <span class="text-danger">*</span>
        </label>


        <select
            name="menu_group"
            id="menu_group"
            class="form-select
                   @error('menu_group')
                       is-invalid
                   @enderror"
            required
        >

            <option value="">
                Select Menu Group
            </option>


            <option
                value="about"
                @selected(
                    old(
                        'menu_group',
                        $journalPage->menu_group ?? ''
                    ) === 'about'
                )
            >
                About
            </option>


            <option
                value="editorial_board"
                @selected(
                    old(
                        'menu_group',
                        $journalPage->menu_group ?? ''
                    ) === 'editorial_board'
                )
            >
                Editorial Board
            </option>


            <option
                value="journal"
                @selected(
                    old(
                        'menu_group',
                        $journalPage->menu_group ?? ''
                    ) === 'journal'
                )
            >
                Journal
            </option>


            <option
                value="authors"
                @selected(
                    old(
                        'menu_group',
                        $journalPage->menu_group ?? ''
                    ) === 'authors'
                )
            >
                Authors
            </option>


            <option
                value="reviewers"
                @selected(
                    old(
                        'menu_group',
                        $journalPage->menu_group ?? ''
                    ) === 'reviewers'
                )
            >
                Reviewers
            </option>

        </select>


        @error('menu_group')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- =====================================================
         SLUG
    ====================================================== --}}

    <div class="col-md-6">

        <label
            for="slug"
            class="form-label"
        >
            URL Slug
        </label>


        <input
            type="text"
            name="slug"
            id="slug"
            value="{{ old(
                'slug',
                $journalPage->slug ?? ''
            ) }}"
            class="form-control
                   @error('slug')
                       is-invalid
                   @enderror"
            placeholder="editorial-board"
        >


        <small class="text-muted">

            Leave empty to generate from title.

        </small>


        @error('slug')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- =====================================================
         SORT ORDER
    ====================================================== --}}

    <div class="col-md-3">

        <label
            for="sort_order"
            class="form-label"
        >
            Sort Order
        </label>


        <input
            type="number"
            name="sort_order"
            id="sort_order"
            value="{{ old(
                'sort_order',
                $journalPage->sort_order ?? 0
            ) }}"
            min="0"
            class="form-control"
        >

    </div>


    {{-- =====================================================
         STATUS
    ====================================================== --}}

    <div class="col-md-3">

        <label
            for="status"
            class="form-label"
        >
            Status

            <span class="text-danger">*</span>
        </label>


        <select
            name="status"
            id="status"
            class="form-select"
            required
        >

            <option
                value="draft"
                @selected(
                    old(
                        'status',
                        $journalPage->status ?? 'draft'
                    ) === 'draft'
                )
            >
                Draft
            </option>


            <option
                value="published"
                @selected(
                    old(
                        'status',
                        $journalPage->status ?? ''
                    ) === 'published'
                )
            >
                Published
            </option>


            <option
                value="inactive"
                @selected(
                    old(
                        'status',
                        $journalPage->status ?? ''
                    ) === 'inactive'
                )
            >
                Inactive
            </option>

        </select>

    </div>


    {{-- =====================================================
         SHORT DESCRIPTION
    ====================================================== --}}

    <div class="col-12">

        <label
            for="short_description"
            class="form-label"
        >
            Short Description
        </label>


        <textarea
            name="short_description"
            id="short_description"
            rows="3"
            class="form-control"
        >{{ old(
            'short_description',
            $journalPage->short_description ?? ''
        ) }}</textarea>


        <small class="text-muted">

            Brief introduction shown with the page.

        </small>

    </div>


    {{-- =====================================================
         PAGE CONTENT
    ====================================================== --}}

    <div class="col-12">

        <label
            for="content"
            class="form-label fw-semibold"
        >

            Page Content

            <span class="text-danger">*</span>

        </label>


        {{-- =================================================
             CONTENT MODE
        ================================================== --}}

        <input
            type="hidden"
            name="content_mode"
            id="content_mode"
            value="{{ old(
                'content_mode',
                $journalPage->content_mode ?? 'visual'
            ) }}"
        >


        {{-- =================================================
             EDITOR TOOLBAR
        ================================================== --}}

        <div
            class="d-flex
                   flex-wrap
                   align-items-center
                   gap-2
                   p-2
                   bg-light
                   border
                   border-bottom-0
                   rounded-top"
        >

            <button
                type="button"
                id="visualModeBtn"
                class="btn btn-sm btn-primary"
            >

                <i
                    class="bi
                           bi-pencil-square
                           me-1"
                ></i>

                Visual Editor

            </button>


            <button
                type="button"
                id="htmlModeBtn"
                class="btn btn-sm btn-outline-dark"
            >

                <i
                    class="bi
                           bi-code-slash
                           me-1"
                ></i>

                HTML Source

            </button>


            <button
                type="button"
                id="previewHtmlBtn"
                class="btn
                       btn-sm
                       btn-outline-success
                       d-none"
            >

                <i
                    class="bi
                           bi-eye
                           me-1"
                ></i>

                Preview

            </button>


            <span
                class="ms-md-auto
                       small
                       text-muted"
            >

                Custom HTML is protected from CKEditor.

            </span>

        </div>


        {{-- =================================================
             HTML PROTECTION NOTICE
        ================================================== --}}

        <div
            id="htmlModeNotice"
            class="alert
                   alert-warning
                   rounded-0
                   mb-0
                   d-none"
        >

            <div
                class="d-flex
                       align-items-start
                       gap-2"
            >

                <i
                    class="bi
                           bi-shield-check
                           fs-5"
                ></i>


                <div>

                    <strong>
                        Custom HTML Protection Active
                    </strong>


                    <div class="small mt-1">

                        This page contains custom HTML.

                        Visual Editor is disabled because CKEditor
                        can remove DIV elements, Bootstrap classes,
                        inline styles and responsive layout markup.

                        Edit this page using HTML Source and use
                        Preview to check the design.

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
             CONTENT TEXTAREA
        ================================================== --}}

        <textarea
            name="content"
            id="content"
            rows="25"
            class="form-control
                   rounded-top-0
                   @error('content')
                       is-invalid
                   @enderror"
            style="
                min-height:520px;
                font-family:
                    Consolas,
                    Monaco,
                    'Courier New',
                    monospace;
            "
        >{{ old(
            'content',
            $journalPage->content ?? ''
        ) }}</textarea>


        @error('content')

            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>

        @enderror


        {{-- =================================================
             HTML PREVIEW
        ================================================== --}}

        <div
            id="htmlPreviewContainer"
            class="border
                   rounded
                   p-3
                   mt-3
                   d-none"
        >

            <div
                class="d-flex
                       flex-wrap
                       justify-content-between
                       align-items-center
                       gap-2
                       mb-3"
            >

                <strong>

                    <i class="bi bi-eye me-1"></i>

                    HTML Preview

                </strong>


                <button
                    type="button"
                    id="closeHtmlPreviewBtn"
                    class="btn
                           btn-sm
                           btn-outline-secondary"
                >

                    <i class="bi bi-x-lg me-1"></i>

                    Close Preview

                </button>

            </div>


            <iframe
                id="htmlPreviewFrame"
                title="HTML Preview"
                style="
                    width:100%;
                    min-height:700px;
                    border:1px solid #dee2e6;
                    background:#ffffff;
                "
            ></iframe>

        </div>

    </div>


    {{-- =====================================================
         FEATURED IMAGE
    ====================================================== --}}

    <div class="col-md-6">

        <label
            for="featured_image"
            class="form-label"
        >
            Featured Image
        </label>


        <input
            type="file"
            name="featured_image"
            id="featured_image"
            accept=".jpg,.jpeg,.png,.webp"
            class="form-control"
        >


        @if(
            isset($journalPage)
            &&
            $journalPage->featured_image
        )

            <div class="mt-3">

                <small
                    class="text-muted
                           d-block
                           mb-2"
                >
                    Current Image
                </small>


                <img
                    src="{{ asset(
                        'storage/'
                        .
                        $journalPage->featured_image
                    ) }}"
                    alt="{{ $journalPage->title }}"
                    class="img-thumbnail"
                    style="
                        max-height:180px;
                    "
                >

            </div>

        @endif

    </div>


    {{-- =====================================================
         DISPLAY OPTIONS
    ====================================================== --}}

    <div class="col-md-6">

        <label class="form-label">
            Display Options
        </label>


        <div
            class="border
                   rounded
                   bg-light
                   p-3"
        >

            <div class="form-check mb-3">

                <input
                    type="checkbox"
                    name="show_in_menu"
                    id="show_in_menu"
                    value="1"
                    class="form-check-input"
                    @checked(
                        old(
                            'show_in_menu',
                            $journalPage->show_in_menu
                            ?? true
                        )
                    )
                >


                <label
                    for="show_in_menu"
                    class="form-check-label"
                >
                    Show in Website Menu
                </label>

            </div>


            <div class="form-check">

                <input
                    type="checkbox"
                    name="show_on_homepage"
                    id="show_on_homepage"
                    value="1"
                    class="form-check-input"
                    @checked(
                        old(
                            'show_on_homepage',
                            $journalPage->show_on_homepage
                            ?? false
                        )
                    )
                >


                <label
                    for="show_on_homepage"
                    class="form-check-label"
                >
                    Show on Homepage
                </label>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================
     EDITOR SCRIPT
============================================================= --}}

@once

@push('scripts')

<script
    src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js">
</script>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | Elements
        |--------------------------------------------------------------------------
        */

        const textarea =
            document.getElementById(
                'content'
            );


        const contentMode =
            document.getElementById(
                'content_mode'
            );


        const visualButton =
            document.getElementById(
                'visualModeBtn'
            );


        const htmlButton =
            document.getElementById(
                'htmlModeBtn'
            );


        const htmlNotice =
            document.getElementById(
                'htmlModeNotice'
            );


        const previewButton =
            document.getElementById(
                'previewHtmlBtn'
            );


        const previewContainer =
            document.getElementById(
                'htmlPreviewContainer'
            );


        const previewFrame =
            document.getElementById(
                'htmlPreviewFrame'
            );


        const closePreviewButton =
            document.getElementById(
                'closeHtmlPreviewBtn'
            );


        const form =
            textarea.closest(
                'form'
            );


        /*
        |--------------------------------------------------------------------------
        | Editor Instance
        |--------------------------------------------------------------------------
        */

        let editorInstance =
            null;


        /*
        |--------------------------------------------------------------------------
        | VISUAL MODE UI
        |--------------------------------------------------------------------------
        */

        function showVisualMode()
        {

            visualButton.disabled =
                false;


            visualButton
                .classList
                .remove(
                    'btn-outline-secondary'
                );


            visualButton
                .classList
                .add(
                    'btn-primary'
                );


            htmlButton
                .classList
                .remove(
                    'btn-dark'
                );


            htmlButton
                .classList
                .add(
                    'btn-outline-dark'
                );


            previewButton
                .classList
                .add(
                    'd-none'
                );


            htmlNotice
                .classList
                .add(
                    'd-none'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | HTML MODE UI
        |--------------------------------------------------------------------------
        */

        function showHtmlMode()
        {

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |
            | Disable Visual Editor completely.
            |
            | This prevents CKEditor from touching raw HTML.
            |--------------------------------------------------------------------------
            */

            visualButton.disabled =
                true;


            visualButton
                .classList
                .remove(
                    'btn-primary'
                );


            visualButton
                .classList
                .add(
                    'btn-outline-secondary'
                );


            htmlButton
                .classList
                .remove(
                    'btn-outline-dark'
                );


            htmlButton
                .classList
                .add(
                    'btn-dark'
                );


            previewButton
                .classList
                .remove(
                    'd-none'
                );


            htmlNotice
                .classList
                .remove(
                    'd-none'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | START VISUAL EDITOR
        |--------------------------------------------------------------------------
        |
        | CKEditor is allowed ONLY for visual pages.
        |--------------------------------------------------------------------------
        */

        function startVisualEditor()
        {

            if (
                editorInstance
                ||
                !textarea
            ) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Absolute HTML Protection
            |--------------------------------------------------------------------------
            */

            if (
                contentMode.value
                === 'html'
            ) {

                return;

            }


            ClassicEditor
                .create(
                    textarea,
                    {

                        toolbar: {

                            items: [

                                'heading',

                                '|',

                                'bold',

                                'italic',

                                'link',

                                '|',

                                'bulletedList',

                                'numberedList',

                                '|',

                                'blockQuote',

                                'insertTable',

                                '|',

                                'undo',

                                'redo'

                            ]

                        }

                    }
                )
                .then(
                    function (editor) {

                        editorInstance =
                            editor;


                        contentMode.value =
                            'visual';


                        showVisualMode();

                    }
                )
                .catch(
                    function (error) {

                        console.error(
                            'CKEditor initialization error:',
                            error
                        );

                    }
                );

        }


        /*
        |--------------------------------------------------------------------------
        | VISUAL -> HTML
        |--------------------------------------------------------------------------
        */

        function switchToHtml()
        {

            /*
            |--------------------------------------------------------------------------
            | Already HTML
            |--------------------------------------------------------------------------
            */

            if (!editorInstance) {

                contentMode.value =
                    'html';


                textarea.style.display =
                    'block';


                textarea.style.minHeight =
                    '520px';


                showHtmlMode();


                textarea.focus();


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Get Current CKEditor HTML
            |--------------------------------------------------------------------------
            */

            const currentContent =
                editorInstance
                    .getData();


            /*
            |--------------------------------------------------------------------------
            | Destroy CKEditor
            |--------------------------------------------------------------------------
            */

            editorInstance
                .destroy()
                .then(
                    function () {

                        editorInstance =
                            null;


                        /*
                        |--------------------------------------------------------------------------
                        | Put current content into textarea
                        |--------------------------------------------------------------------------
                        */

                        textarea.value =
                            currentContent;


                        /*
                        |--------------------------------------------------------------------------
                        | Convert page to protected HTML mode
                        |--------------------------------------------------------------------------
                        */

                        contentMode.value =
                            'html';


                        textarea.style.display =
                            'block';


                        textarea.style.minHeight =
                            '520px';


                        showHtmlMode();


                        textarea.focus();

                    }
                )
                .catch(
                    function (error) {

                        console.error(
                            'CKEditor destroy error:',
                            error
                        );

                    }
                );

        }


        /*
        |--------------------------------------------------------------------------
        | HTML BUTTON
        |--------------------------------------------------------------------------
        */

        if (htmlButton) {

            htmlButton
                .addEventListener(
                    'click',
                    function () {

                        switchToHtml();

                    }
                );

        }


        /*
        |--------------------------------------------------------------------------
        | VISUAL BUTTON
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | Once page is HTML, Visual Editor is disabled.
        |--------------------------------------------------------------------------
        */

        if (visualButton) {

            visualButton
                .addEventListener(
                    'click',
                    function () {

                        if (
                            contentMode.value
                            === 'html'
                        ) {

                            return;

                        }


                        startVisualEditor();

                    }
                );

        }


        /*
        |--------------------------------------------------------------------------
        | HTML PREVIEW
        |--------------------------------------------------------------------------
        */

        if (previewButton) {

            previewButton
                .addEventListener(
                    'click',
                    function () {

                        const html =
                            textarea.value;


                        const previewDocument =
                            previewFrame
                                .contentDocument
                            ||
                            previewFrame
                                .contentWindow
                                .document;


                        previewDocument.open();


                        previewDocument.write(
                            `
                            <!DOCTYPE html>

                            <html>

                            <head>

                                <meta charset="UTF-8">

                                <meta
                                    name="viewport"
                                    content="width=device-width, initial-scale=1"
                                >

                                <link
                                    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                                    rel="stylesheet"
                                >

                                <link
                                    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
                                    rel="stylesheet"
                                >

                                <style>

                                    body {

                                        padding:
                                            25px;

                                        background:
                                            #ffffff;

                                    }


                                    img {

                                        max-width:
                                            100%;

                                        height:
                                            auto;

                                    }

                                </style>

                            </head>


                            <body>

                                ${html}

                            </body>

                            </html>
                            `
                        );


                        previewDocument.close();


                        previewContainer
                            .classList
                            .remove(
                                'd-none'
                            );


                        previewContainer
                            .scrollIntoView({

                                behavior:
                                    'smooth',

                                block:
                                    'start',

                            });

                    }
                );

        }


        /*
        |--------------------------------------------------------------------------
        | CLOSE PREVIEW
        |--------------------------------------------------------------------------
        */

        if (closePreviewButton) {

            closePreviewButton
                .addEventListener(
                    'click',
                    function () {

                        previewContainer
                            .classList
                            .add(
                                'd-none'
                            );

                    }
                );

        }


        /*
        |--------------------------------------------------------------------------
        | FORM SUBMIT
        |--------------------------------------------------------------------------
        */

        if (form) {

            form.addEventListener(
                'submit',
                function () {

                    /*
                    |--------------------------------------------------------------------------
                    | Visual Mode
                    |--------------------------------------------------------------------------
                    */

                    if (
                        contentMode.value
                        === 'visual'
                        &&
                        editorInstance
                    ) {

                        textarea.value =
                            editorInstance
                                .getData();

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | HTML Mode
                    |--------------------------------------------------------------------------
                    |
                    | DO NOTHING.
                    |
                    | Raw HTML is submitted directly.
                    |--------------------------------------------------------------------------
                    */

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | INITIAL PAGE MODE
        |--------------------------------------------------------------------------
        */

        if (
            contentMode.value
            === 'html'
        ) {

            /*
            |--------------------------------------------------------------------------
            | Existing HTML page
            |--------------------------------------------------------------------------
            |
            | CKEditor is NEVER started.
            |--------------------------------------------------------------------------
            */

            textarea.style.display =
                'block';


            textarea.style.minHeight =
                '520px';


            showHtmlMode();

        } else {

            /*
            |--------------------------------------------------------------------------
            | Normal visual page
            |--------------------------------------------------------------------------
            */

            startVisualEditor();

        }

    }
);

</script>

@endpush

@endonce


{{-- =============================================================
     STYLES
============================================================= --}}

@once

@push('styles')

<style>

    /*
    |--------------------------------------------------------------------------
    | CKEditor
    |--------------------------------------------------------------------------
    */

    .ck-editor__editable {

        min-height:
            420px;

    }


    /*
    |--------------------------------------------------------------------------
    | HTML Source
    |--------------------------------------------------------------------------
    */

    #content {

        resize:
            vertical;

        tab-size:
            4;

    }


    /*
    |--------------------------------------------------------------------------
    | Preview
    |--------------------------------------------------------------------------
    */

    #htmlPreviewFrame {

        border-radius:
            6px;

    }


    /*
    |--------------------------------------------------------------------------
    | Responsive
    |--------------------------------------------------------------------------
    */

    @media (
        max-width:767.98px
    ) {

        .ck-editor__editable {

            min-height:
                320px;

        }


        #content {

            min-height:
                380px !important;

        }


        #htmlPreviewFrame {

            min-height:
                500px !important;

        }

    }

</style>

@endpush

@endonce