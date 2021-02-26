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
					Products</strong></h6>
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
					<th class="thead" style="width:300px">Product Name</th>
					<th class="thead">Product Code</th>
					<th class="thead">Item Price</th>
					<th class="thead">Item Weight</th>
					<th class="thead">Quantity</th>
					<th class="text-center thead">Actions</th>
                  </tr>
                  </thead>
                  <tbody>
					<?php $index=0; ?>
						@foreach($products as $product)
						<tr>
						<td>{{$index=$index+1}}.</td>
							<td style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2; -webkit-box-orient: vertical;width:300px">{{$product->product_name}}</td>
							<td>{{$product->product_code}}</td>
							<td>KES {{number_format($product->product_price)}}</td>
							<td>{{$product->weight}}</td>
							<td>{{number_format($product->quantity)}}</td>
							<td class="text-center">
							<div style="width:150px" class="row">
								<!-- <a data-toggle="tooltip" title="Assign to agent" style="margin-right:10px" href="/admin/product-assign/{{$product->id}}" class="btn btn-outline-primary"><i class="fa fa-user-plus"></i></a> -->
								@if(auth()->user()->role == "admin")
								<a data-toggle="tooltip" title="Edit product" style="margin-right:10px" href="/admin/product-edit/{{$product->id}}" class="btn btn-outline-primary"><i class="fa fa-edit"></i></a>
								<a data-toggle="tooltip" title="Delete product" onclick="return confirm('Are you sure to delete this product') ? true : false" style="margin-right:10px" href="/admin/product-delete/{{$product->id}}" class="btn btn-outline-danger"><i class="fa fa-trash"></i></a>
								@elseif(auth()->user()->role == "agent")
								<a data-toggle="tooltip" title="Edit product" style="margin-right:10px" href="/agent/product-edit/{{$product->id}}" class="btn btn-outline-primary"><i class="fa fa-edit"></i></a>
								<a data-toggle="tooltip" title="Delete product" onclick="return confirm('Are you sure to delete this product') ? true : false" style="margin-right:10px" href="/agent/product-delete/{{$product->id}}" class="btn btn-outline-danger"><i class="fa fa-trash"></i></a>
								@elseif(auth()->user()->role == "vendor")
								<a data-toggle="tooltip" title="Edit product" style="margin-right:10px" href="/vendor/product-edit/{{$product->id}}" class="btn btn-outline-primary"><i class="fa fa-edit"></i></a>
								<a data-toggle="tooltip" title="Delete product" onclick="return confirm('Are you sure to delete this product') ? true : false" style="margin-right:10px" href="/vendor/product-delete/{{$product->id}}" class="btn btn-outline-danger"><i class="fa fa-trash"></i></a>
								@elseif(auth()->user()->role == "influencer")
								<a data-toggle="tooltip" title="Edit product" style="margin-right:10px" href="/influencer/product-edit/{{$product->id}}" class="btn btn-outline-primary"><i class="fa fa-edit"></i></a>
								<a data-toggle="tooltip" title="Delete product" onclick="return confirm('Are you sure to delete this product') ? true : false" style="margin-right:10px" href="/influencer/product-delete/{{$product->id}}" class="btn btn-outline-danger"><i class="fa fa-trash"></i></a>
								@endif
							</div>
							</td>
						</tr>
						@endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                    <th class="thead">No.</th>
					<th class="thead" style="width:300px">Product Name</th>
					<th class="thead">Product Code</th>
					<th class="thead">Item Price</th>
					<th class="thead">Item Weight</th>
					<th class="thead">Quantity</th>
					<th class="text-center thead">Actions</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

@endsection
