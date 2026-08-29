@extends('reviewer.layouts.app')

@section('title', 'Reviewer Dashboard - BMRC Journal')

@section('content')

<div class="page-header">

    <h1>
        Reviewer Dashboard
    </h1>

    <p>
        Welcome, {{ $profile->display_name }}.
        Your BMRC reviewer account is active.
    </p>

</div>


<div class="row g-4">

    <div class="col-md-3">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="text-muted">
                    New Invitations
                </div>

                <h2 class="fw-bold">
                    0
                </h2>

            </div>

        </div>

    </div>


    <div class="col-md-3">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="text-muted">
                    Pending Reviews
                </div>

                <h2 class="fw-bold">
                    0
                </h2>

            </div>

        </div>

    </div>


    <div class="col-md-3">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="text-muted">
                    Completed Reviews
                </div>

                <h2 class="fw-bold">
                    0
                </h2>

            </div>

        </div>

    </div>


    <div class="col-md-3">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="text-muted">
                    Payments
                </div>

                <h2 class="fw-bold">
                    ৳ 0
                </h2>

            </div>

        </div>

    </div>

</div>


<div class="card border-0 shadow-sm mt-4">

    <div class="card-body">

        <h5 class="fw-bold">
            Reviewer Information
        </h5>

        <hr>

        <div class="row">

            <div class="col-md-6 mb-3">

                <strong>
                    Application ID
                </strong>

                <div>
                    {{ $profile->application_id }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <strong>
                    Institution
                </strong>

                <div>
                    {{ $profile->institution }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <strong>
                    Department
                </strong>

                <div>
                    {{ $profile->department }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <strong>
                    Designation
                </strong>

                <div>
                    {{ $profile->designation }}
                </div>

            </div>

        </div>

    </div>

</div>

@endsection