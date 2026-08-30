@extends('author.layouts.app')


@section('content')


<div class="container-fluid">


<h3 class="mb-4">
Declaration & Submission Checklist
</h3>



<form method="POST"
action="{{route(
'author.submission.step12.store',
$manuscript->id
)}}">


@csrf



@php

$list=[

'original_manuscript'=>
'The manuscript is original.',


'not_published_elsewhere'=>
'The manuscript has not been published elsewhere.',


'not_under_consideration_elsewhere'=>
'The manuscript is not under consideration by another journal.',


'authors_approved'=>
'All authors have approved the manuscript.',


'author_order_approved'=>
'Author order has been approved by all authors.',


'ethics_information_provided'=>
'Ethical approval information has been provided.',


'consent_information_provided'=>
'Informed consent information has been provided.',


'funding_declared'=>
'Funding information has been declared.',


'coi_declared'=>
'Conflict of interest has been declared.',


'journal_guidelines_followed'=>
'BMRC journal guidelines have been followed.',


'references_checked'=>
'References and citations have been checked.',


'tables_figures_checked'=>
'Tables and figures are correctly identified.',


'required_files_uploaded'=>
'Required files have been uploaded.',


'corresponding_author_authorized'=>
'Corresponding author is authorized.',


'publication_policy_agreed'=>
'I agree to publication and editorial policies.'

];


@endphp





@foreach($list as $field=>$text)


<div class="form-check mb-3">


<input

class="form-check-input"

type="checkbox"

name="{{$field}}"

value="1"

required>


<label class="form-check-label">

{{$text}}

</label>


</div>



@endforeach





<button class="btn btn-primary">

Continue to Submission Confirmation

</button>



</form>


</div>


@endsection