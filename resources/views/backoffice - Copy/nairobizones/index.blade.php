@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>zones</strong></h6>
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
        
        <form action="/admin/zones/save" method="post">
          @csrf

          <h6 class="text-uppercase font-size-sm font-weight-bold">Add zone</legend>

          <div class="form-group row">
            <label class="col-form-label col-lg-2">Zone Name</label>
            <div class="col-lg-10">
                <input tclass="form-control" name="zone_name" placeholder="Enter zone" type="text" class="form-control @if($errors->has('zone_name')) invalid_field @endif" required>
               
                @error('zone_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>
        </div>

        <div class="form-group row">
                <label class="col-form-label col-lg-2">Price One Way</label>
                <div class="col-lg-10">
                 <input tclass="form-control" name="price_one_way" placeholder="price One Way" type="number" class="form-control @if($errors->has('price_one_way')) invalid_field @endif" required>
                 @error('price_one_way')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                 @enderror
                  </div>
            </div>   
            <div class="form-group row">
                <label class="col-form-label col-lg-2">Price Return</label>
                <div class="col-lg-10">
                <input tclass="form-control" name="price_return" placeholder="Price Return" type="number" class="form-control @if($errors->has('price_return')) invalid_field @endif" required>
                @error('price_return')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                 @enderror
                  </div>
            </div>


         <button type="submit" class="btn btn-primary">Add</button>

        </form>

        <h6 class="text-uppercase font-size-sm font-weight-bold">zones</legend>

        <table id="myTable" class="table table-bordered table-striped">
						<thead>
							<tr>
                                <th class="thead">No.</th>
								<th class="thead">Zone Name</th>
                                <th class="thead">Price One Way (Kshs)</th>
                                <th class="thead">Price Return (Kshs)</th>
                                <th class="thead">Actions</th>
							</tr>
						</thead>
						<tbody>
                            <?php $index=0; ?>
                            @foreach($zones as $zone)


                            <!-- Modal -->
                            <form action="/admin/zones/update/{{$zone->id}}" method="post">
                                @csrf
                                <div class="modal fade" id="editModal{{$zone->id}}" tabindex="-1" role="dialog" aria-labelledby="editModal{{$zone->id}}Label" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModal{{$zone->id}}Label">Edit zone</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                    <div class="form-group row">
                                        <label >Name</label>
                                        <div class="col-lg-10">
                                            <input tclass="form-control" value="{{$zone->zone_name}}" name="zone_name" placeholder="Enter name" type="text" class="form-control @if($errors->has('zone_name')) invalid_field @endif" required>
                                        
                                            @error('zone_name')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-2">Price One Way</label>
                                        <div class="col-lg-10">
                                        <input tclass="form-control" value="{{$zone->price_one_way}}" name="price_one_way" placeholder="price One Way" type="number" class="form-control @if($errors->has('price_one_way')) invalid_field @endif" required>
                                        @error('price_one_way')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        </div>
                                    </div>   
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-2">Price Return</label>
                                        <div class="col-lg-10">
                                        <input value="{{$zone->price_return}}" tclass="form-control" name="price_return" placeholder="Price Return" type="number" class="form-control @if($errors->has('price_return')) invalid_field @endif" required>
                                        @error('price_return')
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
								<td>{{$zone->zone_name}}</td>
                                <td>{{$zone->price_one_way}}</td>
                                <td>{{$zone->price_return}}</td>
                                <td>
                                 <div class="row">
                                    <a style="margin-right:10px" class="btn btn-outline-success" href="/admin/zones/view/{{$zone->id}}"><i class="fa fa-eye"></i></a>
                                    <a class="btn btn-outline-success" data-toggle="modal" data-target="#editModal{{$zone->id}}"><i class="fa fa-edit"></i></a>
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
