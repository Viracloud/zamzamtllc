<section class="hero" style="background-image: url('{{ cms_image($s->image, 'assets/front/img/hero.jpg') }}')">
    <div class="container">
        @if($s->kicker)<span class="kicker">{{ cms_text($s->kicker) }}</span>@endif
        <h1>{{ cms_text($s->title) }}@if($s->subtitle)<span>{{ cms_text($s->subtitle) }}</span>@endif</h1>
        @if($s->body)<p class="hero-text">{{ cms_text($s->body) }}</p>@endif
        @if($s->button_text || $s->button2_text)
        <div class="hero-actions">
            @if($s->button_text)
                <a href="{{ cms_url($s->button_link) }}" class="btn btn-primary">{{ cms_text($s->button_text) }} <i class="fa-solid fa-arrow-right"></i></a>
            @endif
            @if($s->button2_text)
                <a href="{{ cms_url($s->button2_link) }}" class="btn btn-outline-light">
                    @if(str_contains((string) $s->button2_link, 'phone') || str_starts_with((string) $s->button2_link, 'tel:'))<i class="fa-solid fa-phone"></i>@endif
                    {{ cms_text($s->button2_text) }}
                </a>
            @endif
        </div>
        @endif
    </div>
</section>

@if($s->group('features')->isNotEmpty())
<div class="feature-strip">
    <div class="container">
        <div class="feature-strip-inner" style="--cols: {{ min(4, $s->group('features')->count()) }}">
            @foreach($s->group('features') as $item)
            <div class="feature">
                @if($item->icon)<i class="fa-solid {{ $item->icon }}"></i>@endif
                <div><strong>{{ cms_text($item->title) }}</strong><span>{{ cms_text($item->subtitle) }}</span></div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
