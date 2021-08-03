@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Aggregate Bookings</strong></h6>
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
		<form action="{{route('admin.agbookings')}}" >
		<div class="container row mt-2">
			<div class="col-2">
				<label>Selected Year </label>
				<select class="form-=control" name="year">
					<option value="2020" @if($year==2020 ) selected @endif>
						2020
					</option>
					<option value="2021" @if($year==2021 ) selected @endif>
						2021
					</option>
					<option value="2022" @if($year==2022 ) selected @endif>
						2022
					</option>
					
					<option value="2023" @if($year==2023 ) selected @endif>
						2023
					</option>
					<option value="2024" @if($year==2024 ) selected @endif>
						2024
					</option>
					<option value="2025" @if($year==2025 ) selected @endif>
						2025
					</option>
				</select>
			</div>

			<div class="col-3">
				<label>Selected Month </label>
				<select class="form-=control" name="month">
					<option value="1" @if($month==1 ) selected @endif>
						January
					</option>
					<option value="2" @if($month==2 ) selected @endif>
						February
					</option>
					<option value="3" @if($month==3 ) selected @endif>
						March
					</option>
					
					<option value="4" @if($month==4 ) selected @endif>
						April
					</option>
					<option value="5" @if($month==5 ) selected @endif>
						May
					</option>
					<option value="6" @if($month==6 ) selected @endif>
						June
					</option>
					<option value="7" @if($month==7 ) selected @endif>
						July
					</option>
					<option value="8" @if($month==8 ) selected @endif>
						August
					</option>
					<option value="9" @if($month==9 ) selected @endif>
						September
					</option>
					<option value="10" @if($month==10 ) selected @endif>
						October
					</option>
					<option value="11" @if($month==11 ) selected @endif>
						November
					</option>
					<option value="12" @if($month==12 ) selected @endif>
						December
					</option>
				</select>
		
		</div>
<button  type="submit" class="bn">
	<i class="fa fa-filter"></i> Filter
</button>

<style type="text/css">
	.bn {
  background: none!important;
  border: none;
  padding: 0!important;
  /*optional*/
  font-family: arial, sans-serif;
  /*input has OS specific font-family*/
  color: #069;
  cursor: pointer;
}
</style>
		</div>
	</form>
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
