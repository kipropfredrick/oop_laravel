@extends('front.app')

@section('content')

   <div class="container">

        <section>

        <div class="site__body">
            <div class="page-header">
            <div class="page-header__container container">
            <div class="page-header__breadcrumb">
            </div>
            </div>
            </div>
                <div class="block">
                <div class="container">
                <div class="product product--layout--standard" data-layout="standard">
                <div class="product__content">
                    <!-- .product__gallery -->
                    <div class="product__gallery">
                        <div class="product-gallery">
                            <div class="product-gallery__featured">
                                <div class="owl-carousel" id="product-image">
                                                    <a href="/storage/images/{{$product->product_image}}" target="_blank">
                                                        <img src="/storage/images/{{$product->product_image}}" alt="">
                                                    </a>
                                                    @foreach($product->gallery as $gallery)
                                                        <a href="/storage/gallery/images/{{$gallery->image_path}}" class="product-gallery__carousel-item">
                                                            <img class="product-gallery__carousel-image" src="/storage/gallery/images/{{$gallery->image_path}}" alt="">
                                                        </a>
                                                    @endforeach
                                                    </div>
                                                </div>
                                                <div class="product-gallery__carousel">
                                                    <div class="owl-carousel" id="product-carousel">
                                                        <a class="product-gallery__carousel-item" href="/storage/images/{{$product->image}}" target="_blank">
                                                            <img src="/storage/images/{{$product->product_image}}" alt="">
                                                        </a>
                                                        @foreach($product->gallery as $gallery)
                                                            <a href="/storage/gallery/images/{{$gallery->image_path}}" class="product-gallery__carousel-item">
                                                                <img class="product-gallery__carousel-image" src="/storage/gallery/images/{{$gallery->image_path}}" alt="">
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                                            <!-- .product__gallery / end -->
                                                            <!-- .product__info -->
                                                            <div class="product__info">
                                                                <h1 class="product__name">{{$product->product_name}} ({{$product->product_code}})</h1>


                                                                <div class="spec__section">
                                                                    {!!$product->highlights!!} 
                                                                </div>

                                                                <ul class="product__meta">
                                                                    <li class="product__meta-availability">Availability: 
                                                                    @if($product->quantity>0)
                                                                        <span class="text-success">In Stock</span>
                                                                        @else
                                                                        <span class="text-danger">Out of Stock</span>
                                                                        @endif
                                                                    </li>
                                                                    @if(isset($product->influencer))
                                                                    <li>
                                                                        <a href="/shop/{{$product->influencer->id}}">
                                                                            <div  class="tag _glb _sm">{{$product->influencer->store_name}}</div>
                                                                        </a>
                                                                    </li>
                                                                   @else
                                                                    <li class="product__meta-availability">Sold by: 
                                                                      @if(isset($product->vendor))
                                                                        <span class="text-success">{{$product->vendor->user->name}}</span>
                                                                        @elseif(isset($product->agent))
                                                                        <span class="text-danger">{{$product->agent->user->name}}</span>
                                                                        @else
                                                                        <span class="text-success">Admin</span>
                                                                        @endif
                                                                    @endif    
                                                                    </li>
                                                                    <li class="product__meta-availability"> Condition: 
                                                                        <span class="text-success" style="text-transform: uppercase;">{{$product->condition}}</span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <!-- .product__info / end -->
                                                            <!-- .product__sidebar -->
                                                            <div class="product__sidebar">
                                                                <div class="product__availability">Availability: 
                                                                @if($product->quantity>0)
                                                                    <span class="text-success">In Stock</span>
                                                                 @else
                                                                    <span class="text-danger">Out of Stock</span>
                                                                @endif
                                                                </div>
                                                                <div class="product__prices">KSh {{number_format($product->product_price)}}</div>
                                                        <!-- .product__options -->
                                                        <!-- <form action="/checkout/{{$product->slug}}" method="post" class="product__options"> -->
                                                                <!-- @csrf -->
                                                                <div class="form-group product__option">
                                                                    <label class="product__option-label" for="product-quantity">Quantity</label>
                                                                    <div class="product__actions">
                                                                        <div class="product__actions-item">
                                                                            <div class="input-number product__quantity">
                                                                                <input disabled name="product_quantity" id="product-quantity" class="input-number__input form-control form-control-lg" type="number" min="1" value="1">
                                                                                    <!-- <div class="input-number__add"></div>
                                                                                    <div class="input-number__sub"></div> -->
                                                                                </div>
                                                                            </div>
                                                                            <div class="product__actions-item product__actions-item--addtocart row">
                                                                                <a  href="/checkout/{{$product->slug}}" class="btn btn-primary">Lipia polepole</a>
                                                                                <a style="margin-left:10px" href="/checkout/bonga/{{$product->slug}}" style="padding:7px" class="btn btn-success product-card__addtocart" type="button">Lipa Na Bonga</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <!-- </form> -->
                                                                <!-- .product__options / end -->
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="product-tabs">
                                                        <div class="product-tabs__list">
                                                            <a href="#tab-description" class="product-tabs__item product-tabs__item--active">Description</a>
                                                            <a href="#tab-specification" class="product-tabs__item">Highlights</a>
                                                            <!-- <a href="#tab-reviews" class="product-tabs__item">Reviews</a> -->
                                                        </div>
                                                        <div class="product-tabs__content">
                                                            <div class="product-tabs__pane product-tabs__pane--active" id="tab-description">
                                                                <div class="typography">
                                                                    <h3>Product Full Description</h3>
                                                                        <p>{!!$product->description!!}</p>
                                                                </div>
                                                            </div>
                                                            <div class="product-tabs__pane" id="tab-specification">
                                                                <div class="spec">
                                                                    <h3 class="spec__header">Specification</h3>
                                                                    <div class="spec__section">
                                                                        {!!$product->highlights!!} 
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                        </section>

            </div>
@endsection