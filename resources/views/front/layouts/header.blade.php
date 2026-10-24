@php($header = cms_global('header'))
@php($navServices = \App\Models\Service::active()->get())
<header class="site-header">
    <div class="container header-inner">
        @include('front.layouts.logo')

        <button class="nav-toggle" type="button" aria-controls="main-nav" aria-expanded="false" aria-label="Open menu">
            <i class="fa-solid fa-bars"></i>
        </button>

        <nav id="main-nav" class="main-nav" aria-label="Main">
            <ul>
                @foreach($header->group('menu') as $item)
                    @php($current = cms_url_is_current($item->link))
                    @if($item->value === 'services' && $navServices->isNotEmpty())
                    <li class="has-dropdown">
                        <a href="{{ cms_url($item->link) }}" class="{{ $current || request()->routeIs('service') ? 'active' : '' }}" @if($current) aria-current="page" @endif>
                            {{ cms_text($item->title) }} <i class="fa-solid fa-chevron-down nav-caret" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown">
                            @foreach($navServices as $service)
                            <li>
                                <a href="{{ $service->url() }}" class="{{ request()->routeIs('service') && request()->route('slug') === $service->slug ? 'active' : '' }}">
                                    @if($service->icon)<i class="fa-solid {{ $service->icon }}" aria-hidden="true"></i>@endif {{ $service->title }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @else
                    <li>
                        <a href="{{ cms_url($item->link) }}" class="{{ $current ? 'active' : '' }}" @if($current) aria-current="page" @endif>{{ cms_text($item->title) }}</a>
                    </li>
                    @endif
                @endforeach
            </ul>
            @if($header->button_text)
            <a href="{{ cms_url($header->button_link) }}" class="btn btn-dark btn-sm">{{ cms_text($header->button_text) }}</a>
            @endif
        </nav>
    </div>
</header>
