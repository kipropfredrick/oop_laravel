<div class="mdg-top"></div>
<!-- space at the top of a bage without breadcrumbs -->

<div class="bg-white-alt">
    <div class="">
        <div class="home-banner">
            <img src="" alt="Lipa Mos Mos">
        </div>
    </div>
</div>

<!-- page content -->
<div class="bg-white">
    <!-- categories -->
    <div class="container">
        <div>
            <div class="ht mb-3">
                <h5>Explore our categories</h5>
            </div>

            <div>
                <!-- category row -->
                <div class="hc-grid">
                    <?php $categories = \App\Categories::all(); ?>
                    @foreach ($categories as  $category)
                   <div class="mos-hc">
                        <div class="row">
                            <div class="col-5">
                         <a href="/cat/{{$category->slug}}">
                            <img src="/storage/images/{{$category->category_icon}}" alt="{{$category->category_name}}">

                         </a>
                            </div>
                            <div class="col-7">
                                <div class="hc-name">
                                    <a href="/cat/{{$category->slug}}">{{$category->category_name}}</a>
                                </div>
                                <?php $subcategories = \App\SubCategories::where('category_id',$category->id)->get(); ?>

                                <div class="hc-list">
                                 <ul>
                                    @foreach ($subcategories as  $subcat)
                                    <li><a href="/sub/{{$subcat->slug}}">{{$subcat->subcategory_name}}</a></li>
                                    @endforeach
                                </ul>
                                </div>
                                <div class="hc-all">
                                    <a href="/cat/{{$category->slug}}">Explore full range</a>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endforeach
                    <br>
                </div>

            </div>

        </div>
    </div>
    <!-- end categories -->

</div>

{{-- <div class="bg-gray">

    <!-- products carousel -->
    <div class="container">
        <div class="mb-3">
            <div class="ht mb-3">
                <h5>Today's Hot Deals</i></h5>
            </div>

            <div id="product-carousel">
                <div class="slick">

                    <div class="p-c-sec">
                        <div class="p-c-inner">
                            <a href="product.php">
                                <img src="assets/img/product/1.jpg" alt="Product Name">
                                <div class="p-c-name">Apple MacBook Air (13-inch Retina Display, 8GB RAM, 256GB SSD Storage)</div>
                                <div class="p-c-price">KSh.100,000</div>

                                <a href="product.php" class="btn btn-sm btn-block p-btn">Lipa Mos Mos</a>
                            </a>
                        </div>
                    </div>

                    <div class="p-c-sec">
                        <div class="p-c-inner">
                            <a href="product.php">
                                <img src="http://www.samsutech.net/Refrigerators/RS65R5691M9.png" alt="Product Name">
                                <div class="p-c-name">Apple MacBook Air (13-inch Retina Display, 8GB RAM, 256GB SSD Storage)</div>
                                <div class="p-c-price">KSh.100,000</div>

                                <a href="product.php" class="btn btn-sm btn-block p-btn">Lipa Mos Mos</a>
                            </a>
                        </div>
                    </div>

                    <div class="p-c-sec">
                        <div class="p-c-inner">
                            <a href="product.php">
                                <img src="assets/img/product/1.jpg" alt="Product Name">
                                <div class="p-c-name">Apple MacBook Air (13-inch Retina Display, 8GB RAM, 256GB SSD Storage)</div>
                                <div class="p-c-price">KSh.100,000</div>

                                <a href="product.php" class="btn btn-sm btn-block p-btn">Lipa Mos Mos</a>
                            </a>
                        </div>
                    </div>

                    <div class="p-c-sec">
                        <div class="p-c-inner">
                            <a href="product.php">
                                <img src="http://www.samsutech.net/Refrigerators/RS65R5691M9.png" alt="Product Name">
                                <div class="p-c-name">Apple MacBook Air (13-inch Retina Display, 8GB RAM, 256GB SSD Storage)</div>
                                <div class="p-c-price">KSh.100,000</div>

                                <a href="product.php" class="btn btn-sm btn-block p-btn">Lipa Mos Mos</a>
                            </a>
                        </div>
                    </div>

                    <div class="p-c-sec">
                        <div class="p-c-inner">
                            <a href="product.php">
                                <img src="assets/img/product/1.jpg" alt="Product Name">
                                <div class="p-c-name">Apple MacBook Air (13-inch Retina Display, 8GB RAM, 256GB SSD Storage)</div>
                                <div class="p-c-price">KSh.100,000</div>

                                <a href="product.php" class="btn btn-sm btn-block p-btn">Lipa Mos Mos</a>
                            </a>
                        </div>
                    </div>

                    <div class="p-c-sec">
                        <div class="p-c-inner">
                            <a href="product.php">
                                <img src="http://www.samsutech.net/Refrigerators/RS65R5691M9.png" alt="Product Name">
                                <div class="p-c-name">Apple MacBook Air (13-inch Retina Display, 8GB RAM, 256GB SSD Storage)</div>
                                <div class="p-c-price">KSh.100,000</div>

                                <a href="product.php" class="btn btn-sm btn-block p-btn">Lipa Mos Mos</a>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- end products carousel -->

</div> --}}
