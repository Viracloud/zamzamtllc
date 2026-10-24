<section class="section section-gray">
    <div class="container split-grid">
        <div class="reveal">
            @if($s->kicker)<span class="kicker">{{ cms_text($s->kicker) }}</span>@endif
            @if($s->title)<h2 class="title">{{ cms_text($s->title) }}</h2>@endif
            @if($s->body)<p class="lead">{{ cms_text($s->body) }}</p>@endif

            @foreach($s->group('pay') as $item)
            <div class="card pay-card" @if($loop->first) style="margin-top: 28px" @endif>
                <div class="pay-head">
                    <h3>{{ cms_text($item->title) }}</h3>
                    <div class="pay-rate">{{ cms_text($item->value) }} <small>{{ cms_text($item->subtitle) }}</small></div>
                </div>
                <ul class="bullet-list">
                    @foreach(cms_lines($item->body) as $line)
                    <li>{{ cms_text($line) }}</li>
                    @endforeach
                </ul>
            </div>
            @endforeach

            @if($s->group('requirements')->isNotEmpty())
            <div class="requirements">
                @if($s->field('requirements_title'))<h3>{{ cms_text($s->field('requirements_title')) }}</h3>@endif
                <ul class="check-list">
                    @foreach($s->group('requirements') as $item)
                    <li><i class="fa-solid fa-circle-check"></i> {{ cms_text($item->title) }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        @include('front.forms.application', ['return' => $page->key ?? 'careers'])
    </div>
</section>
