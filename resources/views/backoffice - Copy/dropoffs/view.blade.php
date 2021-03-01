@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Dropoff Locations</strong></h6>
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
          
          <h6 class="text-uppercase font-size-sm font-weight-bold">Add Dropoff Location</legend>

          <div class="form-group row">
            
            <div class="col-lg-12">

            <div class="form-group">

                <label for="">Zone</label>
                <select class="form-control" name="zone_id" id="" required>
                        <option value="">Select/Search Location</option>
                    @foreach($zones as $zone)
                        <option value="{{$zone->id}}">{{$zone->zone_name}}</option>
                    @endforeach
                </select>

            </div>

                <div class="form-row">

                  <div class="col">
                    <label >Dropoff Name</label>
                         <input tclass="form-control" name="dropoff_name" placeholder="Enter Dropoff Name" type="" class="form-control @if($errors->has('dropoff_name')) invalid_field @endif" required>
                        
                        @error('dropoff_name')
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

        <h6 class="text-uppercase font-size-sm font-weight-bold">Dropoff Locations</legend>

        <table id="myTable" class="table table-bordered table-striped">
						<thead>
							<tr>
                                <th class="thead">No.</th>
								<th class="thead">Dropoff Location</th>
                                <th class="thead">Zone</th>
                                <th class="thead">Actions</th>
							</tr>
						</thead>
						<tbody>
                            <?php $index=0; ?>
                            @foreach($dropoffs as $location)


                            <!-- Modal -->
                           
                                <div class="modal fade" id="editModal{{$location->id}}" tabindex="-1" role="dialog" aria-labelledby="editModal{{$location->id}}Label" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModal{{$location->id}}Label">Edit location</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                    <form action="/admin/zones/dropoffs/update/{{$location->id}}" method="post">
                                        @csrf
                                        <div class="form-group">
                                                <div class="form-row">
                                                    <label >Location Name</label>
                                                        <input tclass="form-control" name="dropoff_name" value="{{$location->dropoff_name}}" placeholder="Enter dropoff Name" type="" class="form-control @if($errors->has('dropoff_name')) invalid_field @endif" required>
                                                        
                                                        @error('dropoff_name')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                        @enderror
                                                </div>
                                            </div>

                                        <div class="form-group">
                                            <div class="form-row">
                                                <label >Zone Name</label>

                                                    <select class="form-control" name="zone_id" id="" required>
                                                            <option value="{{$zone->id}}">{{$zone->zone_name}}</option>
                                                            @foreach($zones as $zone)
                                                                <option value="{{$zone->id}}">{{$zone->zone_name}}</option>
                                                            @endforeach
                                                    </select>
                                                    
                                                        @error('dropoff_name')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                        @enderror
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
								<td>{{$location->dropoff_name}}</td>
                                <td>{{$location->zone->zone_name}}</td>
                                <td><a class="btn btn-outline-success" data-toggle="modal" data-target="#editModal{{$location->id}}">Edit</a></td>
                            </tr>
                            @endforeach
						</tbody>
					</table>


        </div>
     </div>
    </div>
@endsection
