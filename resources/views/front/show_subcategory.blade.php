@extends('layouts.app')

@section('title', $category->category_name)

@section('content')

<?php 
    $current_sub = $subcategory;
?>

<!-- breadcrumb --> 
<div class="bc-bg">
    <div class="container">
        <div class="bc-link">
            <a href="/">
                <i class="fas fa-home"></i>
            </a>

            <span class="bc-sep"></span>

            <a href="/cat/{{$category->slug}}">
                <span>{{$category->category_name}}</span>
            </a>

            <span class="bc-sep"></span>

            <span>{{$current_sub->subcategory_name}}</span>

        </div>
    </div>
</div>
<!-- end -->

<input type="hidden" id="subcategory_id" value="{{$subcategory->id}}">

<div class="bg-gray-alt">
    <!-- products section -->
    <div class="container">
        <div class="lmm-p-listing-page">
            <div>
                <!-- sidebar -->
                <div class="lmm-sb-sec">
                    <div class="lmm-sidebar lmmsb-hide-sm">
                        <!-- categories filter -->
                        <div class="lmmsb-sec">
                            <div class="lmmsbt">
                                <h5>SUB CATEGORY</h5>
                            </div>

                            @foreach(\App\SubCategories::where('category_id',$category->id)->get() as $sub)

                            <div class="lmmsbt">
                                <span class="far <?php if($current_sub->id == $sub->id){echo 'fa-arrow-alt-circle-right';}else{echo 'fa-arrow-alt-circle-left';} ?>"></span> 
                                <a href="/sub/{{$sub->slug}}">{{$sub->subcategory_name}}</a>
                            </div>

                            @if($current_sub->id == $sub->id)
                            <!-- sub/tlc list -->
                            <div class="lmmslist">
                                <ul>
                                    @foreach($sub->thirdlevelcategories as $tlc)
                                    <a href="/tlc/{{$sub->slug}}/{{$tlc->slug}}"><li>{{$tlc->name}}</li></a>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @endforeach


                        </div>

                        <!-- brand filter -->
                        <div class="lmmsb-sec">
                            <div class="lmmsbt">
                                <h5>Brands</h5>
                                <!-- brands listed are only the ones associated with the category selected -->
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" id="brandSearch" placeholder="Type to search live...">
                            </div>

                            <div id="brandList" class="lmmsbfilter lmmsb-sec-scr">
                                @foreach($brands as $brand)
                                <div class="form-check">
                                    <input type="checkbox" <?php if(!empty($current_b) && $current_b->id === $brand->id){echo "checked";} ?> class="form-check-input" onclick="brandClicked('{{$brand->slug}}')" id="briPhone">
                                    <label class="form-check-label" for="briPhone">{{$brand->brand_name}}</label>
                                </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>

                <!-- archive listing -->
                <div class="lmm-p-listing-sec">
                    <!-- category / brand name / title -->
                    <div class="ht-title">
                        <h5>{{$current_sub->subcategory_name}} <?php if(!empty($current_b)){echo " / ".$current_b->brand_name;} ?></h5>
                    </div>

                    <div class="ht mb-3">

                    <?php 
                     $count = App\Products::where('quantity','>',0)
                                           ->where('status','=','approved')
                                           ->where('subcategory_id',$current_sub->id)
                                           ->where(function($query) use ($current_b)
                                           {
                                               if (!empty($current_b)) {
                                                   $query->where('brand_id', $current_b->id);
                                               }
                                           })
                                           ->count(); 
                    ?>

                        <div>
                            <div class="filters">
                                <div class="p-count">
                                    <label class="col-form-label">{{number_format($productsCount)}} product(s) found</label>
                                </div>

                                <div class="p-filter">

                                    <div class="p-sort">
                                        <div class="form-group row">
                                            <label class="col-3 col-form-label">Sort by:</label>
                                            <div class="col-7">
                                            
                                            <form action="/sub/{{$subcategory->slug}}" id="filter-form">
                                                <select onchange="filter(this);" name="sort_by" id="sort_by" class="form-control">
                                                <option <?php if($sort_by == "id"||$sort_by == ""){echo "selected";} ?> value="id">ID</option>
                                                <option <?php if($sort_by == "best-sellers"){echo "selected";} ?> value="best-sellers">Best sellers</option>
                                                <option <?php if($sort_by == "price-asc"){echo "selected";} ?> value="price-asc">Low to high price</option>
                                                <option <?php if($sort_by == "price-desc"){echo "selected";} ?> value="price-desc">High to low price</option> 
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
                        

                        <div class="p-grid product-list">
                            @foreach($products as $product)
                                <div class="p-cat product-box">
                                    <div class="p-c-sec">
                                        <div class="p-c-inner">
                                            <a href="/product/{{$product->slug}}">
                                                <img src="/storage/images/{{$product->product_image}}" alt="{{$product->product_name}}">
                                                <div class="p-c-name">{{$product->product_name}}</div>
                                                <div class="p-c-price">KSh.{{number_format($product->product_price)}}</div>

                                                <a href="/checkout/{{$product->slug}}" class="btn btn-sm btn-block p-btn">Lipa Mos Mos</a>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                        </div>

                    
                        @if($count==0)
                            <div class="text-center">
                                <img class="img-fluid" src="{{asset('images/crying-face.png')}}" alt="">
                                <h6 class="text-center">No Products Found!</h6>
                            </div>
                        @endif

                        <?php 
                        $currentP = $products->currentPage();
                        $nextP = $currentP+1;
                        $lastp = $products->lastPage();
                        $baseUrl = \URL::to('/');
                        $url = $baseUrl.'/sub/'.$subcategory->slug;
                        $loadUrl = $url."?page=".$nextP;
                        ?>


                        @if($currentP!=$lastp)
                            <div class="row justify-content-center">
                                <button data-totalResult="{{$count}}" style="width:150px;margin-top:20px" class="btn btn-block load-more-btn">Load more</button>
                            </div>
                        @endif

                        
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- end products grid -->
</div>

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
                                <img src="/storage/images/{{$product->product_image}}" alt="{{$product->product_name}}">
                                <div class="p-c-name">{{$product->product_name}}</div>
                                <div class="p-c-price">KSh.{{number_format($product->product_price)}}</div>
                                
                                <div class="text-center">
                                    <a href="/checkout/{{$product->slug}}" class="btn btn-sm p-btn btn-block">Lipa Mos Mos</a>
                                </div>
                                
                            </a>
                        </div>
                    </div>
                    @empty
                    
                    <div class="text-center">
                        <img style="margin-right:auto;margin-left:auto;" class="img-fluid" src="{{asset('images/crying-face.png')}}" alt="">
                        <h6 style="margin-right:auto;margin-left:auto;" class="text-center">No Products Found!</h6>
                    </div>

                    @endforelse

                </div>
            </div>
        </div>
    </div>
    <!-- end products carousel -->
