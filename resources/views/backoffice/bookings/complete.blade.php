@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Complete Bookings</strong></h6>
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
								<th class="thead">Product Name</th>
								<th class="thead">Booking Reference</th>
                                <th class="thead">Product Code</th>
                                @if(auth()->user()->role !== 'influencer')
								<th class="thead">Customer</th>
								@if(auth()->user()->role !== 'vendor')
								<th class="thead">Vendor</th>
								@endif
								<th class="thead">Delivery Location</th>
								@if(auth()->user()->role !== 'vendor')
                                <th class="thead">Phone Number</th>
								@endif
								<th class="thead">Item Cost</th>
								<th class="thead">Shipping Cost</th>
								<th class="thead">Discount</th>
								<th class="thead">Total Price</th>
                                <th class="thead">Amount Paid</th>
                                <th class="thead">Balance</th>
                                  <th class="thead">Sent to Wallet</th>
                                <th class="thead">Booking Date</th>
                                <th class="thead">Due Date</th>
                                 <th class="thead">Count</th>
                                <th class="thead">Progress</th>
                                <th class="thead">Platform</th>
                                <th class="thead">Status</th>
								<!-- <th class="text-center thead">Actions</th> -->
								<th class="thead">Date Completed</th>
								@else
								<th class="thead">Commission</th>
								@endif
								@if(auth()->user()->role=="agent")
								<th class="thead">Action</th>
								@endif
								 <th style="width: 400px;">View</th>

							
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
	searching:true,
	order: [[ 15, "desc" ]],
	ajax: url,
	columns: [
		{data: "id",name:"bookings.id"},
{data: "product.product_name",name:"product.product_name",  render(data) {
                return `
                <div style="height: 1.5em; overflow: hidden;white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">
                   ${data}
                </div>
                `;
            }},
            {
            	data:"booking_reference",name:"bookings.booking_reference"
            },
               {
            	data:"product.product_code",name:"product.product_code"
            },
             {
            	data:"customer.user.name",name:"customer.user.name"
            },
             {
            	data:"vendor.user.name","render": function(data, type, full, meta){
            		var agent=JSON.stringify(full);
            		 if(full.vendor_code !== null){
              

                              return ` ${data}(Vendor)
 `;

            }else{
           
               return `Lipa Mos Mos (Admin)
 `;
            } 


            	}
            },

            {
                data:"id","render": function(data, type, full, meta){
                    var agent=JSON.stringify(full);
                    var s="";
                    var point="";
                     if(full.county !== null){

                         point=full.county.county_name+" County,";

                        if (full.location!=null) {
                             s=full.location['town']+" Town "+full.location['center_name'];
                        }
                        else{
                             s=" "+full.exact_location;
                        }
                        point=point+s;
              

    

            }else if(full.zone!=null){
           
   point=full.zone.name+"("+full.dropoff['dropoff_name']+")";
            }
            else{
                point="No location";
            } 
                                      return ` ${point}
 `;


                }
            },


       
             {
            	data:"customer.phone","render": function(data, type, full, meta){
            		var agent=full;
return `
	@if(auth()->user()->role !== 'vendor')
									<td>${agent.customer.phone}</td>
									@endif  `;

            	}
            },
          {
		  data: "item_cost",name:'bookings.item_cost',
		  "width": "400px",
		  render: (data) => 'Ksh. ' + numberFormat(data)
		},
          
          {
		  data: "shipping_cost",name:'bookings.shipping_cost',
		  "width": "400px",
		  render: (data) => 'Ksh. ' + numberFormat(data)
		},
		
          {
		  data: "discount",name:'bookings.discount',
		  "width": "400px",
		  render: (data) => 'Ksh. ' + numberFormat(data)
		},
		
          {
		  data: "total_cost",name:'bookings.total_cost',
		  "width": "400px",
		  render: (data) => 'Ksh. ' + numberFormat(data)
		},
		
          {
		  data: "amount_paid",name:'bookings.amount_paid',
		  "width": "400px",
		  render: (data) => 'Ksh. ' + numberFormat(data)
		},
		{
		  data: "balance",name:'bookings.balance',
		  "width": "400px",
		  render: (data) => 'Ksh. ' + numberFormat(data)
		},{
		data:"balance","render": function(data, type, full, meta){
            		var totalbalance=full.amount_paid-full.total_cost;
            	
return 'Ksh. ' + numberFormat(totalbalance);

            	}
            },{
		data:"created_at","render": function(data, type, full, meta){
            		var agent=full;
            		var strdate = new Date(data);
var date = moment(strdate).format('DD.MM.YYYY');
 //07.02.2017
return `
${date}
								 `;

            	}
            }
            ,{
		data:"due_date","render": function(data, type, full, meta){
            		var agent=full;
            		var strdate = new Date(data);
var date = moment(strdate).format('DD.MM.YYYY');
 //07.02.2017
return `
${date}
								 `;

            	}
            },
            {
		  data: "payments_count",name:'due_date',
		  "width": "400px",
		  render: (data) => numberFormat(data)
		}

                ,{
		data:"due_date","render": function(data, type, full, meta){
            	var dd=full;

            	var progress= Math.round((full.amount_paid/full.total_cost)*100);
 //07.02.2017
return `
<div class="progress">
										<div class="progress-bar" role="progressbar" aria-valuenow="${progress}"
										aria-valuemin="0" aria-valuemax="100" style="width:${progress}%">
										  ${progress}%
										</div>
									</div>

								 `;

            	}
            },

              {
            	data:"platform",name:"bookings.platform"
            },
             {
            	data:"status",name:"bookings.status"
            },
            {
		data:"updated_at","render": function(data, type, full, meta){
            		var agent=full;
            		var strdate = new Date(data);
var date = moment(strdate).format('DD.MM.YYYY');
 //07.02.2017
return `
${date}
								 `;

            	}
            },
            {
        data:"id","width": "400px","render": function(data, type, full, meta){
       
return `<div><a class="btn btn-outline-success mt-1" href="/admin/view-booking/${data}"><i class="fa fa-eye"> </i></a></div>

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
