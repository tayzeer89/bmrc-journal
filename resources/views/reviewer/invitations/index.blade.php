@extends('reviewer.layouts.app')

@section('title', 'Review Invitations | BMRC Journal')

@section('content')

<div class="page-header mb-4">
    <h1>Review Invitations</h1>

    <p class="text-muted mb-0">
        View and respond to manuscript review invitations.
    </p>
</div>


{{-- =========================================================
    INVITATION SUMMARY
========================================================= --}}

<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="reviewer-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">

                    <div class="me-3">
                        <i class="bi bi-envelope-paper fs-2 text-primary"></i>
                    </div>

                    <div>
                        <div class="text-muted small">
                            Total Invitations
                        </div>

                        <h4 class="mb-0">
                            {{ $invitations->count() }}
                        </h4>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="reviewer-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">

                    <div class="me-3">
                        <i class="bi bi-hourglass-split fs-2 text-warning"></i>
                    </div>

                    <div>
                        <div class="text-muted small">
                            Pending Response
                        </div>

                        <h4 class="mb-0">
                            {{ $invitations->where('status', 'pending')->count() }}
                        </h4>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="reviewer-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">

                    <div class="me-3">
                        <i class="bi bi-check-circle fs-2 text-success"></i>
                    </div>

                    <div>
                        <div class="text-muted small">
                            Accepted
                        </div>

                        <h4 class="mb-0">
                            {{ $invitations->where('status', 'accepted')->count() }}
                        </h4>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>


{{-- =========================================================
    INVITATION TABLE
========================================================= --}}

<div class="reviewer-card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <div>
            <i class="bi bi-envelope me-1"></i>
            Review Invitations
        </div>

        @php
            $pendingCount = $invitations
                ->where('status', 'pending')
                ->count();
        @endphp

        @if($pendingCount > 0)

            <span class="badge bg-danger">
                {{ $pendingCount }} Pending
            </span>

        @endif

    </div>


    <div class="card-body">

        @if($invitations->isEmpty())

            <div class="text-center py-5">

                <i
                    class="bi bi-envelope-open text-muted"
                    style="font-size:45px;"
                ></i>

                <h5 class="mt-3">
                    No Review Invitations
                </h5>

                <p class="text-muted mb-0">
                    You currently have no manuscript review invitations.
                </p>

            </div>

        @else

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>
                            <th>Manuscript</th>
                            <th>Article</th>
                            <th>Invited</th>
                            <th>Response Deadline</th>
                            <th>Review Deadline</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($invitations as $invitation)

                            @php

                                $manuscript = $invitation->manuscript;

                                $status = strtolower(
                                    $invitation->status ?? 'pending'
                                );

                            @endphp


                            <tr>

                                {{-- MANUSCRIPT ID --}}
                                <td>

                                    <strong class="text-primary">

                                        {{ $manuscript->manuscript_id
                                            ?? $invitation->manuscript_id }}

                                    </strong>

                                </td>


                                {{-- ARTICLE INFORMATION --}}
                                <td style="min-width:280px;">

                                    <div class="fw-semibold">

                                        {{ $manuscript->title
                                            ?? 'Manuscript title unavailable' }}

                                    </div>


                                    @if($manuscript?->articleType)

                                        <small class="text-muted">

                                            {{ $manuscript->articleType->name }}

                                        </small>

                                    @endif

                                </td>


                                {{-- INVITATION DATE --}}
                                <td>

                                    @if($invitation->invited_at)

                                        {{ \Carbon\Carbon::parse(
                                            $invitation->invited_at
                                        )->format('d M Y') }}

                                    @elseif($invitation->created_at)

                                        {{ $invitation->created_at
                                            ->format('d M Y') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- RESPONSE DEADLINE --}}
                                <td>

                                    @if($invitation->expires_at)

                                        {{ \Carbon\Carbon::parse(
                                            $invitation->expires_at
                                        )->format('d M Y') }}

                                        @if(
                                            $status === 'pending' &&
                                            \Carbon\Carbon::parse(
                                                $invitation->expires_at
                                            )->isPast()
                                        )

                                            <div>
                                                <span class="badge bg-danger mt-1">
                                                    Expired
                                                </span>
                                            </div>

                                        @endif

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- REVIEW DEADLINE --}}
                                <td>

                                    @if($invitation->review_deadline)

                                        {{ \Carbon\Carbon::parse(
                                            $invitation->review_deadline
                                        )->format('d M Y') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- STATUS --}}
                                <td>

                                    @switch($status)

                                        @case('pending')

                                            <span class="badge bg-warning text-dark">

                                                <i class="bi bi-clock me-1"></i>
                                                Pending

                                            </span>

                                            @break


                                        @case('accepted')

                                            <span class="badge bg-success">

                                                <i class="bi bi-check-circle me-1"></i>
                                                Accepted

                                            </span>

                                            @break


                                        @case('declined')

                                            <span class="badge bg-danger">

                                                <i class="bi bi-x-circle me-1"></i>
                                                Declined

                                            </span>

                                            @break


                                        @case('expired')

                                            <span class="badge bg-secondary">

                                                <i class="bi bi-clock-history me-1"></i>
                                                Expired

                                            </span>

                                            @break


                                        @default

                                            <span class="badge bg-secondary">

                                                {{ ucfirst($status) }}

                                            </span>

                                    @endswitch

                                </td>


                                {{-- ACTION --}}
                                <td class="text-center">

                                    <a
                                        href="{{ route(
                                            'reviewer.invitations.show',
                                            $invitation
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >

                                        <i class="bi bi-eye"></i>
                                        View

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

</div>

@endsection