@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive">
        <div class="card-header header-elements-inline">
            <legend style="color: #005b77;" class="card-title"><strong>View Banner</strong></legend>
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
                <input disabled class="form-control" value="{{$banner->title}}" cols="30" rows="10" name="title" id="title" placeholder="Title" type="" class="form-control @if($errors->has('title')) invalid_field @endif" required>
            </div>

        </div>

			<div class="form-group row">
            <label class="col-form-label col-lg-2">Description</label>
            <div class="col-lg-10">
                <textarea disabled  class="form-control" cols="30" rows="10" name="description" id="description" placeholder="Description" type="number" class="form-control @if($errors->has('description')) invalid_field @endif" required>
                  {!!$banner->description!!}
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
                  <img style="height:150px;width:100%;object-fit:contain" src="/storage/banners/{{$banner->image}}" alt="banner">
            </div>

        </div>
			</form>

        </div>

        
                </div>
             </div>
@endsection
