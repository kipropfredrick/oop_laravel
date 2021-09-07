@extends('backoffice.app')

@section('content')

<div class="">
		         @if (session()->has('success'))

				<div class="alert alert-success fade show" role="alert">
					{{ session()->get('success') }}
				</div>

				@elseif (session()->has('error'))

				<div class="alert alert-danger fade show" role="alert">
					{{ session()->get('error') }}
				</div>

			@endif

<!-- The text field -->
<div class="card p-3 row ">
<h3>Settings - API Key</h3>
<p>An API Key helps us authenticate requests that you make to our APIs.</p>

	<textarea class="w-50" id="myInput" rows="5">{{$encrypted}}</textarea>
		<button onclick="myFunction()" class="col-1 mt-1 btn btn-secondary" >Copy Key</button>
        
</div>
		</div>

		<script type="text/javascript">
			function myFunction() {
			
			 var $temp = $("<input>");
    $("body").append($temp);
    $temp.val('{{$encrypted}}').select();
    document.execCommand("copy");
    alert("copied to clipboard")
    $temp.remove();
}
		</script>
@endsection
