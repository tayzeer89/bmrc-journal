@extends('admin.layouts.app')

@section('title', 'Verify Payment')

@section('content')

<div class="container-fluid">

    {{-- ================================================================
         Header
    ================================================================= --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">

                <i class="bi bi-shield-check me-2"></i>

                Payment Verification

            </h4>

            <p class="text-muted mb-0">

                Review payment information before verification.

            </p>

        </div>


        <div class="mt-2 mt-md-0">

            <a
                href="{{ route('admin.payments.verification.index') }}"
                class="btn btn-outline-secondary"
            >

                <i class="bi bi-arrow-left me-1"></i>

                Back to Verification Queue

            </a>

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


    <div class="row g-4">


        {{-- ============================================================
             LEFT COLUMN
        ============================================================= --}}

        <div class="col-lg-8">


            {{-- ========================================================
                 Invoice Information
            ========================================================= --}}

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <h6 class="mb-0 fw-semibold">

                        <i class="bi bi-receipt me-2"></i>

                        Invoice Information

                    </h6>

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Invoice Number
                            </label>

                            <div class="fw-bold">

                                {{ $payment->invoice_no }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Fee Type
                            </label>

                            <div>

                                {{ $payment->fee_type }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <label class="text-muted small">
                                Amount
                            </label>

                            <div class="fw-bold fs-5">

                                {{ $payment->currency }}

                                {{ number_format(
                                    (float) $payment->amount,
                                    2
                                ) }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <label class="text-muted small">
                                Invoice Date
                            </label>

                            <div>

                                {{ $payment->invoice_date?->format('d M Y') }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <label class="text-muted small">
                                Payment Deadline
                            </label>

                            <div>

                                @if($payment->payment_deadline)

                                    {{ $payment->payment_deadline->format('d M Y') }}

                                @else

                                    <span class="text-muted">
                                        Not specified
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================
                 Manuscript Information
            ========================================================= --}}

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <h6 class="mb-0 fw-semibold">

                        <i class="bi bi-file-earmark-text me-2"></i>

                        Manuscript Information

                    </h6>

                </div>


                <div class="card-body">

                    @if($payment->manuscript)

                        <div class="mb-3">

                            <label class="text-muted small">
                                Manuscript Title
                            </label>

                            <div class="fw-semibold">

                                {{ $payment->manuscript->title }}

                            </div>

                        </div>


                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted small">
                                    Manuscript ID
                                </label>

                                <div>

                                    {{ $payment->manuscript->manuscript_id }}

                                </div>

                            </div>


                            <div class="col-md-6">

                                <label class="text-muted small">
                                    Article Type
                                </label>

                                <div>

                                    {{ $payment->manuscript->articleType?->name ?? 'N/A' }}

                                </div>

                            </div>


                            <div class="col-md-6">

                                <label class="text-muted small">
                                    Journal
                                </label>

                                <div>

                                    {{ $payment->manuscript->journal?->name ?? 'N/A' }}

                                </div>

                            </div>


                            <div class="col-md-6">

                                <label class="text-muted small">
                                    Manuscript Status
                                </label>

                                <div>

                                    <span class="badge bg-secondary">

                                        {{ ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $payment->manuscript->status
                                            )
                                        ) }}

                                    </span>

                                </div>

                            </div>

                        </div>

                    @else

                        <div class="alert alert-danger mb-0">

                            Manuscript information could not be found.

                        </div>

                    @endif

                </div>

            </div>


            {{-- ========================================================
                 Author Information
            ========================================================= --}}

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <h6 class="mb-0 fw-semibold">

                        <i class="bi bi-person me-2"></i>

                        Author Information

                    </h6>

                </div>


                <div class="card-body">

                    @if(
                        $payment->manuscript &&
                        $payment->manuscript->submitter
                    )

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted small">
                                    Name
                                </label>

                                <div class="fw-semibold">

                                    {{ $payment->manuscript->submitter->name }}

                                </div>

                            </div>


                            <div class="col-md-6">

                                <label class="text-muted small">
                                    Email
                                </label>

                                <div>

                                    {{ $payment->manuscript->submitter->email }}

                                </div>

                            </div>

                        </div>

                    @else

                        <span class="text-muted">
                            Author information unavailable.
                        </span>

                    @endif

                </div>

            </div>


            {{-- ========================================================
                 Submitted Payment Information
            ========================================================= --}}

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <h6 class="mb-0 fw-semibold">

                        <i class="bi bi-credit-card me-2"></i>

                        Submitted Payment Information

                    </h6>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Payment Method
                            </label>

                            <div class="fw-semibold">

                                {{ $payment->payment_method ?? 'N/A' }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Payment Gateway
                            </label>

                            <div>

                                {{ $payment->payment_gateway ?? 'N/A' }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Transaction ID
                            </label>

                            <div>

                                @if($payment->transaction_id)

                                    <code class="fs-6">

                                        {{ $payment->transaction_id }}

                                    </code>

                                @else

                                    <span class="text-muted">
                                        N/A
                                    </span>

                                @endif

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Payment Date
                            </label>

                            <div>

                                {{ $payment->payment_date?->format('d M Y h:i A') ?? 'N/A' }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Payer Name
                            </label>

                            <div>

                                {{ $payment->payer_name ?? 'N/A' }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Payer Mobile
                            </label>

                            <div>

                                {{ $payment->payer_mobile ?? 'N/A' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================
                 Remarks
            ========================================================= --}}

            @if($payment->remarks)

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white py-3">

                        <h6 class="mb-0 fw-semibold">

                            <i class="bi bi-chat-left-text me-2"></i>

                            Remarks

                        </h6>

                    </div>

                    <div class="card-body">

                        {!! nl2br(e($payment->remarks)) !!}

                    </div>

                </div>

            @endif


            {{-- ========================================================
                 Previous Verification Notes
            ========================================================= --}}

            @if($payment->verification_notes)

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white py-3">

                        <h6 class="mb-0 fw-semibold">

                            <i class="bi bi-info-circle me-2"></i>

                            Verification Notes

                        </h6>

                    </div>

                    <div class="card-body">

                        {!! nl2br(e($payment->verification_notes)) !!}

                    </div>

                </div>

            @endif

        </div>


        {{-- ============================================================
             RIGHT COLUMN
        ============================================================= --}}

        <div class="col-lg-4">


            {{-- ========================================================
                 Current Status
            ========================================================= --}}

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <h6 class="mb-0 fw-semibold">

                        <i class="bi bi-activity me-2"></i>

                        Payment Status

                    </h6>

                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted d-block mb-1">
                            Payment Status
                        </small>

                        @if($payment->payment_status === 'submitted')

                            <span class="badge bg-warning text-dark">

                                Submitted

                            </span>

                        @elseif($payment->payment_status === 'paid')

                            <span class="badge bg-success">

                                Paid

                            </span>

                        @else

                            <span class="badge bg-secondary">

                                {{ ucfirst($payment->payment_status) }}

                            </span>

                        @endif

                    </div>


                    <div>

                        <small class="text-muted d-block mb-1">
                            Verification Status
                        </small>

                        @if($payment->verification_status === 'pending')

                            <span class="badge bg-warning text-dark">

                                Pending Verification

                            </span>

                        @elseif($payment->verification_status === 'verified')

                            <span class="badge bg-success">

                                Verified

                            </span>

                        @elseif($payment->verification_status === 'rejected')

                            <span class="badge bg-danger">

                                Rejected

                            </span>

                        @else

                            <span class="badge bg-secondary">

                                {{ ucfirst(
                                    $payment->verification_status
                                ) }}

                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- ========================================================
                 Verification Actions
            ========================================================= --}}

            @if(
                $payment->payment_status === 'submitted' &&
                $payment->verification_status === 'pending'
            )

                {{-- ====================================================
                     Verify Payment
                ===================================================== --}}

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white py-3">

                        <h6 class="mb-0 fw-semibold text-success">

                            <i class="bi bi-check-circle me-2"></i>

                            Verify Payment

                        </h6>

                    </div>


                    <div class="card-body">

                        <p class="text-muted small">

                            Confirm that the submitted transaction
                            information is valid and the payment has
                            been received.

                        </p>


                        <form
                            action="{{ route(
                                'admin.payments.verification.verify',
                                $payment
                            ) }}"
                            method="POST"
                        >

                            @csrf

                            <div class="mb-3">

                                <label
                                    for="verification_notes"
                                    class="form-label"
                                >
                                    Verification Notes
                                </label>

                                <textarea
                                    name="verification_notes"
                                    id="verification_notes"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Optional verification note..."
                                ></textarea>

                            </div>


                            <button
                                type="submit"
                                class="btn btn-success w-100"
                                onclick="return confirm(
                                    'Are you sure you want to verify this payment?'
                                );"
                            >

                                <i class="bi bi-check-circle me-1"></i>

                                Verify Payment

                            </button>

                        </form>

                    </div>

                </div>


                {{-- ====================================================
                     Reject Payment
                ===================================================== --}}

                <div class="card shadow-sm border-0 border-danger mb-4">

                    <div class="card-header bg-white py-3">

                        <h6 class="mb-0 fw-semibold text-danger">

                            <i class="bi bi-x-circle me-2"></i>

                            Reject Payment

                        </h6>

                    </div>


                    <div class="card-body">

                        <p class="text-muted small">

                            Reject this payment if the transaction
                            information is incorrect or the payment
                            cannot be confirmed.

                        </p>


                        <form
                            action="{{ route(
                                'admin.payments.verification.reject',
                                $payment
                            ) }}"
                            method="POST"
                        >

                            @csrf

                            <div class="mb-3">

                                <label
                                    for="reject_notes"
                                    class="form-label"
                                >

                                    Rejection Reason
                                    <span class="text-danger">*</span>

                                </label>

                                <textarea
                                    name="verification_notes"
                                    id="reject_notes"
                                    class="form-control @error('verification_notes') is-invalid @enderror"
                                    rows="4"
                                    required
                                    placeholder="Explain why this payment is being rejected..."
                                >{{ old('verification_notes') }}</textarea>


                                @error('verification_notes')

                                    <div class="invalid-feedback">

                                        {{ $message }}

                                    </div>

                                @enderror

                            </div>


                            <button
                                type="submit"
                                class="btn btn-danger w-100"
                                onclick="return confirm(
                                    'Are you sure you want to reject this payment?'
                                );"
                            >

                                <i class="bi bi-x-circle me-1"></i>

                                Reject Payment

                            </button>

                        </form>

                    </div>

                </div>

            @else

                {{-- ====================================================
                     Already Processed
                ===================================================== --}}

                <div class="card shadow-sm border-0">

                    <div class="card-body text-center py-4">

                        @if(
                            $payment->verification_status === 'verified'
                        )

                            <i
                                class="bi bi-check-circle-fill text-success"
                                style="font-size: 3rem;"
                            ></i>

                            <h5 class="mt-3 text-success">

                                Payment Verified

                            </h5>

                            @if($payment->verified_at)

                                <p class="text-muted mb-0">

                                    Verified on
                                    {{ $payment->verified_at->format(
                                        'd M Y h:i A'
                                    ) }}

                                </p>

                            @endif

                        @elseif(
                            $payment->verification_status === 'rejected'
                        )

                            <i
                                class="bi bi-x-circle-fill text-danger"
                                style="font-size: 3rem;"
                            ></i>

                            <h5 class="mt-3 text-danger">

                                Payment Rejected

                            </h5>

                            <p class="text-muted mb-0">

                                The payment has been returned to the
                                author for correction.

                            </p>

                        @else

                            <i
                                class="bi bi-info-circle-fill text-secondary"
                                style="font-size: 3rem;"
                            ></i>

                            <h5 class="mt-3">

                                Payment Already Processed

                            </h5>

                        @endif

                    </div>

                </div>

            @endif


        </div>

    </div>

</div>

@endsection