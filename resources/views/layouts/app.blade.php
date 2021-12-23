<!Doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="user-id" content="{{ Auth::id() }}">
  <title>@yield('title', '勤怠管理')</title>
  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>
  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
  <!-- Styles -->
  <link rel="stylesheet" href="{{ asset('css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/vendor.bundle.base.css') }}">
  <link href="{{ asset('css/template.css') }}" rel="stylesheet">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
</head>
<body id="body">
  @auth
  <div id="app">
    <div class="container-scroller">
      <header-component :auth_user="{{ json_encode(Auth::user()) }}"></header-component>
      <div class="container-fluid page-body-wrapper">
        <sidebar-component :auth_user="{{ json_encode(Auth::user()) }}"></sidebar-component>
        <div class="main-panel">
          @yield('content')
          <footer-component></footer-component>
        </div>
      </div>
    </div></div>
  @endauth

  @guest
  @yield('content')
  @endguest

</body>
</html>
