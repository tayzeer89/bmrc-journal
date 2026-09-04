@extends('admin.layouts.app')

@section('title', 'Payment Details')

@section('content')

<style>
    .payment-page {
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: 1.5rem;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: .25rem;
    }

    .page-subtitle {
        color: #6c757d;
        font-size: .875rem;
        margin-bottom: 0;
    }

    .payment-card {
        border: 0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 .125rem .5rem rgba(0, 0, 0, .06);
    }

    .payment-card .card-header {
        background: #fff;
        border-bottom: 1px solid #edf0f2;
        padding: 1rem 1.25rem;
    }

    .payment-card .card-body {
        padding: 1.25rem;
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        padding-bottom: 1.25rem;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid #edf0f2;
    }

    .invoice-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0d6efd;
    }

    .invoice-label {
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        font-weight: 700;
        color: #868e96;
        margin-bottom: .25rem;
    }

    .invoice-amount {
        font-size: 1.75rem;
        font-weight: 700;
        color: #212529;
    }

    .info-section {
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: .95rem;
        font-weight: 700;
        color: #343a40;
        margin-bottom: .85rem;
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        border-bottom: 1px solid #f1f3f5;
        padding: .7rem 0;
    }

    .info-row:last-child {
        border-bottom: 0;
    }

    .info-label {
        width: 180px;
        flex-shrink: 0;
        color: #6c757d;
        font-size: .8rem;
        font-weight: 600;
    }

    .info-value {
        color: #212529;
        font-size: .875rem;
        word-break: break-word;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .4rem .65rem;
        border-radius: 6px;
        font-size: .72rem;
        font-weight: 600;
    }

    .status-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .workflow {
        display: flex;
        align-items: center;
        gap: .5rem;
        flex-wrap: wrap;
        margin-top: .5rem;
    }

    .workflow-step {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        font-size: .75rem;
        font-weight: 600;
        padding: .4rem .65rem;
        border-radius: 6px;
        background: #f8f9fa;
        color: #6c757d;
        border: 1px solid #e9ecef;
    }

    .workflow-step.active {
        background: #e7f1ff;
        color: #0d6efd;
        border-color: #b6d4fe;
    }

    .workflow-step.completed {
        background: #e9f7ef;
        color: #198754;
        border-color: #badbcc;
    }

    .action-area {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
        padding-top: 1rem;
        border-top: 1px solid #edf0f2;
    }

    .action-area .btn {
        border-radius: 7px;
        font-size: .82rem;
        font-weight: 500;
    }

    .remarks-box {
        background: #f8f9fa;
        border-radius: 8px;
        padding: .9rem 1rem;
        color: #495057;
        font-size: .85rem;
        line-height: 1.6;
    }

    @media (max-width: 767.98px) {

        .payment-page {
            padding-left: .75rem;
            padding-right: .75rem;
        }

        .page-title {
            font-size: 1.3rem;
        }

        .invoice-header {
            flex-direction: column;
        }

        .invoice-amount {
            font-size: 1.5rem;
        }

        .info-row {
            display: block;
        }

        .info-label {
            width: auto;
            margin-bottom: .2rem;
        }

        .action-area {
            display: grid;
            grid-template-columns: 1fr;
        }

        .action-area .btn {
            width: 100%;
        }

        .workflow {
            display: grid;
            grid-template-columns: 1fr;
        }

        .workflow-step {
            width: 100%;
        }
    }
</style>

<div class="container-fluid py-4 payment-page">

{{-- ============================================================
     Page Header
============================================================= --}}

<div class="page-header">

    <h2 class="page-title">
        Payment Details
    </h2>

    <p class="page-subtitle">
        View and manage the manuscript payment request.
    </p>

</div>


{{-- ============================================================
     Flash Messages
============================================================= --}}

@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show"
         role="alert">

        <i class="bi bi-check-circle me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

@endif


