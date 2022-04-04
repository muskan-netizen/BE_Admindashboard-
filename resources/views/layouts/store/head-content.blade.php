{{--<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/font-awesome.min.css')}}">
<link rel="stylesheet" media="all" type="text/css" href="{{asset('front-assets/css/themify-icons.css')}}">--}}


<link rel="stylesheet"  href="{{asset('css/aos.css')}}">
<link rel="stylesheet" media="all" type="text/css" href="{{asset('front-assets/css/icons-style.css')}}">
<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"media="all" id="bs-default-stylesheet" />



{{--<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/color1.css')}}" media="screen" id="color">
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/style.css')}}">--}}

<link rel="stylesheet" type="text/css" media="all" href="{{asset('front-assets/css/color1-style.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

{{--<link rel="stylesheet" type="text/css" media="all" href="{{asset('css/waitMe.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert2.min.css')}}">--}}

@if(isset($set_template)  && $set_template->template_id == 3 && $set_template->template_id == 1 )
@else
{{-- <link rel="stylesheet" type="text/css"  media="all" href="{{asset('front-assets/css/custom.css')}}">--}}
@endif

@if(isset($set_template)  && $set_template->template_id == 1)
{{--<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/custom-template-one.css')}}">--}}
@elseif(isset($set_template)  && $set_template->template_id == 3)



{{--<link rel="stylesheet"  href="{{asset('frontend/template_four/header/header.css')}}">
<link rel="stylesheet"  href="{{asset('frontend/template_four/footer/footer.css')}}">
<link rel="stylesheet"  href="{{asset('frontend/template_four/homepage/homepage.css')}}">
<link rel="stylesheet"  href="{{asset('frontend/template_four/resposive/resposive.css')}}">--}}
@endif


@if(isset($set_template)  && $set_template->template_id == 1)
<link rel="stylesheet"  href="{{asset('frontend/template_one/homepage/homepage.css')}}">
<link rel="stylesheet"  href="{{asset('frontend/template_one/header/header.css')}}">
<link rel="stylesheet"  href="{{asset('frontend/template_one/footer/footer.css')}}">
@elseif(isset($set_template)  && $set_template->template_id == 2)
<link rel="stylesheet"  href="{{asset('frontend/template_two/homepage/homepage.css')}}">
<link rel="stylesheet"  href="{{asset('frontend/template_two/header/header.css')}}">
<link rel="stylesheet"  href="{{asset('frontend/template_two/footer/footer.css')}}">
@elseif(isset($set_template)  && $set_template->template_id == 3)
<link rel="stylesheet"  href="{{asset('frontend/template_three/homepage/homepage.css')}}">
<link rel="stylesheet"  href="{{asset('frontend/template_three/header/header.css')}}">
<link rel="stylesheet"  href="{{asset('frontend/template_three/footer/footer.css')}}">
<link rel="stylesheet"  href="{{asset('frontend/template_three/homepage/inner_page.css')}}">
<link href="{{asset('assets/css/thiredtemplate.css')}}" rel="stylesheet" type="text/css" />
@elseif(isset($set_template)  && $set_template->template_id == 4)
<link rel="stylesheet"  href="{{asset('frontend/template_four/homepage/homepage.css')}}">
<link rel="stylesheet"  href="{{asset('frontend/template_four/header/header.css')}}">
<link rel="stylesheet"  href="{{asset('frontend/template_four/footer/footer.css')}}">
@endif



<meta name="_token" content="{{ csrf_token() }}">
@yield('css-links')
