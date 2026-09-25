@extends('admin.layouts.app')

@section('title', 'Similarity Check')

@section('content')

<div class="container-fluid py-4">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">
            <h4 class="mb-1">
                Similarity Check
            </h4>

            <div class="text-muted">
                {{ $manuscript->manuscript_id }}
                —
                {{ $manuscript->title }}
            </div>
        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'admin.similarity-checks.store',
                    $manuscript
                ) }}"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Check Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $checkNumber }}"
                            readonly
                        >
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Similarity %
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            max="100"
                            name="similarity_percentage"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Threshold %
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="threshold_percentage"
                            class="form-control"
                            value="20"
                            required
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Software / Service
                        </label>

                        <input
                            type="text"
                            name="software_name"
                            class="form-control"
                            placeholder="e.g. Turnitin / iThenticate"
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Similarity Report
                        </label>

                        <input
                            type="file"
                            name="report_file"
                            class="form-control"
                            accept=".pdf"
                        >
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">
                            Decision
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >
                            <option value="">
                                Select Decision
                            </option>

                            <option value="passed">
                                Passed
                            </option>

                            <option value="review_required">
                                Review Required
                            </option>

                            <option value="returned_to_author">
                                Return to Author
                            </option>

                            <option value="escalated">
                                Escalate
                            </option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">
                            Comments
                        </label>

                        <textarea
                            name="comments"
                            rows="5"
                            class="form-control"
                        ></textarea>
                    </div>

                </div>

                <div class="text-end">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Save Similarity Check
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

@endsection