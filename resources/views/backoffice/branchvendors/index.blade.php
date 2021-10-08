@extends('backoffice.app')

@section('content')

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
		 
		 <table id="myTable" class="table table-bordered table-striped">
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
										<a class="btn btn-outline-success" href="/admin/view-vendor/{{$vendor->id}}">View</a>
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
