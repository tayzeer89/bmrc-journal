@extends('author.layouts.app')


@section('content')


<div class="container-fluid">


<h3>
Funding Information
</h3>



<form method="POST"
action="{{route(
'author.submission.step8.store',
$manuscript->id
)}}">


@csrf



<div class="card">

<div class="card-body">


<div class="mb-3">

<label>
Funding Received?
</label>


<select name="funding_received"
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
Funding Type
</label>


<select name="funding_type"
class="form-control">


<option>
BMRC Research Grant
</option>


<option>
Government
</option>


<option>
University
</option>


<option>
NGO
</option>


<option>
International Organization
</option>


<option>
Industry
</option>


<option>
Self-funded
</option>


<option>
Other
</option>


</select>


</div>




<div class="mb-3">

<label>
Funding Organization
</label>


<input type="text"
name="funding_organization"
class="form-control">


</div>




<div class="mb-3">

<label>
Grant Number
</label>


<input type="text"
name="grant_number"
class="form-control">


</div>



<div class="mb-3">

<label>
Grant Amount
</label>


<input type="number"
name="grant_amount"
class="form-control">


</div>



<div class="mb-3">

<label>
Funding Statement
</label>


<textarea
name="funding_statement"
class="form-control"></textarea>


</div>



<button class="btn btn-primary">

Save & Continue

</button>



</div>

</div>



</form>


</div>


@endsection