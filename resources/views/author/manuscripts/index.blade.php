@extends('author.layouts.app')


@section('content')


<div class="container-fluid py-4">


<div class="card shadow-sm">


<div class="card-header">

<h4>
My Manuscripts
</h4>

</div>


<div class="card-body">


<table class="table table-bordered">


<thead>

<tr>

<th>
Manuscript ID
</th>


<th>
Title
</th>


<th>
Journal
</th>


<th>
Status
</th>


<th>
Progress
</th>


<th>
Action
</th>


</tr>

</thead>


<tbody>


@foreach($manuscripts as $manuscript)


<tr>


<td>

{{ $manuscript->manuscript_id }}

</td>


<td>

{{ Str::limit($manuscript->title,50) }}

</td>


<td>

{{ $manuscript->journal->name ?? 'N/A' }}

</td>



<td>


<span class="badge bg-primary">

{{ ucfirst($manuscript->status) }}

</span>


</td>



<td>


<div class="progress">


<div class="progress-bar"
style="width:
{{ $manuscript->completion_percentage }}%">


{{ $manuscript->completion_percentage }}%


</div>


</div>


</td>



<td>


<a href="{{ route(
'author.manuscripts.show',
$manuscript->id
)}}"
class="btn btn-sm btn-info">


View


</a>


</td>


</tr>


@endforeach


</tbody>


</table>


{{ $manuscripts->links() }}


</div>


</div>


</div>


@endsection