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
        
        <form action="/admin/save-category" method="post">
          @csrf

          <legend class="text-uppercase font-size-sm font-weight-bold">Add Category</legend>

          <div class="form-group row">
            <label class="col-form-label col-lg-2">Name</label>
            <div class="col-lg-10">
                <input tclass="form-control" name="category_name" placeholder="Enter name" type="text" class="form-control @if($errors->has('category_name')) invalid_field @endif" required>
               
                @error('category_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>
        </div>

        <button type="submit" class="btn btn-primary">Add</button>

        </form>

        <legend class="text-uppercase font-size-sm font-weight-bold">Categories</legend>

        <table class="table datatable-basic  table-striped">
						<thead>
							<tr>
                                <th class="thead">No.</th>
								<th class="thead">Category Name</th>
                                <th class="thead">Actions</th>
							</tr>
						</thead>
						<tbody>
                            <?php $index=0; ?>
                            @foreach($categories as $category)
							<tr>
                                <td>{{$index=$index+1}}.</td>
								<td>{{$category->category_name}}</td>
                                <td>
                                 <div class="row">
                                    <a style="margin-right:10px" class="btn btn-outline-success" href="/admin/view-category/{{$category->id}}"><i class="fa fa-eye"></i></a>
                                    <a class="btn btn-outline-primary" href="/admin/edit-category/{{$category->id}}"><i class="fa fa-edit"></i></a>
                                 </div>
                                </td>
                            </tr>
                            @endforeach
						</tbody>
					</table>


        </div>
     </div>
    </div>
@endsection
