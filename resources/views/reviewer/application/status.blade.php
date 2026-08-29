@extends('layouts.app')

@section('title', 'Reviewer Application Status')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-12 col-md-9 col-lg-7">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4 p-md-5 text-center">

                    <div class="mb-4">

                        @if($reviewerProfile->status === 'pending')

                            <i class="bi bi-hourglass-split text-warning"
                               style="font-size: 60px;"></i>

                        @elseif($reviewerProfile->status === 'approved')

                            <i class="bi bi-check-circle-fill text-success"
                               style="font-size: 60px;"></i>

                        @elseif($reviewerProfile->status === 'rejected')

                            <i class="bi bi-x-circle-fill text-danger"
                               style="font-size: 60px;"></i>

                        @endif

                    </div>


                    <h3 class="fw-bold mb-3">

                        Reviewer Application

                    </h3>


                    @if($reviewerProfile->status === 'pending')

                        <div class="alert alert-warning">

                            <h5 class="fw-bold">
                                Application Under Review
                            </h5>

                            <p class="mb-0">

                                Your reviewer application has been
                                submitted successfully and is currently
                                awaiting approval by the BMRC Journal
                                Editorial Office.

                            </p>

                        </div>

                    @elseif($reviewerProfile->status === 'approved')

                        <div class="alert alert-success">

                            <h5 class="fw-bold">
                                Application Approved
                            </h5>

                            <p class="mb-0">

                                Congratulations. Your reviewer
                                application has been approved.

                            </p>

                        </div>

                        <a href="{{ route('reviewer.dashboard') }}"
                           class="btn btn-primary">

                            Go to Reviewer Dashboard

                        </a>

                    @elseif($reviewerProfile->status === 'rejected')

                        <div class="alert alert-danger">

                            <h5 class="fw-bold">
                                Application Not Approved
                            </h5>

                            @if($reviewerProfile->rejection_reason)

                                <p class="mb-0">

                                    {{ $reviewerProfile->rejection_reason }}

                                </p>

                            @endif

                        </div>

                    @endif


                    <hr class="my-4">


                    <div class="row text-start g-3">

                        <div class="col-md-6">

                            <strong>
                                Application ID
                            </strong>

                            <div class="text-muted">

                                {{ $reviewerProfile->application_id }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <strong>
                                Reviewer ID
                            </strong>

                            <div class="text-muted">

                                {{ $reviewerProfile->reviewer_id }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <strong>
                                Applicant
                            </strong>

                            <div class="text-muted">

                                {{ $reviewerProfile->display_name }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <strong>
                                Application Date
                            </strong>

                            <div class="text-muted">

                                {{ optional($reviewerProfile->applied_at)->format('d M Y') }}

                            </div>

                        </div>

                    </div>


                    <div class="mt-4">

                        <form method="POST"
                              action="{{ route('reviewer.logout') }}">

                            @csrf

                            <button type="submit"
                                    class="btn btn-outline-secondary">

                                Sign Out

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection