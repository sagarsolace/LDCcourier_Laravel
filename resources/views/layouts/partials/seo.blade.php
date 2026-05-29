@php
    use Illuminate\Support\Str;

    $routeName = request()->route()?->getName();
    $pages = config('seo.pages', []);
    $page = $pages[$routeName] ?? [];
    $siteUrl = rtrim(config('seo.site_url'), '/');
    $path = $page['path'] ?? '/' . ltrim(request()->path(), '/');
    $canonical = $siteUrl . ($path === '/' ? '/' : '/' . trim($path, '/'));
    $title = $page['title'] ?? (trim($__env->yieldContent('title')) ?: config('seo.site_name'));
    $description = $page['description'] ?? (trim($__env->yieldContent('meta_description')) ?: config('seo.organization.description'));
    $imagePath = $page['image'] ?? config('seo.default_image');
    $image = Str::startsWith($imagePath, ['http://', 'https://']) ? $imagePath : $siteUrl . '/' . ltrim($imagePath, '/');
    $logoPath = config('seo.logo');
    $logo = Str::startsWith($logoPath, ['http://', 'https://']) ? $logoPath : $siteUrl . '/' . ltrim($logoPath, '/');
    $robots = $page['robots'] ?? 'max-image-preview:large';
    $breadcrumbName = $page['breadcrumb'] ?? $title;
    $organization = config('seo.organization');
    $services = $page['services'] ?? [];
    $areasServed = [
        ['@type' => 'City', 'name' => 'Kidderminster'],
        ['@type' => 'City', 'name' => 'Worcester'],
        ['@type' => 'City', 'name' => 'Birmingham'],
        ['@type' => 'Country', 'name' => 'United Kingdom'],
    ];

    $graph = [
        [
            '@type' => 'BreadcrumbList',
            '@id' => $canonical . '#breadcrumblist',
            'itemListElement' => $path === '/'
                ? [[
                    '@type' => 'ListItem',
                    '@id' => $siteUrl . '#listItem',
                    'position' => 1,
                    'name' => 'Home',
                ]]
                : [
                    [
                        '@type' => 'ListItem',
                        '@id' => $siteUrl . '#listItem',
                        'position' => 1,
                        'name' => 'Home',
                        'item' => $siteUrl . '/',
                    ],
                    [
                        '@type' => 'ListItem',
                        '@id' => $canonical . '#listItem',
                        'position' => 2,
                        'name' => $breadcrumbName,
                    ],
                ],
        ],
        [
            '@type' => 'Organization',
            '@id' => $siteUrl . '/#organization',
            'name' => $organization['name'],
            'description' => $organization['description'],
            'url' => $siteUrl . '/',
            'telephone' => $organization['telephone'],
            'email' => $organization['email'] ?? null,
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $organization['address']['addressLocality'] ?? 'Kidderminster',
                'addressRegion' => $organization['address']['addressRegion'] ?? 'Worcestershire',
                'addressCountry' => $organization['address']['addressCountry'] ?? 'GB',
            ],
            'areaServed' => $areasServed,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $logo,
                '@id' => $siteUrl . '/#organizationLogo',
            ],
            'image' => [
                '@id' => $siteUrl . '/#organizationLogo',
            ],
        ],
        [
            '@type' => 'WebSite',
            '@id' => $siteUrl . '/#website',
            'url' => $siteUrl . '/',
            'name' => config('seo.site_name'),
            'publisher' => [
                '@id' => $siteUrl . '/#organization',
            ],
        ],
        [
            '@type' => $page['schema_type'] ?? 'WebPage',
            '@id' => $canonical . '#webpage',
            'url' => $canonical,
            'name' => $title,
            'description' => $description,
            'isPartOf' => [
                '@id' => $siteUrl . '/#website',
            ],
            'breadcrumb' => [
                '@id' => $canonical . '#breadcrumblist',
            ],
            'image' => [
                '@type' => 'ImageObject',
                'url' => $image,
                'width' => config('seo.default_image_width'),
                'height' => config('seo.default_image_height'),
            ],
            'areaServed' => $areasServed,
        ],
    ];

    if (! empty($services)) {
        $graph[3]['about'] = array_map(static fn ($service) => [
            '@type' => 'Service',
            'name' => $service,
            'provider' => [
                '@id' => $siteUrl . '/#organization',
            ],
            'areaServed' => $areasServed,
        ], $services);
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => $graph,
    ];

    $ogType = $path === '/' ? 'website' : 'article';
@endphp
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonical }}">
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta property="og:locale" content="{{ config('seo.locale') }}">
<meta property="og:site_name" content="{{ config('seo.site_name') }}">
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:image:secure_url" content="{{ $image }}">
<meta property="og:image:width" content="{{ config('seo.default_image_width') }}">
<meta property="og:image:height" content="{{ config('seo.default_image_height') }}">
@if ($ogType === 'article')
<meta property="article:published_time" content="2026-03-25T12:06:25+00:00">
<meta property="article:modified_time" content="2026-04-25T07:16:06+00:00">
@endif
<meta name="twitter:card" content="{{ config('seo.twitter_card') }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">
<script type="application/ld+json" class="aioseo-schema">@json($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>

@if (config('seo.ga4_id'))
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('consent', 'default', {
            'ad_storage': 'denied',
            'ad_user_data': 'denied',
            'ad_personalization': 'denied',
            'analytics_storage': 'denied'
        });
    </script>
@endif

@if (config('seo.cookieyes_id'))
    <script id="cookieyes" type="text/javascript" src="https://cdn-cookieyes.com/client_data/{{ config('seo.cookieyes_id') }}/script.js"></script>
@endif

@if (config('seo.ga4_id'))
    <script type="text/plain" data-cookieyes="cookieyes-analytics" async src="https://www.googletagmanager.com/gtag/js?id={{ config('seo.ga4_id') }}"></script>
    <script type="text/plain" data-cookieyes="cookieyes-analytics">
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config('seo.ga4_id') }}');
    </script>
@endif
