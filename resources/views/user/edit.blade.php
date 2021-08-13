@extends('backoffice.app')
@section('content')
    <div class="card p-5">
        <div class="panel-heading">
            <h6 class="panel-title">Edit User</h6>

            <div class="heading-elements">

            </div>
        </div>
      
        <form method="post" action="{{'/admin/user/'.$user->id.'/update'}}" class="form-horizontal" enctype="multipart/form-data">
        {{csrf_field()}}        
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label">Name</label>
                <input type="text" name="name" class="form-control" required="" value="{{$user->name}}">
            </div>

            <div class="form-group ">
                
                <label class="control-label">Email</label>
                <input type="email" class="form-control" name="email" required=""   value="{{$user->email}}">
            </div>
            <div class="form-group">
               
                <label class="control-label">password</label>
                <input type="password" class="form-control" name="password" >
            </div>
            <div class="form-group">
           <label class="control-label">password</label>
                <input type="password" class="form-control" name="rpassword">
            </div>
            <div class="form-group">
             <label>Role {{$user->role}}</label>
                 <input type="text" class="form-control" name="previous_role" value="{{$selected}}" hidden="">
                    <select class="select2 form-control" id="parent_id" name="role" class="">
               
 @foreach ($role as $key => $value) 
       <option value='{!! $key !!}' @if($selected==$value) selected @endif >{!! $value !!}</option>
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