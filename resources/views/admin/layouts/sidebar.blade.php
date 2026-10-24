@php($new_quotes_count = \App\Models\QuoteRequest::where('status', 'New')->count())
@php($new_applications_count = \App\Models\DriverApplication::where('status', 'New')->count())
<aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <img class="sidebar-logo-img" src="{{ $global_setting->imageUrl('logo', 'assets/front/img/brand/logo-240.png') }}" alt="">
        <span class="sidebar-brand sidebar-label">Zam Zam Admin</span>
    </div>
    <nav class="sidebar-nav space-y-1">
        <a href="{{ route('admin_dashboard') }}" class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i>
            <span class="sidebar-label">Dashboard</span>
        </a>

        <a href="{{ route('admin_quote_request_index') }}" class="nav-link {{ Request::is('admin/quote-requests*') ? 'active' : '' }}">
            <i class="fa-solid fa-file-invoice-dollar"></i>
            <span class="sidebar-label">Quote Requests</span>
            @if($new_quotes_count)<span class="nav-count sidebar-label">{{ $new_quotes_count }}</span>@endif
        </a>

        <a href="{{ route('admin_driver_application_index') }}" class="nav-link {{ Request::is('admin/driver-applications*') ? 'active' : '' }}">
            <i class="fa-solid fa-id-card"></i>
            <span class="sidebar-label">Driver Applications</span>
            @if($new_applications_count)<span class="nav-count sidebar-label">{{ $new_applications_count }}</span>@endif
        </a>

        <div class="sidebar-group-label sidebar-label">Website</div>

        <a href="{{ route('admin_content_pages') }}" class="nav-link {{ Request::is('admin/content/pages*') || Request::is('admin/content/sections*') ? 'active' : '' }}">
            <i class="fa-solid fa-layer-group"></i>
            <span class="sidebar-label">Pages &amp; Sections</span>
        </a>

        <a href="{{ route('admin_service_index') }}" class="nav-link {{ Request::is('admin/services*') ? 'active' : '' }}">
            <i class="fa-solid fa-truck-fast"></i>
            <span class="sidebar-label">Services</span>
        </a>

        <a href="{{ route('admin_content_global', 'header') }}" class="nav-link {{ Request::is('admin/content/global/header') ? 'active' : '' }}">
            <i class="fa-solid fa-bars"></i>
            <span class="sidebar-label">Header &amp; Menu</span>
        </a>

        <a href="{{ route('admin_content_global', 'footer') }}" class="nav-link {{ Request::is('admin/content/global/footer') ? 'active' : '' }}">
            <i class="fa-solid fa-table-columns"></i>
            <span class="sidebar-label">Footer</span>
        </a>

        <a href="{{ route('admin_content_global', 'forms') }}" class="nav-link {{ Request::is('admin/content/global/forms') ? 'active' : '' }}">
            <i class="fa-solid fa-rectangle-list"></i>
            <span class="sidebar-label">Forms &amp; Labels</span>
        </a>

        <div class="sidebar-group-label sidebar-label">Settings</div>

        <a href="{{ route('admin_setting_company') }}" class="nav-link {{ Request::is('admin/setting/company*') ? 'active' : '' }}">
            <i class="fa-solid fa-building"></i>
            <span class="sidebar-label">Company Settings</span>
        </a>

        <a href="{{ route('admin_setting_theme') }}" class="nav-link {{ Request::is('admin/setting/theme*') ? 'active' : '' }}">
            <i class="fa-solid fa-palette"></i>
            <span class="sidebar-label">Theme Colors</span>
        </a>

        <a href="{{ route('admin_setting_images') }}" class="nav-link {{ Request::is('admin/setting/images*') ? 'active' : '' }}">
            <i class="fa-solid fa-image"></i>
            <span class="sidebar-label">Logo &amp; Images</span>
        </a>
    </nav>
</aside>
