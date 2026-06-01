{{-- Screen-reader-only nav: matches existing H1 pattern; gives crawlers direct links to every sitemap URL (canonical paths, no trailing slash). --}}
<nav class="elementor-screen-only" aria-label="Site pages">
@foreach (config('seo.pages', []) as $page)
    @if (! empty($page['path']) && $page['path'] !== '/')
    <a href="{{ $page['path'] }}">{{ $page['breadcrumb'] ?? 'Page' }}</a>
    @endif
@endforeach
</nav>
