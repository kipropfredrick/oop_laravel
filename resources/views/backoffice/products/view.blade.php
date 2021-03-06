@extends('backoffice.app')

@section('content')

    <div class="card">
    <div class="card-header">
    <h3 class="card-title">Edit Product</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
    @if(auth()->user()->role == 'admin')
    <form action="/admin/update-product/{{$product->id}}" method="post"  enctype="multipart/form-data">
    @elseif(auth()->user()->role == 'vendor')
    <form action="/vendor/update-product/{{$product->id}}" method="post"  enctype="multipart/form-data">
    @endif
       @csrf
         <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Category</label>
                  <select  onchange="get_subs()" name="category_id" required id="category_id" class="form-control select2 dynamic" data-dependent="subcategory_id" style="width: 100%;">
                  <option value="{{$product->category->id}}">{{$product->category->category_name}}</option>
                  <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->category_name}}</option>
                    @endforeach
                  </select>
                 </div>
                </div>
                
                <div class="col-md-6">
                <div class="form-group">
                  <label>Sub Category</label>
                   <select onchange="get_third_subs()" required name="subcategory_id" id="subcategory_id" class="form-control select2 dynamic" data-dependent="third_level_category_id" style="width: 100%;">
                    <option value="{{$product->subcategory->id}}">{{$product->subcategory->subcategory_name}}</option>
                  </select>
                </div>
                </div>
                
              </div>

              <div class="row">
              <!-- /.col -->
              
              <div class="col-md-6">
                <div class="form-group">
                 <label>Third level Category</label>
                  <select name="third_level_category_id" id="third_level_category_id" class="form-control select2 dynamic"  style="width: 100%;">
                    <option value="{{$product->third_level_category_id}}">@if(isset($product->third_level_category)){{$product->third_level_category->name}}@endif</option>
                  </select>
                </div>
                </div>

              <div class="col-md-6">
                <div class="form-group">
                 <label>Brand</label>
                 <?php $brands = \App\Brand::all(); ?>
                  <select name="brand_id" id="brand_id" class="form-control select2 dynamic"  style="width: 100%;">
                    @if(isset($product->brand))
                    <option value="{{$product->brand->id}}">{{$product->brand->brand_name}}</option>
                    @else
                    <option value="">Select brand</option>
                    @endif
                    @foreach($brands as $brand)
                     <option value="{{$brand->id}}">{{$brand->brand_name}}</option>
                    @endforeach
                  </select>
                </div>
                </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">

            <div class="col-md-6">
            <div class="form-group">
            <label>Product Name</label>
                <input tclass="form-control" value="{{$product->product_name}}" name="product_name" placeholder="Enter Product name" type="text" class="form-control @if($errors->has('product_name')) invalid_field @endif" required>
               
                @error('product_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

        </div>

            <div class="col-md-6">
            <div class="form-group">
            <label>Buying price</label>
                <input value="{{$product->buying_price}}" min="1" tclass="form-control" name="buying_price" placeholder="Enter Product Buying price" type="number" class="form-control @if($errors->has('buying_price')) invalid_field @endif" required>
               
                @error('buying_price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

            </div>

            </div>

            <div class="row">
             <div class="col-md-6">
             <div class="form-group">
               <label class="col-form-label">Selling price</label>
                <input min="1" value="{{$product->product_price}}" tclass="form-control" name="product_price" placeholder="Enter Product price" type="number" class="form-control @if($errors->has('product_price')) invalid_field @endif" required>
               
                @error('product_price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

        </div>
        
          <div class="col-md-6" >
          <div class="form-group">
            <label class="col-form-label">Product Condition</label>
                <select  tclass="form-control" name="condition"  class="form-control @if($errors->has('condition')) invalid_field @endif" required>
                    @if($product->condition == "new")
                    <option value="new" selected>New</option>
                    <option value="used">Used</option>
                    <option value="refurbished">Refurbished</option>
                    @elseif($product->condition == "used")
                    <option value="new">New</option>
                    <option value="used" selected>Used</option>
                    <option value="refurbished">Refurbished</option>
                    @elseif($product->condition == "refurbished")
                    <option value="new">New</option>
                    <option value="used">Used</option>
                    <option value="refurbished" selected>Refurbished</option>
                    @endif
               </select>
                @error('condition')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

        </div>
     </div>

     <div class="row">

           <div class="col-md-6">
           <div class="form-group">
            <label class="">Product Weight</label>
                <input min="1" step=".01" tclass="form-control" value="{{$product->weight[0]}}" name="weight" placeholder="Enter Product weight" type="number" class="form-control @if($errors->has('weight')) invalid_field @endif" required>
               
                @error('weight')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

            </div>

           </div>


            <div class="col-lg-6">
                <div class="form-group">
                <label class="">Unit</label>
                <select tclass="form-control" name="unit"  type="" class="form-control @if($errors->has('unit')) invalid_field @endif" required>
                    <option value="{{$product->weight[1]}}">
                        @if($product->weight[1] == 'kg')
                        Kilograms (Kg)
                        @else
                        Grams (g)
                        @endif
                    </option>
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

     </div>

     <div class="row">

        <div class="col-md-6">

        <div class="form-group">
            <label class="">Product Image</label>
            <div class="col-lg-10 row">
               <img style="width:100px;height:100px;object-fit:contain" src="/storage/images/{{$product->product_image}}" alt="">
                
            </div>
            </div>

        </div>

        <div class="col-md-6">
        <div class="form-group">
            <label class="">Change Product Image</label>
                <input name="product_image" type="file" data-toggle="tooltip" title="Change Product image" style="height:35px;margin-top:30px">
            </div>
        </div>

     </div>

     <div class="row">
     
     <div class="col-md-6">
        <div class="form-group">
            <label class="">Gallery Images</label>
                @forelse($product->gallery as $gallery)
                    <div class="row">
                    <img style="width:100px;height:100px;object-fit:contain" src="/storage/gallery/images/{{$gallery->image_path}}" alt="">
                        <a data-toggle="tooltip" title="Delete image" onclick="return confirm('Are you sure to delete this image') ? true : false" href="/vendor/image-delete/{{$gallery->id}}" style="height:35px;margin-top:30px" class="btn btn-danger" href=""><i class="fa fa-trash"></i></a>
                    </div>
                @empty
                No Images
                @endforelse

            </div>
        </div>


        <div class="col-lg-6" style="margin-bottom:20px">
            <div class="form-group">
                <label class="col-form-label">Add a gallery Image (Optional)</label>
                <input type="file" data-toggle="tooltip" name="image_path" title="Add Gallery Image" style="height:35px;">
            </div>
        </div>

     
     </div>

     

     <div class="form-group">
            <label class="">Product quantity</label>
                <input value="{{$product->quantity}}" tclass="form-control" name="quantity" placeholder="Enter Product quantity" type="number" class="form-control @if($errors->has('quantity')) invalid_field @endif" required>
               
                @error('quantity')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

        </div>

     <div class="form-group">
            <label class="">Highlights</label>
                <textarea class="form-control" cols="30" rows="10" name="highlights" id="highlights" placeholder="Enter Product price" type="number" class="form-control @if($errors->has('highlights')) invalid_field @endif" required>
                {!!$product->highlights!!}
                </textarea>
                @error('highlights')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

        </div>

        <div class="form-group">
            <label class="">Description</label>
                <textarea tclass="form-control" cols="30" rows="10" name="description" id="description" placeholder="Enter Product price" type="number" class="form-control @if($errors->has('description')) invalid_field @endif" required>
                {!!$product->description!!}
                </textarea>
                @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                 @enderror

        </div>


    <div class="form-group">    
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
                
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

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


    function filterT(){

    var x = document.getElementById("subs");
    var val = x.value;

    var subs = document.getElementsByClassName('subcategories');
    var subsNames = document.getElementsByClassName('subcategoriesnames');
    var tnames = document.getElementsByClassName('third_level_categoriesnames');
    var subsIds = document.getElementsByClassName('subcategoriesid');
    var tsubsIds = document.getElementsByClassName('third_level_categoriesid'); 

    var _arrayId = [];
    var _arrayName = [];
    var _arraytName = [];
    var _arraySubsId = [];
    var _arraytSubsId = [];

    for(i =  0; i < subs.length; i++){
        if(subs[i].innerHTML == x.value){
            _arrayId.push(subs[i].innerHTML);
            _arrayName.push(subsNames[i].innerHTML);
            _arraytName.push(tnames[i].innerHTML);
            _arraySubsId.push(subsIds[i].innerHTML);
            _arraytSubsId.push(tsubsIds[i].innerHTML);
        }
        
    }

    var y = document.getElementById("subs");
    y.innerHTML = "";
    for(i = 0; i < _arrayId.length; i++){
        var node = document.createElement("option");
        // node.innerHTML = _array[i];
        node.setAttribute('value', _arraySubsId[i]);
        node.innerHTML = _arrayName[i];

        node.setAttribute('value', _arraytSubsId[i]);
        node.innerHTML = _arraytName[i];
        y.appendChild(node);  
    }

    console.log(_arrayId);


    }


    function get_subs(){

        var category_id = document.getElementById("category_id").value;

        console.log("Category id => "+category_id);

        $('#subcategory_id').children().remove();
        
        $.ajax({
                url: '/api/get-categories',
                type: 'POST',

                data: {category_id:category_id},

                success: function (data) { 
                    var length = data.length;
                    for (i = 0; i < length; i++)
                    { 
                        $('#subcategory_id').append( '<option value="'+data[i].id+'">'+data[i].subcategory_name+'</option>' );
                    }
                }
          });

          get_third_subs();
           
    } 


    function get_third_subs(){

    var subcategory_id = document.getElementById("subcategory_id").value;

    console.log("subcategory_id id => "+subcategory_id);

    $('#third_level_category_id').children().remove();

    $.ajax({
            url: '/api/get-third-categories',
            type: 'POST',

            data: {subcategory_id:subcategory_id},

            success: function (data) { 
                var length = data.length;
                console.log("Data => "+JSON.stringify(data));
                for (i = 0; i < length; i++)
                { 
                    $('#third_level_category_id').append( '<option value="'+data[i].id+'">'+data[i].name+'</option>' );
                }
            }
    });

    var subcategory_id = document.getElementById("subcategory_id").value;

    console.log("subcategory_id id => "+subcategory_id);
    
    } 
    

</script>

@endsection