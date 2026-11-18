@php
    $new_quotes_count = \App\Models\QuoteRequest::where('status', 'New')->count();
    $new_applications_count = \App\Models\DriverApplication::where('status', 'New')->count();

    // Two collapsible groups; the group holding the current page opens by itself.
    $groups = [
        'website' => [
            'label' => 'Website Content',
            'icon' => 'fa-globe',
            'links' => [
                ['Pages & Sections', 'fa-layer-group', route('admin_content_pages'), Request::is('admin/content/pages*') || Request::is('admin/content/sections*')],
                ['Services', 'fa-truck-fast', route('admin_service_index'), Request::is('admin/services*')],
                ['Header & Menu', 'fa-bars', route('admin_content_global', 'header'), Request::is('admin/content/global/header')],
                ['Footer', 'fa-table-columns', route('admin_content_global', 'footer'), Request::is('admin/content/global/footer')],
                ['Forms & Labels', 'fa-rectangle-list', route('admin_content_global', 'forms'), Request::is('admin/content/global/forms')],
            ],
        ],
        'settings' => [
            'label' => 'Settings',
            'icon' => 'fa-gear',
            'links' => [
                ['Company Settings', 'fa-building', route('admin_setting_company'), Request::is('admin/setting/company*')],
                ['Theme Colors', 'fa-palette', route('admin_setting_theme'), Request::is('admin/setting/theme*')],
                ['Logo & Images', 'fa-image', route('admin_setting_images'), Request::is('admin/setting/images*')],
                ['SEO & Tracking', 'fa-magnifying-glass-chart', route('admin_setting_seo'), Request::is('admin/setting/seo*')],
            ],
        ],
    ];
    foreach ($groups as $key => $group) {
        $groups[$key]['open'] = collect($group['links'])->contains(fn ($l) => $l[3]);
    }
@endphp
<aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <img class="sidebar-logo-img" src="{{ $global_setting->imageUrl('logo', 'assets/front/img/brand/logo-240.png') }}" alt="">
        <span class="sidebar-brand sidebar-label">Zam Zam Admin</span>
    </div>
    <nav class="sidebar-nav space-y-1">
        {{-- Daily work: always visible --}}
        <a href="{{ route('admin_dashboard') }}" class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}" title="Dashboard">
            <i class="fa-solid fa-house"></i>
            <span class="sidebar-label">Dashboard</span>
        </a>

        <a href="{{ route('admin_quote_request_index') }}" class="nav-link {{ Request::is('admin/quote-requests*') ? 'active' : '' }}" title="Quote Requests">
            <i class="fa-solid fa-file-invoice-dollar"></i>
            <span class="sidebar-label">Quote Requests</span>
            @if($new_quotes_count)<span class="nav-count sidebar-label">{{ $new_quotes_count }}</span>@endif
        </a>

        <a href="{{ route('admin_driver_application_index') }}" class="nav-link {{ Request::is('admin/driver-applications*') ? 'active' : '' }}" title="Driver Applications">
            <i class="fa-solid fa-id-card"></i>
            <span class="sidebar-label">Driver Applications</span>
            @if($new_applications_count)<span class="nav-count sidebar-label">{{ $new_applications_count }}</span>@endif
        </a>

        <div class="sidebar-divider"></div>

        {{-- Occasional work: grouped and folded away --}}
        @foreach($groups as $key => $group)
        <div class="nav-dropdown {{ $group['open'] ? 'open' : '' }}">
            <button type="button" class="nav-link nav-dropdown-toggle w-100 border-0 bg-transparent" aria-expanded="{{ $group['open'] ? 'true' : 'false' }}" aria-controls="nav-{{ $key }}" title="{{ $group['label'] }}">
                <i class="fa-solid {{ $group['icon'] }}"></i>
                <span class="sidebar-label">{{ $group['label'] }}</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </button>
            <div class="nav-dropdown-menu" id="nav-{{ $key }}">
                @foreach($group['links'] as [$label, $icon, $url, $active])
                <a href="{{ $url }}" class="nav-link nav-group-link {{ $active ? 'active' : '' }}" title="{{ $label }}">
                    <i class="fa-solid {{ $icon }}"></i>
                    <span class="sidebar-label">{{ $label }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- Last item: help and ideas --}}
        <div class="sidebar-divider"></div>
        <a href="{{ route('admin_support') }}" class="nav-link {{ Request::is('admin/support*') ? 'active' : '' }}" title="Support & improve">
            <i class="fa-solid fa-headset"></i>
            <span class="sidebar-label">Support &amp; improve</span>
        </a>
    </nav>
</aside>
