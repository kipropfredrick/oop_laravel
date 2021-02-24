<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>LIPA MOS MOS</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<link href="{{asset('global_backoffice/css/icons/icomoon/styles.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('backoffice/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('backoffice/css/bootstrap_limitless.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('backoffice/css/layout.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('backoffice/css/layout.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('backoffice/css/components.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('backoffice/css/colors.min.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/af-2.3.4/b-1.6.1/b-flash-1.6.1/cr-1.5.2/fc-3.3.0/fh-3.1.6/kt-2.5.1/r-2.2.3/rg-1.1.1/rr-1.2.6/sc-2.0.1/sp-1.0.1/sl-1.3.1/datatables.min.css"/>
	<!-- /global stylesheets -->

	<style>
            .dataTables_paginate .paginate_button.disabled, .dataTables_paginate .paginate_button.disabled:focus, .dataTables_paginate .paginate_button.disabled:hover {
                cursor: default;
                background-color: #FFF !important;
                color: white !important;
            }

    </style>

	<!-- Core JS files -->
	<script src="{{ asset('global_backoffice/js/main/jquery.min.js')}}"></script>
	<script src="{{ asset('global_backoffice/js/main/bootstrap.bundle.min.js')}}"></script>
	<script src="{{ asset('global_backoffice/js/plugins/loaders/blockui.min.js')}}"></script>
	<script src="{{ asset('global_backoffice/js/plugins/tables/datatables/datatables.min.js')}}"></script>
	<script src="{{ asset('global_backoffice/js/demo_pages/datatables_basic.js')}}"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="{{ asset('global_backoffice/js/plugins/visualization/d3/d3.min.js')}}"></script>
	<script src="{{ asset('global_backoffice/js/plugins/visualization/d3/d3_tooltip.js')}}"></script>
	<script src="{{ asset('global_backoffice/js/plugins/forms/styling/switchery.min.js')}}"></script>
	<script src="{{ asset('global_backoffice/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
	<script src="{{ asset('global_backoffice/js/plugins/ui/moment/moment.min.js')}}"></script>
	<script src="{{ asset('global_backoffice/js/plugins/pickers/daterangepicker.js')}}"></script>

	<script src="{{ asset('backoffice/js/app.js')}}"></script>
	<script src="{{ asset('global_backoffice/js/demo_pages/backoffice.js')}}"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css"></script>

	<!-- /theme JS files -->

	<style>
        .services-icon {
            min-height: 30px;
            color: #00a651 !important;
        }
        .tooltip {
            position: relative;
            display: inline-block;
        }
        .tooltip .tooltiptext { 
            visibility: hidden;
            width: 120px;
            background-color: black;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 0;

            /* Position the tooltip */
            position: absolute;
            z-index: 1;
            }

            .tooltip:hover .tooltiptext {
            visibility: visible;
            }
    </style>

	@yield('extra-js')


		<script>
			$(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip();   
			});
		</script>
		


</head>

<body>



	<!-- Main navbar -->

	<div class="navbar navbar-expand-md navbar-dark">
		<div class="navbar-brand">
			<a  href="" class="d-inline-block">
				<h5 style="color:#FFF;font-weight:bold">
				COMBINE 
				@if(auth()->user()->role =='admin')
				ADMIN
				@elseif(auth()->user()->role =='vendor')
				VENDOR
				(VD{{auth()->user()->id}})
				@endif
				</h5>
			</a>
		</div>

		<div class="d-md-none">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>
			<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				<i class="icon-paragraph-justify3"></i>
			</button>
		</div>

		<div class="collapse navbar-collapse" id="navbar-mobile">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>
			</ul>

			<span class="ml-md-3 mr-md-auto"></span>

			<ul class="navbar-nav">

				<li class="nav-item dropdown">
				</li>

				<li class="nav-item dropdown dropdown-user">
					
						<a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
							<i style="color:#FFF;margin-right:10px" class="fa fa-user-circle fa-2x"></i>
							{{auth()->user()->name}}
						</a>
						
					<div class="dropdown-menu dropdown-menu-right">
						@if(auth()->user()->role == "admin")
							<a href="{{ route('admin.profile') }}" class="dropdown-item"><i class="icon-user-plus"></i>Profile</a>
						@elseif(auth()->user()->role == "vendor")
							<a href="/vendor/profile" class="dropdown-item"><i class="icon-user-plus"></i>Profile</a>
						@endif
						<a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="icon-switch2"></i>
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
					</div>
				</li>
			</ul>
		</div>
	</div>

	<!-- /main navbar -->


	<!-- Page content -->
	<div class="page-content">

		<!-- Main sidebar -->
		<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

			<!-- Sidebar mobile toggler -->
			<div class="sidebar-mobile-toggler text-center">
				<a href="#" class="sidebar-mobile-main-toggle">
					<i class="icon-arrow-left8"></i>
				</a>
				Navigation
				<a href="#" class="sidebar-mobile-expand">
					<i class="icon-screen-full"></i>
					<i class="icon-screen-normal"></i>
				</a>
			</div>
			<!-- /sidebar mobile toggler -->


			<!-- Sidebar content -->
