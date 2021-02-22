@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Payment History</strong></h6>
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
        
        <table class="table datatable-basic  table-striped">
						<thead>
							<tr>
                                <th class="thead">No.</th>
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
