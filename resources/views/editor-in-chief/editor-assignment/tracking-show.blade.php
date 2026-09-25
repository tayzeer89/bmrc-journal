@extends('admin.layouts.app')

@section('title', 'Assignment Details')
@section('page_title', 'Handling Editor Assignment Details')

@section('content')

@php
    $manuscript = $assignment->manuscript;

    $status = strtolower(
        $assignment->status ?? 'pending'
    );

    $statusClass = match($status) {
        'accepted'   => 'success',
        'pending'    => 'warning',
        'declined'   => 'danger',
        'completed'  => 'primary',
        'cancelled'  => 'secondary',
        'reassigned' => 'info',
        default      => 'secondary',
    };
@endphp


<div class="container-fluid px-0">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Handling Editor Assignment
            </h4>

            <div class="text-muted">
                View manuscript assignment and editorial progress
            </div>
        </div>

        <a
            href="{{ route('eic.editor-assignment.tracking') }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left"></i>
            Back to Tracking
        </a>

    </div>


    {{-- =====================================================
         MANUSCRIPT INFORMATION
    ====================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-light">
            <strong>
                <i class="bi bi-file-earmark-text me-1"></i>
                Manuscript Information
            </strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">
                    <small class="text-muted d-block">
                        Manuscript ID
                    </small>

                    <strong>
                        {{ $manuscript->manuscript_id ?? $manuscript->id }}
                    </strong>
                </div>


                <div class="col-md-4">
                    <small class="text-muted d-block">
                        Journal
                    </small>

                    {{ $manuscript->journal->name ?? 'N/A' }}
                </div>


                <div class="col-md-4">
                    <small class="text-muted d-block">
                        Article Type
                    </small>

                    {{ $manuscript->articleType->name ?? 'N/A' }}
                </div>


                <div class="col-12">

                    <small class="text-muted d-block">
                        Article Title
                    </small>

                    <strong>
                        {{ $manuscript->title }}
                    </strong>

                </div>


                <div class="col-md-6">

                    <small class="text-muted d-block">
                        Current Manuscript Status
                    </small>

                    <span class="badge bg-primary">
                        {{
                            ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $manuscript->status
                                )
                            )
                        }}
                    </span>

                </div>


                <div class="col-md-6">

                    <small class="text-muted d-block">
                        Current Workflow Stage
                    </small>

                    <span class="badge bg-info text-dark">
                        {{
                            ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $manuscript->current_stage
                                        ?? $manuscript->status
                                )
                            )
                        }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         HANDLING EDITOR
    ====================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-light">
            <strong>
                <i class="bi bi-person-check me-1"></i>
                Handling Editor Assignment
            </strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">

                    <small class="text-muted d-block">
                        Handling Editor
                    </small>

                    <strong>
                        {{ $assignment->editor->name ?? 'N/A' }}
                    </strong>

                    @if($assignment->editor?->email)

                        <div class="small text-muted">
                            {{ $assignment->editor->email }}
                        </div>

                    @endif

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Assignment Status
                    </small>

                    <span class="badge bg-{{ $statusClass }}">
                        {{
                            ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $assignment->status
                                )
                            )
                        }}
                    </span>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Assignment Round
                    </small>

                    <strong>
                        {{ $assignment->assignment_round ?? 1 }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Assigned By
                    </small>

                    {{ $assignment->assignedBy->name ?? 'N/A' }}

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Assigned Date
                    </small>

                    {{
                        $assignment->assigned_at
                            ? \Carbon\Carbon::parse(
                                $assignment->assigned_at
                            )->format('d M Y h:i A')
                            : 'N/A'
                    }}

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Assessment Deadline
                    </small>

                    {{
                        $assignment->due_date
                            ? \Carbon\Carbon::parse(
                                $assignment->due_date
                            )->format('d M Y')
                            : 'N/A'
                    }}

                </div>


                @if($assignment->accepted_at)

                    <div class="col-md-4">

                        <small class="text-muted d-block">
                            Accepted At
                        </small>

                        {{
                            \Carbon\Carbon::parse(
                                $assignment->accepted_at
                            )->format('d M Y h:i A')
                        }}

                    </div>

                @endif


                @if($assignment->declined_at)

                    <div class="col-md-4">

                        <small class="text-muted d-block">
                            Declined At
                        </small>

                        {{
                            \Carbon\Carbon::parse(
                                $assignment->declined_at
                            )->format('d M Y h:i A')
                        }}

                    </div>

                @endif


                @if($assignment->assignment_note)

                    <div class="col-12">

                        <hr>

                        <small class="text-muted d-block">
                            Assignment Instructions
                        </small>

                        <div class="mt-1">
                            {{ $assignment->assignment_note }}
                        </div>

                    </div>

                @endif


                @if($assignment->decline_reason)

                    <div class="col-12">

                        <div class="alert alert-danger mb-0">

                            <strong>
                                Decline Reason:
                            </strong>

                            {{ $assignment->decline_reason }}

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =====================================================
         EDITORIAL PROGRESS
    ====================================================== --}}

    <div class="card shadow-sm">

        <div class="card-header bg-light">
            <strong>
                <i class="bi bi-diagram-3 me-1"></i>
                Editorial Progress
            </strong>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>Stage</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody>

                        <tr>
                            <td>
                                Handling Editor Assignment
                            </td>

                            <td>
                                <span class="badge bg-{{ $statusClass }}">
                                    {{
                                        ucfirst(
                                            $assignment->status
                                        )
                                    }}
                                </span>
                            </td>
                        </tr>


                        <tr>
                            <td>
                                Editorial Assessment
                            </td>

                            <td>
                                @if(
                                    $manuscript->latestEditorialAssessment
                                )

                                    <span class="badge bg-success">
                                        Completed
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Pending
                                    </span>

                                @endif
                            </td>
                        </tr>


                        <tr>
                            <td>
                                Editorial Recommendation
                            </td>

                            <td>
                                @if($manuscript->latestRecommendation)

                                    <span class="badge bg-success">
                                        {{
                                            ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $manuscript
                                                        ->latestRecommendation
                                                        ->recommendation
                                                )
                                            )
                                        }}
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Pending
                                    </span>

                                @endif
                            </td>
                        </tr>


                        <tr>
                            <td>
                                Final Decision
                            </td>

                            <td>
                                @if($manuscript->latestEditorialDecision)

                                    <span class="badge bg-success">
                                        {{
                                            ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $manuscript
                                                        ->latestEditorialDecision
                                                        ->decision
                                                )
                                            )
                                        }}
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Pending
                                    </span>

                                @endif
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection