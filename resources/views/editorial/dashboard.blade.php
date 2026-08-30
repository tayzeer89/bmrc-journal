@extends('admin.layouts.app')

@section('title', 'Editorial Dashboard')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="mb-4">

        <h2 class="fw-bold">
            Editorial Dashboard
        </h2>

        <p class="text-muted mb-0">
            Welcome, {{ auth()->user()->name }}
        </p>

    </div>


    {{-- Dashboard Cards --}}
    <div class="row g-4">


        {{-- Manuscripts --}}
        @can('manuscript.view')

        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Manuscripts
                    </h5>

                    <p class="text-muted">
                        Review submitted manuscripts,
                        track status and workflow.
                    </p>

                    <a href="#"
                       class="btn btn-primary">
                        View Manuscripts
                    </a>

                </div>

            </div>

        </div>

        @endcan



        {{-- Reviewer Management --}}
        @can('reviewer.view')

        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Reviewers
                    </h5>

                    <p class="text-muted">
                        Manage reviewers,
                        assignments and evaluations.
                    </p>

                    <a href="#"
                       class="btn btn-primary">
                        Manage Reviewers
                    </a>

                </div>

            </div>

        </div>

        @endcan



        {{-- Review Assignment --}}
        @can('review.assignment')

        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Review Assignment
                    </h5>

                    <p class="text-muted">
                        Assign manuscripts to
                        appropriate reviewers.
                    </p>

                    <a href="#"
                       class="btn btn-primary">
                        Assign Reviewer
                    </a>

                </div>

            </div>

        </div>

        @endcan



        {{-- Editorial Decision --}}
        @can('editorial.decision')

        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Editorial Decision
                    </h5>

                    <p class="text-muted">
                        Accept, revise or reject
                        submitted manuscripts.
                    </p>

                    <a href="#"
                       class="btn btn-primary">
                        Make Decision
                    </a>

                </div>

            </div>

        </div>

        @endcan



        {{-- Publication --}}
        @can('publication.manage')

        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Publication
                    </h5>

                    <p class="text-muted">
                        Manage issue creation,
                        articles and publishing.
                    </p>

                    <a href="#"
                       class="btn btn-primary">
                        Manage Publication
                    </a>

                </div>

            </div>

        </div>

        @endcan



        {{-- Editorial Reports --}}
        @can('editorial.report')

        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Reports
                    </h5>

                    <p class="text-muted">
                        Editorial statistics,
                        review performance and reports.
                    </p>

                    <a href="#"
                       class="btn btn-outline-primary">
                        View Reports
                    </a>

                </div>

            </div>

        </div>

        @endcan


    </div>

</div>

@endsection