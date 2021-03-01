@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>{{ucfirst($zone->zone_name)}}</strong></h6>
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
        
        <form action="/admin/zones/dropoffs/save" method="post">
          @csrf

          <input type="hidden" name="zone_id" value="{{$zone->id}}">

          <h6 class="text-uppercase font-size-sm font-weight-bold">Add Dropoff Location</legend>

          <div class="form-group row">

                <div class="col-lg-12 form-group">
                    <label >Dropoff Location Name</label>
                         <input tclass="form-control" name="dropoff_name" placeholder="Enter Location Name" type="" class="form-control @if($errors->has('dropoff_name')) invalid_field @endif" required>
                        
                        @error('dropoff_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                </div>
                
        </div>

        <button type="submit" class="btn btn-primary">Add</button>

        </form>

        <h6 class="text-uppercase font-size-sm font-weight-bold">dropoffs</legend>

        <table id="myTable" class="table table-bordered table-striped">
						<thead>
							<tr>
                                <th class="thead">No.</th>
								<th class="thead">Dropoff Location</th>
                                <th class="thead">Actions</th>
							</tr>
						</thead>
						<tbody>
                            <?php $index=0; ?>
                            @foreach($zone->dropoffs as $dropoff)


                            <!-- Modal -->
                           
                                <div class="modal fade" id="editModal{{$dropoff->id}}" tabindex="-1" role="dialog" aria-labelledby="editModal{{$dropoff->id}}Label" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModal{{$dropoff->id}}Label">Edit dropoff</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                    <form action="/admin/zones/dropoffs/update/{{$dropoff->id}}" method="post">
                                        @csrf
                                    <div class="form-group row">
            
                                        <div class="col-lg-12">
                                            <div class="form-row">

                                            <div class="col">
                                                <label >Center Name</label>
                                                    <input tclass="form-control" name="dropoff_name" value="{{$dropoff->dropoff_name}}" placeholder="Enter Center Name" type="" class="form-control @if($errors->has('dropoff_name')) invalid_field @endif" required>
                                                    
                                                    @error('dropoff_name')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                        @enderror
                                                </div>
                                        </div>
                                    </div>
                                    </div>

                                    <div style="margin-left: -1px;" class="form-group row">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                         &nbsp;
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>

                                    </form>
                                    
                                    </div>
                                </div>
                                </div>


							<tr>
                                <td>{{$index=$index+1}}.</td>
								<td>{{$dropoff->dropoff_name}}</td>
                                <td><a class="btn btn-outline-success" data-toggle="modal" data-target="#editModal{{$dropoff->id}}">Edit</a></td>
                            </tr>
                            @endforeach
						</tbody>
					</table>


        </div>
     </div>
    </div>
@endsection
