@extends('author.layouts.app')


@section('content')

<div class="container-fluid">

<h3>
Manuscript File Upload
</h3>


<form method="POST"
action="{{route(
'author.submission.step6.store',
$manuscript->id
)}}"
enctype="multipart/form-data">


@csrf


<div class="card">

<div class="card-body">


<div class="mb-3">

<label>
Main Manuscript (DOC/DOCX)
</label>

<input type="file"
name="main_manuscript"
class="form-control"
required>

</div>



<div class="mb-3">

<label>
Title Page
</label>

<input type="file"
name="title_page"
class="form-control">

</div>



<div class="mb-3">

<label>
Cover Letter
</label>

<input type="file"
name="cover_letter"
class="form-control">

</div>



<div class="mb-3">

<label>
Tables
</label>

<input type="file"
name="tables"
class="form-control">

</div>



<div class="mb-3">

<label>
Figures
</label>

<input type="file"
name="figures"
class="form-control">

</div>



<button class="btn btn-primary">

Save & Continue

</button>


</div>

</div>


</form>


</div>

@endsection