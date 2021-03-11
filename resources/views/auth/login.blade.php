@extends('layouts.app')

@section('title', 'Login')

@section('content')


<!-- breadcrumb --> 
<div class="bc-bg">
    <div class="container">
        <div class="bc-link">
            <a href="/">
                <i class="fas fa-home"></i>
            </a>

            <span class="bc-sep"></span>

            <span>Login</span>
        </div>
    </div>
</div>
<!-- end -->

<!-- page content -->
<div class="bg-white">
    <div class="container">
        <div class="row">

            <!-- features -->
            <div class="col-sm-3">
            </div>

            <!-- checkout -->
            <div class="col-sm-6">
                <div class="checkout">
                    <div class="car">
                        <div class="m-4">
                            <!-- summarry -->
                            <div>
                                <h3>Login</h3>
                                <hr/>
                            </div>
                            <!-- end --> 

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <!-- re-purpose for forgot passord // update breadcrumb -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Email address</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Password</label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-block p-btn">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- features -->
            <div class="col-sm-3">
            </div>
        </div>
    </div>
</div>
<!-- end --> 

@endsection
