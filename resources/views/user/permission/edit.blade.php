@extends('backoffice.app')

@section('content')
    <div class="card p-5">
        <div class="panel-heading">
            <h6 class="panel-title">Edit Permission</h6>

        </div>
       
        <form action="{{'/admin/user/permission/'.$permission->id.'/update'}}" method="post" enctype="multipart/form-data" >
{{csrf_field()}}
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="form-line">
                           <label class="label">Type</label>
                    
                    <select class="form-control" id="type">
                        <option value="0">Parent Permission</option>
                        <option value="1">Sub Permission</option>
                    </select>
                        </div>
                    </div>
                    <div class="form-group" id="parent">
                           <label class="control-label"> Parent</label>
                    <select class="select2" id="parent_id" name="parent_id">
               
 @foreach ($parent as $key => $value) 
       <option value='{!! $key !!}'>{!! $value !!}</option>
  @endforeach

                    </select>
                    </div>
                    <div class="form-group">
                        <div class="form-line">
                          

                             <label class="control-label">Name</label>
            <input type="text" class="form-control" value="{{$permission->name}}" name="name">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-line">

                             <label class="control-label">Name</label>
            <input type="text" class="form-control" value="{{$permission->slug}}" name="slug">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-line">
                           

                                 <label class="control-label">Description</label>
                
                    <textarea name="description" class="form-control">{{$permission->description}}</textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- /.panel-body -->
        <div class="panel-footer">
            <button type="submit" class="btn btn-primary pull-right">Save</button>
        </div>
       </form>
    </div>
    <script>
        $(document).ready(function () {
            if ($('#type').val() == 0) {
                $('#parent').hide();
            } else {
                $('#parent').show();
            }
            $('#type').change(function () {
                if ($('#type').val() == 0) {
                    $('#parent').hide();
                    $('#type').val('0')
                } else {
                    $('#parent').show();
                }
            })
        })
    </script>
@endsection