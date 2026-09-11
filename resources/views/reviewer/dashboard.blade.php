@extends('reviewer.layouts.app')

@section('title', 'Reviewer Dashboard | BMRC Journal')

@section('content')

<style>

    .reviewer-dashboard {
        background: #f5f7fa;
        min-height: calc(100vh - 70px);
        padding: 35px 0;
    }

    .dashboard-card {
        border: 1px solid #e4e7ec;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,.04);
    }

    .profile-percentage {
        font-size: 1.6rem;
        font-weight: 700;
        color: #0d3b66;
    }

    .information-label {
        color: #667085;
        font-size: .8rem;
    }

    .information-value {
        color: #1d2939;
        font-weight: 500;
    }

    .keyword-badge {
        background: #edf4f8;
        color: #0d3b66;
        padding: 5px 9px;
        border-radius: 20px;
        margin: 2px;
        display: inline-block;
        font-size: .8rem;
    }

</style>


<div class="reviewer-dashboard">

    <div class="container">


        @if(session('success'))

            <div class="alert alert-success">

                <i class="bi bi-check-circle me-2"></i>

                {{ session('success') }}

            </div>

        @endif


        <div class="row g-4">


            {{-- Main --}}

            <div class="col-lg-8">

                <div class="card dashboard-card mb-4">

                    <div class="card-body p-4">

                        <div
                            class="d-flex
                                   justify-content-between
                                   align-items-start
                                   flex-wrap
                                   gap-3"
                        >

                            <div>

                                <small class="text-muted">
                                    Welcome to BMRC Journal
                                </small>

                                <h3 class="mt-1 mb-1">
                                    {{ $profile?->display_name ?? $reviewer->name }}
                                </h3>

                                <div class="text-muted">

                                    {{ $profile?->designation }}

                                    @if($profile?->institution)

                                        · {{ $profile->institution }}

                                    @endif

                                </div>

                            </div>


                            <div>

                                @switch(
                                    $profile?->approval_status
                                )

                                    @case('draft')

                                        <span class="badge bg-secondary">
                                            Draft Profile
                                        </span>

                                        @break


                                    @case('pending_approval')

                                        <span class="badge bg-warning text-dark">
                                            Pending Approval
                                        </span>

                                        @break


                                    @case('update_requested')

                                        <span class="badge bg-info">
                                            Update Requested
                                        </span>

                                        @break


                                    @case('approved')

                                        <span class="badge bg-success">
                                            Approved Reviewer
                                        </span>

                                        @break


                                    @case('rejected')

                                        <span class="badge bg-danger">
                                            Not Approved
                                        </span>

                                        @break

                                @endswitch

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Completion --}}

                <div class="card dashboard-card mb-4">

                    <div class="card-body p-4">

                        <div
                            class="d-flex
                                   justify-content-between
                                   align-items-center
                                   mb-2"
                        >

                            <strong>
                                Profile Completion
                            </strong>

                            <span class="profile-percentage">

                                {{ $profile?->profile_completion_percentage ?? 0 }}%

                            </span>

                        </div>


                        <div
                            class="progress"
                            style="height:10px;"
                        >

                            <div
                                class="progress-bar"
                                style="
                                    width:
                                    {{ $profile?->profile_completion_percentage ?? 0 }}%;
                                "
                            ></div>

                        </div>


                        @if(
                            !$profile?->profile_completed
                        )

                            <div class="alert alert-warning mt-4 mb-3">

                                <i class="bi bi-exclamation-triangle me-2"></i>

                                Your reviewer profile is not yet complete.
                                Complete the remaining academic, research,
                                professional and declaration information before
                                submitting it for editorial approval.

                            </div>


                            <a
                                href="{{ route('reviewer.application.edit') }}"
                                class="btn btn-primary"
                            >

                                <i class="bi bi-pencil-square me-2"></i>

                                Complete Reviewer Profile

                            </a>

                        @endif

                    </div>

                </div>


                {{-- Application status --}}

                <div class="card dashboard-card mb-4">

                    <div class="card-header bg-white py-3">

                        <strong>
                            Reviewer Application
                        </strong>

                    </div>


                    <div class="card-body p-4">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <div class="information-label">
                                    Application ID
                                </div>

                                <div class="information-value">

                                    {{ $profile?->application_id ?? '-' }}

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="information-label">
                                    Reviewer Code
                                </div>

                                <div class="information-value">

                                    {{ $profile?->reviewer_code ?? 'Assigned after approval' }}

                                </div>

                            </div>

                        </div>


                        @if(
                            $profile?->approval_status
                            === 'update_requested'
                        )

                            <div class="alert alert-info mt-4">

                                <strong>
                                    Editorial Office requested an update:
                                </strong>

                                <div class="mt-2">

                                    {{ $profile->profile_update_request }}

                                </div>

                            </div>

                        @endif


                        @if(
                            $profile?->approval_status
                            === 'rejected'
                        )

                            <div class="alert alert-danger mt-4">

                                <strong>
                                    Reason:
                                </strong>

                                <div class="mt-2">

                                    {{ $profile->rejection_reason ?? 'Please contact the Editorial Office.' }}

                                </div>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- CV --}}

                @if($profile?->cv_file)

                    <div class="card dashboard-card">

                        <div class="card-header bg-white py-3">

                            <strong>
                                Curriculum Vitae
                            </strong>

                        </div>


                        <div class="card-body">

                            <a
                                href="{{ asset('storage/' . $profile->cv_file) }}"
                                target="_blank"
                                rel="noopener"
                                class="btn btn-outline-primary mt-3"
                            >

                                <i class="bi bi-box-arrow-up-right me-1"></i>

                                Open CV in New Window

                            </a>

                        </div>

                    </div>

                @endif

            </div>


            {{-- Right Sidebar --}}

            <div class="col-lg-4">


                <div class="card dashboard-card mb-4">

                    <div class="card-header bg-white py-3">

                        <strong>
                            Professional Information
                        </strong>

                    </div>


                    <div class="card-body">

                        <div class="mb-3">

                            <div class="information-label">
                                Email
                            </div>

                            <div class="information-value">
                                {{ $reviewer->email }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="information-label">
                                Mobile
                            </div>

                            <div class="information-value">
                                {{ $profile?->mobile ?? '-' }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="information-label">
                                Institution / Organization
                            </div>

                            <div class="information-value">
                                {{ $profile?->institution ?? '-' }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="information-label">
                                Department
                            </div>

                            <div class="information-value">
                                {{ $profile?->department ?? '-' }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="information-label">
                                Designation
                            </div>

                            <div class="information-value">
                                {{ $profile?->designation ?? '-' }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="information-label">
                                Highest Academic Degree
                            </div>

                            <div class="information-value">
                                {{ $profile?->highest_degree ?? '-' }}
                            </div>

                        </div>


                        <div>

                            <div class="information-label">
                                Location
                            </div>

                            <div class="information-value">

                                {{ $profile?->city_district ?? '-' }},

                                {{ $profile?->division_state ?? '-' }}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Specializations --}}

                <div class="card dashboard-card mb-4">

                    <div class="card-header bg-white py-3">

                        <strong>
                            Specialization
                        </strong>

                    </div>

                    <div class="card-body">

                        @forelse(
                            array_filter(
                                array_map(
                                    'trim',
                                    explode(
                                        ',',
                                        $profile?->specialization ?? ''
                                    )
                                )
                            )
                            as $item
                        )

                            <span class="keyword-badge">
                                {{ $item }}
                            </span>

                        @empty

                            <span class="text-muted">
                                Not provided
                            </span>

                        @endforelse

                    </div>

                </div>


                {{-- Research Interests --}}

                <div class="card dashboard-card mb-4">

                    <div class="card-header bg-white py-3">

                        <strong>
                            Research Interests
                        </strong>

                    </div>

                    <div class="card-body">

                        @forelse(
                            array_filter(
                                array_map(
                                    'trim',
                                    explode(
                                        ',',
                                        $profile?->research_interests ?? ''
                                    )
                                )
                            )
                            as $item
                        )

                            <span class="keyword-badge">
                                {{ $item }}
                            </span>

                        @empty

                            <span class="text-muted">
                                Not provided
                            </span>

                        @endforelse

                    </div>

                </div>


                {{-- Review Keywords --}}

                <div class="card dashboard-card">

                    <div class="card-header bg-white py-3">

                        <strong>
                            Research / Review Keywords
                        </strong>

                    </div>

                    <div class="card-body">

                        @forelse(
                            array_filter(
                                array_map(
                                    'trim',
                                    explode(
                                        ',',
                                        $profile?->expertise_keywords ?? ''
                                    )
                                )
                            )
                            as $item
                        )

                            <span class="keyword-badge">
                                {{ $item }}
                            </span>

                        @empty

                            <span class="text-muted">
                                Not provided
                            </span>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection