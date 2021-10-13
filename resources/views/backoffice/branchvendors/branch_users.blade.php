@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Branch Users</strong></h6>
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
								<th class="thead">Phone</th>
								<th>Email</th>
								<th class="thead">Location</th>
								<th class="thead">Status</th>

								<th class="thead">Role</th>
								<th class="thead">Date Added</th>
								<th class="thead">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php $index=0; ?>
                            @foreach($branch_users as $branch_user)
                                <tr>
                                    <td>
										{{$index = $index+1}}.
									</td>
									<td>
										{{$branch_user->user->name}}
									</td>
								
									<td>
										{{$branch_user->phone}}
									</td>
									<td>
									 {{$branch_user->user->email}}
									</td>
									<td>
										{{$branch_user->location}}
									</td>
									<td>
										{{ucfirst($branch_user->status)}}
									</td>
									<td>
										{{ucfirst($branch_user->role)}}
									</td>
									<td>
										{{date('M d'.', '.'Y', strtotime($branch_user->created_at))}}
									</td>
									
									<td>
									
										
										
									</td>
                                </tr>
                            @endforeach
						</tbody>
					</table>
		 
		 </div>
        
         
		</div>
		</div>
@endsection