</div>

<!-- handheld filter toggle --> 
<div class="lmm-filter-toggle">
    <div>
        <div class="hh-ft-sec">
            <!-- sort section -->
            <div class="hh-ft-sort">
                <div class="form-group">
                    <div class="">
                        <form action="/sub/{{$subcategory->slug}}" id="filter-form_mob">
                            <select onchange="filter_mob(this);" name="sort_by" id="sort_by" class="form-control">
                                <option <?php if($sort_by == "id"||$sort_by == ""){echo "selected";} ?> value="id">Sort by ID</option>
                                <option <?php if($sort_by == "best-sellers"){echo "selected";} ?> value="best-sellers">Best sellers</option>
                                <option <?php if($sort_by == "price-asc"){echo "selected";} ?> value="price-asc">Low to high price</option>
                                <option <?php if($sort_by == "price-desc"){echo "selected";} ?> value="price-desc">High to low price</option> 
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <!-- filter section -->
            <div class="hh-ft-filter">
                <div class="form-group rw">
                    <div class="">
                        <a href="#" class="btn btn-block p-btn" data-toggle="modal" data-target="#hhFilterModal">Filter <i class="fas fa-filter"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end -->

<!-- handheld filter options -->
<div class="lmm-filter-options">
    <div class="modal fade" id="hhFilterModal" tabindex="-1" aria-labelledby="hhModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <!-- filter title -->
                <div class="modal-header">
                    <h5 class="modal-title" id="hhModalLabel">Get exactly what you want!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="lmm-times" aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- filter options -->
                <div class="modal-body">
                    <div class="lmm-sidebar">
                        <!-- categories filter -->
                        <div class="lmmsb-sec">
                            
                        <div class="lmmsbt">
                                <h5>SUB CATEGORY</h5>
                            </div>

                            @foreach(\App\SubCategories::with('thirdlevelcategories')->where('category_id',$category->id)->get() as $sub)

                            <div class="lmmsbt">
                                <span class="far <?php if($current_sub->id == $sub->id){echo 'fa-arrow-alt-circle-right';}else{echo 'fa-arrow-alt-circle-left';} ?>"></span> 
                                <a href="/sub/{{$sub->slug}}">{{$sub->subcategory_name}}</a>
                            </div>

                            @if($current_sub->id == $sub->id)
                            <!-- sub/tlc list -->
                            <div class="lmmslist">
                                <ul>
                                    @foreach($sub->thirdlevelcategories as $tlc)
                                    <a href="/tlc/{{$sub->slug}}/{{$tlc->slug}}"><li>{{$tlc->name}}</li></a>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @endforeach
                            
                        </div>

                        <!-- brand filter -->
                        <div class="lmmsb-sec">
                            <div class="lmmsbt">
                                <h5>Brands</h5>
                                <!-- brands listed are only the ones associated with the category selected -->
                            </div>

                            <div class="form-group">
                                <input type="text"  class="form-control" id="brandSearch2" placeholder="Type to search live...">
                            </div>

                            <div id="brandList2" class="lmmsbfilter lmmsb-sec-scr">
                               
                                @foreach($brands as $brand)
                                <div class="form-check">
                                    <input type="checkbox" <?php if(!empty($current_b) && $current_b->id === $brand->id){echo "checked";} ?> class="form-check-input" onclick="brandClicked('{{$brand->slug}}')" id="briPhone">
                                    <label class="form-check-label" for="briPhone">{{$brand->brand_name}}</label>
                                </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>

                <!-- buttons -->
                <div class="modal-footer">
                    <button type="button" class="btn p-btn-sec" data-dismiss="modal">Close</button>
                    <button type="button" class="btn p-btn">Save Filter Options</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end --> 

