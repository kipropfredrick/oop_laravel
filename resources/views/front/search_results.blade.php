@extends('layouts.app')

@section('title', $search)

@section('content')


<!-- breadcrumb --> 
<div class="bc-bg">
    <div class="container">
        <div class="bc-link">
            <a href="/">
                <i class="fas fa-home"></i>
            </a>

            <span class="bc-sep"></span>

            <!-- <a href=""> -->
                <span>{{ucfirst($search)}}</span>
            <!-- </a> -->

            <!-- <span class="bc-sep"></span> -->

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

                <?php 
                
                $count = \App\Products::where ( 'product_name', 'LIKE', '%' . $search . '%' )
                                    ->where('status','=','approved')
                                    ->where('quantity','>',0)
                                    ->orderBy('id','DESC')
                                    ->count(); 
                ?>

                <div>
                    <div class="filters">
                        <div class="p-count">
                            <label class="col-form-label">{{number_format($count)}} product(s) found</label>
                        </div>

                        <div class="p-filter">

                            <div class="p-sort">
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">Sort by:</label>
                                    <div class="col-7">
                                    <form action="/search?/{{$search}}" id="filter-form">
                                            <input type="hidden" id="search_i" name="search" value="{{$search}}">
                                            <select onchange="filter(this);" name="sort_by" id="sort_by" class="form-control">
                                                @if(isset($sort_by))
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
                                                @else
                                                    <option value="id">ID</option>
                                                    <option value="best-sellers">Best sellers</option>
                                                    <option value="price-asc">Low to high price</option>
                                                    <option value="price-desc">High to low price</option>
                                                @endif
                                            </select>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
                   

                <div class="p-grid product-list">
                @foreach($products as $product)
                    <div class="p-cat product-box">
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
           <!-- <div style="margin-left:5px">{{ $products->render()}}</div> -->

           <?php 
            $currentP = $products->currentPage();
            $nextP = $currentP+1;
            $lastp = $products->lastPage();
            $baseUrl = \URL::to('/');
            $url = $baseUrl.'/search?'.$search;
            $loadUrl = $url."?page=".$nextP;
            ?>


        @if($currentP!=$lastp)
        <div class="row justify-content-center">
            <!-- <a style="width:150px;margin-top:20px" class="btn btn-block load-more-btn" href="{{$loadUrl}}">Load more</a> -->
            <button data-totalResult="{{$count}}" style="width:150px;margin-top:20px" class="btn btn-block load-more-btn">Load more</button>
        </div>
        @endif

            
    </div>
    <!-- end products grid -->

        </div>
    </div>
    <!-- end products carousel -->
</div>

 

</div>

</div>




@endsection

@section('extra-js')

<script type="text/javascript">
    var current_url = window.location.href;
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".load-more-btn").on('click',function(){
            var _totalCurrentResult=$(".product-box").length;
            var search = $('#search_i').val();
            // Ajax Reuqest
            $.ajax({
                url:current_url,
                type:'post',
                dataType:'json',
                data:{
                    skip:_totalCurrentResult,
                    _token:"{{ csrf_token() }}",
                    search:search
                },
                beforeSend:function(){
                    $(".load-more-btn").html('Loading...');
                },
                success:function(response){
                    
                    var _html='';
                    var image="/storage/images/";
                    var p_url ="/product/";
                    var c_url ="/checkout/";
                    $.each(response,function(index,value){
                        _html+='<div class="p-cat product-box">';
                        _html+='<div class="p-c-sec">';
                            _html+='<div class="p-c-inner">';
                                _html+='<a href="'+p_url+value.slug+'">';
                                    _html+='<img src="'+image+value.product_image+'" alt="'+value.product_name+'">';
                                    _html+='<div class="p-c-name">'+value.product_name+'</div>';
                                    _html+='<div class="p-c-price">KSh.'+value.product_price+'</div>';
                                    _html+='<a href="'+c_url+value.slug+'" class="btn btn-block p-btn">Lipa Mos Mos</a>';
                                _html+='</a>';
                            _html+='</div>';
                        _html+='</div>';
                    _html+='</div>';
                    });
                    $(".product-list").append(_html);
                    // Change Load More When No Further result
                    var _totalCurrentResult=$(".product-box").length;
                    var _totalResult=parseInt($(".load-more-btn").attr('data-totalResult'));
                    console.log(_totalCurrentResult);
                    console.log(_totalResult);
                    if(_totalCurrentResult==_totalResult){
                        $(".load-more-btn").remove();
                    }else{
                        $(".load-more-btn").html('Load More');
                    }
                }
            });
        });
    });
</script>

@endsection