<!DOCTYPE html>
<html dir="ltr" lang="en-GB" class="background-fixed">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="robots" content="max-image-preview:large">
    <link rel="canonical" href="{{ url('/') }}">
    <title>@yield('title', 'LDC Courier')</title>
    @hasSection('meta_description')
        <meta name="description" content="@yield('meta_description')">
    @endif

    <link rel="icon" href="{{ asset('assets/images/2025/11/cropped-logo_black_little-32x32.png') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('assets/images/2025/11/cropped-logo_black_little-192x192.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/2025/11/cropped-logo_black_little-180x180.png') }}">

    <link rel="stylesheet" href="{{ asset('assets/wp-content/litespeed/css/342595e036c3c420bdbafddab4b4e3e2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/images/elementor/css/post-1042.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/images/elementor/css/post-1471.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/images/elementor/css/post-1496.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/images/elementor/css/post-1920.css') }}">

    @stack('styles')
</head>
<body class="@yield('body_class')">
    @include('layouts.header')

    <div id="content" class="site-content">
        @yield('content')
    </div>

    @include('layouts.footer')

    @php
        $elementorFrontendConfig = view('layouts.partials.home-elementor-frontend-config')->render();
        $elementorProConfig = view('layouts.partials.home-elementor-pro-config')->render();
        $wprConfigScript = view('layouts.partials.home-wpr-config')->render();
    @endphp

    @include('layouts.partials.home-scripts')

    <span id="elementor-device-mode" class="elementor-screen-only"></span>
    <svg style="display: none;" class="e-font-icon-svg-symbols"></svg>

    @stack('scripts')
</body>
</html>
