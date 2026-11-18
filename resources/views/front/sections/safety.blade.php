@php($bg = ['gray' => 'section-gray', 'dark' => 'section-dark'][$s->field('background')] ?? '')
<section class="section safety-section {{ $bg }}">
    <div class="container safety-grid">
        <div class="reveal">
            @if($s->kicker)<span class="kicker">{{ cms_text($s->kicker) }}</span>@endif
            @if($s->title)<h2 class="title">{{ cms_text($s->title) }}</h2>@endif
            @if($s->body)<p class="lead">{{ cms_text($s->body) }}</p>@endif

            @if($s->group('authority')->isNotEmpty())
            <div class="authority-grid">
                @foreach($s->group('authority') as $item)
                <div><span>{{ cms_text($item->title) }}</span><strong>{{ cms_text($item->value) }}</strong></div>
                @endforeach
            </div>
            @endif

            @if($s->button_text && $global_setting->fmcsaLink())
            <a href="{{ $global_setting->fmcsaLink() }}" class="link-arrow" target="_blank" rel="noopener">{{ cms_text($s->button_text) }} <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            @endif
        </div>

        <div class="safety-cards">
            @foreach($s->group('cards') as $item)
            <div class="safety-card reveal">
                @if($item->icon)<i class="fa-solid {{ $item->icon }}" aria-hidden="true"></i>@endif
                <h3>{{ cms_text($item->title) }}</h3>
                <p>{{ cms_text($item->body) }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
