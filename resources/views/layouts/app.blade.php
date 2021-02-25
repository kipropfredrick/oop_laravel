<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Lipia your favourite items Mos Mos and get them delivered to your doorstep upon payment completion.">
        <meta name="keywords" content="Lipa Pole Pole, Lipa Mos Mos , Lipa Later, Lipa Mdogo Mdogo">
        <meta name="author" content="Rdfyne Technologies Limited">
        
        <title>Lipa Mos Mos</title>
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
                            <form class="form-inline">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="What are you looking for?" aria-label="What are you looking for?" aria-describedby="mdg-btn-search">
                                    <div class="input-group-append">
                                        <a class="btn mdg-btn-search" type="button" id="mdg-btn-search">
                                            <span class="fa fa-search"></span>
                                        </a>
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
                                                    <a href="tel:+254700000000">0700 000 000</a>
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
                                                    <a href="/login">Login</a>
                                                    <!-- Visit Dashboard, if user id logged in -->
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
                        @foreach($lcategories as $category)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="{{$category->id}}" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{$category->category_name}}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                               <?php $subcategories = $category->subcategories; ?>
                                @forelse($subcategories as $subcategory)
                                 <a class="dropdown-item" href="/subcategory/{{$subcategory->id}}">{{$subcategory->subcategory_name}}</a>
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
                <a class="navbar-brand" href="#">
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
                <!-- handheld search -->
                <div class="hh-search mt-2">
                    <form class="form-inline my-2">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="What are you looking for?" aria-label="What are you looking for?" aria-describedby="mdg-btn-search">
                            <div class="input-group-append">
                                <a class="btn mdg-btn-search" type="button" id="mdg-btn-search">
                                    <span class="fa fa-search"></span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- handheld categories -->
                <ul class="navbar-nav mr-auto">
                    @foreach($lcategories as $category)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="{{$category->id}}" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{$category->category_name}}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <?php $subcategories = $category->subcategories; ?>
                                @forelse($subcategories as $subcategory)
                                 <a class="dropdown-item" href="/subcategory/{{$subcategory->id}}">{{$subcategory->subcategory_name}}</a>
                                @empty
                                No Subcategories
                                @endforelse
                        </div>
                    </li>
                   @endforeach
                </ul>

            </div>
        </nav>

        <!-- handheld search -->
        <div class="hh-fix-search">
            <div class="hh-search">
                <form class="form-inline my-2">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="What are you looking for?" aria-label="What are you looking for?" aria-describedby="mdg-btn-search">
                        <div class="input-group-append">
                            <a class="btn mdg-btn-search" type="button" id="mdg-btn-search">
                                <span class="fa fa-search"></span>
                            </a>
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
                        <a href="#" target="_blank"><span class="fa fa-2x fa-facebook"></span></a>
                        <a href="#" target="_blank"><span class="fa fa-2x fa-instagram"></span></a>
                    </div>

                    <div class="ftr-subscribe">
                        <div class="ftr-search mt-2">
                            <h3>Fear of Missing Out?</h3>
                            <p>Subscribe to our newsletter to be the first to know of all the latest offers, deals and new products as they drop.</p>

                            <form class="form-inline">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Enter your Email Address" aria-label="What are you looking for?" aria-describedby="mdg-btn-search">
                                    <div class="input-group-append">
                                        <a class="btn mdg-btn-search" type="button" id="mdg-btn-search">
                                            <span class="fa fa-envelope-o"></span>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="ftr-legal">
                        <div class="mb-2">
                            <small>
                                <span><a href="#">Terms</a></span>
                                <span class="ftr-sep"></span>
                                <span><a href="#">Privacy</a></span>
                            </small>
                        </div>

                        <div class="mb-2">
                            <small>
                                <span><a href="#">How to Order</a></span>
                                <span class="ftr-sep"></span>
                                <span><a href="#">FAQs</a></span>
                                <span class="ftr-sep"></span>
                                <span><a href="#">My Account</a></span>
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
    </script>
    <!-- END mobile toggle -->

    <!-- current year -->
    <script type="text/javascript">
        document.getElementById("currentYear").innerHTML = new Date().getFullYear();
    </script>
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
    </script>
    
    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

  </body>
</html>