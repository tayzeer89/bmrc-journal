@extends('author.layouts.app')


@section('content')


<div class="container-fluid py-4">


<div class="card shadow-sm">


<div class="card-header bg-success text-white">

<h4>
Submitted Manuscripts
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
Submitted Date
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


@foreach($manuscripts as $manuscript)


<tr>


<td>

{{ $manuscript->manuscript_id }}

</td>



<td>

{{ Str::limit($manuscript->title,50) }}

</td>



<td>

{{ $manuscript->submitted_at?->format('d M Y') }}

</td>



<td>

<span class="badge bg-success">

Submitted

</span>

</td>



<td>

<a href="{{ route(
'author.submitted.show',
$manuscript->id
)}}"
class="btn btn-sm btn-primary">

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