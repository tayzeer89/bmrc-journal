@extends('admin.layouts.app')

@section('title', 'Similarity Check')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Similarity Check
            </h3>

            <p class="text-muted mb-0">
                Manage similarity screening for manuscripts
                after payment verification.
            </p>
        </div>

    </div>


    {{-- =========================================================
         FLASH MESSAGE
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
         MAIN CARD
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="fw-bold mb-0">
                        Similarity Check Queue
                    </h5>
                </div>

                <span class="badge bg-primary">
                    {{ $manuscripts->total() }} Manuscript(s)
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-4">
                                #
                            </th>

                            <th>
                                Manuscript
                            </th>

                            <th>
                                Article Type
                            </th>

                            <th>
                                Payment
                            </th>

                            <th>
                                Similarity
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end pe-4">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($manuscripts as $manuscript)

                        @php
                            $similarity =
                                $manuscript->latestSimilarityCheck;
                        @endphp

                        <tr>

                            {{-- SERIAL --}}
                            <td class="ps-4">

                                {{ $loop->iteration
                                    + ($manuscripts->currentPage() - 1)
                                    * $manuscripts->perPage()
                                }}

                            </td>


                            {{-- MANUSCRIPT --}}
                            <td style="min-width: 300px;">

                                <div class="fw-semibold">

                                    {{ $manuscript->manuscript_id }}

                                </div>

                                <div class="text-muted small">

                                    {{ \Illuminate\Support\Str::limit(
                                        $manuscript->title,
                                        80
                                    ) }}

                                </div>

                            </td>


                            {{-- ARTICLE TYPE --}}
                            <td>

                                {{ $manuscript->articleType->name
                                    ?? 'N/A'
                                }}

                            </td>


                            {{-- PAYMENT --}}
                            <td>

                                <span class="badge bg-success">

                                    <i class="bi bi-check-circle me-1"></i>

                                    Verified

                                </span>

                            </td>


                            {{-- SIMILARITY --}}
                            <td>

                                @if($similarity)

                                    <strong>
                                        {{
                                            number_format(
                                                $similarity
                                                    ->similarity_percentage,
                                                2
                                            )
                                        }}%
                                    </strong>

                                    <div class="small text-muted">

                                        Threshold:
                                        {{
                                            number_format(
                                                $similarity
                                                    ->threshold_percentage,
                                                2
                                            )
                                        }}%

                                    </div>

                                @else

                                    <span class="text-muted">
                                        Not checked
                                    </span>

                                @endif

                            </td>


                            {{-- STATUS --}}
                            <td>

                                @if(!$similarity)

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @elseif($similarity->status === 'passed')

                                    <span class="badge bg-success">
                                        Passed
                                    </span>

                                @elseif(
                                    $similarity->status
                                    === 'review_required'
                                )

                                    <span class="badge bg-warning text-dark">
                                        Review Required
                                    </span>

                                @elseif(
                                    $similarity->status
                                    === 'returned_to_author'
                                )

                                    <span class="badge bg-info text-dark">
                                        Returned to Author
                                    </span>

                                @elseif(
                                    $similarity->status
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
                                                    $similarity->status
                                                )
                                            )
                                        }}

                                    </span>

                                @endif

                            </td>


                            {{-- ACTION --}}
                            <td class="text-end pe-4">

                                <div class="btn-group">

                                    <a
                                        href="{{
                                            route(
                                                'admin.similarity-checks.show',
                                                $manuscript
                                            )
                                        }}"
                                        class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-eye"></i>

                                        View

                                    </a>


                                    @if(!$similarity)

                                        <a
                                            href="{{
                                                route(
                                                    'admin.similarity-checks.create',
                                                    $manuscript
                                                )
                                            }}"
                                            class="btn btn-sm btn-primary">

                                            <i class="bi bi-search"></i>

                                            Check

                                        </a>

                                    @elseif(
                                        in_array(
                                            $similarity->status,
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
                                            class="btn btn-sm btn-warning">

                                            <i class="bi bi-arrow-repeat"></i>

                                            Recheck

                                        </a>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5">

                                <i
                                    class="bi bi-file-earmark-check
                                           fs-1 text-muted">
                                </i>

                                <h5 class="mt-3">
                                    No Manuscripts Found
                                </h5>

                                <p class="text-muted mb-0">
                                    No manuscripts are currently
                                    waiting for similarity checking.
                                </p>

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
        @if($manuscripts->hasPages())

            <div class="card-footer bg-white">

                {{
                    $manuscripts->links('pagination::bootstrap-4')
                }}

            </div>

        @endif

    </div>

</div>

@endsection