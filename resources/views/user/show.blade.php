@extends('backoffice.app')
@section('content')
    <div class=" card p-5 row">
        <div class="col-md-6">
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h6 class="panel-title">{{ $user->name }}</h6>
                    <div class="heading-elements">

                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-responsive table-hover">
                      
                        <tr>
                            <td>Email</td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        
                      
                        <tr>
                            <td>User Created At</td>
                            <td>{{ $user->created_at }}</td>
                        </tr>
                        <tr>
                            <td>User Updated At</td>
                            <td>{{ $user->updated_at }}</td>
                        </tr>
                        <tr>
                            <td>Last login</td>
                            <td>{{ $user->last_login }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
     
    </div>
 
@endsection

