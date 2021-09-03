<!DOCTYPE html>
<html lang="en">
<head>
  @include('layouts.store.title-meta', ['title' => $title])
  @include('layouts.store.head-content', ["demo" => "creative"])
  <style>
    :root {
      --theme-deafult: <?= ($client_preference_detail) ? $client_preference_detail->web_color : '#ff4c3b' ?>;
      --top-header-color: <?= ($client_preference_detail) ? $client_preference_detail->site_top_header_color : '#4c4c4c' ?>;
    }
    a {
      color: <?= ($client_preference_detail) ? $client_preference_detail->web_color : '#ff4c3b' ?>;
    }
  </style>
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
<body class="{{$dark_mode}}" dir="{{session()->get('locale') == 'ar' ? 'rtl' : ''}}">
  
  @yield('content')
  @include('layouts.store/footer-content')
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
</body>
</html>