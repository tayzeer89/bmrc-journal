@extends('admin.layouts.app')

@section('title', 'Editorial Assessment')

@section('content')

<div class="container-fluid py-4">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-clipboard-check"></i>
                Editorial Assessment
            </h4>

            <p class="text-muted mb-0">
                Review manuscripts assigned for scientific and editorial assessment.
            </p>
        </div>

    </div>


    {{-- =====================================================
         SUCCESS / ERROR MESSAGES
    ====================================================== --}}

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


    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-1"></i>
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif


    {{-- =====================================================
         SEARCH
    ====================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('handling-editor.assessment.index') }}"
            >

                <div class="row g-2">

                    <div class="col-md-9">

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Search by Manuscript ID or Article Title"
                        >

                    </div>

                    <div class="col-md-3">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            <i class="bi bi-search"></i>
                            Search
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
         MANUSCRIPT LIST
    ====================================================== --}}

    <div class="card shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <strong>
                <i class="bi bi-journal-medical me-1"></i>
                Manuscripts Awaiting Editorial Assessment
            </strong>

            <span class="badge bg-primary">
                {{ $manuscripts->total() }}
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th width="60">#</th>

                            <th>
                                Manuscript
                            </th>

                            <th>
                                Article Type
                            </th>

                            <th>
                                Journal
                            </th>

                            <th>
                                Handling Editor
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="150">
                                Action
                            </th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse($manuscripts as $manuscript)

                            <tr>

                                {{-- Serial --}}
                                <td>
                                    {{ $manuscripts->firstItem() + $loop->index }}
                                </td>


                                {{-- Manuscript --}}
                                <td>

                                    <div class="fw-semibold text-primary">
                                        {{ $manuscript->manuscript_id }}
                                    </div>

                                    <div class="mt-1">

                                        {{ \Illuminate\Support\Str::limit(
                                            strip_tags($manuscript->title),
                                            90
                                        ) }}

                                    </div>

                                </td>


                                {{-- Article Type --}}
                                <td>

                                    {{ $manuscript->articleType->name
                                        ?? 'N/A' }}

                                </td>


                                {{-- Journal --}}
                                <td>

                                    {{ $manuscript->journal->name
                                        ?? 'N/A' }}

                                </td>


                                {{-- Handling Editor --}}
                                <td>

                                    @if($manuscript->handlingEditor)

                                        <div class="fw-semibold">
                                            {{ $manuscript->handlingEditor->name }}
                                        </div>

                                        @if($manuscript->handlingEditor->email)

                                            <small class="text-muted">
                                                {{ $manuscript->handlingEditor->email }}
                                            </small>

                                        @endif

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    <span class="badge bg-info text-dark">
                                        Editorial Assessment
                                    </span>

                                </td>


                                {{-- Action --}}
                                <td>

                                    <a
                                        href="{{ route(
                                            'handling-editor.assessment.show',
                                            $manuscript->id
                                        ) }}"
                                        class="btn btn-sm btn-primary"
                                    >
                                        <i class="bi bi-clipboard-check"></i>
                                        Assess
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>

                                        <h6>
                                            No manuscripts awaiting assessment
                                        </h6>

                                        <p class="mb-0 small">
                                            Accepted Handling Editor assignments
                                            will appear here.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($manuscripts->hasPages())

            <div class="card-footer bg-white">

                {{ $manuscripts->links() }}

            </div>

        @endif

    </div>

</div>

@endsection