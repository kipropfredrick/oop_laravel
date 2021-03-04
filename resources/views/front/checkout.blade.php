@extends('layouts.app')

@section('content')

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

<div class="container">

 <section style="margin-top:20px">
    <!-- site__body -->
    <div class="site__body">
        
        <div style="margin-top:20px" class="checkout block">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-6 col-xl-7">
                        <div class="card mb-lg-0">
                            <div class="card-body">

                            @if (session()->has('success'))

                                <div class="alert alert-success fade show" role="alert">
                                    {{ session()->get('success') }}
                                </div>

                                @elseif (session()->has('error'))

                                    <div class="alert alert-danger fade show" role="alert">
                                        {{ session()->get('error') }}
                                    </div>

                                @endif
                                <div class="">
                                <div>
                                <p>You are placing an order for <strong>{{$product->product_name}}</strong>. Minimum deposit id <strong>KSh.500</strong>.</p>
                                <a href="/checkout-with-existing/{{$product->slug}}">Have an account?</a>
                            </div>
                                       <!-- <a href="/checkout-with-existing/{{$product->slug}}" style="margin-left:5px" class="btn btn-outline-warning">Have an Account?</a> -->
                                </div>
                                
                                    <hr>
                                    <h4>Personal Details</h4>
                                
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                    <form action="/make-booking" method="post">
                                        @csrf
                                        <input type="hidden" name="quantity" value="{{$product_quantity}}">
                                        <input type="hidden" name="product_id" value="{{$product->id}}">
                                        <input name="status" value="pending" type="hidden">
                                        <input name="minDeposit" value="{{$minDeposit}}" type="hidden">
                                        <label for="checkout-first-name">Full name</label>
                                        <input required name="name" type="text" class="form-control" id="checkout-first-name" placeholder="Full Name">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="checkout-last-name">Email</label>
                                            <input required type="email" name="email" type="text" class="form-control" id="checkout-last-name" placeholder="Email Address">
                                            </div>
                                        </div>
                                          <div class="form-row">
                                          <div class="form-group col-md-6">
                                                <label for="checkout-company-name">Phone Number 
                                                </label>
                                                <input required name="phone" type="number" class="form-control" id="checkout-company-name" placeholder="07XXXXXXXX">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="checkout-street-address">Initial Deposit</label>
                                                <input min="500" required name="initial_deposit" type="number" class="form-control" id="checkout-street-address" placeholder="Initial deposit">
                                             </div>
                                          </div>


                                          <label for="location"><strong>Delivery Location</strong></label> <br>

                                          <div style="margin-top:10px" class="form-group">
                                            <input  type="radio" id="location_radio1" name="location" value="1"  checked> Within Nairobi <br>
                                            <input class="margin_top" type="radio" id="location_radio2" name="location" value="2" > Outside Nairobi
                                          </div>




                                          <div id="within_nairobi" class="within_nairobi">

                                            <?php 
                                            $zones = \App\NairobiZones::with('dropoffs')->get(); 
                                            $dropoffs = \App\NairobiDropOffs::all(); 
                                            ?>

                                            <div class="form-group">
                                                <label for="zone">Pick your preferred delivery location</label>
                                                <select class="form-control js-example-basic-single dependent-selects__parent" name="dropoff" id="id_parent" data-child-id="id_child" required>
                                                    <option value="">Select/Search Location</option>
                                                    @foreach($dropoffs as $dropoff)
                                                    <option value="{{$dropoff->id}}"  >{{$dropoff->dropoff_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                          </div>

                                          
                                          <div  id="location-fields" class="location-fields">

                                          <div class="form-row">
                                          <div class="form-group col-md-6">
                                            <label for="checkout-company-name">County</label>
                                            <?php 
                                            $counties = \App\Counties::all();
                                            $locations = \App\PickupLocation::all();
                                            ?>
                                              <select id="counties" class="form-control" name="county_id" placeholder="Enter name" type="text" class="form-control @if($errors->has('county_id')) invalid_field @endif" onchange="filter()" required>
                                                <option value="">Select county</option>
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
                                                <label for="checkout-street-address">Drop Off Location</label>
                                                @foreach($locations as $location)
                                                <div class="locations" style="display: none">{{$location->county_id}}</div>
                                                @endforeach

                                                @foreach($locations as $location)
                                                    <div class="locationsid" style="display: none">{{$location->id}}</div>
                                                @endforeach

                                                @foreach($locations as $location)
                                                    <div class="locationsnames" style="display: none">{{$location->center_name}}</div>
                                                @endforeach
                                                <select class="form-control" name="location_id" id="subs" placeholder="Enter name" type="text" class="form-control @if($errors->has('location_id')) invalid_field @endif" required>
                                                        <option value="">Select Pickup Location</option>
                                                    </select>
                                                    @error('location_id')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                    @enderror
                                                <div class="col-lg-10">
                                                   
                                            </div>
                                             </div>
                                          </div>


                                          </div>

                                             <!-- terms -->
                                            <div class="mb-2">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                                    <label class="form-check-label" for="terms">
                                                        I agree to the <a href="/terms" target="_blank">Terms of Service</a> and <a href="/privacy-policy" target="_blank">Privacy Policy</a>.*
                                                    </label>
                                                    </div>
                                                </div>
                                            </div>
                                                <button type="submit" class="btn btn-primary">Make Booking</button>
                                            </div>
                                            </form>
                                            <div class="card-divider"></div>
                                            </div>
                                            </div>

                                      <!-- features -->
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
                                        Ksh.500
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
                                        4 months
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
                                        Countrywide
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>



        </section>

</div>
@endsection

@section('extra-js')

<script type="text/javascript">

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
                    document.getElementById('counties').value = '';
                    document.getElementById('subs').value = '';
                    var y = document.getElementById('within_nairobi');
                    y.style.display = "block";
                    
                }else{
                    console.log('Two Checked')
                    var y = document.getElementById('within_nairobi');
                    y.style.display = "none"; 
                    document.getElementById('id_parent').removeAttribute("required");
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