@extends('backoffice.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>New Booking</strong></h6>
		</div>
		
		<div class="m-4">
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
       
        
        <form  class="row" method="post" action="/vendor/make-booking">
            @csrf
            <div class="col-4 form-group ml-4 mt-0">
                <label>
                    Select category
                </label>
                <select class="form-control" onchange="return getpostdata(1,this.value)" id="lev1">
                  <option value="">Select Category</option>
                  @foreach($categories as $category)

  <option value="{{$category->id}}">
                 {{$category->category_name}}       
                    </option>

                  @endforeach
                </select>
                
            </div>

            <div class="col-4 form-group ml-4 mt-0">
                <label>
                    Select subcategory items
                </label>
                <select class="form-control" id="sel2" onchange="return getpostdata(2,this.value)">
                  <option>Select Category</option>
          
                </select>
                
            </div>

                  <div class="col-4 form-group ml-4 mt-0">
                <label>
                       Select third level category
                </label>
                <select class="form-control" id="sel3" onchange="return getpostdata(3,this.value)">
              <option>Select category</option>
                </select>
                
            </div>


                  <div class="col-4 form-group ml-4 mt-0">
                <label>
                   Select product
                </label>
                <select class="form-control" id="sel4" onchange="return getpostdata(4,this.value)">
                  <option>Select product</option>
              
                </select>
                
            </div>
            
   

            <!-- checkout -->
            <div class="col-12" >
                <div class="checkout">
                    <div class="card">
                        <div class="m-4">
                            <!-- summarry -->
                            <div>
                                <p>You are placing an order for <strong><span id="item_name">test</span></strong>. Minimum deposit is <strong>KSh.100</strong>.</p>
                                
                                <hr/>
                            </div>
                            <!-- end --> 

                            <input type="hidden" name="quantity" id="product_quantity" value="1">
                                <input type="hidden" name="product_id" id="productid">
                                <input name="status" value="pending" type="hidden">
                                <input name="minDeposit" value="100" type="hidden">

                                <?php 
                                
                                $vendor = \App\Vendor::where('user_id',Auth::user()->id)->first();

                                if($vendor!=null){
                                    $vendor_code = $vendor->vendor_code;
                                }
                                ?>
                                <input name="vendor_code" value="@if(isset($vendor_code)){{$vendor_code}}@endif" type="hidden">
                                <h4>Personal Details</h4>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="checkout-first-name">Full name</label><span style="color:red">*</span>
                                        <input required name="name" type="text" class="form-control" id="checkout-first-name" value="{{ old('name') }}" placeholder="Full Name" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="checkout-company-name">Phone Number<span style="color:red">*</span>
                                        </label>
                                        <input required name="phone" type=""  class="form-control" id="checkout-company-name" placeholder="07XXXXXXXX" value="{{ old('phone') }}">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="checkout-last-name">Email</label> &nbsp; &nbsp;<span style="color:red" id="emailerror">*</span>
                                        <input required type="email" id="email"  name="email" type="text" class="form-control"  placeholder="Email Address" value=" ">
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
                                        <select id="counties" class="js-example-placeholder-single js-states form-control" name="county_id" placeholder="Enter name" type="text" class="form-control @if($errors->has('county_id')) invalid_field @endif" onchange="filter()" required>
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
                         
                        </div>
                    </div>
                </div>
        
</div>
 <div style="position: absolute; left: 40%; margin-top: 150px;" id="loading">
            <img src="{{asset('images/spinner.gif')}}" style="width: 100px;height: 100px;">
        </div>
</form>

            </div>
	</div>

@endsection

@section('extra-js')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script type="text/javascript">
    // A $( document ).ready() block.
$( document ).ready(function() {
 $("#loading").hide();
 $('#counties').select2({
    width:"100%",
            tags: true,
            "padding":"20px;",
            allowClear: true

          
        });

  $('#sel4').select2({
    width:"100%",
            tags: true,
            "padding":"20px;",
            allowClear: true

          
        });
});
 
    function getpostdata(level,id)
{


    var level=level;
    var id=id;
  
   $("#loading").show();
        $.ajax({
        url:"/vendor/getproducts",
        type:"GET",

        data:{
          id: id,
          level:level,
          vendor:'{{$vendor->id}}'
        },
        success:function(response) {
           //alert(JSON.stringify(response));
          //document.getElementById("total_items").value=response;
          if (level==1) {
            $("#loading").hide();
             $('#sel2').html("");
                     $('#sel2').append($('<option>', { 
        value: "",
        text : "Select sub category " 
    }));
            $.each(response, function (i, item) {
    $('#sel2').append($('<option>', { 
        value: item.id,
        text : item.subcategory_name 
    }));
});
          }
              if (level==2) {
            $("#loading").hide();
             $('#sel3').html("");
                 $('#sel3').append($('<option>', { 
        value: "",
        text : "Select third level category " 
    }));
            $.each(response, function (i, item) {
    $('#sel3').append($('<option>', { 
        value: item.id,
        text : item.name 
    }));
});
          }

           if (level==3) {
            $("#loading").hide();
             $('#sel4').html("");
                 $('#sel4').append($('<option>', { 
        value: "",
        text : "Select product " 
    }));
            $.each(response, function (i, item) {
    $('#sel4').append($('<option>', { 
        value: item.id,
        text : item.product_name 
    }));
});
          }
          if (level==4) {
            $("#loading").hide();
$("#item_name").html(response.product_name);
$('#productid').val(response.id);

          }

       },
       error:function(){
         $("#loading").hide();
        alert("error");
       }

      });

}
</script>



@endsection

