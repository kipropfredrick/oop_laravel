@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive">
        <div class="card-header header-elements-inline">
            <legend style="color: #005b77;" class="card-title"><strong>Add Banner</strong></legend>
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
			<form action="/admin/banner_save" method="post" enctype="multipart/form-data">

			@csrf


			<div class="form-group row">
            <label class="col-form-label col-lg-2">Title</label>
            <div class="col-lg-10">
                <input tclass="form-control" cols="30" rows="10" name="title" id="title" placeholder="Title" type="" class="form-control @if($errors->has('title')) invalid_field @endif" required>
                @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

        </div>


        <div class="form-group row">
            <label class="col-form-label col-lg-2">Link</label>
            <div class="col-lg-10">
                <input tclass="form-control" cols="30" rows="10" name="link" id="link" placeholder="E.g https://combine.co.ke/category/Electronics-&-audio-systems" type="" class="form-control @if($errors->has('link')) invalid_field @endif" required>
                @error('link')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Caption</label>
            <div class="col-lg-10">
                <textarea tclass="form-control" cols="30" rows="10" name="description" id="description" placeholder="description" type="" class="form-control @if($errors->has('description')) invalid_field @endif" required>
                </textarea>
                @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

        </div>
        
    <div class="form-group row">
            <label class="col-form-label col-lg-2">Image</label>
            <div class="col-lg-10">
            <input type="file" id="image" name="image" class="form-control" required accept="image/*">
               
                @error('product_price')
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
