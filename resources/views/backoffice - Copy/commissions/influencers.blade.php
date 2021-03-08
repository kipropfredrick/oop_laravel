@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Influencer Commissions</strong></h6>
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
								<th class="thead">Total Cost</th>
                                <th class="thead">Commisssion</th>
                                <th class="thead">Influencer</th>
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
									<?php $commisionF = intval($commision->commission) ?>
									<td>KES {{number_format($commisionF)}}</td>
                                    <td>{{$commision->influencer->user->name}}</td>
                                </tr>
                            @endforeach
						</tbody>
					</table>
                </div>
             </div>
@endsection
