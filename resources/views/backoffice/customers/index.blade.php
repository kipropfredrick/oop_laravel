@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>{{$title}}</strong></h6>
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
        
        <table style="margin-top:10px" id="table1" class="table table-bordered table-striped">
				<thead>
							<tr>
                                <th class="thead">No.</th>
								<th class="thead">Full Name</th>
								<th class="thead">Phone</th>
								<th>Bookings</th>

								<th class="thead">Date created</th>
								<th class="thead">Action/Booking Status</th>
								<th class="thead">Platform</th>
							</tr>
						</thead>
			<tbody>
						
			</tbody>
		</table>
	</div>
	</div>
@endsection

@section('extra-js')

<script>

$(document).ready(function() {

var url = window.location.href;

var t =  $('#table1').DataTable({
	processing: true,
	serverSide: true,
		ajax: {

            "url" : url,
         "type": "POST",
         "data" : {
            "users" : '{{$users}}',
            "type":'{{$type}}',
             "_token": "{{ csrf_token() }}",

          
        }},
	columns: [
		{data: "id",name:"customers.id"},
		{data: "user.name",name:"user.name"},
		{data: "phone",name:"customers.phone"},
		{data: "total",name:"customers.phone"},
		
		{data: "date",name:"customers.created_at"},
		{data: "status",name:"customers.phone"},
		{data: "user.platform",name:"user.platform"},
	],
});

t.on( 'draw.dt', function () {
var PageInfo = $('#table1').DataTable().page.info();
	 t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
		cell.innerHTML = i + 1 + PageInfo.start;
	} );
} );
} );

// function check_if_booking_exists(){

// 	url = '/api/check-booking-exists';

// }


</script>

@endsection
