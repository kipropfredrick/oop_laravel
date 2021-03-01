@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->

@if(auth()->user()->role =='influencer')

<?php 
    $influencer = \App\Influencer::with('user','commission_totals')->where('user_id','=',auth()->user()->id)->first();
 ?>

<div class="card-body py-0 padding">
            <div class="row">
                <div class="col-sm-4">
                    <div class="d-flex align-items-center justify-content-center mb-2 quick-links">
                        
                        <div>
                            <div class="font-weight-semibold">Total Commission : <span><small>KES {{number_format($influencer->commission_totals->total_commission)}}</small></span></div>
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="col-sm-4">
                    <div class="d-flex align-items-center justify-content-center mb-2 quick-links">
						<div>
                            <div class="font-weight-semibold">Commission Paid : <span><small>KES {{number_format($influencer->commission_totals->commission_paid)}}</small></span></div>
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="col-sm-4">
                    <div class="d-flex align-items-center justify-content-center mb-2 quick-links">
						<div>
                            <div class="font-weight-semibold">Pending Payment : <span><small>KES {{number_format($influencer->commission_totals->pending_payment)}}</small></span></div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    </div>
    </div>

@endif

@if(auth()->user()->role =='admin')
<div class="card padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Quick Actions</strong></h6>
        </div>

        <div class="card-body py-0">
            <div class="row">
                <div class="col-sm-4">
                    <div class="d-flex align-items-center justify-content-center mb-2 quick-links">
                        <a href="/admin/active_bookings" class="btn bg-transparent border-teal text-teal rounded-round border-2 btn-icon mr-3">
                            <i class="fa fa-shopping-cart"></i>
                        </a>
                        <div>
                            <div class="font-weight-semibold">Manage Sales</div>
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="col-sm-4">
                    <div class="d-flex align-items-center justify-content-center mb-2 quick-links">
                        <a href="/admin/products" class="btn bg-transparent border-warning-400 text-warning-400 rounded-round border-2 btn-icon mr-3">
                            <i class="fa fa-shopping-basket"></i>
                        </a>
                        <div>
                            <div class="font-weight-semibold">Manage Products</div>
                            <!-- <span class="text-muted">200</span> -->
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="col-sm-4">
                    <div class="d-flex align-items-center justify-content-center mb-2 quick-links">
                        <a href="/admin/agents" class="btn bg-transparent border-indigo-400 text-indigo-400 rounded-round border-2 btn-icon mr-3">
                            <i class="fa fa-users"></i>
                        </a>
                        <div>
                            <div class="font-weight-semibold">Manage Agents</div>
                            <!-- <span class="text-muted">2</span> -->
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    </div>
<!-- /traffic sources -->

<div class="card padding">
    <div class="row">
        <div class="col-md-8">
           <h6 class="headings"><strong> Bookings</strong></h6>
           <hr style="margin-left:10px">
           <h5 class="money"><strong>KES {{number_format($totalBookingAmount)}}</strong></h5>
           <div style="margin-left:10px" class="row">
               <div style="font-size:10px;font-weight:400;color:#A9A9A9" class="col">
                  ACTIVE BOOKINGS
               </div>
               <div style="font-size:10px;font-weight:400;color:#A9A9A9" class="col">
                 OVERDUE BOOKINGS
               </div>
               <div style="font-size:10px;font-weight:400;color:#A9A9A9" class="col">
                 SUCCESSFULL BOOKINGS
               </div>
               <div style="font-size:10px;font-weight:400;color:#A9A9A9" class="col">
                 PENDING BOOKINGS
               </div>
           </div>

           <div style="margin-left:10px" class="row">
               <div class="col">
                 <h5><strong>KES {{number_format($activeBookingAmount)}}</strong></h5>
               </div>
               <div class="col">
               <h5><strong>KES {{number_format($overdueBookingAmount)}}</strong></h5>
               </div>
               <div class="col">
               <h5><strong>KES {{number_format($completeBookingAmount)}}</strong></h5>
               </div>
               <div class="col">
               <h5><strong>KES {{number_format($pendingBookingAmount)}}</strong></h5>
               </div>
           </div>

        </div>
        <div class="col-md-4">
         <h6 class="headings"><strong>Customers</strong></h6>
         <hr>
           <h5 class="money"><strong>{{number_format($customersCount)}}</strong></h5>
           <a  href="/admin/active_bookings" class="viewmore">View Bookings <span style="font-family: Verdana,sans-serif;">+</span></a>
        </div>
    </div>
</div>
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
                        <a href="/customer/complete-bookings" class="btn bg-transparent border-teal text-teal rounded-round border-2 btn-icon mr-3">
                            <i class="fa fa-bookmark"></i>
                        </a>
                        <div>
                            <div class="font-weight-semibold"><a href="/customer/revoked-bookings">RevokedBookings</a></div>
                            <!-- <span class="text-muted">400</span> -->
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    </div>

@endif

@endsection
