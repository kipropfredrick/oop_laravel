@extends('emails.master')

@section('content')
       <div class="container">

       <div class="card">

       <div style="background-color:#1E22A9;color:#FFF;padding:5px;width:100%" class="card-header">
            <img style="width:150px;height:60px;object-fit:contain;margin-left:auto" class="text-center center" src="{{asset('assets/img/logo/web-logo.png')}}">
       </div>

       <div class="card-body padding">

               <h5 class="text-center mt-2"><strong>Your Payment has been Received!</strong></h5 class="text-center"> 
               <hr>
               <span class="font-weight-bold d-block mt-4">Hello, {{ucfirst($details['customer_name'])}}</span> <span>Your payment of <strong>KES {{number_format($details['latestPayment']->transaction_amount,2)}}</strong> has been received for <strong>{{$details['product_name']}}</strong>, booking reference <strong>{{$details['booking_reference']}}</strong> <br> Mpesa Code : <strong>{{$details['latestPayment']->mpesapayment->transac_code}}</strong></span>
                     Payment Date : <strong>{{date('M d'.', '.'Y', strtotime($details['latestPayment']->mpesapayment->date_paid))}}</strong></span>
                    



                    <br>
                    <br>

             <p><strong>Thank you.</strong></p>


             <h5 class="text-center"><strong>Payment History</strong></h5>

                <div class="row d-flex justify-content-end">
                    <div class="col-md-12 padding">
                    <table id="invoice" class="table table-bordered table-striped table-hover">

                        <thead style="background-color:#1E22A9 !important;color:#FFF">
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
                <p class="font-weight-bold mb-0">Thanks for shopping with us!</p> <span>Lipia Mos Mos Team</span>
                </div>
                <div class="text-center padding"> <span>Need Help? Call <a>0113 980 270</a></span></div>

           
       </div>

       <div class="card-footer">

        <div class="text-center">
            &copy; Copyright, Lipa Mos mos
        </div>

        </div>
           
       </div>
       
       </div>
@endsection