<div class="sidebar-content">

<!-- User menu -->
<div class="sidebar-user">
	<div class="card-body">
		<div class="media">
		</div>
	</div>
</div>
<!-- /user menu -->


<!-- Main navigation -->
<div class="card card-sidebar-mobile">
	<ul class="nav nav-sidebar" data-nav-type="accordion">

		<!-- Main -->
		<!-- <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Main</div> <i class="icon-menu" title="Main"></i></li> -->
		@if(auth()->user()->role =='admin')

		<li  class="nav-item">
			<a href="/admin/dashboard" class="nav-link {{ (request()->is('admin/dashboard')) ? 'active' : '' }}">
				<i class="icon-home4"></i>
				<span>
					Dashboard
				</span>
			</a>
		</li>
		<li class="nav-item nav-item-submenu">
			<a href="#" class="nav-link"><i class="fa fa-shopping-basket"></i> <span>Products</span></a>

			<ul class="nav nav-group-sub" data-submenu-title="Products">
				<li class="nav-item {{ (request()->is('admin/products')) ? 'active' : '' }}"><a href="{{route('admin.products')}}" class="nav-link active"><span>Product List</span></a></li>
				<li class="nav-item {{ (request()->is('admin/add-product')) ? 'active' : '' }}"><a href="/admin/add-product" class="nav-link"><span>Add Product</span></a></li>
				<li class="nav-item"><a href="/admin/product-categories" class="nav-link"><span>Categories</span></a></li>
			</ul>
		</li>


		<li class="nav-item nav-item-submenu">
			<a href="#" class="nav-link"><i class="fa fa-list"></i> <span>Counties</span></a>

			<ul class="nav nav-group-sub" data-submenu-title="Productss">
				<li class="nav-item"><a href="/admin/counties/view-all" class="nav-link active"><span>View All</span></a></li>
				<li class="nav-item"><a href="/admin/counties/locations/all" class="nav-link active"><span>Pickup Locations</span></a></li>
			</ul>
		</li>


		<li class="nav-item nav-item-submenu">
			<a href="#" class="nav-link"><i class="fa fa-list"></i> <span>Nairobi Zones</span></a>

			<ul class="nav nav-group-sub" data-submenu-title="Productss">
				<li class="nav-item"><a href="/admin/zones/view-all" class="nav-link active"><span>View All</span></a></li>
				<li class="nav-item"><a href="/admin/zones/dropoffs/all" class="nav-link active"><span>Dropoff Locations</span></a></li>
			</ul>
		</li>


		<li class="nav-item nav-item-submenu">
			<a href="#" class="nav-link"><i class="fa fa-shopping-basket"></i> <span>Vendor Products</span></a>

			<ul class="nav nav-group-sub" data-submenu-title="Productss">
				<li class="nav-item"><a href="/admin/vendor/approved-products" class="nav-link active"><span>Approved Products</span></a></li>
				<li class="nav-item"><a href="/admin/vendor/pending-products" class="nav-link active"><span>Pending Products</span></a></li>
				<li class="nav-item"><a href="/admin/vendor/rejected-products" class="nav-link active"><span>Rejected Products</span></a></li>
			</ul>
		</li>

		<li class="nav-item nav-item-submenu">
			<a href="#" class="nav-link"><i class="fa fa-bookmark"></i> <span>Bookings</span></a>

			<ul class="nav nav-group-sub" data-submenu-title="Bookings">
				<li class="nav-item"><a href="/admin/active_bookings" class="nav-link active"><span>Active Bookings</span></a></li>
				<li class="nav-item"><a href="/admin/complete_bookings" class="nav-link"><span>Complete Bookings</span></a></li>
				<li class="nav-item"><a href="/admin/overdue_bookings" class="nav-link"><span>Overdue Bookings</span></a></li>
				<li class="nav-item"><a href="/admin/revoked_bookings" class="nav-link"><span>Revoked Bookings</span></a></li>
				<li class="nav-item"><a href="/admin/delivered_bookings" class="nav-link"><span>Delivered Bookings</span></a></li>
				<li class="nav-item"><a href="/admin/confirmed_deliveries" class="nav-link"><span>Confirmed Deliveries</span></a></li>
				<li class="nav-item"><a href="/admin/unserviced_bookings" class="nav-link"><span>Unserviced Bookings</span></a></li>
				<li class="nav-item"><a href="/admin/pending_bookings" class="nav-link"><span>Pending Bookings</span></a></li>
				<li class="nav-item"><a href="/admin/transfer-order" class="nav-link"><span>Transfer Order</span></a></li>
			</ul>
		</li>

		<li class="nav-item">
			<a href="/admin/payments" class="nav-link {{ (request()->is('admin/payments')) ? 'active' : '' }}"><i class="fa fa-credit-card"></i> <span>Payments</span></a>
		</li>


		<li class="nav-item">
			<a href="/admin/payment-callbacks" class="nav-link {{ (request()->is('admin/payments-callbacks')) ? 'active' : '' }}"><i class="fa fa-credit-card"></i> <span>Full Payment Info</span></a>
		</li>

		
		<li class="nav-item">
			<a href="/admin/commissions" class="nav-link {{ (request()->is('admin/commissions')) ? 'active' : '' }}"><i class="fa fa-gift"></i> <span>Commissions</span></a>
		</li>

		<li class="nav-item">
			<a href="/admin/customers" class="nav-link {{ (request()->is('admin/customers')) ? 'active' : '' }}"><i class="fa fa-users"></i> <span>Customers</span></a>
		</li>

		<li class="nav-item">
			<a href="/admin/vendors" class="nav-link {{ (request()->is('admin/vendors')) ? 'active' : '' }}"><i class="fa fa-users"></i> <span>Vendors</span></a>
		</li>

		<li class="nav-item">
			<a href="/admin/cities" class="nav-link {{ (request()->is('admin/cities')) ? 'active' : '' }}"><i class="fa fa-building"></i> <span>Cities</span></a>
		</li>

		<li class="nav-item nav-item-submenu">
			<a href="#" class="nav-link"><i class="fa fa-image"></i> <span>Banners</span></a>

			<ul class="nav nav-group-sub" data-submenu-title="">
				<li class="nav-item"><a href="/admin/banners" class="nav-link active"><span>Banners List</span></a></li>
				<li class="nav-item"><a href="/admin/add_banner" class="nav-link"><span>Add Banner</span></a></li>
			</ul>
		</li>
		
		@elseif(auth()->user()->role =='vendor')

		<li class="nav-item nav-item-submenu">
			<a href="#" class="nav-link"><i class="fa fa-shopping-basket"></i> <span>Products</span></a>

			<ul class="nav nav-group-sub" data-submenu-title="Products">
                <li class="nav-item"><a href="/vendor/approved-products" class="nav-link active"><span>Approved Products</span></a></li>
				<li class="nav-item"><a href="/vendor/pending-products" class="nav-link active"><span>Pending Products</span></a></li>
				<li class="nav-item"><a href="/vendor/rejected-products" class="nav-link active"><span>Rejected Products</span></a></li>
				<li class="nav-item"><a href="/vendor/add-product" class="nav-link active"><span>Add Product</span></a></li>
			</ul>
		</li>
		<li class="nav-item nav-item-submenu">
			<a href="#" class="nav-link"><i class="fa fa-bookmark"></i> <span>Bookings</span></a>

			<ul class="nav nav-group-sub" data-submenu-title="Bookings">
				<li class="nav-item"><a href="/vendor/active-bookings" class="nav-link active"><span>Active Bookings</span></a></li>
				<li class="nav-item"><a href="/vendor/complete-bookings" class="nav-link"><span>Complete Bookings</span></a></li>
				<li class="nav-item"><a href="/vendor/overdue-bookings" class="nav-link"><span>Overdue Bookings</span></a></li>
				<li class="nav-item"><a href="/vendor/revoked-bookings" class="nav-link"><span>Revoked Bookings</span></a></li>
				<li class="nav-item"><a href="/vendor/delivered-bookings" class="nav-link"><span>Delivered Bookings</span></a></li>
				<li class="nav-item"><a href="/vendor/pending-bookings" class="nav-link"><span>Pending Bookings</span></a></li>
				<li class="nav-item"><a href="/vendor/transfer-order" class="nav-link"><span>Transfer Order</span></a></li>
				<!-- <li class="nav-item"><a href="/vendor/confirmed-deliveries" class="nav-link"><span>Confirmed Deliveries</span></a></li> -->
				<li class="nav-item"><a href="/vendor/unserviced-bookings" class="nav-link"><span>Unserviced Bookings</span></a></li>
			</ul>
		</li>

		@elseif(auth()->user()->role =='user')

		<li class="nav-item nav-item-submenu">
			<a href="#" class="nav-link"><i class="fa fa-list-ol"></i> <span>My Bookings</span></a>

			<ul class="nav nav-group-sub" data-submenu-title="">
				<li class="nav-item"><a href="/customer/complete-bookings" class="nav-link active"><span>Complete Bookings</span></a></li>
				<li class="nav-item"><a href="/customer/active-bookings" class="nav-link"><span>Active Bookings</span></a></li>
				<li class="nav-item"><a href="/customer/revoked-bookings" class="nav-link"><span>Revoked Bookings</span></a></li>
			</ul>
		</li>


		@endif

	</ul>
