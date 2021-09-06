@extends('layouts.store', ['title' => __('Login')])
@section('content')
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<section class="wrapper-main mb-5 py-lg-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-lg-0 mb-3 text-center border-right pb-4">
                <h3 class="mb-2">{{ __('Login To Your Account') }}</h3>
                @if(session('preferences'))
                @if(session('preferences')->fb_login == 1 || session('preferences')->twitter_login == 1 || session('preferences')->google_login == 1 || session('preferences')->apple_login == 1)
                <ul class="social-links d-flex align-items-center mx-auto mb-4 mt-3">
                    @if(session('preferences')->google_login == 1)
                    <li>
                        <a href="{{url('auth/google')}}">
                            <img src="{{asset('front-assets/images/google.svg')}}" alt="">
                        </a>
                    </li>
                    @endif
                     @if(session('preferences')->fb_login == 1)
                    <li>
                        <a href="{{url('auth/facebook')}}"><img src="{{asset('front-assets/images/facebook.svg')}}" alt=""></a>
                    </li>
                    @endif
                    @if(session('preferences')->twitter_login)
                    <li>
                        <a href="{{url('auth/twitter')}}"><img src="{{asset('front-assets/images/twitter.svg')}}" alt=""></a>
                    </li>
                    @endif
                    @if(session('preferences')->apple_login == 1)
                    <li>
                        <a href="javascript::void(0);">
                            <img src="{{asset('front-assets/images/apple.svg')}}">
                        </a>
                    </li>
                    @endif
                </ul>
                <div class="divider_line m-auto">
                    <span>OR</span>
                </div>
                @endif
                @endif
                <div class="row mt-3 arabic-language">
                    <div class="offset-xl-2 col-xl-8 text-left">
                        <form name="login" id="login" action="{{route('customer.loginData')}}"  class="px-lg-4" method="post"> 
                            @csrf
                            <div class="form-group">
                                <label for="">{{ __('Email') }}</label>
                                <input type="email" class="form-control @if(isset($errors) && $errors->has('email')) is-invalid @endif" aria-describedby="" placeholder="{{ __('Email') }}" value="{{ old('email')}}" name="email">
                                <input type="hidden" value="" id="access_token" name="access_token">
                                @if($errors->first('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                @if(\Session::has('err_email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! \Session::get('err_email') !!}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Password') }}</label>
                                <input type="password" class="form-control @if(isset($errors) && $errors->has('password')) is-invalid @endif" name="password" placeholder="{{ __('Password') }}">
                                @if($errors->first('password') || \Session::has('Error'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                @if(\Session::has('err_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! \Session::get('err_password') !!}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <input type="hidden" name="device_type" value="web">
                                    <input type="hidden" name="device_token" value="web">
                                    <button type="submit" class="btn btn-solid submitLogin">{{ __('Login') }}</button>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <a class="forgot_btn" href="{{url('user/forgotPassword')}}">{{ __('Forgot Password?') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>    
            </div>
            <div class="col-lg-6 text-center">
                <h3 class="mb-md-5 mb-4">{{ __('New Customer') }}</h3>   
                <div class="create_box">
                    <h6>{{ __('Create An Account') }}</h6>
                    <p>{{ __('Sign up for a free account at our store. Registration is quick and easy. It allows you to be able to order from our shop. To start shopping click register.') }}</p>
                    <a href="{{route('customer.register')}}" class="btn btn-solid mt-4">{{ __('Create An Account') }}</a>
                </div>
            </div>
        </div>
    </div>
</section> 
@endsection
@section('script')
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase.js"></script>
<script>
        var firebaseConfig = {
            apiKey: "AIzaSyBppfct1EwlyUSAT9QKbiuo4e6HiMvV4Fs",
            authDomain: "royo-apps-1624361718359.firebaseapp.com",
            databaseURL: "https://royo-apps-1624361718359-default-rtdb.firebaseio.com",
            projectId: "royo-apps-1624361718359",
            storageBucket: "royo-apps-1624361718359.appspot.com",
            messagingSenderId: "1030919748357",
            appId: "1:1030919748357:web:9c29df0aca70b4f508156c",
            measurementId: "G-EFBPR3ZDKE"
        };
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();
        messaging
        .requestPermission()
        .then(function () {
            return messaging.getToken()
        })
        .then(function(token) {
            $("#access_token").val(token);
            console.log(token);
        })
        .catch(function (err) {
        });
</script>
@endsection