@extends('backoffice.app')

@section('content')
<!-- Main content -->

<section class="content">
    @if(Sentinel::hasAccess('dashboard'))
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        @if(auth()->user()->role == "admin")

        <?php 

        $totalBookingAmount = \App\Bookings::where('amount_paid','>',0)->sum('total_cost');
        $totalBookingCount = \App\Bookings::distinct('customer_id')->whereIn('status',['complete','active','overdue','unserviced'])->count();
        $productsCount = \App\Products::where('status','=','approved')->count();
        $activeBookingAmount = \App\Bookings::whereIn('status',['active','overdue','unserviced'])->sum('total_cost');
        $activeBookingsCount = \App\Bookings::distinct('customer_id')->whereIn('status',['active','overdue','unserviced'])->count();
        $overdueBookingAmount = \App\Bookings::where('status','=','overdue')->sum('total_cost');
        $completeBookingAmount = \App\Bookings::where('status','=','complete')->sum('total_cost');
        $completeBookingCount = \App\Bookings::where('status','=','complete')->count();
        $pendingBookingAmount = \App\Bookings::where('status','=','pending')->sum('total_cost');
        $pendingBookingCount = \App\Bookings::where('status','=','pending')->count();

$customers=\App\Bookings::whereIn('status',['active','overdue','unserviced'])->pluck('customer_id')->toArray();
        $customersCount = \App\Customers::whereIn("id",$customers)->count();
        $vendorsCount = \App\Vendor::count();
                
        ?>

        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{number_format($totalBookingCount)}}</h3>
                <h5>KES {{number_format($totalBookingAmount)}}</h5>

                <p>Total Bookings</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="/admin/active_bookings" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{number_format($activeBookingsCount)}}</h3>
                <h5>KES {{number_format($activeBookingAmount)}}</h5>

                <p>Active Bookings</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="/admin/active_bookings" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{number_format($completeBookingCount)}}</h3>

                <h5>KES {{number_format($completeBookingAmount)}}</h5>


                <p>Complete Bookings</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="/admin/complete_bookings" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

           <!-- ./col -->
           <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{number_format($pendingBookingCount)}}</h3>

                <h5>KES {{number_format($pendingBookingAmount)}}</h5>

                <p>Pending Bookings</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="/admin/pending_bookings" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-secondary">
              <div class="inner">
                <h3>{{number_format($vendorsCount)}}</h3>

                <p>Vendors</p>
              </div>
              <div class="icon">
                <i class="fas fa-users"></i>
              </div>
              <a href="/admin/vendors" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{number_format($customersCount)}}</h3>

                <p>Active Customers</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              <a href="/admin/customers/active" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{number_format($productsCount)}}</h3>

                <p>Products</p>
              </div>
              <div class="icon">
                <i class="fa fa-shopping-basket"></i>
              </div>
              <a href="/admin/vendor/approved-products" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
            <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3> {{number_format($utiliybalance)}}</h3>

                <p>Utility balance</p>
              </div>
              <div class="icon">
                <i class="fa fa-shopping-basket"></i>
              </div>
              <a href="{{route('admin.agall')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          
          <!-- ./col -->
        </div>
        <!-- /.row -->

        @elseif(auth()->user()->role =='user')


        <?php 

        $customer = auth()->user()->customer;
        $customer_id = $customer->id;;
$phone=$customer->phone;
        $totalBookingAmount = \App\Bookings::where('amount_paid','>',0)->where('customer_id',$customer_id)->sum('total_cost');
        $totalBookingCount = \App\Bookings::where('amount_paid','>',0)->where('customer_id',$customer_id)->count();
        $activeBookingAmount = \App\Bookings::where('status','=','active')->where('customer_id',$customer_id)->sum('total_cost');
        $activeBookingsCount = \App\Bookings::where('status','=','active')->where('customer_id',$customer_id)->count();
        $revokedBookingAmount = \App\Bookings::where('status','=','revoked')->where('customer_id',$customer_id)->sum('total_cost');
        $revokedBookingCount = \App\Bookings::where('status','=','revoked')->where('customer_id',$customer_id)->count();
        $completeBookingAmount = \App\Bookings::where('status','=','complete')->where('customer_id',$customer_id)->sum('total_cost');
        $completeBookingCount = \App\Bookings::where('status','=','complete')->where('customer_id',$customer_id)->count();
        $pendingBookingAmount = \App\Bookings::where('status','=','pending')->where('customer_id',$customer_id)->sum('total_cost');
        $pendingBookingCount = \App\Bookings::where('status','=','pending')->where('customer_id',$customer_id)->count();
        $customers=DB::table('customers')->where('id','=',$customer_id)->first();
        $balance=DB::table("users")->whereId($customers->user_id)->first()->balance;

        $existingUser = \App\User::where('email',  auth()->user()->email)->first();
