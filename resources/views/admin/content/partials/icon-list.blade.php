{{-- Suggestions for icon inputs (any Font Awesome 6 free solid icon name works) --}}
<datalist id="fa-icons">
    @foreach([
        'fa-truck', 'fa-truck-fast', 'fa-truck-front', 'fa-truck-moving', 'fa-truck-ramp-box', 'fa-trailer', 'fa-van-shuttle',
        'fa-road', 'fa-route', 'fa-map', 'fa-map-location-dot', 'fa-location-dot', 'fa-globe', 'fa-flag-usa',
        'fa-box', 'fa-boxes-stacked', 'fa-pallet', 'fa-warehouse', 'fa-dolly', 'fa-weight-hanging',
        'fa-shield-halved', 'fa-shield', 'fa-certificate', 'fa-award', 'fa-medal', 'fa-star', 'fa-thumbs-up',
        'fa-clock', 'fa-stopwatch', 'fa-calendar-check', 'fa-gauge-high', 'fa-bolt',
        'fa-user-tie', 'fa-users', 'fa-id-card', 'fa-id-badge', 'fa-handshake', 'fa-headset', 'fa-people-carry-box',
        'fa-phone', 'fa-envelope', 'fa-comments', 'fa-mobile-screen', 'fa-paper-plane',
        'fa-clipboard-check', 'fa-file-signature', 'fa-file-invoice-dollar', 'fa-file-lines', 'fa-list-check',
        'fa-screwdriver-wrench', 'fa-gear', 'fa-oil-can', 'fa-gas-pump', 'fa-flask-vial',
        'fa-satellite-dish', 'fa-wifi', 'fa-signal', 'fa-laptop', 'fa-chart-line',
        'fa-dollar-sign', 'fa-money-bill-wave', 'fa-wallet', 'fa-piggy-bank',
        'fa-house', 'fa-building', 'fa-industry', 'fa-store', 'fa-circle-check', 'fa-check', 'fa-heart', 'fa-leaf',
    ] as $icon)
        <option value="{{ $icon }}"></option>
    @endforeach
</datalist>
