@extends('admin.layouts.app')

@section('title', 'Request New Reviewer')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Request New Reviewer</h4>

            <p class="text-muted mb-0">
                Submit a request to add a new reviewer to the reviewer pool.
            </p>
        </div>

        <a
            href="{{ route('admin.reviewers.requests.index') }}"
            class="btn btn-outline-secondary"
        >
            Back to Requests
        </a>

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
            <strong>Reviewer Request Information</strong>
        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.reviewers.requests.store') }}"
            >

                @csrf


                <div class="row g-3">

                    {{-- Reviewer Name --}}
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
                            value="{{ old('name') }}"
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
                            Reviewer Email
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            required
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Institution --}}
                    <div class="col-md-6">

                        <label
                            for="institution"
                            class="form-label"
                        >
                            Institution
                        </label>

                        <input
                            type="text"
                            id="institution"
                            name="institution"
                            value="{{ old('institution') }}"
                            class="form-control @error('institution') is-invalid @enderror"
                        >

                        @error('institution')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Designation --}}
                    <div class="col-md-6">

                        <label
                            for="designation"
                            class="form-label"
                        >
                            Designation
                        </label>

                        <input
                            type="text"
                            id="designation"
                            name="designation"
                            value="{{ old('designation') }}"
                            class="form-control @error('designation') is-invalid @enderror"
                        >

                        @error('designation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Specialization --}}
                    <div class="col-md-12">

                        <label
                            for="specialization"
                            class="form-label"
                        >
                            Specialization / Expertise
                        </label>

                        <input
                            type="text"
                            id="specialization"
                            name="specialization"
                            value="{{ old('specialization') }}"
                            class="form-control @error('specialization') is-invalid @enderror"
                            placeholder="Example: Public Health, Epidemiology, Neurology"
                        >

                        @error('specialization')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Reason --}}
                    <div class="col-md-12">

                        <label
                            for="reason"
                            class="form-label"
                        >
                            Reason for Request
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            id="reason"
                            name="reason"
                            rows="4"
                            class="form-control @error('reason') is-invalid @enderror"
                            required
                        >{{ old('reason') }}</textarea>

                        @error('reason')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                <hr class="my-4">


                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('admin.reviewers.requests.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Submit Reviewer Request
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection