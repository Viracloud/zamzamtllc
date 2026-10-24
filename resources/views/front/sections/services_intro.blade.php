@php($services = \App\Models\Service::active()->get())
<section class="section">
    <div class="container services-grid">
        <div class="reveal">
            @if($s->kicker)<span class="kicker">{{ cms_text($s->kicker) }}</span>@endif
            @if($s->title)<h2 class="title">{{ cms_text($s->title) }}</h2>@endif
            @if($s->body)<p class="lead">{{ cms_text($s->body) }}</p>@endif
            @if($s->button_text)<a href="{{ cms_url($s->button_link) }}" class="btn btn-primary">{{ cms_text($s->button_text) }}</a>@endif
        </div>

        <div class="service-cards">
            @foreach($services as $service)
            <article class="card service-card reveal">
                <a href="{{ $service->url() }}" tabindex="-1" aria-hidden="true">
                    <img src="{{ cms_image($service->image) }}" alt="{{ $service->image_alt }}" loading="lazy" width="800" height="520">
                </a>
                <div class="card-body">
                    <h3><a href="{{ $service->url() }}" class="card-title-link">{{ $service->title }}</a></h3>
                    <p>{{ cms_text($service->short) }}</p>
                    <a href="{{ $service->url() }}" class="link-arrow">{{ cms_text($s->field('card_link_text', 'Learn More')) }} <i class="fa-solid fa-arrow-right"></i><span class="sr-only"> about {{ $service->title }}</span></a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
