@php($set = $global_setting)
@php($footer = cms_global('footer'))
<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-about">
            @include('front.layouts.logo', ['class' => 'logo-footer'])
            @if($footer->body)<p>{{ cms_text($footer->body) }}</p>@endif
            @if($footer->field('authority_line'))<div class="footer-authority">{{ cms_text($footer->field('authority_line')) }}</div>@endif
            @if($socials = $set->socialLinks())
            <ul class="footer-social" aria-label="Follow us">
                @foreach($socials as [$label, $icon, $url])
                <li><a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ $label }}" title="{{ $label }}">@include('front.partials.social-icon', ['name' => $icon])</a></li>
                @endforeach
            </ul>
            @endif
        </div>

        <div>
            @if($footer->field('col1_title'))<h2 class="footer-title">{{ cms_text($footer->field('col1_title')) }}</h2>@endif
            <ul class="footer-links">
                @foreach($footer->group('links') as $link)
                <li><a href="{{ cms_url($link->link) }}">{{ cms_text($link->title) }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            @if($footer->field('col2_title'))<h2 class="footer-title">{{ cms_text($footer->field('col2_title')) }}</h2>@endif
            <ul class="footer-links">
                @foreach(cms_services() as $service)
                <li><a href="{{ $service->url() }}">{{ $service->title }}</a></li>
                @endforeach
                @if($footer->button_text)
                <li><a href="{{ cms_url($footer->button_link) }}">{{ cms_text($footer->button_text) }}</a></li>
                @endif
            </ul>
        </div>

        <div>
            @if($footer->field('col3_title'))<h2 class="footer-title">{{ cms_text($footer->field('col3_title')) }}</h2>@endif
            <ul class="footer-contact">
                <li><i class="fa-solid fa-phone" aria-hidden="true"></i><a href="{{ $set->phoneHref() }}">{{ $set->phone }}</a></li>
                <li><i class="fa-solid fa-envelope" aria-hidden="true"></i><a href="mailto:{{ $set->email }}">{{ $set->email }}</a></li>
                <li><i class="fa-solid fa-location-dot" aria-hidden="true"></i><span>{{ $set->address_street }}<br>{{ $set->address_city }}</span></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <span>{{ cms_text($footer->subtitle) }}</span>
            @if($footer->group('legal')->isNotEmpty())
            <nav aria-label="Legal">
                @foreach($footer->group('legal') as $link)
                    <a href="{{ cms_url($link->link) }}">{{ cms_text($link->title) }}</a>@if(!$loop->last)<span>|</span>@endif
                @endforeach
            </nav>
            @endif
        </div>
    </div>
</footer>
