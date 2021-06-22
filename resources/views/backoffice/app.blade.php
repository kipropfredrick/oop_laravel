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
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-shopping-basket"></i>
              <p>
                Products
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/product-categories" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Categories</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/product-brands" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Brands</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/vendor/approved-products" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Approved Products</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/vendor/pending-products" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pending Products</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/vendor/rejected-products" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rejected Products</p>
                </a>
              </li>
            </ul>
          </li>
          </li>


          <li class="nav-item">
              <a href="/admin/counties/view-all" class="nav-link">
                <i class="nav-icon fas fa-th-large"></i>
                <p>
                  Counties
                </p>
              </a>
            </li>

		  
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

		  <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-bookmark"></i>
              <p>
                Bookings
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="/admin/pending_bookings" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pending Bookings</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/active_bookings" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Active Bookings</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/complete_bookings" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Complete Bookings</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/overdue_bookings" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Overdue Bookings</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/revoked_bookings" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Revoked Bookings</p>
                </a>
              </li>
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
              <li class="nav-item">
                <a href="/admin/unserviced_bookings" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Unserviced Bookings</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/transfer-order" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Transfer Order</p>
                </a>
              </li>
            </ul>
          </li>
          </li>

		  <li class="nav-item">
			<a href="/admin/payments" class="nav-link {{ (request()->is('admin/payments')) ? 'active' : '' }}"><i class="fa fa-credit-card"></i> <p>&nbsp;Payments</p></a>
		</li>


		<li class="nav-item">
			<a href="/admin/payment-callbacks" class="nav-link {{ (request()->is('admin/payments-callbacks')) ? 'active' : '' }}"><i class="fa fa-credit-card"></i> <p>&nbsp;Full Payment Info</p></a>
		</li>

		
		<li class="nav-item">
			<a href="/admin/commissions" class="nav-link {{ (request()->is('admin/commissions')) ? 'active' : '' }}"><i class="fa fa-gift"></i> <p>&nbsp;Commissions</p></a>
		</li>
<!-- 
		<li class="nav-item">
			<a href="/admin/customers" class="nav-link {{ (request()->is('admin/customers')) ? 'active' : '' }}"><i class="fa fa-users"></i> <p>&nbsp;Customers</p></a>
		</li>
 -->
		<!-- <li class="nav-item">
			<a href="/admin/vendors" class="nav-link {{ (request()->is('admin/vendors')) ? 'active' : '' }}"><i class="fa fa-users"></i> <p>&nbsp;Vendors</p></a>
		</li> -->


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
          <li class="nav-item {{ (request()->is('admin/customers/active')) ? 'active' : '' }}">
          <a href="{{route('admin.customers',['type'=>'inactive'])}}" class="nav-link "><i class="far fa-circle nav-icon"></i> <p>&nbsp;Inactive customers</p></a>
        </li>
 
      </ul>
    </li>


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

		<li class="nav-item">
			<a href="/admin/cities" class="nav-link {{ (request()->is('admin/cities')) ? 'active' : '' }}"><i class="fa fa-building"></i> <p>&nbsp;Cities</p></a>
		</li>
		  
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
          </li>

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
                  <p>Send Notifications</p>
                </a>
              </li>
         
            </ul>
          </li>
          </li>

         @elseif(auth()->user()->role =='vendor')
		 <li class="nav-item">
			<a href="#" class="nav-link"><i class="fa fa-shopping-basket"></i> <span>Products</span><i class="right fas fa-angle-left"></i></a>

			<ul class="nav nav-treeview" data-submenu-title="Products">
        <li class="nav-item"><a href="/vendor/approved-products" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Approved Products</span></a></li>
				<li class="nav-item"><a href="/vendor/pending-products" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Pending Products</span></a></li>
				<li class="nav-item"><a href="/vendor/rejected-products" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Rejected Products</span></a></li>
				<li class="nav-item"><a href="/vendor/add-product" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Add Product</span></a></li>
			</ul>
		</li>
		<li class="nav-item">
			<a href="#" class="nav-link"><i class="fa fa-bookmark"></i> <span>Bookings</span><i class="right fas fa-angle-left"></i></a>

			<ul class="nav nav-treeview" data-submenu-title="Bookings">
        <li class="nav-item"><a href="/vendor/pending-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Pending Bookings</span></a></li>
				<li class="nav-item"><a href="/vendor/active-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Active Bookings</span></a></li>
				<li class="nav-item"><a href="/vendor/complete-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Complete Bookings</span></a></li>
				<li class="nav-item"><a href="/vendor/overdue-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Overdue Bookings</span></a></li>
				<li class="nav-item"><a href="/vendor/revoked-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Revoked Bookings</span></a></li>
				<li class="nav-item"><a href="/vendor/delivered-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Delivered Bookings</span></a></li>
				<li class="nav-item"><a href="/vendor/transfer-order" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Transfer Order</span></a></li>
				<!-- <li class="nav-item"><a href="/vendor/confirmed-deliveries" class="nav-link"><span>Confirmed Deliveries</span></a></li> -->
				<li class="nav-item"><a href="/vendor/unserviced-bookings" class="nav-link"><i class="far fa-circle nav-icon"></i> <span>Unserviced Bookings</span></a></li>
			</ul>
		</li>
		@elseif(auth()->user()->role =='user')
			<li class="nav-item nav-item-submenu">
				<a href="#" class="nav-link"><i class="fa fa-bookmark"></i> <span>My Bookings</span></a>

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
<!-- Summernote -->
<script src="{{asset('backoffice/summernote/summernote-bs4.min.js')}}"></script>

<!-- Select2 -->
<script src="{{asset('backoffice/plugins/select2/js/select2.full.min.js')}}"></script>

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
</script>

</body>
</html>
