@extends('layouts.app')

@section('title', $product->product_name.' - Checkout')

@section('content')

<?php
 
 if ((auth()->user())!=null) {
    # code...
     $existingUser = \App\User::where('email',  auth()->user()->email)->first();
}
else
{
    $existingUser=null;
}

$fill=false;

        if($existingUser!=null)
        {
            if ($existingUser->role == "user" ) {
$name=$existingUser->name;
 $email=auth()->user()->email;

        $existingCustomer = \App\Customers::where('user_id','=',$existingUser->id)->first();

$phone=$existingCustomer->phone;
$fill=true;
       
}
        }

?>
<!-- breadcrumb --> 
<div style="margin-top:110px" class="bc-bg">
    <div class="container">
        <div class="bc-link">
            <a href="/">
                <i class="fas fa-home"></i>
            </a>

            <span class="bc-sep"></span>

            <a href="/product/{{$product->slug}}">
                <span>{{$product->product_name}}</span>
            </a>

            <span class="bc-sep"></span>

            <span>Checkout</span>
        </div>
    </div>
</div>
<!-- end -->

<!-- page content -->
<div class="bg-white">
    <div class="container">
        <div class="row">

            <!-- checkout -->
            <div class="col-sm-8">
                <div class="checkout">
                    <div class="card">
                        <div class="m-4">
                            <!-- summarry -->
                            <div>
                                <p>You are placing an order for <strong>{{$product->product_name}}</strong>. Minimum deposit id <strong>KSh.100</strong>.</p>
                                @if(! $fill) <a href="/checkout-with-existing/{{$product->slug}}">Have an account?</a> @endif
                                <hr/>
                            </div>
                            <!-- end --> 

                            <form action="/make-booking" method="post" id="checkoutform">
                                @csrf
                                <input type="hidden" name="quantity" value="{{$product_quantity}}">
                                <input type="hidden" name="product_id" value="{{$product->id}}">
                                <input name="status" value="pending" type="hidden">
                                <input name="minDeposit" value="{{$minDeposit}}" type="hidden">
                                <?php 
                                
                                $vendor = \App\Vendor::where('id',$product->vendor_id)->first();

                                if($vendor!=null){
                                    $vendor_code = $vendor->vendor_code;
                                }
                                ?>
                                <input name="vendor_code" value="@if(isset($vendor_code)){{$vendor_code}}@endif" type="hidden">
                                <h4>Personal Details</h4>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="checkout-first-name">Full name</label><span style="color:red">*</span>
                                        <input required name="name" type="text" class="form-control" id="checkout-first-name" value=" @if($fill) {{$name}} @else {{ old('name') }} @endif " placeholder="Full Name" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="checkout-company-name">Phone Number<span style="color:red">*</span>
                                        </label>
                                        <input required name="phone" type=""  class="form-control" id="checkout-company-name" placeholder="07XXXXXXXX" value=" @if($fill) {{$phone}} @else {{ old('phone') }} @endif ">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="checkout-last-name">Email</label> &nbsp; &nbsp;<span style="color:red" id="emailerror">*</span>
                                        <input required type="email" id="email"  name="email" type="text" class="form-control"  placeholder="Email Address" value=" @if($fill) {{$email}} @else {{ old('email') }} @endif ">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="checkout-street-address">Initial Deposit <span style="color:red">*</span> (Ksh.100 minimum)</label>
                                        <input min="100" required name="initial_deposit" autocomplete="initial_deposit" value="{{ old('initial_deposit') }}" type="number" class="form-control" id="checkout-street-address" placeholder="Initial deposit">
                                    </div>
                                </div>

                                <h4>Delivery Details</h4>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                    <label for="checkout-company-name">County</label><span style="color:red">*</span>
                                    <?php 
                                    $counties = \App\Counties::all();
                                    $locations = \App\PickupLocation::all();
                                    ?>
                                        <select id="counties" class="form-control js-example-basic-single" name="county_id" placeholder="Enter name" type="text" class="form-control @if($errors->has('county_id')) invalid_field @endif" onchange="filter()" required>
                                        <option value="">Select/search county</option>
                                        @foreach($counties as $county)
                                            <option value="{{$county->id}}" class="counties">{{$county->county_name}}</option>
                                        @endforeach
                                        </select>
                                        @error('county_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                        @enderror
                                        </div>
                                    
                                        <div class="form-group col-md-6">
                                        <label for="checkout-street-address">Exact Location</label><span style="color:red">*</span>
                                        
                                        <input min="100" required name="exact_location" value="{{ old('exact_location') }}" type="" class="form-control" id="checkout-street-address" placeholder="Eg. City, Town, street name">

                                        <div class="col-lg-10">
                                            
                                    </div>
                                        </div>
                                    </div>

                                 <!-- terms -->
                                 <div class="mb-2">
                                    <div class="form-group">
                                        <div class="form-check">
                                        <input class="form-check-input" type="checkbox" checked id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            I agree to the <a href="/terms" target="_blank">Terms of Service</a> and <a href="/privacy-policy" target="_blank">Privacy Policy</a>.*
                                        </label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Make Booking</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="mdg-features">
                    <div class="mdgf">
                        <div class="row">
                            <div class="col-2">
                                <div class="mdgf-icon">
                                    <span class="fas fa-coins fa-3x"></span>
                                </div>
                            </div>
                            <div class="col-10">
                                <div class="mdgf-text">  
                                    <span>Minimum deposit</span>
                                    <h6>
                                        Ksh.100
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mdgf">
                        <div class="row">
                            <div class="col-2">
                                <div class="mdgf-icon">
                                    <span class="far fa-clock fa-3x"></span>
                                </div>
                            </div>
                            <div class="col-10">
                                <div class="mdgf-text">  
                                    <span>Payment period</span>
                                    <h6>
                                        Pay at your own pace
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mdgf">
                        <div class="row">
                            <div class="col-2">
                                <div class="mdgf-icon">
                                    <span class="fas fa-percent fa-3x"></span>
                                </div>
                            </div>
                            <div class="col-10">
                                <div class="mdgf-text">  
                                    <span>No extra fees</span>
                                    <h6>
                                        0% interest rates
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mdgf">
                        <div class="row">
                            <div class="col-2">
                                <div class="mdgf-icon">
                                    <span class="fas fa-truck fa-3x"></span>
                                </div>
                            </div>
                            <div class="col-10">
                                <div class="mdgf-text">  
                                    <span>Doorstep delivery</span>
                                    <h6>
                                        Countrywide delivery Upon completion of payment
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
<!-- end --> 

@endsection

@section('extra-js')

<script type="text/javascript">

    $(document).ready(function() {
  $('#checkoutform').on('submit', function(e){
    // validation code here
    var emailaddress=$("#email").val();
    if( !validateEmail(emailaddress)) { /* do stuff here */
$("#email").focus();
$("#emailerror").html("Invalid Email Address *");
  e.preventDefault();
     }
     else{
        $("#email").removeClass("invalid").addClass("valid");
       
     }
  
  });
});
 function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{3,4})?$/;
  return emailReg.test( $email );
}

    function filter(){

        var x = document.getElementById("counties");
        var val = x.value;

        console.log(x.value);

        var subs = document.getElementsByClassName('locations');
        var subsNames = document.getElementsByClassName('locationsnames');
        var subsIds = document.getElementsByClassName('locationsid');
        var _arrayId = [];
        var _arrayName = [];
        var _arraySubsId = [];

        for(i =  0; i < subs.length; i++){
            if(subs[i].innerHTML == x.value){
                _arrayId.push(subs[i].innerHTML);
                _arrayName.push(subsNames[i].innerHTML);
                _arraySubsId.push(subsIds[i].innerHTML);
            }
            
        }

        var y = document.getElementById("subs");
        y.innerHTML = "";
        for(i = 0; i < _arrayId.length; i++){
            var node = document.createElement("option");
            // node.innerHTML = _array[i];
            node.setAttribute('value', _arraySubsId[i]);
            node.innerHTML = _arrayName[i];
            y.appendChild(node);  
        }

        console.log(_arrayId);


    }

    
    $('input[name="location"]').on('change', function() {
        $('.location-fields')
            .toggle(+this.value === 2 && this.checked);
            var x =document.getElementById('location_radio1');

                if(x.checked){
                    console.log('One Checked')
                    document.getElementById('counties').removeAttribute("required"); 
                    document.getElementById('subs').removeAttribute("required");
                    document.getElementById('id_parent').setAttribute("required", "");
                    document.getElementById('counties').value = '';
                    document.getElementById('subs').value = '';
                    var y = document.getElementById('within_nairobi');
                    y.style.display = "block";
                    
                }else{
                    console.log('Two Checked')
                    var y = document.getElementById('within_nairobi');
                    y.style.display = "none"; 
                    document.getElementById('id_parent').removeAttribute("required");
                    document.getElementById('counties').setAttribute("required", ""); 
                    document.getElementById('subs').setAttribute("required", "");
                    document.getElementById('id_parent').value = ''; 
                    y.removeAttribute("required");
                }
           
        }).change();



        var $zone = $( '#zone' ),
        $dropoff = $( '#dropoff' ),
        $options = $dropoff.find( 'option' );
            
        $zone.on( 'change', function() {
            $dropoff.html( $options.filter( '[value="' + this.value + '"]' ) );
        } ).trigger( 'change' );



        function addPickUp(){

            var x = document.getElementById("location_type").value;
            var y = document.getElementById('delivery_location');

            console.log("Selected => "+x);

            if(x === "home_or_office"){

                y.style.display = "block";

            }else{

                y.style.display = "none";
                y.removeAttribute("required");
            }

        }

        $('#location_radio2').click(function(){
            $('#within_nairobi').load(' #within_nairobi');
        })

        $('#location_radio1').click(function(){
            $('#location-fields').load(' #location-fields');
        })

        $(document).ready(function() {
            var y = document.getElementById('delivery_location');
            y.style.display = "none";
            y.removeAttribute("required"); 
        });


</script>

@endsection