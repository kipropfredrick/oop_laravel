@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Cities</strong></h6>
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
        
        <form action="/admin/save-city" method="post">
          @csrf

          <h6 class="text-uppercase font-size-sm font-weight-bold">Add City</legend>

          <div class="form-group row">
            <label class="col-form-label col-lg-2">Name</label>
            <div class="col-lg-10">
                <input tclass="form-control" name="city_name" placeholder="Enter city name" type="text" class="form-control @if($errors->has('city_name')) invalid_field @endif" required>
               
                @error('city_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>
        </div>

        <button type="submit" class="btn btn-primary">Add</button>

        </form>

        <h6 class="text-uppercase font-size-sm font-weight-bold">Cities</legend>

        <table id="myTable" class="table table-bordered table-striped">
						<thead>
							<tr>
                                <th class="thead">No.</th>
								<th class="thead">City Name</th>
                                <th class="thead">Actions</th>
							</tr>
						</thead>
						<tbody>
                            <?php $index=0; ?>
                            @foreach($cities as $city)

                            <!-- Modal -->
                            <form action="/admin/update-city/{{$city->id}}" method="post">
                                @csrf
                                <div class="modal fade" id="editModal{{$city->id}}" tabindex="-1" role="dialog" aria-labelledby="editModal{{$city->id}}Label" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModal{{$city->id}}Label">Edit city</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-2">Name</label>
                                        <div class="col-lg-10">
                                            <input tclass="form-control" value="{{$city->city_name}}" name="city_name" placeholder="Enter name" type="text" class="form-control @if($errors->has('city_name')) invalid_field @endif" required>
                                        
                                            @error('city_name')
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
								<td>{{$city->city_name}}</td>
                                <td><a class="btn btn-outline-success" data-toggle="modal" data-target="#editModal{{$city->id}}">Edit</a></td>
                            </tr>
                            @endforeach
						</tbody>
					</table>


        </div>
     </div>
    </div>
@endsection
