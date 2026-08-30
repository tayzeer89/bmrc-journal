@extends('author.layouts.app')


@section('content')


<div class="card">


<div class="card-header">

<h4>
Corresponding Author Declaration
</h4>

</div>


<form method="POST"
action="{{route(
'author.submission.step5.store',
$manuscript->id
)}}">

@csrf


<div class="card-body">


@if($correspondingAuthor)


<h5>
Corresponding Author
</h5>


<p>
<strong>Name:</strong>

{{$correspondingAuthor->first_name}}
{{$correspondingAuthor->last_name}}

</p>


<p>
<strong>Email:</strong>

{{$correspondingAuthor->email}}

</p>


<p>
<strong>Institution:</strong>

{{$correspondingAuthor->institution}}

</p>


<input type="hidden"
name="manuscript_author_id"
value="{{$correspondingAuthor->id}}">


@endif




<div class="mb-3">

<label>
Preferred Communication Method
</label>


<select name="preferred_communication_method"
class="form-control">


<option value="Email">
Email
</option>


<option value="Phone">
Phone
</option>


<option value="Both">
Both
</option>


</select>


</div>




<div class="mb-3">


<label>
Available for Editorial Communication?
</label>


<select name="available_for_editorial_communication"
class="form-control">


<option value="1">
Yes
</option>


<option value="0">
No
</option>


</select>


</div>





<div class="form-check">


<input class="form-check-input"
type="checkbox"
name="declaration_confirmed"
value="1">


<label class="form-check-label">

I confirm that I am authorized to communicate with BMRC on behalf of all authors.

</label>


</div>



</div>


<div class="card-footer">


<button class="btn btn-primary">

Save & Continue

</button>


</div>



</form>


</div>


@endsection