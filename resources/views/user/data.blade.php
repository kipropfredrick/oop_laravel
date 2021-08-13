@extends('backoffice.app')

@section('content')
    <div class="card p-5">
        <div class="panel-heading">
            <h6 class="panel-title">Users</h6>

            <div class="heading-elements">
                @if(Sentinel::hasAccess('users.create'))
                    <a href="{{ url('/admin/user/create') }}" class="btn btn-info btn-xs float-right p-2 m-1">
                        Add User
                    </a>
                @endif
            </div>
        </div>
        <div class="panel-body table-responsive">
            <table class="table  table-striped table-hover table-condensed" id="data-table">
                <thead>
                <tr>
                    <th>Name</th>
                   
                 
                    <th>Email</th>
                   
                    <th>Role</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $key)
                    <tr>
                        <td>{{ $key->name }}</td>
                        
                     
                        <td>{{ $key->email }}</td>
                        
                        <td>
                            @if(!empty($key->roles))
                                @if(!empty( $key->roles->first()))
                                    <span class="label label-danger">{{ $key->roles->first()->name }} </span>
                                @endif
                            @endif
                        </td>
                        <td>
                               <ul class="icons-list" style="list-style: none;">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-bars"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        @if(Sentinel::hasAccess('users.view'))
                                            <li>
                                                <a href="{{ url('/admin/user/'.$key.'/show') }}"><i
                                                            class="fa fa-search"></i>
                                                   &nbsp Details</a>
                                                   <hr>
                                            </li>
                                        @endif
                                        @if(Sentinel::hasAccess('users.update'))
                                            <li>
                                                <a href="{{ url('admin/user/'.$key->id.'/edit') }}"><i
                                                            class="fa fa-edit"></i>
                                                    &nbsp Edit</a>
                                                    <hr>
                                            </li>
                                        @endif
                                        @if(Sentinel::hasAccess('users.delete'))
                                            <li>
                                                <a href="{{ url('admin/user/'.$key->id.'/delete') }}"
                                                   class="delete"><i
                                                            class="fa fa-trash"></i>
                                                    &nbsp Delete</a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            </ul>

                           <!--  <ul class="icons-list">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>
                                   
                                </li>
                            </ul> -->
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('footer-scripts')

    <script>

        $('#data-table').DataTable({
            "order": [[0, "asc"]],
            "columnDefs": [
                {"orderable": false, "targets": [6]}
            ],
            "language": {
                "lengthMenu": "{{ trans('general.lengthMenu') }}",
                "zeroRecords": "{{ trans('general.zeroRecords') }}",
                "info": "{{ trans('general.info') }}",
                "infoEmpty": "{{ trans('general.infoEmpty') }}",
                "search": "{{ trans('general.search') }}",
                "infoFiltered": "{{ trans('general.infoFiltered') }}",
                "paginate": {
                    "first": "{{ trans('general.first') }}",
                    "last": "{{ trans('general.last') }}",
                    "next": "{{ trans('general.next') }}",
                    "previous": "{{ trans('general.previous') }}"
                }
            },
            responsive: false
        });
    </script>
@endsection
