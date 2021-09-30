@extends('backoffice.app')

@section('content')


<div class="card p-10">
  <h2>Dynamic Tabs</h2>
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#mobile">Mobile Payments </a></li>
    <li><a data-toggle="tab" href="#bank">Bank Payments</a></li>
 
  </ul>

  <div class="tab-content">
    <div id="mobile" class="tab-pane fade in active">
      <h3>Mobile</h3>
     <table class="table-striped table-bordered">
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

     		<tbody>
     			@foreach($mobile as $value)
<td>{{$value->created_at}}</td>
<td>{{$value->booking_id}}</td>
<td>Ksh. {{number_format($value->amount)}}</td>
     			@endforeach
     		</tbody>

     	</tr>
     </table>
    </div>
    <div id="bank" class="tab-pane fade">
      <h3>Bank</h3>
      <table class="table-striped table-bordered">
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

     		<tbody>
     			@foreach($bank as $value)
<td>{{$value->created_at}}</td>
<td>{{$value->booking_id}}</td>
<td>Ksh. {{number_format($value->amount)}}</td>
     			@endforeach
     		</tbody>

     	</tr>
     </table>
    </div>

  </div>
</div>


@endsection()