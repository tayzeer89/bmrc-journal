@extends('admin.layouts.app')

@section('title', 'Verified Payments')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">
                <i class="bi bi-check-circle text-success me-2"></i>
                Verified Payments
            </h2>

            <p class="text-muted mb-0">
                Previously verified payment records
            </p>
        </div>

        <a href="{{ route('admin.payments.verification.index') }}"
           class="btn btn-primary">

            <i class="bi bi-hourglass-split me-1"></i>
            Pending Verification

        </a>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- =========================================================
         VERIFIED PAYMENTS TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Payment History
                </h5>

                <span class="badge bg-success">
                    {{ $payments->total() }} Verified
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($payments->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Manuscript</th>

                                <th>Author</th>

                                <th>Article Type</th>

                                <th>Amount</th>

                                <th>Payment Method</th>

                                <th>Verified By</th>

                                <th>Verified At</th>

                                <th class="text-center">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($payments as $payment)

                                <tr>

                                    {{-- ID --}}

                                    <td>
                                        <strong>
                                            #{{ $payment->id }}
                                        </strong>
                                    </td>


                                    {{-- Manuscript --}}

                                    <td>

                                        @if($payment->manuscript)

                                            <div>
                                                <strong>
                                                    {{ $payment->manuscript->manuscript_code
                                                        ?? 'MS-' . $payment->manuscript->id }}
                                                </strong>
                                            </div>

                                            <small class="text-muted">
                                                {{ Str::limit(
                                                    $payment->manuscript->title ?? 'Untitled Manuscript',
                                                    50
                                                ) }}
                                            </small>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Author --}}

                                    <td>

                                        @if($payment->manuscript?->submitter)

                                            {{ $payment->manuscript->submitter->name }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Article Type --}}

                                    <td>

                                        {{ $payment->manuscript?->articleType?->name ?? '—' }}

                                    </td>


                                    {{-- Amount --}}

                                    <td>

                                        <strong>
                                            ৳ {{ number_format($payment->amount ?? 0, 2) }}
                                        </strong>

                                    </td>


                                    {{-- Payment Method --}}

                                    <td>

                                        {{ $payment->payment_method ?? '—' }}

                                    </td>


                                    {{-- Verified By --}}

                                    <td>

                                        {{ $payment->verifiedBy?->name ?? 'System' }}

                                    </td>


                                    {{-- Verified At --}}

                                    <td>

                                        @if($payment->verified_at)

                                            <div>
                                                {{ $payment->verified_at->format('d M Y') }}
                                            </div>

                                            <small class="text-muted">
                                                {{ $payment->verified_at->format('h:i A') }}
                                            </small>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Action --}}

                                    <td class="text-center">

                                        <a href="{{ route(
                                            'admin.payments.show',
                                            $payment
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary">

                                            <i class="bi bi-eye me-1"></i>
                                            Details

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- =================================================
                     PAGINATION
                ================================================== --}}

                <div class="p-3">

                    {{ $payments->links() }}

                </div>

            @else

                <div class="text-center py-5">

                    <i class="bi bi-receipt fs-1 text-muted"></i>

                    <h5 class="mt-3">
                        No Verified Payments
                    </h5>

                    <p class="text-muted mb-0">
                        No payment has been verified yet.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection