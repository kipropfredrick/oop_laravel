@extends('layouts.app')

@section('title', "Booking Exists")

@section('content')
<!-- breadcrumb --> 
<div class="bc-bg">
    <div class="container">
        <div class="bc-link">
            <a href="/">
                <i class="fas fa-home"></i>
            </a>

            <span class="bc-sep"></span>

            <span>Booking Exists</span>
        </div>
    </div>
</div>
<!-- end -->

<!-- page content -->
<div class="bg-white">
    <div class="container">
        <div class="row">

            <!-- checkout -->
            <div class="col-sm-8">
                <div class="">
                    <div class="card">
                        <div class="m-4">
                        <h4>You already have an existing order</h4>
                        <p>Hello, you already have an ongoing order with us.</p>
                        <p>A booking for <strong>{{$booking->product->_product_name}}</strong>, order reference: <strong>{{$booking->booking_reference}}</strong> and the amount remaining is  <strong>KSh.{{number_format($booking->balance)}}</strong>.</p>
                        <p>ou can only order a new item upon completion of payment for your existing order. If you wish to transfer from one item to another, feel free to contact our support team.</p>
                        <p>Use Paybill: <strong>4040299</strong> and Account Number: <strong>{{$booking->booking_reference}} </strong>for all your next payments.</p><br>
                        <p>Call <strong>0113 980 270</strong> or email <strong>support@mosmos.co.ke</strong> for further assistance.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- features -->
            <div class="col-sm-4">
                <div class="mdg-features">
                    <div class="mdgf">
                        <div class="row">
                            <div class="col-2">
                                <div class="mdgf-icon">
                                    <span class="fas fa-coins fa-3x"></span>
                                </div>
                            </div>
                            <div class="col-10">
                                <div class="mdgf-text">  
                                    <span>Minimum deposit</span>
                                    <h6>
                                        KSh.100
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mdgf">
                        <div class="row">
                            <div class="col-2">
                                <div class="mdgf-icon">
                                    <span class="far fa-clock fa-3x"></span>
                                </div>
                            </div>
                            <div class="col-10">
                                <div class="mdgf-text">  
                                    <span>Payment period</span>
                                    <h6>
                                        Pay at your own pace
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mdgf">
                        <div class="row">
                            <div class="col-2">
                                <div class="mdgf-icon">
                                    <span class="fas fa-percent fa-3x"></span>
                                </div>
                            </div>
                            <div class="col-10">
                                <div class="mdgf-text">  
                                    <span>No extra fees</span>
                                    <h6>
                                        0% interest rates
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mdgf">
                        <div class="row">
                            <div class="col-2">
                                <div class="mdgf-icon">
                                    <span class="fas fa-truck fa-3x"></span>
                                </div>
                            </div>
                            <div class="col-10">
                                <div class="mdgf-text">  
                                    <span>Doorstep delivery</span>
                                    <h6>
                                        Countrywide delivery Upon completion of payment
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
<!-- end --> 
@endsection
