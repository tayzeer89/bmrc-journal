@extends('author.layouts.app')


@section('title','Draft Manuscripts')


@section('content')

<div class="container-fluid py-4">


<div class="d-flex justify-content-between mb-4">

<h3>
Draft Manuscripts
</h3>


<a href="{{ route('author.submission.create') }}"
class="btn btn-primary">

+ New Submission

</a>


</div>



<div class="card shadow-sm">


<div class="card-body">


<table class="table table-bordered">


<thead class="table-light">

<tr>

<th>
Manuscript ID
</th>

<th>
Title
</th>

<th>
Article Type
</th>

<th>
Progress
</th>

<th>
Last Saved
</th>

<th>
Action
</th>


</tr>

</thead>


<tbody>


@forelse($drafts as $draft)


<tr>


<td>
{{ $draft->manuscript_id }}
</td>



<td>
{{ $draft->title ?? 'Untitled Manuscript' }}
</td>



<td>

{{ 
$draft->articleType->name ?? '-'
}}

</td>



<td>


<div class="progress">

<div class="progress-bar"
style="width:{{ $draft->completion_percentage }}%">

{{ $draft->completion_percentage }}%

</div>

</div>


</td>



<td>

{{ 
$draft->draft_saved_at?->format('d M Y h:i A')
}}

</td>



<td>


<a href="{{ route(
'author.drafts.edit',
$draft->id
) }}"
class="btn btn-sm btn-success">

Continue

</a>



<form action="{{ route(
'author.drafts.destroy',
$draft->id
) }}"
method="POST"
class="d-inline">


@csrf

@method('DELETE')


<button class="btn btn-sm btn-danger"
onclick="return confirm('Delete draft?')">

Delete

</button>


</form>


</td>


</tr>


@empty


<tr>

<td colspan="6"
class="text-center">

No draft manuscript available

</td>

</tr>


@endforelse


</tbody>


</table>



{{ $drafts->links() }}


</div>

</div>


</div>


@endsection