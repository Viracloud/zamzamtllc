<section class="section">
    <div class="container about-grid">
        <div class="about-media reveal">
            <img src="{{ cms_image($s->image, 'assets/front/img/about.jpg') }}" alt="{{ cms_text($s->title) }}" loading="lazy" width="1000" height="800">
            @if($s->field('badge_title'))
            <div class="since-badge">
                <strong>{{ cms_text($s->field('badge_title')) }}</strong>
                @if($s->field('badge_text'))<span>{{ cms_text($s->field('badge_text')) }}</span>@endif
            </div>
            @endif
        </div>

        <div class="reveal">
            @if($s->kicker)<span class="kicker">{{ cms_text($s->kicker) }}</span>@endif
            @if($s->title)<h2 class="title">{{ cms_text($s->title) }}</h2>@endif
            @foreach(cms_paragraphs($s->body) as $paragraph)
                <p>{{ cms_text($paragraph) }}</p>
            @endforeach
            @if($s->group('checklist')->isNotEmpty())
            <ul class="check-list">
                @foreach($s->group('checklist') as $item)
                <li><i class="fa-solid fa-circle-check"></i> {{ cms_text($item->title) }}</li>
                @endforeach
            </ul>
            @endif
            @if($s->button_text)
            <a href="{{ cms_url($s->button_link) }}" class="btn btn-dark" style="margin-top: 32px">{{ cms_text($s->button_text) }} <i class="fa-solid fa-arrow-right"></i></a>
            @endif
        </div>
    </div>
</section>
