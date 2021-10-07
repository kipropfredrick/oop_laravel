<!DOCTYPE html>
<html>
<head>
    <title>Payment Invoice | Travel Mos Mos</title>
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js'></script>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>

    <style>

        body {
        background-color: #f8f8f8;
    }

    .main-body {
        margin: 20px 10px;
    }

    .email-header {
        background-color: #CCC;
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
    <img style="height:50px;width:100px;object-fit:contain;" src="https://travelmosmos.co.ke/storage/images/{{$details['agent']->company_logo}}" alt="Travel Mos Mos">
</div>

<div class="container mt-5 mb-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-8">
            <div class="card" style="padding:10px;">

             <?php $now = now(); ?>

                {{date('M d'.', '.'Y', strtotime($now))}}
        
                    <h3><strong>{{ucfirst($details['customer']->name)}} Payment History</strong></h3>

                    <div class="row d-flex justify-content-end">
                        <div class="col-md-5">
                            <table id="invoice" class="table table-striped table-hover">

                                <thead class="">
                                    <tr>
                                        <th scope="col">No. </th>
                                        <th scope="col">Date</th>
                                        <th scope="col">TXN Ref</th>
                                        <th scope="col">Amount Paid (KES)</th>
                                    </tr>
                                </thead>
                                <tbody class="totals">

                                <?php $index = 0; $payments = $details['payments'];  ?>
                                 @foreach($payments as $payment)
                                    <tr>
                                        <td scope="row"><strong>{{$index = $index+1}}.</strong></td>
                                        <td>{{date('M d'.', '.'Y', strtotime($payment->created_at))}}</td>
                                        <td>{{$payment->TransID}}</td>
                                        <td>{{number_format($payment->TransAmount,2)}}</td>
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
                            <td>KSh.{{number_format($details['booking']->amount_paid)}}</td>
                        </tr>
                        <tr>
                            <td>Total Cost</td>
                            <td>KSh.{{$details['booking']->total_cost}}</td>
                        </tr>
                        <tr>
                            <td>Balance</td>
                            <td>KSh.{{$details['booking']->balance}}</td>
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