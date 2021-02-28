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

            <a href="/category/{{$category->slug}}">
                <span>{{$category->category_name}}</span>
            </a>

            <span class="bc-sep"></span>

            <a href="/subcategory/{{$subcategory->id}}">
                <span>{{$subcategory->subcategory_name}}</span>
            </a>

            <!-- <span class="bc-sep"></span>

            <span>Third Categorisation Name</span> -->

            <!-- this section applies for categories, sub categories, third category and brand //
            remove some elements to fit each level // last span is not a link //
            remove this comment after implementation -->
        </div>
    </div>
</div>
<!-- end -->

<div class="bg-gray-alt">
    <!-- products grid -->
    <div class="container">
        <div>
            <div class="ht mb-3">
                <h5>{{$category->category_name}} /{{$subcategory->subcategory_name}}</h5>

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
            
            <div id="product-carousel">
                <div class="slick">

				 @forelse($trendingProducts as $product)
                    <div class="p-c-sec">
                        <div class="p-c-inner">
                            <a href="/product/{{$product->slug}}">
                                <img src="/storage/images/{{$product->product_image}}" alt="Product Name">
                                <div class="p-c-name">{{$product->product_name}}</div>
                                <div class="p-c-price">KSh.{{number_format($product->product_price)}}</div>

                                <a href="/product/{{$product->slug}}" class="btn btn-block p-btn">Lipa Mos Mos</a>
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

 <!-- {{ $products->links() }} -->


 <div class="bg-white">
    <!-- products carousel -->
    <div class="container">
        <div class="mb-3">
            <div class="ht mb-3">
                <h5>{{$subcategory->subcategory_name}} Weekly Best Sellers</h5>
            </div>

            <div id="product-carousel">
                <div class="slick">

				 @forelse($trendingProducts as $product)
                    <div class="p-c-sec">
                        <div class="p-c-inner">
                            <a href="/product/{{$product->slug}}">
                                <img src="/storage/images/{{$product->product_image}}" alt="Product Name">
                                <div class="p-c-name">{{$product->product_name}}</div>
                                <div class="p-c-price">KSh.{{number_format($product->product_price)}}</div>

                                <a href="/product/{{$product->slug}}" class="btn btn-block p-btn">Lipa Mos Mos</a>
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

</div>

</div>




@endsection