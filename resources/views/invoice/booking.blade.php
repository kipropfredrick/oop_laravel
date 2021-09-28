<!-- header-->
@extends('invoice.head')
<!-- end-->

    <!-- booking lead -->
    @section('content')
    <div class="bg-gray">
        <div class="container">

            <div>
                <div>
                    <h3>{{$booking->product->product_name}}</h3>
                    <span><strong>Total:</strong> KSh.{{number_format($booking->total_cost)}}</span> /
                    <span><strong>Paid:</strong> KSh.{{number_format($booking->amount_paid)}}</span> /
                    <span><strong>Balance:</strong> KSh.{{number_format($booking->balance)}}</span>
                </div>

                <div class="mt-3">
                       @if($booking->total_cost<=$booking->amount_paid)
                        Fully paid
                       
                        @else
                       <a href="/payments/{{$ordernumber}}/pay" class="btn inv-btn">Pay Now<span></span></a>
                        @endif
                    
                </div>
            </div>

        </div>
    </div>
    <!-- end -->

    <!-- booking invoice -->
    <div class="bg">
        <div class="container">

            <div class="inv-sec">
                <div class="inv-logo text-center">
                    <img src="{{asset('assets/img/logo/logo-blue.png')}}"> 
                    <h3>Invoice: {{$booking->booking_reference}}</h3>
                    <?php $date=Now(); ?>
                    <p>  {{ $date->format('M d, Y')  }}</p>
                </div>

                <div>
                    <div class="inv-status">
                        @if($booking->total_cost<=$booking->amount_paid)
                        Fully paid
                         @elseif($booking->amount_paid==0)
                        Unpaid
                        @else
                        Partially Paid
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div><strong>To:</strong></div>
                            <div>{{$booking->customer->user->name}}</div>
                            <div>{{$booking->customer->user->email}}</div>
                            <div>Kenya</div>
                        </div>
                        
                        <div class="col-6">
                            <div class="inv-ta">
                                <div><strong>From:</strong></div>
                                <h5>MosMos Payments</h5>
                                <div>order@mosmos.co.ke</div>
                                <div>Nairobi,Kenya</div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="inv-table table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Description</th>
                                        <th scope="col">Unit</th>
                                        <th scope="col">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{$booking->product->product_name}}</td>
                                        <td>1</td>
                                        <td>KSh.{{number_format($booking->total_cost)}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>Amount<strong></td>
                                        <td>KSh.{{number_format($booking->total_cost)}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>Paid<strong></td>
                                        <td>KSh.{{number_format($booking->amount_paid)}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>Balance<strong></td>
                                        <td>KSh.{{number_format($booking->balance)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-3">
                           @if($booking->total_cost<=$booking->amount_paid)
                        Fully paid
                        @else
                       <a href="/payments/{{$ordernumber}}/pay" class="btn btn-block inv-btn">Pay Now<span></span></a>
                        @endif

                        
                    </div>

                    <div>
                        <div class="inv-table">
                            <h5>Transactions</h5>
                            
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">Date</th>
                                            <th scope="col">Gateway</th>
                                            <th scope="col">Transaction ID</th>
                                            <th scope="col">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                           
                                            @foreach($booking->payments as $value)
                                             <tr>
                                                <td>
                                                    {{ $value->created_at->format('M d Y')  }}
                                                </td>
                                                <td>
                                                    {{$value->transaction_type?$value->transaction_type:'Mpesa'}}
                                                </td>
                                                <td>{{$value->booking->booking_reference}}</td>
<td>ksh. {{number_format($value->transaction_amount)}}</td>
                                                 </tr>

                                            @endforeach 
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end -->

<!-- footer-->
<div class="bg">
    <div class="container">
        <div class="inv-ftr">
            <a href="https://travelmosmos.co.ke">
                <img src="{{asset('assets/img/logo/logo-blue.png')}}">
            </a>
        </div>
    </div>
</div>

<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
@endsection
<!-- end-->