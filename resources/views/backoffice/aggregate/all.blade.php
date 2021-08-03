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
		<div class="container row mt-2">
			<div class="col-2">
				<label>Selected Year </label>
				<select class="form-=control">
					<option value="2020">
						2020
					</option>
					<option value="2021">
						2021
					</option>
					<option value="2022">
						2022
					</option>
					
					<option value="2023">
						2023
					</option>
					<option value="2024">
						2024
					</option>
					<option value="2025">
						2025
					</option>
				</select>
			</div>

			<div class="col-3">
				<label>Selected Month </label>
				<select class="form-=control">
					<option value="1">
						January
					</option>
					<option value="2">
						February
					</option>
					<option value="3">
						March
					</option>
					
					<option value="4">
						April
					</option>
					<option value="5">
						May
					</option>
					<option value="6">
						June
					</option>
					<option value="7">
						July
					</option>
					<option value="8">
						August
					</option>
					<option value="9">
						September
					</option>
					<option value="10">
						October
					</option>
					<option value="11">
						November
					</option>
					<option value="12">
						December
					</option>
				</select>
		
		</div>
<a href="" class="">
	<i class="fa fa-filter"></i> Filter
</a>
		</div>
		<hr>
        
        
			
        <table id="myTable" class="table table-bordered table-striped">
						<thead>
							<tr>
                                <th class="thead">No.</th>
								<th class="thead">Date</th>
								<th class="thead">Total Amount</th>
								<th class="thead">Unique Accounts</th>
								
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
									{{date('M d'.', '.'Y', strtotime($payment['date']))}}
									</td>
									<td>
										KSh {{number_format($payment['total'])}}
									</td>
									<td>
											{{$payment['unique']}}
									</td>


								
                                </tr>
                            @endforeach
						</tbody>
					</table>
                </div>
             </div>
@endsection
