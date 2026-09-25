@extends('admin.layouts.app')

@section('title', 'Reviewer Invitations')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Reviewer Invitations</h4>

            <p class="text-muted mb-0">
                Manage reviewer invitations for manuscript peer review.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.reviewers.index') }}"
                class="btn btn-outline-secondary"
            >
                Reviewer Pool
            </a>

            @can('reviewer.invite')
                <a
                    href="{{ route('admin.reviewer-invitations.create') }}"
                    class="btn btn-primary"
                >
                    Send Invitation
                </a>
            @endcan

        </div>

    </div>


    {{-- Flash Messages --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Statistics --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-3 col-md-6">

            <div class="card h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Total Invitations
                    </div>

                    <h3 class="mb-0">
                        {{ $statistics['total'] ?? 0 }}
                    </h3>

                </div>
            </div>

        </div>


        <div class="col-lg-3 col-md-6">

            <div class="card h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Pending
                    </div>

                    <h3 class="mb-0">
                        {{ $statistics['pending'] ?? 0 }}
                    </h3>

                </div>
            </div>

        </div>


        <div class="col-lg-3 col-md-6">

            <div class="card h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Accepted
                    </div>

                    <h3 class="mb-0">
                        {{ $statistics['accepted'] ?? 0 }}
                    </h3>

                </div>
            </div>

        </div>


        <div class="col-lg-3 col-md-6">

            <div class="card h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Declined
                    </div>

                    <h3 class="mb-0">
                        {{ $statistics['declined'] ?? 0 }}
                    </h3>

                </div>
            </div>

        </div>

    </div>


    {{-- Filters --}}
    <div class="card mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.reviewer-invitations.index') }}"
            >

                <div class="row g-3">

                    <div class="col-lg-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Reviewer, email, manuscript..."
                        >

                    </div>


                    <div class="col-lg-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="pending"
                                @selected(request('status') === 'pending')
                            >
                                Pending
                            </option>

                            <option
                                value="accepted"
                                @selected(request('status') === 'accepted')
                            >
                                Accepted
                            </option>

                            <option
                                value="declined"
                                @selected(request('status') === 'declined')
                            >
                                Declined
                            </option>

                            <option
                                value="expired"
                                @selected(request('status') === 'expired')
                            >
                                Expired
                            </option>

                            <option
                                value="cancelled"
                                @selected(request('status') === 'cancelled')
                            >
                                Cancelled
                            </option>

                        </select>

                    </div>


                    <div class="col-lg-2 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Filter
                        </button>

                    </div>


                    <div class="col-lg-2 d-flex align-items-end">

                        <a
                            href="{{ route('admin.reviewer-invitations.index') }}"
                            class="btn btn-outline-secondary w-100"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Invitation List --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Invitation List
            </strong>

            @isset($invitations)

                <span class="badge bg-primary">
                    {{ $invitations->total() }}
                </span>

            @endisset

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Manuscript</th>
                            <th>Reviewer</th>
                            <th>Invited</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse(($invitations ?? []) as $invitation)

                            <tr>

                                <td>
                                    {{ $invitation->id }}
                                </td>


                                <td>

                                    @if($invitation->manuscript)

                                        <strong>
                                            {{
                                                $invitation->manuscript->manuscript_number
                                                ?? $invitation->manuscript->submission_number
                                                ?? 'Manuscript #'.$invitation->manuscript_id
                                            }}
                                        </strong>

                                        @if($invitation->manuscript->title ?? false)

                                            <div class="small text-muted">
                                                {{
                                                    \Illuminate\Support\Str::limit(
                                                        $invitation->manuscript->title,
                                                        60
                                                    )
                                                }}
                                            </div>

                                        @endif

                                    @else

                                        Manuscript #{{ $invitation->manuscript_id ?? '—' }}

                                    @endif

                                </td>


                                <td>

                                    @if($invitation->reviewer)

                                        <strong>
                                            {{ $invitation->reviewer->name }}
                                        </strong>

                                        <div class="small text-muted">
                                            {{ $invitation->reviewer->email }}
                                        </div>

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    {{
                                        $invitation->created_at
                                            ?->format('d M Y')
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    {{
                                        $invitation->due_date
                                            ?->format('d M Y')
                                        ?? $invitation->deadline
                                            ?->format('d M Y')
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    @switch($invitation->status)

                                        @case('accepted')

                                            <span class="badge bg-success">
                                                Accepted
                                            </span>

                                            @break


                                        @case('declined')

                                            <span class="badge bg-danger">
                                                Declined
                                            </span>

                                            @break


                                        @case('expired')

                                            <span class="badge bg-dark">
                                                Expired
                                            </span>

                                            @break


                                        @case('cancelled')

                                            <span class="badge bg-secondary">
                                                Cancelled
                                            </span>

                                            @break


                                        @default

                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>

                                    @endswitch

                                </td>


                                <td>

                                    <div class="d-flex flex-wrap gap-1">

                                        @can('reviewer.invitation.view')

                                            <a
                                                href="{{ route(
                                                    'admin.reviewer-invitations.show',
                                                    $invitation
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>

                                        @endcan


                                        @if($invitation->status === 'pending')

                                            @can('reviewer.invitation.remind')

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.reviewer-invitations.remind',
                                                        $invitation
                                                    ) }}"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-warning"
                                                    >
                                                        Remind
                                                    </button>

                                                </form>

                                            @endcan


                                            @can('reviewer.invitation.cancel')

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.reviewer-invitations.cancel',
                                                        $invitation
                                                    ) }}"
                                                    onsubmit="return confirm(
                                                        'Cancel this reviewer invitation?'
                                                    );"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                    >
                                                        Cancel
                                                    </button>

                                                </form>

                                            @endcan

                                        @endif

                                    </div>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <h6 class="mb-2">
                                        No Reviewer Invitations
                                    </h6>

                                    <p class="text-muted mb-3">
                                        No reviewer invitations have been created yet.
                                    </p>

                                    @can('reviewer.invite')

                                        <a
                                            href="{{ route(
                                                'admin.reviewer-invitations.create'
                                            ) }}"
                                            class="btn btn-primary"
                                        >
                                            Send First Invitation
                                        </a>

                                    @endcan

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @isset($invitations)

            @if(
                method_exists($invitations, 'hasPages')
                && $invitations->hasPages()
            )

                <div class="card-footer">

                    {{ $invitations->links('pagination::bootstrap-4') }}

                </div>

            @endif

        @endisset

    </div>

</div>

@endsection