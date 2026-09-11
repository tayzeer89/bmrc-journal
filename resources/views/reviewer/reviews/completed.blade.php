@extends('reviewer.layouts.app')

@section('title', 'Completed Reviews | BMRC Journal')

@section('content')

<div class="page-header">

    <h1>
        Completed Reviews
    </h1>

    <p>
        Reviews that you have completed and submitted.
    </p>

</div>


<div class="reviewer-card">

    <div class="card-header">

        <i class="bi bi-check2-circle me-1"></i>

        Completed Reviews

    </div>


    <div class="card-body">

        @if($reviews->isEmpty())

            <div class="text-center py-5">

                <i
                    class="bi bi-check-circle text-muted"
                    style="font-size:45px;"
                ></i>

                <h5 class="mt-3">
                    No Completed Reviews
                </h5>

                <p class="text-muted mb-0">
                    Completed review records will appear here.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection