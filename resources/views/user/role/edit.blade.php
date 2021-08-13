@extends('backoffice.app')
@section('content')
    <div class="card p-5">
        <div class="panel-heading">
            <h6 class="panel-title">Edit Roles</h6>

        </div>
       
        <form action="{{'/admin/user/role/'.$role->id.'/update'}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
                <div class="panel-body">
            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="form-line">
                   
                            <label class="control-label">Name</label>
                            <input type="text" value="{{$role->name}}" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <hr>
                       <h4>Manage Permission</h4>

                        <div class="col-md-6">
                            <table class="table table-stripped table-hover">
                                @foreach($data as $permission)
                                    <tr>
                                        <td>
                                            @if($permission->parent_id==0)
                                                <strong>{{$permission->name}}</strong>
                                            @else
                                                __ {{$permission->name}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($permission->description))
                                                <i class="fa fa-info" data-toggle="tooltip"
                                                   data-original-title="{!!  $permission->description!!}"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <input @if(array_key_exists($permission->slug,$role->permissions)) checked=""
                                                   @endif type="checkbox" data-parent="{{$permission->parent_id}}"
                                                   name="permission[]" value="{{$permission->slug}}"
                                                   id="{{$permission->id}}"
                                                   class="styled pcheck">
                                            <label class="" for="{{$permission->id}}">

                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.panel-body -->
        <div class="panel-footer">
            <div class="heading-elements">
                <button type="submit" class="btn btn-primary float-right">Save</button>
            </div>
        </div>
        </form>
    </div>
    <script>
        $(document).ready(function () {
            $(".pcheck").on('click', function (e) {
                if ($(this).is(":checked")) {
                    if ($(this).attr('data-parent') == 0) {
                        var id = $(this).attr('id');
                        $(":checkbox[data-parent=" + id + "]").attr('checked', 'checked');

                    }
                } else {
                    if ($(this).attr('data-parent') == 0) {
                        var id = $(this).attr('id');
                        $(":checkbox[data-parent=" + id + "]").removeAttr('checked');

                    }
                }
                $.uniform.update();
            });
        })
    </script>
@endsection