<section class="cta-band">
    <div class="container cta-inner">
        <div>
            @if($s->title)<h2>{{ cms_text($s->title) }}</h2>@endif
            @if($s->subtitle)<p>{{ cms_text($s->subtitle) }}</p>@endif
        </div>
        @if($s->button_text)
        <a href="{{ cms_url($s->button_link) }}" class="btn btn-dark">{{ cms_text($s->button_text) }} <i class="fa-solid fa-arrow-right"></i></a>
        @endif
    </div>
</section>
