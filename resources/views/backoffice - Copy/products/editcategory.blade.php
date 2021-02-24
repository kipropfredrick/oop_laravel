@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Categories</strong></h6>
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
        
        <form action="/admin/update-category/{{$category->id}}" method="post">
          @csrf

          <h6 class="text-uppercase font-size-sm font-weight-bold">Update Category</legend>

          <div class="form-group row">
            <label class="col-form-label col-lg-2">Name</label>
            <div class="col-lg-10">
                <input value="{{$category->category_name}}" tclass="form-control" name="category_name" placeholder="Enter name" type="text" class="form-control @if($errors->has('category_name')) invalid_field @endif" required>
               
                @error('category_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>
        </div>

        <button style="margin-bottom:20px" type="submit" class="btn btn-primary">Update</button>

        </form>
        </div>
     </div>
    </div>
@endsection
