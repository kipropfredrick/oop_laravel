@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Products</strong></h6>
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
								<th class="thead">Product Name</th>
								<th class="thead">Product Code</th>
								<th class="thead">Item Price</th>
								<th class="thead">Agent</th>
								<th class="text-center thead">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php $index=0; ?>
                            @foreach($products as $product)
							<tr>
							<td>{{$index=$index+1}}.</td>
								<td>{{$product->product_name}}</td>
								<td>{{$product->product_code}}</td>
								<td>{{$product->product_price}}</td>
								<td>{{$product->agent->user->name}}</td>
								<td class="text-center">
									<div class="row">
										<a data-toggle="tooltip" title="View Product" href="/admin/agent/product-view/{{$product->id}}" class="btn btn-outline-primary"><i class="fa fa-eye"></i></a>
										@if($product->status !== "approved")
											<a data-toggle="tooltip" title="Approve Product" href="/admin/agent/product-approve/{{$product->id}}" class="btn btn-outline-success"><i class="fa fa-check"></i></a>
										@endif
										@if($product->status !== "rejected")
										 <a data-toggle="tooltip" title="Reject Product" href="/admin/agent/product-reject/{{$product->id}}" class="btn btn-outline-danger"><i class="fa fa-thumbs-down"></i></a>
										@endif
									</div>
								</td>
                            </tr>
                            @endforeach
						</tbody>
					</table>
                </div>
             </div>
@endsection
