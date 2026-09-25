@extends('admin.layouts.app')

@section('title', 'Payment Verification')

@section('content')

<div class="container-fluid">

    {{-- ================================================================
         Page Header
    ================================================================= --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-shield-check me-2"></i>
                Payment Verification
            </h4>

            <p class="text-muted mb-0">
                Review and verify author payment submissions.
            </p>
        </div>

        <div class="mt-2 mt-md-0">

            <span class="badge bg-warning text-dark fs-6">
                <i class="bi bi-hourglass-split me-1"></i>
                {{ $payments->total() }} Pending
            </span>

        </div>

    </div>


    {{-- ================================================================
         Success Message
    ================================================================= --}}

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


    {{-- ================================================================
         Error Message
    ================================================================= --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ================================================================
         Verification Queue
    ================================================================= --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-credit-card-2-front me-2"></i>
                    Payments Awaiting Verification
                </h6>

                <span class="text-muted small">
                    Showing {{ $payments->count() }}
                    of {{ $payments->total() }}
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($payments->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="ps-3">
                                    #
                                </th>

                                <th>
                                    Invoice
                                </th>

                                <th>
                                    Manuscript
                                </th>

                                <th>
                                    Author
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Payment Method
                                </th>

                                <th>
                                    Transaction ID
                                </th>

                                <th>
                                    Submitted
                                </th>

                                <th class="text-center">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($payments as $payment)

                                <tr>

                                    {{-- Number --}}

                                    <td class="ps-3">

                                        {{ $payments->firstItem() + $loop->index }}

                                    </td>


                                    {{-- Invoice --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $payment->invoice_no }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $payment->fee_type }}

                                        </small>

                                    </td>


                                    {{-- Manuscript --}}

                                    <td style="min-width: 220px;">

                                        @if($payment->manuscript)

                                            <div class="fw-semibold">

                                                {{ \Illuminate\Support\Str::limit(
                                                    $payment->manuscript->title,
                                                    70
                                                ) }}

                                            </div>

                                            @if($payment->manuscript->articleType)

                                                <small class="text-muted">

                                                    {{ $payment->manuscript->articleType->name }}

                                                </small>

                                            @endif

                                        @else

                                            <span class="text-danger">
                                                Manuscript not found
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Author --}}

                                    <td>

                                        @if(
                                            $payment->manuscript &&
                                            $payment->manuscript->submitter
                                        )

                                            <div class="fw-semibold">

                                                {{ $payment->manuscript->submitter->name }}

                                            </div>

                                            <small class="text-muted">

                                                {{ $payment->manuscript->submitter->email }}

                                            </small>

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Amount --}}

                                    <td>

                                        <span class="fw-bold">

                                            {{ $payment->currency }}
                                            {{ number_format(
                                                (float) $payment->amount,
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- Payment Method --}}

                                    <td>

                                        @if($payment->payment_method)

                                            <span class="badge bg-info text-dark">

                                                {{ $payment->payment_method }}

                                            </span>

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                        @if($payment->payment_gateway)

                                            <div class="small text-muted mt-1">

                                                {{ $payment->payment_gateway }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Transaction ID --}}

                                    <td>

                                        @if($payment->transaction_id)

                                            <code>
                                                {{ $payment->transaction_id }}
                                            </code>

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Submitted Date --}}

                                    <td>

                                        @if($payment->payment_date)

                                            <div>

                                                {{ $payment->payment_date->format('d M Y') }}

                                            </div>

                                            <small class="text-muted">

                                                {{ $payment->payment_date->format('h:i A') }}

                                            </small>

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Action --}}

                                    <td class="text-center">

                                        <a
                                            href="{{ route(
                                                'admin.payments.verification.show',
                                                $payment
                                            ) }}"
                                            class="btn btn-sm btn-primary"
                                        >

                                            <i class="bi bi-eye me-1"></i>

                                            Review

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- Empty State --}}

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="bi bi-check-circle-fill text-success"
                            style="font-size: 3rem;"
                        ></i>

                    </div>

                    <h5 class="mb-2">
                        No Payments Awaiting Verification
                    </h5>

                    <p class="text-muted mb-0">

                        There are currently no author payment submissions
                        waiting for verification.

                    </p>

                </div>

            @endif

        </div>


        {{-- ============================================================
             Pagination
        ============================================================= --}}

        @if($payments->hasPages())

            <div class="card-footer bg-white">

                <div class="d-flex justify-content-center">

                    {{ $payments->links('pagination::bootstrap-4') }}

                </div>

            </div>

        @endif

    </div>

</div>

@endsection