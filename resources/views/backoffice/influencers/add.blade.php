@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Add Influencer</strong></h6>
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
			<form action="/admin/influencer_save" method="post">

			@csrf

			<div class="form-group row">
				<label class="col-form-label col-lg-2">Influencer's Name</label>
				<div class="col-lg-10">
					<input value="{{ old('name') }}" tclass="form-control" name="name" placeholder="Enter Influencer's name" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
				
					@error('name')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
					@enderror

					</div>
				</div>

				<div class="form-group row">
					<label class="col-form-label col-lg-2">Commission (%)</label>
					<div class="col-lg-10">
						<input value="{{ old('commission') }}" tclass="form-control" name="commission" placeholder="10" type="number" class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
						@error('commission')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror

				</div>
				</div>

				<div class="form-group row">
					<label class="col-form-label col-lg-2">Store Name</label>
					<div class="col-lg-10">
						<input value="{{ old('store_name') }}"  class="form-control" name="store_name" placeholder="store Name"  class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
						@error('store_name')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror

				</div>
				</div>

				<div class="form-group row">
					<label class="col-form-label col-lg-2">Phone</label>
					<div class="col-lg-10">
						<input value="{{ old('phone') }}" minLegth="10" maxLegth="10" tclass="form-control" name="phone" placeholder="0XXXXXXXXX" type="number" class="form-control @if($errors->has('name')) invalid_field @endif" required>
					
						@error('phone')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
						@enderror

				</div>
				</div>
				

				<div class="form-group row">
				<label class="col-form-label col-lg-2">Email</label>
				<div class="col-lg-10">
					<input value="{{ old('email') }}" tclass="form-control" name="email" placeholder="Enter email" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
				
					@error('email')
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
