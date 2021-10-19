@extends('backoffice.app')

@section('content')

<div class="">
		         @if (session()->has('success'))

				<div class="alert alert-success fade show" role="alert">
					{{ session()->get('success') }}
				</div>

				@elseif (session()->has('error'))

				<div class="alert alert-danger fade show" role="alert">
					{{ session()->get('error') }}
				</div>

			@endif


		<div class="card">
              <div class="card-header">
                <h3 class="card-title">Add Product</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
 <form action="/vendor/vendor-savebooking" method="post" id="checkoutform">
                                @csrf
                                <input type="hidden" name="quantity" value="{{$product_quantity}}">
                               
                                <input name="status" value="pending" type="hidden">
                               
                                <?php 
                                
                                $vendor = \App\Vendor::whereUser_id(Auth()->user()->id)->first();

                                if($vendor!=null){
                                    $vendor_code = $vendor->vendor_code;
                                }
                                ?>
                                <input name="vendor_code" value="@if(isset($vendor_code)){{$vendor_code}}@endif" type="hidden">
                                <h4>Personal Details</h4>
      <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="checkout-first-name">Product Name</label><span style="color:red">*</span>
                                        <input required name="product_name" type="text" class="form-control" id="" value="{{ old('product_name') }}" placeholder="Product Name" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="checkout-company-name">Product price<span style="color:red">*</span>
                                        </label>
                                        <input required name="productPrice" type=""  class="form-control" id="e" placeholder="10000" value="{{ old('productPrice') }}">
                                    </div>
                                </div>

                                  <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="checkout-first-name">Delivery cost</label><span style="color:red">*</span>
                                        <input required name="delivery_cost" type="number" class="form-control" id="" value="{{ old('deleuevery_cost') }}" placeholder="delivery_cost(KSh)" >
                                    </div>
                              
                                </div>
<hr>
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
                                        <input required type="email" id="email"  name="email" type="text" class="form-control"  placeholder="Email Address" value="  ">
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

                                <button type="submit" class="btn btn-primary">Make Booking</button>
                            </form>
               
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
		
        
		</div>
@endsection
