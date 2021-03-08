@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>{{ucfirst($county->county_name)}}</strong></h6>
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
        
        <form action="/admin/counties/locations/save" method="post">
          @csrf

          <input type="hidden" name="county_id" value="{{$county->id}}">

          <h6 class="text-uppercase font-size-sm font-weight-bold">Add Pickup Locatiom</legend>

          <div class="form-group row">
            
            <div class="col-lg-12">
                <div class="form-row">

                  <div class="col">
                    <label >Center Name</label>
                         <input tclass="form-control" name="center_name" placeholder="Enter Center Name" type="" class="form-control @if($errors->has('center_name')) invalid_field @endif" required>
                        
                        @error('center_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                            @enderror
                    </div>


                    <div class="col">
                    <label >Town</label>
                         <input tclass="form-control" name="town" placeholder="Enter Town" type="text" class="form-control @if($errors->has('town')) invalid_field @endif" required>
                        
                        @error('town')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                            @enderror
                    </div>

                    <div class="col">
                    <label >Street</label>
                         <input tclass="form-control" name="street" placeholder="Enter street" type="text" class="form-control @if($errors->has('street')) invalid_field @endif" required>
                        
                        @error('street')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                            @enderror
                    </div>

                </div>
            </div>
        </div>

        <div class="form-group row">
            
            <div class="col-lg-12">
                <div class="form-row">
                    
                    <div class="col">
                    <label >Building</label>
                         <input tclass="form-control" name="building" placeholder="Enter Building" type="" class="form-control @if($errors->has('building')) invalid_field @endif" required>
                        
                        @error('building')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                            @enderror
                    </div>

                    
                    <div class="col">
                    <label >Direction Tip</label>
                         <input tclass="form-control" name="direction_tip" placeholder="Enter Direction Tip" type="text" class="form-control @if($errors->has('direction_tip')) invalid_field @endif" required>
                        
                        @error('direction_tip')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                            @enderror
                    </div>

                    <div class="col">
                    <label >Contact No</label>
                         <input tclass="form-control" name="contact_no" placeholder="Enter Contact No" type="" class="form-control @if($errors->has('contact_no')) invalid_field @endif" required>
                        
                        @error('contact_no')
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

        <h6 class="text-uppercase font-size-sm font-weight-bold">Pickuplocations</legend>

        <table id="myTable" class="table table-bordered table-striped">
						<thead>
							<tr>
                                <th class="thead">No.</th>
								<th class="thead">Center Name</th>
                                <th class="thead">Town</th>
                                <th class="thead">Street</th>
                                <th class="thead">Building</th>
                                <th class="thead">Direction Tip</th>
                                <th class="thead">Contact No</th>
                                <th class="thead">Actions</th>
							</tr>
						</thead>
						<tbody>
                            <?php $index=0; ?>
                            @foreach($pickuplocations as $location)


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

                                    <form action="/admin/counties/locations/update/{{$location->id}}" method="post">
                                        @csrf
                                    <div class="form-group row">
            
                                        <div class="col-lg-12">
                                            <div class="form-row">

                                            <div class="col">
                                                <label >Center Name</label>
                                                    <input tclass="form-control" name="center_name" value="{{$location->center_name}}" placeholder="Enter Center Name" type="" class="form-control @if($errors->has('center_name')) invalid_field @endif" required>
                                                    
                                                    @error('center_name')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                        @enderror
                                                </div>


                                                <div class="col">
                                                <label >Town</label>
                                                    <input tclass="form-control" name="town" value="{{$location->town}}" placeholder="Enter Town" type="text" class="form-control @if($errors->has('town')) invalid_field @endif" required>
                                                    
                                                    @error('town')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                        @enderror
                                                </div>

                                                <div class="col">
                                                <label >Street</label>
                                                    <input tclass="form-control" name="street" placeholder="Enter street" value="{{$location->street}}" type="text" class="form-control @if($errors->has('street')) invalid_field @endif" required>
                                                    
                                                    @error('street')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                        @enderror
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        
                                        <div class="col-lg-12">
                                            <div class="form-row">
                                                
                                                <div class="col">
                                                <label >Building</label>
                                                    <input tclass="form-control" name="building" value="{{$location->building}}" placeholder="Enter Building" type="" class="form-control @if($errors->has('building')) invalid_field @endif" required>
                                                    
                                                    @error('building')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                        @enderror
                                                </div>

                                                
                                                <div class="col">
                                                <label >Direction Tip</label>
                                                    <input tclass="form-control" name="direction_tip" placeholder="Enter Direction Tip" value="{{$location->direction_tip}}" type="text" class="form-control @if($errors->has('direction_tip')) invalid_field @endif" required>
                                                    
                                                    @error('direction_tip')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                        @enderror
                                                </div>

                                                <div class="col">
                                                <label >Contact No</label>
                                                    <input tclass="form-control" name="contact_no" placeholder="Enter Contact No" value="{{$location->contact_no}}" type="" class="form-control @if($errors->has('contact_no')) invalid_field @endif" required>
                                                    
                                                    @error('contact_no')
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
								<td>{{$location->center_name}}</td>
                                <td>{{$location->town}}</td>
                                <td>{{$location->street}}</td>
                                <td>{{$location->building}}</td>
                                <td>{{$location->direction_tip}}</td>
                                <td>{{$location->contact_no}}</td>
                                <td><a class="btn btn-outline-success" data-toggle="modal" data-target="#editModal{{$location->id}}">Edit</a></td>
                            </tr>
                            @endforeach
						</tbody>
					</table>


        </div>
     </div>
    </div>
@endsection
