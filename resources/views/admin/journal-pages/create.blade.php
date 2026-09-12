@extends('admin.layouts.app')

@section(
    'title',
    'Create Journal Website Page'
)

@section(
    'page_title',
    'Create Journal Website Page'
)


@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div
                class="d-flex
                       flex-wrap
                       justify-content-between
                       align-items-center
                       gap-3"
            >

                <div>

                    <h5 class="mb-1">

                        Create Journal Website Page

                    </h5>


                    <small class="text-muted">

                        Add content for the public journal website.

                    </small>

                </div>


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


        <div class="card-body">


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


            <form
                method="POST"
                action="{{ route(
                    'admin.journal-pages.store'
                ) }}"
                enctype="multipart/form-data"
                id="journalPageForm"
            >

                @csrf


                @include(
                    'admin.journal-pages._form'
                )


                <hr class="my-4">


                <div
                    class="d-flex
                           justify-content-end
                           gap-2"
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

                        <i class="bi bi-save me-1"></i>

                        Save Page

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection