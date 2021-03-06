<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LIPA MOS MOS | Dashboard</title>
  <link rel="icon" href="{{asset('assets/img/logo/favicon.png')}}" type="image/png"/>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('backoffice/plugins/fontawesome-free/css/all.min.css')}}">

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css')}}">
  <!-- Tempusdominus Bootstrap 4 -->

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('backoffice/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('backoffice/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('vendor/plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('backoffice/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">

  <link rel="stylesheet" href="{{asset('backoffice/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('backoffice/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{asset('backoffice/plugins/jqvmap/jqvmap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('backoffice/dist/css/adminlte.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('backoffice/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('backoffice/plugins/daterangepicker/daterangepicker.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{asset('backoffice/plugins/summernote/summernote-bs4.min.css')}}">

  <!-- Select2 -->
<link rel="stylesheet" href="{{asset('backoffice/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('backoffice/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

 <!-- Bootstrap4 Duallistbox -->
 <link rel="stylesheet" href="{{asset('backoffice/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')}}">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="{{asset('backoffice/plugins/bs-stepper/css/bs-stepper.min.css')}}">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="{{asset('backoffice/plugins/dropzone/min/dropzone.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('backoffice/dist/css/adminlte.min.css')}}">

  <style>

  .padding{
    padding:10px;
  }
  #myTable{
   padding:10px !important;
  }

  .ellipsis{
    display: inline-block;
    width: 200px;
    white-space: nowrap;
    overflow: hidden !important;
    text-overflow: ellipsis;
}

  </style>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->

    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->

      <!-- Notifications Dropdown Menu -->
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
	  <div class="dropdown">
		<a style="color:#000;margin-top:10px" class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			{{auth()->user()->name}}
		</a>
		<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a href="/edit-profile" class="dropdown-item" ><i class="fa fa-user-edit"></i> Edit Profile</a>
		<a class="dropdown-item" href="{{ route('logout') }}"
			onclick="event.preventDefault();
							document.getElementById('logout-form').submit();"><i class="fa fa-sign-out-alt"></i>
			{{ __('Logout') }}
		</a>

		<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
			@csrf
		</form>
		</div>
		</div>
      </li>
      +
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/dashboard/home" class="brand-link">
      <!-- <img src="{{asset('backoffice/dist/img/AdminLTELogo.png')}}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
      <span class="brand-text font-weight-light">LIPA MOS MOS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <i style="color:#FFF" class="fa fa-user-circle fa-2x"></i>
        </div>
        <div class="info">
          <a href="#" class="d-block">{{auth()->user()->name}}</a>
        </div>
      </div> -->

      <!-- SidebarSearch Form -->
      <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <a href="/dashboard/home" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

		  @if(auth()->user()->role =='admin')
      @if(Sentinel::hasAccess('products'))
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-shopping-basket"></i>
              <p>
                Products
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                @if(Sentinel::hasAccess('products.categories'))
              <li class="nav-item">
                <a href="/admin/product-categories" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Categories</p>
                </a>
              </li>
              @endif
                @if(Sentinel::hasAccess('products.brands'))
              <li class="nav-item">
                <a href="/admin/product-brands" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Brands</p>
                </a>
              </li>
              @endif
              @if(Sentinel::hasAccess('products.approved'))
              <li class="nav-item">
                <a href="/admin/vendor/approved-products" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Approved Products</p>
                </a>
              </li>
              @endif
              @if(Sentinel::hasAccess('products.pending'))
              <li class="nav-item">
                <a href="/admin/vendor/pending-products" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pending Products</p>
                </a>
              </li>
              @endif
              @if(Sentinel::hasAccess('products.rejected'))
              <li class="nav-item">
                <a href="/admin/vendor/rejected-products" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rejected Products</p>
                </a>
              </li>
              @endif
            </ul>
          </li>
          </li>
          @endif

  @if(Sentinel::hasAccess('counties'))
          <li class="nav-item">
              <a href="/admin/counties/view-all" class="nav-link">
                <i class="nav-icon fas fa-th-large"></i>
                <p>
                  Counties
                </p>
              </a>
            </li>
            @endif


          </li>

		  <!-- <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Nairobi Zones
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/zones/view-all" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View All</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/zones/dropoffs/all" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dropoff Locations</p>
                </a>
              </li>
            </ul>
          </li>
          </li> -->
  @if(Sentinel::hasAccess('bookings'))
		  <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-bookmark"></i>
              <p>
                Bookings
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
              @if(Sentinel::hasAccess('pending.bookings'))
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="/admin/pending_bookings" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pending Bookings</p>
                </a>
              </li>
              @endif
                @if(Sentinel::hasAccess('active.bookings'))
              <li class="nav-item">
                <a href="/admin/active_bookings" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Active Bookings</p>
                </a>
              </li>
              @endif
                @if(Sentinel::hasAccess('complete.bookings'))
              <li class="nav-item">
                <a href="/admin/complete_bookings" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Complete Bookings</p>
                </a>
              </li>
              @endif
                @if(Sentinel::hasAccess('overdue.bookings'))
              <li class="nav-item">
                <a href="/admin/overdue_bookings" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Overdue Bookings</p>
                </a>
              </li>
              @endif
                @if(Sentinel::hasAccess('revoked.bookings'))
              <li class="nav-item">
                <a href="/admin/revoked_bookings" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Revoked Bookings</p>
                </a>
              </li>
              @endif
            <!--   <li class="nav-item">
                <a href="/admin/delivered_bookings" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Delivered Bookings</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/confirmed_deliveries" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Confirmed Deliveries</p>
                </a>
              </li> -->
                @if(Sentinel::hasAccess('unserviced.bookings'))
              <li class="nav-item">
                <a href="/admin/unserviced_bookings" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Unserviced Bookings</p>
                </a>
              </li>
              @endif
                @if(Sentinel::hasAccess('transfer.order'))
              <li class="nav-item">
                <a href="/admin/transfer-order" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Transfer Order</p>
                </a>
              </li>
              @endif
            </ul>
          </li>
          </li>
@endif
  @if(Sentinel::hasAccess('payments'))
		  <li class="nav-item">
			<a href="/admin/payments" class="nav-link {{ (request()->is('admin/payments')) ? 'active' : '' }}"><i class="fa fa-credit-card"></i> <p>&nbsp;Payments</p></a>
		</li>
    @endif
 @if(Sentinel::hasAccess('aggregatepayments'))

              <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-bookmark"></i>
              <p>
             &nbsp Aggregate Payments
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('admin.agall')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.agbookings')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Bookings</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="{{route('admin.agairtime')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Airtime</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="{{route('admin.agutility')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Utilities</p>
                </a>
              </li>
            </ul>
          </li>
          @endif

@if(Sentinel::hasAccess('fullpayment.infor'))
		<li class="nav-item">
			<a href="/admin/payment-callbacks" class="nav-link {{ (request()->is('admin/payments-callbacks')) ? 'active' : '' }}"><i class="fa fa-credit-card"></i> <p>&nbsp;Full Payment Info</p></a>
		</li>
@endif
@if(Sentinel::hasAccess('payments.monitoring'))

          <li class="nav-item">
      <a href="{{route('admin.monitorPayments')}}" class="nav-link"><i class="fa fa-building"></i> <p>&nbsp;Payments Monitoring</p></a>
    </li>

@endif
@if(Sentinel::hasAccess('commission'))


    <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-bookmark"></i>
              <p>
             Earnings
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            
                <li class="nav-item">
      <a href="/admin/commissions" class="nav-link {{ (request()->is('admin/commissions')) ? 'active' : '' }}"><i class="fa fa-gift"></i> <p>&nbsp;Commissions</p></a>
    </li>
                <li class="nav-item">
      <a href="/admin/fixed-payout" class="nav-link {{ (request()->is('admin/fixed-payout')) ? 'active' : '' }}"><i class="fa fa-gift"></i> <p>&nbsp;Fixed Payouts</p></a>
    </li>
              
              
            </ul>
          </li>
    @endif


 @if(Sentinel::hasAccess('lmmpayments'))
              <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-bookmark"></i>
              <p>
             LMM Pay
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
               <li class="nav-item">
                <a href="/admin/bill-payment-callbacks" class="nav-link {{ (request()->is('admin/bill-payments-callbacks')) ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>&nbsp;Bill Full Payment Info</p>
                </a>
              <li class="nav-item">
                <a href="/admin/topups" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Wallet Top-up</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/purchases" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Airtime</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="/admin/utilities" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Utility</p>
                </a>
              </li>
              
            </ul>
          </li>
          @endif
<!--
		<li class="nav-item">
			<a href="/admin/customers" class="nav-link {{ (request()->is('admin/customers')) ? 'active' : '' }}"><i class="fa fa-users"></i> <p>&nbsp;Customers</p></a>
		</li>
 -->
		<!-- <li class="nav-item">
			<a href="/admin/vendors" class="nav-link {{ (request()->is('admin/vendors')) ? 'active' : '' }}"><i class="fa fa-users"></i> <p>&nbsp;Vendors</p></a>
		</li> -->

 @if(Sentinel::hasAccess('customers'))
    <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="fas fa-users"></i>
        <p>
          Customers
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">

         <li class="nav-item {{ (request()->is('admin/customers/active')) ? 'active' : '' }}">
          <a href="{{route('admin.customers',['type'=>'active'])}}" class="nav-link "><i class="far fa-circle nav-icon"></i> <p>&nbsp;Active Customers</p></a>
        </li>
         <li class="nav-item {{ (request()->is('admin/customers/complete')) ? 'active' : '' }}">
          <a href="{{route('admin.customers',['type'=>'complete'])}}" class="nav-link "><i class="far fa-circle nav-icon"></i> <p>&nbsp;CB Customers</p></a>
        </li>
         <li class="nav-item {{ (request()->is('admin/customers/active')) ? 'active' : '' }}">
          <a href="{{route('admin.customers',['type'=>'active-bookings'])}}" class="nav-link "><i class="far fa-circle nav-icon"></i> <p>&nbsp;AB Customers</p></a>
        </li>
         <li class="nav-item {{ (request()->is('admin/customers/active')) ? 'active' : '' }}">
          <a href="{{route('admin.customers',['type'=>'pending-bookings'])}}" class="nav-link "><i class="far fa-circle nav-icon"></i> <p>&nbsp;PB Customers</p></a>
        </li>
         <li class="nav-item {{ (request()->is('admin/customers/active')) ? 'active' : '' }}">
          <a href="{{route('admin.customers',['type'=>'revoked-bookings'])}}" class="nav-link "><i class="far fa-circle nav-icon"></i> <p>&nbsp;RB customers</p></a>
        </li>
          <li class="nav-item {{ (request()->is('admin/customers/overdue')) ? 'active' : '' }}">
          <a href="{{route('admin.customers',['type'=>'overdue'])}}" class="nav-link "><i class="far fa-circle nav-icon"></i> <p>&nbsp;OB Customers</p></a>
        </li>
         <li class="nav-item {{ (request()->is('admin/customers/unserviced')) ? 'active' : '' }}">
          <a href="{{route('admin.customers',['type'=>'unserviced'])}}" class="nav-link "><i class="far fa-circle nav-icon"></i> <p>&nbsp;UB Customers</p></a>
        </li>
          <li class="nav-item {{ (request()->is('admin/customers/active')) ? 'active' : '' }}">
          <a href="{{route('admin.customers',['type'=>'inactive'])}}" class="nav-link "><i class="far fa-circle nav-icon"></i> <p>&nbsp;Inactive customers</p></a>
        </li>


      </ul>
    </li>
    @endif

 @if(Sentinel::hasAccess('vendors'))
    <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="fas fa-users"></i>
        <p>
          Vendors
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="/admin/vendors" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Vendors List</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/admin/add-vendor" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Add Vendor</p>
          </a>
        </li>
      </ul>
    </li>
    </li>
    @endif
    @if(Sentinel::hasAccess('cities'))

		<li class="nav-item">
			<a href="/admin/cities" class="nav-link {{ (request()->is('admin/cities')) ? 'active' : '' }}"><i class="fa fa-building"></i> <p>&nbsp;Cities</p></a>
		</li>

@endif
 @if(Sentinel::hasAccess('banners'))
		<li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-bookmark"></i>
              <p>
                Banners
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/banners" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Banners List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/add_banner" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Banner</p>
                </a>
              </li>
            </ul>
          </li>
          @endif




          </li>
 @if(Sentinel::hasAccess('sms'))
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-envelope"></i>
              <p>
                SMS
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/sms-log" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>SMS Log</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/send-sms" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Send SMS</p>
                </a>
              </li>
            </ul>
          </li>

@endif

 @if(Sentinel::hasAccess('notifications'))
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-envelope"></i>
              <p>
                Notifications
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/notifications" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>General Notifications</p>
                </a>
              </li>

                <li class="nav-item">
                <a href="/admin/custom" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Custom Notifications</p>
                </a>
              </li>

            </ul>
        </li>
        @endif
  @if(Sentinel::hasAccess('cities'))
          <li class="nav-item">
      <a href="{{route('admin.promotions')}}" class="nav-link"><i class="fa fa-building"></i> <p>&nbsp;Promotions</p></a>
    </li>
    @endif
    @if(Sentinel::hasAccess('users'))

              <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-bookmark"></i>
              <p>
             Users
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
  @if(Sentinel::hasAccess('users.view'))
               <li class="nav-item">
                <a href="/admin/user/data" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>&nbsp;View Users</p>
                </a></li>
                @endif
                  @if(Sentinel::hasAccess('users.roles'))
              <li class="nav-item">
                <a href="/admin/user/role/data" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Roles</p>
                </a>
              </li>
              @endif
               @if(Sentinel::hasAccess('users.create'))
              <li class="nav-item">
                <a href="/admin/user/create" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Users</p>
                </a>
              </li>
              @endif
               @if(Sentinel::hasAccess('users'))
        <li class="nav-item">
                <a href="/admin/user/permission/data" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Permission</p>
                </a>
              </li>
              @endif
              
            </ul>
          </li>

          @endif

    
    


          </li>

         @elseif(auth()->user()->role =='vendor')

         <?php 

$vendor=\App\Vendor::whereUser_id(auth()->user()->id)->first();


         ?>
         @if($vendor->add_product==1)
 <li class="nav-item"><a href="/vendor/add-product" class="nav-link"><i class="fas fa-plus-square nav-icon"></i><span>Create New Booking</span></a></li>
 @endif
        @if($vendor->add_product==1)
          <li class="nav-item"><a href="/vendor/create-bookings" class="nav-link"><i class="fas fa-plus-square nav-icon"></i><span>Product Booking</span></a></li>
@endif
                 <li class="nav-item"><a href="/vendor/vendor-booking" class="nav-link"><i class="fas fa-plus-square nav-icon"></i><span>Direct Booking
</span></a></li>
		 <li class="nav-item">
			<a href="#" class="nav-link"><i class="fa fa-shopping-basket"></i><span>&nbsp   Products</span><i class="right fas fa-angle-left"></i></a>

			<ul class="nav nav-treeview" data-submenu-title="Products">
        <li class="nav-item"><a href="/vendor/approved-products" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Approved Products</span></a></li>
				<li class="nav-item"><a href="/vendor/pending-products" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Pending Products</span></a></li>
				<li class="nav-item"><a href="/vendor/rejected-products" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Inactive Products</span></a></li>
			<!-- 	<li class="nav-item"><a href="/vendor/add-product" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Add Product</span></a></li> -->
			</ul>
		</li>
		<li class="nav-item">
			<a href="#" class="nav-link"><i class="fa fa-bookmark"></i>  <span>&nbsp Bookings</span><i class="right fas fa-angle-left"></i></a>

			<ul class="nav nav-treeview" data-submenu-title="Bookings">
  <!--       <li class="nav-item"><a href="/vendor/create-bookings" class="nav-link"><i class="fas fa-plus-square nav-icon"></i> <span>Create Bookings</span></a></li> -->
        <li class="nav-item"><a href="/vendor/pending-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Pending Bookings</span></a></li>
				<li class="nav-item"><a href="/vendor/active-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Active Bookings</span></a></li>
				<li class="nav-item"><a href="/vendor/complete-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Complete Bookings</span></a></li>
				<li class="nav-item"><a href="/vendor/overdue-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Overdue Bookings</span></a></li>
				<li class="nav-item"><a href="/vendor/revoked-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Revoked Bookings</span></a></li>
			<!-- 	<li class="nav-item"><a href="/vendor/delivered-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Delivered Bookings</span></a></li> -->
			
				<!-- <li class="nav-item"><a href="/vendor/confirmed-deliveries" class="nav-link"><span>Confirmed Deliveries</span></a></li> -->
				<li class="nav-item"><a href="/vendor/unserviced-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Unserviced Bookings</span></a></li>

          <li class="nav-item"><a href="/vendor/transfer-order" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Transfer Order</span></a></li>
			</ul>
		</li>
  <li class="nav-item"><a href="/vendor/payments" class="nav-link"><i class="fas fa-wallet nav-icon"></i><span>Payments</span></a></li>
      <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="fas fa-store"></i>
        <p>
          &nbsp Branches
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="/vendor/branches" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>All Branches</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/vendor/add-branch" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Add Branch</p>
          </a>
        </li>
      </ul>
    </li>
    </li>
      <li class="nav-item"><a href="/vendor/key" class="nav-link"><i class="fas fa-cogs nav-icon"></i><span>Settings</span></a></li>
        @elseif(auth()->user()->role =='branch_vendor')


                 <li class="nav-item"><a href="/branch/branch-booking" class="nav-link"><i class="fas fa-plus-square nav-icon"></i> <span>Direct Booking
</span></a></li>

    <li class="nav-item">
      <a href="#" class="nav-link">&nbsp   <i class="fa fa-bookmark"></i>&nbsp &nbsp <span>Bookings</span><i class="right fas fa-angle-left"></i></a>

      <ul class="nav nav-treeview" data-submenu-title="Bookings">
  <!--       <li class="nav-item"><a href="/vendor/create-bookings" class="nav-link"><i class="fas fa-plus-square nav-icon"></i> <span>Create Bookings</span></a></li> -->
        <li class="nav-item"><a href="/branch/pending-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Pending Bookings</span></a></li>
        <li class="nav-item"><a href="/branch/active-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Active Bookings</span></a></li>
        <li class="nav-item"><a href="/branch/complete-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Complete Bookings</span></a></li>
        <li class="nav-item"><a href="/branch/overdue-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Overdue Bookings</span></a></li>
        <li class="nav-item"><a href="/branch/revoked-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Revoked Bookings</span></a></li>
      <!--  <li class="nav-item"><a href="/vendor/delivered-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Delivered Bookings</span></a></li> -->
      
        <!-- <li class="nav-item"><a href="/vendor/confirmed-deliveries" class="nav-link"><span>Confirmed Deliveries</span></a></li> -->
        <li class="nav-item"><a href="/branch/unserviced-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Unserviced Bookings</span></a></li>

          <li class="nav-item"><a href="/branch/transfer-order" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Transfer Order</span></a></li>
      </ul>
    </li>
  <li class="nav-item"><a href="/branch/payments" class="nav-link"><i class="fas fa-wallet nav-icon"></i> <span>Payments</span></a></li>
  @if(auth()->user()->branch_user->role=='admin')
  <li class="nav-item">
      <a href="#" class="nav-link">&nbsp  
        <i class="fas fa-store"></i>
        <p> &nbsp
        Users
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="/branch/branchusers" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>All Users</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/branch/adduser" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Add User</p>
          </a>
        </li>
      </ul>
    </li>
    @endif



		@elseif(auth()->user()->role =='user')
			<li class="nav-item nav-item-submenu">
				<a href="#" class="nav-link"><i class="fa fa-bookmark"></i> <span>My Bookings</span>   <i class="right fas fa-angle-left"></i></a>

				<ul class="nav nav-treeview" data-submenu-title="">
          <li class="nav-item"><a href="/customer/pending-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i><span>Pending Bookings</span></a></li>
          <li class="nav-item"><a href="/customer/active-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i><span>Active Bookings</span></a></li>
					<li class="nav-item"><a href="/customer/complete-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i><span>Complete Bookings</span></a></li>
					<li class="nav-item"><a href="/customer/revoked-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i><span>Revoked Bookings</span></a></li>
            <li class="nav-item"><a href="/customer/payments" class="nav-link"><i class="far fa-circle nav-icon"></i><span>Payments</span></a></li>
				</ul>
			</li>
		 @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div  class="content-wrapper">
    <!-- Main content -->
    <section style="margin-top:20px" class="content">
      <div   class="container-fluid">

	  @yield('content')

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy;Lipa Mos Mos</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.1.0-rc
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('backoffice/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('backoffice/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('backoffice/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('backoffice/plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('backoffice/plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('backoffice/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('backoffice/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('backoffice/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('backoffice/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('backoffice/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('backoffice/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('backoffice/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('backoffice/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('backoffice/dist/js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('backoffice/dist/js/demo.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('backoffice/dist/js/pages/dashboard.js')}}"></script>

<!-- DataTables  & Plugins -->
<script src="{{asset('backoffice/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('backoffice/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('backoffice/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('backoffice/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('backoffice/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('backoffice/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('backoffice/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('backoffice/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('backoffice/plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('backoffice/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('backoffice/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('backoffice/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

<!-- <script type="text/javascript" src="{{asset('vendor/plugins/select2/js/select2.min.js')}}"></script> -->
<!-- Summernote -->
<script src="{{asset('backoffice/summernote/summernote-bs4.min.js')}}"></script>

<!-- Select2 -->
<!-- <script src="{{asset('backoffice/plugins/select2/js/select2.full.min.js')}}"></script> -->

@yield('extra-js')
<script>
	$(document).ready(function() {
	$('#myTable').DataTable();
	$('#myTable2').DataTable();
	$('#myTable3').DataTable();

	$('#highlights').summernote()
	$('#description').summernote()

   //Initialize Select2 Elements
   $('.select2').select2()

  //Initialize Select2 Elements
  $('.select2bs4').select2({
    theme: 'bootstrap4'
  })


	function filter(){

	var x = document.getElementById("categories");
	var val = x.value;

	console.log(x.value);

	var subs = document.getElementsByClassName('subcategories');
	var subsNames = document.getElementsByClassName('subcategoriesnames');
	var subsIds = document.getElementsByClassName('subcategoriesid');
	var _arrayId = [];
	var _arrayName = [];
	var _arraySubsId = [];

	for(i =  0; i < subs.length; i++){
		if(subs[i].innerHTML == x.value){
			_arrayId.push(subs[i].innerHTML);
			_arrayName.push(subsNames[i].innerHTML);
			_arraySubsId.push(subsIds[i].innerHTML);
		}

	}

	var y = document.getElementById("subs");
	y.innerHTML = "";
	for(i = 0; i < _arrayId.length; i++){
		var node = document.createElement("option");
		// node.innerHTML = _array[i];
		node.setAttribute('value', _arraySubsId[i]);
		node.innerHTML = _arrayName[i];
		y.appendChild(node);
	}

	console.log(_arrayId);


	}

});

function numberFormat(num) {
   if(num==null){
    num=0;
  }
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

</script>

</body>
</html>
