@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<legend style="padding:20px"  class="text-uppercase font-size-sm font-weight-bold">Add Product</legend>
  <div style="margin-bottom:20px" class="container">

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

  <form action="/vendor/update-profile" method="post">
          @csrf


                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Name</label>
                    <div class="col-lg-10">
                        <input disabled value="{{$user->name}}" tclass="form-control" name="name" placeholder="" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
                        
                        @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                        @enderror

                    </div>

                </div>

                <input type="hidden" value="{{$user->id}}" name="user_id">

                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Email</label>
                    <div class="col-lg-10">
                        <input disabled value="{{$user->email}}" tclass="form-control" name="email" placeholder="" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
                        
                        @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                        @enderror

                    </div>

                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Change Password</label>
                    <div class="col-lg-10">
                        <input tclass="form-control" name="password" placeholder="Enter Password" type="password" class="form-control @if($errors->has('password')) invalid_field @endif" required>
                        
                        @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                        @enderror

                    </div>

                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Confirm Password</label>
                    <div class="col-lg-10">
                        <input tclass="form-control" name="password_confirm" placeholder="Confirm Password" type="password" class="form-control @if($errors->has('quantity')) invalid_field @endif" required>
                        
                        @error('quantity')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                        @enderror

                    </div>

                </div>

                <button type="submit" class="btn btn-primary">Update</button>

                </form>
  </div>
    </div>
@endsection