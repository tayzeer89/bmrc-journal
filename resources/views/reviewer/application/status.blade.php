@extends('reviewer.layouts.app')

@section('title', 'Reviewer Application Status | BMRC Journal')

@section('content')

<div class="page-header">

    <h1>
        Reviewer Application Status
    </h1>

    <p>
        Track the current status of your reviewer application.
    </p>

</div>


<div class="reviewer-card mb-4">

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-4">

                <small class="text-muted d-block">
                    Application ID
                </small>

                <strong>
                    {{ $profile->application_id ?: '-' }}
                </strong>

            </div>


            <div class="col-md-4">

                <small class="text-muted d-block">
                    Reviewer Code
                </small>

                <strong>
                    {{ $profile->reviewer_code ?: 'Pending' }}
                </strong>

            </div>


            <div class="col-md-4">

                <small class="text-muted d-block">
                    Account Status
                </small>

                <strong>
                    {{ ucfirst($reviewer->status) }}
                </strong>

            </div>

        </div>

    </div>

</div>


<div class="reviewer-card">

    <div class="card-header fw-bold">

        <i class="bi bi-clipboard-check me-2"></i>

        Application Review Status

    </div>


    <div class="card-body">


        @if($profile->isDraft())

            <div class="alert alert-secondary">

                <h5 class="alert-heading">
                    Draft
                </h5>

                <p class="mb-3">
                    Your reviewer application has not yet been submitted for editorial approval.
                </p>

                <a
                    href="{{ route('reviewer.application.edit') }}"
                    class="btn btn-primary"
                >
                    Complete Application
                </a>

            </div>


        @elseif($profile->isPendingApproval())

            <div class="alert alert-info">

                <h5 class="alert-heading">
                    Application Submitted
                </h5>

                <p class="mb-2">
                    Your reviewer application has been submitted to the BMRC Journal Editorial Office.
                </p>

                @if($profile->submitted_for_approval_at)

                    <small>
                        Submitted:
                        {{ $profile->submitted_for_approval_at->format('d M Y, h:i A') }}
                    </small>

                @endif

            </div>


        @elseif($profile->isUpdateRequested())

            <div class="alert alert-warning">

                <h5 class="alert-heading">
                    Profile Update Requested
                </h5>

                <p>
                    The BMRC Editorial Office has requested changes to your reviewer profile.
                </p>

                @if($profile->profile_update_request)

                    <div class="border rounded p-3 bg-white mb-3">

                        <strong>
                            Editorial Comment:
                        </strong>

                        <div class="mt-2">
                            {{ $profile->profile_update_request }}
                        </div>

                    </div>

                @endif


                @if($profile->update_requested_at)

                    <small class="d-block mb-3">
                        Requested:
                        {{ $profile->update_requested_at->format('d M Y, h:i A') }}
                    </small>

                @endif


                <a
                    href="{{ route('reviewer.application.edit') }}"
                    class="btn btn-warning"
                >
                    Update Profile
                </a>

            </div>


        @elseif($profile->isApproved())

            <div class="alert alert-success">

                <h5 class="alert-heading">
                    Reviewer Application Approved
                </h5>

                <p>
                    Your reviewer application has been approved by the BMRC Journal Editorial Office.
                </p>

                @if($profile->approved_at)

                    <small class="d-block mb-3">
                        Approved:
                        {{ $profile->approved_at->format('d M Y, h:i A') }}
                    </small>

                @endif


                <a
                    href="{{ route('reviewer.profile.show') }}"
                    class="btn btn-success"
                >
                    View Reviewer Profile
                </a>

            </div>


        @elseif($profile->isRejected())

            <div class="alert alert-danger">

                <h5 class="alert-heading">
                    Reviewer Application Not Approved
                </h5>

                <p>
                    Your reviewer application was not approved.
                </p>


                @if($profile->rejection_reason)

                    <div class="border rounded p-3 bg-white mb-3">

                        <strong>
                            Reason:
                        </strong>

                        <div class="mt-2">
                            {{ $profile->rejection_reason }}
                        </div>

                    </div>

                @endif


                @if($profile->rejected_at)

                    <small>
                        Decision Date:
                        {{ $profile->rejected_at->format('d M Y, h:i A') }}
                    </small>

                @endif

            </div>

        @endif


    </div>

</div>


<div class="mt-4 d-flex gap-2">

    <a
        href="{{ route('reviewer.dashboard') }}"
        class="btn btn-outline-secondary"
    >
        <i class="bi bi-arrow-left me-1"></i>
        Dashboard
    </a>


    <a
        href="{{ route('reviewer.profile.show') }}"
        class="btn btn-outline-primary"
    >
        <i class="bi bi-person me-1"></i>
        View Profile
    </a>

</div>

@endsection