{{-- Inner-page banner. $crumbs (optional) adds breadcrumb levels between Home and the title. --}}
@php
    $heroImage = cms_hero_image();
    $crumbList = collect([cms_label('lbl_home') => route('home')])->merge($crumbs ?? [])->put(cms_text($s->title), url()->current());
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $crumbList->keys()->map(fn ($name, $i) => ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $name, 'item' => $crumbList[$name]])->all(),
    ];
@endphp
@push('schema')
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush
<section class="page-hero" style="background-image: url('{{ cms_image($s->image, $heroImage ?: 'assets/front/img/hero.jpg') }}')">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">{{ cms_label('lbl_home') }}</a>
            @foreach(($crumbs ?? []) as $label => $url)
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i><a href="{{ $url }}">{{ $label }}</a>
            @endforeach
            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i><span aria-current="page">{{ cms_text($s->title) }}</span>
        </nav>
        <h1>{{ cms_text($s->title) }}</h1>
        @if($s->subtitle)<p>{{ cms_text($s->subtitle) }}</p>@endif
    </div>
</section>
