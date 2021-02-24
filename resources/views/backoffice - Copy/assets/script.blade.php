<script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=Intl.~locale.en"></script>
<script src='https://cdn.polyfill.io/v2/polyfill.min.js'></script>
<!-- jQuery -->
<script src="{{ asset('vendor/plugins/jquery/jquery.min.js') }}"></script>
<!-- jquery-validation -->
<script src="{{ asset('vendor/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('vendor/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('vendor/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- DataTables -->
<script type="text/javascript" src="{{ asset('vendor/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/plugins/datatables/js/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/plugins/datatables/js/buttons.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/plugins/datatables/js/dataTables.fixedHeader.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/plugins/datatables/js/dataTables.select.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('vendor/plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('vendor/plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('vendor/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('vendor/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('vendor/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('vendor/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('vendor/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('vendor/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Ckeditor -->
<script src="{{ asset('vendor/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('vendor/plugins/ckeditor/adapters/jquery.js') }}"></script>
<!-- pace-progress -->
<script src="{{ asset('vendor/plugins/pace-progress/pace.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('vendor/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('vendor/plugins/toastr/toastr.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('vendor/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('vendor/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('vendor/dist/js/adminlte.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('vendor/dist/js/pages/dashboard.js') }}"></script>
<!-- Vue -->
<script src="{{ asset('vendor/vue@2.js') }}"></script>
<!-- Fontawesome -->
<script src="https://kit.fontawesome.com/3e5f9a0a23.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<!-- Application -->
<script src="{{ asset('js/manifest.js') }}"></script>
<script src="{{ asset('js/vendor.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript">
	jQuery(document).ready( function ($) {
		$(this).ajaxError( function () {
			toastr.error('Oops! Something went wrong')
		})
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		})
	})
</script>

@stack('script')