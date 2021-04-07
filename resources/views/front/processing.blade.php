@extends('layouts.app')

@section('title', "Processing Payment")

@section('content')
<!-- breadcrumb --> 
<div class="bc-bg">
    <div class="container">
        <div class="bc-link">
            <a href="/">
                <i class="fas fa-home"></i>
            </a>

            <span class="bc-sep"></span>

            <a href="/product/{{$product->slug}}">
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
                              <p id="message"> <span id="introMsgBp"></span> <span id="pbSection" style="font-weight:bold"></span> <span id="bkgRefMsgIntro"> </span> <span style="font-weight:bold" id="bkgRefMsg"></span> <span id="amtIntro"></span> <span id="amntMsg" style="font-weight:bold"></span> <span id="thankMsgBp"></span> </p>
                              <p id="messageN"> <span id="introMsgBp"></span> <span id="pbSection" style="font-weight:bold"></span> <span id="bkgRefMsgIntro"> </span> <span style="font-weight:bold" id="bkgRefMsg"></span> <span id="amtIntro"></span> <span id="amntMsg" style="font-weight:bold"></span> <span id="thankMsgBp"></span> </p>
                              <p  id="direction">Use Paybill <strong>4040299</strong> and account number <strong>{{$booking_reference}}</strong> for all your next payments.
                            </div>

                            <input type="hidden" name="payment_made" id="payment_made">

                            <a href="/login" id="d-button" class="btn p-btn">Visit Dashboard</a>

                        </div>

                          <input type="hidden" name="payment_ref" id="payment_ref" value="{{$booking_reference}}">
                          <input type="hidden" id="msg_input" value="{{$stkMessage}}">
                          <input type="hidden" id="amount_input" value="{{number_format($amount)}}">

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

@section('extra-js')

<script>
    $(document).ready(function(){

    $('#paymentProcess').show();

    $('#afterPaymentView').hide();

    $('#payment_made').val('0');
  
    var payment_ref = $("#payment_ref").val();
    var intVar = setInterval(checkPayment, 3000);

    setTimeout(function( ) { 
        clearInterval(intVar); 
        $('#loadingView').hide();
        $('#paymentProcess').hide();
        $('#payAlert').hide();
        
        var message =  $('#msg_input').val();
        var amount = $('#amount_input').val();
        var paid =  $('#payment_made').val();

        if(paid == "0"){

            $('#message').show();
            $('#d-button').hide();
            $('#afterPaymentView').show();
            $('#direction').hide();

            document.getElementById('introMsgBp').innerHTML = "No payment has been received, Go to your MPESA, Select, Paybill Enter :";
            document.getElementById('pbSection').innerHTML = "4040299 ";
            document.getElementById('amtIntro').innerHTML = " , Enter Amount : ";
            document.getElementById('amntMsg').innerHTML = amount;
            document.getElementById('bkgRefMsgIntro').innerHTML = " and Account Number : ";
            document.getElementById('bkgRefMsg').innerHTML = payment_ref;
            document.getElementById('thankMsgBp').innerHTML = " , Thank you.";
        }

        }, 30000);

    function checkPayment(){
        // console.log('Payment Ref =>'+payment_ref);
        $.ajax({
            /* the route pointing to the post function */
            url: '/checkpayment',
            type: 'POST',
            data: {payment_ref: payment_ref,_token:"{{ csrf_token() }}"},
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) { 
                if(data.status==0){
                    
                }else{
                    // window.location.replace(base_url);
                    clearInterval(intVar);
                    // document.getElementById("message").innerHTML = data.message;
                    $('#paymentProcess').hide();
                    $('#afterPaymentView').show();
                    $('#direction').show();
                    $('#message').show();
                    $('#messageN').show();
                    $('#d-button').show();

                    $('#payment_made').val('1');

                    document.getElementById('introMsgBp').innerHTML = "Hello ";
                    document.getElementById('pbSection').innerHTML =  data.name;
                    document.getElementById('bkgRefMsgIntro').innerHTML = ", We have received your payment of of Ksh ";
                    document.getElementById('bkgRefMsg').innerHTML = data.amount;
                    document.getElementById('amtIntro').innerHTML = " for product ";
                    document.getElementById('amntMsg').innerHTML = data.product;
                    document.getElementById('thankMsgBp').innerHTML = "";
                    
                }    
            }
        }); 
    }  
    });
</script>

@endsection