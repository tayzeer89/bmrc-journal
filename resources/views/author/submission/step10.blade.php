@extends('author.layouts.app')


@section('content')


<div class="container-fluid py-4">


<div class="card shadow-sm">


<div class="card-header">

<h4>

Step 10 : Data Availability

</h4>

</div>



<div class="card-body">


<form method="POST"

action="{{ route(
'author.submission.step10.store',
$manuscript->id
) }}">


@csrf



{{-- Data Available --}}

<div class="mb-3">


<label class="form-label fw-bold">

Is Data Available?

</label>



<select

name="data_available"

class="form-select">


<option value="1"

@if(
old(
'data_available',
$dataAvailability->data_available ?? ''
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
'data_available',
$dataAvailability->data_available ?? ''
)==0
)

selected

@endif

>

No

</option>



</select>


</div>





{{-- Statement --}}


<div class="mb-3">


<label class="form-label fw-bold">

Data Availability Statement

</label>


<textarea

name="statement"

class="form-control"

rows="5"

placeholder="Describe where and how the research data can be accessed">

{{ old(
'statement',
$dataAvailability->statement ?? ''
) }}

</textarea>


</div>







{{-- Repository --}}


<div class="mb-3">


<label class="form-label fw-bold">

Data Repository

</label>



<select

name="repository"

class="form-select">


<option value="">
Select Repository
</option>


<option value="Figshare"

{{ 
(old('repository',
$dataAvailability->repository ?? '')=='Figshare')
?'selected':''
}}

>

Figshare

</option>



<option value="Dryad"

{{ 
(old('repository',
$dataAvailability->repository ?? '')=='Dryad')
?'selected':''
}}

>

Dryad

</option>



<option value="Zenodo"

{{ 
(old('repository',
$dataAvailability->repository ?? '')=='Zenodo')
?'selected':''
}}

>

Zenodo

</option>



<option value="Other"

{{ 
(old('repository',
$dataAvailability->repository ?? '')=='Other')
?'selected':''
}}

>

Other

</option>


</select>


</div>







{{-- Repository Name --}}


<div class="mb-3">


<label class="form-label">

Repository Name

</label>


<input

type="text"

name="repository_name"

class="form-control"

value="{{old(
'repository_name',
$dataAvailability->repository_name ?? ''
)}}">


</div>






{{-- DOI --}}


<div class="mb-3">


<label class="form-label">

DOI / URL

</label>


<input

type="text"

name="doi_url"

class="form-control"

value="{{old(
'doi_url',
$dataAvailability->doi_url ?? ''
)}}">


</div>







{{-- Access Restriction --}}


<div class="mb-3">


<label class="form-label">

Access Restriction

</label>



<select

name="access_restriction"

class="form-select">


<option value="None">

None

</option>


<option value="Restricted">

Restricted

</option>


<option value="Embargo">

Embargo

</option>


</select>


</div>








{{-- Restriction Reason --}}


<div class="mb-3">


<label class="form-label">

Reason for Restriction

</label>


<textarea

name="restriction_reason"

class="form-control"

rows="3">

{{old(
'restriction_reason',
$dataAvailability->restriction_reason ?? ''
)}}

</textarea>


</div>







<button class="btn btn-primary">

Save & Continue

</button>



</form>



</div>


</div>


</div>


@endsection