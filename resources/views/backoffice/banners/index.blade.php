@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class="table-responsive padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Banners</strong></h6>
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
								<th class="thead">Image</th>
								<th class="thead">Title</th>
								<th class="thead">Description</th>
								<th class="thead">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php $index=0; ?>
                            @foreach($banners as $banner)
                                <tr>
                                    <td>
										{{$index = $index+1}}.
									</td>
									<td>
									  <img style="height:50px;width:50px;object-fit:contain" src="/storage/banners/{{$banner->image}}" alt="banner">
									</td>
									<td>
										{{$banner->title}}
									</td>
									<td style="width: 500px;">
										<div style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2; -webkit-box-orient: vertical;">
											{!!$banner->description!!}
										</div>
									</td>
									<td>
								      <div class="row">
											<a style="margin:5px" class="btn btn-outline-success" href="/admin/view-banner/{{$banner->id}}"><i class="fa fa-eye"></i></a>
											<a style="margin:5px" class="btn btn-outline-danger" href="/admin/delete-banner/{{$banner->id}}" onclick="return confirm('Are you sure you want to delete this banner?') ? true : false"><i class="fa fa-trash"></i></a>
									  </div>
									</td>
                                </tr>
                            @endforeach
						</tbody>
					</table>
                </div>
             </div>
@endsection
