@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Subcategory : {{ucfirst($subcategory->subcategory_name)}}</strong></h6>
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
        
        <form action="/admin/save-tsubcategory" method="post">
          @csrf

          <input type="hidden" name="subcategory_id" value="{{$subcategory->id}}">

          <br>
          <h6 class="text-uppercase font-size-sm font-weight-bold">Add Third Level Category</h6>

          <div class="form-group row">
            
            <div class="col-lg-10">
                <div class="form-row">
                    <div class="col">
                    <!-- <label >Name</label> -->
                         <input tclass="form-control" name="name" placeholder="Enter name" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
                        
                            @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                            @enderror
                    </div>

                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Add</button>

        </form>

        <br>
        <h6 class="text-uppercase font-size-sm font-weight-bold">Third Level Categories</h6>

        <table id="myTable" class="table table-bordered table-striped">
						<thead>
							<tr>
                                <th class="thead">No.</th>
								<th class="thead">Third Level ategory Name</th>
                                <th class="thead">Actions</th>
							</tr>
						</thead>
						<tbody>
                            <?php $index=0; ?>
                            @foreach($subcategory->thirdlevelcategories as $category)


                            <!-- Modal -->
                            <form action="/admin/update-tsubcategory/{{$category->id}}" method="post">
                                @csrf
                                <div class="modal fade" id="editModal{{$category->id}}" tabindex="-1" role="dialog" aria-labelledby="editModal{{$subcategory->id}}Label" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModal{{$category->id}}Label">Edit Third Level Category</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                    <div class="form-group row">
                                        <label >Name</label>
                                        <div class="col-lg-10">
                                            <input tclass="form-control" value="{{$category->name}}" name="name" placeholder="Enter name" type="text" class="form-control @if($errors->has('name')) invalid_field @endif" required>
                                        
                                            @error('name')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                            @enderror

                                        </div>
                                    </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                    </div>
                                </div>
                                </div>
                             </form>


							<tr>
                                <td>{{$index=$index+1}}.</td>
								<td>{{$category->name}}</td>
                                <td>
                                    <div class="row">
                                        <a class="btn btn-outline-success" data-toggle="modal" data-target="#editModal{{$subcategory->id}}">Edit</a>
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
