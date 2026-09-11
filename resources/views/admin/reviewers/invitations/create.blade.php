@extends('admin.layouts.app')

@section('title', 'Send Reviewer Invitation')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Send Reviewer Invitation</h4>

            <p class="text-muted mb-0">
                Invite an approved reviewer to review a manuscript.
            </p>
        </div>

        <a
            href="{{ route('admin.reviewer-invitations.index') }}"
            class="btn btn-outline-secondary"
        >
            Back to Invitations
        </a>

    </div>

    <div class="card">

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.reviewer-invitations.store') }}"
            >
                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Reviewer ID
                    </label>

                    <input
                        type="number"
                        name="reviewer_id"
                        class="form-control"
                        value="{{ old('reviewer_id') }}"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Manuscript ID
                    </label>

                    <input
                        type="number"
                        name="manuscript_id"
                        class="form-control"
                        value="{{ old('manuscript_id') }}"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Invitation Message
                    </label>

                    <textarea
                        name="message"
                        class="form-control"
                        rows="5"
                    >{{ old('message') }}</textarea>

                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Send Invitation
                </button>

            </form>

        </div>

    </div>

</div>

@endsection