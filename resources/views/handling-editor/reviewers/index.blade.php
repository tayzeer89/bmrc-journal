@extends('admin.layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Reviewer Selection
            </h3>

            <p class="text-muted mb-0">

                Select appropriate reviewers for

                <strong>
                    {{ $manuscript->manuscript_id }}
                </strong>

            </p>

        </div>


        <a
            href="{{ route(
                'handling-editor.assignments.index'
            ) }}"
            class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left"></i>

            Back

        </a>

    </div>


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Manuscript Summary --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <div class="row">

                <div class="col-md-2">

                    <small class="text-muted">
                        Manuscript ID
                    </small>

                    <div class="fw-semibold">

                        {{ $manuscript->manuscript_id }}

                    </div>

                </div>


                <div class="col-md-5">

                    <small class="text-muted">
                        Title
                    </small>

                    <div class="fw-semibold">

                        {{ $manuscript->title }}

                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Subject
                    </small>

                    <div>

                        {{ $manuscript->subject_category ?? 'N/A' }}

                    </div>

                </div>


                <div class="col-md-2">

                    <small class="text-muted">
                        Review Round
                    </small>

                    <div>

                        {{ $manuscript->review_round ?? 1 }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Search --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Search Reviewer Database
            </h5>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route(
                    'handling-editor.reviewers.index',
                    $manuscript
                ) }}">

                <div class="row g-3">


                    <div class="col-md-4">

                        <label class="form-label">
                            Name / Email
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Reviewer name or email">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Subject / Specialty
                        </label>

                        <input
                            type="text"
                            name="specialty"
                            value="{{ request('specialty') }}"
                            class="form-control"
                            placeholder="e.g. Cardiology">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Reviewer Type
                        </label>

                        <select
                            name="reviewer_type"
                            class="form-select">

                            <option value="">
                                All
                            </option>

                            <option
                                value="national"
                                @selected(
                                    request('reviewer_type')
                                    ===
                                    'national'
                                )>

                                National

                            </option>

                            <option
                                value="international"
                                @selected(
                                    request('reviewer_type')
                                    ===
                                    'international'
                                )>

                                International

                            </option>

                        </select>

                    </div>


                    <div class="col-md-2 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            <i class="bi bi-search"></i>

                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <div class="row">


        {{-- Reviewer Database --}}
        <div class="col-xl-8">

            <div class="card shadow-sm border-0">

                <div
                    class="card-header bg-white
                           d-flex justify-content-between
                           align-items-center">

                    <h5 class="mb-0">
                        Available Reviewers
                    </h5>


                    <span class="badge bg-primary">

                        {{ $reviewers->total() }}

                    </span>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                            <tr>

                                <th class="ps-4">
                                    Reviewer
                                </th>

                                <th>
                                    Specialty
                                </th>

                                <th>
                                    Institution
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                            </thead>


                            <tbody>

                            @forelse($reviewers as $reviewer)

                                <tr>

                                    <td class="ps-4">

                                        <strong>

                                            {{ $reviewer->name }}

                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            {{ $reviewer->email }}

                                        </small>

                                    </td>


                                    <td>

                                        {{
                                            $reviewer
                                            ->profile
                                            ->specialty
                                            ?? 'N/A'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $reviewer
                                            ->profile
                                            ->institution
                                            ?? 'N/A'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            ucfirst(
                                                $reviewer
                                                ->profile
                                                ->reviewer_type
                                                ?? 'N/A'
                                            )
                                        }}

                                    </td>


                                    <td>

                                        @if($reviewer->status === 'approved')

                                            <span class="badge bg-success">

                                                Approved

                                            </span>

                                        @else

                                            <span class="badge bg-secondary">

                                                {{
                                                    ucfirst(
                                                        $reviewer->status
                                                    )
                                                }}

                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if($reviewer->status === 'approved')

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'handling-editor.reviewers.invite',
                                                    [
                                                        $manuscript,
                                                        $reviewer
                                                    ]
                                                ) }}">

                                                @csrf


                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-primary"
                                                    onclick="return confirm(
                                                        'Send reviewer invitation?'
                                                    )">

                                                    <i class="bi bi-envelope"></i>

                                                    Invite

                                                </button>

                                            </form>

                                        @else

                                            <button
                                                class="btn btn-sm btn-secondary"
                                                disabled>

                                                Not Available

                                            </button>

                                        @endif

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td
                                        colspan="6"
                                        class="text-center py-5 text-muted">

                                        No reviewers found.

                                    </td>

                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                @if($reviewers->hasPages())

                    <div class="card-footer bg-white">

                        {{
                            $reviewers
                            ->withQueryString()
                            ->links()
                        }}

                    </div>

                @endif

            </div>

        </div>


        {{-- Selected / Invited Reviewers --}}
        <div class="col-xl-4">

            <div
                class="card shadow-sm border-0"
                style="position: sticky; top: 20px;">

                <div class="card-header bg-primary text-white">

                    <h5 class="mb-0">

                        <i class="bi bi-people"></i>

                        Selected Reviewers

                    </h5>

                </div>


                <div class="card-body">

                    @if(
                        isset($invitations)
                        &&
                        $invitations->count()
                    )

                        @foreach($invitations as $invitation)

                            <div class="border rounded p-3 mb-3">

                                <div class="fw-semibold">

                                    {{
                                        $invitation
                                        ->reviewer
                                        ->name
                                        ?? 'Reviewer'
                                    }}

                                </div>


                                <small class="text-muted">

                                    {{
                                        $invitation
                                        ->reviewer
                                        ->email
                                        ?? ''
                                    }}

                                </small>


                                <div class="mt-2">

                                    @switch($invitation->status)

                                        @case('pending')

                                            <span class="badge bg-warning text-dark">
                                                Invitation Pending
                                            </span>

                                            @break


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


                                        @default

                                            <span class="badge bg-secondary">

                                                {{
                                                    ucfirst(
                                                        $invitation->status
                                                    )
                                                }}

                                            </span>

                                    @endswitch

                                </div>

                            </div>

                        @endforeach


                        <hr>


                        <div class="small text-muted">

                            Recommended reviewer assignment:
                            approximately 2–3 reviewers according
                            to journal policy.

                        </div>

                    @else

                        <div class="text-center py-4">

                            <i class="bi bi-person-plus fs-1 text-muted"></i>

                            <p class="text-muted mt-2 mb-0">

                                No reviewers selected yet.

                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection