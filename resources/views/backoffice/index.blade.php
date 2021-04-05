@extends('backoffice.app')

@section('content')
<!-- Main content -->

<section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        @if(auth()->user()->role == "admin")

        <?php 

        $totalBookingAmount = \App\Bookings::where('amount_paid','>',0)->sum('total_cost');
        $totalBookingCount = \App\Bookings::where('amount_paid','>',0)->count();
        $productsCount = \App\Products::where('status','=','approved')->count();
        $activeBookingAmount = \App\Bookings::where('status','=','active')->sum('total_cost');
        $activeBookingsCount = \App\Bookings::where('status','=','active')->count();
        $overdueBookingAmount = \App\Bookings::where('status','=','overdue')->sum('total_cost');
        $completeBookingAmount = \App\Bookings::where('status','=','complete')->sum('total_cost');
        $completeBookingCount = \App\Bookings::where('status','=','complete')->count();
        $pendingBookingAmount = \App\Bookings::where('status','=','pending')->sum('total_cost');
        $pendingBookingCount = \App\Bookings::where('status','=','pending')->count();

        $customersCount = \App\Customers::count();
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

                <p>Customers</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              <a href="/admin/customers" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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
          <!-- ./col -->
        </div>
        <!-- /.row -->

        @elseif(auth()->user()->role =='user')


        <?php 

        $customer = auth()->user()->customer;
        $customer_id = $customer->id;;

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
              <a href="/customer/complete-bookings" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>


          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{number_format($activeBookingAmount)}}</h3>

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
                    <h3>{{number_format($activeBookingAmount)}}</h3>

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
    </section>
    <!-- /.content -->
@endsection
