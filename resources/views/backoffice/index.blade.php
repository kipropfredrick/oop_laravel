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

@endif

@endsection