$email=auth()->user()->email;
$hasbooking=false;
        if($existingUser!=null)
        {

        $user = $existingUser;

        $existingCustomer = \App\Customers::where('user_id','=',$existingUser->id)->first();


        $booking = \App\Bookings::where('customer_id','=',$existingCustomer->id)->whereNotIn('status', ['complete','revoked'])->first();

        if ($booking!=null) {
          # code...
          $hasbooking=true;
        }
      }
                
        ?>
       
        <div class="row">
     <div style="float:right; width: 100%;">
    @if(Session::has("success"))
    <div class="alert alert-info float-right" role="alert">
  {{Session::get("success")}}. 
</div>

    @endif

    @if(Session::has("error"))
    <div class="alert alert-danger float-right" role="alert">
{{Session::get("error")}}. 
</div>

    @endif </div>
         <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Redeem Cash To Airtime</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <form id="form" action="{{route('customer.redeem')}}" method="POST" class="form">
        {{csrf_field()}}
         <div class="modal-body">
<p> My Wallet Balance: KES {{$balance}} </p>
<p >A service fee of 30 % will be charged</p>
                     <div class="form-group col-12 m-0">
                        <label for="title">Phone Number</label>
                        <input type="text" name="phone" id="phone"
                               class="form-control"
                               data-rule-maxlength="255"
                               data-rule-required="true" value="{{$phone}}">
                       
                    </div>
                     <div class="form-group col-12 m-0">
                        <label for="title">Amount</label>
                        <input type="text" name="amount" id="amount"
                               class="form-control"
                               data-rule-maxlength="255"
                               data-rule-required="true" required="">
                       
                    </div>
                    </div>
                       <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Reedem</button>
      </div>
       </form>
 
    </div>
  </div>
</div>

<div class="col-12 card ">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Wallet and Payments</strong></h6>
    </div>
    <div class="row mt-1">
      <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-secondary">
              <div class="inner">
                

                <h5>KES  @if($activeBookingsCount==0) {{number_format($activeBookingAmount)}} @else {{$balance }} @endif</h5>


                <p>My Wallet Balance</p>
              </div>
              <div class="icon">
                <i class="fa fa-wallet"></i>
              </div>
             @if($activeBookingAmount==0)  
               <a href="#exampleModalCenter" data-toggle="modal" data-target="#exampleModalCenter" class="small-box-footer mt-3">Redeem <i class="fas fa-arrow-circle-right"></i></a> @endif
            </div>
          </div>

   <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h5>  KES @if($hasbooking) {{number_format($booking->amount_paid)}} @else 0 @endif</h5>

                <h6>Balance KES @if($hasbooking) {{number_format($booking->balance)}} @else 0 @endif </h6>


                <p>Payments</p>
              </div>
              <div class="icon">
                <i class="fa fa-check-circle"></i>
              </div>
              <a href="/customer/payments" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
    </div>
    
  
                </div>
             </div>

