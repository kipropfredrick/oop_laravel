<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>
             PRODUCTS REPORT
        </title>

        <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<link href="{{asset('global_assets/css/icons/icomoon/styles.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/bootstrap_limitless.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/layout.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/layout.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">
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
	<script src="{{ asset('global_assets/js/main/jquery.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/main/bootstrap.bundle.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/plugins/loaders/blockui.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/demo_pages/datatables_basic.js')}}"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="{{ asset('global_assets/js/plugins/visualization/d3/d3.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/plugins/visualization/d3/d3_tooltip.js')}}"></script>
	<script src="{{ asset('global_assets/js/plugins/forms/styling/switchery.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
	<script src="{{ asset('global_assets/js/plugins/ui/moment/moment.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/plugins/pickers/daterangepicker.js')}}"></script>

	<script src="{{ asset('assets/js/app.js')}}"></script>
	<script src="{{ asset('global_assets/js/demo_pages/backoffice.js')}}"></script>
	<!-- /theme JS files -->
    </head>
<body>
  <div style="font-family: Arial, Helvetica, sans-serif;" class="container">
  <!-- <i style="color:#ce3221;text-align:center;justify-content: center;" class="fas fa-file-pdf fa-2x">
        <a  style="color:#000" href="{{ URL::to('/report/pdf') }}">Export Pdf</a>
     </i> -->
     <div class="col-md-12">
    <div style="width:100%;height:50px;margin-top:20px;font-weight:bold;text-align:center;justify-content: center;" class="row card-header col-md-12">
         <h3 style="padding:5px;font-weight:bold;color:#000;font-family: Arial, Helvetica, sans-serif;">PRODUCTS REPORT</h3>
    </div>
    <hr>
    <div style="text-align:center;">
     <h5 style="padding:5px;font-weight:bold;color:#000">Agent : {{$agent->user->name}}</h5>
    </div>
    </div>
    <div class="col-md-12">
    <table class="table datatable-basic table-bordered  table-striped">
        <thead style="" class="">
            <th class="thead">No.</th>
            <th class="thead" style="width:300px">Product Name</th>
            <th class="thead">Product Code</th>
            <th class="thead">Item Price</th>
            <th class="thead">Quantity</th>
        </thead>
        <tbody>
        <?php $index=0; ?>
        @foreach($products as $product)
        <tr class="row100 body">
            <td>{{$index=$index+1}}.</td>
            <td style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2; -webkit-box-orient: vertical;width:300px">{{$product->product_name}}</td>
            <td>{{$product->product_code}}</td>
            <td>KES {{number_format($product->product_price)}}</td>
            <td>{{number_format($product->quantity)}}</td>
        </tr>
        @endforeach
        <tfoot>
        <tr>
            <td style="font-weight:bold" colspan="3">Totals</td>
            
            <td style="font-weight:bold">KES {{number_format($price_total)}}</td>
            <td style="font-weight:bold">{{number_format($quantity_total)}}</td>
        </tr>
  </tfoot>
    </tbody>
    </table>

  </div>
</div>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/af-2.3.4/b-1.6.1/b-flash-1.6.1/cr-1.5.2/fc-3.3.0/fh-3.1.6/kt-2.5.1/r-2.2.3/rg-1.1.1/rr-1.2.6/sc-2.0.1/sp-1.0.1/sl-1.3.1/datatables.min.js"></script>



<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
        $('#myTable2').DataTable();
        $('#myTable3').DataTable();
        });
</script>

</body>
</html>