@if(session('warning'))

    <div class="alert alert-warning alert-dismissible fade show"
         role="alert">

        <i class="bi bi-exclamation-circle me-2"></i>

        {{ session('warning') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

@endif


@if(session('error'))

    <div class="alert alert-danger alert-dismissible fade show"
         role="alert">

        <i class="bi bi-exclamation-triangle me-2"></i>

        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- ============================================================
     Payment Card
============================================================= --}}

<div class="card payment-card">

    <div class="card-header">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h5 class="mb-1 fw-semibold">
                    Payment Invoice
                </h5>

                <small class="text-muted">
                    BMRC Manuscript Payment
                </small>

            </div>

            <a
                href="{{ route(
                    'admin.manuscripts.show',
                    $payment->manuscript
                ) }}"
                class="btn btn-sm btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Back to Manuscript

            </a>

        </div>

    </div>


    <div class="card-body">

        {{-- ====================================================
             Invoice Header
        ===================================================== --}}

        <div class="invoice-header">

            <div>

                <div class="invoice-label">
                    Invoice Number
                </div>

                <div class="invoice-number">

                    {{ $payment->invoice_no }}

                </div>

            </div>


            <div class="text-md-end">

                <div class="invoice-label">
                    Amount
                </div>

                <div class="invoice-amount">

                    {{ number_format(
                        (float) $payment->amount,
                        2
                    ) }}

                    <span class="fs-6 fw-semibold">

                        {{ $payment->currency }}

                    </span>

                </div>

            </div>

        </div>


        {{-- ====================================================
             Payment Workflow
        ===================================================== --}}

        <div class="info-section">

            <div class="section-title">
                Payment Workflow
            </div>

            <div class="workflow">

                {{-- Invoice Created --}}

                <div class="workflow-step completed">

                    <i class="bi bi-check-circle"></i>

                    Invoice Created

                </div>


                {{-- Sent to Author --}}

                @if($payment->sent_to_author_at)

                    <div class="workflow-step completed">

                        <i class="bi bi-check-circle"></i>

                        Sent to Author

                    </div>

                @else

                    <div class="workflow-step active">

                        <i class="bi bi-hourglass-split"></i>

                        Awaiting Author

                    </div>

                @endif


                {{-- Payment Submitted --}}

                @if(
                    in_array(
                        $payment->payment_status,
                        ['submitted', 'paid']
                    )
                )

                    <div class="workflow-step completed">

                        <i class="bi bi-check-circle"></i>

                        Payment Submitted

                    </div>

                @else

                    <div class="workflow-step">

                        <i class="bi bi-clock"></i>

                        Payment Pending

                    </div>

                @endif


                {{-- Verification --}}

                @if($payment->verification_status === 'verified')

                    <div class="workflow-step completed">

                        <i class="bi bi-check-circle"></i>

                        Verified

                    </div>

                @elseif($payment->verification_status === 'rejected')

                    <div class="workflow-step"
                         style="color:#dc3545;">

                        <i class="bi bi-x-circle"></i>

                        Rejected

                    </div>

                @else

                    <div class="workflow-step">

                        <i class="bi bi-shield-check"></i>

                        Verification Pending

                    </div>

                @endif

            </div>

        </div>


        {{-- ====================================================
             Manuscript Information
        ===================================================== --}}

        <div class="info-section">

            <div class="section-title">
                Manuscript Information
            </div>

            <div class="info-row">

                <div class="info-label">
                    Manuscript ID
                </div>

                <div class="info-value fw-semibold">

                    {{ $payment->manuscript->manuscript_id
                        ?? $payment->manuscript->id }}

                </div>

            </div>


            <div class="info-row">

                <div class="info-label">
                    Title
                </div>

                <div class="info-value fw-semibold">

                    {{ $payment->manuscript->title
                        ?? 'Untitled Manuscript' }}

                </div>

            </div>


            @if($payment->manuscript->articleType)

                <div class="info-row">

                    <div class="info-label">
                        Article Type
                    </div>

                    <div class="info-value">

                        {{ $payment->manuscript->articleType->name }}

                    </div>

                </div>

            @endif


            @if($payment->manuscript->journal)

                <div class="info-row">

                    <div class="info-label">
                        Journal
                    </div>

                    <div class="info-value">

                        {{ $payment->manuscript->journal->name }}

                    </div>

                </div>

            @endif


            @if($payment->manuscript->submitter)

                <div class="info-row">

                    <div class="info-label">
                        Submitter
                    </div>

                    <div class="info-value">

                        {{ $payment->manuscript->submitter->name }}

                    </div>

                </div>

            @endif

        </div>


        {{-- ====================================================
             Invoice Information
        ===================================================== --}}

        <div class="info-section">

            <div class="section-title">
                Invoice Information
            </div>

            <div class="info-row">

                <div class="info-label">
                    Fee Type
                </div>

                <div class="info-value">

                    {{ $payment->fee_type }}

                </div>

            </div>


            <div class="info-row">

                <div class="info-label">
                    Amount
                </div>

                <div class="info-value fw-semibold">

                    {{ number_format(
                        (float) $payment->amount,
                        2
                    ) }}

                    {{ $payment->currency }}

                </div>

            </div>


            <div class="info-row">

                <div class="info-label">
                    Invoice Date
                </div>

                <div class="info-value">

                    {{ $payment->invoice_date
                        ? $payment->invoice_date->format('d M Y')
                        : '—' }}

                </div>

            </div>


            <div class="info-row">

                <div class="info-label">
                    Payment Deadline
                </div>

                <div class="info-value">

                    {{ $payment->payment_deadline
                        ? $payment->payment_deadline->format('d M Y')
                        : '—' }}

                </div>

            </div>


            <div class="info-row">

                <div class="info-label">
                    Payment Status
                </div>

                <div class="info-value">

                    @php

                        $paymentStatusClass = match(
                            $payment->payment_status
                        ) {

                            'pending'
                                => 'bg-warning text-dark',

                            'submitted'
                                => 'bg-info text-dark',

                            'paid'
                                => 'bg-success',

                            'rejected'
                                => 'bg-danger',

                            'cancelled'
                                => 'bg-secondary',

                            default
                                => 'bg-secondary',

                        };

                    @endphp


                    <span class="status-badge {{ $paymentStatusClass }}">

                        {{ ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $payment->payment_status
                                ?? 'Unknown'
                            )
                        ) }}

                    </span>

                </div>

            </div>


            <div class="info-row">

                <div class="info-label">
                    Verification Status
                </div>

                <div class="info-value">

                    @php

                        $verificationClass = match(
                            $payment->verification_status
                        ) {

                            'pending'
                                => 'bg-warning text-dark',

                            'verified'
                                => 'bg-success',

                            'rejected'
                                => 'bg-danger',

                            default
                                => 'bg-secondary',

                        };

                    @endphp


                    <span class="status-badge {{ $verificationClass }}">

                        {{ ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $payment->verification_status
                                ?? 'Unknown'
                            )
                        ) }}

                    </span>

                </div>

            </div>

        </div>


        {{-- ====================================================
             Author Payment Information
        ===================================================== --}}

        @if(
            $payment->payer_name
            ||
            $payment->payer_mobile
            ||
            $payment->payment_method
            ||
            $payment->transaction_id
            ||
            $payment->payment_date
        )

            <div class="info-section">

                <div class="section-title">
                    Author Payment Information
                </div>


                @if($payment->payer_name)

                    <div class="info-row">

                        <div class="info-label">
                            Payer Name
                        </div>

                        <div class="info-value">

                            {{ $payment->payer_name }}

                        </div>

                    </div>

                @endif


                @if($payment->payer_mobile)

                    <div class="info-row">

                        <div class="info-label">
                            Mobile
                        </div>

                        <div class="info-value">

                            {{ $payment->payer_mobile }}

                        </div>

                    </div>

                @endif


                @if($payment->payment_method)

                    <div class="info-row">

                        <div class="info-label">
                            Payment Method
                        </div>

                        <div class="info-value">

                            {{ $payment->payment_method }}

                        </div>

                    </div>

                @endif


                @if($payment->payment_gateway)

                    <div class="info-row">

                        <div class="info-label">
                            Payment Gateway
                        </div>

                        <div class="info-value">

                            {{ $payment->payment_gateway }}

                        </div>

                    </div>

                @endif


                @if($payment->transaction_id)

                    <div class="info-row">

                        <div class="info-label">
                            Transaction ID
                        </div>

                        <div class="info-value fw-semibold">

                            {{ $payment->transaction_id }}

                        </div>

                    </div>

                @endif


                @if($payment->payment_date)

                    <div class="info-row">

                        <div class="info-label">
                            Payment Date
                        </div>

                        <div class="info-value">

                            {{ $payment->payment_date->format(
                                'd M Y h:i A'
                            ) }}

                        </div>

                    </div>

                @endif

            </div>

        @endif


        {{-- ====================================================
             Creation / Verification Information
        ===================================================== --}}

        <div class="info-section">

            <div class="section-title">
                Record Information
            </div>


            @if($payment->createdBy)

                <div class="info-row">

                    <div class="info-label">
                        Created By
                    </div>

                    <div class="info-value">

                        {{ $payment->createdBy->name }}

                    </div>

                </div>

            @endif


            <div class="info-row">

                <div class="info-label">
                    Created At
                </div>

                <div class="info-value">

                    {{ $payment->created_at
                        ? $payment->created_at->format(
                            'd M Y h:i A'
                        )
                        : '—' }}

                </div>

            </div>


            @if($payment->sent_to_author_at)

                <div class="info-row">

                    <div class="info-label">
                        Sent to Author
                    </div>

                    <div class="info-value">

                        {{ $payment->sent_to_author_at->format(
                            'd M Y h:i A'
                        ) }}

                    </div>

                </div>

            @endif


            @if($payment->verifiedBy)

                <div class="info-row">

                    <div class="info-label">
                        Verified By
                    </div>

                    <div class="info-value">

                        {{ $payment->verifiedBy->name }}

                    </div>

                </div>

            @endif


            @if($payment->verified_at)

                <div class="info-row">

                    <div class="info-label">
                        Verified At
                    </div>

                    <div class="info-value">

                        {{ $payment->verified_at->format(
                            'd M Y h:i A'
                        ) }}

                    </div>

                </div>

            @endif

        </div>


        {{-- ====================================================
             Remarks
        ===================================================== --}}

        @if($payment->remarks)

            <div class="info-section">

                <div class="section-title">
                    Remarks
                </div>

                <div class="remarks-box">

                    {{ $payment->remarks }}

                </div>

            </div>

        @endif


        {{-- ====================================================
             Verification Notes
        ===================================================== --}}

        @if($payment->verification_notes)

            <div class="info-section">

                <div class="section-title">
                    Verification Notes
                </div>

                <div class="remarks-box">

                    {{ $payment->verification_notes }}

                </div>

            </div>

        @endif


        {{-- ====================================================
             Actions
        ===================================================== --}}

        <div class="action-area">

            <div>

                <a
                    href="{{ route(
                        'admin.manuscripts.show',
                        $payment->manuscript
                    ) }}"
                    class="btn btn-outline-secondary">

                    <i class="bi bi-arrow-left me-1"></i>

                    Back

                </a>

            </div>


            <div class="d-flex gap-2 flex-wrap">

                {{-- =================================================
                     Send Payment Request
                ================================================== --}}

                @if(!$payment->sent_to_author_at)

                    @can('payment.send')

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.payments.send-to-author',
                                $payment
                            ) }}"
                            onsubmit="return confirm(
                                'Are you sure you want to send this payment request to the author?'
                            );">

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="bi bi-send me-1"></i>

                                Send Payment Request

                            </button>

                        </form>

                    @endcan

                @else

                    <span class="btn btn-outline-success disabled">

                        <i class="bi bi-check-circle me-1"></i>

                        Sent to Author

                    </span>

                @endif

            </div>

        </div>

    </div>

</div>

</div>

@endsection
