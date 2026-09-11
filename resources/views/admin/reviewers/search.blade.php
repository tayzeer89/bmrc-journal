@extends('admin.layouts.app')

@section('title', 'Search Reviewer')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Search Reviewer
            </h4>

            <p class="text-muted mb-0">
                Find suitable approved reviewers by expertise,
                professional information and location.
            </p>

        </div>


        <a
            href="{{ route('admin.reviewers.index') }}"
            class="btn btn-outline-secondary"
        >
            Reviewer Pool
        </a>

    </div>


    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Reviewer Search Criteria
            </strong>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.reviewers.search') }}"
            >

                {{-- General Search --}}

                <div class="mb-4">

                    <label class="form-label">
                        Keyword
                    </label>

                    <input
                        type="text"
                        name="keyword"
                        value="{{ request('keyword') }}"
                        class="form-control"
                        placeholder="Reviewer name, expertise, institution..."
                    >

                </div>


                {{-- Professional --}}

                <div class="border rounded p-3 mb-3">

                    <h6>
                        Professional Information
                    </h6>

                    <div class="row g-3">

                        <div class="col-lg-3">

                            <label class="form-label">
                                Designation
                            </label>

                            <select
                                name="designation"
                                class="form-select"
                            >

                                <option value="">
                                    All Designations
                                </option>

                                @foreach($designations as $designation)

                                    <option
                                        value="{{ $designation }}"
                                        @selected(
                                            request('designation')
                                            === $designation
                                        )
                                    >
                                        {{ $designation }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-lg-3">

                            <label class="form-label">
                                Department
                            </label>

                            <select
                                name="department"
                                class="form-select"
                            >

                                <option value="">
                                    All Departments
                                </option>

                                @foreach($departments as $department)

                                    <option
                                        value="{{ $department }}"
                                        @selected(
                                            request('department')
                                            === $department
                                        )
                                    >
                                        {{ $department }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-lg-3">

                            <label class="form-label">
                                Institution
                            </label>

                            <select
                                name="institution"
                                class="form-select"
                            >

                                <option value="">
                                    All Institutions
                                </option>

                                @foreach($institutions as $institution)

                                    <option
                                        value="{{ $institution }}"
                                        @selected(
                                            request('institution')
                                            === $institution
                                        )
                                    >
                                        {{ $institution }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-lg-3">

                            <label class="form-label">
                                Qualification
                            </label>

                            <select
                                name="qualification"
                                class="form-select"
                            >

                                <option value="">
                                    All Qualifications
                                </option>

                                @foreach($qualifications as $qualification)

                                    <option
                                        value="{{ $qualification }}"
                                        @selected(
                                            request('qualification')
                                            === $qualification
                                        )
                                    >
                                        {{ $qualification }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                </div>


                {{-- Expertise --}}

                <div class="border rounded p-3 mb-3">

                    <h6>
                        Expertise & Research
                    </h6>

                    <div class="row g-3">

                        <div class="col-lg-6">

                            <label class="form-label">
                                Specialization
                            </label>

                            <select
                                name="specialization"
                                class="form-select"
                            >

                                <option value="">
                                    All Specializations
                                </option>

                                @foreach(
                                    $specializations
                                    as $specialization
                                )

                                    <option
                                        value="{{ $specialization }}"
                                        @selected(
                                            request('specialization')
                                            === $specialization
                                        )
                                    >
                                        {{ $specialization }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-lg-6">

                            <label class="form-label">
                                Research Interest
                            </label>

                            <select
                                name="research_interest"
                                class="form-select"
                            >

                                <option value="">
                                    All Research Interests
                                </option>

                                @foreach(
                                    $researchInterests
                                    as $interest
                                )

                                    <option
                                        value="{{ $interest }}"
                                        @selected(
                                            request('research_interest')
                                            === $interest
                                        )
                                    >
                                        {{ $interest }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                </div>


                {{-- Location --}}

                <div class="border rounded p-3 mb-3">

                    <h6>
                        Location
                    </h6>

                    <div class="row g-3">

                        <div class="col-lg-4">

                            <label class="form-label">
                                Country
                            </label>

                            <select
                                name="country"
                                class="form-select"
                                onchange="this.form.submit()"
                            >

                                <option value="">
                                    All Countries
                                </option>

                                @foreach($countries as $country)

                                    <option
                                        value="{{ $country }}"
                                        @selected(
                                            request('country')
                                            === $country
                                        )
                                    >
                                        {{ $country }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-lg-4">

                            <label class="form-label">
                                Division
                            </label>

                            <select
                                name="division"
                                class="form-select"
                                onchange="this.form.submit()"
                            >

                                <option value="">
                                    All Divisions
                                </option>

                                @foreach($divisions as $division)

                                    <option
                                        value="{{ $division }}"
                                        @selected(
                                            request('division')
                                            === $division
                                        )
                                    >
                                        {{ $division }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-lg-4">

                            <label class="form-label">
                                District
                            </label>

                            <select
                                name="district"
                                class="form-select"
                            >

                                <option value="">
                                    All Districts
                                </option>

                                @foreach($districts as $district)

                                    <option
                                        value="{{ $district }}"
                                        @selected(
                                            request('district')
                                            === $district
                                        )
                                    >
                                        {{ $district }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                </div>


                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('admin.reviewers.search') }}"
                        class="btn btn-outline-secondary"
                    >
                        Reset
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Search Suitable Reviewers
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- Search Results --}}

    <div class="card">

        <div class="card-header d-flex justify-content-between">

            <strong>
                Suitable Reviewers
            </strong>

            <span class="badge bg-primary">
                {{ $reviewers->total() }} Found
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>
                                Reviewer
                            </th>

                            <th>
                                Designation
                            </th>

                            <th>
                                Institution
                            </th>

                            <th>
                                Specialization
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Availability
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($reviewers as $reviewer)

                            <tr>

                                <td>

                                    <strong>
                                        {{ $reviewer->name }}
                                    </strong>

                                    <div class="small text-muted">
                                        {{ $reviewer->email }}
                                    </div>

                                </td>


                                <td>
                                    {{ $reviewer->profile?->designation ?? '—' }}
                                </td>


                                <td>
                                    {{ $reviewer->profile?->institution ?? '—' }}
                                </td>


                                <td>
                                    {{ $reviewer->profile?->specialization ?? '—' }}
                                </td>


                                <td>

                                    {{ $reviewer->profile?->district ?? '' }}

                                    @if($reviewer->profile?->division)

                                        <div class="small text-muted">
                                            {{ $reviewer->profile->division }}
                                        </div>

                                    @endif

                                </td>


                                <td>

                                    @if(
                                        $reviewer
                                            ->profile
                                            ?->available_for_review
                                    )

                                        <span class="badge bg-success">
                                            Available
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Unavailable
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.reviewers.show',
                                            $reviewer
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View Profile
                                    </a>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <strong>
                                        No suitable reviewers found.
                                    </strong>

                                    <div class="text-muted">
                                        Try changing your search criteria.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($reviewers->hasPages())

            <div class="card-footer">

                {{ $reviewers->links('pagination::bootstrap-4') }}

            </div>

        @endif

    </div>

</div>

@endsection