<!-- Favicon -->
<link rel="shortcut icon" href="{{ asset('image/favicon.png') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Tempusdominus Bbootstrap 4 -->
<link rel="stylesheet" href="{{ asset('vendor/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<!-- iCheck -->
<link rel="stylesheet" href="{{ asset('vendor/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<!-- JQVMap -->
<link rel="stylesheet" href="{{ asset('vendor/plugins/jqvmap/jqvmap.min.css') }}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{ asset('vendor/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('vendor/plugins/daterangepicker/daterangepicker.css') }}">
<!-- summernote -->
<link rel="stylesheet" href="{{ asset('vendor/plugins/summernote/summernote-bs4.css') }}">
<!-- pace-progress -->
<link rel="stylesheet" href="{{ asset('vendor/plugins/pace-progress/themes/black/pace-theme-flat-top.css') }}">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{ asset('vendor/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
<!-- Toastr Css -->
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/plugins/toastr/toastr.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/plugins/datatables/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/plugins/datatables/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/plugins/datatables/css/fixedHeader.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/plugins/datatables/css/select.dataTables.min.css') }}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('vendor/plugins/select2/css/select2.min.css') }}">
<!-- Google Font: Source Sans Pro -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('vendor/dist/css/adminlte.min.css') }}">
<style type="text/css">
    .pace {
        -webkit-pointer-events: none;
        pointer-events: none;

        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }
    .pace-inactive {
        display: none;
    }
    .pace .pace-progress {
        background: #fd7e14;
        position: fixed;
        z-index: 2000;
        top: 0;
        right: 100%;
        width: 100%;
        height: 2px;
    }
    .btn.action {
        display: inline;
        margin-right: 10px;
        color: #fff !important;
    }
    form label.error {
        font-weight: 400 !important;
        font-size: 14px;
        color: red;
    }
    .content-wrapper>.content {
        padding-bottom: 1rem !important;
    }
    .swal2-content *:not(#swal2-content) {
        text-align: left !important;
    }
    .swal2-radio label {
        margin-left: 30px !important;
        cursor: pointer;
    }
    .swal2-radio .swal2-label::before, .swal2-radio .swal2-label::after {
        top: calc(80% - 1rem) !important;
    }
    #swal2-validation-message {
        white-space: pre-line;
    }
    .select2-container {
        z-index: 2099;
    }
    .form-control[type="file"] {
        padding: 3px .75rem 3px 3px;
    }
    .form-group {
        text-align: left;
    }
    .invalid-feedback {
        display: block !important;
    }
</style>

@stack('style')