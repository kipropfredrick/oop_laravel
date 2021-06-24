@extends('backoffice.app')

@section('content')

<div style="padding:10px" class="card">
	<div class="card-header">
	<h6 style="color: #005b77;" class="card-title"><strong>
		@if(isset($status))
		{{$status}}&nbsp;
		@endif 
		@if(isset($title))
		{{$title}}&nbsp;
		@endif 
		SMS logs</strong></h6>
	</div>

	@if (session()->has('success'))

	<div class="alert alert-success fade show" role="alert">
		{{ session()->get('success') }}
	</div>

	@elseif (session()->has('error'))

		<div class="alert alert-danger fade show" role="alert">
			{{ session()->get('error') }}
		</div>

	@endif

	<!-- /.card-header -->
	<div class="table-responsive">
	<table id="table1" class="table table-bordered table-striped">
		<thead>
		<tr>
		<th class="thead">No.</th>
		<th class="thead">Message</th>
		<th class="thead" style="width:300px">Receiver</th>
		<th class="thead">Type</th>
		<th class="thead">Status</th>
		<th class="thead">Cost</th>
		<th class="thead">Comment</th>
		<th>Sent On</th>
		</tr>
		</thead>
		<tbody>
		
		</tbody>
		<tfoot>
		<tr>
		<th class="thead">No.</th>
		<th class="thead">Message</th>
		<th class="thead" style="width:300px">Receiver</th>
		<th class="thead">Type</th>
		<th class="thead">Status</th>
		<th class="thead">Cost</th>
		<th class="thead">Comment</th>
		<th>Sent On</th>
		</tr>
		</tfoot>
	</table>
	</div>
	<!-- /.card-body -->
</div>
<!-- /.card -->

@endsection

@section('extra-js')

<script>

$(document).ready(function() {

var url = window.location.href;

var t =  $('#table1').DataTable({
	processing: true,
	serverSide: true,
	ajax: url,
	columns: [
		{data: "id",name:"id"},
		{
            data: "message",
            orderable: false,
            render(data) {
                return `
                <div style="width:200px;" class="ellipsis">
                   ${data}
                </div>
                `;
            }
        },
		{
            data: "receiver",
            orderable: false,
            render(data) {
                return `
                <div style="width:200px;" class="ellipsis">
                   ${data}
                </div>
                `;
            }
        },
		{data:'type',name:'type'},
		{data:'status',name:'status'},
		{
            data: "cost",
            orderable: false,
            render(data) {
                return `
                <div style="width:200px;" class="ellipsis">
                   ${data}
                </div>
                `;
            }
        },
		{data:'comment',name:'comment'},
		{
            data: "created_at_",name:'created_at',
            orderable: false,
            render(data) {
                return `
                <div style="width:200px;" class="ellipsis">
                   ${data}
                </div>
                `;
            }
        },
	],
});

t.on( 'draw.dt', function () {
var PageInfo = $('#table1').DataTable().page.info();
	 t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
		cell.innerHTML = i + 1 + PageInfo.start;
	} );
} );
} );


</script>

</script>

@endsection
