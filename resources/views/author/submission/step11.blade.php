@extends('author.layouts.app')


@section('content')


<div class="container-fluid py-4">


<div class="card shadow-sm">


<div class="card-header">

<h4>
Step 11 : Acknowledgement
</h4>

</div>



<div class="card-body">


<form method="POST"

action="{{route(
'author.submission.step11.store',
$manuscript->id
)}}">


@csrf



<div class="mb-4">


<label class="form-label fw-bold">

Acknowledgement Applicable?

</label>


<select 
name="applicable"
class="form-select">


<option value="1"

@if(
old(
'applicable',
$acknowledgement->applicable ?? ''
)==1
)

selected

@endif

>

Yes

</option>



<option value="0"

@if(
old(
'applicable',
$acknowledgement->applicable ?? ''
)==0
)

selected

@endif

>

No

</option>


</select>


</div>





<div class="mb-4">


<label class="form-label fw-bold">

Acknowledgement Text

</label>



<textarea

name="text"

class="form-control"

rows="6"

placeholder="Enter acknowledgement statement">

{{old(
'text',
$acknowledgement->text ?? ''
)}}

</textarea>


</div>





<div class="alert alert-info">

Example:

<br>

"The authors acknowledge the support provided by Bangladesh Medical Research Council (BMRC)."

</div>





<button class="btn btn-primary">

Save & Continue

</button>


</form>


</div>


</div>


</div>


@endsection