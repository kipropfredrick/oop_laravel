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
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{number_format($activeBookingsCount)}}</h3>
                <h5>KES {{number_format($activeBookingAmount)}}</h5>

                <p>Active Bookings</p>
              </div>
              <div class="icon">
                <i class="ion ion-bookmark"></i>
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
                <i class="ion ion-spinner"></i>
              </div>
              <a href="/admin/complete_bookings" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

           <!-- ./col -->
           <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{number_format($pendingBookingAmount)}}</h3>

                <h5>KES {{number_format($pendingBookingAmount)}}</h5>

                <p>Pending Bookings</p>
              </div>
              <div class="icon">
                <i class="ion ion-spinner"></i>
              </div>
              <a href="/admin/pending_bookings" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{number_format($vendorsCount)}}</h3>

                <p>Vendors</p>
              </div>
              <div class="icon">
                <i class="fas fa-spinner"></i>
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
                <i class="ion ion-person-add"></i>
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

<div class="card padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Quick Actions</strong></h6>
        </div>

        <div class="card-body py-0">
            <div class="row">
                <div class="col-sm-4">
                    <div class="d-flex align-items-center justify-content-center mb-2 quick-links">
                        <a href="/customer/complete-bookings" class="btn bg-transparent border-teal text-teal rounded-round border-2 btn-icon mr-3">
                            <i class="fa fa-bookmark"></i>
                        </a>
                        <div>
                            <div class="font-weight-semibold"><a href="/customer/complete-bookings">Complete Bookings</a></div>
                            <!-- <span class="text-muted">400</span> -->
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="col-sm-4">
                    <div class="d-flex align-items-center justify-content-center mb-2 quick-links">
                        <a href="/customer/active-bookings" class="btn bg-transparent border-warning-400 text-warning-400 rounded-round border-2 btn-icon mr-3">
                            <i class="fa fa-bookmark"></i>
                        </a>
                        <div>
                            <div class="font-weight-semibold"> <a href="/customer/active-bookings">Active Bookings</a></div>
                            <!-- <span class="text-muted">200</span> -->
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="col-sm-4">
                    <div class="d-flex align-items-center justify-content-center mb-2 quick-links">
                        <a href="/customer/active-bookings" class="btn bg-transparent border-warning-400 text-warning-400 rounded-round border-2 btn-icon mr-3">
                            <i class="fa fa-spinner"></i>
                        </a>
                        <div>
                            <div class="font-weight-semibold"> <a href="/customer/pending-bookings">Pending Bookings</a></div>
                            <!-- <span class="text-muted">200</span> -->
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="col-sm-4">
                    <div class="d-flex align-items-center justify-content-center mb-2 quick-links">
                        <a href="/customer/complete-bookings" class="btn bg-transparent border-teal text-teal rounded-round border-2 btn-icon mr-3">
                            <i class="fa fa-bookmark"></i>
                        </a>
                        <div>
                            <div class="font-weight-semibold"><a href="/customer/revoked-bookings">Revoked Bookings</a></div>
                            <!-- <span class="text-muted">400</span> -->
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    </div>
        @elseif(auth()->user() == "vendor")
        @endif
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
