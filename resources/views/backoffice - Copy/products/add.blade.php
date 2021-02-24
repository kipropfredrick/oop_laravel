@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<h6 style="padding:20px"  class="text-uppercase font-size-sm font-weight-bold">Add Product</legend>
  <div style="margin-bottom:20px" class="container">
  @if(auth()->user()->role == 'admin')
  <form action="/admin/save-product" method="post"  enctype="multipart/form-data">
    @elseif(auth()->user()->role == 'agent')
  <form action="/agent/save-product" method="post"  enctype="multipart/form-data">
  @elseif(auth()->user()->role == 'influencer')
  <form action="/influencer/save-product" method="post"  enctype="multipart/form-data">
    @elseif(auth()->user()->role == 'vendor')
  <form action="/vendor/save-product" method="post"  enctype="multipart/form-data">
  @endif

          @csrf

          <div class="form-group row">
            <label class="col-form-label col-lg-2">Category</label>
            <div class="col-lg-10">
                <select id="categories" class="form-control" name="category_id" placeholder="Enter name" type="text" class="form-control @if($errors->has('category_id')) invalid_field @endif" required onchange="filter()">
                <option value="">Select category</option>
                @foreach($categories as $category)
                    <option value="{{$category->id}}" class="categories">{{$category->category_name}}</option>
                     
                @endforeach
                </select>
                @error('category_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>
        </div>

        @foreach($subcategories as $subcategory)
            <div class="subcategories" style="display: none">{{$subcategory->category_id}}</div>
        @endforeach

        @foreach($subcategories as $subcategory)
            <div class="subcategoriesid" style="display: none">{{$subcategory->id}}</div>
        @endforeach

        @foreach($subcategories as $subcategory)
            <div class="subcategoriesnames" style="display: none">{{$subcategory->subcategory_name}}</div>
        @endforeach
    <div class="form-group row">
            <label class="col-form-label col-lg-2">Sub Category</label>
            <div class="col-lg-10">
                <select class="form-control" name="subcategory_id" id="subs" placeholder="Enter name" type="text" class="form-control @if($errors->has('subcategory_id')) invalid_field @endif" required>
                  <option value="">Select Sub category</option>
                </select>
                @error('subcategory_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Product Name</label>
            <div class="col-lg-10">
                <input tclass="form-control" name="product_name" placeholder="Enter Product name" type="text" class="form-control @if($errors->has('product_name')) invalid_field @endif" required>
               
                @error('product_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Buying price</label>
            <div class="col-lg-10">
                <input min="1" tclass="form-control" name="buying_price" placeholder="Enter Product price" type="number" class="form-control @if($errors->has('buying_price')) invalid_field @endif" required>
               
                @error('buying_price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Selling price</label>
            <div class="col-lg-10">
                <input min="1" tclass="form-control" name="product_price" placeholder="Enter Product price" type="number" class="form-control @if($errors->has('product_price')) invalid_field @endif" required>
               
                @error('product_price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Product Condition</label>
            <div class="col-lg-10">
                <select  tclass="form-control" name="condition"  class="form-control @if($errors->has('condition')) invalid_field @endif" required>
                <option value="">Product Condition</option>
                <option value="new">New</option>
                <option value="used">Used</option>
                <option value="refurbished">Refurbished</option>
               </select>
                @error('condition')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

        </div>


        <div class="form-group row">
            <label class="col-form-label col-lg-2">Product Weight</label>
            <div class="col-lg-5">
                <input min="1" tclass="form-control" name="weight" placeholder="Enter Product weight" type="number" class="form-control @if($errors->has('weight')) invalid_field @endif" required>
               
                @error('weight')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

            <div class="col-lg-5">
                <select tclass="form-control" name="unit"  type="" class="form-control @if($errors->has('unit')) invalid_field @endif" required>
                    <option value="kg">Kilograms (Kg)</option>
                    <option value="g">Grams (g)</option>
                </select>
                @error('unit')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Product quantity</label>
            <div class="col-lg-10">
                <input tclass="form-control" name="quantity" placeholder="Enter Product quantity" type="number" class="form-control @if($errors->has('quantity')) invalid_field @endif" required>
               
                @error('quantity')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Highlights</label>
            <div class="col-lg-10">
                <textarea class="form-control" cols="30" rows="10" name="highlights" id="highlights" placeholder="Enter Product price" type="number" class="form-control @if($errors->has('highlights')) invalid_field @endif" required>
               
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
                <textarea tclass="form-control" cols="30" rows="10" name="description" id="description" placeholder="Enter Product price" type="number" class="form-control @if($errors->has('description')) invalid_field @endif" required>
               
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
            <input type="file" id="product_image" name="product_image" class="form-control" required accept="image/*">
               
                @error('product_price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Gallery Images</label>
            <div class="col-lg-10">
            <input type="file" id="image_path" name="image_path[]" class="form-control" required  multiple>
                @error('product_price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

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