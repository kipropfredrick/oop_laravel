<!-- header-->
@extends('invoice.head')
<!-- end-->

    <!-- booking lead -->
    @section('content')
<!-- end-->

<!-- header -->
<div class="pay-h">
</div>
<!-- end -->

<!-- page content -->
<div class="bg">
    <div class="container">
        <div class="row">

            <!-- checkout -->
            <div class="col-sm-8">
                <div class="">
                    <!-- this card is for successful iPay payments and for m-pesa if pay within the STK push threshhold -->
                    <div class="card">
                        <div class="m-4">
                            <div>
                                <h3>Payment Successful</h3>
                                
                                <p>Hello <strong>{{$details['customer_name']}}</strong>, you have successfully paid <strong>{{$details['amount_paid']}}</strong> for 
                                <strong>{{$details['product_name']}}</strong>. Your balance is <strong>{{$details['balance']}}</strong>. Thank you.</p>

                                <div class="mt-3">
                                    <a href="{{$details['url']}}" class="btn p-btn">View Invoice <span></span></a>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <!-- this card is ONLY for m-pesa, if someone cancels STK push or doesn't pay in a few seconds. Do not include on iPay -->
            <!--         <div class="card">
                        <div class="m-4">
                            <div>
                                <h3>Payment Pending</h3>
                                
                                <p>Hello <strong>[full-name]</strong>, your M-Pesa payment request has timed out. To pay with M-Pesa, use
                                Paybill <strong>4040299</strong> and Account Number <strong>[booking-ref]</strong>. Thank you.</p>

                                <div class="mt-3">
                                    <a href="booking.php" class="btn p-btn">View Invoice <span></span></a>
                                </div>
                                
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end --> 

<!-- footer-->
<div class="bg">
    <div class="container">
        <div class="inv-ftr">
            <a href="https://travelmosmos.co.ke">
                <img src="{{asset('assets/img/logo/logo-blue.png')}}">
            </a>
        </div>
    </div>
</div>

<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

<!-- end-->
@endsection()