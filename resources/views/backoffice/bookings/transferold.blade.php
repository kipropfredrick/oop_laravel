@extends('backoffice.app')

@section('content')
<link rel="stylesheet" href="{{asset('assets/css/pdfcsv.css')}}">

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Transfer Order</strong></h6>
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
								<th class="thead">Product Name</th>
								<th class="thead">Booking Reference</th>
                                <th class="thead">Product Code</th>
                                <th class="thead">Customer</th>
								@if(auth()->user()->role !== 'vendor')
								<th class="thead">Vendor</th>
								@endif
								<th class="thead">Delivery Location</th>
								@if(auth()->user()->role !== 'vendor')
                                <th class="thead">Phone Number</th>
								@endif
                                <th class="thead">Item Cost</th>
								<th class="thead">Shipping Cost</th>
								<th class="thead">Discount</th>
								<th class="thead">Total Price</th>
                                <th class="thead">Amount Paid</th>
                                <th class="thead">Balance</th>
                                <th class="thead">Booking Date</th>
                                <th class="thead">Due Date</th>
                                <th class="thead">Progress</th>
                                <th class="thead">Status</th>
								<th class="thead">Action</th>
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
									<td>
									@if(isset($booking->customer->user))
									 {{ucfirst($booking->customer->user->name)}}
									@endif
									</td>
									@if(auth()->user()->role !== 'vendor')
									<td>{{ucfirst($booking->agent)}}</td>
									@endif
									<td>
										@if($booking->county !=null)
										{{$booking->county->county_name}} County,{{$booking->location['town']}} @if(isset($booking->location['center_name'])) Town ({{$booking->location['center_name']}}) @else {{ $booking->exact_location}} @endif
										@elseif(isset($booking->zone))
										 {{$booking->zone->zone_name}} ({{$booking->dropoff['dropoff_name']}})
										@else
										 No Location
										@endif
									</td>
									@if(auth()->user()->role !== 'vendor')
									<td>
									 @if(isset($booking->customer))
									{{ucfirst($booking->customer->phone)}}
									@endif
									@endif
									</td>
									<td>Ksh {{number_format($booking->item_cost ?:$booking->product->product_price)}}</td>
									<td>Ksh {{number_format($booking->shipping_cost)}}</td>
									<td>KSh {{number_format($booking->discount)}}</td>
									<td>KSh {{number_format($booking->total_cost)}}</td>
									<td>KSh {{number_format($booking->amount_paid)}}</td>
									<td>KSh {{number_format($booking->balance)}}</td>
									<td>{{date('M d'.', '.'Y', strtotime($booking->created_at))}}</td>
									</td>
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
									<td>
										@if(auth()->user()->role == "agent")
										<a class="btn btn-outline-primary" data-toggle="modal" data-target="#transferorder{{$booking->id}}">Transfer</a>
										@elseif(auth()->user()->role == "admin")
										<a class="btn btn-outline-primary"data-toggle="modal" data-target="#transferorder{{$booking->id}}">Transfer</a>
										@elseif(auth()->user()->role == "vendor")
										<a class="btn btn-outline-primary" data-toggle="modal" data-target="#transferorder{{$booking->id}}">Transfer</a>
										@endif
									</td>
                                </tr>



							<!-- Modal -->
						<div class="modal fade"  id="transferorder{{$booking->id}}" tabindex="-1" role="dialog" aria-labelledby="transferorderLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
							<h5 style="padding:10px !important;" class="text-center"><strong>Transfer An Item</strong></h5>
							<div class="modal-header">
								<h5 class="modal-title" id="transferorderLabel">{{$booking->product->product_name}} ({{$booking->product->product_code}})</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
							</div>
							@if(auth()->user()->role == "agent")
							<form action="/agent/transfer-order/{{$booking->id}}" method="post">
							@elseif(auth()->user()->role == "admin")
							<form action="/admin/transfer-order/{{$booking->id}}" method="post">
							@elseif(auth()->user()->role == "vendor")
							<form action="/vendor/transfer-order/{{$booking->id}}" method="post">
							@endif

							@csrf
							<div class="modal-body">
								<label for="product_code">New Product Code</label>
								<input class="form-control" name="product_code" type="" required>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
							
							</form>

							</div>

                            @endforeach
							
						</tbody>
						
					</table>
						</div>
						</div>
						</div>

					</div>
				</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                dom: "Blfrtip",
                buttons: [
                    {
                        text: 'csv',
                        extend: 'csvHtml5',
                        title: "Transfer",
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },
                    {
                        text: 'excel',
                        extend: 'excelHtml5',
                        title: "Transfer  Bookings",
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },
                    {
                        text: 'pdf',
                        extend: 'pdfHtml5',
                        title: "Transfer Bookings",
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },
                    {
                        text: 'print',
                        extend: 'print',
                        title: "Transfer Bookings",
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },

                ],
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }]
            });
        });
    </script>