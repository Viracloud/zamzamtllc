@php($bg = ['gray' => 'section-gray', 'dark' => 'section-dark'][$s->field('background')] ?? '')
<section class="section {{ $bg }}">
    <div class="container split-grid">
        <div class="reveal">
            @if($s->kicker)<span class="kicker">{{ cms_text($s->kicker) }}</span>@endif
            @if($s->title)<h2 class="title">{{ cms_text($s->title) }}</h2>@endif
            @if($s->body)<p class="lead">{{ cms_text($s->body) }}</p>@endif
            @if($s->field('show_contact'))
                @include('front.partials.contact-box')
            @endif
        </div>

        @include('front.forms.quote', ['return' => $page->key ?? 'quote'])
    </div>
</section>
