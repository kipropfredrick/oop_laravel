<!DOCTYPE html>
<html>
<head>
    <!-- title -->
    <title>New Payment</title>
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

    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

        td, th {
        border: 1px solid #dddddd;text-align: left;padding: 8px;
    }

        tr:nth-child(even) {
        background-color: #dddddd;
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
        <div style="margin: 20px 10px;" class="main-body">
            <div style="background-color: #ffffff;padding: 10px;max-width: 600px;margin: auto;" class="email-body">
                <div style="background-color: #1e22a9;padding: 10px;" class="email-header">
                    <img style="width: 200px;" src="https://mosmos.co.ke/assets/img/logo/web-logo.png" alt="Lipa Mos Mos">
                </div>

                <div class="email-text">
                    <p>Hello, {{ucfirst($details['customer_name'])}}.</p>

                    <p>Your payment of <strong>KSh. {{number_format($details['latestPayment']->transaction_amount,2)}}</strong> for <strong>{{$details['product_name']}}</strong> has been received.</p>

                    <p>Booking Code: <strong>{{$details['booking_reference']}}</strong><br>
                        M-PESA Reference: <strong>{{$details['latestPayment']->mpesapayment->transac_code}}</strong><br>
                        Payment Date: <strong>{{date('M d'.', '.'Y', strtotime($details['latestPayment']->mpesapayment->date_paid))}}</strong></p>

                    <!-- Payment history table -->
                    <h4>Payment History</h4>
                    
                    <table>
                        <tr>
                            <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">No.</th>
                            <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Date</th>
                            <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">M-Pesa Reference</th>
                            <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Amount Paid</th>
                        </tr>
                        <?php $index = 0; ?>
                        @foreach($details['payments'] as $payment)
                        <tr>
                            <td>{{$index = $index+1}}.</td>
                            <td>{{date('M d'.', '.'Y', strtotime($payment->mpesapayment->date_paid))}}</td>
                            <td>{{$payment->mpesapayment->transac_code}}</td>
                            <td>KSh.{{number_format($payment->transaction_amount,2)}}</td>
                        </tr>
                       @endforeach
                    </table>

                    <h4>Summary</h4>
                    
                    <table>
                        <tr>
                            <td>Total Paid</td>
                            <td>KSh.{{$details['amount_paid']}}</td>
                        </tr>
                        <tr>
                            <td>Total Product Cost</td>
                            <td>KSh.{{$details['total_cost']}}</td>
                        </tr>
                        <tr>
                            <td>Balance</td>
                            <td>KSh.{{$details['balance']}}</td>
                        </tr>
                    </table>
                    <!-- end -->

                    <p>Thank you,<br/>
                        Lipa Mos Mos Team</p>
                </div>
            </div>

            <div style="padding: 10px;max-width: 600px;margin: auto;text-align: center;" class="email-footer">
                <div style="text-align: center;" class="ftr-socials">
                    <a href="https://facebook.com/lipamosmoske" target="_blank"><span style="color: #fff;width: 30px;height: 30px;border-radius: 50%;text-align: center;line-height: 30px;vertical-align: middle;padding: 10px;margin: 10px;background: #e1306c;" class="fa fa-2x fa-facebook"></span></a>
                    <a href="https://instagram.com/lipamosmoske" target="_blank"><span style="color: #fff;width: 30px;height: 30px;border-radius: 50%;text-align: center;line-height: 30px;vertical-align: middle;padding: 10px;margin: 10px; background: #007bff;" class="fa fa-2x fa-instagram"></span></a>
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