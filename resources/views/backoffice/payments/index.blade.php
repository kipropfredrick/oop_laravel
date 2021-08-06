@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Payments Logs</strong></h6>
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
	ajax: url,
	columns: [
		{data: "id",name:"payments.id"},
		{
            data: "customer.user.name",name:'payments.customer.user.name',
            render(data) {
                return `
                <div style="width:200px;" class="ellipsis">
                   ${data}
                </div>
                `;
            }
        },
		{
            data: "customer.phone",name:'payments.customer.phone',
            render(data) {
                return `
                <div style="width:200px;" class="ellipsis">
                   ${data}
                </div>
                `;
            }
        },
		{
		  data: "transaction_amount",name:'payments.transaction_amount',
		  render: (data) => 'Ksh. ' + numberFormat(data)
		},
		{data:'mpesapayment.transac_code',name:'payments.mpesapayment.transac_code'},
{data:'product.product_code',name:'payments.product.product_code'},

		{
			data: "product.product_name",name:"payments.product.product_name",
            render(data) {
                return `
                <div style="height: 1.5em; overflow: hidden;white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">
                   ${data}
                </div>
                `;
            }
        },
		{data:'booking.booking_reference',name:'payments.booking.booking_reference'},
		{
		  data: "booking.total_cost",name:'payments.booking.total_cost',
		  render: (data) => 'Ksh. ' + numberFormat(data)
		},
		{data:'created_at',name:'payments.created_at'},
		
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
