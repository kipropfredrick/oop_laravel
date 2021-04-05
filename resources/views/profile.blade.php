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

       <form action="/update-profile" method="post">
        @csrf

        <div class="form-group">
          <label for="">Name</label>
          <input type="text" name="name" class="form-control" required value="{{$profile->name}}">
        </div>

        <div class="form-group">
          <label for="">Email</label>
          <input type="text" name="email" class="form-control" required value="{{$profile->email}}">
        </div>


        <div class="form-group">
          <label for="">Change Password</label>
          <input type="password" name="password" placeholder="Type New Password" class="form-control" autocomplete="new-password">
        </div>

        <button class="btn btn-primary">Update</button>
       
       </form>
      </div>
    </section>
    <!-- /.content -->
@endsection
