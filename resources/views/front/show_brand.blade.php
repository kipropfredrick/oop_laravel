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

            <a href="/brand/{{$brand->slug}}">
                <span>{{$brand->brand_name}}</span>
            </a>

        </div>
    </div>
</div>
<!-- end -->

<div class="bg-gray-alt">
    <!-- products grid -->
    <div class="container">
        <div>
            <div class="ht mb-3">
                <h5>{{$brand->brand_name}}</h5>

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
                                    <form action="/brand/{{$brand->slug}}" id="filter-form">
                                            <select onchange="filter(this);" name="sort_by" id="sort_by" class="form-control">
                                                @if ($sort_by == "id")
                                                    <option value="id">ID</option>
                                                    <option value="best-sellers">Best sellers</option>
                                                    <option value="price-asc">Low to high price</option>
                                                    <option value="price-desc">High to low price</option> 
                                                @elseif($sort_by == "best-sellers")
                                                    <option value="best-sellers">Best sellers</option>
                                                    <option value="id">ID</option>
                                                    <option value="price-asc">Low to high price</option>
                                                    <option value="price-desc">High to low price</option> 
                                                @elseif($sort_by == "price-asc")
                                                    <option value="price-asc">Low to high price</option>
                                                    <option value="price-desc">High to low price</option> 
                                                    <option value="best-sellers">Best sellers</option>
                                                    <option value="id">ID</option>
                                                @elseif($sort_by == "price-desc")
                                                    <option value="price-desc">High to low price</option>
                                                    <option value="best-sellers">Best sellers</option>
                                                    <option value="id">ID</option>
                                                    <option value="price-asc">Low to high price</option>
                                                @else
                                                    <option value="id">ID</option>
                                                    <option value="best-sellers">Best sellers</option>
                                                    <option value="price-asc">Low to high price</option>
                                                    <option value="price-desc">High to low price</option> 
                                                @endif
                                            </select>
                                        </form>
                                    </div>
                                    <input type="hidden" name="url" id="url" value="{{Request::url()}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div>

            
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
                <h5>{{$brand->brand_name}} Weekly Best Sellers</h5>
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

</div>


@endsection