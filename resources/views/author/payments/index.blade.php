@extends('author.layouts.app')

@section('title', 'My Payments')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">

        <div>
            <h3 class="mb-1 fw-bold">
                My Payments & Invoices
            </h3>

            <p class="text-muted mb-0">
                View your invoices and submit payment information.
            </p>
        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-3">
                                Invoice No
                            </th>

                            <th>
                                Manuscript
                            </th>

                            <th>
                                Fee Type
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Payment Status
                            </th>

                            <th>
                                Verification
                            </th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($payments as $payment)

                            <tr>

                                {{-- Invoice --}}
                                <td class="px-3">

                                    <strong>
                                        {{ $payment->invoice_no }}
                                    </strong>

                                    @if($payment->payment_deadline)

                                        <div class="small text-muted mt-1">

                                            Due:
                                            {{ \Carbon\Carbon::parse($payment->payment_deadline)->format('d M Y') }}

                                        </div>

                                    @endif

                                </td>


                                {{-- Manuscript --}}
                                <td>

                                    <div class="fw-semibold">
                                        {{ Str::limit($payment->manuscript->title ?? 'N/A', 45) }}
                                    </div>

                                    @if($payment->manuscript)

                                        <small class="text-muted">
                                            {{ $payment->manuscript->manuscript_id }}
                                        </small>

                                    @endif

                                </td>


                                {{-- Fee Type --}}
                                <td>

                                    {{ $payment->fee_type }}

                                </td>


                                {{-- Amount --}}
                                <td>

                                    <strong>
                                        {{ number_format($payment->amount, 2) }}
                                    </strong>

                                    {{ $payment->currency }}

                                </td>


                                {{-- Payment Status --}}
                                <td>

                                    @if($payment->payment_status === 'pending')

                                        <span class="badge bg-warning text-dark">
                                            Payment Required
                                        </span>

                                    @elseif($payment->payment_status === 'submitted')

                                        <span class="badge bg-info">
                                            Submitted
                                        </span>

                                    @elseif($payment->payment_status === 'paid')

                                        <span class="badge bg-success">
                                            Paid
                                        </span>

                                    @elseif($payment->payment_status === 'rejected')

                                        <span class="badge bg-danger">
                                            Rejected
                                        </span>

                                    @elseif($payment->payment_status === 'cancelled')

                                        <span class="badge bg-secondary">
                                            Cancelled
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ ucfirst($payment->payment_status) }}
                                        </span>

                                    @endif

                                </td>


                                {{-- Verification Status --}}
                                <td>

                                    @if($payment->verification_status === 'verified')

                                        <span class="badge bg-success">
                                            Verified
                                        </span>

                                    @elseif($payment->verification_status === 'rejected')

                                        <span class="badge bg-danger">
                                            Rejected
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Pending
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="text-center">

                                    <div class="d-flex justify-content-center gap-1 flex-wrap">


                                        {{-- PAY NOW --}}
                                        @if(
                                            $payment->payment_status === 'pending' &&
                                            $payment->manuscript &&
                                            $payment->manuscript->status === 'payment_required'
                                        )

                                            <a
                                                href="{{ route('author.payments.show', $payment->id) }}"
                                                class="btn btn-sm btn-success"
                                            >

                                                <i class="bi bi-credit-card me-1"></i>

                                                Pay Now

                                            </a>


                                        {{-- UPDATE PAYMENT --}}
                                        @elseif(
                                            $payment->payment_status === 'submitted' &&
                                            $payment->verification_status === 'rejected'
                                        )

                                            <a
                                                href="{{ route('author.payments.show', $payment->id) }}"
                                                class="btn btn-sm btn-warning"
                                            >

                                                <i class="bi bi-pencil-square me-1"></i>

                                                Update Payment

                                            </a>


                                        {{-- PAYMENT SUBMITTED --}}
                                        @elseif($payment->payment_status === 'submitted')

                                            <a
                                                href="{{ route('author.payments.show', $payment->id) }}"
                                                class="btn btn-sm btn-info text-white"
                                            >

                                                <i class="bi bi-eye me-1"></i>

                                                View

                                            </a>


                                        {{-- VERIFIED --}}
                                        @elseif(
                                            $payment->payment_status === 'paid' &&
                                            $payment->verification_status === 'verified'
                                        )

                                            <a
                                                href="{{ route('author.payments.show', $payment->id) }}"
                                                class="btn btn-sm btn-success"
                                            >

                                                <i class="bi bi-check-circle me-1"></i>

                                                Paid

                                            </a>


                                        {{-- DEFAULT VIEW --}}
                                        @else

                                            <a
                                                href="{{ route('author.payments.show', $payment->id) }}"
                                                class="btn btn-sm btn-primary"
                                            >

                                                <i class="bi bi-eye me-1"></i>

                                                View

                                            </a>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i class="bi bi-receipt fs-1 d-block mb-3"></i>

                                        <h5>
                                            No Payments Found
                                        </h5>

                                        <p class="mb-0">
                                            You don't have any payment invoices yet.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if(method_exists($payments, 'links'))

                <div class="p-3">

                    {{ $payments->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection