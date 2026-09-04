@extends('admin.layouts.app')

@section('title', 'Create Payment Invoice')

@section('content')

<style>
    .payment-create-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px 18px 45px;
    }

    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .page-title-wrap {
        display: flex;
        align-items: flex-start;
        gap: 13px;
    }

    .header-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #f1f5f9;
        color: #495057;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        flex-shrink: 0;
    }

    .page-title {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #212529;
    }

    .page-subtitle {
        margin: 5px 0 0;
        color: #6c757d;
        font-size: 13px;
        line-height: 1.5;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border: 1px solid #dee2e6;
        background: #fff;
        color: #495057;
        border-radius: 8px;
        padding: 8px 13px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
    }

    .back-btn:hover {
        background: #f8f9fa;
        color: #212529;
    }

    .card {
        border: 1px solid #e9ecef;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, .04);
        background: #fff;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .card-header {
        padding: 18px 21px;
        border-bottom: 1px solid #edf0f2;
        background: #fff;
    }

    .card-title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #212529;
    }

    .card-description {
        margin: 4px 0 0;
        color: #6c757d;
        font-size: 12px;
    }

    .card-body {
        padding: 22px;
    }

    .manuscript-summary {
        display: grid;
        grid-template-columns: 190px 1fr;
        gap: 12px 20px;
    }

    .summary-label {
        color: #6c757d;
        font-size: 12px;
        font-weight: 600;
    }

    .summary-value {
        color: #212529;
        font-size: 13px;
        font-weight: 600;
    }

    .summary-title {
        line-height: 1.6;
        font-weight: 500;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 9px;
        border-radius: 7px;
        background: #d1e7dd;
        color: #0f5132;
        font-size: 11px;
        font-weight: 700;
    }

    .form-label {
        color: #343a40;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .required {
        color: #dc3545;
    }

    .form-control,
    .form-select {
        min-height: 42px;
        border-radius: 8px;
        border-color: #dfe3e7;
        font-size: 13px;
        padding: 9px 12px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #adb5bd;
        box-shadow: 0 0 0 .2rem rgba(108, 117, 125, .1);
    }

    textarea.form-control {
        min-height: 115px;
        resize: vertical;
    }

    .form-text {
        color: #868e96;
        font-size: 11px;
        margin-top: 5px;
    }

    .invalid-feedback {
        font-size: 11px;
    }

    .amount-group {
        position: relative;
    }

    .amount-group .currency-label {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 12px;
        color: #6c757d;
        font-weight: 700;
        z-index: 2;
    }

    .amount-group .form-control {
        padding-left: 46px;
        font-size: 17px;
        font-weight: 700;
    }

    .info-box {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 14px;
        margin-top: 20px;
    }

    .info-box-icon {
        color: #6c757d;
        font-size: 17px;
        margin-top: 1px;
    }

    .info-box-text {
        color: #6c757d;
        font-size: 12px;
        line-height: 1.6;
    }

    .info-box-text strong {
        color: #495057;
    }

    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 18px 21px;
        border-top: 1px solid #edf0f2;
        background: #fafbfc;
    }

    .action-note {
        color: #6c757d;
        font-size: 11px;
        line-height: 1.5;
    }

    .button-group {
        display: flex;
        gap: 9px;
        align-items: center;
    }

    .btn-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 40px;
        padding: 8px 15px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
    }

    .btn-secondary-custom {
        background: #fff;
        color: #495057;
        border-color: #dee2e6;
    }

    .btn-secondary-custom:hover {
        background: #f8f9fa;
        color: #212529;
    }

    .btn-primary-custom {
        background: #212529;
        color: #fff;
        border-color: #212529;
    }

    .btn-primary-custom:hover {
        background: #343a40;
        color: #fff;
    }

    .section-divider {
        height: 1px;
        background: #edf0f2;
        margin: 24px 0;
    }

    .invoice-preview {
        border: 1px dashed #ced4da;
        border-radius: 10px;
        padding: 16px;
        background: #fafbfc;
    }

    .preview-title {
        color: #6c757d;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .5px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .preview-amount {
        font-size: 24px;
        font-weight: 800;
        color: #212529;
    }

    @media (max-width: 767.98px) {

        .payment-create-page {
            padding: 18px 12px 30px;
        }

        .page-header {
            flex-direction: column;
        }

        .page-title {
            font-size: 21px;
        }

        .manuscript-summary {
            grid-template-columns: 1fr;
            gap: 5px;
        }

        .summary-value {
            margin-bottom: 8px;
        }

        .action-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .button-group {
            width: 100%;
            flex-direction: column-reverse;
        }

        .btn-custom {
            width: 100%;
        }
    }
</style>

<div class="payment-create-page">

    {{-- Header --}}
    <div class="page-header">

        <div class="page-title-wrap">

            <div class="header-icon">
                <i class="bi bi-receipt"></i>
            </div>

            <div>
                <h1 class="page-title">
                    Create Payment Invoice
                </h1>

                <p class="page-subtitle">
                    Set the article processing fee and create a payment
                    invoice for the selected manuscript.
                </p>
            </div>

        </div>

        <a
            href="{{ route('admin.manuscripts.payment.index') }}"
            class="back-btn"
        >
            <i class="bi bi-arrow-left"></i>
            Back to Payment Setup
        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <div class="fw-semibold mb-1">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0 ps-4">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Manuscript Information --}}
    <div class="card">

        <div class="card-header">

            <h2 class="card-title">
                <i class="bi bi-file-earmark-text me-2"></i>
                Manuscript Information
            </h2>

            <p class="card-description">
                Confirm the manuscript before preparing the payment invoice.
            </p>

        </div>

        <div class="card-body">

            <div class="manuscript-summary">

                <div class="summary-label">
                    Manuscript ID
                </div>

                <div class="summary-value">
                    {{ $manuscript->manuscript_id }}
                </div>


                <div class="summary-label">
                    Article Title
                </div>

                <div class="summary-value summary-title">
                    {{ $manuscript->title }}
                </div>


                <div class="summary-label">
                    Article Type
                </div>

                <div class="summary-value">
                    {{ optional($manuscript->articleType)->name ?? '—' }}
                </div>


                <div class="summary-label">
                    Manuscript Status
                </div>

                <div class="summary-value">

                    <span class="status-badge">
                        <i class="bi bi-check-circle-fill"></i>
                        Technical Check Passed
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- Payment Form --}}
    <div class="card">

        <div class="card-header">

            <h2 class="card-title">
                <i class="bi bi-credit-card me-2"></i>
                Payment Information
            </h2>

            <p class="card-description">
                Enter the fee information that will be shown to the author.
            </p>

        </div>


        <form
            method="POST"
            action="{{ route(
                'admin.manuscripts.payment.store',
                $manuscript
            ) }}"
        >

            @csrf

            <div class="card-body">

                <div class="row g-4">

                    {{-- Fee Type --}}
                    <div class="col-md-6">

                        <label
                            for="fee_type"
                            class="form-label"
                        >
                            Fee Type
                            <span class="required">*</span>
                        </label>

                        <select
                            id="fee_type"
                            name="fee_type"
                            class="form-select @error('fee_type') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select fee type
                            </option>

                            <option
                                value="Article Processing Charge"
                                @selected(
                                    old('fee_type') ===
                                    'Article Processing Charge'
                                )
                            >
                                Article Processing Charge
                            </option>

                            <option
                                value="Submission Fee"
                                @selected(
                                    old('fee_type') ===
                                    'Submission Fee'
                                )
                            >
                                Submission Fee
                            </option>

                            <option
                                value="Publication Fee"
                                @selected(
                                    old('fee_type') ===
                                    'Publication Fee'
                                )
                            >
                                Publication Fee
                            </option>

                            <option
                                value="Other"
                                @selected(
                                    old('fee_type') === 'Other'
                                )
                            >
                                Other
                            </option>

                        </select>

                        @error('fee_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Currency --}}
                    <div class="col-md-3">

                        <label
                            for="currency"
                            class="form-label"
                        >
                            Currency
                            <span class="required">*</span>
                        </label>

                        <select
                            id="currency"
                            name="currency"
                            class="form-select @error('currency') is-invalid @enderror"
                            required
                        >

                            <option
                                value="BDT"
                                @selected(
                                    old('currency', 'BDT') === 'BDT'
                                )
                            >
                                BDT — Bangladeshi Taka
                            </option>

                            <option
                                value="USD"
                                @selected(
                                    old('currency') === 'USD'
                                )
                            >
                                USD — US Dollar
                            </option>

                        </select>

                        @error('currency')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Amount --}}
                    <div class="col-md-3">

                        <label
                            for="amount"
                            class="form-label"
                        >
                            Amount
                            <span class="required">*</span>
                        </label>

                        <div class="amount-group">

                            <span
                                class="currency-label"
                                id="currencySymbol"
                            >
                                ৳
                            </span>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="amount"
                                name="amount"
                                value="{{ old('amount') }}"
                                class="form-control @error('amount') is-invalid @enderror"
                                placeholder="0.00"
                                required
                            >

                        </div>

                        @error('amount')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Invoice Date --}}
                    <div class="col-md-6">

                        <label
                            for="invoice_date"
                            class="form-label"
                        >
                            Invoice Date
                            <span class="required">*</span>
                        </label>

                        <input
                            type="date"
                            id="invoice_date"
                            name="invoice_date"
                            value="{{ old(
                                'invoice_date',
                                now()->format('Y-m-d')
                            ) }}"
                            class="form-control @error('invoice_date') is-invalid @enderror"
                            required
                        >

                        @error('invoice_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Payment Deadline --}}
                    <div class="col-md-6">

                        <label
                            for="payment_deadline"
                            class="form-label"
                        >
                            Payment Deadline
                        </label>

                        <input
                            type="date"
                            id="payment_deadline"
                            name="payment_deadline"
                            value="{{ old('payment_deadline') }}"
                            class="form-control @error('payment_deadline') is-invalid @enderror"
                        >

                        <div class="form-text">
                            Leave blank if there is no payment deadline.
                        </div>

                        @error('payment_deadline')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Remarks --}}
                    <div class="col-12">

                        <label
                            for="remarks"
                            class="form-label"
                        >
                            Payment Instructions / Remarks
                        </label>

                        <textarea
                            id="remarks"
                            name="remarks"
                            class="form-control @error('remarks') is-invalid @enderror"
                            placeholder="Enter payment instructions, bank information, mobile financial service information, or other instructions for the author..."
                        >{{ old('remarks') }}</textarea>

                        <div class="form-text">
                            These remarks can be used to provide payment instructions
                            to the author.
                        </div>

                        @error('remarks')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- Information --}}
                <div class="info-box">

                    <div class="info-box-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div class="info-box-text">
                        <strong>Important:</strong>
                        Creating the invoice does not send it to the author.
                        After the invoice is created, you will review the
                        invoice and use <strong>Send Payment Request to Author</strong>
                        to move the manuscript to the author payment stage.
                    </div>

                </div>

            </div>


            {{-- Actions --}}
            <div class="action-bar">

                <div class="action-note">
                    <i class="bi bi-shield-check me-1"></i>
                    The invoice will be recorded against this manuscript.
                </div>

                <div class="button-group">

                    <a
                        href="{{ route(
                            'admin.manuscripts.payment.index'
                        ) }}"
                        class="btn-custom btn-secondary-custom"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn-custom btn-primary-custom"
                    >
                        <i class="bi bi-file-earmark-plus"></i>
                        Create Payment Invoice
                    </button>

                </div>

            </div>

        </form>

    </div>


    {{-- Preview --}}
    <div class="card">

        <div class="card-header">

            <h2 class="card-title">
                <i class="bi bi-eye me-2"></i>
                Payment Request Preview
            </h2>

            <p class="card-description">
                The amount below will be used when the payment request is
                presented to the author.
            </p>

        </div>

        <div class="card-body">

            <div class="invoice-preview">

                <div class="preview-title">
                    Payment Amount
                </div>

                <div
                    class="preview-amount"
                    id="amountPreview"
                >
                    ৳ 0.00
                </div>

            </div>

        </div>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const amountInput = document.getElementById('amount');
    const currencyInput = document.getElementById('currency');
    const currencySymbol = document.getElementById('currencySymbol');
    const amountPreview = document.getElementById('amountPreview');

    function updateCurrency() {

        if (currencyInput.value === 'USD') {
            currencySymbol.textContent = '$';
        } else {
            currencySymbol.textContent = '৳';
        }

        updateAmount();
    }

    function updateAmount() {

        const amount = parseFloat(amountInput.value || 0);

        let symbol = '৳';

        if (currencyInput.value === 'USD') {
            symbol = '$';
        }

        amountPreview.textContent =
            symbol + ' ' + amount.toLocaleString(
                'en-US',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );
    }

    currencyInput.addEventListener(
        'change',
        updateCurrency
    );

    amountInput.addEventListener(
        'input',
        updateAmount
    );

    updateCurrency();
});
</script>

@endsection