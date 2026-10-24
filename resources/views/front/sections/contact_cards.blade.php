<section class="section section-tight">
    <div class="container contact-cards">
        @foreach($s->group('cards') as $item)
            @php($tag = $item->link ? 'a' : 'div')
            <{{ $tag }} @if($item->link) href="{{ cms_url($item->link) }}" @endif class="card contact-card reveal">
                @if($item->icon)<div class="contact-icon"><i class="fa-solid {{ $item->icon }}"></i></div>@endif
                <span>{{ cms_text($item->subtitle) }}</span>
                <strong>{!! nl2br(e(cms_text($item->title))) !!}</strong>
            </{{ $tag }}>
        @endforeach
    </div>
</section>
