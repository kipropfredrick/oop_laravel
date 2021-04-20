@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div class="mdg-top"></div>
<!-- space at the top of a bage without breadcrumbs -->

<div class="bg-white-alt">
    <div class="">
        <div class="home-banner">
             <?php $banner = \App\Banners::latest()->first(); ?>
             @if(empty($banner))
                <img src="assets/img/extra/mm-hb.jpg" alt="Lipa Mos Mos">
            @else
             <a href="{{$banner->link}}">
                <img src="/storage/banners/{{$banner->image}}" alt="Lipa Mos Mos">
             </a>
            @endif
        </div>
    </div>
</div>

<!-- page content -->
<div class="bg-gray">
    <!-- products carousel -->
    <div class="container">
        <div class="mb-3">
            <div class="ht mb-3">
                <h5>&#128293;  Today's Hot Deals</i></h5>
            </div>

            <div id="product-carousel">
                <div class="slick">
                    @forelse($products as $product)
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
                    @empty
                    <div class="text-center">
                      No Products
                    </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
    <!-- end products carousel -->

    <!-- products carousel -->
    <div class="container">
        <div class="mb-3">
            <div class="ht mb-3">
                <h5>&#x1F4AF; Weekly Best Sellers</h5>
            </div>

            <div id="product-carousel">
                <div class="slick">

                   @forelse($bestSellers as $product)
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
                    @empty
                    <div class="text-center">No products</div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
    <!-- end products carousel -->

    <!-- products carousel -->
    <div class="container">
        <div class="mb-3">
            <div class="ht mb-3">
                <h5>&#9889; Top Trending</h5>
            </div>

            <div id="product-carousel">
                <div class="slick">

                    @forelse($trendingProducts as $product)
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
                    @empty
                    <div class="text-center">No products</div>
                    @endforelse


                </div>
            </div>
        </div>
    </div>
    <!-- end products carousel -->
</div>
<!-- end -->

<div class="bg-white">
    <!-- categories -->
    <div class="container">
        <div class="mb-3">
            <div class="ht mb-3">
                <h5>&#128293; Explore all categories</h5>
            </div>

            <?php $categories = \App\Categories::all(); ?>

            <div class="c-grid">
                @forelse($categories as $category)
                    <div class="">
                            <div class="mdg-c">
                                <img src="/storage/images/{{$category->category_icon}}" alt="Category Name">
                                <span class="cat-name">
                                    <a href="/{{$category->slug}}">{{$category->category_name}}</a>
                                </span>
                            </div>
                     </div>
                @empty
                <div class="text-center">No categories</div>
                @endforelse
            </div>

        </div>
    </div>
    <!-- end categories -->

</div>

<div class="bg-gray">

    <!-- brands -->
    <div class="container">
        <div class="mb-3">
            <div class="ht mb-3">
                <h5>&#x1f60d; The brands you love</h5>
            </div>

            <?php $brands = \App\Brand::all(); ?>

            <div id="brands-carousel">
                <div class="slick">

                    @forelse($brands as $brand)
                    <div class="mdg-b">
                        <a href="/brand/{{$brand->slug}}">
                            <img src="/storage/images/{{$brand->brand_icon}}" alt="{{$brand->brand_name}}">
                        </a>
                    </div>
                    @empty
                    <div class="text-center">No brands</div>
                    @endforelse

                </div>

            </div>

        </div>
    </div>
    <!-- end brands -->

</div>
@endsection
