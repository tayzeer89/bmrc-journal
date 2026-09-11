@extends('reviewer.layouts.app')

@section('title', 'Reviewer Payments | BMRC Journal')

@section('content')

<div class="page-header">

    <h1>
        Reviewer Payments
    </h1>

    <p>
        View reviewer honorarium and payment information.
    </p>

</div>


<div class="row g-3 mb-4">

    <div class="col-md-6">

        <div class="reviewer-card">

            <div class="card-body">

                <small class="text-muted">
                    Total Paid
                </small>

                <h4 class="mt-2 mb-0">
                    ৳ {{ number_format($totalPaid, 2) }}
                </h4>

            </div>

        </div>

    </div>


    <div class="col-md-6">

        <div class="reviewer-card">

            <div class="card-body">

                <small class="text-muted">
                    Pending Payment
                </small>

                <h4 class="mt-2 mb-0">
                    ৳ {{ number_format($totalPending, 2) }}
                </h4>

            </div>

        </div>

    </div>

</div>


<div class="reviewer-card">

    <div class="card-header">

        <i class="bi bi-wallet2 me-1"></i>

        Payment History

    </div>


    <div class="card-body">

        @if($payments->isEmpty())

            <div class="text-center py-5">

                <i
                    class="bi bi-wallet text-muted"
                    style="font-size:45px;"
                ></i>

                <h5 class="mt-3">
                    No Payment Records
                </h5>

                <p class="text-muted mb-0">
                    Reviewer payment records will appear here after eligible reviews are completed.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection