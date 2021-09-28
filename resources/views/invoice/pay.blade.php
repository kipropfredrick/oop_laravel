<!-- header-->
@extends('invoice.head')
<!-- end-->

    <!-- header -->
    @section('content')
    <div class="pay-h">
    </div>
    <!-- end -->

    <!-- pay -->
    <div class="bg">
        <div class="container">
            <div class="pay-sec">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="pay-sum">
                            <h5>Payment Summary</h5>
                            <div><strong>Invoice #:</strong> {{$booking->booking_reference}}</div>
                            <div><strong>Total:</strong> KSh.{{number_format($booking->total_cost)}}</div>
                            <div><strong>Paid:</strong> KSh.{{number_format($booking->amount_paid)}}</div>
                            <div><strong>Balance:</strong> KSh.{{number_format($booking->balance)}}</div>
                        </div>
                    </div>

                    <div class="col-sm-8">
                        <div>
                            <h3>One-Click Payment</h3>
                            <p>Hello <strong>{{$booking->customer->user->name}}</strong>. Choose your preferred mode of payment and pay for <strong>{{$booking->product->product_name}}</strong>.</p>
                        </div>

                        <!-- Select iPay -->
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="method" id="card" value="1" checked>
                                <label class="form-check-label" for="card">
                                    Pay with Card (Visa, Mastercard)
                                </label>
                            </div>

                            <!-- iPay form -->
                        <form action="https://mosmos.co.ke/TravelCardTransaction" method="post">
                            {{csrf_field()}}
                            <input type="text" hidden="" value="{{$booking->customer->phone}}" name="phone">
                               <input type="text" hidden="" value="{{$booking->booking_reference}}" name="bookingref">

                              <div id="fromcard">
                                <div class="pay-form">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <input type="email" class="form-control" placeholder="Email Address*" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="number" class="form-control" value="" min="50" placeholder="Amount*" name="amount" required>
                                    </div>
                                </div>
                            </div>
                              <div class="mt-3">
                                <button type="submit" class="btn btn-block inv-btn">Pay Now</button>
                            </div>
                          </div>
                        </form>
                            
                            <!-- Select M-Pesa -->
                            <div class="form-check">
                                <input class="form-check-input" name="method" type="radio" id="mpesa" value="2">
                                <label class="form-check-label" for="mpesa">
                                    Pay with M-Pesa
                                </label>
                            </div>

                            <!-- M-Pesa form -->
                                  <form action="https://mosmos.co.ke/simulatetransaction" method="post">
                            {{csrf_field()}}
                           <input type="text" hidden="" value="{{$booking->booking_reference}}" name="bookingref">
                            <div id="frommpesa">
                            <div class="pay-form">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <input type="number" value="{{$booking->customer->phone}}" class="form-control" placeholder="Phone Number*" required name="phone">
                                    </div>
                                    <div class="form-group col-md-6"> 
                                        <input type="number" class="form-control" placeholder="Amount*" name="amount" required>
                                    </div>
                                </div>
                            </div>
                            <!-- end --> 
                                        <div class="mt-3">
                                <button type="submit" class="btn btn-block inv-btn">Pay Now</button>
                            </div>
                            
</form>
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
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script> -->

<!-- end-->
  <script type="text/javascript">
    $("#frommpesa").hide();
    $(function()
    {
      $('[name="method"]').change(function()
      {
        if ($(this).is(':checked')) {
           // Do something...
         
          if (this.value==2) {
  $("#fromcard").hide();
    $("#frommpesa").show();
          }
          else{
  $("#fromcard").show();
    $("#frommpesa").hide();
          }
        };
      });
    });
  </script>
@endsection