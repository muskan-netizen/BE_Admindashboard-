{{--<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/font-awesome.min.css')}}">
<link rel="stylesheet" media="all" type="text/css" href="{{asset('front-assets/css/themify-icons.css')}}">--}}
<link rel="stylesheet" media="all" type="text/css" href="{{asset('front-assets/css/icons-style.css')}}">
<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"media="all" id="bs-default-stylesheet" />
{{--<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/color1.css')}}" media="screen" id="color">--}}
{{--<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/style.css')}}">--}}
<link rel="stylesheet" type="text/css" media="all" href="{{asset('front-assets/css/color1-style.css')}}">
<link rel="stylesheet" type="text/css"  media="all" href="{{asset('front-assets/css/custom.css')}}">
{{--<link rel="stylesheet" type="text/css" media="all" href="{{asset('css/waitMe.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert2.min.css')}}">--}}
@if(in_array('yoco',$client_payment_options))
<script src="https://js.yoco.com/sdk/v1/yoco-sdk-web.js"></script>
@endif<meta name="_token" content="{{ csrf_token() }}">@yield('css')
