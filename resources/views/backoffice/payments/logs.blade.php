@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
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
									Status/Action
								</th>
                                <th  class="thead">Date Paid</th>
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
									 @if(\App\Bookings::where('booking_reference',$payment->BillRefNumber)->exists())
										{{$payment->status}}
									@else
										@if(auth()->user()->role === "admin")
										<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#recordPaymentModal{{$payment->id}}">
											Record Payment
										</button>
										@else
										{{$payment->status}}
										@endif
									
									@endif
									</td>
									<td width="200">
									 {{date('M d'.', '.'Y'.' '.'h'.':'.'i'.':'.'s', strtotime($payment->TransTime))}}
									</td>

                                </tr>

								<!-- Modal -->
								<div class="modal fade" id="recordPaymentModal{{$payment->id}}" tabindex="-1" role="dialog" aria-labelledby="recordPaymentModal{{$payment->id}}Label" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="recordPaymentModal{{$payment->id}}Label">Record Payment</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<form action="/admin/record-payment/{{$payment->id}}" method="post">
										@csrf
										<div class="modal-body">
											<label for="">Amount</label>
												<input type="number" name="amount" readonly="readonly" value="{{$payment->TransAmount}}" class="form-control">
											<label for="">Correct Account No.</label>
												<input type="" name="booking_reference" required class="form-control">
										</div>
										<div class="modal-footer">
											<button type="submit" class="btn btn-primary">Submit</button>
										</div>

									</form>

									</div>
								</div>
								</div>

                            @endforeach
						</tbody>
					</table>
                </div>
             </div>
@endsection
