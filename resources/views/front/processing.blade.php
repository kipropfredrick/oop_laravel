@extends('layouts.app')

@section('content')
<!-- breadcrumb --> 
<div class="bc-bg">
    <div class="container">
        <div class="bc-link">
            <a href="index.php">
                <i class="fas fa-home"></i>
            </a>

            <span class="bc-sep"></span>

            <a href="product.php">
                <span>{{$product->product_name}}</span>
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

                        <div id="paymentProcess" class="row justify-content-center padding">
                            <img style="hight:50px;width:50px" src="{{asset('images/spinner.gif')}}"/>
                            <h6 style="margin:20px">Processing Payment please wait ...</h6>
                        </div>

                        <div id="afterPaymentView">
                            <h3>Thank you</h3>
                           
                            <div class="message">
                              <p id="message"></p>
                              <p id="messageN"></p>
                              <p  id="direction">Use Paybill <strong>4040299</strong> and account number <strong>{{$booking_reference}}</strong> for all your next 
                            </div>

                            <a href="/login" class="btn p-btn">Visit Dashboard</a>
                        </div>

                          <input type="hidden" name="payment_ref" id="payment_ref" value="{{$booking_reference}}">
                          <input type="hidden" id="msg_input" value="{{$stkMessage}}">

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

@section('extra-js')

<script>
    $(document).ready(function(){

    $('#paymentProcess').show();

    $('#afterPaymentView').hide();
  
    var payment_ref = $("#payment_ref").val();
    var intVar = setInterval(checkPayment, 3000);

    setTimeout(function( ) { 
        clearInterval(intVar); 
        $('#loadingView').hide();
        $('#paymentProcess').hide();
        $('#payAlert').hide();
        $('#message').show();
        $('#afterPaymentView').show();
        var message =  $('#msg_input').val();
        document.getElementById("messageN").innerHTML = "No payment as been received! "+message;
        }, 30000);

    function checkPayment(){
        console.log('Payment Ref =>'+payment_ref);
        $.ajax({
            /* the route pointing to the post function */
            url: '/checkpayment',
            type: 'POST',
            data: {payment_ref: payment_ref,_token:"{{ csrf_token() }}"},
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) { 
                console.log(data);
                if(data.status==0){
                    
                }else{
                    // window.location.replace(base_url);
                    clearInterval(intVar);
                    document.getElementById("message").innerHTML = data.message;
                    $('#paymentProcess').hide();
                    $('#afterPaymentView').show();
                    $('#direction').show();
                    $('#message').show();
                    $('#messageN').hide();
                }    
            }
        }); 
    }  
    });
</script>

@endsection