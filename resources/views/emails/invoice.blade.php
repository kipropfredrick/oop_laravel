<!DOCTYPE html>
<html>
<head>
    <title>New Payment | Lipa Mos mos</title>
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js'></script>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>

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

</head>
<body>

<div class="email-header">
    <img src="https://mosmos.co.ke/assets/img/logo/web-logo.png" alt="Lipa Mos Mos">
</div>

<div class="container mt-5 mb-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-8">
            <div class="card" style="padding:10px;">

             <?php $now = now(); ?>

                {{date('M d'.', '.'Y', strtotime($now))}}
        
                    <h3><strong>{{ucfirst($details['customer_name'])}} Payment History</strong></h3>

                    <div class="row d-flex justify-content-end">
                        <div class="col-md-5">
                            <table id="invoice" class="table table-striped table-hover">

                                <thead class="">
                                    <tr>
                                        <th scope="col">No. </th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Mpesa Code</th>
                                        <th scope="col">Amount Paid (KES)</th>
                                    </tr>
                                </thead>
                                <tbody class="totals">
                                <?php $index = 0; ?>
                                 @foreach($details['payments'] as $payment)
                                    <tr>
                                        <td scope="row"><strong>{{$index = $index+1}}.</strong></td>
                                        <td>{{date('M d'.', '.'Y', strtotime($payment->created_at))}}</td>
                                        <td>{{$payment->mpesapayment?$payment->mpesapayment->transac_code:NULL}}</td>
                                        <td>{{number_format($payment->transaction_amount,2)}}</td>
                                    </tr>
                                @endforeach
                                </tbody>


                            </table>
                        </div>
                    </div>
                    
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
                    
                </div>

            </div>
        </div>
    </div>
</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>   
</body>
</html>