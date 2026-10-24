@php($bg = ['gray' => 'section-gray', 'dark' => 'section-dark'][$s->field('background')] ?? '')
<section class="section {{ $bg }}">
    <div class="container">
        @if($s->kicker || $s->title || $s->subtitle)
        <div class="section-head text-center reveal">
            @if($s->kicker)<span class="kicker">{{ cms_text($s->kicker) }}</span>@endif
            @if($s->title)<h2 class="title">{{ cms_text($s->title) }}</h2>@endif
            @if($s->subtitle)<p class="lead">{{ cms_text($s->subtitle) }}</p>@endif
        </div>
        @endif
        <div class="explore-grid">
            @foreach($s->group('cards') as $item)
                @php($tag = $item->link ? 'a' : 'div')
                <{{ $tag }} @if($item->link) href="{{ cms_url($item->link) }}" @endif class="card explore-card reveal">
                    @if($item->icon)<div class="equip-icon"><i class="fa-solid {{ $item->icon }}"></i></div>@endif
                    @if($item->subtitle)<span class="explore-kicker">{{ cms_text($item->subtitle) }}</span>@endif
                    <h3>{{ cms_text($item->title) }}</h3>
                    @if($item->body)<p>{{ cms_text($item->body) }}</p>@endif
                    @if($item->link && $item->value)<span class="link-arrow">{{ cms_text($item->value) }} <i class="fa-solid fa-arrow-right"></i></span>@endif
                </{{ $tag }}>
            @endforeach
        </div>
    </div>
</section>
