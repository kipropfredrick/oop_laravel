@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<h6 style="padding:20px"  class="text-uppercase font-size-sm font-weight-bold">Assign Product</legend>
  <div style="margin-bottom:20px" class="container">
		@if (session()->has('success'))

			<div class="alert alert-success fade show" role="alert">
				{{ session()->get('success') }}
			</div>

			@elseif (session()->has('error'))

				<div class="alert alert-danger fade show" role="alert">
					{{ session()->get('error') }}
				</div>

			@endif
        
  <form action="/admin/assign-product/{{$product->id}}" method="post">
          @csrf

          
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Product Name</label>
            <div class="col-lg-10">
                <div class="form-control"  class="form-control @if($errors->has('product_name')) invalid_field @endif">
                 {{$product->product_name}}
                </div>

            </div>

        </div>


        <div class="form-group row">
            <label class="col-form-label col-lg-2">Product Quantity</label>
            <div class="col-lg-10">
                <div  class="form-control"  class="form-control">
                 {{number_format($product->quantity)}}
                </div>

            </div>

        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Product price</label>
            <div class="col-lg-10">
                <div class="form-control" name="product_price" placeholder="Enter Product price" type="number" class="form-control @if($errors->has('product_price')) invalid_field @endif" required>
                Ksh {{number_format($product->product_price)}}
             </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Assign Agent</label>
            <div class="col-lg-10">
               <select  class="form-control" name="agent_id" id="agent_id" required>
                    <option value="">Select Agent</option>
                    @foreach($agents as $agent)
                     <option value="{{$agent->agent_id}}">{{$agent->name}} ({{$agent->business_name}})</option>
                    @endforeach
               </select>
             </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Quantity</label>
            <div class="col-lg-10">
                <input value="{{ old('quantity') }}" tclass="form-control" name="quantity" placeholder="Enter quantity to be assigned" type="number" class="form-control @if($errors->has('quantity')) invalid_field @endif" required>
               
                @error('quantity')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

        </div>

        <button type="submit" class="btn btn-primary">Assign</button>

        </form>
  </div>
    </div>
@endsection
