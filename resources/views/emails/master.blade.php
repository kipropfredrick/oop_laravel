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

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
        <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script> -->

        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-533QG0VQF7"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-533QG0VQF7');
        </script>

    </head>
    <body>

    <div class="main">
        @yield('content')
    </div>
  
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

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->

    
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