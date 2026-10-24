@php($set = $global_setting)
@php($ogImage = optional(\App\Models\Section::where('page', 'home')->where('type', 'hero')->first())->image)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $set->company_name . ' | Dry Van & Full Truckload Carrier')</title>
    <meta name="description" content="@yield('description', $set->company_name . ' provides reliable dry van and full truckload transportation across all 48 states. Request a quote or apply to drive.')">
    <meta property="og:title" content="@yield('title', $set->company_name)">
    <meta property="og:description" content="@yield('description')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:image" content="{{ cms_image($ogImage, 'assets/front/img/hero.jpg') }}">
    <meta name="theme-color" content="{{ \App\Support\Theme::isHex($set->color_dark) ? $set->color_dark : \App\Support\Theme::DEFAULT_DARK }}">

    <link rel="icon" type="image/png" href="{{ $set->imageUrl('favicon', 'assets/front/img/brand/favicon-64.png') }}">
    <link rel="apple-touch-icon" href="{{ $set->favicon ? asset('uploads/'.$set->favicon) : asset('assets/front/img/brand/apple-touch-icon.png') }}">

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

    <script src="{{ asset('assets/front/js/main.js') }}?v={{ filemtime(public_path('assets/front/js/main.js')) }}" defer></script>
</body>
</html>
