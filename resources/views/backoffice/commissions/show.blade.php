@extends('backoffice.app')

@section('content')


<div class="card p-10">
  <h2>Order Transaction History</h2>
  <ul class="nav nav-tabs">
    <li class="btn btn-default active"><a data-toggle="tab" href="#mobile">Mobile Payments </a></li>
    <li class="btn btn-default"><a data-toggle="tab" href="#bank">Bank Payments</a></li>
 
  </ul>

  <div class="tab-content">
    <div id="mobile" class="tab-pane  in active">
      <h3>Mobile</h3>
     <table class="table-striped table">
     	<tr>
     		<th class="thead"> 
     			Date 
     		</th>
     		<th class="thead"> 
     			Booking reference 
     		</th>
     		<th class="thead"> 
     			Amount
     		</th>
</tr>
     		<tbody>
     			@foreach($mobile as $value)
     			<tr>
<td>{{$value->created_at}}</td>
<td>{{$value->booking_id}}</td>
<td>Ksh. {{number_format($value->amount)}}</td>
</tr>
     			@endforeach

     		</tbody>

     	
     </table>
    </div>
    <div id="bank" class="tab-pane fade">
      <h3>Bank</h3>
      <table class="table-striped table">
     	<tr>
     		<th class="thead"> 
     			Date 
     		</th>
     		<th class="thead"> 
     			Booking reference 
     		</th>
     		<th class="thead"> 
     			Amount
     		</th>
</tr>
     		<tbody>
     			@foreach($bank as $value)
     			<tr>
<td>{{$value->created_at}}</td>
<td>{{$value->booking_id}}</td>
<td>Ksh. {{number_format($value->amount)}}</td>
</tr>
     			@endforeach
     		</tbody>

     </table>
    </div>

  </div>
</div>
<style type="text/css">
	li.active{
		background: green;
		color: white;
	}
	li.active a{
		color: white;
	}
</style>

@endsection()