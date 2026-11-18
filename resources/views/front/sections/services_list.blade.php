<section class="section">
    <div class="container">
        <div class="service-list">
            @foreach(cms_services() as $service)
            <article class="service-row reveal">
                <a href="{{ $service->url() }}" class="service-row-media" tabindex="-1" aria-hidden="true">
                    <img src="{{ cms_image($service->image) }}" alt="{{ $service->image_alt }}" loading="lazy" width="800" height="520">
                </a>
                <div class="service-row-body">
                    @if($s->kicker)<span class="kicker">@if($service->icon)<i class="fa-solid {{ $service->icon }}" aria-hidden="true"></i>&nbsp; @endif{{ cms_text($s->kicker) }}</span>@endif
                    <h2><a href="{{ $service->url() }}" class="card-title-link">{{ $service->title }}</a></h2>
                    @if($service->paragraphs())<p>{{ cms_text($service->paragraphs()[0]) }}</p>@endif
                    <ul class="check-list">
                        @foreach(array_slice($service->featureList(), 0, (int) $s->field('feature_count', 4)) as $feature)
                        <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i> {{ cms_text($feature) }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ $service->url() }}" class="btn btn-dark">{{ cms_text($s->button_text ?: 'Learn More') }} <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
