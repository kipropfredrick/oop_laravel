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

        
        
        <form action="/admin/send-sms-save"  method="post">
          @csrf

          <div class="text-center">
            <input type="radio" <?php if (old('type') == "single" || old('type') == ""){echo "checked";} ?> onclick="singleClicked()" value="single"  name="type"><label for="">&nbsp; Single Reciepient</label> &nbsp;
            <input type="radio" <?php if (old('type') == "group"){echo "checked";} ?> onclick="groupClicked()" value="group" name="type"><label for="">&nbsp; Group</label>
          </div>
          
          <div style="display:<?php if (old('type') == "group"){echo "none";} ?>" id="single" class="form-group row">
           
            <label class="col-form-label col-lg-2">Recipient</label>
            <div class="col-lg-10">
                <input  tclass="form-control" name="receiver" value="{{ old('receiver') }}" id="receiver" placeholder="2547xxxxxxxx" type="text" class="form-control @if($errors->has('receiver')) invalid_field @endif">
               
                @error('receiver')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>
            
        </div>


        <div id="group" style="display:<?php if (old('type') == "single" || old('type') == ""){echo "none";} ?>" class="form-group row">
           
            <label class="col-form-label col-lg-2">Group</label>
            <div class="col-lg-10">
                <select name="group" id="group_reciepients" class="form-control" id="">
                    <option value="">Select Group</option>
                    
                    <option <?php if (old('group') == "active_customers"){echo "selected";} ?>   value="active_customers">Active Customers</option>
                    
                    <option <?php if (old('group') == "cb_customers"){echo "selected";} ?> value="cb_customers">CB Customers</option>
                    <option <?php if (old('group') == "ab_customers"){echo "selected";} ?> value="ab_customers">AB Customers</option>
                    <option <?php if (old('group') == "pb_customers"){echo "selected";} ?> value="pb_customers">PB Customers</option>
                    <option <?php if (old('group') == "rb_customers"){echo "selected";} ?> value="rb_customers">RB Customers</option>
                    <option <?php if (old('group') == "inactive_customers"){echo "selected";} ?> value="inactive_customers">Inactive Customers</option>
                </select>               
                @error('group')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>
            
        </div>


        <div class="form-group row">
            <label class="col-form-label col-lg-2">Message</label>
            <div class="col-lg-10">
                <textarea cols="30" rows="10" tclass="form-control" name="message" placeholder="Reciepient" type="text" class="form-control @if($errors->has('message')) invalid_field @endif" required>{{old('message')}}</textarea>
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

@section('extra-js')

<script>

function singleClicked(){
    $('#single').show();
    $('#group').hide();
}


function groupClicked(){
    $('#single').hide();
    $('#group').show();
}

</script>

@endsection