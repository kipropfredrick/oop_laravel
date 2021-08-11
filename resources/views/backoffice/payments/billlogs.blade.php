@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Bill Payments Logs</strong></h6>
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
				<th class="thead">TransactionType</th>
				<th class="thead">TransID</th>
				<th class="thead">TransAmount</th>
				<th class="thead">BillRefNumber</th>
				<th class="thead">MSISDN</th>
				<th class="thead">OrgAccountBalance</th>
				<th class="thead">
				FirstName
				</th>

				<th class="thead">
				MiddleName
				</th>

				<th class="thead">
				LastName
				</th>

				<th class="thead">
					Status/Action
				</th>
				<th  class="thead">Date Paid</th>
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
		{data: "id",name:"billpayment_logs.id"},
		{
            data: "TransactionType",name:'billpayment_logs.TransactionType',
            render(data) {
                return `
                <div style="width:200px;" class="ellipsis">
                   ${data}
                </div>
                `;
            }
        },
		{
            data: "TransID",name:'billpayment_logs.TransID',
            render(data) {
                return `
                <div style="width:200px;" class="ellipsis">
                   ${data}
                </div>
                `;
            }
        },
		{
		  data: "TransAmount",name:'billpayment_logs.TransAmount',
		  render: (data) => 'Ksh. ' + numberFormat(data)
		},
		{data:'BillRefNumber',name:'billpayment_logs.BillRefNumber'},
		{
			data: "MSISDN",name:"billpayment_logs.MSISDN",
            render(data) {
                return `
                <div style="width:200px;" class="ellipsis">
                   ${data}
                </div>
                `;
            }
        },
		{
                data: "OrgAccountBalance",name:"billpayment_logs.OrgAccountBalance",
                render: (data) => 'Ksh. ' + numberFormat(data)
		},
		{data:'FirstName',name:'billpayment_logs.FirstName'},
		{data:'MiddleName',name:'billpayment_logs.MiddleName'},
		{data:'LastName',name:'billpayment_logs.LastName'},
		{
		data: 'billpayment_logs.status',
		"width": "400px",
		"render": function(data, type, full, meta){
			var status = full.status;
			
			if(status === "unverified"){
			return `
				<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#recordPaymentModal${full.id}">
					Record Payment
				</button>

				<!-- Modal -->
					<div class="modal fade" id="recordPaymentModal${full.id}" tabindex="-1" role="dialog" aria-labelledby="recordPaymentModal${full.id}Label" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="recordPaymentModal${full.id}Label">Record Payment</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form action="/admin/recordbill-payment/${full.id}" method="post">
							@csrf
							<div class="modal-body">
								<label for="">Amount</label>
									<input type="number" name="amount" readonly="readonly" value=${full.TransAmount} class="form-control">
								<label for="">Correct Account No.</label>
									<input type="" name="reference" required class="form-control">
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>

						</form>

						</div>
					</div>
					</div>

			`;
			}else{
				return `
					<div>${full.status}</div>
				`;
			}
			
		}
	},
		{
            data: "TransTime_f",name:'billpayment_logs.TransTime',
            render(data) {
                return `
                <div style="width:200px;" class="ellipsis">
                   ${data}
                </div>
                `;
            }
        },
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
