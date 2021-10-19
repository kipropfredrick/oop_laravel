@extends('backoffice.app')

@section('content')


<!-- Traffic sources -->
<div class="card p-3">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class=" text-center"><strong>Booking Details</strong></h6>
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

    <div class="row">
      <label class="col-12">Customer Details</label>
      <div class="col-sm-12 col-md-4 col-lg-4">

        <label>Customer Name</label>
        <p class="name">{{$user->name}}</p>
        
      </div>
       <div class="col-sm-12 col-md-4 col-lg-4">
        <label>Email</label>
        <p class="name">{{$user->email}}</p>
        
      </div>
      
           <div class="col-sm-12 col-md-4 col-lg-4">
        <label>Phone</label>
        <p class="name">{{$customer->phone}}</p>
        
      </div>
        
        
<br>
           <div class="col-sm-12 col-md-12 col-lg-12 row">
        <label class="col-12">Booking Details</label>
          <div class="col-sm-12 col-md-12 col-lg-12">
        <label>Booking Reference</label>
        <p class="name">{{$booking->booking_reference}}</p>
        
      </div>
         <div class="col-sm-12 col-md-6 col-lg-6">
        <label>Product Name</label>
        <p class="name">{{$product->product_name}}</p>
        
      </div>
       <div class="col-sm-12 col-md-6 col-lg-6">
        <label>Platform</label>
        <p class="name">{{$booking->platform}}</p>
        
      </div>

       <div class="col-sm-4 col-md-12 col-lg-4">
        <label>Item Cost</label>
        <p class="name">Ksh {{number_format($booking->item_cost)}}</p>
        
      </div>

          <div class="col-sm-4 col-md-12 col-lg-4">
        <label>Shipping cost </label>
        <p class="name">Ksh {{number_format($booking->shipping_cost)}}</p>
        
      </div>

      
          <div class="col-sm-4 col-md-12 col-lg-4">
        <label>Total cost</label>
        <p class="name">KSh {{number_format($booking->total_cost)}}</p>
        
      </div>
       <div class="col-sm-4 col-md-12 col-lg-4">
        <label>Amount Paid</label>
        <p class="name">KSh {{number_format($booking->amount_paid)}}</p>
        
      </div>
       <div class="col-sm-4 col-md-12 col-lg-4">
        <label>Balance</label>
        <p class="name">KSh {{number_format($booking->balance)}}</p>
        
      </div>
      <div class="col-sm-6 col-md-12 col-lg-6">
        <label>Booking date</label>
        <p class="name">{{date('M d'.', '.'Y', strtotime($booking->created_at))}}</p>
        
      </div>

      </div>
    </div>
        
      
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
  <style type="text/css">
    .name{
      font-weight: 1;
      font-size: 20px;
      color: black;
    }
    label{
      color: black;
      font-weight: bold;
      font-size: 18px;
    }
  </style>
@endsection

@section('extra-js')

<script>

$(document).ready(function() {

var url = window.location.href;

var t =  $('#table1').DataTable({
  processing: true,
  serverSide: true,
  order: [[ 0, "desc" ]],
  ajax: {

            "url" : url+"/payments",
         "type": "POST",
         "data" : {
            "_token": "{{ csrf_token() }}",
        }},
  columns: [
    {data: "id",name:"payments.id"},
    {
            data: "customer.user.name",name:'customer.user.name',
            render(data) {
                return `
                <div style="width:200px;" class="ellipsis">
                   ${data}
                </div>
                `;
            }
        },
    {
            data: "customer.phone",name:'customer.phone',
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
    {data:'mpesapayment.transac_code',name:'mpesapayment.transac_code'},
{data:'product.product_code',name:'product.product_code'},

    {
      data: "product.product_name",name:"product.product_name",
            render(data) {
                return `
                <div style="height: 1.5em; overflow: hidden;white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">
                   ${data}
                </div>
                `;
            }
        },
    {data:'booking.booking_reference',name:'booking.booking_reference'},
    {
      data: "booking.total_cost",name:'booking.total_cost',
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

//  url = '/api/check-booking-exists';

// }


</script>

@endsection

