<meta charset="utf-8" />
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta name="description" content="@yield('meta_description', __('messages.site_meta_desc'))" />
<meta name="keywords" content="{{ __('messages.site_meta_keywords') }}" />
<meta name="author" content="{{ __('messages.site_name') }}" />
<meta name="robots" content="@yield('meta_robots', 'index, follow')" />
<meta property="og:title" content="@yield('og_title', __('messages.site_og_title'))" />
<meta property="og:description" content="@yield('og_description', __('messages.site_og_desc'))" />
<meta property="og:type" content="website" />
<meta property="og:image" content="{{ asset('images/logo.png') }}" />
<meta property="og:image:width" content="512" />
<meta property="og:image:height" content="512" />
<meta property="og:image:alt" content="{{ __('messages.site_logo_alt') }}" />
<meta property="og:locale" content="{{ session('locale', 'ar') === 'ar' ? 'ar_AR' : 'en_US' }}" />
<meta property="og:site_name" content="{{ __('messages.site_name') }}" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="@yield('twitter_title', __('messages.site_og_title'))" />
<meta name="twitter:description" content="@yield('twitter_description', __('messages.site_og_desc'))" />
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}" />
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}" />
<link rel="icon" type="image/png" sizes="512x512" href="{{ asset('images/favicon/android-chrome-512x512.png') }}" />
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}" />
<link rel="shortcut icon" href="{{ asset('images/favicon/favicon.ico') }}" type="image/x-icon" />
<link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}" />
<link
    href="{{ session('locale', 'ar') === 'ar' ? 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css' : 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' }}"
    rel="stylesheet" id="bootstrap-css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet" />
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
<link href="{{ asset('css/style.css') }}" rel="stylesheet" />
<link href="{{ asset('css/direction.css') }}" rel="stylesheet" />
@stack('styles')
<style>
    .text-secondary {
        color: rgb(48 48 48) !important;
    }

    p {
        color: rgb(48 48 48) !important;
    }

    .featured-card-desc {
        color: rgba(255, 255, 255, 0.78) !important;
    }

    .hero-trust-rating {
        color: #ffffff !important;
    }

    .page-hero p {
        color: rgba(255, 255, 255, 0.75) !important;
    }

    .public-page input:not([type="checkbox"]):not([type="radio"]),
    .public-page textarea,
    .public-page select,
    .form-control,
    .form-select {
        border-color: #5c5a5a !important;
    }
</style>
<title>@yield('title', __('messages.site_og_title'))</title>