<!-- <input type="hidden" name ="url" id="url" value="/cat/{{$category->slug}}"> -->

@endsection

@section('extra-js')

<script type="text/javascript">
    var current_url = window.location.href;
    var url = $('#url').val();
</script>
<script type="text/javascript">

    function brandClicked(slug){
        // console.log('Slug => '+slug);
        window.location.replace(url+"?brand="+slug);
    }

    $(document).ready(function(){
        $(".load-more-btn").on('click',function(){
            var _totalCurrentResult=$(".product-box").length;
            // Ajax Reuqest
            $.ajax({
                url:current_url,
                type:'post',
                dataType:'json',
                data:{
                    skip:_totalCurrentResult,
                    _token:"{{ csrf_token() }}"
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
                                    _html+='<a href="'+c_url+value.slug+'" class="btn btn-sm btn-block p-btn">Lipa Mos Mos</a>';
                                _html+='</a>';
                            _html+='</div>';
                        _html+='</div>';
                    _html+='</div>';
                    });
                    $(".product-list").append(_html);
                    // Change Load More When No Further result
                    var _totalCurrentResult=$(".product-box").length;
                    var _totalResult=parseInt($(".load-more-btn").attr('data-totalResult'));
                    // console.log("_totalCurrentResult => "+_totalCurrentResult);
                    // console.log("_totalResult => "+_totalResult);
                    if(_totalCurrentResult==_totalResult){
                        $(".load-more-btn").remove();
                        // console.log('End of list');
                    }else{
                        $(".load-more-btn").html('Load More');
                    }
                }
            });
        });
    });

    $('#brandSearch').keyup(function () {
            var searchTerm = $('#brandSearch').val();
            var category_id = $('#category_id').val();
            var url = '/brand/search';

            $.ajax({
                url : url,
                type: "POST", 
                data: {searchTerm: searchTerm,_token:"{{ csrf_token() }}"},
                async : false, 
                success: function(response, textStatus, jqXHR) {
                    // console.log(response);
                    var _html='';
                    if(response.length>0){
                        $.each(response,function(index,value){
                            var slug = value.slug;
                            // console.log('slug => '+slug);
                            _html+='<div class="form-check">';
                            _html+='<input type="checkbox"  class="form-check-input" onclick="brandClicked(\''+slug+'\')" id="briPhone">';
                            _html+='<label class="form-check-label" for="briPhone">'+value.brand_name+'</label>';
                            _html+='</div>';
                        });
                    }else{
                        _html='No results found';
                    }
                    // console.log('_html => '+_html)
                    $(".lmmsbfilter").html(_html);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
            

    });

    $('#brandSearch2').keyup(function () {
            var searchTerm = $('#brandSearch2').val();
            var category_id = $('#category_id').val();
            var url = '/brand/search';

            $.ajax({
                url : url,
                type: "POST", 
                data: {searchTerm: searchTerm,_token:"{{ csrf_token() }}"},
                async : false, 
                success: function(response, textStatus, jqXHR) {
                    // console.log(response);
                    var _html='';
                    if(response.length>0){

                            $.each(response,function(index,value){
                                var slug = value.slug;
                                // console.log('slug => '+slug);
                                _html+='<div class="form-check">';
                                _html+='<input type="checkbox"  class="form-check-input" onclick="brandClicked(\''+slug+'\')" id="briPhone">';
                                _html+='<label class="form-check-label" for="briPhone">'+value.brand_name+'</label>';
                                _html+='</div>';
                            });

                    }else{
                        _html='No results found';
                    }

                    $("#brandList2").html(_html);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
            

    });

    function filter_cat(sel)
    {
        var formAction = $('#filter-form').attr("action");
        var sort_by = $('#sort_by').val();
        var url = formAction+'?sort_by='+sort_by;
        console.log('sort_by => '+sort_by);
        window.location.href = url;
        // $('#filter-form').submit();
    }

    function filter_mob(sel)
    {
        // var formAction = $('#filter-form_mob').attr("action");
        // var sort_by = $('#sort_by_mob').val();
        // var url = formAction+'?sort_by='+sort_by;
        // window.location.href = url;
        $('#filter-form_mob').submit();
    }


</script>

@endsection