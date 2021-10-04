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
<div class="md-col-6 col-lg-6 col-sm-12">
		<label>Commission Type</label>
		<div class="form-check">
  <input class="form-check-input" type="radio" id="flexRadioDefault1" checked="" value="1" name="commissionrate_enabled">
  <label class="form-check-label" for="flexRadioDefault1">
    Commission Rate
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" name="commissionrate_enabled" id="flexRadioDefault2" value="0"  >
  <label class="form-check-label" for="flexRadioDefault2" >
    Fixed Commission
  </label>
</div>
</div>
<div class="md-col-6 col-lg-6 col-sm-12">
		<label>Categories Types</label>
		<div class="form-check">
  <input class="form-check-input" type="radio" name="category" id="flexRadioDefault12" value="1"  >
  <label class="form-check-label" for="flexRadioDefault12" >
   Specific categories
  </label>
</div>
		<div class="form-check">
  <input class="form-check-input" type="radio" id="flexRadioDefault11" checked="" value="0" name="category">
  <label class="form-check-label" for="flexRadioDefault11">
  General Categories
  </label>
</div>

</div>
</div>

		
<!-- 
					   <div class="row" id="fixed">

			   <div class="col-md-6">

			   <div class="form-group">
				<label>Mobile Money</label>
					<input  value="{{ old('fixed_mobile_money') }}" tclass="form-control" name="fixed_mobile_money" placeholder="3" type="number" class="form-control @if($errors->has('fixed_mobile_money')) invalid_field @endif"  step="1">
				
					@error('fixed_mobile_money')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
					@enderror
				</div>
			   
			   </div>

				<div class="col-md-6">
				<div class="form-group">
					<label>Bank/Card Payments</label>
						<input  value="{{ old('fixed_bank') }}"  class="form-control" name="fixed_bank" placeholder="5000" type="number" class="form-control @if($errors->has('fixed_bank')) invalid_field @endif" >
					
						@error('fixed_bank')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror
				</div>
				</div>
			   
			   </div> -->
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
		

@endsection()


@section('extra-js')


<script type="text/javascript">
		

			window.addEventListener("load", function(){
    	$("#fixed").hide();
		
});
				function updateWidget(item){
			 	$("#fixed").toggle();
			 	 	$("#commissionrate").toggle();
			}
		</script>




@endsection()