@extends('author.layouts.app')


@section('content')


<div class="container-fluid py-4">


<div class="card shadow-sm">


<div class="card-header bg-success text-white">

<h4 class="mb-0">

Submission Confirmation

</h4>

</div>



<table class="table table-bordered">


<tr>

<th width="30%">
Manuscript ID
</th>


<td>

{{ $manuscript->manuscript_id }}

</td>

</tr>



<tr>

<th>
Article Title
</th>


<td>

{{ $manuscript->title }}

</td>

</tr>



<tr>

<th>
Article Type
</th>


<td>

{{ 
$manuscript->articleType->name ?? 'N/A'
}}

</td>

</tr>



<tr>

<th>
Journal
</th>


<td>

{{ 
$manuscript->journal->name ?? 'N/A'
}}

</td>

</tr>



<tr>

<th>
Submission Date
</th>


<td>

{{ 
$manuscript->created_at->format('d M Y')
}}

</td>

</tr>



<tr>
    <th>
        Current Status
    </th>

    <td>

        @if($manuscript->status == 'draft')

            <span class="badge bg-warning">
                Draft
            </span>


        @elseif($manuscript->status == 'submitted')

            <span class="badge bg-success">
                Submitted
            </span>


        @elseif($manuscript->status == 'technical_check')

            <span class="badge bg-info">
                Technical Review
            </span>


        @elseif($manuscript->status == 'under_review')

            <span class="badge bg-primary">
                Under Review
            </span>


        @elseif($manuscript->status == 'accepted')

            <span class="badge bg-success">
                Accepted
            </span>


        @elseif($manuscript->status == 'rejected')

            <span class="badge bg-danger">
                Rejected
            </span>


        @endif

    </td>

</tr>



<tr>

<th>
Submission Version
</th>


<td>

V1.0

</td>

</tr>



<tr>

<th>
Payment Status
</th>


<td>

Pending

</td>

</tr>



<tr>

<th>
Technical Check Status
</th>


<td>

Pending

</td>

</tr>


</table>



<div class="alert alert-info">

<strong>Next Action:</strong>

<br>


@if($manuscript->status == 'submitted')

    BMRC editorial office will perform technical checking of your manuscript.


@elseif($manuscript->status == 'technical_check')

    Your manuscript is currently under technical checking.


@elseif($manuscript->status == 'under_review')

    Your manuscript has been assigned for peer review.


@elseif($manuscript->status == 'accepted')

    Your manuscript has been accepted for publication.


@elseif($manuscript->status == 'rejected')

    Your manuscript has been rejected.


@else

    Please complete your submission.

@endif


</div>




<form method="POST"
      action="{{ route('author.submission.finalSubmit',$manuscript->id) }}">

    @csrf


    <button type="submit"
            class="btn btn-success">

        <i class="bi bi-send"></i>

        Final Submit Manuscript

    </button>


</form>


</div>


</div>


</div>


@endsection