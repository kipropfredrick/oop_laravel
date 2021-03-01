@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Payments Logs</strong></h6>
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
								<th class="thead">TransactionType</th>
								<th class="thead">TransID</th>
								<th class="thead">TransAmount</th>
								<th class="thead">BillRefNumber</th>
								<th class="thead">MSISDN</th>
								<th class="thead">OrgAccountBalance</th>
								<th class="thead">
								FirstName
								</th>

								<th class="thead">
								MiddleName
								</th>

								<th class="thead">
								LastName
								</th>

								<th class="thead">
								LastName
								</th>

								<th class="thead">
								Status
								</th>
                                <th class="thead">Date Paid</th>
							</tr>
						</thead>
						<tbody>
						<?php $index=0; ?>
                            @foreach($payments as $payment)
                                <tr>
								<td>
									{{$index = $index+1}}.
									</td>
									<td>
									{{$payment->TransactionType}}
									</td>
									<td>
									{{$payment->TransID}}
									</td>
									
									<td>
										KSh {{number_format($payment->TransAmount)}}
									</td>

									<td>
										{{$payment->BillRefNumber}}
									</td>

									<td>
										{{$payment->MSISDN}}
									</td>
									
									<td>
										{{$payment->OrgAccountBalance}}
									</td>

									<td>
										{{$payment->FirstName}}
									</td>

									<td>
										{{$payment->MiddleName}}
									</td>

									<td>
										{{$payment->LastName}}
									</td>

									<td>
										{{$payment->LastName}}
									</td>

									<td>
										{{$payment->status}}
									</td>
									<td>
									{{date('M d'.', '.'Y', strtotime($payment->TransTime))}}
									</td>

                                </tr>
                            @endforeach
						</tbody>
					</table>
                </div>
             </div>
@endsection
