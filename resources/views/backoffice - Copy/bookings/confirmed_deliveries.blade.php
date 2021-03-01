@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Confirmed Deliveries</strong></h6>
		</div>
		
		<div class="container">
		@if (session()->has('success'))

			<div class="alert alert-success fade show" role="alert">
				{{ session()->get('success') }}
			</div>

			@elseif (session()->has('error'))

				<div class="alert alert-danger fade show" role="alert">
					{{ session()->get('error') }}
				</div>

			@endif
		</div>
        
        <table id="myTable" class="table table-bordered table-striped">
						<thead>
							<tr>
                                <th class="thead">No.</th>
								<th class="thead">Product Name</th>
								<th class="thead">Booking Reference</th>
                                <th class="thead">Product Code</th>
								@if(auth()->user()->role !== 'influencer')
                                <th class="thead">Customer</th>
								<th class="thead">Agent</th>
								<th class="thead">Delivery Location</th>
                                <th class="thead">Phone Number</th><th class="thead">Item Cost</th>
								<th class="thead">Item Cost</th>
								<th class="thead">Shipping Cost</th>
								<th class="thead">Discount</th>
								<th class="thead">Total Price</th>
                                <th class="thead">Amount Paid</th>
                                <th class="thead">Balance</th>
                                <th class="thead">Booking Date</th>
                                <th class="thead">Due Date</th>
                                <th class="thead">Status</th>
								@else
								<th class="thead">Commission</th>
								@endif
							</tr>
						</thead>
						<tbody>
							<?php $index = 0?>
							@foreach($bookings as $booking)
                                <tr>
									<td>{{$index = $index+1}}.</td>
									<td style="height: 1.5em; overflow: hidden;white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">{{$booking->product->product_name}}</td>
									<td>{{$booking->booking_reference}}</td>
									<td>{{$booking->product->product_code}}</td>
									@if(auth()->user()->role !== 'influencer')
									<td>{{ucfirst($booking->customer->user->name)}}</td>
									<td>{{$booking->agent}}</td>
									<td>
										@if($booking->county !=null)
										 {{$booking->county->county_name}} County,{{$booking->location['town']}} Town ({{$booking->location['center_name']}})
										@elseif(isset($booking->zone))
										 {{$booking->zone->zone_name}} ({{$booking->dropoff['dropoff_name']}})
										@else
										 No Location
										@endif
									</td>
									<td>{{ucfirst($booking->customer->phone)}}</td>
									<td>Ksh {{number_format($booking->product->product_price)}}</td>
									<td>Ksh {{number_format($booking->shipping_cost)}}</td>
									<td>KSh {{number_format($booking->discount)}}</td>
									<td>KSh {{number_format($booking->total_cost)}}</td>
									<td>KSh {{number_format($booking->amount_paid)}}</td>
									<td>KSh {{number_format($booking->balance)}}</td>
									<td>{{date('M d'.', '.'Y', strtotime($booking->created_at))}}</td>
									<td>{{date('M d'.', '.'Y', strtotime($booking->due_date))}}</td>
									<td>Confirmed Delivery</td>
									@else
									<?php 
									 $commission =  ($booking->product->product_price - $booking->product->buying_price) * ($influencer->commission/100)
									?>
									<td>KES {{number_format($commission)}}</td>
									@endif
                                </tr>
                            @endforeach
						</tbody>
					</table>
                </div>
             </div>
@endsection
