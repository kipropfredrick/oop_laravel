@extends('front.app')

@section('content')

<div class="container">

 <section>

 <div style="margin-top:20px;margin-bottom:20px" class="row justify-content-center">

   <div class="col-md-8 card">
        <div style="color:#FFF;width:100%" class="card-header text-center bg-success">
            {{$message}}
        </div>
        <div class="card-body">
            <div class="text-center">
                 <h5>{{$product->product_name}}</h5>
                 <img style="height:300px;width:100%;object-fit:contain" src="/storage/images/{{$product->product_image}}" alt="">
            </div>
            <div><span><strong>Booking Reference : </strong></span>{{$booking_reference}} <span style="color:#9F6000;"><strong>(Use this as your payment account number.)</strong></span></div>
        </div>
   </div>

</div>

 </section>

</div>
@endsection