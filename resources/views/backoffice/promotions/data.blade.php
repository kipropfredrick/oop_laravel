@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Promotions</strong></h6>
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
								<th class="thead">Date</th>
                                <th class="thead">Booking Reference</th>
								<th class="thead">Name</th>
                                <th class="thead">Phone number </th>
                                <th class="thead">Amount paid</th>
                                <th class="thead">Discount</th>

							</tr>
						</thead>
						<tbody>
							<?php $index = 0?>
                            @foreach($users as $user)
                            <tr>
                            <td>{{$index = $index+1}}.</td>
                            <td>{{date('M d'.', '.'Y', strtotime($user->discounted_at))}} </td>
                            <td>{{$user->booking_reference}}</td>
                            <td>{{$user->customer->user->name}}</td>
                            <td>{{$user->customer->phone}}</td>
                            <td>{{$user->totalpaid}}</td>
                            <td>{{$user->discount}}</td>
                            </tr>
                            @endforeach

						</tbody>
					</table>
				</div>

		</div>


		</div>
		</div>
@endsection
