{{-- Inner-page banner. $crumbs (optional) adds breadcrumb levels between Home and the title. --}}
@php($heroImage = optional(\App\Models\Section::where('page', 'home')->where('type', 'hero')->first())->image)
<section class="page-hero" style="background-image: url('{{ cms_image($s->image, $heroImage ?: 'assets/front/img/hero.jpg') }}')">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            @foreach(($crumbs ?? []) as $label => $url)
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i><a href="{{ $url }}">{{ $label }}</a>
            @endforeach
            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i><span aria-current="page">{{ cms_text($s->title) }}</span>
        </nav>
        <h1>{{ cms_text($s->title) }}</h1>
        @if($s->subtitle)<p>{{ cms_text($s->subtitle) }}</p>@endif
    </div>
</section>
