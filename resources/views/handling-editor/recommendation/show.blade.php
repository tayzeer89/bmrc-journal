@extends('admin.layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Editorial Recommendation
            </h3>

            <p class="text-muted mb-0">

                {{ $manuscript->manuscript_id }}
                —
                {{ $manuscript->title }}

            </p>

        </div>


        <a
            href="{{ route('handling-editor.assignments.index') }}"
            class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left"></i>

            Back

        </a>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
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


    <div class="row">

        {{-- LEFT --}}
        <div class="col-xl-7">


            {{-- Manuscript --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Manuscript Information
                    </h5>

                </div>


                <div class="card-body">

                    <table class="table table-bordered mb-0">

                        <tr>
                            <th width="30%">
                                Manuscript ID
                            </th>

                            <td>
                                {{ $manuscript->manuscript_id }}
                            </td>
                        </tr>


                        <tr>
                            <th>
                                Title
                            </th>

                            <td>
                                {{ $manuscript->title }}
                            </td>
                        </tr>


                        <tr>
                            <th>
                                Article Type
                            </th>

                            <td>
                                {{ $manuscript->articleType->name ?? 'N/A' }}
                            </td>
                        </tr>


                        <tr>
                            <th>
                                Review Round
                            </th>

                            <td>
                                {{ $manuscript->review_round ?? 1 }}
                            </td>
                        </tr>

                    </table>

                </div>

            </div>


            {{-- Reviewer Reports --}}
            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Reviewer Reports
                    </h5>

                </div>


                <div class="card-body">

                    @if(
                        isset($reviewReports)
                        &&
                        $reviewReports->count()
                    )

                        @foreach($reviewReports as $report)

                            <div class="border rounded p-3 mb-3">

                                <div
                                    class="d-flex justify-content-between
                                           align-items-center mb-3">

                                    <strong>

                                        Reviewer {{ $loop->iteration }}

                                    </strong>


                                    <span class="badge bg-secondary">

                                        {{
                                            ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $report->recommendation
                                                )
                                            )
                                        }}

                                    </span>

                                </div>


                                @if($report->comments)

                                    <div>

                                        {!! nl2br(e($report->comments)) !!}

                                    </div>

                                @endif

                            </div>

                        @endforeach

                    @else

                        <div class="alert alert-warning mb-0">

                            Reviewer reports are not available.

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- RIGHT --}}
        <div class="col-xl-5">

            <div
                class="card shadow-sm border-0"
                style="position: sticky; top: 20px;">

                <div class="card-header bg-primary text-white">

                    <h5 class="mb-0">
                        Submit Recommendation
                    </h5>

                </div>


                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ route(
                            'handling-editor.recommendation.store',
                            $manuscript
                        ) }}">

                        @csrf


                        {{-- Recommendation --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                Recommendation

                                <span class="text-danger">*</span>

                            </label>


                            <select
                                name="recommendation"
                                class="form-select"
                                required>

                                <option value="">
                                    -- Select Recommendation --
                                </option>


                                <option
                                    value="accept"
                                    @selected(
                                        old('recommendation')
                                        ===
                                        'accept'
                                    )>

                                    Recommend Acceptance

                                </option>


                                <option
                                    value="minor_revision"
                                    @selected(
                                        old('recommendation')
                                        ===
                                        'minor_revision'
                                    )>

                                    Minor Revision

                                </option>


                                <option
                                    value="major_revision"
                                    @selected(
                                        old('recommendation')
                                        ===
                                        'major_revision'
                                    )>

                                    Major Revision

                                </option>


                                <option
                                    value="reject"
                                    @selected(
                                        old('recommendation')
                                        ===
                                        'reject'
                                    )>

                                    Recommend Rejection

                                </option>

                            </select>

                        </div>


                        {{-- Re-review --}}
                        <div class="mb-4">

                            <div class="form-check">

                                <input
                                    type="checkbox"
                                    name="re_review_required"
                                    value="1"
                                    id="re_review_required"
                                    class="form-check-input"
                                    @checked(
                                        old('re_review_required')
                                    )>

                                <label
                                    for="re_review_required"
                                    class="form-check-label">

                                    Reviewer re-review required
                                    after revision

                                </label>

                            </div>

                        </div>


                        {{-- Comments --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                Editorial Recommendation Comments

                                <span class="text-danger">*</span>

                            </label>


                            <textarea
                                name="comments"
                                rows="8"
                                required
                                class="form-control"
                                placeholder="Provide the reason and editorial recommendation...">{{ old('comments') }}</textarea>

                        </div>


                        <div class="alert alert-warning">

                            <strong>
                                Editorial Authority
                            </strong>

                            <br>

                            This is a Handling Editor recommendation.
                            The final journal decision will be made
                            by the Editor-in-Chief.

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            <i class="bi bi-send"></i>

                            Submit Recommendation to EIC

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection