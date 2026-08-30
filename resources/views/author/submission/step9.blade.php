@extends('author.layouts.app')


@section('content')


<div class="container-fluid">


<h3>
Conflict of Interest
</h3>



<form method="POST"
action="{{route(
'author.submission.step9.store',
$manuscript->id
)}}">


@csrf



<div class="card">

<div class="card-body">



<div class="mb-3">

<label>
Conflict of Interest Exists?
</label>


<select name="conflict_exists"
class="form-control">


<option value="0">
No
</option>


<option value="1">
Yes
</option>


</select>


</div>




<div class="mb-3">


<label>
Conflict Description
</label>


<textarea
name="conflict_description"
class="form-control"></textarea>


</div>




<div class="form-check mb-3">

    <input 
        class="form-check-input"
        type="checkbox"
        name="author_declaration"
        value="The authors declare that they have no conflict of interest."
        id="authorDeclaration"
        required>

    <label class="form-check-label" for="authorDeclaration">

        The authors declare that they have no conflict of interest.

    </label>

</div>




<div class="form-check mb-3">

    <input 
        class="form-check-input"
        type="checkbox"
        name="all_authors_agreed"
        value="1"
        id="allAuthorsAgreed"
        required>

    <label 
        class="form-check-label"
        for="allAuthorsAgreed">

        All Authors Agree

    </label>

</div>



<button class="btn btn-primary">

Save & Continue

</button>



</div>

</div>



</form>


</div>


@endsection