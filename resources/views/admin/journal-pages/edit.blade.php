@extends('admin.layouts.app')


@section(
    'title',
    'Edit Website Page'
)


@section(
    'page_title',
    'Edit Website Page'
)


@section('content')

<div class="container-fluid">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div
        class="d-flex
               flex-wrap
               justify-content-between
               align-items-center
               gap-3
               mb-4"
    >

        <div>

            <h1 class="h3 mb-1">
                Edit Website Page
            </h1>

            <p class="text-muted mb-0">
                {{ $journalPage->title }}
            </p>

        </div>


        <div class="d-flex gap-2">


            @if(
                $journalPage->status
                === 'published'
            )

                <a
                    href="{{ route(
                        'journal.page',
                        $journalPage->slug
                    ) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn btn-outline-primary"
                >

                    <i
                        class="bi
                               bi-box-arrow-up-right
                               me-1"
                    ></i>

                    View Page

                </a>

            @endif


            <a
                href="{{ route(
                    'admin.journal-pages.index'
                ) }}"
                class="btn btn-outline-secondary"
            >

                <i class="bi bi-arrow-left me-1"></i>

                Back

            </a>

        </div>

    </div>


    {{-- =========================================================
         HTML MODE INFORMATION
    ========================================================== --}}

    @if(
        $journalPage->content_mode
        === 'html'
    )

        <div class="alert alert-warning">

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

                        This page is using protected HTML mode.

                        CKEditor will not process the stored HTML.

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
         VALIDATION
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>


            <ul class="mb-0 mt-2">

                @foreach(
                    $errors->all()
                    as $error
                )

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         FORM
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0">

                <i
                    class="bi
                           bi-pencil-square
                           me-1"
                ></i>

                Page Information

            </h5>

        </div>


        <div class="card-body p-4">

            <form
                action="{{ route(
                    'admin.journal-pages.update',
                    $journalPage
                ) }}"
                method="POST"
                enctype="multipart/form-data"
                id="journalPageForm"
            >

                @csrf

                @method('PUT')


                @include(
                    'admin.journal-pages._form',
                    [
                        'journalPage'
                            => $journalPage
                    ]
                )


                <div
                    class="d-flex
                           justify-content-end
                           gap-2
                           border-top
                           mt-4
                           pt-4"
                >

                    <a
                        href="{{ route(
                            'admin.journal-pages.index'
                        ) }}"
                        class="btn btn-light border"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i
                            class="bi
                                   bi-check-circle
                                   me-1"
                        ></i>

                        Update Page

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection