@extends('admin.layouts.app')

@section('title', 'Payment Setup')

@section('content')

<style>
    .payment-page {
        max-width: 1600px;
        margin: 0 auto;
        padding: 24px 18px 40px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-title {
        margin: 0;
        font-size: 25px;
        font-weight: 700;
        color: #212529;
        letter-spacing: -0.3px;
    }

    .page-subtitle {
        margin: 6px 0 0;
        color: #6c757d;
        font-size: 14px;
    }

    .header-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #f1f5f9;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #495057;
        font-size: 21px;
        margin-right: 12px;
        vertical-align: middle;
    }

    .workflow-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
        padding: 18px 20px;
        margin-bottom: 22px;
    }

    .workflow-title {
        font-size: 13px;
        font-weight: 700;
        color: #495057;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 15px;
    }

    .workflow {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .workflow-step {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 12px;
        border-radius: 9px;
        background: #f8f9fa;
        color: #6c757d;
        font-size: 12px;
        font-weight: 600;
    }

    .workflow-step.active {
        background: #fff3cd;
        color: #856404;
    }

    .workflow-step.completed {
        background: #d1e7dd;
        color: #0f5132;
    }

    .workflow-arrow {
        color: #adb5bd;
        font-size: 13px;
    }

    .queue-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .queue-header {
        padding: 20px 22px;
        border-bottom: 1px solid #edf0f2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .queue-title {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: #212529;
    }

    .queue-description {
        margin: 4px 0 0;
        font-size: 13px;
        color: #6c757d;
    }

    .count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 30px;
        padding: 0 10px;
        border-radius: 20px;
        background: #f1f3f5;
        color: #495057;
        font-size: 13px;
        font-weight: 700;
    }

    .table-wrap {
        overflow-x: auto;
    }

    .payment-table {
        width: 100%;
        margin: 0;
        border-collapse: collapse;
    }

    .payment-table th {
        background: #f8f9fa;
        color: #6c757d;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .45px;
        padding: 13px 16px;
        border-bottom: 1px solid #e9ecef;
        white-space: nowrap;
    }

    .payment-table td {
        padding: 16px;
        border-bottom: 1px solid #f0f1f2;
        vertical-align: middle;
        color: #343a40;
        font-size: 13px;
    }

    .payment-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .payment-table tbody tr:hover {
        background: #fafbfc;
    }

    .manuscript-id {
        font-weight: 700;
        color: #212529;
        font-size: 13px;
    }

    .manuscript-title {
        margin-top: 4px;
        color: #6c757d;
        font-size: 12px;
        max-width: 420px;
        line-height: 1.5;
    }

    .article-type {
        color: #495057;
        font-size: 13px;
    }

    .technical-passed {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 9px;
        border-radius: 7px;
        background: #d1e7dd;
        color: #0f5132;
        font-size: 11px;
        font-weight: 700;
    }

    .payment-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 9px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
    }

    .payment-status.setup {
        background: #fff3cd;
        color: #856404;
    }

    .payment-status.pending {
        background: #e2e3e5;
        color: #41464b;
    }

    .amount-box {
        font-weight: 700;
        color: #212529;
    }

    .no-payment {
        color: #adb5bd;
        font-size: 12px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 36px;
        padding: 7px 13px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        background: #fff;
        color: #343a40;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all .15s ease;
        white-space: nowrap;
    }

    .action-btn:hover {
        background: #f8f9fa;
        border-color: #adb5bd;
        color: #212529;
    }

    .action-btn.primary {
        background: #212529;
        border-color: #212529;
        color: #fff;
    }

    .action-btn.primary:hover {
        background: #343a40;
        border-color: #343a40;
        color: #fff;
    }

    .empty-state {
        padding: 65px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #f8f9fa;
        color: #adb5bd;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
        margin-bottom: 15px;
    }

    .empty-title {
        font-size: 16px;
        font-weight: 700;
        color: #495057;
        margin-bottom: 5px;
    }

    .empty-text {
        font-size: 13px;
        color: #868e96;
        max-width: 500px;
        margin: 0 auto;
    }

    .pagination-wrap {
        padding: 17px 20px;
        border-top: 1px solid #edf0f2;
        display: flex;
        justify-content: flex-end;
    }

    .alert {
        border-radius: 10px;
        font-size: 13px;
    }

    @media (max-width: 767.98px) {

        .payment-page {
            padding: 18px 12px 30px;
        }

        .page-header {
            flex-direction: column;
            gap: 12px;
        }

        .page-title {
            font-size: 21px;
        }

        .workflow {
            align-items: stretch;
        }

        .workflow-arrow {
            display: none;
        }

        .workflow-step {
            width: 100%;
        }

        .queue-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .payment-table {
            min-width: 1050px;
        }

        .pagination-wrap {
            justify-content: center;
        }
    }
</style>

<div class="payment-page">

    {{-- Page Header --}}
    <div class="page-header">

        <div>
            <h1 class="page-title">
                <span class="header-icon">
                    <i class="bi bi-credit-card-2-front"></i>
                </span>
                Payment Setup
            </h1>

            <p class="page-subtitle">
                Configure article processing fees and prepare payment requests
                for authors after successful technical review.
            </p>
        </div>

    </div>


    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
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
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('warning') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-x-circle me-2"></i>
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Workflow --}}
    <div class="workflow-card">

        <div class="workflow-title">
            <i class="bi bi-diagram-3 me-1"></i>
            Current Workflow
        </div>

        <div class="workflow">

            <div class="workflow-step completed">
                <i class="bi bi-check-circle-fill"></i>
                Submission
            </div>

            <span class="workflow-arrow">
                <i class="bi bi-chevron-right"></i>
            </span>

            <div class="workflow-step completed">
                <i class="bi bi-check-circle-fill"></i>
                Technical Review
            </div>

            <span class="workflow-arrow">
                <i class="bi bi-chevron-right"></i>
            </span>

            <div class="workflow-step completed">
                <i class="bi bi-check-circle-fill"></i>
                Technical Check
            </div>

            <span class="workflow-arrow">
                <i class="bi bi-chevron-right"></i>
            </span>

            <div class="workflow-step active">
                <i class="bi bi-credit-card"></i>
                Payment Setup
            </div>

            <span class="workflow-arrow">
                <i class="bi bi-chevron-right"></i>
            </span>

            <div class="workflow-step">
                <i class="bi bi-person"></i>
                Author Payment
            </div>

            <span class="workflow-arrow">
                <i class="bi bi-chevron-right"></i>
            </span>

            <div class="workflow-step">
                <i class="bi bi-check2-square"></i>
                Verification
            </div>

            <span class="workflow-arrow">
                <i class="bi bi-chevron-right"></i>
            </span>

            <div class="workflow-step">
                <i class="bi bi-journal-check"></i>
                Editorial Assessment
            </div>

        </div>
    </div>


    {{-- Queue --}}
    <div class="queue-card">

        <div class="queue-header">

            <div>
                <h2 class="queue-title">
                    Manuscripts Awaiting Payment Setup
                </h2>

                <p class="queue-description">
                    These manuscripts have passed technical review and require
                    an invoice before the payment request can be sent to the author.
                </p>
            </div>

            <span class="count-badge">
                {{ $manuscripts->total() }}
            </span>

        </div>


        @if($manuscripts->count())

            <div class="table-wrap">

                <table class="payment-table">

                    <thead>
                        <tr>
                            <th style="width: 210px;">Manuscript</th>
                            <th>Article Type</th>
                            <th>Technical Review</th>
                            <th>Payment</th>
                            <th>Amount</th>
                            <th style="width: 170px;">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($manuscripts as $manuscript)

                            @php
                                $payment = $manuscript->latestPayment;
                            @endphp

                            <tr>

                                {{-- Manuscript --}}
                                <td>

                                    <div class="manuscript-id">
                                        {{ $manuscript->manuscript_id }}
                                    </div>

                                    <div class="manuscript-title">
                                        {{ \Illuminate\Support\Str::limit(
                                            $manuscript->title,
                                            85
                                        ) }}
                                    </div>

                                </td>


                                {{-- Article Type --}}
                                <td>

                                    <div class="article-type">
                                        {{ optional($manuscript->articleType)->name ?? '—' }}
                                    </div>

                                </td>


                                {{-- Technical Check --}}
                                <td>

                                    <span class="technical-passed">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Passed
                                    </span>

                                </td>


                                {{-- Payment --}}
                                <td>

                                    @if($payment)

                                        @if($payment->sent_to_author_at)

                                            <span class="payment-status pending">
                                                <i class="bi bi-send-check"></i>
                                                Sent to Author
                                            </span>

                                        @else

                                            <span class="payment-status setup">
                                                <i class="bi bi-file-earmark-plus"></i>
                                                Invoice Created
                                            </span>

                                        @endif

                                    @else

                                        <span class="payment-status setup">
                                            <i class="bi bi-hourglass-split"></i>
                                            Setup Required
                                        </span>

                                    @endif

                                </td>


                                {{-- Amount --}}
                                <td>

                                    @if($payment)

                                        <span class="amount-box">
                                            {{ $payment->currency }}
                                            {{ number_format(
                                                (float) $payment->amount,
                                                2
                                            ) }}
                                        </span>

                                    @else

                                        <span class="no-payment">
                                            Not Set
                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td>

                                    @if($payment)

                                        <a
                                            href="{{ route(
                                                'admin.payments.show',
                                                $payment
                                            ) }}"
                                            class="action-btn"
                                        >
                                            <i class="bi bi-eye"></i>
                                            View Invoice
                                        </a>

                                    @else

                                        <a
                                            href="{{ route(
                                                'admin.manuscripts.payment.create',
                                                $manuscript
                                            ) }}"
                                            class="action-btn primary"
                                        >
                                            <i class="bi bi-plus-lg"></i>
                                            Set Payment
                                        </a>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($manuscripts->hasPages())

                <div class="pagination-wrap">
                    {{ $manuscripts->links() }}
                </div>

            @endif

        @else

            {{-- Empty State --}}
            <div class="empty-state">

                <div class="empty-icon">
                    <i class="bi bi-credit-card"></i>
                </div>

                <div class="empty-title">
                    No Manuscripts Awaiting Payment Setup
                </div>

                <p class="empty-text">
                    There are currently no manuscripts waiting for payment
                    configuration. Manuscripts will appear here after their
                    technical check has been successfully completed.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection