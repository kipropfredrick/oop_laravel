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
                <h3 class="card-title">Add User</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">

			  <form action="/branch/user-save" method="post">

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
				<label>Branch admin Email</label>
					<input value="{{ old('email') }}" tclass="form-control" name="email" placeholder="Enter email" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
				
					@error('email')
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

