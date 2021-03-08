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
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>View Agent</strong></h6>
		</div>

		<div class="scrollmenu">
			<a target="_blank" href="/admin/agent/products-report/{{$agent->id}}">Products</a>
			<a target="_blank" href="/admin/agent/active-bookings-report/{{$agent->id}}">Active Bookings</a>
			<a target="_blank" href="/admin/agent/complete-bookings-report/{{$agent->id}}">Complete Bookings</a>
			<a target="_blank" href="/admin/agent/pending-bookings-report/{{$agent->id}}">Pending Bookings</a>
			<a target="_blank" href="/admin/agent/unserviced-bookings-report/{{$agent->id}}">Unserviced Bookings</a>
			<a target="_blank" href="/admin/agent/overdue-bookings-report/{{$agent->id}}">Overdue Bookings</a>
			<a target="_blank" href="/admin/agent/delivered-bookings-report/{{$agent->id}}">Delivered Bookings</a>
			<a target="_blank" href="/admin/agent/confirmed-deliveries-report/{{$agent->id}}">Confirmed Deliveries</a>
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
					<input  value="{{$agent->user->name}}" disabled tclass="form-control" name="name" placeholder="Enter full name" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
				
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
						<input disabled value="{{$agent->phone}}" minLegth="10" maxLegth="10" tclass="form-control" name="phone" placeholder="07XXXXXXXX" type="number" class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
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
						<input disabled value="{{$agent->business_name}}"  class="form-control" name="business_name" placeholder="Business Name"  class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
						@error('business_name')
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
							
								<option value="">{{$agent->city->city_name}}</option>
							</select>

            </div>
        </div>
				<div class="form-group row">
				<label class="col-form-label col-lg-2">Email</label>
				<div class="col-lg-10">
					<input disabled value="{{$agent->email}}" tclass="form-control" name="email" placeholder="Enter full name" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
				
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
						<input disabled value="{{$agent->location}}" tclass="form-control" name="location" placeholder="Enter Location" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
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

        
                </div>
             </div>
@endsection
