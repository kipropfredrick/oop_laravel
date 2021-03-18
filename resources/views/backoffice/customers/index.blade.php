@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Customers</strong></h6>
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
								<th class="thead">Full Name</th>
								<th class="thead">Phone</th>
								<th>Bookings</th>
								<th class="thead">Date created</th>
								<th>Action/Booking Status</th>
							</tr>
						</thead>

						<tbody>
							<?php $index = 0?>
							@foreach($customers as $customer)
							<tr>
								<td>{{ $index = $index + 1}}.</td>
								<td>{{$customer->name}}</td>
								<td>{{$customer->phone}}</td>
								<td>{{number_format($customer->bookingsCount)}}</td>
								<td>{{date('M d'.', '.'Y', strtotime($customer->created_at))}}</td>
								<td>
									@if($customer->bookingsCount == 0)
										<a class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this customer?') ? true : false" href="/admin/delete-customer/{{$customer->customer_id}}"><i class="fa fa-trash"></i> Delete</a>
									@else
									<h6 style="text-transform: uppercase">{{$customer->booking_status}}</h6>
									@endif
								</td>
							</tr>
							@endforeach
						</tbody>
						
					</table>
                </div>
             </div>
@endsection
