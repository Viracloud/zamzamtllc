<section class="stats-band" style="background-image: url('{{ cms_image($s->image, 'assets/front/img/stats.jpg') }}')" aria-label="Company at a glance">
    <div class="container stats-grid" style="--cols: {{ max(1, min(4, $s->group('stats')->count())) }}">
        @foreach($s->group('stats') as $item)
        <div class="stat">
            @if($item->icon)<i class="fa-solid {{ $item->icon }}"></i>@endif
            <div><strong>{{ cms_text($item->value) }}</strong><span>{{ cms_text($item->subtitle) }}</span></div>
        </div>
        @endforeach
    </div>
</section>
