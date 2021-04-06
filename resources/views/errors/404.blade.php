@extends('layouts.app')

@section('title', 'Page not found')

@section('content')


    <div style="margin-bottom:100px" class="container">
       <div class="justify-content-center">
                <img class="img-fluid mx-auto d-block img-404" style="height:auto;width:50%;object-fit:contain;" src="{{asset('images/404.png')}}" alt="">

            <!-- <h1 class="text-center"><strong>Page Not Found</strong></h1> -->

            <div class="text-center">
                <a class="btn btn-primary mb-2 mt-2" href="/"> <i class="fa fa-home"></i>&nbsp; Go Home</a>
            </div>

            <div class="text-center">
                <a class="btn btn-primary" href="/dashboard/home"> <i class="fa fa-arrow-right"></i>&nbsp; Visit Dashboard</a>
            </div>
       </div>
    </div>

@endsection
