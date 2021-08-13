
@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Permissions</strong></h6>
              <div class="heading-elements float-right mb-2">
                <a href="{{ url('admin/user/role/create') }}" class="btn btn-info btn-xs">
                    Add Role
                </a>
            </div>
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
        </div>
        
        <div class="table-responsive padding">
        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>slug</th>
                    <th>action</th>
                </tr>
                </thead>
                        <tbody>
                            <?php $index=0; ?>
                       @foreach($data as $key)
                    <tr>
                        <td>{{$index+1}}</td>
                        <?php $index=$index+1; ?>
                       
                             <td>{{ $key->name }}</td>
                        <td>{{ $key->slug}}</td>
                        <td>
                            <ul class="icons-list" style="list-style: none;">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-bars"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right p-2" >
                                        <li>
                                            <a href="{{ url('/admin/user/role/'.$key->id.'/edit') }}"><i
                                                        class="fa fa-edit"></i>
                                                edit</a>
                                                <hr>
                                        </li>
                                       

                                        <li>
                                            <a href="{{ url('/admin/user/role/'.$key->id.'/delete') }}"
                                               data-toggle="confirmation"><i
                                                        class="fa fa-trash"></i>
                                               delete</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </td>
                    </tr>
                @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
             </div>
@endsection