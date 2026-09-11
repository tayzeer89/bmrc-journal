@extends('reviewer.layouts.app')

@section('title', 'Review History | BMRC Journal')

@section('content')

<div class="page-header">

    <h1>
        Review History
    </h1>

    <p>
        Your complete BMRC manuscript reviewing history.
    </p>

</div>


<div class="reviewer-card">

    <div class="card-header">

        <i class="bi bi-clock-history me-1"></i>

        Review History

    </div>


    <div class="card-body">

        @if($reviews->isEmpty())

            <div class="text-center py-5">

                <i
                    class="bi bi-clock-history text-muted"
                    style="font-size:45px;"
                ></i>

                <h5 class="mt-3">
                    No Review History
                </h5>

                <p class="text-muted mb-0">
                    Your review history will appear here after you begin reviewing manuscripts.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection