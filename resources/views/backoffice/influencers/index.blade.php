@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Influencers</strong></h6>
		</div>

		<a href="/admin/add-influencer" style="margin:10px !important" class="btn btn-outline-primary">Add Influencer</a>
		
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
								<th class="thead">Influencer Code</th>
								<th class="thead">Date Added</th>
								<th class="thead">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php $index=0; ?>
                            @foreach($influencers as $influencer)
                                <tr>
                                    <td>
										{{$index = $index+1}}.
									</td>
									<td>
										@if(isset($influencer->user->name))
											{{$influencer->user->name}}
										@endif
									</td>
									<td>
										{{$influencer->phone}}
									</td>
									<td>
										{{$influencer->code}}
									</td>
									<td>
									{{date('M d'.', '.'Y', strtotime($influencer->created_at))}}
									</td>
									<td style="width:200px">
								    	<div class="row">
											<a style="margin-right:5px" class="btn btn-outline-success" href="/admin/view-influencer/{{$influencer->id}}">View</a>
											<a class="btn btn-outline-danger" onclick="return confirm('Are you sure to delete this influencer?') ? true : false" href="/admin/influencer/delete-account/{{$influencer->id}}">Delete</a>
										</div>
									</td>
                                </tr>  
                            @endforeach
						</tbody>
					</table>
                </div>
             </div>
@endsection
