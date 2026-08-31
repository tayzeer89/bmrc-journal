@extends('author.layouts.app')


@section('title', 'Submitted Manuscript Details')


@section('content')


<div class="container-fluid py-4">


<div class="card shadow-sm">


<div class="card-header bg-success text-white">

<h4 class="mb-0">

Submitted Manuscript Details

</h4>

</div>



<div class="card-body">


{{-- Success Message --}}

@if(session('success'))

<div class="alert alert-success">

<i class="bi bi-check-circle"></i>

{{ session('success') }}

</div>

@endif



{{-- Manuscript Information --}}

<h5 class="mb-3">

Manuscript Information

</h5>



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
Title
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

{{ $manuscript->articleType->name ?? 'N/A' }}

</td>

</tr>



<tr>

<th>
Journal
</th>


<td>

{{ $manuscript->journal->name ?? 'N/A' }}

</td>

</tr>



<tr>

<th>
Submission Date
</th>


<td>

{{ $manuscript->submitted_at?->format('d M Y') }}

</td>

</tr>



<tr>

<th>
Status
</th>


<td>

<span class="badge bg-success">

Submitted

</span>

</td>

</tr>



<tr>

<th>
Technical Review
</th>


<td>

<span class="badge bg-warning text-dark">

Pending

</span>

</td>

</tr>



<tr>

<th>
Submission Version
</th>


<td>

{{ $manuscript->submission_version }}

</td>

</tr>


</table>





{{-- Authors --}}

<h5 class="mt-4">

Authors

</h5>


<table class="table table-bordered">


<thead class="table-light">

<tr>

<th>
#
</th>

<th>
Name
</th>

<th>
Email
</th>

<th>
Institution
</th>

<th>
Country
</th>

</tr>

</thead>



<tbody>


@foreach($manuscript->authors as $author)


<tr>


<td>

{{ $author->author_order }}

</td>


<td>

{{ $author->full_name }}

@if($author->is_corresponding)

<span class="badge bg-primary">
Corresponding
</span>

@endif

</td>



<td>

{{ $author->email }}

</td>



<td>

{{ $author->institution }}

</td>



<td>

{{ $author->country }}

</td>


</tr>


@endforeach


</tbody>


</table>







{{-- Uploaded Files --}}


<h5 class="mt-4">

Uploaded Files

</h5>



<table class="table table-bordered">


<thead class="table-light">

<tr>

<th>
File Type
</th>


<th>
File Name
</th>


<th>
Action
</th>


</tr>


</thead>



<tbody>


@forelse($manuscript->files as $file)


<tr>


<td>

{{ $file->file_type }}

</td>



<td>

{{ $file->original_name }}

</td>



<td>


<a href="{{ asset('storage/'.$file->file_path) }}"
target="_blank"
class="btn btn-sm btn-primary">


<i class="bi bi-eye"></i>

View


</a>


</td>


</tr>


@empty


<tr>

<td colspan="3"
class="text-center text-muted">

No files found.

</td>


</tr>


@endforelse


</tbody>


</table>






{{-- Submission Progress --}}


<h5 class="mt-4">

Submission Progress

</h5>



<div class="progress mb-3">


<div class="progress-bar bg-success"

style="width: {{ $manuscript->completion_percentage }}%">


{{ $manuscript->completion_percentage }}%


</div>


</div>





{{-- Status Timeline --}}


<h5 class="mt-4">

Submission Status

</h5>



<ul class="list-group">


<li class="list-group-item">

<i class="bi bi-check-circle text-success"></i>

Draft Completed

</li>



<li class="list-group-item">

<i class="bi bi-check-circle text-success"></i>

Manuscript Submitted

</li>



<li class="list-group-item">

<i class="bi bi-hourglass-split text-warning"></i>

Technical Review Pending

</li>



<li class="list-group-item">

<i class="bi bi-circle text-secondary"></i>

Editorial Decision

</li>


<li class="list-group-item">

<i class="bi bi-circle text-secondary"></i>

Publication

</li>


</ul>





<div class="mt-4">


<a href="{{ route('author.submitted.index') }}"

class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back to Submitted Manuscripts

</a>



<a href="{{ route('author.dashboard') }}"

class="btn btn-primary">

Dashboard

</a>


</div>



</div>


</div>


</div>


@endsection