<!DOCTYPE html>
<html lang="en">
<head>
  @include('layouts.store.title-meta', ['title' => $title])
  @include('layouts.store.head-content', ["demo" => "creative"])
  <style>
    :root {
      --theme-deafult: <?= ($client_preference_detail) ? $client_preference_detail->web_color : '#ff4c3b' ?>;
    }
    a {
      color: <?= ($client_preference_detail) ? $client_preference_detail->web_color : '#ff4c3b' ?>;
    }
  </style>
</head>
<body class="{{session()->has('config_theme') ? session()->get('config_theme') : ''}}" dir="{{session()->get('locale') == 'ar' ? 'rtl' : ''}}">
  
  @yield('content')
  @include('layouts.store/footer-content')
  @include('layouts.store/footer')
  <div class="loader_box" style="display: none;">
    <div class="spinner-border text-danger m-2 showLoader" role="status"></div>
  </div>
  @yield('script')
</body>
</html>