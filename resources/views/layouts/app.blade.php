<!DOCTYPE html>
<html dir="ltr" lang="en-GB" class="background-fixed">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    @include('layouts.partials.seo')

    <link rel="icon" href="{{ asset('assets/images/2025/11/cropped-logo_footer-32x32.png') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('assets/images/2025/11/cropped-logo_footer-192x192.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/2025/11/cropped-logo_footer-180x180.png') }}">
    <link rel="preload" href="{{ asset('assets/wp-content/fonts/onest/gNMKW3F-SZuj7xmf-HY.woff2') }}" as="font" type="font/woff2" crossorigin>
    @if (request()->routeIs('contact-us'))
        <link rel="dns-prefetch" href="//maps.google.com">
        <link rel="preconnect" href="https://maps.google.com" crossorigin>
    @endif

    <link rel="stylesheet" href="{{ asset('assets/wp-content/litespeed/css/342595e036c3c420bdbafddab4b4e3e2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/images/elementor/css/post-1042.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/images/elementor/css/post-1471.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/images/elementor/css/post-1496.css') }}">

    @php
        $bodyClass = trim($__env->yieldContent('body_class'));
        preg_match('/elementor-page-(\d+)/', $bodyClass, $elementorPageMatch);
        $elementorPageId = $elementorPageMatch[1] ?? null;
    @endphp

    @if ($elementorPageId && $elementorPageId !== '1920')
        <link rel="stylesheet" href="{{ asset('assets/images/elementor/css/post-' . $elementorPageId . '.css') }}">
    @endif

    @if ($elementorPageId === '1920' || request()->is('/'))
        <link rel="stylesheet" href="{{ asset('assets/images/elementor/css/post-1920.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/responsive-overrides.css') }}">

    @stack('styles')
</head>
<body class="{{ $bodyClass }}">
    @include('layouts.header')

    <div id="content" class="site-content">
        @yield('content')
    </div>

    @include('layouts.footer')

    @include('layouts.partials.scripts')

    <span id="elementor-device-mode" class="elementor-screen-only"></span>
    <svg style="display: none;" class="e-font-icon-svg-symbols"></svg>

    @stack('scripts')
</body>
</html>
