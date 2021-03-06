@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Agents</strong></h6>
		</div>
		
		<div class="container">
		@if (session()->has('success'))

			<div class="alert alert-success fade show" role="alert">
				{{ session()->get('success') }}
			</div>

			@elseif (session()->has('error'))

				<div class="alert alert-danger fade show" role="alert">
					{{ session()->get('error') }}
				</div>

			@endif
		</div>
        
        <table id="myTable" class="table table-bordered table-striped">
						<thead>
							<tr>
                                <th class="thead">No.</th>
								<th class="thead">Full Name</th>
								<th class="thead">Phone</th>
								<th class="thead">Location</th>
								<th class="thead">Agent Code</th>
								<th class="thead">Date Added</th>
								<th class="thead">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php $index=0; ?>
                            @foreach($agents as $agent)
                                <tr>
                                    <td>
										{{$index = $index+1}}.
									</td>
									<td>
										@if(isset($agent->user->name))
											{{$agent->user->name}}
										@endif
									</td>
									<td>
										{{$agent->phone}}
									</td>
									<td>
										{{$agent->location}}
									</td>
									<td>
										{{$agent->agent_code}}
									</td>
									<td>
									{{date('M d'.', '.'Y', strtotime($agent->created_at))}}
									</td>
									<td>
								    	<a class="btn btn-outline-success" href="/admin/view-agent/{{$agent->id}}">View</a>
										<a class="btn btn-outline-danger" onclick="return confirm('Are you sure to delete this Agent?') ? true : false" href="/admin/agent/delete-account/{{$agent->id}}">Delete</a>
									</td>
                                </tr>  
                            @endforeach
						</tbody>
					</table>
                </div>
             </div>
@endsection
