@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>{{ucfirst($category->category_name)}}</strong></h6>
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
        
        <form action="/admin/save-subcategory" method="post">
          @csrf

          <input type="hidden" name="category_id" value="{{$category->id}}">

          <h6 class="text-uppercase font-size-sm font-weight-bold">Add Subcategory</legend>

          <div class="form-group row">
            
            <div class="col-lg-10">
                <div class="form-row">
                    <div class="col">
                    <label >Name</label>
                         <input tclass="form-control" name="subcategory_name" placeholder="Enter name" type="text" class="form-control @if($errors->has('subcategory_name')) invalid_field @endif" required>
                        
                        @error('subcategory_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                            @enderror
                    </div>

                    <div class="col">
                    <label >Commision(%)</label>
                         <input tclass="form-control" name="commision" placeholder="E.g 5,10,20" type="number" class="form-control @if($errors->has('commision')) invalid_field @endif" required>
                        
                        @error('commision')
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

        <h6 class="text-uppercase font-size-sm font-weight-bold">Subcategories</legend>

        <table class="table datatable-basic  table-striped">
						<thead>
							<tr>
                                <th class="thead">No.</th>
								<th class="thead">Subcategory Name</th>
                                <th class="thead">Actions</th>
							</tr>
						</thead>
						<tbody>
                            <?php $index=0; ?>
                            @foreach($subcategories as $subcategory)


                            <!-- Modal -->
                            <form action="/admin/update-subcategory/{{$subcategory->id}}" method="post">
                                @csrf
                                <div class="modal fade" id="editModal{{$subcategory->id}}" tabindex="-1" role="dialog" aria-labelledby="editModal{{$subcategory->id}}Label" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModal{{$subcategory->id}}Label">Edit Subcategory</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                    <div class="form-group row">
                                        <label >Name</label>
                                        <div class="col-lg-10">
                                            <input tclass="form-control" value="{{$subcategory->subcategory_name}}" name="subcategory_name" placeholder="Enter name" type="text" class="form-control @if($errors->has('subcategory_name')) invalid_field @endif" required>
                                        
                                            @error('subcategory_name')
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
								<td>{{$subcategory->subcategory_name}}</td>
                                <td><a class="btn btn-outline-success" data-toggle="modal" data-target="#editModal{{$subcategory->id}}">Edit</a></td>
                            </tr>
                            @endforeach
						</tbody>
					</table>


        </div>
     </div>
    </div>
@endsection
