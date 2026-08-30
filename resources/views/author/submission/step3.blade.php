@extends('author.layouts.app')

@section('content')

<div class="container">

<h3>Add Authors</h3>

<form method="POST"
      action="{{ route('author.submission.step3.store',$manuscript->id) }}">

@csrf


<div id="authors-wrapper">


<div class="author-card border rounded p-3 mb-3">

<h5>
Author 1
</h5>


<div class="row">


<div class="col-md-4">
<label>Title</label>
<select name="authors[0][title]" class="form-control">
<option value="">Select</option>
<option>Dr.</option>
<option>Prof.</option>
<option>Mr.</option>
<option>Ms.</option>
</select>
</div>


<div class="col-md-4">
<label>First Name *</label>
<input type="text"
name="authors[0][first_name]"
class="form-control"
required>
</div>


<div class="col-md-4">
<label>Last Name *</label>
<input type="text"
name="authors[0][last_name]"
class="form-control"
required>
</div>


<div class="col-md-6 mt-3">
<label>Email *</label>
<input type="email"
name="authors[0][email]"
class="form-control"
required>
</div>


<div class="col-md-6 mt-3">
<label>Mobile</label>
<input type="text"
name="authors[0][mobile]"
class="form-control">
</div>


<div class="col-md-6 mt-3">
<label>Institution *</label>
<input type="text"
name="authors[0][institution]"
class="form-control"
required>
</div>


<div class="col-md-6 mt-3">
<label>Department</label>
<input type="text"
name="authors[0][department]"
class="form-control">
</div>


<div class="col-md-6 mt-3">
<label>Designation</label>
<input type="text"
name="authors[0][designation]"
class="form-control">
</div>


<div class="col-md-6 mt-3">
<label>Country *</label>

<input type="text"
name="authors[0][country]"
class="form-control"
required>

</div>


<div class="col-md-6 mt-3">

<label>ORCID</label>

<input type="text"
name="authors[0][orcid]"
class="form-control">

</div>



<div class="col-md-6 mt-3">

<label>Corresponding Author</label>

<select name="authors[0][is_corresponding]"
class="form-control">

<option value="0">
No
</option>

<option value="1">
Yes
</option>

</select>

</div>


</div>


</div>


</div>



<button type="button"
id="add-author"
class="btn btn-primary">

+ Add Another Author

</button>



<button type="submit"
class="btn btn-success">

Save Authors & Continue

</button>


</form>


</div>



<script>


let authorIndex = 1;


document
.getElementById('add-author')
.addEventListener('click',function(){



let html = `

<div class="author-card border rounded p-3 mb-3">


<div class="d-flex justify-content-between">

<h5>
Author ${authorIndex+1}
</h5>


<button type="button"
class="btn btn-danger remove-author">
Remove
</button>


</div>



<div class="row">


<div class="col-md-4">
<label>Title</label>

<select name="authors[${authorIndex}][title]"
class="form-control">

<option value="">
Select
</option>

<option>
Dr.
</option>

<option>
Prof.
</option>

</select>

</div>



<div class="col-md-4">

<label>First Name *</label>

<input type="text"
name="authors[${authorIndex}][first_name]"
class="form-control"
required>

</div>



<div class="col-md-4">

<label>Last Name *</label>

<input type="text"
name="authors[${authorIndex}][last_name]"
class="form-control"
required>

</div>



<div class="col-md-6 mt-3">

<label>Email *</label>

<input type="email"
name="authors[${authorIndex}][email]"
class="form-control"
required>

</div>


<div class="col-md-6 mt-3">

<label>Institution *</label>

<input type="text"
name="authors[${authorIndex}][institution]"
class="form-control"
required>

</div>


<div class="col-md-6 mt-3">

<label>Department</label>

<input type="text"
name="authors[${authorIndex}][department]"
class="form-control">

</div>


<div class="col-md-6 mt-3">

<label>Country *</label>

<input type="text"
name="authors[${authorIndex}][country]"
class="form-control"
required>

</div>


</div>


</div>

`;



document
.getElementById('authors-wrapper')
.insertAdjacentHTML(
'beforeend',
html
);



authorIndex++;


});




// Remove author


document
.addEventListener(
'click',
function(e){


if(
e.target.classList.contains(
'remove-author'
)
)

{

e.target
.closest('.author-card')
.remove();


}


});



</script>


@endsection