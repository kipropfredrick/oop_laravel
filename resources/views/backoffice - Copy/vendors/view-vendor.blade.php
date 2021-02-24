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
<div class="table-responsive">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>View Vendor</strong></h6>
			
		</div>

		<div class="scrollmenu">
			<a target="_blank" href="/admin/vendor/products-report/{{$vendor->id}}">Products</a>
			<a target="_blank" href="/admin/vendor/active-bookings-report/{{$vendor->id}}">Active Bookings</a>
			<a target="_blank" href="/admin/vendor/complete-bookings-report/{{$vendor->id}}">Complete Bookings</a>
			<a target="_blank" href="/admin/vendor/pending-bookings-report/{{$vendor->id}}">Pending Bookings</a>
			<a target="_blank" href="/admin/vendor/unserviced-bookings-report/{{$vendor->id}}">Unserviced Bookings</a>
			<a target="_blank" href="/admin/vendor/overdue-bookings-report/{{$vendor->id}}">Overdue Bookings</a>
			<a target="_blank" href="/admin/vendor/delivered-bookings-report/{{$vendor->id}}">Delivered Bookings</a>
			<a target="_blank" href="/admin/vendor/confirmed-deliveries-report/{{$vendor->id}}">Confirmed Deliveries</a>
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
			<form action="/admin/agent_save" method="post">

			@csrf

			<div class="form-group row">
				<label class="col-form-label col-lg-2">Full Name</label>
				<div class="col-lg-10">
					<input  value="{{$vendor->user->name}}" disabled tclass="form-control" name="name" placeholder="Enter full name" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
				
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
						<input disabled value="{{$vendor->phone}}" minLegth="10" maxLegth="10" tclass="form-control" name="phone" placeholder="07XXXXXXXX" type="number" class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
						@error('phone')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror

				</div>
				</div>

				<div class="form-group row">
					<label class="col-form-label col-lg-2">Business Name</label>
					<div class="col-lg-10">
						<input disabled value="{{$vendor->business_name}}"  class="form-control" name="business_name" placeholder="Business Name"  class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
						@error('business_name')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror

				</div>
				</div>


				<div class="form-group row">
					<label class="col-form-label col-lg-2">Business Logo</label>
					<div class="col-lg-10">
						<!-- <input disabled value="{{$vendor->logo}}"  class="form-control" name="logo" placeholder="Business Name"  class="form-control @if($errors->has('name')) invalid_field @endif" required> -->
					    <View style="border-color:gray;border-width: 2px;height: 100px;width: 100px;">
					   	 <img style="height:100px;width:100px;object-fit:contain;border-color:gray;border-width:2px" src="/storage/images/{{$vendor->logo}}" alt="Business Logo">
						</View>
						@error('logo')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror

				</div>
				</div>


				<div class="form-group row">
					<label class="col-form-label col-lg-2">Business Description</label>
					<div class="col-lg-10">
					   <textarea class="form-control" name="business_description" name="" id="" cols="30" rows="10" disabled>
                        @if(isset($vendor->business_description))
						  {{$vendor->business_description}}
						@else
						 No Description found
						@endif
                       </textarea>
						@error('logo')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror

				</div>
				</div>


				<div class="form-group row">
					<label class="col-form-label col-lg-2">Country</label>
					<div class="col-lg-10">
						<select disabled id="categories" class="form-control" name="country" placeholder="Enter name" type="text" class="form-control @if($errors->has('country')) invalid_field @endif" required onchange="filter()">
							<option value="Kenya">Kenya</option>
						</select>
						@error('country')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror

            </div>
        </div>


				<div class="form-group row">
					<label class="col-form-label col-lg-2">City</label>
					<div class="col-lg-10">
						<select disabled class="form-control" name="city_id" placeholder="Enter name" type="text" class="form-control @if($errors->has('city_id')) invalid_field @endif">
							
								<option value="">{{$vendor->city->city_name}}</option>
							</select>

            </div>
        </div>
				<div class="form-group row">
				<label class="col-form-label col-lg-2">Email</label>
				<div class="col-lg-10">
					<input disabled value="{{$vendor->user->email}}" tclass="form-control" name="email" placeholder="Enter Email" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
				
					@error('email')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
					@enderror

					</div>
				</div>


				<div class="form-group row">
					<label class="col-form-label col-lg-2">Location</label>
					<div class="col-lg-10">
						<input disabled value="{{$vendor->location}}" tclass="form-control" name="location" placeholder="Enter Location" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
						@error('location')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror

				</div>
				</div>

				<!-- <button class="btn btn-primary" type="submit">Save</button> -->

			</form>

        </div>

		
		<div class="table-responsive">

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
             </div>
@endsection
