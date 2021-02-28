@extends('layouts.app')

@section('content')
<!-- breadcrumb --> 
<div class="bc-bg">
    <div class="container">
        <div class="bc-link">
            <a href="/">
                <i class="fas fa-home"></i>
            </a>

            <span class="bc-sep"></span>

            <a href="product.php">
                <span>Product Name</span>
            </a>

            <span class="bc-sep"></span>

            <span>Checkout</span>
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
                            <div>
                                <h3>Thank you</h3>
                                
                                <p>Hello <strong>[full-name]</strong>, we have received your payment of <strong>[initial-deposit]</strong> for 
                                <strong>[product-name]</strong>.</p>
                                <p>Use Paybill <strong>4040299</strong> and account number <strong>[order-id]</strong> for all your next 
                                payments.</p>

                                <a href="#" class="btn p-btn">Visit Dashboard</a>
                            </div>
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
                                        Ksh.500
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
                                        4 months
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
                                        Countrywide
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