<script src="{{ asset('assets/wp-reference/jquery.min.js.download') }}" defer></script>
<script src="{{ asset('assets/wp-reference/frontend.min.js.download') }}" id="wpr-addons-js-js" defer></script>
<script src="{{ asset('assets/wp-content/plugins/elementor/assets/lib/jquery-numerator/jquery-numerator.min.js') }}" defer></script>

<script id="elementor-frontend-js-before">@include('layouts.partials.home-elementor-frontend-config')</script>
<script id="elementor-pro-frontend-js-before">@include('layouts.partials.home-elementor-pro-config')</script>
<script id="wpr-addons-js-js-extra">@include('layouts.partials.home-wpr-config')</script>

<script>
window.wpcf7 = window.wpcf7 || {
    api: {
        root: window.location.origin + '/wp-json/contact-form-7/v1/',
        namespace: 'contact-form-7/v1'
    },
    cached: 0
};
</script>
<script src="{{ asset('assets/wp-content/litespeed/js/35977dfbd0908a7a05e0ff2a632c4d2b.js') }}" defer></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var currentPath = window.location.pathname.replace(/\/+$/, '') || '/';

    document.querySelectorAll('.wpr-nav-menu a, .wpr-mobile-nav-menu a').forEach(function (link) {
        var linkPath = new URL(link.getAttribute('href'), window.location.origin).pathname.replace(/\/+$/, '') || '/';
        var isActive = linkPath === currentPath;
        var item = link.closest('.menu-item');

        link.classList.toggle('wpr-active-menu-item', isActive);
        link.toggleAttribute('aria-current', isActive);

        if (item) {
            item.classList.toggle('current-menu-item', isActive);
            item.classList.toggle('current_page_item', isActive);
        }
    });

    document.querySelectorAll('img[data-src]').forEach(function (img) {
        var src = img.getAttribute('data-src');
        if (src && (!img.getAttribute('src') || img.getAttribute('src').indexOf('data:image') === 0)) {
            img.setAttribute('src', src);
            if (img.dataset.srcset) {
                img.setAttribute('srcset', img.dataset.srcset);
            }
        }
    });
});
</script>
