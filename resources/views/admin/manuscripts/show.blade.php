@extends('admin.layouts.app')

@section('title', 'Manuscript Details')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between
                align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                Manuscript Details

            </h2>

            <p class="text-muted mb-0">

                {{ $manuscript->manuscript_no ?? 'Manuscript #'.$manuscript->id }}

            </p>

        </div>


        <a href="{{ route('admin.manuscripts.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left"></i>

            Back

        </a>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-8">

                    <small class="text-muted">

                        Title

                    </small>

                    <h4 class="fw-bold">

                        {{ $manuscript->title }}

                    </h4>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">

                        Status

                    </small>

                    <div>

                        <span class="badge bg-primary">

                            {{ ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $manuscript->status
                                )
                            ) }}

                        </span>

                    </div>

                </div>


                <div class="col-md-6">

                    <small class="text-muted">

                        Article Type

                    </small>

                    <div class="fw-semibold">

                        {{ $manuscript->article_type ?? '—' }}

                    </div>

                </div>


                <div class="col-md-6">

                    <small class="text-muted">

                        Submitted Date

                    </small>

                    <div class="fw-semibold">

                        {{ optional(
                            $manuscript->submitted_at
                        )->format('d M Y') ?? '—' }}

                    </div>

                </div>

            </div>

        </div>


        <div class="card-footer bg-white">

            @can('technical_check.view')

                <a href="{{ route(
                    'admin.manuscripts.technical-check',
                    $manuscript
                ) }}"
                   class="btn btn-primary">

                    <i class="bi bi-clipboard-check me-1"></i>

                    Technical Check

                </a>

            @endcan

        </div>

    </div>

</div>

@endsection