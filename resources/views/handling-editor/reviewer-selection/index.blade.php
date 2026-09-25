@extends('admin.layouts.app')

@section('title', 'Reviewer Selection')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-people"></i>
                Reviewer Selection
            </h4>

            <p class="text-muted mb-0">
                Select reviewers for manuscripts approved for peer review.
            </p>
        </div>

    </div>


    {{-- Search / Filter --}}
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('handling-editor.reviewer-selection.index') }}"
            >

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Search Manuscript
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Manuscript ID or title"
                        >

                    </div>


                    <div class="col-md-3">

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
                                value="reviewer_selection"
                                @selected(
                                    request('status')
                                    === 'reviewer_selection'
                                )
                            >
                                Reviewer Selection
                            </option>

                            <option
                                value="reviewer_invitation"
                                @selected(
                                    request('status')
                                    === 'reviewer_invitation'
                                )
                            >
                                Reviewer Invitation
                            </option>

                        </select>

                    </div>


                    <div class="col-md-3 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-search"></i>
                            Search
                        </button>

                        <a
                            href="{{ route(
                                'handling-editor.reviewer-selection.index'
                            ) }}"
                            class="btn btn-outline-secondary"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Manuscript List --}}
    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <strong>
                Manuscripts Ready for Reviewer Selection
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Manuscript</th>
                            <th>Article Type</th>
                            <th>Journal</th>
                            <th>Status</th>
                            <th width="150">Action</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse($manuscripts as $manuscript)

                            <tr>

                                <td>
                                    {{ $manuscripts->firstItem() + $loop->index }}
                                </td>


                                <td>

                                    <strong>
                                        {{ $manuscript->manuscript_id }}
                                    </strong>

                                    <div class="small text-muted mt-1">
                                        {{ \Illuminate\Support\Str::limit(
                                            strip_tags($manuscript->title),
                                            80
                                        ) }}
                                    </div>

                                </td>


                                <td>

                                    {{ $manuscript->articleType->name
                                        ?? 'N/A' }}

                                </td>


                                <td>

                                    {{ $manuscript->journal->name
                                        ?? 'N/A' }}

                                </td>


                                <td>

                                    @if(
                                        $manuscript->status
                                        === 'reviewer_selection'
                                    )

                                        <span class="badge bg-primary">
                                            Reviewer Selection
                                        </span>

                                    @elseif(
                                        $manuscript->status
                                        === 'reviewer_invitation'
                                    )

                                        <span class="badge bg-warning text-dark">
                                            Invitation in Progress
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $manuscript->status
                                                )
                                            ) }}
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    {{-- Add route in Step 2 --}}

                                  <a
                                    href="{{ route(
                                        'handling-editor.reviewer-selection.show',
                                        $manuscript->id
                                    ) }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    <i class="bi bi-person-plus"></i>
                                    Select Reviewers
                                </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-5 text-muted"
                                >

                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>

                                    No manuscripts are currently available
                                    for reviewer selection.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($manuscripts->hasPages())

            <div class="card-footer bg-white">

                {{ $manuscripts->links() }}

            </div>

        @endif

    </div>

</div>

@endsection