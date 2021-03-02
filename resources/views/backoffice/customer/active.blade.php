@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Active Bookings</strong></h6>
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
        
        <div class="padding">
		
		<table id="myTable" class="table table-bordered table-striped">
						<thead>
							<tr>
                                <th class="thead">No.</th>
								<th class="thead">Product Name</th>
								<th class="thead">Booking Reference</th>
                                <th class="thead">Product Code</th>
                                <th class="thead">Customer</th>
                                <th class="thead">Phone Number</th>
                                <th class="thead">Booking Price</th>
                                <th class="thead">Amount Paid</th>
                                <th class="thead">Balance</th>
                                <th class="thead">Booking Date</th>
                                <th class="thead">Due Date</th>
                                <th class="thead">Progress</th>
                                <th class="thead">Status</th>
								<!-- <th class="text-center thead">Actions</th> -->
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
									<td>{{ucfirst($booking->customer->user->name)}}</td>
									<td>{{ucfirst($booking->customer->phone)}}</td>
									<td>KSh {{number_format($booking->total_cost)}}</td>
									<td>KSh {{number_format($booking->amount_paid)}}</td>
									<td>KSh {{number_format($booking->balance)}}</td>
									<td>{{date('M d'.', '.'Y', strtotime($booking->created_at))}}</td>
									<td>{{date('M d'.', '.'Y', strtotime($booking->due_date))}}</td>
									<td>
									<div class="progress">
										<div class="progress-bar" role="progressbar" aria-valuenow="{{$booking->progress}}"
										aria-valuemin="0" aria-valuemax="100" style="width:{{$booking->progress}}%">
										  {{$booking->progress}}%
										</div>
									</div>
									</td>
									<td>{{$booking->status}}</td>
									<!-- <td><a class="btn btn-outline-danger" href="/admin/revoke-booking/{{$booking->id}}" onclick="return confirm('Are you sure you want to revoke this booking?') ? true : false">Revoke</a></td> -->
                                </tr>
                            @endforeach
						</tbody>
					</table>

		</div>
                </div>
             </div>
@endsection
