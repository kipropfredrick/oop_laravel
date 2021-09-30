@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Commissions</strong></h6>
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
								<th class="thead">Booking Reference</th>
                                <th class="thead">Product Code</th>
								<th class="thead">Item Cost</th>
								<th class="thead">Mobile transactions</th>
								<th class="thead">Bank transactions</th>
								<th class="thead">Mobile unit</th>
								<th class="thead">Bank unit</th>
								<th class="thead">Mobile Total</th>
								<th class="thead">Bank Total</th>
                                <th class="thead">Admin's Commisssion</th>
                                <th class="thead">Agent/Vendor</th>
                                <th class="thead">Vendor payout</th>
                                    <th class="thead">Action</th>
								<!-- <th class="thead">Completed On</th> -->
							</tr>
						</thead>
						<tbody>
						<?php $index = 0?>
                            @foreach($commissions as $commision) 
                                <tr>
                                    <td>{{$index = $index+1}}.</td>
									<td>{{$commision->booking->booking_reference}}</td>
                                    <td style="height: 1.5em; overflow: hidden;white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">{{$commision->product->product_code}}</td>
									<td>KES {{number_format($commision->booking->total_cost)}}</td>
									<td>{{$commision->countmobiletransfers}}</td>
									<td>{{$commision->countbanktransfers}}</td>
									<td>{{$commision->vendor->fixed_mobile_money}}</td>
									<td>{{$commision->vendor->fixed_bank}}</td>
										<td>{{$commision->totalmobiletransfers}}</td>
									<td>{{$commision->totalbanktransfers}}</td>
									

									<td>KES {{number_format($commision->commission)}}</td>
                                    <td>
                                    @if($commision->vendor_id !==null)
										@if(isset($commision->vendor->user))
										{{$commision->vendor->user->name}}
										@else
										Vendor name
										@endif
                                    @else
									@if(isset($commision->agent->user))
                                    {{$commision->agent->user->name}}
									@else
										Agent name
										@endif
                                    @endif
                                    </td>
                                    <td>KES {{number_format($commision->vendor_payout)}}</td>
                                    <th><a href="show-commissions/{{$commision->booking->id}}">View</a></th>

									<!-- <td>{{date('M d'.', '.'Y', strtotime($commision->created_at))}}</td> -->
                                </tr>
                            @endforeach
						</tbody>
					</table>
                </div>
             </div>
@endsection
