@extends('admin.layouts.app')

@section('title', 'Reviewer Request Details')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Reviewer Request Details
            </h4>

            <p class="text-muted mb-0">
                Review the proposed reviewer information and request details.
            </p>
        </div>

        <a
            href="{{ route('admin.reviewers.requests.index') }}"
            class="btn btn-outline-secondary"
        >
            Back to Request List
        </a>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- Reviewer Information --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>Proposed Reviewer Information</strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">

                    <label class="form-label text-muted">
                        Reviewer Name
                    </label>

                    <div class="fw-semibold">
                        {{ $reviewerRequest->name ?? '—' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <label class="form-label text-muted">
                        Email Address
                    </label>

                    <div>
                        {{ $reviewerRequest->email ?? '—' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <label class="form-label text-muted">
                        Institution
                    </label>

                    <div>
                        {{ $reviewerRequest->institution ?? '—' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <label class="form-label text-muted">
                        Designation
                    </label>

                    <div>
                        {{ $reviewerRequest->designation ?? '—' }}
                    </div>

                </div>


                <div class="col-md-12">

                    <label class="form-label text-muted">
                        Specialization / Expertise
                    </label>

                    <div>
                        {{ $reviewerRequest->specialization ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Request Information --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>Request Information</strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">

                    <label class="form-label text-muted">
                        Requested By
                    </label>

                    <div>
                        {{ $reviewerRequest->requester?->name ?? '—' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <label class="form-label text-muted">
                        Requested Date
                    </label>

                    <div>
                        {{
                            $reviewerRequest->created_at
                                ?->format('d M Y h:i A')
                            ?? '—'
                        }}
                    </div>

                </div>


                <div class="col-md-6">

                    <label class="form-label text-muted">
                        Request Status
                    </label>

                    <div>

                        @switch($reviewerRequest->status)

                            @case('approved')

                                <span class="badge bg-success">
                                    Approved
                                </span>

                                @break


                            @case('rejected')

                                <span class="badge bg-danger">
                                    Rejected
                                </span>

                                @break


                            @case('completed')

                                <span class="badge bg-primary">
                                    Completed
                                </span>

                                @break


                            @default

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                        @endswitch

                    </div>

                </div>


                <div class="col-md-6">

                    <label class="form-label text-muted">
                        Processed By
                    </label>

                    <div>
                        {{ $reviewerRequest->processor?->name ?? 'Not Processed' }}
                    </div>

                </div>


                <div class="col-md-12">

                    <label class="form-label text-muted">
                        Reason for Request
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {{
                            $reviewerRequest->reason
                            ?? 'No reason provided.'
                        }}

                    </div>

                </div>


                @if($reviewerRequest->remarks)

                    <div class="col-md-12">

                        <label class="form-label text-muted">
                            Processing Remarks
                        </label>

                        <div class="border rounded p-3">

                            {{ $reviewerRequest->remarks }}

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- Processing Information --}}
    @if($reviewerRequest->processed_at)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Processing Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">

                        <label class="form-label text-muted">
                            Processed By
                        </label>

                        <div>
                            {{ $reviewerRequest->processor?->name ?? '—' }}
                        </div>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label text-muted">
                            Processed Date
                        </label>

                        <div>

                            {{
                                $reviewerRequest->processed_at
                                    ?->format('d M Y h:i A')
                                ?? '—'
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- Footer Actions --}}
    <div class="d-flex justify-content-between">

        <a
            href="{{ route('admin.reviewers.requests.index') }}"
            class="btn btn-outline-secondary"
        >
            Back
        </a>


        @if($reviewerRequest->status === 'pending')

            <div class="text-muted small align-self-center">

                This reviewer request is waiting for processing.

            </div>

        @endif

    </div>

</div>

@endsection