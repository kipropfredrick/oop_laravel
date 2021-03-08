@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Add Agent</strong></h6>
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
		
		<div style="margin-bottom:20px" class="container">
			<form action="/admin/agent_save" method="post">

			@csrf

			<div class="form-group row">
				<label class="col-form-label col-lg-2">Full Name</label>
				<div class="col-lg-10">
					<input value="{{ old('name') }}" tclass="form-control" name="name" placeholder="Enter full name" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
				
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
						<input value="{{ old('phone') }}" minLegth="10" maxLegth="10" tclass="form-control" name="phone" placeholder="07XXXXXXXX" type="number" class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
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
						<input value="{{ old('business_name') }}"  class="form-control" name="business_name" placeholder="Business Name"  class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
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
						<select id="categories" class="form-control" name="country" placeholder="Enter name" type="text" class="form-control @if($errors->has('country')) invalid_field @endif" required onchange="filter()">
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
						<select class="form-control" name="city_id" placeholder="Enter name" type="text" class="form-control @if($errors->has('city_id')) invalid_field @endif">
							@foreach (App\City::all() as $city)
								<option value="{{$city->id}}">{{$city->city_name}}</option>
							@endforeach
							</select>
						@error('city_id')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror

            </div>
        </div>
				<div class="form-group row">
				<label class="col-form-label col-lg-2">Email</label>
				<div class="col-lg-10">
					<input value="{{ old('email') }}" tclass="form-control" name="email" placeholder="Enter full name" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
				
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
						<input value="{{ old('location') }}" tclass="form-control" name="location" placeholder="Enter Location" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
						@error('location')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror

				</div>
				</div>

				<button class="btn btn-primary" type="submit">Save</button>

			</form>

        </div>

        
                </div>
             </div>
@endsection
