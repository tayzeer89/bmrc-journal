@extends('admin.layouts.app')

@section('title', 'Handling Editor Assignment Tracking')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="fas fa-tasks me-2"></i>
                Handling Editor Assignment Tracking
            </h4>

            <p class="text-muted mb-0">
                Monitor manuscripts assigned to Handling Editors
            </p>

        </div>

        <a
            href="{{ route('eic.editor-assignment.index') }}"
            class="btn btn-primary">

            <i class="fas fa-user-plus me-1"></i>
            Assignment Queue

        </a>

    </div>


    {{-- Filter --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('eic.editor-assignment.tracking') }}">

                <div class="row g-3">


                    {{-- Search --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Search Manuscript
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Manuscript ID or title">

                    </div>


                    {{-- Editor --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Handling Editor
                        </label>

                        <select
                            name="editor_id"
                            class="form-select">

                            <option value="">
                                All Handling Editors
                            </option>

                            @foreach($editors as $editor)

                                <option
                                    value="{{ $editor->id }}"
                                    {{ request('editor_id') == $editor->id
                                        ? 'selected'
                                        : ''
                                    }}>

                                    {{ $editor->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Assignment Status
                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option value="">
                                All Statuses
                            </option>

                            <option
                                value="pending"
                                {{ request('status') === 'pending'
                                    ? 'selected'
                                    : ''
                                }}>
                                Pending
                            </option>

                            <option
                                value="accepted"
                                {{ request('status') === 'accepted'
                                    ? 'selected'
                                    : ''
                                }}>
                                Accepted
                            </option>

                            <option
                                value="declined"
                                {{ request('status') === 'declined'
                                    ? 'selected'
                                    : ''
                                }}>
                                Declined
                            </option>

                            <option
                                value="completed"
                                {{ request('status') === 'completed'
                                    ? 'selected'
                                    : ''
                                }}>
                                Completed
                            </option>

                            <option
                                value="cancelled"
                                {{ request('status') === 'cancelled'
                                    ? 'selected'
                                    : ''
                                }}>
                                Cancelled
                            </option>

                        </select>

                    </div>


                    <div class="col-md-2 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="fas fa-search"></i>
                            Filter

                        </button>

                        <a
                            href="{{ route('eic.editor-assignment.tracking') }}"
                            class="btn btn-outline-secondary">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Assignment Table --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Assigned Manuscripts
                </h5>

                <span class="badge bg-primary">
                    {{ $assignments->total() }} Assignment(s)
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Manuscript</th>
                            <th>Handling Editor</th>
                            <th>Assigned</th>
                            <th>Deadline</th>
                            <th>Assignment Status</th>
                            <th>Current Stage</th>
                            <th width="100">Action</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse($assignments as $assignment)

                            @php

                                $manuscript =
                                    $assignment->manuscript;

                            @endphp

                            <tr>

                                {{-- Serial --}}
                                <td>
                                    {{
                                        $assignments->firstItem()
                                        + $loop->index
                                    }}
                                </td>


                                {{-- Manuscript --}}
                                <td>

                                    <strong>
                                        {{
                                            $manuscript->manuscript_id
                                            ?? 'N/A'
                                        }}
                                    </strong>

                                    <div class="small mt-1">

                                        {{
                                            \Illuminate\Support\Str::limit(
                                                $manuscript->title
                                                ?? 'Untitled',
                                                70
                                            )
                                        }}

                                    </div>

                                    @if($manuscript->articleType)

                                        <div class="small text-muted mt-1">

                                            {{
                                                $manuscript
                                                    ->articleType
                                                    ->name
                                            }}

                                        </div>

                                    @endif

                                </td>


                                {{-- Handling Editor --}}
                                <td>

                                    <i class="fas fa-user-edit me-1 text-primary"></i>

                                    <strong>
                                        {{
                                            $assignment
                                                ->editor
                                                ->name
                                            ?? 'N/A'
                                        }}
                                    </strong>

                                </td>


                                {{-- Assigned --}}
                                <td>

                                    @if($assignment->assigned_at)

                                        {{
                                            $assignment
                                                ->assigned_at
                                                ->format('d M Y')
                                        }}

                                        <div class="small text-muted">

                                            {{
                                                $assignment
                                                    ->assigned_at
                                                    ->format('h:i A')
                                            }}

                                        </div>

                                    @else

                                        N/A

                                    @endif

                                </td>


                                {{-- Deadline --}}
                                <td>

                                    @if($assignment->due_date)

                                        {{
                                            $assignment
                                                ->due_date
                                                ->format('d M Y')
                                        }}

                                        @if(
                                            $assignment->due_date->isPast()
                                            &&
                                            !in_array(
                                                $assignment->status,
                                                [
                                                    'completed',
                                                    'cancelled',
                                                    'declined'
                                                ]
                                            )
                                        )

                                            <div class="mt-1">

                                                <span class="badge bg-danger">
                                                    Overdue
                                                </span>

                                            </div>

                                        @endif

                                    @else

                                        <span class="text-muted">
                                            No deadline
                                        </span>

                                    @endif

                                </td>


                                {{-- Assignment Status --}}
                                <td>

                                    @switch($assignment->status)

                                        @case('pending')

                                            <span class="badge bg-warning text-dark">
                                                Pending Acceptance
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


                                        @case('completed')

                                            <span class="badge bg-primary">
                                                Completed
                                            </span>

                                            @break


                                        @case('cancelled')

                                            <span class="badge bg-secondary">
                                                Cancelled
                                            </span>

                                            @break


                                        @case('reassigned')

                                            <span class="badge bg-info text-dark">
                                                Reassigned
                                            </span>

                                            @break


                                        @default

                                            <span class="badge bg-secondary">

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

                                    @endswitch

                                </td>


                                {{-- Current Manuscript Stage --}}
                                <td>

                                    @php

                                        $stage =
                                            $manuscript->current_stage
                                            ??
                                            $manuscript->status
                                            ??
                                            'N/A';

                                    @endphp

                                    <span class="badge bg-light text-dark border">

                                        {{
                                            ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $stage
                                                )
                                            )
                                        }}

                                    </span>

                                </td>


                                {{-- Action --}}
                                <td>

                                    <a
                                        href="{{ route(
                                            'eic.editor-assignment.tracking.show',
                                            $assignment->id
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        <i class="bi bi-eye"></i>
                                        View
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5">

                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>

                                    <h6>
                                        No assignments found
                                    </h6>

                                    <p class="text-muted mb-0">
                                        No manuscript assignments match the selected filters.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($assignments->hasPages())

            <div class="card-footer bg-white">

                {{ $assignments->links() }}

            </div>

        @endif

    </div>

</div>

@endsection