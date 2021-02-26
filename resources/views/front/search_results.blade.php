@extends('layouts.app')

@section('content')


<div class="container">

<div  class="row" style="margin-bottom:60px;margin-top:40px">
  @forelse($products as $product)
    <div class="block-products-carousel__column col-md-3" style="margin-bottom:10px">
	<div class="block-products-carousel__cell">
		<div class="product-card" style="min-height:370px !important;">
			<button class="product-card__quickview" type="button">
				<svg width="16px" height="16px">
					<use xlink:href="images/sprite.svg#quickview-16"></use>
				</svg>
				<span class="fake-svg-icon"></span>
			</button>
			<div class="product-card__image">
				<a href="/product/{{$product->slug}}">
					<img style="width:100%;height:250px;object-fit:contain;" src="/storage/images/{{$product->product_image}}" alt="">
					</a>
					@if(isset($product->influencer))
					<a href="/shop/{{$product->influencer->id}}">
						<div  class="tag _glb _sm">{{$product->influencer->store_name}}</div>
					</a>
					@endif
				</div>
				<div class="product-card__info">
					<div class="product-card__name">
						<a style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2; -webkit-box-orient: vertical;" href="/product-details/{{$product->slug}}">{{ucfirst($product->product_name)}}</a>
					</div>
				</div>
				<div  class="product-card__actions">
					<div style="margin-left:15px" class="product-card__availability">Availability: 
						<span class="text-success">In Stock</span>
					</div>
					<div style="margin-left:15px" class="product-card__prices">Ksh {{number_format($product->product_price)}}</div>
					<div style="margin:15px" class="product-card__buttons row">
						<a href="/checkout/{{$product->slug}}" style="padding:7px" class="btn btn-primary product-card__addtocart" type="button">Lipia Polepole</a>
						<a href="/checkout/bonga/{{$product->slug}}" style="padding:7px" class="btn btn-success product-card__addtocart" type="button">Lipa Na Bonga</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	@empty
	<div style="color:#FFF;width:100%;padding:10px" class="bg-secondary text-center">No items in this category</div>
    @endforelse

 </div>

 {{ $products->links() }}

</div>


@endsection