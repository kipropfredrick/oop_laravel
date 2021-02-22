@extends('front.app')

@section('content')
<div style="margin-top:50px" class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

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
                <div class="card-header" style="background-color:#333333;color:#FFF">Vendor Registration</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register-vendor') }}" enctype="multipart/form-data">
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
					<label class="col-form-label col-lg-2">Business Logo</label>
					<div class="col-lg-10">
						<input value="{{ old('business_logo') }}"   type="file" name="business_logo" class="@if($errors->has('name')) invalid_field @endif" required>
					
						@error('business_logo')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror

				</div>
				</div>


				<div class="form-group row">
					<label class="col-form-label col-lg-2">Business Description</label>
					<div class="col-lg-10">
						<!-- <textarea cols="30" rows="10"  class="form-control" name="business_description" placeholder="Business Description"  class="form-control @if($errors->has('name')) invalid_field @endif" required>
                        </textarea> -->
						<textarea cols="30" rows="10" name="business_description" class="form-control" required></textarea>
						@error('business_description')
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
					<input value="{{ old('email') }}" tclass="form-control" name="email" placeholder="Enter Email Adress" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
				
					@error('email')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
					@enderror

					</div>
				</div>


				<div class="form-group row">
					<label class="col-form-label col-lg-2">Exact Shop Location</label>
					<div class="col-lg-10">
						<input value="{{ old('location') }}" tclass="form-control" name="location" placeholder="Enter Location" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
						@error('location')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror

				</div>
				</div>
				
				
				<div class="form-group row">
				<label class="col-form-label col-lg-2">Password</label>
				<div class="col-lg-10">
					<input value="{{ old('password') }}" tclass="form-control" name="password" placeholder="Enter Password" type="password" class="form-control @if($errors->has('name')) invalid_field @endif" required>
				
					@error('password')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
					@enderror

					</div>
				</div>

				<div class="form-group row">
				<label class="col-form-label col-lg-2">Confirm password </label>
				<div class="col-lg-10">
					<input value="{{ old('password-confirm') }}" tclass="form-control" name="password-confirm" placeholder="Enter password-confirm" type="password" class="form-control @if($errors->has('name')) invalid_field @endif" required>
				
					@error('password-confirm')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
					@enderror

					</div>
				</div>
				
				<div class="form-group">
					<input style="margin-top:5px" type="checkbox" name="" id="" required>
					<span  style="margin-left:5px;color:#E0A800"><a  style="color:#E0A800" target="__blank" href="/terms">By signing up for an account you agree to our Terms and Conditions</a></span>
				</div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
