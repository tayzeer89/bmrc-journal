@extends('admin.layouts.app')

@section('title', 'Editor Assignment Queue')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="fas fa-user-edit me-2"></i>
                Editor Assignment Queue
            </h4>

            <p class="text-muted mb-0">
                Manuscripts ready for Handling Editor assignment
            </p>
        </div>

        <span class="badge bg-primary fs-6">
            {{ $manuscripts->total() }} Pending
        </span>
    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Error Messages --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                Manuscripts Awaiting Assignment
            </h5>
        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Manuscript</th>
                            <th>Article Type</th>
                            <th>Submitted</th>
                            <th>Technical</th>
                            <th>Payment</th>
                            <th>Similarity</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($manuscripts as $manuscript)

                            <tr>

                                <td>
                                    {{ $manuscripts->firstItem() + $loop->index }}
                                </td>


                                {{-- Manuscript --}}
                                <td style="min-width: 260px;">

                                    <strong>
                                        {{ $manuscript->manuscript_id ?? 'N/A' }}
                                    </strong>

                                    <div class="small mt-1">
                                        {{ \Illuminate\Support\Str::limit(
                                            $manuscript->title,
                                            80
                                        ) }}
                                    </div>

                                    @if($manuscript->journal)
                                        <div class="small text-muted mt-1">
                                            {{ $manuscript->journal->name }}
                                        </div>
                                    @endif

                                </td>


                                {{-- Article Type --}}
                                <td>
                                    {{ $manuscript->articleType->name ?? 'N/A' }}
                                </td>


                                {{-- Submitted --}}
                                <td>
                                    @if($manuscript->submitted_at)
                                        {{ $manuscript->submitted_at->format('d M Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>


                                {{-- Technical Check --}}
                                <td>

                                    @if($manuscript->latestTechnicalCheck)

                                        <span class="badge bg-success">
                                            Completed
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Payment --}}
                                <td>

                                    @if($manuscript->latestPayment)

                                        @php
                                            $paymentStatus =
                                                strtolower(
                                                    $manuscript
                                                        ->latestPayment
                                                        ->status ?? ''
                                                );
                                        @endphp

                                        @if(
                                            in_array(
                                                $paymentStatus,
                                                ['verified', 'payment_verified']
                                            )
                                        )
                                            <span class="badge bg-success">
                                                Verified
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                {{ ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $paymentStatus ?: 'Pending'
                                                    )
                                                ) }}
                                            </span>
                                        @endif

                                    @else

                                        <span class="badge bg-secondary">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Similarity --}}
                                <td>

                                    @if($manuscript->latestSimilarityCheck)

                                        @php
                                            $similarity =
                                                $manuscript
                                                    ->latestSimilarityCheck
                                                    ->similarity_percentage
                                                ??
                                                $manuscript
                                                    ->latestSimilarityCheck
                                                    ->similarity_score
                                                ??
                                                null;
                                        @endphp

                                        @if(!is_null($similarity))

                                            <span
                                                class="badge
                                                {{ $similarity < 20
                                                    ? 'bg-success'
                                                    : 'bg-warning text-dark' }}">

                                                {{ $similarity }}%

                                            </span>

                                        @else

                                            <span class="badge bg-success">
                                                Checked
                                            </span>

                                        @endif

                                    @else

                                        <span class="badge bg-secondary">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Workflow Status --}}
                                <td>

                                    <span class="badge bg-info text-dark">
                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $manuscript->status
                                            )
                                        ) }}
                                    </span>

                                </td>


                                {{-- Action --}}
                                <td class="text-center">

                                    <a
                                        href="{{ route(
                                            'eic.editor-assignment.show',
                                            $manuscript
                                        ) }}"
                                        class="btn btn-primary btn-sm">

                                        <i class="fas fa-eye me-1"></i>
                                        Review & Assign

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="9"
                                    class="text-center py-5">

                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>

                                    <h6>
                                        No manuscripts awaiting editor assignment.
                                    </h6>

                                    <p class="text-muted mb-0">
                                        Manuscripts will appear here after
                                        completion of the similarity check.
                                    </p>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($manuscripts->hasPages())

            <div class="card-footer bg-white">

                {{ $manuscripts->links() }}

            </div>

        @endif

    </div>

</div>

@endsection