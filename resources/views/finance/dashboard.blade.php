@extends('admin.layouts.app')

@section('title', 'Finance Dashboard')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="mb-4">

        <h2 class="fw-bold">
            Finance & Accounts Dashboard
        </h2>

        <p class="text-muted mb-0">
            Welcome, {{ auth()->user()->name }}
        </p>

    </div>


    {{-- Dashboard Cards --}}
    <div class="row g-4">

        {{-- Payments --}}
        @can('payment.view')

        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Payments
                    </h5>

                    <p class="text-muted">
                        Manage journal payments,
                        verification and refunds.
                    </p>

                    @can('payment.view')
                        <a href="#"
                           class="btn btn-primary">
                            View Payments
                        </a>
                    @endcan

                </div>

            </div>

        </div>

        @endcan


        {{-- Tax --}}
        @can('tax.view')

        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Tax
                    </h5>

                    <p class="text-muted">
                        View and manage tax-related
                        financial information.
                    </p>

                    @can('tax.manage')

                        <a href="#"
                           class="btn btn-primary">
                            Manage Tax
                        </a>

                    @else

                        <a href="#"
                           class="btn btn-outline-primary">
                            View Tax
                        </a>

                    @endcan

                </div>

            </div>

        </div>

        @endcan


        {{-- TDS --}}
        @can('tds.manage')

        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold">
                        TDS Management
                    </h5>

                    <p class="text-muted">
                        Manage Tax Deducted at Source
                        information.
                    </p>

                    <a href="#"
                       class="btn btn-primary">
                        Manage TDS
                    </a>

                </div>

            </div>

        </div>

        @endcan


        {{-- Reviewer Payment --}}
        @can('reviewer_payment.view')

        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Reviewer Payments
                    </h5>

                    <p class="text-muted">
                        Manage reviewer payment
                        activities.
                    </p>

                    <a href="#"
                       class="btn btn-primary">
                        Reviewer Payments
                    </a>

                </div>

            </div>

        </div>

        @endcan

    </div>

</div>

@endsection