@extends('backoffice.app')

@section('content')
<!-- Main content -->

<section class="content">
      <div class="container-fluid">

      @if (session()->has('success'))

      <div class="alert alert-success fade show" role="alert">
          {{ session()->get('success') }}
      </div>

      @elseif (session()->has('error'))

          <div class="alert alert-danger fade show" role="alert">
              {{ session()->get('error') }}
          </div>

      @endif

      <div  class="card padding">

      <div class="card-header">
       <h6 style="color: #005b77;" class="card-title"><strong>Edit Your Profile</strong></h6>
      </div>

      <div class="card-body padding">

          <form action="/update-profile/{{auth()->user()->role}}" method="post">
            @csrf

            
            <div class="form-row">

              <div class="col form-group">
                <label for="">Name</label>
                <input type="text" name="name" class="form-control" required value="{{$profile->name}}">
              </div>

              <div class="col form-group">
                <label for="">Email</label>
                <input type="text" name="email" class="form-control" required value="{{$profile->email}}">
              </div>
            
            </div>


            <div class="form-row">
            
                @if(auth()->user()->role == "user")

                <?php 
                  $customer = \App\Customers::where('user_id',auth()->user()->id)->first(); 
                ?>

                <div class="col form-group">
                  <label for="">Phone</label>
                  <input type="text" name="phone" class="form-control" required value="{{$customer->phone}}">
                </div>

                @elseif(auth()->user()->role == "vendor")

                <?php 
                  $vendor = \App\Vendor::where('user_id',auth()->user()->id)->first(); 
                ?>

                <div class="col form-group">
                  <label for="">Phone</label>
                  <input type="text" name="phone" class="form-control" required value="{{$vendor->phone}}">
                </div>

                @endif

                <div class="col form-group">
                  <label for="">Change Password</label>
                  <input type="password" name="password" placeholder="Type New Password" class="form-control" autocomplete="new-password">
                </div>
            
            </div>

            <button class="btn btn-primary">Update</button>
          
          </form>
      
      </div>

      </div>
       
      </div>
    </section>
    <!-- /.content -->
@endsection
