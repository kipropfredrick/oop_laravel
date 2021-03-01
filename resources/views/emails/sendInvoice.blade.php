<!DOCTYPE html>
<html>
<head>
    <title>Combine</title>
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js'></script>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>

    <style>

        @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');

        body {
            background-color: #1E22A9;
            font-family: 'Montserrat', sans-serif
        }

        .card {
            border: none
        }

        .logo {
            background-color: #333333
        }

        .totals tr td {
            font-size: 13px
        }

        .footer {
            background-color: #333333
        }

        .footer span {
            font-size: 12px
        }

        .product-qty span {
            font-size: 12px;
            color: #dedbdb
        }

        #invoice {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        }

        #invoice td, #customers th {
        border: 1px solid #ddd;
        padding: 8px;
        }

        #invoice tr:nth-child(even){background-color: #f2f2f2;}

        #invoice tr:hover {background-color: #ddd;}

        #invoice th {
        padding-top: 12px;
        padding-bottom: 12px;
        padding-left: 10px;
        text-align: left;
        background-color:#333333;
        color: white;
        }


    </style>

</head>
<body>
<h1 class="text-center">Lipia Mosmos</h1>
<div class="container mt-5 mb-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-8">
            <div class="card">
                    <h2><strong>Your Payment has been Received!</strong></h2> 
                    <span class="font-weight-bold d-block mt-4">Hello, {{ucfirst($details['customer_name'])}}</span> <span>Your payment of <strong>KES {{number_format($details['latestPayment']->transaction_amount,2)}}</strong> has been received for <strong>{{$details['product_name']}}</strong>, booking reference <strong>{{$details['booking_reference']}}</strong> <br> Mpesa Code : <strong>{{$details['latestPayment']->mpesapayment->transac_code}}</strong></span>
                    <div class="payment border-top mt-3 mb-3 border-bottom table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="py-2"> <span class="d-block text-muted">Payment Date : </span> <span></span> {{date('M d'.', '.'Y', strtotime($details['latestPayment']->mpesapayment->date_paid))}}</div> 
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h3><strong>Payment History</strong></h3>

                    <div class="row d-flex justify-content-end">
                        <div class="col-md-5">
                            <table id="invoice" class="table table-bordered table-striped table-hover">

                                <thead class="thead-dark">
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
                                        <td>{{date('M d'.', '.'Y', strtotime($payment->mpesapayment->date_paid))}}</td>
                                        <td>{{$payment->mpesapayment->transac_code}}</td>
                                        <td>{{number_format($payment->transaction_amount,2)}}</td>
                                    </tr>
                                @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="3"><strong>Total Paid</strong> </td>
                                        <td>{{$details['amount_paid']}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><strong>Total Product Cost</strong> </td>
                                        <td>{{$details['total_cost']}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><strong>Balance</strong> </td>
                                        <td>{{$details['balance']}}</td>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                    </div>
                    <p class="font-weight-bold mb-0">Thanks for shopping with us!</p> <span>Combine Team</span>
                </div>
                <div> <span>Need Help? Call <a>0759 701 616</a></span></div>
            </div>
        </div>
    </div>
</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>   
</body>
</html>