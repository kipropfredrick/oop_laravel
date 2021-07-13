@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>@if(isset($status)) {{$status}} @endif Products</strong></h6>
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
								<th>Weight</th>
								<th class="thead">Vendor</th>
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
								<td>{{$product->weight}}</td>
								<td>{{$product->vendor->user->name}}</td>
								<td class="text-center">
									<div class="row">
										<a data-toggle="tooltip" title="Edit Product" href="/admin/vendor/product-view/{{$product->id}}" class="btn mr-2 btn-outline-primary"><i class="fa fa-edit"></i></a>
										@if($product->status !== "approved")
											<a data-toggle="tooltip" title="Approve Product" href="/admin/vendor-product-approve/{{$product->id}}" class="btn mr-2 btn-outline-success"><i class="fa fa-check"></i></a>
										@endif
										@if($product->status !== "rejected")
										 <a data-toggle="tooltip" title="Reject Product" href="/admin/vendor-product-reject/{{$product->id}}" class="btn mr-2 btn-outline-danger"><i class="fa fa-thumbs-down"></i></a>
										@endif
										@if($product->status == "rejected")
										 <a data-toggle="tooltip" title="Delete Product" href="/admin/vendor-product-delete/{{$product->id}}" class="btn mr-2 btn-outline-danger"><i class="fa fa-trash"></i></a>
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
