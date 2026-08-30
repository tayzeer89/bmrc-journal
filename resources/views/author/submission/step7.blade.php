@extends('author.layouts.app')


@section('title','Ethical Information')


@section('content')


<div class="container-fluid">


<div class="card">

<div class="card-header">

<h4>
Ethical Information
</h4>

</div>


<div class="card-body">


<form method="POST"
action="{{route(
'author.submission.step7.store',
$manuscript->id
)}}">

@csrf



<div class="mb-3">

<label>
Human Participants Involved?
</label>


<select name="human_participants"
class="form-control">

<option value="1">
Yes
</option>

<option value="0">
No
</option>

</select>


</div>




<div class="mb-3">

<label>
Animal Study?
</label>


<select name="animal_study"
class="form-control">


<option value="1">
Yes
</option>


<option value="0">
No
</option>


</select>


</div>





<div class="mb-3">

<label>
Ethics Committee Name
</label>


<input type="text"
name="ethics_committee_name"
class="form-control">


</div>





<div class="mb-3">

<label>
Approval Number
</label>


<input type="text"
name="approval_number"
class="form-control">


</div>





<div class="mb-3">

<label>
Approval Date
</label>


<input type="date"
name="approval_date"
class="form-control">


</div>





<button class="btn btn-primary">

Save & Continue

</button>


</form>



</div>


</div>


</div>


@endsection