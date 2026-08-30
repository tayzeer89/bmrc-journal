@extends('author.layouts.app')


@section('content')


<div class="container-fluid py-4">


<div class="card shadow-sm">


<div class="card-header bg-success text-white">

<h4 class="mb-0">

Submission Confirmation

</h4>

</div>



<div class="card-body">


<div class="alert alert-success">

<i class="bi bi-check-circle"></i>

Your manuscript submission process is completed.

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

<span class="badge bg-primary">

Submitted

</span>

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

BMRC editorial office will perform technical checking of your manuscript.


</div>




<a href="{{route('author.dashboard')}}"

class="btn btn-primary">

Go to Dashboard

</a>



</div>


</div>


</div>


@endsection