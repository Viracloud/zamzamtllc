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

        <div class="equipment-grid">
            @foreach($s->group('cards') as $item)
            <article class="card equip-card reveal">
                @if($item->icon)<div class="equip-icon"><i class="fa-solid {{ $item->icon }}" aria-hidden="true"></i></div>@endif
                <h3>{{ cms_text($item->title) }}</h3>
                <ul class="spec-list">
                    @foreach(cms_lines($item->body) as $line)
                        @php([$label, $value] = array_pad(array_map('trim', explode('|', $line, 2)), 2, ''))
                        <li><span>{{ cms_text($label) }}</span><strong>{{ cms_text($value) }}</strong></li>
                    @endforeach
                </ul>
            </article>
            @endforeach
        </div>
    </div>
</section>
