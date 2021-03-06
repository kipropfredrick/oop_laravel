@extends('layouts.app')

@section('title', $product->product_name)

@section('content')

  <!-- breadcrumb --> 
<div class="bc-bg">
    <div class="container">
        <div class="bc-link">
            <a href="/">
                <i class="fas fa-home"></i>
            </a>

            <span class="bc-sep"></span>

            <a href="/category/{{$product->category->slug}}">
             
                <span>{{$product->category->category_name}}</span>
            </a>

            <span class="bc-sep"></span>

            <span>{{$product->product_name}}</span>
        </div>
    </div>
</div>
<!-- end -->

<!-- page content --> 
<div class="bg-white">
    <div class="container">
        <div class="bg-white">
            <div class="info-sec">
                <div class="row">
                    <!-- product carousel -->
                    <div class="col-sm-4">
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                               <div class="p-img carousel-item active">
                                    <img src="/storage/images/{{$product->product_image}}" class="d-block w-100" alt="Product Name">
                                </div>
                              @foreach($product->gallery as $gallery)
                                <div class="p-img carousel-item">
                                    <img src="/storage/gallery/images/{{$gallery->image_path}}" class="d-block w-100" alt="Product Name">
                                </div>
                               @endforeach
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>

                    <!-- product info -->
                    <div class="col-sm-5">
                        <div class="booking-sec">

                            <a href="/brand/{{$product->brand->slug}}">
                                <img style="height:50px;width:100px;object-fit:contain" src="/storage/images/{{$product->brand->brand_icon}}" alt="Brand Name">
                            </a> <!-- only appears when brand is selected when adding the product // the brand image -->

                            <h3 class="product-name">{{$product->product_name}}</h3>

                            <div>
                                Product Code: <span class="p-code">{{$product->product_code}}</span>
                            </div>


                            <div>
                                <a style="color:#F68B1E !important;margin-top:10px;margin-bottom:10px" href="/vendors/{{$product->vendor->slug}}">Sold By : <span class="p-code" >{{$product->vendor->business_name}}</span></a>
                            </div>


                            <div class="product-price">KSh. {{number_format($product->product_price)}}</div>

                           <div class="mb-3">
                                <div>
                                    <span class="fa fa-check-circle"></span>
                                    <small>Book with as little as <strong>KSh.100</strong></small>
                                </div>

                                <div>
                                    <span class="fa fa-check-circle"></span>
                                    <small>Pay the balance at <strong>your own pace</strong></small>
                                </div>

                                <div>
                                    <span class="fa fa-check-circle"></span>
                                    <small>Countrywide delivery upon <strong>completion of payment</strong></small>
                                </div>
                            </div>


                            <div>
                                <a href="/checkout/{{$product->slug}}" class="btn btn-block p-btn">Click to Order</a>
                             
                            </div>
                           
                            
                            <div class="highlights">
                                <h5>Key Highlights</h5>
                                {!!$product->highlights!!}
                            </div>
                        </div>
                    </div>

                    <!-- mdg features -->
                    <div class="col-sm-3">
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
                                                KSh.100
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

            <!-- product description --> 
            <div class="tabbable">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pdesc-tab" data-toggle="tab" href="#pdesc" role="tab" aria-controls="pdesc" aria-selected="true">Product Description</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="binfo-tab" data-toggle="tab" href="#binfo" role="tab" aria-controls="binfo" aria-selected="false">How to Order</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="pdesc" role="tabpanel" aria-labelledby="pdesc-tab">
                        <div class="p-desc mt-3">
                          {!!$product->description!!}
                        </div>
                    </div>

                    <div class="tab-pane fade" id="binfo" role="tabpanel" aria-labelledby="binfo-tab">
                        <div class="mt-3">
                            <div class="row">
                                <!-- website booking -->
                                <div class="col-sm-6">
                                    <h3>
                                        How to order on the website
                                    </h3>
                                    <ul>
                                        <li>On your selected item, click on the <strong>Lipa Pole Pole</strong> button</li>
                                        <li>Enter your name, phone number and email address</li>
                                        <li>Enter the amount you want to pay. We accept a minimum of <strong>KSh.100</strong> on all items</li>
                                        <li>Click on the <strong>Proceed to Pay</strong> button</li>
                                        <li>You'll be prompted to enter your M-Pesa pin to pay automatically</li>
                                        <li>If you don't receive the prompt, follow the steps sent to you on SMS to activate your booking</li>
                                    </ul>
                                </div>
                                <!-- <div class="col-sm-6">
                                    <h3>
                                        How to order with USSD
                                    </h3>
                                    <ul>
                                        <li>Dial <strong>*544*6#</strong> on your phone</li>
                                        <li>Select <strong>make a booking</strong></li>
                                        <li>Follow the prompt to reister your account if not registered</li>
                                        <li>Enter the product code <strong>{{$product->product_code}}</strong></li>
                                        <li>Enter the amount you want to pay. We accept a minimum of <strong>KSh.100</strong> on all items</li>
                                        <li>Click on the <strong>Proceed to Pay</strong></li>
                                        <li>You'll be prompted to enter your M-Pesa pin to pay automatically</li>
                                        <li>If you don't receive the prompt, follow the steps sent to you on SMS to activate your booking</li>
                                    </ul>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end -->

<div class="bg-gray">
    <!-- products carousel -->
    <div class="container">
        <div class="mb-3">
            <div class="ht mb-3">
                <h5>These might actually interest you</h5>
            </div>

            <div id="product-carousel">
                <div class="slick">

                <?php $products = \App\Products::where('category_id',$product->category_id)->where('status','=','approved')->skip(20)->inRandomOrder()->take(20)->get();  ?>

                @forelse($products as $product)
                    <div class="p-c-sec">
                        <div class="p-c-inner">
                            <a href="/product/{{$product->slug}}">
                                <img src="/storage/images/{{$product->product_image}}" alt="{{$product->product_name}}">
                                <div class="p-c-name">{{$product->product_name}}</div>
                                <div class="p-c-price text-center">KSh.{{$product->product_price}}</div>
                                <div class="text-center">
                                    <a href="/checkout/{{$product->slug}}" class="btn btn-sm p-btn">Lipa Mos Mos</a>
                                </div>
                            </a>
                        </div>
                    </div>
                @empty
                <div class="text-center">No Products</div>
                @endforelse

                    
                </div>
            </div>
        </div>
    </div>
    <!-- end products carousel -->
</div>


<script type="text/javascript">
new mosmos(
    {name:"Lipa Pole Pole",productName:"Dell Latitude E5420 Intel Core I5 4GB Ram 500GB HDD 14 Inches",imageSource:"https://mosmos.co.ke/storage/images/2021-03-09-09-25-05samsung-galaxy-tab-a7-10-4-lte-gold-32gb-and-3gb-ram-sm-t505n.jpg", productPrice:10,

    onApprove: function(data){

alert("approved");
alert(JSON.stringify(data));
},
onDecline:function(data){
    alert("declined");
    alert(JSON.stringify(data))
}


});
</script>

@endsection