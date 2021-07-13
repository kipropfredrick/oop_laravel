@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>User Account Top-Ups</strong></h6>
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
        
		<div class="table-responsive padding">
        <table id="myTable" class="table table-bordered table-striped">
						<thead>
							<tr>
                                <th class="thead">No.</th>
								<th class="thead">Name</th>
								<th class="thead">Phone Number</th>
                                <th class="thead">Transaction Id</th>
                          
								<th class="thead">amount</th>
								
								<th class="thead">balance</th>
								
                                <th class="thead">Transaction Date</th>
                              
							</tr>
						</thead>
						<tbody>
						<?php $index = 0?>
                            @foreach($bookings as $booking) 
                                <tr>
                                    <td>{{$index = $index+1}}.</td>
									<td style="height: 1.5em; overflow: hidden;white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">{{$topups->user->name}}</td>
									
									<td>{{$topups->customer->phone}}}}</td>
									<td>{{$topups->transid}}</td>
									<td>KSh {{number_format(intval($topups->amount))}}</td>
								<td>KSh {{number_format(intval($topups->balance))}}</td>
									
									<td>{{date('M d'.', '.'Y : H:m:i', strtotime($booking->created_at))}}</td>
									
								
									</tr>
                            @endforeach
						</tbody>
					</table>
				</div>
                </div>
             </div>
@endsection
