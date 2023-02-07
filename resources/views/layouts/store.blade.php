@php
$set_template = \App\Models\WebStylingOption::where('web_styling_id',1)->where('is_selected',1)->first();
$set_common_business_type = $client_preference_detail->business_type??'';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  @include('layouts.store.title-meta')
  @include('layouts.store.head-content', ["demo" => "creative"])
</head>
@yield('customcss')

@yield('cssnew')
@php

$dark_mode = '';
if($client_preference_detail->show_dark_mode == 1){
  $dark_mode = 'dark';
}else if($client_preference_detail->show_dark_mode == 2){
  if(session()->has('config_theme')){
    $dark_mode = session()->get('config_theme');
  }
}

$body_class = "";
if(isset($set_template))
{
  if($set_template->template_id == 1)
    $body_class = "al_body_template_one";
  elseif($set_template->template_id == 2)
    $body_class = "al_body_template_two";
  elseif($set_template->template_id == 3)
    $body_class = "al_body_template_three";
  elseif($set_template->template_id == 4)
    $body_class = "al_body_template_four";
  elseif($set_template->template_id == 5)
    $body_class = "al_body_template_five";
  elseif($set_template->template_id == 6){
    $body_class = "al_body_template_six";
    if(Route::currentRouteName() == "customer.login" || Route::currentRouteName() == "customer.register"){
      $body_class =  $body_class. " login";
    }
    
  }
  elseif($set_template->template_id == 8)
    $body_class = "al_body_template_eight p2p-module"; //p2p-module class is required because in template 8 css fixed using this class
  elseif($set_template->template_id == 9)
    $body_class = "al_body_template_nine p2p-module";
}


@endphp
@include('layouts.shared.variables-constant-js')
@include('layouts.language')
@yield('headerJs')
<body  class="{{$dark_mode}}{{ Request::is('category/cabservice') ? 'cab-booking-body' : '' }} {{$body_class}} " dir="{{session()->get('locale') == 'ar' ? 'rtl' : ''}}">
<article id="page-container">
  <article id="content-wrap">
  @if(isset($set_template)  && ($set_template->template_id == 3 || $set_template->template_id == 6 || $set_template->template_id == 1 ))
    <article class="al_new_wrapper_design">
  @endif
    <header>
      
      <div class="mobile-fix-option_al"></div>
      @if(isset($set_template)  && $set_template->template_id == 1)
      @include('layouts.store/left-sidebar-template-one')
      @elseif(isset($set_template)  && $set_template->template_id == 2)
      @include('layouts.store/left-sidebar-template-two')
      @elseif(isset($set_template)  && $set_template->template_id == 3)
      @include('layouts.store/left-sidebar-template-three')
      @elseif(isset($set_template)  && $set_template->template_id == 4)
      @include('layouts.store/left-sidebar-template-four')
      @elseif(isset($set_template)  && $set_template->template_id == 5)
      @include('layouts.store/left-sidebar-template-five')
      @elseif(isset($set_template)  && $set_template->template_id == 6)
      @include('layouts.store/left-sidebar-template-six')
      @elseif(isset($set_template)  && $set_template->template_id == 8)
      @include('layouts.store/left-sidebar-template-eight')
      @elseif(isset($set_template)  && $set_template->template_id == 9)
      @include('layouts.store/left-sidebar-template-nine')
      @else
      @include('layouts.store/left-sidebar-template-one')
      @endif
    </header>

    @if(isset($set_template)  && $set_template->template_id == 4)
    @include('frontend.template_four.layouts.vendor_type')
    @endif

    @yield('content')
    @if(isset($set_template)  && $set_template->template_id == 1)
    @include('layouts.store/footer-content-template-one')
    @elseif(isset($set_template)  && $set_template->template_id == 2)
    @include('layouts.store/footer-content-template-two')
    @elseif(isset($set_template)  && $set_template->template_id == 3)
    @include('layouts.store/footer-content-template-three')
    @elseif(isset($set_template)  && $set_template->template_id == 4)
    @include('layouts.store/footer-content-template-four')
    @elseif(isset($set_template)  && $set_template->template_id == 5)
    @include('layouts.store/footer-content-template-five')
    @elseif(isset($set_template)  && $set_template->template_id == 6)
    @include('layouts.store/footer-content-template-six')
    @elseif(isset($set_template)  && $set_template->template_id == 8)
    @include('layouts.store/footer-content-template-eight')
    @elseif(isset($set_template)  && $set_template->template_id == 9)
    @include('layouts.store/footer-content-template-nine')
    @else
    @include('layouts.store/footer-content-template-one')
    @endif
    @include('layouts.store/footer')
</body>

</html>
