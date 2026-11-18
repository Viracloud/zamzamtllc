@php($bg = ['gray' => 'section-gray', 'dark' => 'section-dark'][$s->field('background')] ?? '')
<section class="section {{ $bg }}">
    <div class="container about-grid {{ $s->field('image_position') === 'right' ? 'image-right' : '' }}">
        @if($s->image)
        <div class="about-media plain reveal">
            <img src="{{ cms_image($s->image) }}" alt="{{ cms_text($s->title) }}" loading="lazy">
        </div>
        @endif

        <div class="reveal">
            @if($s->kicker)<span class="kicker">{{ cms_text($s->kicker) }}</span>@endif
            @if($s->title)<h2 class="title">{{ cms_text($s->title) }}</h2>@endif
            @foreach(cms_paragraphs($s->body) as $paragraph)
                <p>{{ cms_text($paragraph) }}</p>
            @endforeach
            @if($s->group('checklist')->isNotEmpty())
            <ul class="check-list">
                @foreach($s->group('checklist') as $item)
                <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i> {{ cms_text($item->title) }}</li>
                @endforeach
            </ul>
            @endif
            @if($s->button_text)
            <a href="{{ cms_url($s->button_link) }}" class="btn {{ $bg === 'section-dark' ? 'btn-primary' : 'btn-dark' }}" style="margin-top: 28px">{{ cms_text($s->button_text) }} <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            @endif
        </div>
    </div>
</section>
