@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Payments</strong></h6>
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
								<th class="thead">Transaction Amount</th>
								<th class="thead">Transaction Code</th>
								<th class="thead">Product Code</th>
								<th class="thead">Product Name</th>
								<th class="thead">Booking Reference</th>
								<th class="thead">Booking Price</th>
                                <th class="thead">Date Paid</th>
							</tr>
						</thead>
						<tbody>
						<?php $index=0; ?>
                            @foreach($payments as $payment)
                                <tr>
								<td>
									{{$index = $index+1}}.
									</td>
									<td>
									 @if(isset($payment->customer))
									 {{ucfirst($payment->customer->user->name)}}
									 @endif
									</td>
									<td>
									@if(isset($payment->customer))
										{{$payment->customer->phone}}
									@endif
									</td>
									
									<td>
										KSh {{number_format($payment->transaction_amount)}}
									</td>

									<td>
									@if(isset($payment->mpesapayment->transac_code))
									{{$payment->mpesapayment->transac_code}}
									@endif
									</td>

									<td>
										{{$payment->product->product_code}}
									</td>
									<td style="height: 1.5em; overflow: hidden;white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">{{$payment->product->product_name}}</td>
									
									<td>
									@if(isset($payment->booking))
										{{$payment->booking->booking_reference}}
									@endif
									</td>
									
									<td>
									@if(isset($payment->booking))
										KSh {{number_format($payment->booking->total_cost)}}
									@endif
									</td>
									<td>
									{{date('M d'.', '.'Y', strtotime($payment->created_at))}}
									</td>
                                </tr>
                            @endforeach
						</tbody>
					</table>
                </div>
             </div>
@endsection
