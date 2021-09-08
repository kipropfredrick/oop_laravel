@extends('backoffice.app')

@section('content')

<div class="">
		         @if (session()->has('success'))

				<div class="alert alert-success fade show" role="alert">
					{{ session()->get('success') }}
				</div>

				@elseif (session()->has('error'))

				<div class="alert alert-danger fade show" role="alert">
					{{ session()->get('error') }}
				</div>

			@endif


		<div class="card">
              <div class="card-header">
                <h3 class="card-title">Add Vendor</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">

			  <form action="/admin/vendor-save" method="post">

			@csrf

			   <div class="row">

			   <div class="col-md-6">

			   <div class="form-group">
				<label>Full Name</label>
					<input value="{{ old('name') }}" tclass="form-control" name="name" placeholder="Enter full name" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
				
					@error('name')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
					@enderror
				</div>
			   
			   </div>

				<div class="col-md-6">
				<div class="form-group">
					<label>Phone</label>
						<input value="{{ old('phone') }}" minLegth="10" maxLegth="10" tclass="form-control" name="phone" placeholder="07XXXXXXXX" type="number" class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
						@error('phone')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror
				</div>
				</div>
			   
			   </div>

				<div class="row">
				
				<div class="col-md-6">
				
				<div class="form-group">
					<label>Business Name</label>
						<input value="{{ old('business_name') }}"  class="form-control" name="business_name" placeholder="Business Name"  class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
						@error('business_name')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror
				</div>
				
				</div>


				<div class="col-md-6">

				<div class="form-group">
					<label>Country</label>
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
				
				</div>

				   <div class="row">

			   <div class="col-md-6">

			   <div class="form-group">
				<label>Commission rate</label>
					<input required value="{{ old('commission_rate') }}" tclass="form-control" name="commission_rate" placeholder="3" type="number" class="form-control @if($errors->has('commission_rate')) invalid_field @endif" required step=".01">
				
					@error('commission_rate')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
					@enderror
				</div>
			   
			   </div>

				<div class="col-md-6">
				<div class="form-group">
					<label>Commission cap</label>
						<input required value="{{ old('commission_cap') }}"  class="form-control" name="commission_cap" placeholder="5000" type="number" class="form-control @if($errors->has('commission_cap')) invalid_field @endif" required>
					
						@error('commission_cap')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror
				</div>
				</div>
			   
			   </div>

				
				<div class="row">

				 <div class="col-md-6">
				 <div class="form-group">
					<label>City</label>
						<select required class="form-control" name="city_id" placeholder="Enter name" type="text" class="form-control @if($errors->has('city_id')) invalid_field @endif">
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

				 <div class="col-md-6">
				
				<div class="form-group">
				<label>Email</label>
					<input value="{{ old('email') }}" tclass="form-control" name="email" placeholder="Enter full name" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
				
					@error('email')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
					@enderror
				</div>
				
				</div>

				</div>

				<div class="row">

				<div class="col-md-6">
				<div class="form-group">
					<label>Location</label>
						<input value="{{ old('location') }}" tclass="form-control" name="location" placeholder="Enter Location" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
						@error('location')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror
				</div>
				</div>

				<div class="col-md-6">
				<div class="form-group">
					<label>Password</label>
						<input value="{{ old('password') }}" tclass="form-control" name="password" placeholder="Enter password" type="password" class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
						@error('password')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror
				</div>
				</div>
				
				</div>

				<button class="btn btn-primary" type="submit">Save</button>

			</form>
               
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
		
        
		</div>
@endsection
