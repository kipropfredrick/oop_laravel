@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>{{$status}}</strong></h6>
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
                                <th class="thead">Product Code</th>
                                <th class="thead">Item Price</th>
                                <th>Weight</th>
                                <th class="thead">Vendor</th>
                                <th class="text-center thead">Actions</th>
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
    order: [[ 0, "desc" ]],
	ajax: url,
	columns: [
		{data: "id",name:"products.id"},
        {data: "product_name",name:"products.product_name"},
        {data: "product_code",name:"products.product_code"},
         {
          data: "product_price",name:'products.product_price',
          
          render: (data) => 'Ksh. ' + numberFormat(data)
        },
        {data: "weight",name:"products.weight"},
        {data: "vendor.user.name",name:"vendor.user.name"},
        {
                data:"status","render": function(data, type, full, meta){
                    var agent=data;
var approved="";
                       if(data !== "approved"){
                                            approved= `<a data-toggle="tooltip" title="Approve Product" href="/admin/vendor-product-approve/${full.id}" class="btn mr-2 btn-outline-success"><i class="fa fa-check"></i></a>`;}
  var rejected="";
                       if(data !== "rejected"){
                                            rejected= `<a data-toggle="tooltip" onclick="return confirm('Are you sure you want to reject this product?')" title="Reject Product" href="/admin/vendor-product-reject/${full.id}" class="btn mr-2 btn-outline-danger"><i class="fa fa-thumbs-down"></i></a>`;} 
 var torejected="";
                       if(data == "rejected"){
                                           torejected= `  <a data-toggle="tooltip" onclick="return confirm('Are you sure you want to delete this product?')" title="Delete Product" href="/admin/vendor-product-delete/${full.id}" class="btn mr-2 btn-outline-danger"><i class="fa fa-trash"></i></a>`;}                                                                        
return `
   <div class="row">
                                        <a data-toggle="tooltip" title="Edit Product" href="/admin/vendor/product-view/${full.id}" class="btn mr-2 btn-outline-primary"><i class="fa fa-edit"></i></a>`+approved+rejected+torejected+`
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