<div class="col-12 card ">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Order Booking Summary</strong></h6>
    </div>
    <div class="row mt-1">

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{number_format($completeBookingCount)}}</h3>

                <h5>KES {{number_format($completeBookingAmount)}}</h5>


                <p>Complete Bookings</p>
              </div>
              <div class="icon">
                <i class="fa fa-check-circle"></i>
              </div>
              <a href="/customer/complete-bookings" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>


          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{number_format($activeBookingsCount)}}</h3>

                <h5>KES {{number_format($activeBookingAmount)}}</h5>


                <p>Active Bookings</p>
              </div>
              <div class="icon">
                <i class="fa fa-check-square"></i>
              </div>
              <a href="/customer/active-bookings" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>


          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{number_format($pendingBookingCount)}}</h3>

                <h5>KES {{number_format($pendingBookingAmount)}}</h5>


                <p>Pending Bookings</p>
              </div>
              <div class="icon">
                <i class="fa fa-spinner"></i>
              </div>
              <a href="/customer/pending-bookings" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{number_format($revokedBookingCount)}}</h3>

                <h5>KES {{number_format($revokedBookingAmount)}}</h5>


                <p>Revoked Bookings</p>
              </div>
              <div class="icon">
                <i class="fa fa-times"></i>
              </div>
              <a href="/customer/revoked-bookings" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
             </div>
    
  
                </div>
             </div>
        
         
        </div>
         


        @elseif(auth()->user()->role == "vendor")

        
        <?php 

          $vendor = auth()->user()->vendor;
          $vendor_code = $vendor->vendor_code;

          $totalBookingAmount = \App\Bookings::where('amount_paid','>',0)->where('vendor_code',$vendor_code)->sum('total_cost');
          $totalBookingCount = \App\Bookings::where('amount_paid','>',0)->where('vendor_code',$vendor_code)->count();
          $activeBookingAmount = \App\Bookings::where('status','=','active')->where('vendor_code',$vendor_code)->sum('total_cost');
          $activeBookingsCount = \App\Bookings::where('status','=','active')->where('vendor_code',$vendor_code)->count();
          $revokedBookingAmount = \App\Bookings::where('status','=','revoked')->where('vendor_code',$vendor_code)->sum('total_cost');
          $revokedBookingCount = \App\Bookings::where('status','=','revoked')->where('vendor_code',$vendor_code)->count();
          $completeBookingAmount = \App\Bookings::where('status','=','complete')->where('vendor_code',$vendor_code)->sum('total_cost');
          $completeBookingCount = \App\Bookings::where('status','=','complete')->where('vendor_code',$vendor_code)->count();
          $pendingBookingAmount = \App\Bookings::where('status','=','pending')->where('vendor_code',$vendor_code)->sum('total_cost');
          $pendingBookingCount = \App\Bookings::where('status','=','pending')->where('vendor_code',$vendor_code)->count();
                  
          ?>


            <div class="row">


            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                  <div class="inner">
                    <h3>{{number_format($completeBookingCount)}}</h3>

                    <h5>KES {{number_format($completeBookingAmount)}}</h5>


                    <p>Complete Bookings</p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-check-circle"></i>
                  </div>
                  <a href="/vendor/complete-bookings" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>


              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-primary">
                  <div class="inner">
                    <h3>{{number_format($activeBookingsCount)}}</h3>

                    <h5>KES {{number_format($activeBookingAmount)}}</h5>


                    <p>Active Bookings</p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-check-square"></i>
                  </div>
                  <a href="/vendor/active-bookings" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>


              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-primary">
                  <div class="inner">
                    <h3>{{number_format($pendingBookingCount)}}</h3>

                    <h5>KES {{number_format($pendingBookingAmount)}}</h5>


                    <p>Pending Bookings</p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-spinner"></i>
                  </div>
                  <a href="/vendor/pending-bookings" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                  <div class="inner">
                    <h3>{{number_format($revokedBookingCount)}}</h3>

                    <h5>KES {{number_format($revokedBookingAmount)}}</h5>


                    <p>Revoked Bookings</p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-times"></i>
                  </div>
                  <a href="/vendor/revoked-bookings" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>


            </div>

        @endif
      </div><!-- /.container-fluid -->
 

      <div class="container">
    <div class="row d-flex justify-content-between">
     
        <div class="col-md-12 offset-md-0 card mr-5 ml-2">
            <div class="panel panel-default">
                               <div class="panel-body">
                    <canvas id="canvas" height="125" width="400"></canvas>
                </div>
            </div>
        </div>

           <div class="col-md-12 offset-md-0 card mr-5 ml-2">
            <div class="panel panel-default">
                               <div class="panel-body">
                    <canvas id="canvas1" height="125" width="400"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-12 offset-md-0 card mr-5 ml-2">
            <div class="panel panel-default">
                               <div class="panel-body">
                    <canvas id="canvas2" height="125" width="400"></canvas>
                </div>
            </div>
        </div>

      
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script>
    var days = <?php echo $days; ?>;
    var bookings = <?php echo $bookings; ?>;
    var customers=<?php echo $ucustom; ?>;
      var airtime=<?php echo $airtime; ?>;
      var utility=<?php echo $utility; ?>;
      var billcustomers=<?php echo $billcustomers; ?>;
      var airtimecustomers=<?php echo $airtimecustomers; ?>;
    var myColors = ['red', 'green', 'blue'];
    var barChartData = {
        labels: days,
        datasets: [{
            label: 'Bookings',
         fill: false,
          borderColor: "#F68B1E",
   backgroundColor: "#F68B1E",
   pointBackgroundColor: "#ED1C24",
   pointBorderColor: "#ED1C24",
   pointHoverBackgroundColor: "#ED1C24",
   pointHoverBorderColor: "#ED1C24",
            data: bookings
        },

        {
          label:"Unique Customers",
    fill: false,
   borderColor: "#2CB34A",
   backgroundColor: "#2CB34A",
   pointBackgroundColor: "#55bae7",
   pointBorderColor: "#55bae7",
   pointHoverBackgroundColor: "#55bae7",
   pointHoverBorderColor: "#55bae7",
            data: customers
        }]
    };


        var airtimeData = {
        labels: days,
        datasets: [{
            label: 'Airtime',
         fill: false,
          borderColor: "#2CB34A",
   backgroundColor: "#2CB34A",
   pointBackgroundColor: "#ED1C24",
   pointBorderColor: "#ED1C24",
   pointHoverBackgroundColor: "#ED1C24",
   pointHoverBorderColor: "#ED1C24",
            data: airtime
        },
          {
          label:"Unique Customers",
    fill: false,
   borderColor: "#F68B1E",
   backgroundColor: "#F68B1E",
   pointBackgroundColor: "#55bae7",
   pointBorderColor: "#55bae7",
   pointHoverBackgroundColor: "#55bae7",
   pointHoverBorderColor: "#55bae7",
            data: airtimecustomers
        }

     ]
    };

   var utilitiesData = {
        labels: days,
        datasets: [{
            label: 'Utility',
         fill: false,
          borderColor: "#1E22A9",
   backgroundColor: "#1E22A9",
   pointBackgroundColor: "#ED1C24",
   pointBorderColor: "#ED1C24",
   pointHoverBackgroundColor: "#ED1C24",
   pointHoverBorderColor: "#ED1C24",
            data: utility
        },
          {
          label:"Unique Customers",
    fill: false,
   borderColor: "#F68B1E",
   backgroundColor: "#F68B1E",
   pointBackgroundColor: "#55bae7",
   pointBorderColor: "#55bae7",
   pointHoverBackgroundColor: "#55bae7",
   pointHoverBorderColor: "#55bae7",
            data: billcustomers
        }

     ]
    };
    

    window.onload = function() {
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: 'line',
            data: barChartData,
            options: {
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: '#c1c1c1',
                        borderSkipped: 'bottom'
                    }
                },
                responsive: true,
                title: {
                    display: true,
                    text: 'Bookings Analysis (last 7 days)'
                }
            }
        });

//Airtime
            var ctx = document.getElementById("canvas1").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: 'line',
            data: airtimeData,
            options: {
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: '#c1c1c1',
                        borderSkipped: 'bottom'
                    }
                },
                responsive: true,
                title: {
                    display: true,
                    text: 'Airtime Purchases (last 7 days)'
                }
            }
        });

        //utilities

            var ctx = document.getElementById("canvas2").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: 'line',
            data: utilitiesData,
            options: {
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: '#c1c1c1',
                        borderSkipped: 'bottom'
                    }
                },
                responsive: true,
                title: {
                    display: true,
                    text: 'Utility Payment (last 7 days)'
                }
            }
        });
    };
</script>
@endif
    </section>
    <!-- /.content -->
@endsection