</div>
<!-- /main navigation -->

</div>
<!-- /sidebar content -->
			
		</div>
		<!-- /main sidebar -->


		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header page-header-light">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Dashboard</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>

					<div class="header-elements d-none">
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content">
				
				@yield('content')

			</div>
			<!-- /content area -->


			<!-- Footer -->
			<div class="navbar navbar-expand-lg navbar-light">
				<div class="text-center d-lg-none w-100">
					<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
						<i class="icon-unfold mr-2"></i>
						Footer
					</button>
				</div>

				<div class="navbar-collapse collapse" id="navbar-footer">
					<span class="navbar-text">
						&copy; {{ now()->year }}. Combine. All Rights Reserved.
					</span>
				</div>
			</div>
			<!-- /footer -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/af-2.3.4/b-1.6.1/b-flash-1.6.1/cr-1.5.2/fc-3.3.0/fh-3.1.6/kt-2.5.1/r-2.2.3/rg-1.1.1/rr-1.2.6/sc-2.0.1/sp-1.0.1/sl-1.3.1/datatables.min.js"></script>

	<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
	

	<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
        <script>
                CKEDITOR.replace( 'highlights' );
				CKEDITOR.replace( 'description' );
        </script>
	
	<script>
		$(document).ready(function() {
			$('#myTable').DataTable();
			$('#myTable2').DataTable();
			$('#myTable3').DataTable();
		});


		$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'print',
                text: 'Print all',
                exportOptions: {
                    modifier: {
                        selected: null
                    }
                }
            },
            {
                extend: 'print',
                text: 'Print selected'
            }
        ],
        select: true
    } );
} );

   </script>

	

</body>
</html>
