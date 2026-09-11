@extends('admin.layouts.app')

@section('title', 'Edit Reviewer')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Edit Reviewer
            </h4>

            <p class="text-muted mb-0">
                Update reviewer basic account information.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.reviewers.show', $reviewer) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="card">

        <div class="card-header">

            <strong>
                Basic Reviewer Information
            </strong>

        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.reviewers.update', $reviewer) }}"
            >

                @csrf
                @method('PUT')


                <div class="row g-3">

                    {{-- Name --}}
                    <div class="col-md-6">

                        <label
                            for="name"
                            class="form-label"
                        >
                            Reviewer Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $reviewer->name) }}"
                            class="form-control @error('name') is-invalid @enderror"
                            required
                        >

                        @error('name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Email --}}
                    <div class="col-md-6">

                        <label
                            for="email"
                            class="form-label"
                        >
                            Email Address
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $reviewer->email) }}"
                            class="form-control @error('email') is-invalid @enderror"
                            required
                        >

                        @error('email')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Account Status --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Account Status
                        </label>

                        <div>

                            @if($reviewer->status === 'approved')

                                <span class="badge bg-success">
                                    Approved
                                </span>

                            @elseif($reviewer->status === 'pending')

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @elseif($reviewer->status === 'rejected')

                                <span class="badge bg-danger">
                                    Rejected
                                </span>

                            @elseif($reviewer->status === 'suspended')

                                <span class="badge bg-dark">
                                    Suspended
                                </span>

                            @else

                                <span class="badge bg-secondary">

                                    {{
                                        ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $reviewer->status ?? 'Unknown'
                                            )
                                        )
                                    }}

                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Profile Status --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Profile Status
                        </label>

                        <div>

                            @if($reviewer->profile)

                                <span class="badge bg-info text-dark">

                                    {{
                                        ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $reviewer->profile->status ?? 'draft'
                                            )
                                        )
                                    }}

                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    No Profile
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Profile Completion --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Profile Completion
                        </label>

                        <div>

                            @if($reviewer->profile?->profile_completed)

                                <span class="badge bg-success">
                                    Completed
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    Incomplete
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Reviewer Code --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Reviewer Code
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $reviewer->profile?->reviewer_code ?? 'Not Generated' }}"
                            disabled
                        >

                    </div>


                    {{-- Created Source --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Created Source
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{
                                ucfirst(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $reviewer->created_source ?? 'Unknown'
                                    )
                                )
                            }}"
                            disabled
                        >

                    </div>


                    {{-- Created Date --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Created Date
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $reviewer->created_at?->format('d M Y h:i A') ?? '—' }}"
                            disabled
                        >

                    </div>

                </div>


                <hr class="my-4">


                <div class="alert alert-info">

                    <strong>Note:</strong>

                    The Editorial Officer should edit only the reviewer's
                    basic account information here.

                    Professional profile information should normally be
                    completed and updated by the reviewer through the
                    reviewer portal.

                </div>


                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('admin.reviewers.show', $reviewer) }}"
                        class="btn btn-outline-secondary"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Update Reviewer
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection