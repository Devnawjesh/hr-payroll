<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="keyword" content="madpos,pos,inventory,invoice,sales,ecommerce,product,stock,customer">
    <meta name="description" content="{{ $metaDescription ?? 'Zerithon authentication' }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('app.name', 'ZerithonHR') }}</title>
    @php
        $assetsBase = config('madpos_ui.assets_base', 'assets');
        $favicon = config('madpos_ui.favicon', 'assets/img/zerithon-logo.png');
        $logo = config('madpos_ui.logo', 'assets/img/zerithon-logo.png');
    @endphp
    <link rel="apple-touch-icon" href="{{ asset($favicon) }}">
    <link rel="icon" href="{{ asset($favicon) }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset($assetsBase.'/css/normalize.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset($assetsBase.'/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset($assetsBase.'/css/main.css') }}">
    @stack('styles')
</head>
<body>
    <div id="wrapper" class="wrapper-login">
        <div class="login-inner">
            <div class="auth-logo">
                <img src="{{ asset($logo) }}" alt="HR Payroll">
            </div>
            <div class="card mb-1">
                <div class="card-body p2050">
                    <div class="form-header">
                        <h5>{{ $heading ?? 'Authentication' }}</h5>
                        @isset($subtitle)
                            <p class="auth-subtitle">{{ $subtitle }}</p>
                        @endisset
                    </div>
                </div>
            </div>
            <div class="form-wrapper">
                @include('partials.flash')
                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ asset($assetsBase.'/js/vendor/modernizr-3.5.0.min.js') }}"></script>
    <script src="{{ asset($assetsBase.'/js/vendor/jquery-3.2.1.min.js') }}"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset($assetsBase.'/js/bootstrap5-bridge.js') }}"></script>
    @stack('scripts')
</body>
</html>
