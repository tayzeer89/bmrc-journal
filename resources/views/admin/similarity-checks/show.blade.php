@extends('admin.layouts.app')

@section('title', 'Similarity Check Details')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="d-flex
                justify-content-between
                align-items-start
                flex-wrap
                gap-3
                mb-4">

        <div>

            <div class="mb-2">

                <span class="badge bg-light text-dark border">

                    {{ $manuscript->manuscript_id }}

                </span>

            </div>

            <h3 class="fw-bold mb-2">
                Similarity Check
            </h3>

            <p class="text-muted mb-0">

                {{ $manuscript->title }}

            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{
                    route(
                        'admin.similarity-checks.index'
                    )
                }}"
                class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Back to Queue

            </a>


            @if(
                !$manuscript->latestSimilarityCheck
                ||
                in_array(
                    $manuscript
                        ->latestSimilarityCheck
                        ?->status,
                    [
                        'review_required',
                        'returned_to_author'
                    ]
                )
            )

                <a
                    href="{{
                        route(
                            'admin.similarity-checks.create',
                            $manuscript
                        )
                    }}"
                    class="btn btn-primary">

                    <i class="bi bi-search me-1"></i>

                    {{
                        $manuscript->latestSimilarityCheck
                            ? 'New Similarity Check'
                            : 'Perform Similarity Check'
                    }}

                </a>

            @endif

        </div>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         WORKFLOW STATUS
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row text-center g-3">

                {{-- TECHNICAL --}}
                <div class="col-md">

                    <div class="text-success fs-4">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>

                    <div class="fw-semibold mt-1">
                        Technical Check
                    </div>

                    <small class="text-success">
                        Passed
                    </small>

                </div>


                {{-- PAYMENT --}}
                <div class="col-md">

                    <div class="text-success fs-4">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>

                    <div class="fw-semibold mt-1">
                        Payment
                    </div>

                    <small class="text-success">
                        Verified
                    </small>

                </div>


                {{-- SIMILARITY --}}
                <div class="col-md">

                    <div class="text-primary fs-4">
                        <i class="bi bi-search"></i>
                    </div>

                    <div class="fw-semibold mt-1">
                        Similarity Check
                    </div>

                    @if($manuscript->latestSimilarityCheck)

                        <small class="text-primary">

                            {{
                                ucfirst(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $manuscript
                                            ->latestSimilarityCheck
                                            ->status
                                    )
                                )
                            }}

                        </small>

                    @else

                        <small class="text-muted">
                            Pending
                        </small>

                    @endif

                </div>


                {{-- EDITOR ASSIGNMENT --}}
                <div class="col-md">

                    <div class="text-muted fs-4">
                        <i class="bi bi-person-check"></i>
                    </div>

                    <div class="fw-semibold mt-1">
                        Editor Assignment
                    </div>

                    <small class="text-muted">
                        Next Stage
                    </small>

                </div>


                {{-- EDITORIAL ASSESSMENT --}}
                <div class="col-md">

                    <div class="text-muted fs-4">
                        <i class="bi bi-clipboard-check"></i>
                    </div>

                    <div class="fw-semibold mt-1">
                        Editorial Assessment
                    </div>

                    <small class="text-muted">
                        Pending
                    </small>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        {{-- =====================================================
             LEFT SIDE
        ====================================================== --}}
        <div class="col-lg-8">

            {{-- MANUSCRIPT INFORMATION --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="fw-bold mb-0">

                        <i class="bi bi-file-earmark-text me-2"></i>

                        Manuscript Information

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <small class="text-muted d-block">
                                Manuscript ID
                            </small>

                            <strong>
                                {{ $manuscript->manuscript_id }}
                            </strong>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted d-block">
                                Article Type
                            </small>

                            <strong>
                                {{
                                    $manuscript->articleType->name
                                    ?? 'N/A'
                                }}
                            </strong>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted d-block">
                                Current Status
                            </small>

                            <span class="badge bg-primary">

                                {{
                                    ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $manuscript->status
                                        )
                                    )
                                }}

                            </span>

                        </div>


                        <div class="col-12">

                            <small class="text-muted d-block">
                                Article Title
                            </small>

                            <strong>
                                {{ $manuscript->title }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 SIMILARITY HISTORY
            ================================================== --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <div class="d-flex
                                justify-content-between
                                align-items-center">

                        <h5 class="fw-bold mb-0">

                            <i class="bi bi-clock-history me-2"></i>

                            Similarity Check History

                        </h5>

                        <span class="badge bg-secondary">

                            {{
                                $manuscript
                                    ->similarityChecks
                                    ->count()
                            }}
                            Check(s)

                        </span>

                    </div>

                </div>


                <div class="card-body">

                    @forelse(
                        $manuscript->similarityChecks
                            ->sortByDesc('check_number')
                        as $check
                    )

                        <div class="border rounded p-4 mb-3">

                            <div class="d-flex
                                        justify-content-between
                                        align-items-start
                                        flex-wrap
                                        gap-2
                                        mb-3">

                                <div>

                                    <h6 class="fw-bold mb-1">

                                        Similarity Check
                                        #{{ $check->check_number }}

                                    </h6>

                                    <small class="text-muted">

                                        {{
                                            $check->checked_at
                                                ?->format(
                                                    'd M Y, h:i A'
                                                )
                                            ?? 'N/A'
                                        }}

                                    </small>

                                </div>


                                <div>

                                    @if($check->status === 'passed')

                                        <span class="badge bg-success">
                                            Passed
                                        </span>

                                    @elseif(
                                        $check->status
                                        === 'review_required'
                                    )

                                        <span class="badge bg-warning text-dark">
                                            Review Required
                                        </span>

                                    @elseif(
                                        $check->status
                                        === 'returned_to_author'
                                    )

                                        <span class="badge bg-info text-dark">
                                            Returned to Author
                                        </span>

                                    @elseif(
                                        $check->status
                                        === 'escalated'
                                    )

                                        <span class="badge bg-danger">
                                            Escalated
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            {{
                                                ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $check->status
                                                    )
                                                )
                                            }}

                                        </span>

                                    @endif

                                </div>

                            </div>


                            <div class="row g-3">

                                <div class="col-md-3">

                                    <small class="text-muted d-block">
                                        Similarity
                                    </small>

                                    <span class="fs-4 fw-bold">

                                        {{
                                            number_format(
                                                $check
                                                    ->similarity_percentage,
                                                2
                                            )
                                        }}%

                                    </span>

                                </div>


                                <div class="col-md-3">

                                    <small class="text-muted d-block">
                                        Threshold
                                    </small>

                                    <strong>

                                        {{
                                            number_format(
                                                $check
                                                    ->threshold_percentage,
                                                2
                                            )
                                        }}%

                                    </strong>

                                </div>


                                <div class="col-md-3">

                                    <small class="text-muted d-block">
                                        Software
                                    </small>

                                    <strong>
                                        {{
                                            $check->software_name
                                            ?? 'N/A'
                                        }}
                                    </strong>

                                </div>


                                <div class="col-md-3">

                                    <small class="text-muted d-block">
                                        Checked By
                                    </small>

                                    <strong>
                                        {{
                                            $check->checkedBy->name
                                            ?? 'N/A'
                                        }}
                                    </strong>

                                </div>


                                @if($check->comments)

                                    <div class="col-12">

                                        <hr>

                                        <small class="text-muted d-block mb-1">
                                            Comments
                                        </small>

                                        <div>
                                            {{ $check->comments }}
                                        </div>

                                    </div>

                                @endif


                                @if($check->report_file)

                                    <div class="col-12">

                                        <a
                                            href="{{
                                                asset(
                                                    'storage/'
                                                    . $check->report_file
                                                )
                                            }}"
                                            target="_blank"
                                            class="btn btn-sm
                                                   btn-outline-primary">

                                            <i class="bi bi-file-pdf me-1"></i>

                                            View Similarity Report

                                        </a>

                                    </div>

                                @endif

                            </div>

                        </div>

                    @empty

                        <div class="text-center py-5">

                            <i class="bi bi-search fs-1 text-muted"></i>

                            <h5 class="mt-3">
                                Similarity Check Pending
                            </h5>

                            <p class="text-muted">
                                No similarity check has been
                                recorded for this manuscript.
                            </p>

                            <a
                                href="{{
                                    route(
                                        'admin.similarity-checks.create',
                                        $manuscript
                                    )
                                }}"
                                class="btn btn-primary">

                                Perform Similarity Check

                            </a>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- =====================================================
             RIGHT SIDE
        ====================================================== --}}
        <div class="col-lg-4">

            {{-- CURRENT RESULT --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="fw-bold mb-0">
                        Current Result
                    </h5>

                </div>


                <div class="card-body">

                    @if($manuscript->latestSimilarityCheck)

                        @php
                            $latest =
                                $manuscript
                                    ->latestSimilarityCheck;
                        @endphp

                        <div class="text-center mb-4">

                            <div class="display-5 fw-bold">

                                {{
                                    number_format(
                                        $latest
                                            ->similarity_percentage,
                                        2
                                    )
                                }}%

                            </div>

                            <div class="text-muted">
                                Overall Similarity
                            </div>

                        </div>


                        <hr>


                        <div class="d-flex
                                    justify-content-between
                                    mb-3">

                            <span class="text-muted">
                                Threshold
                            </span>

                            <strong>

                                {{
                                    number_format(
                                        $latest
                                            ->threshold_percentage,
                                        2
                                    )
                                }}%

                            </strong>

                        </div>


                        <div class="d-flex
                                    justify-content-between
                                    mb-3">

                            <span class="text-muted">
                                Check Number
                            </span>

                            <strong>
                                #{{ $latest->check_number }}
                            </strong>

                        </div>


                        <div class="d-flex
                                    justify-content-between">

                            <span class="text-muted">
                                Status
                            </span>

                            <strong>

                                {{
                                    ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $latest->status
                                        )
                                    )
                                }}

                            </strong>

                        </div>

                    @else

                        <div class="text-center py-3">

                            <i class="bi bi-hourglass
                                      fs-1
                                      text-muted">
                            </i>

                            <p class="text-muted mt-2 mb-0">
                                Waiting for similarity check.
                            </p>

                        </div>

                    @endif

                </div>

            </div>


            {{-- NEXT STEP --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="fw-bold mb-0">
                        Next Workflow Step
                    </h5>

                </div>


                <div class="card-body">

                    @if(
                        $manuscript->latestSimilarityCheck
                        &&
                        $manuscript
                            ->latestSimilarityCheck
                            ->status === 'passed'
                    )

                        <div class="text-success mb-3">

                            <i class="bi bi-check-circle-fill me-1"></i>

                            Similarity Check Passed

                        </div>

                        <h6 class="fw-bold">
                            Editor Assignment
                        </h6>

                        <p class="text-muted small">
                            This manuscript is now ready for
                            assignment to a Handling/Associate
                            Editor by the Editor-in-Chief.
                        </p>


                        @if(
                            Route::has(
                                'admin.editor-assignments.show'
                            )
                        )

                            <a
                                href="{{
                                    route(
                                        'admin.editor-assignments.show',
                                        $manuscript
                                    )
                                }}"
                                class="btn btn-primary w-100">

                                Continue to Editor Assignment

                                <i class="bi bi-arrow-right ms-1"></i>

                            </a>

                        @endif

                    @else

                        <p class="text-muted mb-0">

                            Editor Assignment will become
                            available after the similarity
                            check is passed.

                        </p>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection