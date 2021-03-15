@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Send SMS</strong></h6>
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
        
        <div class="padding">
        
        <form action="/admin/send-sms-save" enctype="multipart/form-data" method="post">
          @csrf
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Recipient</label>
            <div class="col-lg-10">
                <input  tclass="form-control" name="receiver" placeholder="2547xxxxxxxx" type="text" class="form-control @if($errors->has('receiver')) invalid_field @endif" required>
               
                @error('receiver')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Message</label>
            <div class="col-lg-10">
                <textarea cols="30" rows="10" tclass="form-control" name="message" placeholder="Reciepient" type="text" class="form-control @if($errors->has('message')) invalid_field @endif" required>
                </textarea>
                @error('message')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>
        </div>

        <button style="margin-bottom:20px" type="submit" class="btn btn-primary">Send</button>

        </form>
        
        </div>
        
        </div>
     </div>
    </div>
@endsection
