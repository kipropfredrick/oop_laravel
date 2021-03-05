<!DOCTYPE html>
<html>
<head>
    <!-- title -->
    <title>Payment Reminder</title>
    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/3e5f9a0a23.js"></script>

    <!-- Google Fonts --> 
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
</head>
<style>
    body {
        background-color: #f8f8f8;
        font-family: 'Source Sans Pro', sans-serif;
    }

    .main-body {
        margin: 20px 10px;
    }

    .email-header {
        background-color: #1e22a9;
        padding: 10px;
    }

    .email-header img {
        width: 200px;
    }

    .email-body {
        background-color: #ffffff;
        padding: 10px;
        max-width: 600px;
        margin: auto;
    }

    .email-btn a {
        background-color: #1e22a9;
        color: #ffffff;
        padding: 5px 10px;
        border-radius: 5px;
        text-decoration: none;
    }

    .email-btn a:hover {
        background-color: #f68b1e;
    }

    .email-footer {
        padding: 10px;
        max-width: 600px;
        margin: auto;
        text-align: center;
    }

    .ftr-socials {
    text-align: center;
    }

    .ftr-socials a .fa {
        color: #fff;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        text-align: center;
        line-height: 30px;
        vertical-align: middle;
        padding: 10px;
        margin: 10px;
    }

    .fa-facebook {
        background: #4267b2;
    }

    .fa-instagram {
        background: #e1306c;
    }

    .ftr-socials a .fa:hover {
        background: #007bff;
    }
</style>
    <body>
        <div class="main-body">
            <div class="email-body">
                <div class="email-header">
                    <img src="https://mosmos.co.ke/assets/img/logo/web-logo.png" alt="Lipa Mos Mos">
                </div>

                <div class="email-text">
                    <p>Hello, {{ $details['customer_name'] }}.</p>
                    
                    <p>Thank you for your interest in using <strong>Lipa Mos Mos</strong>.</p>

                    <p>Complete your booking for <strong>{{ $details['product_name'] }}</strong>.<br>
                        Use Paybill Number <strong>4040299</strong> and account number <strong>{{$details['booking_reference']}}</strong>.<br>
                        Total amount is <strong>KSh.{{ $details['total_cost'] }}</strong>.<br>
                        You can book with a minimum of <strong>KSh.500</strong>.</p>

                    <!-- For those who have not made a subsequent payment in 3 weeks -->
                    <p>Hello, {{ $details['customer_name']}}.</p>

                    <p>
                        It has been a while. Keep paying for <strong>{{ $details['product_name'] }}</strong> to get it delivered to you.
                    </p>

                    <p>
                        Use Paybill Number <strong>4040299</strong> and account number <strong>{{$details['booking_reference']}}</strong>.<br>
                        The balance is <strong>KSh.[balance]</strong>.
                    </p>
                    <!-- end -->

                    <p>Thank you,<br/>
                        Lipa Mos Mos Team</p>
                </div>
            </div>

            <div class="email-footer">
                <div class="ftr-socials">
                    <a href="https://facebook.com/lipamosmoske" target="_blank"><span class="fa fa-2x fa-facebook"></span></a>
                    <a href="https://instagram.com/lipamosmoske" target="_blank"><span class="fa fa-2x fa-instagram"></span></a>
                </div>
                <div>
                    <span>
                        <a href="https://mosmos.co.ke">Visit Website</a>
                    </span> |
                    <span>
                        <a href="mailto:support@mosmos.co.ke">Email Support</a>
                    </span>
                </div>
                <p>&copy; Lipa Mos Mos | All Rights Reserved</p>
            </div>
        </div>

    </body>
</html>