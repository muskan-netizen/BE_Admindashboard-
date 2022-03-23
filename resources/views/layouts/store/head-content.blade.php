{{--<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/font-awesome.min.css')}}">
<link rel="stylesheet" media="all" type="text/css" href="{{asset('front-assets/css/themify-icons.css')}}">--}}
<link rel="stylesheet" media="all" type="text/css" href="{{asset('front-assets/css/icons-style.css')}}">
<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"media="all" id="bs-default-stylesheet" />
{{--<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/color1.css')}}" media="screen" id="color">--}}
{{--<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/style.css')}}">
<link rel="stylesheet" type="text/css" media="all" href="{{asset('front-assets/css/color1-style.css')}}">--}}

{{--<link rel="stylesheet" type="text/css" media="all" href="{{asset('css/waitMe.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert2.min.css')}}">--}}
@if(isset($set_template)  && $set_template->template_id == 3 && \Request::route()->getName() == 'userHome')
@else
<link rel="stylesheet" type="text/css"  media="all" href="{{asset('front-assets/css/custom.css')}}">
@endif
@if(isset($set_template)  && $set_template->template_id == 1)
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/custom-template-one.css')}}">
@elseif(isset($set_template)  && $set_template->template_id == 3)
<link href="{{asset('assets/css/thiredtemplate_.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet"  href="{{asset('css/aos.css')}}">
<link rel="stylesheet"  href="{{asset('frontend/template_three/header/header.css')}}">
<link rel="stylesheet"  href="{{asset('frontend/template_three/footer/footer.css')}}">
@endif
<meta name="_token" content="{{ csrf_token() }}">
@yield('css-links')
