<section class="section section-tight">
    <div class="container">
        @if($s->kicker || $s->title)
        <div class="section-head text-center reveal">
            @if($s->kicker)<span class="kicker">{{ cms_text($s->kicker) }}</span>@endif
            @if($s->title)<h2 class="title">{{ cms_text($s->title) }}</h2>@endif
        </div>
        @endif
        <ol class="steps">
            @foreach($s->group('steps') as $item)
            <li class="step reveal">
                <span class="step-num">{{ $loop->iteration }}</span>
                {{-- Without a section title, steps are the first headings after the page h1 --}}
                <div><{{ $s->title ? 'h3' : 'h2' }} class="step-title">{{ cms_text($item->title) }}</{{ $s->title ? 'h3' : 'h2' }}><p>{{ cms_text($item->body) }}</p></div>
            </li>
            @endforeach
        </ol>
    </div>
</section>
