@extends('backoffice.app')

@section('content')

<div class="card">
              <div class="card-header">
				<h6 style="color: #005b77;" class="card-title"><strong>
					@if(isset($status))
					{{$status}}&nbsp;
					@endif 
					@if(isset($title))
					{{$title}}&nbsp;
					@endif 
					logs</strong></h6>
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

              <!-- /.card-header -->
              <div class="card-body">
                <table id="myTable" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th class="thead">No.</th>
					<th class="thead">Message</th>
                    <th class="thead" style="width:300px">Receiver</th>
                    <th class="thead">Type</th>
					<th class="thead">Status</th>
                    <th class="thead">Cost</th>
                    <th class="thead">Comment</th>
                  </tr>
                  </thead>
                  <tbody>
					<?php $index=0; ?>
						@foreach($logs as $log)
						<tr>
						<td>{{$index=$index+1}}.</td>
							<td>{{$log->message}}</td>
							<td>{{$log->receiver}}</td>
							<td>{{$log->type}}</td>
							<td>{{$log->status}}</td>
                            <td>{{$log->cost}}</td>
                            <td>{{$log->comment}}</td>
						</tr>
						@endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                    <th class="thead">No.</th>
					<th class="thead">Message</th>
                    <th class="thead" style="width:300px">Receiver</th>
                    <th class="thead">Type</th>
					<th class="thead">Status</th>
                    <th class="thead">Cost</th>
                    <th class="thead">Comment</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

@endsection
