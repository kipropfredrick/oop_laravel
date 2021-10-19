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
								<th class="thead">Branch Name</th>
									<th class="thead">Action</th>
								
							</tr>
						</thead>
						<tbody>
							<?php $index=0; ?>
                            @foreach($branches as $branch)
                                <tr>
                                    <td>
										{{$index = $index+1}}.
									</td>
									<td>
										{{$branch->name}}
									</td>
								<td>
									<a class="btn btn-outline-success" href="/vendor/view-branch/{{$branch->id}}">View</a>
									
								</td>
                                </tr>
                            @endforeach
						</tbody>
					</table>
		 
		 </div>
        
         
		</div>
		</div>
@endsection
