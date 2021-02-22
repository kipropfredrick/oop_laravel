@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<legend style="padding:20px"  class="text-uppercase font-size-sm font-weight-bold">View Product</legend>
  <div style="margin-bottom:20px" class="container">
  <form action="/admin/save-product" method="post"  enctype="multipart/form-data">
          @csrf

          <div class="form-group row">
            <label class="col-form-label col-lg-2">Category</label>
            <div class="col-lg-10">
                <select disabled id="categories" class="form-control" name="category_id" placeholder="Enter name" type="text" class="form-control @if($errors->has('category_id')) invalid_field @endif" required onchange="filter()">
                   <option value="">{{$product->category->category_name}}</option>
                </select>

            </div>
        </div>
    <div class="form-group row">
            <label class="col-form-label col-lg-2">Sub Category</label>
            <div class="col-lg-10">
                <select disabled class="form-control" name="subcategory_id" id="subs" placeholder="Enter name" type="text" class="form-control @if($errors->has('subcategory_id')) invalid_field @endif" required>
                  <option value="">{{$product->subcategory->subcategory_name}}</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Product Name</label>
            <div class="col-lg-10">
                <input disabled value="{{$product->product_name}}" tclass="form-control" name="product_name" placeholder="Enter Product name" type="text" class="form-control @if($errors->has('product_name')) invalid_field @endif" required>
            </div>

        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Product price</label>
            <div class="col-lg-10">
                <div disabled class="form-control" name="product_price" placeholder="Enter Product price" type="number" class="form-control @if($errors->has('product_price')) invalid_field @endif" required>
                 KSh {{number_format($product->product_price)}}
            </div>
            </div>

        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Product quantity</label>
            <div class="col-lg-10">
                <div disabled value="" class="form-control" name="quantity" placeholder="Enter Product quantity" type="number" class="form-control @if($errors->has('quantity')) invalid_field @endif" required>
                {{number_format($product->quantity)}}
              </div>
            </div>

        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Highlights</label>
            <div class="col-lg-10">
                <textarea disabled class="form-control" cols="30" rows="10" name="highlights" id="highlights" placeholder="Enter Product price" type="number" class="form-control @if($errors->has('highlights')) invalid_field @endif" required>
               {!!$product->highlights!!}
                </textarea>
                @error('highlights')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Description</label>
            <div class="col-lg-10">
                <textarea disabled tclass="form-control" cols="30" rows="10" name="description" id="description" placeholder="Enter Product price" type="number" class="form-control @if($errors->has('description')) invalid_field @endif" required>
                {!!$product->description!!}
                </textarea>
                @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

        </div>

        

        
    <div class="form-group row">
            <label class="col-form-label col-lg-2">Product Image</label>
            <div class="col-lg-10">
            <img style="height:300px;width:300px;object-fit:contain;" src="/storage/images/{{$product->product_image}}" alt="image">

            </div>

        </div>

        <button type="submit" class="btn btn-primary">Add</button>

        </form>
  </div>
    </div>
@endsection




@section('extra-js')

<script type="text/javascript">

    function filter(){

        var x = document.getElementById("categories");
        var val = x.value;

        console.log(x.value);

        var subs = document.getElementsByClassName('subcategories');
        var subsNames = document.getElementsByClassName('subcategoriesnames');
        var subsIds = document.getElementsByClassName('subcategoriesid');
        var _arrayId = [];
        var _arrayName = [];
        var _arraySubsId = [];

        for(i =  0; i < subs.length; i++){
            if(subs[i].innerHTML == x.value){
                _arrayId.push(subs[i].innerHTML);
                _arrayName.push(subsNames[i].innerHTML);
                _arraySubsId.push(subsIds[i].innerHTML);
            }
            
        }

        var y = document.getElementById("subs");
        y.innerHTML = "";
        for(i = 0; i < _arrayId.length; i++){
            var node = document.createElement("option");
            // node.innerHTML = _array[i];
            node.setAttribute('value', _arraySubsId[i]);
            node.innerHTML = _arrayName[i];
            y.appendChild(node);  
        }

        console.log(_arrayId);


    }

</script>

@endsection