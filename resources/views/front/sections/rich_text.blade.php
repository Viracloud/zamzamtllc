<section class="section">
    <div class="container">
        <div class="prose">
            @if($s->title)<h2 class="title">{{ cms_text($s->title) }}</h2>@endif
            {!! cms_html($s->body) !!}
        </div>
    </div>
</section>
