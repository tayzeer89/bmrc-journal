@extends('admin.layouts.app')

@section('title', 'Journal Website Content')

@section('page_title', 'Journal Website Content')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         PAGE HEADER
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

            <h4 class="mb-1">
                Journal Website Content
            </h4>

            <p class="text-muted mb-0">
                Manage public journal website pages.
            </p>

        </div>


        <a
            href="{{ route('admin.journal-pages.create') }}"
            class="btn btn-primary"
        >

            <i class="bi bi-plus-circle me-1"></i>

            Add Page

        </a>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
         CONTENT TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table
                    class="table
                           table-hover
                           align-middle
                           mb-0"
                >

                    <thead class="table-light">

                        <tr>

                            <th style="width:70px;">
                                #
                            </th>

                            <th>
                                Title
                            </th>

                            <th>
                                Menu Group
                            </th>

                            <th>
                                Content Mode
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Homepage
                            </th>

                            <th>
                                Menu
                            </th>

                            <th>
                                Sort Order
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(
                            $journalPages
                            as $journalPage
                        )

                            <tr>

                                {{-- =================================
                                     SERIAL
                                ================================== --}}

                                <td>

                                    {{ $journalPages->firstItem()
                                        + $loop->index }}

                                </td>


                                {{-- =================================
                                     TITLE
                                ================================== --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ $journalPage->title }}

                                    </div>


                                    <small class="text-muted">

                                        {{ $journalPage->slug }}

                                    </small>

                                </td>


                                {{-- =================================
                                     MENU GROUP
                                ================================== --}}

                                <td>

                                    <span class="badge bg-secondary">

                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $journalPage->menu_group
                                            )
                                        ) }}

                                    </span>

                                </td>


                                {{-- =================================
                                     CONTENT MODE
                                ================================== --}}

                                <td>

                                    @if(
                                        $journalPage->content_mode
                                        === 'html'
                                    )

                                        <span class="badge bg-dark">

                                            <i
                                                class="bi
                                                       bi-code-slash
                                                       me-1"
                                            ></i>

                                            HTML

                                        </span>

                                    @else

                                        <span class="badge bg-primary">

                                            <i
                                                class="bi
                                                       bi-pencil-square
                                                       me-1"
                                            ></i>

                                            Visual

                                        </span>

                                    @endif

                                </td>


                                {{-- =================================
                                     STATUS
                                ================================== --}}

                                <td>

                                    @if(
                                        $journalPage->status
                                        === 'published'
                                    )

                                        <span class="badge bg-success">

                                            Published

                                        </span>

                                    @elseif(
                                        $journalPage->status
                                        === 'draft'
                                    )

                                        <span
                                            class="badge
                                                   bg-warning
                                                   text-dark"
                                        >

                                            Draft

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- =================================
                                     HOMEPAGE
                                ================================== --}}

                                <td>

                                    @if(
                                        $journalPage
                                            ->show_on_homepage
                                    )

                                        <span class="badge bg-success">

                                            Yes

                                        </span>

                                    @else

                                        <span
                                            class="badge
                                                   bg-light
                                                   text-dark
                                                   border"
                                        >

                                            No

                                        </span>

                                    @endif

                                </td>


                                {{-- =================================
                                     MENU VISIBILITY
                                ================================== --}}

                                <td>

                                    @if(
                                        $journalPage->show_in_menu
                                    )

                                        <span class="badge bg-success">

                                            Yes

                                        </span>

                                    @else

                                        <span
                                            class="badge
                                                   bg-light
                                                   text-dark
                                                   border"
                                        >

                                            No

                                        </span>

                                    @endif

                                </td>


                                {{-- =================================
                                     SORT ORDER
                                ================================== --}}

                                <td>

                                    {{ $journalPage->sort_order }}

                                </td>


                                {{-- =================================
                                     ACTIONS
                                ================================== --}}

                                <td class="text-end">

                                    <div
                                        class="d-inline-flex
                                               gap-1"
                                    >

                                        {{-- =========================
                                             VIEW PUBLIC PAGE
                                        ========================== --}}

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
                                                class="btn
                                                       btn-sm
                                                       btn-outline-success"
                                                title="View Page"
                                            >

                                                <i class="bi bi-eye"></i>

                                            </a>

                                        @endif


                                        {{-- =========================
                                             EDIT
                                        ========================== --}}

                                        <a
                                            href="{{ route(
                                                'admin.journal-pages.edit',
                                                $journalPage
                                            ) }}"
                                            class="btn
                                                   btn-sm
                                                   btn-outline-primary"
                                            title="Edit Page"
                                        >

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        {{-- =========================
                                             DELETE
                                        ========================== --}}

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.journal-pages.destroy',
                                                $journalPage
                                            ) }}"
                                            class="d-inline"
                                            onsubmit="
                                                return confirm(
                                                    'Are you sure you want to delete this page?'
                                                );
                                            "
                                        >

                                            @csrf

                                            @method('DELETE')


                                            <button
                                                type="submit"
                                                class="btn
                                                       btn-sm
                                                       btn-outline-danger"
                                                title="Delete Page"
                                            >

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            {{-- =====================================
                                 EMPTY STATE
                            ====================================== --}}

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center
                                           py-5"
                                >

                                    <i
                                        class="bi
                                               bi-file-earmark-text
                                               fs-1
                                               text-muted"
                                    ></i>


                                    <div
                                        class="mt-2
                                               fw-semibold"
                                    >

                                        No journal website pages found.

                                    </div>


                                    <div
                                        class="small
                                               text-muted
                                               mb-3"
                                    >

                                        Create your first website
                                        content page.

                                    </div>


                                    <a
                                        href="{{ route(
                                            'admin.journal-pages.create'
                                        ) }}"
                                        class="btn
                                               btn-sm
                                               btn-primary"
                                    >

                                        <i
                                            class="bi
                                                   bi-plus-circle
                                                   me-1"
                                        ></i>

                                        Add Page

                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
             PAGINATION
        ====================================================== --}}

        @if(
            $journalPages->hasPages()
        )

            <div class="card-footer bg-white">

                <div
                    class="d-flex
                           flex-wrap
                           justify-content-between
                           align-items-center
                           gap-3"
                >

                    <small class="text-muted">

                        Showing

                        {{ $journalPages->firstItem() }}

                        to

                        {{ $journalPages->lastItem() }}

                        of

                        {{ $journalPages->total() }}

                        pages

                    </small>


                    <div>

                        {{ $journalPages->links('pagination::bootstrap-4') }}

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection