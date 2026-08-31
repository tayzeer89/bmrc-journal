@extends('author.layouts.app')


@section('title','Payment Details')


@section('content')


<div class="container py-4">


<div class="card shadow">


<div class="card-header bg-primary text-white">

<h5>
Payment Invoice
</h5>

</div>



<div class="card-body">


<table class="table">


<tr>

<th>
Invoice No
</th>

<td>
{{$payment->invoice_no}}
</td>

</tr>


<tr>

<th>
Manuscript
</th>

<td>

{{$payment->manuscript->title}}

</td>

</tr>


<tr>

<th>
Amount
</th>

<td>

{{$payment->amount}}
{{$payment->currency}}

</td>

</tr>


<tr>

<th>
Status
</th>

<td>

{{$payment->payment_status}}

</td>

</tr>


</table>



@if($payment->payment_status=='pending')


<a href="#"
class="btn btn-success">

Pay Now

</a>


@endif


</div>


</div>


</div>


@endsection