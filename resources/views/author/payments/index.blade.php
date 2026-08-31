@extends('author.layouts.app')


@section('title','My Payments')


@section('content')


<div class="container-fluid py-4">


<h3 class="mb-4">
My Payments & Invoices
</h3>


<div class="card shadow-sm">


<div class="card-body">


<table class="table table-bordered">


<thead>

<tr>

<th>
Invoice No
</th>

<th>
Manuscript
</th>

<th>
Fee Type
</th>

<th>
Amount
</th>

<th>
Status
</th>

<th>
Action
</th>

</tr>

</thead>


<tbody>


@forelse($payments as $payment)


<tr>


<td>

{{ $payment->invoice_no }}

</td>


<td>

{{ $payment->manuscript->title }}

</td>


<td>

{{ $payment->fee_type }}

</td>


<td>

{{ number_format($payment->amount,2) }}

{{ $payment->currency }}

</td>


<td>

<span class="badge bg-warning">

{{ ucfirst($payment->payment_status) }}

</span>

</td>


<td>


<a href="{{route('author.payments.show',$payment->id)}}"

class="btn btn-sm btn-primary">

View

</a>


</td>


</tr>


@empty


<tr>

<td colspan="6"
class="text-center">

No Payment Found

</td>

</tr>


@endforelse


</tbody>


</table>


</div>


</div>


</div>


@endsection