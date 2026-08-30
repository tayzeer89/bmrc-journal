@extends('author.layouts.app')


@section('title','Manuscript Information')


@section('content')


<div class="container-fluid py-4">


    {{-- Header --}}

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <h3 class="fw-bold mb-2">
                Manuscript Information
            </h3>


            <p class="text-muted mb-0">

                Manuscript ID:

                <strong class="text-primary">
                    {{ $manuscript->manuscript_id }}
                </strong>

            </p>


        </div>

    </div>




<form method="POST"
      action="{{ route('author.submission.step2.store',$manuscript->id) }}">


@csrf



<div class="card shadow-sm">

<div class="card-body">



<h5 class="fw-bold mb-4">

Step 2: Scientific Information

</h5>




{{-- Background --}}

<div class="mb-4">

<label class="fw-bold">

Background / Introduction

</label>


<textarea
name="background"
id="background"
class="form-control editor"
rows="6">{{ old('background') }}</textarea>


@error('background')

<div class="text-danger">
{{ $message }}
</div>

@enderror


</div>





{{-- Objective --}}

<div class="mb-4">

<label class="fw-bold">

Objective

</label>


<textarea
name="objective"
id="objective"
class="form-control editor"
rows="5">{{ old('objective') }}</textarea>


</div>





{{-- Methods --}}

<div class="mb-4">

<label class="fw-bold">

Methods

</label>


<textarea
name="methods"
id="methods"
class="form-control editor"
rows="6">{{ old('methods') }}</textarea>


</div>





{{-- Results --}}

<div class="mb-4">

<label class="fw-bold">

Results

</label>


<textarea
name="results"
id="results"
class="form-control editor"
rows="6">{{ old('results') }}</textarea>


</div>





{{-- Conclusion --}}

<div class="mb-4">

<label class="fw-bold">

Conclusion

</label>


<textarea
name="conclusion"
id="conclusion"
class="form-control editor"
rows="5">{{ old('conclusion') }}</textarea>


</div>





<hr>



<h5 class="fw-bold mb-4">

Study Information

</h5>





{{-- Trial Registration --}}

<div class="row">


<div class="col-md-6 mb-3">


<label>

Trial Registration Number

</label>


<input type="text"
name="trial_registration_number"
class="form-control"
value="{{ old('trial_registration_number') }}">


</div>




<div class="col-md-6 mb-3">


<label>

Trial Registration Organization

</label>


<input type="text"
name="trial_registration_organization"
class="form-control"
value="{{ old('trial_registration_organization') }}">


</div>


</div>






{{-- Study Design --}}

<div class="mb-3">

<label class="fw-bold">
    Study Design
</label>


<select name="study_design"
        id="study_design"
        class="form-control">


<option value="">
    Select Study Design
</option>


<option value="Randomized Controlled Trial">
Randomized Controlled Trial
</option>


<option value="Observational Study">
Observational Study
</option>


<option value="Cross Sectional Study">
Cross Sectional Study
</option>


<option value="Case Control Study">
Case Control Study
</option>


<option value="Cohort Study">
Cohort Study
</option>


<option value="Systematic Review">
Systematic Review
</option>


<option value="Meta Analysis">
Meta Analysis
</option>


<option value="Other">
Other
</option>


</select>


</div>



{{-- Other Study Design --}}

<div class="mb-3"
     id="other_study_design_div"
     style="display:none;">


<label class="fw-bold">

Specify Study Design

</label>


<input type="text"
       name="other_study_design"
       id="other_study_design"
       class="form-control"
       placeholder="Enter study design">


</div>






{{-- Study Period --}}

<div class="row">


<div class="col-md-6 mb-3">


<label>

Study Start Date

</label>


<input type="date"
name="study_start_date"
class="form-control">


</div>




<div class="col-md-6 mb-3">


<label>

Study End Date

</label>


<input type="date"
name="study_end_date"
class="form-control">


</div>


</div>






{{-- Location --}}

<div class="row">


<div class="col-md-8 mb-3">


<label>

Study Location

</label>


<input type="text"
name="study_location"
class="form-control"
placeholder="Hospital / Institute / Country">


</div>



<div class="col-md-4 mb-3">


<label>

Sample Size

</label>


<input type="number"
name="sample_size"
class="form-control"
min="0">


</div>


</div>






{{-- Funding Source --}}

<div class="mb-3">


<label class="fw-bold">

Funding Source

</label>


<select name="funding_source"
        id="funding_source"
        class="form-control">


<option value="">
Select Funding Source
</option>


<option value="BMRC Research Grant">
BMRC Research Grant
</option>


<option value="Government">
Government
</option>


<option value="University">
University
</option>


<option value="NGO">
NGO
</option>


<option value="International Organization">
International Organization
</option>


<option value="Industry">
Industry
</option>


<option value="Self Funded">
Self Funded
</option>


<option value="Other">
Other
</option>


</select>


</div>




{{-- Other Funding Source --}}

<div class="mb-3"
     id="other_funding_div"
     style="display:none;">


<label class="fw-bold">

Specify Funding Source

</label>


<input type="text"
       name="other_funding_source"
       id="other_funding_source"
       class="form-control"
       placeholder="Enter funding organization/source">


</div>







<hr>




<h5 class="fw-bold mb-3">

Ethical Approval

</h5>



<div class="form-check mb-3">


<input
class="form-check-input"
type="checkbox"
name="ethical_approval_available"
value="1"
id="ethicalApproval">


<label
class="form-check-label"
for="ethicalApproval">


Ethical Approval Available?


</label>


</div>





<div class="row">


<div class="col-md-6 mb-3">


<label>

Ethical Approval Number

</label>


<input type="text"
name="ethical_approval_number"
class="form-control">


</div>



<div class="col-md-6 mb-3">


<label>

Ethical Approval Date

</label>


<input type="date"
name="ethical_approval_date"
class="form-control">


</div>


</div>






<div class="text-end">


<button type="submit"
class="btn btn-primary px-5">


Save & Continue


</button>


</div>





</div>

</div>


</form>


</div>


@endsection





@push('styles')

<style>


.ck-editor__editable {

    min-height:250px;

}


</style>

@endpush






@push('scripts')


<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>


<script>


document.querySelectorAll('.editor')
.forEach((element)=>{


ClassicEditor
.create(element,{
toolbar:[
'heading',
'|',
'bold',
'italic',
'link',
'bulletedList',
'numberedList',
'blockQuote'
]
})

.catch(error=>{

console.error(error);

});


});


</script>


{{-- Other if Not on List --}}

<script>

document.addEventListener("DOMContentLoaded", function(){



    // Study Design Other

    let studyDesign =
        document.getElementById('study_design');


    let otherStudyDiv =
        document.getElementById('other_study_design_div');



    studyDesign.addEventListener('change',function(){


        if(this.value === 'Other'){

            otherStudyDiv.style.display='block';

        }
        else{

            otherStudyDiv.style.display='none';

        }


    });





    // Funding Other


    let funding =
        document.getElementById('funding_source');


    let otherFundingDiv =
        document.getElementById('other_funding_div');



    funding.addEventListener('change',function(){


        if(this.value === 'Other'){

            otherFundingDiv.style.display='block';

        }
        else{

            otherFundingDiv.style.display='none';

        }


    });



});

</script>

@endpush