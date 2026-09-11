@extends('reviewer.layouts.app')

@section('title', 'Review Invitations | BMRC Journal')

@section('content')

<div class="page-header">

    <h1>
        Review Invitations
    </h1>

    <p>
        View and respond to manuscript review invitations.
    </p>

</div>


<div class="reviewer-card">

    <div class="card-header">

        <i class="bi bi-envelope me-1"></i>

        Review Invitations

    </div>


    <div class="card-body">

        @if($invitations->isEmpty())

            <div class="text-center py-5">

                <i
                    class="bi bi-envelope-open text-muted"
                    style="font-size:45px;"
                ></i>

                <h5 class="mt-3">
                    No Review Invitations
                </h5>

                <p class="text-muted mb-0">
                    You currently have no manuscript review invitations.
                </p>

            </div>

        @else

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>

                        <tr>

                            <th>
                                Manuscript
                            </th>

                            <th>
                                Invited
                            </th>

                            <th>
                                Deadline
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($invitations as $invitation)

                            <tr>

                                <td>
                                    {{ $invitation->manuscript_id }}
                                </td>

                                <td>
                                    —
                                </td>

                                <td>
                                    —
                                </td>

                                <td>
                                    Pending
                                </td>

                                <td>
                                    —
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

</div>

@endsection