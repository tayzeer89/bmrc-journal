@extends('author.layouts.app')


@section('title','New Submission')


@section('content')


<div class="container-fluid py-4">


<h3 class="fw-bold mb-4">
    New Manuscript Submission
</h3>



<form method="POST"
action="{{ route('author.submission.step1.store') }}">
@csrf



<div class="card shadow-sm">

<div class="card-body">


<h5 class="mb-4">
    Step 1: Article Information
</h5>



{{-- Journal --}}

<div class="mb-3">

<label class="fw-bold">
    Journal
</label>


<select name="journal_id"
        class="form-control"
        required>


<option value="">
    Select Journal
</option>


@foreach($journals as $journal)

<option value="{{ $journal->id }}"
@if(old('journal_id')==$journal->id)
selected
@endif
>

{{ $journal->name }}

</option>

@endforeach


</select>


@error('journal_id')

<div class="text-danger">
{{ $message }}
</div>

@enderror


</div>





{{-- Article Type --}}

<div class="mb-3">

<label class="fw-bold">
    Article Type
</label>


<select name="article_type_id"
        class="form-control"
        required>


<option value="">
Select Article Type
</option>



@foreach($articleTypes as $type)


<option value="{{ $type->id }}"
@if(old('article_type_id')==$type->id)
selected
@endif
>

{{ $type->name }}

</option>


@endforeach


</select>


@error('article_type_id')

<div class="text-danger">
{{ $message }}
</div>

@enderror


</div>






{{-- Title --}}

<div class="mb-3">


<label class="fw-bold">
Manuscript Title
</label>


<textarea
name="title"
class="form-control"
rows="2"
required>{{ old('title') }}</textarea>



@error('title')

<div class="text-danger">
{{ $message }}
</div>

@enderror


</div>






{{-- Short Title --}}

<div class="mb-3">


<label>
Short Title
</label>


<input type="text"
name="short_title"
class="form-control"
value="{{ old('short_title') }}">


</div>







{{-- Abstract --}}


<div class="mb-3">


<label class="fw-bold">
Abstract
</label>



<textarea
id="abstract"
name="abstract"
class="form-control"
rows="8">{{ old('abstract') }}</textarea>



@error('abstract')

<div class="text-danger">
{{ $message }}
</div>

@enderror


</div>







{{-- Keywords --}}

<div class="mb-3">


<label class="fw-bold">
Keywords
</label>


<input type="text"
name="keywords"
class="form-control"
value="{{ old('keywords') }}"
placeholder="Example: cancer, health, research">


<small class="text-muted">
Separate keywords using comma (,)
</small>


</div>






<div class="row">


<div class="col-md-6 mb-3">


<label>
Subject Category
</label>


<input type="text"
name="subject_category"
class="form-control"
value="{{ old('subject_category') }}">


</div>




<div class="col-md-6 mb-3">


<label>
Subcategory
</label>


<input type="text"
name="subcategory"
class="form-control"
value="{{ old('subcategory') }}">


</div>


</div>







{{-- Language --}}

<div class="mb-3">


<label class="fw-bold">
Language
</label>



<select name="language"
class="form-control"
required>


<option value="English"
{{ old('language','English')=='English'?'selected':'' }}>
English
</option>


<option value="Chinese">
Chinese
</option>


<option value="Spanish">
Spanish
</option>


<option value="Hindi">
Hindi
</option>


<option value="Arabic">
Arabic
</option>


<option value="French">
French
</option>


<option value="Bengali">
Bengali
</option>


<option value="Portuguese">
Portuguese
</option>


<option value="Russian">
Russian
</option>


<option value="Japanese">
Japanese
</option>


<option value="Other">
Other
</option>



</select>



</div>







{{-- Manuscript Count --}}


<div class="row">



<div class="col-md-4 mb-3">


<label class="fw-bold">
Number of Tables
</label>


<input type="number"
name="number_of_tables"
class="form-control"
value="{{ old('number_of_tables',0) }}"
min="0"
required>


</div>





<div class="col-md-4 mb-3">


<label class="fw-bold">
Number of Figures
</label>


<input type="number"
name="number_of_figures"
class="form-control"
value="{{ old('number_of_figures',0) }}"
min="0"
required>


</div>





<div class="col-md-4 mb-3">


<label class="fw-bold">
Number of References
</label>


<input type="number"
name="number_of_references"
class="form-control"
value="{{ old('number_of_references',0) }}"
min="0"
required>


</div>


</div>








{{-- Word Count --}}

<div class="mb-3">


<label>
Word Count
</label>


<input type="number"
name="word_count"
class="form-control"
value="{{ old('word_count') }}"
min="1">


</div>




<button type="submit"
class="btn btn-primary px-4">

Save & Continue

</button>




</div>

</div>



</form>


</div>


@endsection

@push('scripts')

<script>

document.addEventListener("DOMContentLoaded", function(){


    const editorElement = document.querySelector('#abstract');


    if(editorElement){


        ClassicEditor
        .create(editorElement, {

            toolbar: [
                'heading',
                '|',
                'bold',
                'italic',
                '|',
                'link',
                'bulletedList',
                'numberedList',
                '|',
                'blockQuote'
            ]

        })


        .then(editor=>{

            console.log("CKEditor Loaded Successfully");

        })


        .catch(error=>{

            console.error(error);

        });


    }


});


</script>

@endpush


@push('styles')

<style>

.ck-editor__editable {
    min-height: 250px;
}


.ck-editor {
    width: 100%;
}


</style>

@endpush