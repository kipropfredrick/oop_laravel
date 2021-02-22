@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Counties</strong></h6>
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
        
        <form action="/admin/counties/save" method="post">
          @csrf

          <legend class="text-uppercase font-size-sm font-weight-bold">Add County</legend>

          <div class="form-group row">
            <label class="col-form-label col-lg-2">County Name</label>
            <div class="col-lg-10">
                <input tclass="form-control" name="county_name" placeholder="Enter County" type="text" class="form-control @if($errors->has('county_name')) invalid_field @endif" required>
               
                @error('county_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>
        </div>

        <button type="submit" class="btn btn-primary">Add</button>

        </form>

        <legend class="text-uppercase font-size-sm font-weight-bold">Counties</legend>

        <table class="table datatable-basic  table-striped">
						<thead>
							<tr>
                                <th class="thead">No.</th>
								<th class="thead">County Name</th>
                                <th class="thead">Actions</th>
							</tr>
						</thead>
						<tbody>
                            <?php $index=0; ?>
                            @foreach($counties as $county)


                            <!-- Modal -->
                            <form action="/admin/counties/update/{{$county->id}}" method="post">
                                @csrf
                                <div class="modal fade" id="editModal{{$county->id}}" tabindex="-1" role="dialog" aria-labelledby="editModal{{$county->id}}Label" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModal{{$county->id}}Label">Edit county</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                    <div class="form-group row">
                                        <label >Name</label>
                                        <div class="col-lg-10">
                                            <input tclass="form-control" value="{{$county->county_name}}" name="county_name" placeholder="Enter name" type="text" class="form-control @if($errors->has('county_name')) invalid_field @endif" required>
                                        
                                            @error('county_name')
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
								<td>{{$county->county_name}}</td>
                                <td>
                                 <div class="row">
                                    <a style="margin-right:10px" class="btn btn-outline-success" href="/admin/counties/view/{{$county->id}}"><i class="fa fa-eye"></i></a>
                                    <a class="btn btn-outline-success" data-toggle="modal" data-target="#editModal{{$county->id}}"><i class="fa fa-edit"></i></a>
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
