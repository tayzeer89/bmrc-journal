@extends('reviewer.layouts.app')

@section('title', 'Active Reviews | BMRC Journal')

@section('content')

<div class="page-header">

    <h1>
        Active Reviews
    </h1>

    <p>
        Manuscripts currently assigned to you for peer review.
    </p>

</div>


<div class="reviewer-card">

    <div class="card-header">

        <i class="bi bi-hourglass-split me-1"></i>

        Active Review Assignments

    </div>


    <div class="card-body">

        @if($reviews->isEmpty())

            <div class="text-center py-5">

                <i
                    class="bi bi-journal-text text-muted"
                    style="font-size:45px;"
                ></i>

                <h5 class="mt-3">
                    No Active Reviews
                </h5>

                <p class="text-muted mb-0">
                    You do not currently have any active manuscript reviews.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection