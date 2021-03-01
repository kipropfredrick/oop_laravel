@extends('layouts.app')

@section('content')


<!-- breadcrumb --> 
<div class="bc-bg">
    <div class="container">
        <div class="bc-link">
            <a href="/">
                <i class="fas fa-home"></i>
            </a>

            <span class="bc-sep"></span>

            <a href="">
                <span>{{ucfirst($search)}}</span>
            </a>

            <span class="bc-sep"></span>

        </div>
    </div>
</div>
<!-- end -->

<div class="bg-gray-alt">
    <!-- products grid -->
    <div class="container">
        <div>
            <div class="ht mb-3">
                <h5>{{ucfirst($search)}} Search Results</h5>

                <div>
                    <div class="filters">
                        <div class="p-count">
                            <label class="col-form-label">{{count($products)}} product(s) found</label>
                        </div>

                        <div class="p-filter">

                            <div class="p-sort">
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">Sort by:</label>
                                    <div class="col-7">
                                        <select id="delivery-station" class="form-control">
                                            <option selected>ID</option>
                                            <option>Best sellers</option>
                                            <option>Low to high price</option>
                                            <option>High to low price</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
                   

                <div class="p-grid">
                @foreach($products as $product)
                    <div class="p-cat">
                        <div class="p-c-sec">
                            <div class="p-c-inner">
                                <a href="/product/{{$product->slug}}">
                                    <img src="/storage/images/{{$product->product_image}}" alt="{{$product->product_name}}">
                                    <div class="p-c-name">{{$product->product_name}}</div>
                                    <div class="p-c-price">KSh.{{number_format($product->product_price)}}</div>

                                    <a href="/checkout/{{$product->slug}}" class="btn btn-block p-btn">Lipa Mos Mos</a>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
           </div>
           <div style="margin-left:5px">{{ $products->render()}}</div>
    </div>
    <!-- end products grid -->

        </div>
    </div>
    <!-- end products carousel -->
</div>

 

</div>

</div>




@endsection