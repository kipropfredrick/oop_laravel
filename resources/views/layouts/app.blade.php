<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Order your favourite product, lipa pole pole at 0% interest, get your order delivered upon completion of payment.">
        <meta name="keywords" content="Lipa Pole Pole, Lipa Mos Mos , Lipa Later, Lipa Mdogo Mdogo">
        <meta name="author" content="Rdfyne Technologies Limited">
        
        <title>@yield('title') - Lipa Mos Mos</title>
        <link rel="icon" href="{{asset('assets/img/logo/favicon.png')}}" type="image/png"/>

        <!-- Fontawesome -->
        <script src="https://kit.fontawesome.com/3e5f9a0a23.js"></script>

        <!-- Google Fonts --> 
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        
        <!-- Slick Slider --> 
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css"/>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css"/>

        <!-- Main CSS -->
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
        <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script> -->

        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-533QG0VQF7"></script>
        <script src="//code-eu1.jivosite.com/widget/kaBN9Oc1Gd" async></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-533QG0VQF7');
        </script>

        <script>
            (function(h,o,t,j,a,r){
                h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                h._hjSettings={hjid:2280205,hjsv:6};
                a=o.getElementsByTagName('head')[0];
                r=o.createElement('script');r.async=1;
                r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                a.appendChild(r);
            })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
        </script>

        <script type="text/javascript">
            window.heap=window.heap||[],heap.load=function(e,t){window.heap.appid=e,window.heap.config=t=t||{};var r=document.createElement("script");r.type="text/javascript",r.async=!0,r.src="https://cdn.heapanalytics.com/js/heap-"+e+".js";var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(r,a);for(var n=function(e){return function(){heap.push([e].concat(Array.prototype.slice.call(arguments,0)))}},p=["addEventProperties","addUserProperties","clearEventProperties","identify","resetIdentity","removeEventProperty","setEventProperties","track","unsetEventProperty"],o=0;o<p.length;o++)heap[p[o]]=n(p[o])};
            heap.load("1876780415");
        </script>


    </head>
    <body>

    <?php 
        $categories = \App\Categories::with('subcategories')->get();
        $lcategories = \App\Categories::with('subcategories')->take(10)->get();
    ?>
    
    <!-- menu area (large screens) -->
    <div class="ls-menu fixed-top">
        <div class="mdg-navbar">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3 mdg-logo">
                        <a class="navbar-brand" href="/">
                            <img src="{{asset('assets/img/logo/web-logo.png')}}">
                        </a>
                    </div>

                    <div class="col-sm-5">
                        <div class="mdg-search mt-2">
                            <form class="form-inline" action="/search" method="get"> 
                                <div class="input-group">
                                    <input name="search" type="text" class="form-control" placeholder="What are you looking for?" aria-label="What are you looking for?" aria-describedby="mdg-btn-search">
                                    <div class="input-group-append">
                                        <button class="btn mdg-btn-search" type="submit" id="mdg-btn-search">
                                            <span class="fa fa-search"></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="header-cta">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="cta-icon">
                                                <span class="fas fa-phone-square-alt fa-3x"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="cta-text">  
                                                <span>Customer care</span>
                                                <h6>
                                                    <a href="tel:0113 980 270">0113 980 270</a>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="header-cta">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="cta-icon">
                                                <span class="far fa-user-circle fa-3x"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="cta-text">  
                                                <span>My Account</span>
                                                <h6>
                                                    @if(auth()->user())
                                                    <a href="{{route('dashboard')}}">  Visit Dashboard </a>
                                                    @else
                                                    <a href="/login"> Login </a>
                                                    @endif  
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="mdg-cm">
            <nav class="navbar navbar-expand-lg">
                <div class="container">
                <ul class="navbar-nav">
                        <!-- mega menu -->
                        @foreach($lcategories as $category)
                        <li class="nav-item dropdown megamenu-li">
                            <a class="nav-link dropdown-toggle" href="/cat/{{$category->slug}}" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> {{$category->category_name}}</a>
                            
                            <div class="dropdown-menu megamenu" aria-labelledby="dropdown01">
                                <div class="row">
                                   <?php $subcategories = $category->subcategories; ?>
                                    @forelse($subcategories as $subcategory)
                                    <div class="col-sm-6 col-lg-2">
                                        <!-- second level cat -->
                                        <a class="sec-lev" href="/sub/{{$subcategory->slug}}">{{$subcategory->subcategory_name}}</a>
                                        
                                        <?php $thirdlevelcategories = $subcategory->thirdlevelcategories; ?>
                                        @forelse($thirdlevelcategories->slice(0, 10) as $thirdlevelcategory)
                                        <!-- third level cat (max 10) -->
                                        <a class="dropdown-item" href="/tlc/{{$subcategory->slug}}/{{$thirdlevelcategory->slug}}">{{$thirdlevelcategory->name}}</a>
                                        @empty
                                        <br>
                                        @endforelse
                                        
                                        <a class="mm-explore" href="/sub/{{$subcategory->slug}}"><span class="fa fa-long-arrow-right"></span> Explore All</a>
                                    </div>
                                    @empty
                                    No Subcategories
                                    @endforelse
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <!-- end -->

    <!-- handheld menu -->
    <div class="hh-menu fixed-top">
        <nav class="navbar navbar-expand-lg mdg-navbar-hh">
            
            <div class="mdg-logo">
                <a class="navbar-brand" href="/">
                    <img src="{{asset('assets/img/logo/web-logo.png')}}">
                </a>
            </div>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#hhMenu" aria-controls="hhMenu" aria-expanded="false" aria-label="Toggle navigation">
                <!--<span class="fa fa-bars"></span>-->
                <div class="mobile-toggle" onclick="myFunction(this)">
                    <div class="bar1"></div>
                    <div class="bar2"></div>
                    <div class="bar3"></div>
                </div>
            </button>

            <div class="collapse navbar-collapse" id="hhMenu">
                <!-- handheld menu -->
                <div class="hh-search mt-2">

                    <div class="justify-content-center">
                        <ul class="list_no_bullets mobile-menu">
                            <li class="nav-item">
                                <a class="nav-link"  href="tel:0113 980 270"><span class="fas fa-phone-square-alt"></span> &nbsp; 0113 980 270</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/login"><span class="fas fa-user-circle"></span> &nbsp;@if(auth()->user())  Visit Dashboard @else Login @endif</a>
                            </li>
                            @foreach($lcategories as $category)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="{{$category->id}}" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{$category->category_name}}
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                            <?php $subcategories = $category->subcategories; ?>
                                            @forelse($subcategories as $subcategory)
                                            <a class="dropdown-item" href="/sub/{{$subcategory->slug}}">{{$subcategory->subcategory_name}}</a>
                                            @empty
                                            No Subcategories
                                            @endforelse
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>



                </div>

                <!-- handheld categories -->
                <!-- <ul class="navbar-nav mr-auto">
                   
                </ul> -->

            </div>
        </nav>

        <!-- handheld search -->
        <div class="hh-fix-search">
            <div class="hh-search">
                <form class="form-inline my-2" action="/search" method="get">
                    <div class="input-group">
                        <input name="search" type="text" class="form-control" placeholder="What are you looking for?" aria-label="What are you looking for?" aria-describedby="mdg-btn-search">
                        <div class="input-group-append">
                            <button class="btn mdg-btn-search" type="submit" id="mdg-btn-search">
                                <span class="fa fa-search"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <!-- end --> 

    <div class="main">
        @yield('content')
    </div>
  
    <!-- footer -->
        <span class="truly-african"></span>
    <div class="ftr-bg">
        <div class="container">
            <div class="ftr-container">
                <div class="ftr-content">

                    <div class="ftr-socials">
                        <a href="https://facebook.com/lipamosmoske" target="_blank"><span class="fa fa-2x fa-facebook"></span></a>
                        <a href="https://instagram.com/lipamosmoske" target="_blank"><span class="fa fa-2x fa-instagram"></span></a>
                    </div>

                    <!-- <div class="ftr-subscribe">
                        <div class="ftr-search mt-2">
                            <h3>Fear of Missing Out?</h3>
                            <p>Subscribe to our newsletter to be the first to know of all the latest offers, deals and new products as they drop.</p>

                            <form class="form-inline" action="/search" method="get">
                                <div class="input-group">
                                <input name="search" type="text" class="form-control" placeholder="What are you looking for?" aria-label="What are you looking for?" aria-describedby="mdg-btn-search">
                                    <div class="input-group-append">
                                    <button class="btn mdg-btn-search" type="submit" id="mdg-btn-search">
                                        <span class="fa fa-search"></span>
                                    </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> -->

                    <div class="ftr-legal">
                        <div class="mb-2">
                            <small>
                                <span><a href="/terms">Terms</a></span>
                                <span class="ftr-sep"></span>
                                <span><a href="/privacy-policy">Privacy</a></span>
                            </small>
                        </div>

                        <div class="mb-2">
                            <small>
                                <span><a href="/how-to">How to Order</a></span>
                                <span class="ftr-sep"></span>
                                <span><a href="/faqs">FAQs</a></span>
                                <span class="ftr-sep"></span>
                                <span><a href="/login">My Account</a></span>
                            </small>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- end --> 

    <!-- copyrights -->
    <div class="copyrights">
        <div class="container">
            &copy; <span id="currentYear"></span>. Lipa Mos Mos | Powered by <a href="https://rdfyne.com" target="_blank">Rdfyne Technologies</a>
        </div>
    </div>
    <!-- end -->
    
    <!-- mobile toggle --> 
    <script>
        function myFunction(x) {
        x.classList.toggle("change");
        }

        function filter(sel)
        {
            $('#filter-form').submit();
        }
    </script>
    <!-- END mobile toggle -->
    
    <!-- current year -->
    <script type="text/javascript">
        document.getElementById("currentYear").innerHTML = new Date().getFullYear();
    </script>

    @yield('extra-js')
    <!-- END current year -->

    <!-- Slick Slider -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>

    <!-- products carousel -->
    <script type="text/javascript">
        $(document).ready(() => {
            $('#product-carousel .slick').slick({
                slidesToShow: 5,
                autoplay: true,
                autoplaySpeed: 2000,
                responsive: [
                    {
                    breakpoint: 780,
                    settings: {
                        arrows: false,
                        centerPadding: '40px',
                        slidesToShow: 3
                    }
                    },
                    {
                    breakpoint: 600,
                    settings: {
                        arrows: false,
                        centerPadding: '40px',
                        slidesToShow: 2
                    }
                    },
                    {
                    breakpoint: 480,
                    settings: {
                        arrows: false,
                        centerPadding: '40px',
                        slidesToShow: 1
                    }
                    }
                ]
            });
        });
    </script>

    <!-- brands carousel -->
    <script type="text/javascript">
        $(document).ready(() => {
            $('#brands-carousel .slick').slick({
                slidesToShow: 7,
                autoplay: true,
                autoplaySpeed: 2000,
                responsive: [
                    {
                    breakpoint: 780,
                    settings: {
                        arrows: true,
                        centerPadding: '40px',
                        slidesToShow: 4
                    }
                    },
                    {
                    breakpoint: 600,
                    settings: {
                        arrows: false,
                        centerPadding: '40px',
                        slidesToShow: 3
                    }
                    },
                    {
                    breakpoint: 480,
                    settings: {
                        arrows: false,
                        centerPadding: '40px',
                        slidesToShow: 2
                    }
                    }
                ]
            });
        });

        $(document).ready(function() {
            $('.js-example-basic-single').select2();
            $('.js-example-basic-single1').select2();
            $('.js-example-basic-single2').select2();
        });

    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>


    <script type="text/javascript">
        $('ul.pagination').hide();
        $(function() {
            $('.scrolling-pagination').jscroll({
                autoTrigger: true,
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.scrolling-pagination',
                callback: function() {
                    $('ul.pagination').remove();
                }
            });
        });
    </script>

    
    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <!-- GetButton.io widget -->
    <script type="text/javascript">
        (function () {
            var options = {
                whatsapp: "+254113980270", // WhatsApp number
                call_to_action: "Message us", // Call to action
                position: "left", // Position may be 'right' or 'left'
                pre_filled_message: " ", // WhatsApp pre-filled message
            };
            var proto = document.location.protocol, host = "getbutton.io", url = proto + "//static." + host;
            var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
            s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
            var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
        })();
    </script>
    <!-- /GetButton.io widget -->

  </body>
</html>