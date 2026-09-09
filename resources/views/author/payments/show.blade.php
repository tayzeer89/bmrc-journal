@extends('author.layouts.app')

@section('title', 'Payment Details')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">

        <div>
            <h3 class="mb-1 fw-bold">
                Payment Details
            </h3>

            <p class="text-muted mb-0">
                View invoice details and submit your payment information.
            </p>
        </div>

        <a href="{{ route('author.payments.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Payments

        </a>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         ERROR MESSAGE
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <div class="row g-4">

        {{-- =====================================================
             LEFT: INVOICE INFORMATION
        ====================================================== --}}

        <div class="col-lg-7">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="bi bi-receipt me-2"></i>

                        Invoice Information

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        {{-- Invoice Number --}}
                        <div class="col-md-6">

                            <label class="form-label text-muted">
                                Invoice Number
                            </label>

                            <div class="fw-bold">
                                {{ $payment->invoice_no }}
                            </div>

                        </div>


                        {{-- Invoice Date --}}
                        <div class="col-md-6">

                            <label class="form-label text-muted">
                                Invoice Date
                            </label>

                            <div>

                                {{ $payment->invoice_date
                                    ? \Carbon\Carbon::parse($payment->invoice_date)->format('d M Y')
                                    : 'N/A'
                                }}

                            </div>

                        </div>


                        {{-- Fee Type --}}
                        <div class="col-md-6">

                            <label class="form-label text-muted">
                                Fee Type
                            </label>

                            <div>
                                {{ $payment->fee_type }}
                            </div>

                        </div>


                        {{-- Amount --}}
                        <div class="col-md-6">

                            <label class="form-label text-muted">
                                Amount
                            </label>

                            <div class="fs-4 fw-bold text-primary">

                                {{ number_format($payment->amount, 2) }}

                                {{ $payment->currency }}

                            </div>

                        </div>


                        {{-- Payment Deadline --}}
                        <div class="col-md-6">

                            <label class="form-label text-muted">
                                Payment Deadline
                            </label>

                            <div>

                                @if($payment->payment_deadline)

                                    {{ \Carbon\Carbon::parse(
                                        $payment->payment_deadline
                                    )->format('d M Y') }}

                                @else

                                    <span class="text-muted">
                                        No deadline specified
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- Payment Status --}}
                        <div class="col-md-6">

                            <label class="form-label text-muted">
                                Payment Status
                            </label>

                            <div>

                                @if($payment->payment_status === 'pending')

                                    <span class="badge bg-warning text-dark">
                                        Payment Required
                                    </span>

                                @elseif($payment->payment_status === 'submitted')

                                    <span class="badge bg-info">
                                        Payment Submitted
                                    </span>

                                @elseif($payment->payment_status === 'paid')

                                    <span class="badge bg-success">
                                        Paid
                                    </span>

                                @elseif($payment->payment_status === 'rejected')

                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ ucfirst($payment->payment_status) }}
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- Verification Status --}}
                        <div class="col-md-6">

                            <label class="form-label text-muted">
                                Verification Status
                            </label>

                            <div>

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

                            </div>

                        </div>


                        {{-- Sent To Author --}}
                        <div class="col-md-6">

                            <label class="form-label text-muted">
                                Payment Request
                            </label>

                            <div>

                                @if($payment->sent_to_author_at)

                                    <span class="text-success">

                                        <i class="bi bi-check-circle me-1"></i>

                                        Sent to Author

                                    </span>

                                @else

                                    <span class="text-muted">
                                        Not sent
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         MANUSCRIPT INFORMATION
                    ================================================== --}}

                    <hr class="my-4">

                    <h6 class="fw-bold mb-3">
                        Manuscript Information
                    </h6>


                    @if($payment->manuscript)

                        <div class="mb-3">

                            <label class="form-label text-muted">
                                Manuscript ID
                            </label>

                            <div class="fw-semibold">
                                {{ $payment->manuscript->manuscript_id }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <label class="form-label text-muted">
                                Title
                            </label>

                            <div class="fw-semibold">
                                {{ $payment->manuscript->title }}
                            </div>

                        </div>


                        @if($payment->manuscript->articleType)

                            <div class="mb-3">

                                <label class="form-label text-muted">
                                    Article Type
                                </label>

                                <div>
                                    {{ $payment->manuscript->articleType->name }}
                                </div>

                            </div>

                        @endif

                    @else

                        <div class="alert alert-warning mb-0">
                            Manuscript information is not available.
                        </div>

                    @endif


                    {{-- Remarks --}}
                    @if($payment->remarks)

                        <hr class="my-4">

                        <h6 class="fw-bold">
                            Remarks
                        </h6>

                        <p class="text-muted mb-0">
                            {{ $payment->remarks }}
                        </p>

                    @endif

                </div>

            </div>

        </div>


        {{-- =====================================================
             RIGHT: PAYMENT ACTION
        ====================================================== --}}

        <div class="col-lg-5">


            {{-- =================================================
                 PAYMENT FORM
            ================================================== --}}

            @if(
                $payment->payment_status === 'pending'
                &&
                $payment->manuscript
                &&
                in_array(
                    $payment->manuscript->status,
                    ['payment_setup', 'payment_required']
                )
            )

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-bold text-success">

                            <i class="bi bi-credit-card me-2"></i>

                            Submit Payment

                        </h5>

                    </div>


                    <div class="card-body">

                        {{-- Amount --}}
                        <div class="alert alert-info">

                            <strong>
                                Amount Payable:
                            </strong>

                            <span class="float-end fw-bold">

                                {{ number_format($payment->amount, 2) }}

                                {{ $payment->currency }}

                            </span>

                        </div>


                        {{-- =================================================
                             PAYMENT FORM
                        ================================================== --}}

                        <form method="POST"
                              action="{{ route(
                                  'author.payments.submit',
                                  $payment->id
                              ) }}">

                            @csrf


                            {{-- Payment Method --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">

                                    Payment Method

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <select name="payment_method"
                                        class="form-select @error('payment_method') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Payment Method
                                    </option>

                                    <option value="bKash"
                                        {{ old('payment_method') === 'bKash' ? 'selected' : '' }}>
                                        bKash
                                    </option>

                                    <option value="Nagad"
                                        {{ old('payment_method') === 'Nagad' ? 'selected' : '' }}>
                                        Nagad
                                    </option>

                                    <option value="Bank Transfer"
                                        {{ old('payment_method') === 'Bank Transfer' ? 'selected' : '' }}>
                                        Bank Transfer
                                    </option>

                                    <option value="Credit/Debit Card"
                                        {{ old('payment_method') === 'Credit/Debit Card' ? 'selected' : '' }}>
                                        Credit/Debit Card
                                    </option>

                                    <option value="Other"
                                        {{ old('payment_method') === 'Other' ? 'selected' : '' }}>
                                        Other
                                    </option>

                                </select>


                                @error('payment_method')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Transaction ID --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">

                                    Transaction ID

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <input type="text"
                                       name="transaction_id"
                                       value="{{ old('transaction_id') }}"
                                       class="form-control @error('transaction_id') is-invalid @enderror"
                                       placeholder="Enter transaction ID"
                                       required>


                                @error('transaction_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Payer Name --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">

                                    Payer Name

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <input type="text"
                                       name="payer_name"
                                       value="{{ old(
                                           'payer_name',
                                           Auth::user()->name ?? ''
                                       ) }}"
                                       class="form-control @error('payer_name') is-invalid @enderror"
                                       placeholder="Enter payer name"
                                       required>


                                @error('payer_name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Payer Mobile --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">

                                    Payer Mobile

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <input type="text"
                                       name="payer_mobile"
                                       value="{{ old('payer_mobile') }}"
                                       class="form-control @error('payer_mobile') is-invalid @enderror"
                                       placeholder="01XXXXXXXXX"
                                       required>


                                @error('payer_mobile')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Payment Date --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">

                                    Payment Date

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <input type="date"
                                       name="payment_date"
                                       value="{{ old(
                                           'payment_date',
                                           now()->format('Y-m-d')
                                       ) }}"
                                       class="form-control @error('payment_date') is-invalid @enderror"
                                       required>


                                @error('payment_date')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Confirmation --}}
                            <div class="form-check mb-4">

                                <input class="form-check-input"
                                       type="checkbox"
                                       name="confirmation"
                                       value="1"
                                       id="payment_confirmation"
                                       required>


                                <label class="form-check-label"
                                       for="payment_confirmation">

                                    I confirm that the payment information
                                    provided above is correct.

                                </label>

                            </div>


                            {{-- Submit --}}
                            <button type="submit"
                                    class="btn btn-success w-100">

                                <i class="bi bi-check-circle me-1"></i>

                                Submit Payment Information

                            </button>

                        </form>

                    </div>

                </div>


            {{-- =================================================
                 PAYMENT SUBMITTED
            ================================================== --}}

            @elseif($payment->payment_status === 'submitted')

                <div class="card border-0 shadow-sm">

                    <div class="card-body text-center py-5">

                        <i class="bi bi-hourglass-split text-info"
                           style="font-size: 3rem;">
                        </i>

                        <h5 class="mt-3">
                            Payment Under Verification
                        </h5>

                        <p class="text-muted">

                            Your payment information has been submitted
                            successfully and is waiting for verification.

                        </p>


                        <div class="alert alert-info text-start">

                            <strong>
                                Transaction ID:
                            </strong>

                            <br>

                            {{ $payment->transaction_id ?? 'N/A' }}

                        </div>

                    </div>

                </div>


            {{-- =================================================
                 PAYMENT VERIFIED
            ================================================== --}}

            @elseif(
                $payment->payment_status === 'paid'
                &&
                $payment->verification_status === 'verified'
            )

                <div class="card border-0 shadow-sm">

                    <div class="card-body text-center py-5">

                        <i class="bi bi-check-circle-fill text-success"
                           style="font-size: 4rem;">
                        </i>

                        <h4 class="mt-3 text-success">
                            Payment Verified
                        </h4>

                        <p class="text-muted">
                            Your payment has been successfully verified.
                        </p>


                        @if($payment->transaction_id)

                            <div class="mt-3">

                                <small class="text-muted">
                                    Transaction ID
                                </small>

                                <div class="fw-bold">
                                    {{ $payment->transaction_id }}
                                </div>

                            </div>

                        @endif

                    </div>

                </div>


            {{-- =================================================
                 PAYMENT REJECTED
            ================================================== --}}

            @elseif($payment->verification_status === 'rejected')

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="alert alert-danger">

                            <h5 class="alert-heading">

                                <i class="bi bi-x-circle me-2"></i>

                                Payment Rejected

                            </h5>

                            <p class="mb-0">

                                Your payment information was rejected.
                                Please contact the journal administration
                                for further instructions.

                            </p>

                        </div>

                    </div>

                </div>


            {{-- =================================================
                 DEFAULT
            ================================================== --}}

            @else

                <div class="card border-0 shadow-sm">

                    <div class="card-body text-center py-5">

                        <i class="bi bi-info-circle text-secondary"
                           style="font-size: 3rem;">
                        </i>

                        <h5 class="mt-3">
                            Payment Information
                        </h5>

                        <p class="text-muted mb-0">
                            No payment action is currently required.
                        </p>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
