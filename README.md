# LDC Courier – Laravel 10 Frontend

WordPress site **https://ldccourier.co.uk** converted to a **Laravel 10** Blade frontend with **no WordPress runtime** and **no CMS database** in this project.

## Stack

| Item | Version / note |
|------|----------------|
| Laravel | 10.x |
| PHP | 8.2+ (project targets 8.2–8.4; your XAMPP PHP 8.2 is supported) |
| Database | Not used for the website (SQLite placeholder only for Laravel boot) |
| Source | `wordpress-site.zip` + SQL dump |

## Project structure

```
resources/views/
├── layouts/
│   ├── app.blade.php          # Main layout, Elementor CSS/JS
│   ├── header.blade.php       # Elementor header (ID 1471)
│   ├── footer.blade.php       # Elementor footer (ID 1496)
│   └── partials/scripts.blade.php
└── website/
    ├── home.blade.php
    ├── about-us-2.blade.php
    ├── our-services.blade.php
    ├── contact-us.blade.php
    ├── carpet-flooring-transport.blade.php
    ├── privacy-policy-2.blade.php
    ├── terms-conditions.blade.php
    ├── cookie-policy.blade.php
    └── blog.blade.php

public/assets/
├── images/                    # wp-content/uploads
└── wp-content/                # Elementor, LiteSpeed, plugins, theme assets
```

## URLs (same as live WordPress)

| Page | Route |
|------|--------|
| Home | `/` |
| About | `/about-us-2` |
| Services | `/our-services` |
| Contact | `/contact-us` |
| Carpet & flooring | `/carpet-flooring-transport` |
| Privacy | `/privacy-policy-2` |
| Terms | `/terms-conditions` |
| Cookies | `/cookie-policy` |
| Blog | `/blog` |

Redirects: `/about-us` → `/about-us-2`, `/services` → `/our-services`, trailing slashes normalized.

## Run locally (XAMPP)

1. Point the browser to:
   `http://localhost/ldccourier/public`
2. Or use Artisan:
   ```bash
   cd c:\xampp\htdocs\ldccourier
   php artisan serve
   ```
   Then open `http://127.0.0.1:8000`

## Re-export HTML from WordPress (optional)

A temporary WordPress copy lives in `c:\xampp\htdocs\ldccourier-wp` (local DB `ldc_wp`). To refresh Blade after design changes:

```bash
php c:\xampp\htdocs\ldccourier-wp\export-html.php
php c:\xampp\htdocs\ldccourier\wordpress-src\fix-blade-urls.php
```

Requires Apache/MySQL running and `http://localhost/ldccourier-wp` working.

## Performance cache commands

Use Laravel built-in caches in production:

```bash
composer run optimize:production
```

Clear caches during local development:

```bash
php artisan optimize:clear
```

## Notes

- **Contact form** now posts to Laravel and can send via Brevo API (`BREVO_API_KEY`).
- **Portal links** (`portal.ldccourier.co.uk`) remain external.
- **Tracking / FAQ** pages were not in the live sitemap; add routes/views if you create them in WordPress later.
- Large `wordpress-site.zip` is under `wordpress-src/` for reference only.

## License

Proprietary – LDC Express Courier Ltd.
