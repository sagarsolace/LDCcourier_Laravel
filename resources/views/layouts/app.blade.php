<!DOCTYPE html>
<html dir="ltr" lang="en-GB" class="background-fixed">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="robots" content="max-image-preview:large">
    <link rel="canonical" href="{{ url()->current() }}">

    <title>@yield('title', 'LDC Courier | UK Courier Services')</title>

    @hasSection('meta_description')
        <meta name="description" content="@yield('meta_description')">
    @endif

    <link rel="icon" href="{{ asset('assets/images/2025/11/cropped-logo_footer-32x32.png') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('assets/images/2025/11/cropped-logo_footer-192x192.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/2025/11/cropped-logo_footer-180x180.png') }}">

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
