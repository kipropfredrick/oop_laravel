@extends('layouts.app')

@section('title', 'FAQs')

@section('content')
<!-- breadcrumb --> 
<div class="bc-bg">
    <div class="container">
        <div class="bc-link">
            <a href="/">
                <i class="fas fa-home"></i>
            </a>

            <span class="bc-sep"></span>

            <span>Frequently Asked Questions</span>
        </div>
    </div>
</div>
<!-- end -->

<!-- page content -->
<div class="bg-white">
    <div class="container">
        <div>
            <h1>Frequently Asked Questions</h1>

            <h3>How can I place my order on the website?</h3>
            
            <ul>
                <li>On your selected item, click on the Lipa Mos Mos button</li>
                <li>Enter your name, phone number and email address</li>
                <li>Enter the amount you want to pay. We accept a minimum of <strong>KSh.100</strong> on all items for your first order</li>
                <li>Click on the Proceed to Pay button</li>
                <li>You'll be prompted to enter your M-Pesa pin to pay automatically</li>
                <li>If you don't receive the prompt, follow the steps sent to you on SMS to activate your booking</li>
                <li>Take note of your <strong>order ID</strong>, you will use it to make your subsequent payments</li>
            </ul>

            <!-- <h3>How can I order with USSD?</h3>
            
            <ul>
                <li>Dial <strong>*000*0#</strong> on your phone</li>
                <li>Select make a booking</li>
                <li>Follow the prompt to register your account if not registered</li>
                <li>Enter the product code of the item you want to order (available on every item on the website) e.g.<strong>P0001</strong></li>
                <li>Enter the amount you want to pay. We accept a minimum of <strong>KSh.100</strong> on all items for the first order</li>
                <li>Click on the Proceed to Pay</li>
                <li>You'll be prompted to enter your M-Pesa pin to pay automatically</li>
                <li>If you don't receive the prompt, follow the steps sent to you on SMS to activate your booking</li>
                <li>Take note of your <strong>order ID</strong>, you will use it to make your subsequent payments</li>
            </ul> -->

            <h3>How do I make subsequent payments?</h3>
            
            <ul>
                <li>Go to your M-Pesa menu</li>
                <li>Select Lipa na M-Pesa</li>
                <li>Select Paybill option</li>
                <li>Enter Paybill number <strong>4040299</strong></li>
                <li>Enter your your <strong>Order ID</strong> as the account number</li>
                <li>Enter Amount you want to pay</li>
                <li>Complete the process to pay</li>
                <li>You will receive an email and SMS statement of your order</li>
            </ul>

            <h3>How do I make subsequent payments?</h3>

            <p>
            We recommend a minimum amount of KSh.100 for the first installment. You can pay any amount for the other installments within your order total amount.
            </p>

            <h3>How long do I have to complete payment for my order?</h3>

            <p>
            We reserve the item you have ordered exclusively for you for 90 days. After this, delivery of the item is subject to availability. We however do everything to ensure you get the items you order.
            </p>

            <h3>Do you charge interest?</h3>

            <p>
            Not at all, you only pay the product cost and delivery fee whether you complete your order before or after 90 days. There are no additional or hidden charges and costs.
            </p>

            <h3>Do you deliver?</h3>

            <p>
            Yes, we deliver Countrywide delivery Upon completion of payment through our delivery partners.
            </p>

        </div>
    </div>
</div>
<!-- end --> 
@endsection