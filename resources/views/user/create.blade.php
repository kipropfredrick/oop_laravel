@extends('backoffice.app')
@section('content')
    <div class="card p-5">
        <div class="panel-heading">
            <h6 class="panel-title">Add User</h6>

            <div class="heading-elements">

            </div>
        </div>
    
        <form action="/admin/user/store" enctype="multipart/form-data" method="post">

        {{csrf_field()}}        
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label">Name</label>
                <input type="text" name="name" class="form-control" required="">
            </div>

            <div class="form-group ">
                
                <label class="control-label">Email</label>
                <input type="email" class="form-control" name="email" required="">
            </div>
            <div class="form-group">
               
                <label class="control-label">password</label>
                <input type="password" class="form-control" name="password" required="">
            </div>
            <div class="form-group">
           <label class="control-label">password</label>
                <input type="password" class="form-control" name="rpassword" required="">
            </div>
            <div class="form-group">
             <label>Role</label>
                    <select class="select2 form-control" id="parent_id" name="role" class="">
               
 @foreach ($role as $key => $value) 
       <option value='{!! $key !!}'>{!! $value !!}</option>
  @endforeach

                    </select>
            </div>
        
        </div>
        <!-- /.panel-body -->
        <div class="panel-footer">
            <div class="heading-elements">
                <button type="submit" class="btn btn-primary float-right">save</button>
            </div>
        </div>
       </form>
    </div>
@endsection
@section('footer-scripts')
    <script src="{{ asset('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
@endsection