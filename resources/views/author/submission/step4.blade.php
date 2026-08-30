@extends('author.layouts.app')


@section('title','Author Contribution & Affiliation')


@section('content')


<div class="container-fluid px-0">


<div class="card shadow-sm">


<div class="card-header bg-white">

<h4 class="mb-0">
Author Contribution & Affiliation
</h4>

</div>



<form method="POST"
action="{{ route('author.submission.step4.store',$manuscript->id) }}">

@csrf



<div class="card-body">


@foreach($authors as $author)


<div class="card mb-4">


<div class="card-header">

<strong>
{{ $author->first_name }}
{{ $author->last_name }}
</strong>


</div>




<div class="card-body">


<h6>
CRediT Contribution
</h6>



@php

$contribution=[

'conceptualization'=>'Conceptualization',
'methodology'=>'Methodology',
'software'=>'Software',
'validation'=>'Validation',
'formal_analysis'=>'Formal Analysis',
'investigation'=>'Investigation',
'resources'=>'Resources',
'data_curation'=>'Data Curation',
'writing_original_draft'=>'Writing Original Draft',
'writing_review_editing'=>'Writing Review & Editing',
'visualization'=>'Visualization',
'supervision'=>'Supervision',
'project_administration'=>'Project Administration',
'funding_acquisition'=>'Funding Acquisition'

];

@endphp



<div class="row">


@foreach($contribution as $key=>$label)


<div class="col-lg-4 col-md-6 mb-2">


<div class="form-check">


<input class="form-check-input"

type="checkbox"

name="authors[{{$author->id}}][contribution][]"

value="{{$key}}"

id="{{$author->id}}{{$key}}">



<label class="form-check-label"
for="{{$author->id}}{{$key}}">

{{$label}}

</label>


</div>


</div>


@endforeach


</div>




<hr>



<h6>
Affiliation
</h6>



<div id="affiliation-wrapper-{{$author->id}}">


<div class="affiliation-box border rounded p-3 mb-3">



<div class="row">


<div class="col-md-6 mb-3">

<label>
Institution Name
</label>

<input type="text"
class="form-control"
name="authors[{{$author->id}}][affiliation][0][institution_name]">

</div>



<div class="col-md-6 mb-3">

<label>
Faculty / Institute
</label>

<input type="text"
class="form-control"
name="authors[{{$author->id}}][affiliation][0][faculty_institute]">

</div>



<div class="col-md-6 mb-3">

<label>
Department
</label>

<input type="text"
class="form-control"
name="authors[{{$author->id}}][affiliation][0][department]">

</div>



<div class="col-md-6 mb-3">

<label>
Designation
</label>

<input type="text"
class="form-control"
name="authors[{{$author->id}}][affiliation][0][designation]">

</div>



<div class="col-md-12 mb-3">

<label>
Address
</label>

<textarea
class="form-control"
name="authors[{{$author->id}}][affiliation][0][address]"></textarea>


</div>




<div class="col-md-4 mb-3">

<label>
City
</label>

<input type="text"
class="form-control"
name="authors[{{$author->id}}][affiliation][0][city]">

</div>




<div class="col-md-4 mb-3">

<label>
Country
</label>

<input type="text"
class="form-control"
name="authors[{{$author->id}}][affiliation][0][country]"
value="Bangladesh">

</div>




<div class="col-md-4 mb-3">

<label>
Postal Code
</label>

<input type="text"
class="form-control"
name="authors[{{$author->id}}][affiliation][0][postal_code]">

</div>



<div class="col-md-6 mb-3">

<label>
Institution Email
</label>

<input type="email"
class="form-control"
name="authors[{{$author->id}}][affiliation][0][institution_email]">

</div>




<div class="col-md-6 mb-3">

<label>
Website
</label>

<input type="text"
class="form-control"
name="authors[{{$author->id}}][affiliation][0][institution_website]">

</div>



</div>


</div>


</div>



<button type="button"

class="btn btn-outline-success btn-sm"

onclick="addAffiliation({{$author->id}})">

<i class="bi bi-plus-circle"></i>

Add New Affiliation

</button>




</div>


</div>


@endforeach



</div>




<div class="card-footer bg-white">


<button class="btn btn-primary">

Save & Continue

</button>


</div>



</form>



</div>



</div>


@endsection





@push('scripts')


<script>


function addAffiliation(authorId)
{


let wrapper=document.getElementById(
'affiliation-wrapper-'+authorId
);



let count =
wrapper.querySelectorAll('.affiliation-box').length;



let html=`


<div class="affiliation-box border rounded p-3 mb-3">


<h6>
Additional Affiliation ${count+1}
</h6>


<input class="form-control mb-2"

name="authors[${authorId}][affiliation][${count}][institution_name]"

placeholder="Institution Name">



<input class="form-control mb-2"

name="authors[${authorId}][affiliation][${count}][department]"

placeholder="Department">



<input class="form-control mb-2"

name="authors[${authorId}][affiliation][${count}][designation]"

placeholder="Designation">



<textarea class="form-control mb-2"

name="authors[${authorId}][affiliation][${count}][address]"

placeholder="Address"></textarea>



<input class="form-control mb-2"

name="authors[${authorId}][affiliation][${count}][city]"

placeholder="City">



<input class="form-control"

name="authors[${authorId}][affiliation][${count}][country]"

placeholder="Country">



</div>



`;



wrapper.insertAdjacentHTML(
'beforeend',
html
);



}



</script>


@endpush