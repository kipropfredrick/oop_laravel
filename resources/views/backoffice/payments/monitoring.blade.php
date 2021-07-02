@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Monitoring</strong></h6>
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
        
        <div class="row">
        	<div class="card ml-1">
        		<div class="card-header">
        			Completed Payments
        		</div>
        		<div class="card-body">
        			{{$result->total}}
        		</div>
        	</div>
        	<div class="card ml-5">
        		<div class="card-header">
        			Mobile Payments
        		</div>
        		<div class="card-body">
        			 {{$result->mobile}}
        		</div>
        	</div>

                <div class="card ml-5">
                <div class="card-header">
                    Manual Payments
                </div>
                <div class="card-body">
                   

                    {{intval($result->total)-intval($result->mobile)}}
                </div>
            </div>
        	
        </div>
   
                </div>
             </div>
@endsection
