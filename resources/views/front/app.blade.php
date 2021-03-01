<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <title>Combine | Lipia bidhaa yoyote pole pole</title>
    <link rel="icon" type="image/png" href="{{asset('images/favicon.png')}}">
    <!-- fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i">
    <!-- css -->
    <link rel="stylesheet" href="{{asset('black/vendor/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('black/vendor/owl-carousel/assets/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('black/vendor/photoswipe/photoswipe.css')}}">
    <link rel="stylesheet" href="{{asset('black/vendor/photoswipe/default-skin/default-skin.css')}}">
    <link rel="stylesheet" href="{{asset('black/vendor/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('black/css/style.css')}}">
    <!-- font - fontawesome -->
    <link rel="stylesheet" href="{{asset('black/vendor/fontawesome/css/all.min.css')}}">
    <!-- font - stroyka -->
    <link rel="stylesheet" href="{{asset('black/fonts/stroyka/stroyka.css')}}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    
</head>

<body>
    <!-- site -->
    <div class="site">


        <!-- mobile site__header -->
        <header class="site__header d-lg-none">
            <!-- data-sticky-mode - one of [pullToShow, alwaysOnTop] -->
            <div class="mobile-header mobile-header--sticky" data-sticky-mode="pullToShow">
                <div class="mobile-header__panel">
                    <div class="container">
                        <div class="mobile-header__body">
                            <button class="mobile-header__menu-button">
                               <i style="color:#FFF" class="fa fa-bars" width="18px" height="14px"></i>
                            </button>
                            <a class="mobile-header__logo" href="/">
                                <!-- mobile-logo -->
                                <!-- <h5>COMBINE</h5> -->
                                <img style="height:30px" src="{{asset('images/logo.png')}}" alt="">
                                <!-- mobile-logo / end -->
                            </a>

                            <div class="search search--location--mobile-header mobile-header__search">
                                <div class="search__body">
                                    <form class="search__form" action="">
                                        <input class="search__input" name="search" placeholder="Search over 10,000 products" aria-label="Site search" type="text" autocomplete="off">
                                        <button class="search__button search__button--type--submit" type="submit">
                                            <svg width="20px" height="20px">
                                                <use xlink:href="images/sprite.svg#search-20"></use>
                                            </svg>
                                        </button>
                                        <button class="search__button search__button--type--close" type="button">
                                            <svg width="20px" height="20px">
                                                <use xlink:href="images/sprite.svg#cross-20"></use>
                                            </svg>
                                        </button>
                                        <div class="search__border"></div>
                                    </form>
                                    <div class="search__suggestions suggestions suggestions--location--mobile-header"></div>
                                </div>
                            </div>

                            
                            <div class="mobile-header__indicators">
                                <div class="indicator indicator--mobile">
                                    <a href="cart.html" class="indicator__button">
                                        <span class="indicator__area">
                                        <a style="width:100%" class="btn-warning shadow-sm text-center" href="/register">Become a Vendor </a>
                                            <a href="/login" class="indicator__button">
                                                <span class="indicator__area">
                                                        <i class="far fa-user"></i>
                                                </span>
                                            </a>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- mobile site__header / end -->


        <!-- desktop site__header -->
        <header class="site__header d-lg-block d-none">
            <div class="site-header">

                <div class="site-header__middle container">
                    <div class="site-header__logo">
                        <a href="/">
                            <!-- logo -->
                            <!-- <h5>COMBINE</h5> -->
                            <img style="height:70px" src="{{asset('images/logo.png')}}" alt="">
                            <!-- logo / end -->
                        </a>
					</div>
					
                    <div class="site-header__search">
                        <div class="search search--location--header ">
                            <div class="search__body">
								<form class="search__form" action="/search" method="get">
									<!-- <select name="category_id" class="search__categories" aria-label="Category">
										<option value="">Select Category</option>
										@if(isset($categories))
                                        @foreach($categories as $category)
                                            <option  value="{{$category->id}}">&nbsp;&nbsp;&nbsp;&nbsp;{{$category->category_name}}</option>
										@endforeach
										@endif
                                    </select> -->
                                    <input class="search__input" name="search" placeholder="Search over 10,000 products" aria-label="Site search" type="text" autocomplete="off">
                                    <button class="search__button search__button--type--submit" type="submit">
                                        <i style="color:gray;" width="20px" height="20px" class="fas fa-search"></i>
                                    </button>
                                    <div class="search__border"></div>
                                </form>
                                <div class="search__suggestions suggestions suggestions--location--header"></div>
                            </div>
                        </div>
                    </div>
                    <div class="site-header__phone">
                        <div class="site-header__phone-title">Customer Service</div>
                        <div class="site-header__phone-number"><a style="color:black" href="tel:0759701616"><i class="footer-contacts__icon fas fa-mobile-alt"></i>0759 701 616</a></div>
                    </div>
                </div>
                <div class="site-header__nav-panel">
                    <!-- data-sticky-mode - one of [pullToShow, alwaysOnTop] -->
                    <div class="nav-panel nav-panel--sticky" data-sticky-mode="pullToShow">
                        <div class="nav-panel__container container">
                            <div class="nav-panel__row">
                            <!-- <a class="btn btn-primary" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">
                            <i class="fa fa-list" width="18px" height="14px"></i>
                                 Shop by Category
                            </a> -->
                            
                            <div class="nav-panel__departments">
                                    <!-- .departments -->
                                    <div class="departments " data-departments-fixed-by="">
                                        <div class="departments__body">
                                            <div class="departments__links-wrapper">
                                                <div class="departments__submenus-container"></div>
                                                <ul class="departments__links">

                                                <?php 
                                                $categories = \App\Categories::with('subcategories')->get();
                                                ?>
                                                @foreach($categories as $category)
                                                    <li class="departments__item">
                                                        <a class="departments__item-link" href="/category/{{$category->slug}}">
                                                          {{ucfirst($category->category_name)}}
                                                            <i class="departments__item-arrow fas fa-chevron-down" width="6px" height="9px"></i>
                                                        </a>
                                                        <div class="departments__submenu departments__submenu--type--megamenu departments__submenu--size--sm">
                                                            <!-- .megamenu -->
                                                            <div class="megamenu  megamenu--departments ">
                                                                <div class="megamenu__body">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <ul class="megamenu__links megamenu__links--level--0">
                                                                                <li class="megamenu__item  megamenu__item--with-submenu ">
                                                                                <a href="">{{ucfirst($category->category_name)}}</a>
                                                                                    <ul class="megamenu__links megamenu__links--level--1">
                                                                                        @forelse($category->subcategories as $subcategory)
                                                                                         <li class="megamenu__item"><a href="/subcategory/{{$subcategory->id}}"> {{ucfirst($subcategory->subcategory_name)}}</a></li>
                                                                                        @empty
                                                                                         <div class="text-center" style="font-size:10px;color:gray">No subcategories</div>
                                                                                         @endforelse
                                                                                    </ul>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- .megamenu / end -->
                                                        </div>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <button style="padding-left: 10px !important;" class="departments__button">
                                             <i class="fa fa-list" width="18px" height="14px"></i>
                                            Shop By Category
                                        </button>
                                    </div>
                                    <!-- .departments / end -->
                                </div>

                            <div>

                                

                            </div>

                            
                              
                                <div class="nav-panel__indicators">
                                <div class="indicator row">
                                        <div style="margin-right:20px" class="indicator__button col">
                                        <a style="width:100%" class="btn btn-warning shadow-sm text-center" href="/register">Become a Vendor </a>
                                        </div>
                                        <a href="/login" class="indicator__button">
                                            <span class="indicator__area">
                                                    <i class="far fa-user"></i>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- desktop site__header / end -->
        <!-- site__body -->
        <div class="site__body">
           
                 <div style="min-height:80vh" class="content">

                        @yield('content')

                </div>
                    
            <!-- .block-product-columns / end -->
        </div>
        <!-- site__body / end -->
        <!-- site__footer -->
        <footer class="site__footer">
            <div class="site-footer">
                <div class="container">


                <div class="site-footer__widgets">
                    <!-- .block-brands -->
                <div class="block block-brands">
                    <div>
                        <div class="block-header">
                            <h3 class="block-header__title">Our Partners</h3>
                            <div class="block-header__divider"></div>
                        </div>
                        <div class="block-brands__slider">
                            <div class="owl-carousel">
                                <div class="block-brands__item">
                                    <a href=""><img src="{{asset('partners/safaricom.png')}}" alt="Safaricom"></a>
                                </div> 
                                <div class="block-brands__item">
                                    <a href=""><img src="{{asset('partners/redmi.png')}}" alt="Redmi"></a>
                                </div>
                                <div class="block-brands__item">
                                    <a href=""><img src="{{asset('partners/tecno.png')}}" alt="Tecno"></a>
                                </div>
                                <div class="block-brands__item">
                                    <a href=""><img src="{{asset('partners/oppo.png')}}" alt="Oppo"></a>
                                </div>
                                <div class="block-brands__item">
                                    <a href=""><img src="{{asset('partners/samsung.png')}}" alt="Samsung"></a>
                                </div>
                                <div class="block-brands__item">
                                    <a href=""><img src="{{asset('partners/nokia.png')}}" alt="Nokia"></a>
                                </div>
                                <div class="block-brands__item">
                                    <a href=""><img src="{{asset('partners/hotpoint.png')}}" alt="Hotpoint"></a>
                                </div>
                                <div class="block-brands__item">
                                    <a href=""><img src="{{asset('partners/infinix.png')}}" alt="Infinix"></a>
                                </div>
                                <div class="block-brands__item">
                                    <a href=""><img src="{{asset('partners/itel.png')}}" alt="Itel"></a>
                                </div>
                                <div class="block-brands__item">
                                    <a href=""><img src="{{asset('partners/huawei.png')}}" alt="Huawei"></a>
                                </div>
                                <div class="block-brands__item">
                                    <a href=""><img src="{{asset('partners/ulefone.png')}}" alt="Ulephone"></a>
                                </div>
                                <div class="block-brands__item">
                                    <a href=""><img src="{{asset('partners/xtigi.png')}}" alt="X-Tigi"></a>
                                </div>
                                <div class="block-brands__item">
                                    <a href=""><img src="{{asset('partners/vivo.png')}}" alt="Vivo Phones"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- .block-brands / end -->

                    <div class="site-footer__widgets">
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="site-footer__widget footer-contacts">
                                    <h5 class="footer-contacts__title">Contact Us</h5>
                                    <!-- <div class="footer-contacts__text">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer in feugiat lorem. Pellentque ac placerat tellus.
                                    </div> -->
                                    <ul class="footer-contacts__contacts">
                                        <li><i class="footer-contacts__icon far fa-envelope"></i>info@mosmos.co.ke</li>
                                        <li><a href="tel:0759701616"><i class="footer-contacts__icon fas fa-mobile-alt"></i>0759 701 616</a></li>
                                        <li><i class="footer-contacts__icon far fa-clock"></i> Business Hours : 8AM - 5PM</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 col-lg-2">
                                <div class="site-footer__widget footer-links">
                                    <h5 class="footer-links__title">Customer Service</h5>
                                    <ul class="footer-links__list">
                                        <li class="footer-links__item"><a href="/terms" class="footer-links__link">Payment Policy</a></li>
                                        <li class="footer-links__item"><a href="/terms" class="footer-links__link">Shipping Policy</a></li>
                                        <li class="footer-links__item"><a href="/terms" class="footer-links__link">Return Policy</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 col-lg-2">
                                <div class="site-footer__widget footer-links">
                                    <h5 class="footer-links__title">Legal</h5>
                                    <ul class="footer-links__list">
                                        <li class="footer-links__item"><a href="/terms" class="footer-links__link">Privacy Policy</a></li>
                                        <li class="footer-links__item"><a href="/terms" class="footer-links__link">Terms and Conditions</a></li>
                                        <li class="footer-links__item"><a href="/terms" class="footer-links__link">Disclaimer</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-12 col-md-12 col-lg-4">
                                <div class="site-footer__widget footer-newsletter">
                                    <h5 class="footer-newsletter__title">Newsletter</h5>
                                    <div class="footer-newsletter__text">
                                      Subscribe to our newsletter to get the latest offers, promotions and much more.
                                    </div>
                                    <form action="" class="footer-newsletter__form">
                                        <label class="sr-only" for="footer-newsletter-address">Email Address</label>
                                        <input type="text" class="footer-newsletter__form-input form-control" id="footer-newsletter-address" placeholder="Email Address...">
                                        <button class="footer-newsletter__form-button btn btn-primary">Subscribe</button>
                                    </form>
                                    <div class="footer-newsletter__text footer-newsletter__text--social">
                                        Follow us on social networks
                                    </div>
                                    <!-- social-links -->
                                    <div class="social-links footer-newsletter__social-links social-links--shape--circle">
                                        <ul class="social-links__list">
                                            <!-- <li class="social-links__item">
                                                <a class="social-links__link social-links__link--type--rss" href="" target="_blank">
                                                    <i class="fas fa-rss"></i>
                                                </a>
                                            </li>
                                            <li class="social-links__item">
                                                <a class="social-links__link social-links__link--type--youtube" href="" target="_blank">
                                                    <i class="fab fa-youtube"></i>
                                                </a>
                                            </li> -->
                                            <li class="social-links__item">
                                                <a class="social-links__link social-links__link--type--facebook" href=" https://www.facebook.com/combinekenya" target="_blank">
                                                    <i class="fab fa-facebook-f"></i>
                                                </a>
                                            </li>
                                            <!-- <li class="social-links__item">
                                                <a class="social-links__link social-links__link--type--twitter" href="" target="_blank">
                                                    <i class="fab fa-twitter"></i>
                                                </a>
                                            </li> -->
                                            <li class="social-links__item">
                                                <a class="social-links__link social-links__link--type--instagram" href=" https://www.instagram.com/combine_kenya/" target="_blank">
                                                    <i class="fab fa-instagram"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- social-links / end -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="site-footer__bottom">
                        <div class="site-footer__copyright">
                            <!-- copyright -->
								&copy; {{ now()->year }}. Combine. All Rights Reserved.
                            <!-- copyright / end -->
                        </div>
                        <div class="site-footer__payments">
                           <!-- <div  class="totop__button"> -->
                             <a target="__blank" href="https://rdfyne.com "> <i  style="color:#000" class="fa fa-bolt"></i></a>
                           <!-- </div> -->
                        </div>
                    </div>
                </div>
                <div class="totop">
                    <div class="totop__body">
                        <div class="totop__start"></div>
                        <div class="totop__container container"></div>
                        <div class="totop__end">
                            <button type="button" class="totop__button">
                             <i class="fa fa-angle-up"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- site__footer / end -->
    </div>
    <!-- site / end -->
    <!-- quickview-modal -->
    <div id="quickview-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content"></div>
        </div>
    </div>
    <!-- quickview-modal / end -->
    <!-- mobilemenu -->
    <div class="mobilemenu">
        <div class="mobilemenu__backdrop"></div>
        <div class="mobilemenu__body">
            <div class="mobilemenu__header">
                <div class="mobilemenu__title"><a style="color:#000" href="/">Home</a></div>
                <button type="button" class="mobilemenu__close">
                    <i width="20px" height="20px" class="fa fa-times"></i>
                </button>
            </div>
            <div class="mobilemenu__content">
                <ul class="mobile-links mobile-links--level--0" data-collapse data-collapse-opened-class="mobile-links__item--open">
			
              <?php 
               $categories = \App\Categories::with('subcategories')->get();
              ?>
				@foreach($categories as $category)  
                <li class="mobile-links__item" data-collapse-item>
                        <div class="mobile-links__item-title">
                            <a href="/category/{{$category->slug}}" class="mobile-links__item-link">{{$category->category_name}}</a>
                            <button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div class="mobile-links__item-sub-links" data-collapse-content>
                            <ul class="mobile-links mobile-links--level--1">
                              @foreach($category->subcategories as $subcategory)  
                            <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="/subcategory/{{$subcategory->id}}" class="mobile-links__item-link">{{$subcategory->subcategory_name}}</a>
                                    </div>
                            </li>
                            @endforeach


                            </ul>
                        </div>
                    </li>
					@endforeach
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="mobile-links__item" data-collapse-item>
                        <div class="mobile-links__item-title">
                            <a href="" class="mobile-links__item-link">Shop</a>
                            <button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
                                <svg class="mobile-links__item-arrow" width="12px" height="7px">
                                    <use xlink:href="images/sprite.svg#arrow-rounded-down-12x7"></use>
                                </svg>
                            </button>
                        </div>
                        <div class="mobile-links__item-sub-links" data-collapse-content>
                            <ul class="mobile-links mobile-links--level--1">
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="" class="mobile-links__item-link">Shop Grid</a>
                                        <button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
                                            <svg class="mobile-links__item-arrow" width="12px" height="7px">
                                                <use xlink:href="images/sprite.svg#arrow-rounded-down-12x7"></use>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="mobile-links__item-sub-links" data-collapse-content>
                                        <ul class="mobile-links mobile-links--level--2">
                                            <li class="mobile-links__item" data-collapse-item>
                                                <div class="mobile-links__item-title">
                                                    <a href="" class="mobile-links__item-link">3 Columns Sidebar</a>
                                                </div>
                                            </li>
                                            <li class="mobile-links__item" data-collapse-item>
                                                <div class="mobile-links__item-title">
                                                    <a href="" class="mobile-links__item-link">4 Columns Full</a>
                                                </div>
                                            </li>
                                            <li class="mobile-links__item" data-collapse-item>
                                                <div class="mobile-links__item-title">
                                                    <a href="" class="mobile-links__item-link">5 Columns Full</a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="" class="mobile-links__item-link">Shop List</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="" class="mobile-links__item-link">Shop Right Sidebar</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="" class="mobile-links__item-link">Product</a>
                                        <button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
                                            <svg class="mobile-links__item-arrow" width="12px" height="7px">
                                                <use xlink:href="images/sprite.svg#arrow-rounded-down-12x7"></use>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="mobile-links__item-sub-links" data-collapse-content>
                                        <ul class="mobile-links mobile-links--level--2">
                                            <li class="mobile-links__item" data-collapse-item>
                                                <div class="mobile-links__item-title">
                                                    <a href="" class="mobile-links__item-link">Product</a>
                                                </div>
                                            </li>
                                            <li class="mobile-links__item" data-collapse-item>
                                                <div class="mobile-links__item-title">
                                                    <a href="" class="mobile-links__item-link">Product Alt</a>
                                                </div>
                                            </li>
                                            <li class="mobile-links__item" data-collapse-item>
                                                <div class="mobile-links__item-title">
                                                    <a href="
                                                    " class="mobile-links__item-link">Product Sidebar</a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="" class="mobile-links__item-link">Cart</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="" class="mobile-links__item-link">Cart Empty</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="checkout.html" class="mobile-links__item-link">Checkout</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="order-success.html" class="mobile-links__item-link">Order Success</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="wishlist.html" class="mobile-links__item-link">Wishlist</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="compare.html" class="mobile-links__item-link">Compare</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="track-order.html" class="mobile-links__item-link">Track Order</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="mobile-links__item" data-collapse-item>
                        <div class="mobile-links__item-title">
                            <a href="account-login.html" class="mobile-links__item-link">Account</a>
                            <button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
                                <svg class="mobile-links__item-arrow" width="12px" height="7px">
                                    <use xlink:href="images/sprite.svg#arrow-rounded-down-12x7"></use>
                                </svg>
                            </button>
                        </div>
                        <div class="mobile-links__item-sub-links" data-collapse-content>
                            <ul class="mobile-links mobile-links--level--1">
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="account-login.html" class="mobile-links__item-link">Login</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="account-dashboard.html" class="mobile-links__item-link">Dashboard</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="account-profile.html" class="mobile-links__item-link">Edit Profile</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="account-orders.html" class="mobile-links__item-link">Order History</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="account-order-details.html" class="mobile-links__item-link">Order Details</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="account-addresses.html" class="mobile-links__item-link">Address Book</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="account-edit-address.html" class="mobile-links__item-link">Edit Address</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="account-password.html" class="mobile-links__item-link">Change Password</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="mobile-links__item" data-collapse-item>
                        <div class="mobile-links__item-title">
                            <a href="blog-classic.html" class="mobile-links__item-link">Blog</a>
                            <button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
                                <svg class="mobile-links__item-arrow" width="12px" height="7px">
                                    <use xlink:href="images/sprite.svg#arrow-rounded-down-12x7"></use>
                                </svg>
                            </button>
                        </div>
                        <div class="mobile-links__item-sub-links" data-collapse-content>
                            <ul class="mobile-links mobile-links--level--1">
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="blog-classic.html" class="mobile-links__item-link">Blog Classic</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="blog-grid.html" class="mobile-links__item-link">Blog Grid</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="blog-list.html" class="mobile-links__item-link">Blog List</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="blog-left-sidebar.html" class="mobile-links__item-link">Blog Left Sidebar</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="post.html" class="mobile-links__item-link">Post Page</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="post-without-sidebar.html" class="mobile-links__item-link">Post Without Sidebar</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="mobile-links__item" data-collapse-item>
                        <div class="mobile-links__item-title">
                            <a href="" class="mobile-links__item-link">Pages</a>
                            <button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
                                <svg class="mobile-links__item-arrow" width="12px" height="7px">
                                    <use xlink:href="images/sprite.svg#arrow-rounded-down-12x7"></use>
                                </svg>
                            </button>
                        </div>
                        <div class="mobile-links__item-sub-links" data-collapse-content>
                            <ul class="mobile-links mobile-links--level--1">
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="about-us.html" class="mobile-links__item-link">About Us</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="contact-us.html" class="mobile-links__item-link">Contact Us</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="contact-us-alt.html" class="mobile-links__item-link">Contact Us Alt</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="404.html" class="mobile-links__item-link">404</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="terms-and-conditions.html" class="mobile-links__item-link">Terms And Conditions</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="faq.html" class="mobile-links__item-link">FAQ</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="components.html" class="mobile-links__item-link">Components</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="typography.html" class="mobile-links__item-link">Typography</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="mobile-links__item" data-collapse-item>
                        <div class="mobile-links__item-title">
                            <a data-collapse-trigger class="mobile-links__item-link">Currency</a>
                            <button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
                                <svg class="mobile-links__item-arrow" width="12px" height="7px">
                                    <use xlink:href="images/sprite.svg#arrow-rounded-down-12x7"></use>
                                </svg>
                            </button>
                        </div>
                        <div class="mobile-links__item-sub-links" data-collapse-content>
                            <ul class="mobile-links mobile-links--level--1">
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="" class="mobile-links__item-link">€ Euro</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="" class="mobile-links__item-link">£ Pound Sterling</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="" class="mobile-links__item-link">$ US Dollar</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="" class="mobile-links__item-link">₽ Russian Ruble</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="mobile-links__item" data-collapse-item>
                        <div class="mobile-links__item-title">
                            <a data-collapse-trigger class="mobile-links__item-link">Language</a>
                            <button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
                                <svg class="mobile-links__item-arrow" width="12px" height="7px">
                                    <use xlink:href="images/sprite.svg#arrow-rounded-down-12x7"></use>
                                </svg>
                            </button>
                        </div>
                        <div class="mobile-links__item-sub-links" data-collapse-content>
                            <ul class="mobile-links mobile-links--level--1">
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="" class="mobile-links__item-link">English</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="" class="mobile-links__item-link">French</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="" class="mobile-links__item-link">German</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="" class="mobile-links__item-link">Russian</a>
                                    </div>
                                </li>
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="" class="mobile-links__item-link">Italian</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- mobilemenu / end -->
    <!-- photoswipe -->
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="pswp__bg"></div>
        <div class="pswp__scroll-wrap">
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>
            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                    <!--<button class="pswp__button pswp__button&#45;&#45;share" title="Share"></button>-->
                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>
                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- photoswipe / end -->
    <!-- js -->
    <script src="{{asset('black/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('black/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('black/vendor/owl-carousel/owl.carousel.min.js')}}"></script>
    <script src="{{asset('black/vendor/nouislider/nouislider.min.js')}}"></script>
    <script src="{{asset('black/vendor/photoswipe/photoswipe.min.js')}}"></script>
    <script src="{{asset('black/vendor/photoswipe/photoswipe-ui-default.min.js')}}"></script>
    <script src="{{asset('black/vendor/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('black/js/number.js')}}"></script>
    <script src="{{asset('black/js/main.js')}}"></script>
    <script src="{{asset('black/js/header.js')}}"></script>
    <script src="{{asset('black/vendor/svg4everybody/svg4everybody.min.js')}}"></script>
    <!-- GetButton.io widget -->

    @yield('extra-js')

    <script type="text/javascript">
        (function () {
            var options = {
                whatsapp: "+254759701616", // WhatsApp number
                call_to_action: "Message us", // Call to action
                position: "left", // Position may be 'right' or 'left'
            };
            var proto = document.location.protocol, host = "getbutton.io", url = proto + "//static." + host;
            var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
            s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
            var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
        })();
    </script>
    <!--Start of Tawk.to Script-->
    <!-- <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/5f3ffced1e7ade5df442deca/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
    </script> -->
<!--End of Tawk.to Script-->
<!-- /GetButton.io widget -->

 <!-- Google Tag Manager -->
 <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NJV4L7G');</script>
<!-- End Google Tag Manager -->

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NJV4L7G"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    <script>
        function showAccount() {
        var x = document.getElementById("haveAccount");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
        }
   </script>
    <script>
        svg4everybody();
         // In your Javascript (external .js resource or <script> tag)
         $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>

    <script defer src="{{asset('assets/js/dependent-selects.js')}}"></script>
    
</body>

</html>