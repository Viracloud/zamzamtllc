@php($admin = Auth::guard('admin')->user())
<header class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button onclick="toggleSidebar()" class="topbar-btn d-lg-none">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
    <div class="d-flex align-items-center gap-3">
        <a href="{{ url('/') }}" target="_blank" class="btn-primary-custom"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Website</a>
        <div class="position-relative" id="userDropdown">
            <button onclick="$('#userMenu').toggleClass('show')" class="user-dropdown-btn">
                @if($admin->photo)
                    <img src="{{ asset('uploads/'.$admin->photo) }}" alt="{{ $admin->name }}">
                @else
                    <span class="avatar-initial">{{ strtoupper(mb_substr($admin->name, 0, 1)) }}</span>
                @endif
                <span class="hidden-mobile">{{ $admin->name }}</span>
                <i class="fa-solid fa-chevron-down"></i>
            </button>
            <div id="userMenu" class="user-dropdown-menu">
                <a href="{{ route('admin_profile') }}" class="text-dark-custom">
                    <i class="fa-regular fa-user"></i>Profile
                </a>
                <a href="{{ route('admin_logout') }}" class="text-danger-custom">
                    <i class="fa-solid fa-right-from-bracket"></i>Logout
                </a>
            </div>
        </div>
    </div>
</header>
