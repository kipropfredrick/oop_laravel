@extends('backoffice.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Traffic sources -->
<div class="card">
<div class=" padding">
        <div class="card-header header-elements-inline">
            <h6 style="color: #005b77;" class="card-title"><strong>
              In your notification you can use any of the following tags: {customerName}
            </strong></h6>
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

<form class="col-12" method="post" action="{{route('admin.customnotify')}}" id="form">
    {{csrf_field()}}
<div class="container w-100">
    <label class="label">Notification category</label>
    <div class="form-check">
  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1"  onchange="changelog()" checked>
  <label class="form-check-label" for="flexRadioDefault1">
  Group
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" onchange="changelog()">
  <label class="form-check-label" for="flexRadioDefault2" >
   Individual
  </label>
</div>

  
</div>




  <div class="container w-100" id="group">
    <label>
      Applicable categories
    </label>
    <div class="form-check">
  <input class="form-check-input" name="category[]" type="checkbox" value="active" id="defaultCheck1">
  <label class="form-check-label" for="defaultCheck1" >
   Active Bookings 
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" name="category[]" type="checkbox" value="complete" id="defaultCheck2">
  <label class="form-check-label" for="defaultCheck2" >
    Complete Bookings
  </label>
</div>

 <div class="form-check">
  <input class="form-check-input" name="category[]" type="checkbox" value="pending" id="defaultCheck1">
  <label class="form-check-label" for="defaultCheck1" >
   Pending Bookings 
  </label>
</div>

<div class="form-check">
  <input class="form-check-input" name="category[]" type="checkbox" value="nobooking" id="defaultCheck3">
  <label class="form-check-label" for="defaultCheck2" >
    No Bookings
  </label>
</div>
  </div>
<br>

   <div class="container w-100" id="individual" style="display: none;" >
    <label>Send Notification To:</label>
  <select class="form-control" id="users" name="sendto">
    <option value="000">Select user</option>
@foreach ($users as $user)
    <option value="{{$user->id}}">{{$user->name}}</option>

    @endforeach
  </select>
 
  </div>

      <div class="container row mt-0">


  <div class="form-group col-12 row">

<div class="form-group  col-12 row">
  <label for="comment col-12">Topic</label>
  <input type="text" name="title" class="form-control">
  
</div>
  <label for="comment col-12">Message</label>
  <textarea class="form-control col-12" rows="5" id="comment" name="message"></textarea>
</div>
      </div>


 <button style="margin-bottom:20px" type="submit" class="btn btn-primary">Send</button>

</form>


      </div>
 

      </div>
       
                </div>
             </div>

         <!--     <script type="text/javascript">
               
               $("#select2").select2();
             </script> -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
  
  function changelog(){
 
    if($("#flexRadioDefault1").is(":checked")) {
     //write your code  
      
     $("#individual").hide(500);  
      $("#group").show(500);     

}

  if($("#flexRadioDefault2").is(":checked")) {
     //write your code   
     $("#group").hide(500);  
      $("#individual").show(500);       

}

  }

  $('#form').submit(function(eventObj) {

       if($("#flexRadioDefault1").is(":checked")) {
     //write your code  
       $(this).append('<input type="hidden" name="seletedtext" value="group" /> ');  

}
else{
   $(this).append('<input type="hidden" name="seletedtext" value="individual" /> ');  
}
   
    return true;
});



   $('#users').select2({
    width:"100%",
            tags: true,
            maximumInputLength: 10,
        });

</script>


@endsection
