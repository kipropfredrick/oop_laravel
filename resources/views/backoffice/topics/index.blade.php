@extends('backoffice.app')

@section('content')

<!-- Traffic sources -->
<div class="card">
<div class=" padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>Topics</strong></h6>
		</div>
		
		<div class="mt-1">
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
        <div class="card-body">

      
      <div class="container row mt-0 ">
      	<label for="comment" class="col-12" style="">Edit  <button type="button" class="" data-toggle="modal" data-target="#exampleModalCenter"><i class="fa fa-plus" aria-hidden="true"></i></button></label>

      	<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Topic</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
          <form method="post" action="{{route('admin.addtopic')}}">
          	{{csrf_field()}}
      <div class="modal-body">
    
        	<div class="form-group">
        		<label class="label"> Name</label>
        		<input type="text" name="topic" class="form-control">

        	</div>
     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
         </form>
    </div>
  </div>
</div>
      	


 @foreach ($topics as $key => $value) 
   <div class="chip">
	<i class="fa fa-tasks" aria-hidden="true"></i>
  {{$value->name}}
  <span class="closebtn" onclick=""><a href=" {{route('admin.removetopic',['id'=>$value->id])}}" onclick="return confirm('Are you sure?')">&times;</a></span>
</div>
@endforeach



      </div>
      <form method="post" action="{{route('admin.firebasetopics')}}">

{{csrf_field()}}

      <div class="container row mt-0">


      	
      		<label for="comment" class="col-12" style="">Target Topic</label>

      		<div class="container row">
   


 @foreach ($topics as $key => $value) 
     		<div class="radio ml-2">
  <label><input type="radio" name="topic" value="{{$value->name}}" checked > &nbsp &nbsp {{$value->name}}</label>
</div>
@endforeach

      	</div>

	<div class="form-group col-12 row">

<div class="form-group  col-12 row">
	<label for="comment col-12">Topic</label>
	<input type="text" name="title" class="form-control">
	
</div>
  <label for="comment col-12">Comment:</label>
  <textarea class="form-control col-12" rows="5" id="comment" name="description"></textarea>
</div>
      </div>


 <button style="margin-bottom:20px" type="submit" class="btn btn-primary">Send</button>
  </form>

      </div>
       
                </div>
             </div>


             <style type="text/css">
             	
             	.chip {
  display: inline-block;
  padding: 0 25px;
  height: 50px;
  font-size: 16px;
  line-height: 50px;
  border-radius: 25px;
  background-color: #f1f1f1;
}

.chip img {
  float: left;
  margin: 0 10px 0 -25px;
  height: 50px;
  width: 50px;
  border-radius: 50%;
}

.closebtn {
  padding-left: 10px;
  color: #888;
  font-weight: bold;
  float: right;
  font-size: 20px;
  cursor: pointer;
}

.closebtn:hover {
  color: #000;
}
             </style>
@endsection
