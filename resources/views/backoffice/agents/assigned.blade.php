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
        
        <table class="table datatable-basic  table-striped table-hover">
						<thead>
							<tr>
                                <th class="thead">No.</th>
								<th class="thead" style="width:300px">Product Name</th>
								<th class="thead">Product Code</th>
								<th class="thead">Item Price</th>
								<th class="thead">Quantity</th>
								<th class="text-center thead">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php $index=0; ?>
                            @foreach($products as $product)
							<tr>
							<td>{{$index=$index+1}}.</td>
								<td style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2; -webkit-box-orient: vertical;width:300px">{{$product->product->product_name}}</td>
								<td>{{$product->product->product_code}}</td>
								<td>KES {{number_format($product->product->product_price)}}</td>
								<td>{{number_format($product->quantity)}}</td>
								<td class="text-center">
									<a data-toggle="tooltip" title="View product" style="margin-right:10px" href="/agent/product-view/{{$product->product->id}}" class="btn btn-outline-primary"><i class="fa fa-eye"></i></a>
								</td>
                            </tr>
                            @endforeach
						</tbody>
					</table>
                </div>
             </div>
@endsection
