@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Influencer Payment Logs</strong></h6>
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
								<th class="thead">Influencer Code</th>
								<th class="thead">Amount (KES)</th>
								<th class="thead">Date Paid</th>
							</tr>
						</thead>
						<tbody>
							<?php $index=0; ?>
                            @foreach($logs as $log)
                                <tr>
                                    <td>
										{{$index = $index+1}}.
									</td>
									<td>
										@if(isset($log->influencer->user->name))
											{{$log->influencer->user->name}}
										@endif
									</td>
									<td>
										{{$log->influencer->phone}}
									</td>
									<td>
										{{$log->influencer->code}}
									</td>
									<td>
									  {{number_format($log->amount_paid,2)}}
									</td>
									<td>
									{{date('M d'.', '.'Y', strtotime($log->created_at))}}
									</td>
                                </tr>  
                            @endforeach
						</tbody>
					</table>
                </div>
             </div>
@endsection
