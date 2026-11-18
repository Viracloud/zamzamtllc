@php($set = $global_setting)
@php($ogImage = cms_hero_image())
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php($metaTitle = trim($__env->yieldContent('title')) ?: $set->company_name . ' | Dry Van & Full Truckload Carrier')
    @php($metaDescription = trim($__env->yieldContent('description')) ?: $set->company_name . ' provides reliable dry van and full truckload transportation across all 48 states. Request a quote or apply to drive.')
    @php($ogImageUrl = $__env->yieldContent('og_image') ?: ($set->og_image ? asset('uploads/' . $set->og_image) : cms_image($ogImage, 'assets/front/img/hero.jpg', false)))
    @php($noindex = $__env->yieldContent('noindex') === '1')
    <title>{!! $metaTitle !!}</title>
    <meta name="description" content="{!! $metaDescription !!}">
    <meta name="robots" content="{{ $noindex ? 'noindex, follow' : 'index, follow, max-image-preview:large, max-snippet:-1' }}">
    <link rel="canonical" href="{{ url()->current() }}">
    @if($set->google_site_verification)
    <meta name="google-site-verification" content="{{ $set->google_site_verification }}">
    @endif
    @if($set->bing_site_verification)
    <meta name="msvalidate.01" content="{{ $set->bing_site_verification }}">
    @endif

    {{-- Social sharing (Facebook, LinkedIn, WhatsApp, X…) --}}
    <meta property="og:site_name" content="{{ $set->company_name }}">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{!! $metaTitle !!}">
    <meta property="og:description" content="{!! $metaDescription !!}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $ogImageUrl }}">
    <meta property="og:image:alt" content="{{ $set->company_name }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{!! $metaTitle !!}">
    <meta name="twitter:description" content="{!! $metaDescription !!}">
    <meta name="twitter:image" content="{{ $ogImageUrl }}">
    @if(request()->routeIs('home'))
    <link rel="preload" as="image" href="{{ cms_image($ogImage, 'assets/front/img/hero.jpg') }}" fetchpriority="high">
    @endif
    @include('front.layouts.schema')
    @stack('schema')
    <meta name="theme-color" content="{{ \App\Support\Theme::isHex($set->color_dark) ? $set->color_dark : \App\Support\Theme::DEFAULT_DARK }}">

    <link rel="icon" type="image/png" href="{{ $set->imageUrl('favicon', 'assets/front/img/brand/favicon-64.png') }}">
    <link rel="apple-touch-icon" href="{{ $set->favicon ? asset('uploads/'.$set->favicon) : asset('assets/front/img/brand/apple-touch-icon.png') }}">

    @if($set->google_analytics_id)
    {{-- Google Analytics 4 (Admin > SEO & Tracking) --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $set->google_analytics_id }}"></script>
    <script>window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', @json($set->google_analytics_id));</script>
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600;700;800&display=swap">
    <link rel="stylesheet" href="{{ asset('assets/front/vendor/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/style.css') }}?v={{ filemtime(public_path('assets/front/css/style.css')) }}">
    {{-- Theme colours chosen in Admin > Theme Colors --}}
    <style>{!! \App\Support\Theme::css($set->color_primary, $set->color_dark) !!}</style>
</head>
<body>
    <a href="#main" class="skip-link">Skip to content</a>

    @include('front.layouts.header')

    <main id="main">
        @yield('content')
    </main>

    @include('front.layouts.footer')

    <button type="button" class="scroll-top" aria-label="Back to top" title="Back to top"><i class="fa-solid fa-arrow-up" aria-hidden="true"></i></button>

    <script src="{{ asset('assets/front/js/main.js') }}?v={{ filemtime(public_path('assets/front/js/main.js')) }}" defer></script>
</body>
</html>
