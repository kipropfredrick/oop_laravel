@extends('backoffice.app')
@section('content')
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">Create Permission</h6>

        </div>
    
        <form action="/admin/user/permission/store" method="post" enctype="multipart/form-data">
{{csrf_field()}}
        <div class="panel-body">

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
                <div class="form-line">
               

                    <label class="control-label"> Parent</label>
                    <select class="select2" id="parent_id" name="parent_id">
               
 @foreach ($parent as $key => $value) 
       <option value='{!! $key !!}'>{!! $value !!}</option>
  @endforeach

                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="form-line">
                    <label class="control-label">Name</label>
                    <input type="text" class="form-control" name="name">
          
                </div>
            </div>
            <div class="form-group">
                <div class="form-line">
            
                    <label class="control-label">Slug</label>
            <input type="text" class="form-control" name="slug">
                </div>
            </div>
            <div class="form-group">
                <div class="form-line">
                   <label class="control-label">Description</label>
                
                    <textarea name="description" class="form-control"></textarea>
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
                } else {
                    $('#parent').show();
                }
            })
        })
    </script>
@endsection