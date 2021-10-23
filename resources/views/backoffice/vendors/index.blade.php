@extends('backoffice.app')

@section('content')
<link rel="stylesheet" href="{{asset('assets/css/pdfcsv.css')}}">
<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Vendors</strong></h6>
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

		 <div class="padding">
		 
		 <table id="myTable" class="table table-bordered table-striped myTable">
						<thead>
							<tr>
                                <th class="thead">No.</th>
								<th class="thead">Full Name</th>
								<th class="thead">Business Name</th>
								<th class="thead">Phone</th>
								<th>Email</th>
								<th class="thead">Location</th>
								<th class="thead">Status</th>
								<th class="thead">Date Added</th>
								<th class="thead">Commissions</th>
								<th class="thead">Actons</th>
							</tr>
						</thead>
						<tbody>
							<?php $index=0; ?>
                            @foreach($vendors as $vendor)
                                <tr>
                                    <td>
										{{$index = $index+1}}.
									</td>
									<td>
										{{$vendor->user->name}}
									</td>
									<td>
										{{$vendor->business_name}}
									</td>
									<td>
										{{$vendor->phone}}
									</td>
									<td>
									 {{$vendor->user->email}}
									</td>
									<td>
										{{$vendor->location}}
									</td>
									<td>
										{{ucfirst($vendor->status)}}
									</td>
									<td>
										{{date('M d'.', '.'Y', strtotime($vendor->created_at))}}
									</td>
									<td>
										<a href="setcommissions/{{$vendor->id}}" title="set commissions"><i class="fa fa-plus"></i></a>
									</td>
									<td>
										@if($vendor->status == 'pending')
											<a class="btn btn-outline-primary" href="/admin/approve-vendor/{{$vendor->id}}">Approve</a>
										@else
										@endif
										<a class="btn btn-outline-success" href="/admin/edit-vendor/{{$vendor->id}}">Edit</a>
										<a class="btn btn-outline-danger" onclick="return confirm('Are you sure to delete this vendor?') ? true : false" href="/admin/vendor/delete-account/{{$vendor->id}}">Delete</a>
									</td>
                                </tr>
                            @endforeach
						</tbody>
					</table>
		 
		 </div>
        
         
		</div>
		</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                dom: "Blfrtip",
                buttons: [
                    {
                        text: 'csv',
                        extend: 'csvHtml5',
                        title: "Vendors",
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },
                    {
                        text: 'excel',
                        extend: 'excelHtml5',
                        title: "Vendors",
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },
                    {
                        text: 'pdf',
                        extend: 'pdfHtml5',
                        title: "Vendors",
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },
                    {
                        text: 'print',
                        extend: 'print',
                        title: "Vendors",
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },

                ],
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }]
            });
        });
    </script>
