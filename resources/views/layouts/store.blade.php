@php
$set_template = \App\Models\WebStylingOption::where('web_styling_id',1)->where('is_selected',1)->first();
$set_common_business_type = $client_preference_detail->business_type??'';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <style>
    body{font-size:16px;position:initial}.site-header{width:100%;top:0;left:0;background:#fff;position:fixed;-webkit-transition:all .5s ease-in-out;-moz-transition:all .5s ease-in-out;-ms-transition:all .5s ease-in-out;-o-transition:all .5s ease-in-out;transition:all .5s ease-in-out;z-index:11}.top-header.site-topbar{background:var(--top-header-color);padding:5px 0;display:list-item}.top-header .header-dropdown li{padding:0 20px 0 0}.onhover-dropdown .onhover-show-div{display:none}.top-header .header-dropdown li{padding:0 20px 0 0}.shimmer_effect{overflow:hidden}.menu-slider.items-center .slick-track{justify-content:space-between}.menu-slider .slick-track{max-width:100vw!important;width:100%!important;display:flex;justify-content:center;margin:auto!important}.pixelstrap li a,.product-card-box{position:relative;-webkit-transform:scale(.95);transform:scale(.95);-webkit-transition:.3s ease-in-out;transition:.3s ease-in-out}.pixelstrap a,.pixelstrap a:active,.pixelstrap a:hover,.pixelstrap li>a{padding:0 7px 10px;text-align:center;width:100px;white-space:normal}
  </style>
  @include('layouts.store.title-meta', ['title' => $title])
  @include('layouts.store.head-content', ["demo" => "creative"])
  <style>
    :root {--theme-deafult: <?= ($client_preference_detail) ? $client_preference_detail->web_color : '#ff4c3b' ?>;--top-header-color: <?= ($client_preference_detail) ? $client_preference_detail->site_top_header_color : '#4c4c4c' ?>;}
    a {color: <?= ($client_preference_detail) ? $client_preference_detail->web_color : '#ff4c3b' ?>;}
  </style>
   @yield('css')
</head>
@php
$dark_mode = '';
if($client_preference_detail->show_dark_mode == 0){
  $dark_mode = '';
}
else if($client_preference_detail->show_dark_mode == 1){
  $dark_mode = 'dark';
}
else if($client_preference_detail->show_dark_mode == 2){
  if(session()->has('config_theme')){
    $dark_mode = session()->get('config_theme');
  }
  else{
    $dark_mode = '';
  }
}
@endphp
@if($set_common_business_type == 'taxi')
  <style type="text/css">
  .cabbooking-loader {width: 30px;height: 30px;animation: loading 1s infinite ease-out;margin: auto;border-radius: 50%;background-color: red;}
  @keyframes loading {0% {transform: scale(1);}100% {transform: scale(8);opacity: 0;}}
  .site-topbar,.main-menu.d-block{display: none !important;}
  .cab-booking-header img.img-fluid {height: 50px;}
  .cab-booking-header{display: block !important;}
  .container .main-menu .d-block{display: none;}
  @media(max-width: 991px){
    .cab-booking-header img.img-fluid {height: auto !important;}
  }


@media(max-width:767px){.cab-booking-header a.navbar-brand.mr-0 {margin: 10px auto 0;text-align: center;display: block;}}

  </style>
  @else
  <style>
    .cab-booking-header{display: none;}
 </style>
@endif
<body  class="{{$dark_mode}}{{ Request::is('category/cabservice') ? 'cab-booking-body' : '' }}" dir="{{session()->get('locale') == 'ar' ? 'rtl' : ''}}">
  @yield('content')
  @if(isset($set_template)  && $set_template->template_id == 1)
  @include('layouts.store/footer-content-template-one')
  @elseif(isset($set_template)  && $set_template->template_id == 2)
  @include('layouts.store/footer-content')
  @else
  @endif
  @include('layouts.store/footer')
  <div class="loader_box" style="display: none;">
    <div class="spinner-border text-danger m-2 showLoader" role="status"></div>
  </div>
  <div class="spinner-overlay">
    <div class="page-spinner">
        <div class="circle-border">
            <div class="circle-core"></div>
        </div>
    </div>
  </div>
  @yield('script')
  @if($client_preference_detail->hide_nav_bar == 1 || $set_common_business_type == 'taxi')
  <script>
    $('.main-menu').addClass('d-none').removeClass('d-block');
    $('.menu-navigation').addClass('d-none').removeClass('d-block');
  </script>
  @endif
  @if(isset($set_template)  && $set_template->template_id == 1)
  <script src="{{asset('front-assets/js/custom-template-one.js')}}"></script>
  @endif
   <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-5LPF1QP3Y3"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'G-5LPF1QP3Y3');
</script>

</body>
</html>
