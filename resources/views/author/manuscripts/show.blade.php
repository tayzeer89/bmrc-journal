@extends('author.layouts.app')


@section('content')


<div class="container-fluid py-4">


<div class="card shadow-sm">


<div class="card-header bg-primary text-white">

<h4 class="mb-0">

Manuscript Details

</h4>

</div>



<div class="card-body">


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
Journal
</th>

<td>

{{ $manuscript->journal->name ?? 'N/A' }}

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
Status
</th>

<td>

<span class="badge bg-success">

{{ ucfirst($manuscript->status) }}

</span>

</td>

</tr>



<tr>

<th>
Completion Progress
</th>

<td>


<div class="progress">


<div class="progress-bar"
style="width:{{ $manuscript->completion_percentage }}%">


{{ $manuscript->completion_percentage }}%


</div>


</div>


</td>

</tr>


</table>



<hr>


<h5>
Authors
</h5>


<table class="table table-sm table-bordered">


@foreach($manuscript->authors as $author)

<tr>

<td>

{{ $author->full_name }}

</td>

<td>

{{ $author->institution }}

</td>

</tr>


@endforeach


</table>



<hr>


<h5>
Uploaded Files
</h5>


<table class="table table-bordered">


@foreach($manuscript->files as $file)

<tr>

<td>

{{ $file->file_type }}

</td>


<td>


<a href="{{ Storage::url($file->file_path) }}"
   target="_blank"
   class="btn btn-primary">

    View

</a>


</td>


</tr>


@endforeach


</table>



<a href="{{ route('author.manuscripts.index') }}"
class="btn btn-secondary">

Back

</a>



</div>


</div>


</div>


@endsection