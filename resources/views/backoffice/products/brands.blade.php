@extends('backoffice.app')

@section('content')


<div class="card">
    <div class="card-header">
    <h3 class="card-title">Brands</h3>
    </div>
    <!-- /.card-header -->

    @if (session()->has('success'))

        <div class="alert alert-success fade show" role="alert">
            {{ session()->get('success') }}
        </div>

        @elseif (session()->has('error'))

            <div class="alert alert-danger fade show" role="alert">
                {{ session()->get('error') }}
            </div>

    @endif

    <form action="/admin/save-brand" enctype="multipart/form-data" method="post">
          @csrf
    <div class="row">

    <div class="col-md-6">

    <div class="form-group">
            <label class="col-form-label col-lg-2">Name</label>
            <div class="col-lg-10">
                <input tclass="form-control" name="brand_name" placeholder="Enter name" type="text" class="form-control @if($errors->has('brand_name')) invalid_field @endif" required>
               
                @error('brand_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>
        </div>


    </div>

    <div class="col-md-6">
    <div class="form-group">
            <label class="col-form-label col-lg-2">Icon</label>
            <div class="col-lg-10">
            <input tclass="form-control" name="brand_icon"  type="file" class="form-control @if($errors->has('brand_icon')) invalid_field @endif" required>
               
                @error('brand_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>
        </div>
    </div>

    </div>

    <button type="submit" class="ml-2 btn btn-primary">Add</button>

</form>

    <div class="card-body">
    <table id="myTable" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th class="thead">No.</th>
            <th>Image</th>
            <th class="thead">Brand Name</th>
            <th class="thead">Actions</th>
        </tr>
        </thead>
        <tbody>
            <?php $index=0; ?>
            @foreach($brands as $brand)
            <tr>
                <td>{{$index=$index+1}}.</td>
                <td><img src="/storage/images/{{$brand->brand_icon}}" style="height:30px;width:30px;object-fit:contain" alt="Product Name"></td>
                <td>{{$brand->brand_name}}</td>
                <td>
                    <div class="row">
                    <a class="btn ml-2 btn-outline-primary" data-toggle="modal" data-target="#editModal{{$brand->id}}" href="#"><i class="fa fa-edit"></i></a>
                    </div>
                </td>
            </tr>

            <!-- Modal -->
                           
            <div class="modal fade" id="editModal{{$brand->id}}" tabindex="-1" role="dialog" aria-labelledby="editModal{{$brand->id}}Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModal{{$brand->id}}Label">Edit brand</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                <form action="/admin/update-brand/{{$brand->id}}" enctype="multipart/form-data" method="post">
                    @csrf
                <div class="form-group">

                    <div class="col-lg-12">
                        <div class="form-row">

                        <div class="col">
                            <label >Brand Name</label>
                                <input tclass="form-control" name="brand_name" value="{{$brand->brand_name}}" placeholder="Enter Center Name" type="" class="form-control @if($errors->has('brand_name')) invalid_field @endif" required>
                                
                                @error('brand_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                    @enderror
                            </div>
                    </div>
                </div>
                </div>

                <div class="form-group">

                    <div class="col-lg-12">
                        <div class="form-row">

                        <div class="col">
                            <label >Change Brand Icon</label>
                                <input tclass="form-control" name="brand_icon" placeholder="" type="file" class="form-control @if($errors->has('brand_icon')) invalid_field @endif">
                                
                                @error('brand_icon')
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
                </div>

                </form>
                
                </div>
            </div>
            </div>

            @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th class="thead">No.</th>
            <th>Image</th>
            <th class="thead">Brand Name</th>
            <th class="thead">Actions</th>
        </tr>
        </tfoot>
    </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

@endsection
