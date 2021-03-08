@extends('backoffice.app')

@section('content')

<style>
div.scrollmenu {
  background-color: #324148;
  overflow: auto;
  white-space: nowrap;
}

div.scrollmenu a {
  display: inline-block;
  color: white;
  text-align: center;
  padding: 14px;
  text-decoration: none;
}

div.scrollmenu a:hover {
  background-color: #202B30;
}
</style>

<!-- Traffic sources -->


<div class="card">
		<div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Influencer Commission History</strong></h6>
			<a data-toggle="modal" data-target="#payModal" class="btn btn-outline-primary" href="#" >Record Payment</a>
		</div>

	
<!-- Modal -->
<div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="payModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="payModalLabel">Pay  {{$influencer->user->name}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="/admin/record-influencer-payment" method="post">
		@csrf
		<div class="modal-body">
				<input type="number" name="amount_paid" class="form-control" placeholder="Enter amount" required>
				<input type="hidden" name="influencer_id" value="{{$influencer->id}}">
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary">Pay</button>
		</div>
	  </form>
    </div>
  </div>
</div>

        <div class="card-body py-0">
            <div class="row">
                <div class="col-sm-4">
                    <div class="d-flex align-items-center justify-content-center mb-2 quick-links">
                        
                        <div>
                            <div class="font-weight-semibold">Total Commission : <span><small>KES {{number_format($influencer->commission_totals->total_commission)}}</small></span></div>
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="col-sm-4">
                    <div class="d-flex align-items-center justify-content-center mb-2 quick-links">
						<div>
                            <div class="font-weight-semibold">Commission Paid : <span><small>KES {{number_format($influencer->commission_totals->commission_paid)}}</small></span></div>
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="col-sm-4">
                    <div class="d-flex align-items-center justify-content-center mb-2 quick-links">
						<div>
                            <div class="font-weight-semibold">Pending Payment : <span><small>KES {{number_format($influencer->commission_totals->pending_payment)}}</small></span></div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    </div>
<!-- /traffic sources -->

<div class="card">
<div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Influencer Bookings</strong></h6>
</div>
<div class="table-responsive padding">
        
		<div class="scrollmenu">
			<a target="_blank" href="/admin/influencer/products-report/{{$influencer->id}}">Products</a>
			<a target="_blank" href="/admin/influencer/active-bookings-report/{{$influencer->id}}">Active Bookings</a>
			<a target="_blank" href="/admin/influencer/complete-bookings-report/{{$influencer->id}}">Complete Bookings</a>
			<a target="_blank" href="/admin/influencer/pending-bookings-report/{{$influencer->id}}">Pending Bookings</a>
			<a target="_blank" href="/admin/influencer/unserviced-bookings-report/{{$influencer->id}}">Unserviced Bookings</a>
			<a target="_blank" href="/admin/influencer/overdue-bookings-report/{{$influencer->id}}">Overdue Bookings</a>
			<a target="_blank" href="/admin/influencer/delivered-bookings-report/{{$influencer->id}}">Delivered Bookings</a>
			<a target="_blank" href="/admin/influencer/confirmed-deliveries-report/{{$influencer->id}}">Confirmed Deliveries</a>
		</div>
		
		<div class="container" style="margin-top:20px">
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
		
		<div style="margin-bottom:20px" class="container">
			<form action="/admin/influencer_save" method="post">

			@csrf

			<div class="form-group row">
				<label class="col-form-label col-lg-2">Full Name</label>
				<div class="col-lg-10">
					<input  value="{{$influencer->user->name}}" disabled tclass="form-control" name="name" placeholder="Enter full name" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
				
					@error('name')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
					@enderror

					</div>
				</div>

				<div class="form-group row">
					<label class="col-form-label col-lg-2">Phone</label>
					<div class="col-lg-10">
						<input disabled value="{{$influencer->phone}}" minLegth="10" maxLegth="10" tclass="form-control" name="phone" placeholder="07XXXXXXXX" type="number" class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
						@error('phone')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror

				</div>
				</div>

				<div class="form-group row">
					<label class="col-form-label col-lg-2">Store Name</label>
					<div class="col-lg-10">
						<input disabled value="{{$influencer->store_name}}"  class="form-control" name="store_name" placeholder="Business Name"  class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
						@error('store_name')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror

				</div>
				</div>


				<div class="form-group row">
				<label class="col-form-label col-lg-2">Email</label>
				<div class="col-lg-10">
					<input disabled value="{{$influencer->user->email}}" tclass="form-control" name="email" placeholder="Enter full name" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
				
					@error('email')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
					@enderror

					</div>
				</div>



			</form>

        </div>

        
                </div>

				<div class="card-header header-elements-inline row">
							<h6 style="color: #005b77;" class="card-title"><strong>Influencer Products</strong></h6>
				</div>
				
				<div class="table-responsive padding">

			<table class="table datatable-basic  table-striped table-hover">
						<thead>
							<tr>
                                <th class="thead">No.</th>
								<th class="thead" style="width:300px">Product Name</th>
								<th class="thead">Product Code</th>
								<th class="thead">Item Price</th>
								<th class="thead">Quantity</th>
								<th class="text-center thead">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php $index=0; ?>
                            @foreach($products as $product)
							<tr>
							<td>{{$index=$index+1}}.</td>
								<td style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2; -webkit-box-orient: vertical;width:300px">{{$product->product_name}}</td>
								<td>{{$product->product_code}}</td>
								<td>KES {{number_format($product->product_price)}}</td>
								<td>{{number_format($product->quantity)}}</td>
								<td class="text-center">
								<div style="width:150px" class="row">
									<!-- <a data-toggle="tooltip" title="Assign to agent" style="margin-right:10px" href="/admin/product-assign/{{$product->id}}" class="btn btn-outline-primary"><i class="fa fa-user-plus"></i></a> -->
									@if(auth()->user()->role == "admin")
									<a data-toggle="tooltip" title="Edit product" style="margin-right:10px" href="/admin/product-edit/{{$product->id}}" class="btn btn-outline-primary"><i class="fa fa-edit"></i></a>
									<a data-toggle="tooltip" title="Delete product" onclick="return confirm('Are you sure to delete this product') ? true : false" style="margin-right:10px" href="/admin/product-delete/{{$product->id}}" class="btn btn-outline-danger"><i class="fa fa-trash"></i></a>
									@elseif(auth()->user()->role == "agent")
									<a data-toggle="tooltip" title="Edit product" style="margin-right:10px" href="/agent/product-edit/{{$product->id}}" class="btn btn-outline-primary"><i class="fa fa-edit"></i></a>
									<a data-toggle="tooltip" title="Delete product" onclick="return confirm('Are you sure to delete this product') ? true : false" style="margin-right:10px" href="/agent/product-delete/{{$product->id}}" class="btn btn-outline-danger"><i class="fa fa-trash"></i></a>
									@elseif(auth()->user()->role == "vendor")
									<a data-toggle="tooltip" title="Edit product" style="margin-right:10px" href="/vendor/product-edit/{{$product->id}}" class="btn btn-outline-primary"><i class="fa fa-edit"></i></a>
									<a data-toggle="tooltip" title="Delete product" onclick="return confirm('Are you sure to delete this product') ? true : false" style="margin-right:10px" href="/vendor/product-delete/{{$product->id}}" class="btn btn-outline-danger"><i class="fa fa-trash"></i></a>
									@endif
								</div>
								</td>
                            </tr>
                            @endforeach
						</tbody>
					</table>

		</div>

             </div>
@endsection
