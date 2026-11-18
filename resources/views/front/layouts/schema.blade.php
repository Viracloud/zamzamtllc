{{-- Structured data (schema.org) for search engines: the company on every page, plus the website on the home page. --}}
@php
    $set = $global_setting;
    preg_match('/^\s*(.+?),\s*([A-Za-z]{2})\b\s*(\d{5})?/', (string) $set->address_city, $addr);
    $real = fn ($field) => $set->isPlaceholder($field) ? null : $set->{$field};

    $business = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        '@id' => url('/') . '#business',
        'name' => $set->company_name,
        'description' => $set->company_name . ' is a dry van and full truckload carrier serving shippers and brokers across all 48 states.',
        'url' => url('/'),
        'logo' => $set->imageUrl('logo', 'assets/front/img/brand/logo-240.png'),
        'image' => $ogImageUrl,
        'telephone' => $real('phone'),
        'email' => $real('email'),
        'foundingDate' => $real('since_year'),
        'address' => ($real('address_street') && $addr) ? array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => $set->address_street,
            'addressLocality' => $addr[1],
            'addressRegion' => strtoupper($addr[2]),
            'postalCode' => $addr[3] ?? null,
            'addressCountry' => 'US',
        ]) : null,
        'areaServed' => ['@type' => 'Country', 'name' => 'United States'],
        'openingHoursSpecification' => [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            'opens' => '00:00',
            'closes' => '23:59',
        ],
        'identifier' => array_values(array_filter([
            $real('usdot_number') ? ['@type' => 'PropertyValue', 'propertyID' => 'USDOT', 'value' => $set->usdot_number] : null,
            $real('mc_number') ? ['@type' => 'PropertyValue', 'propertyID' => 'MC', 'value' => 'MC-' . $set->mc_number] : null,
        ])) ?: null,
        'sameAs' => array_values(array_filter(array_map(
            // Only real profile links (a path after the domain), not WhatsApp or a bare platform home page.
            fn ($link) => (str_contains($link[2], 'wa.me') || trim((string) parse_url($link[2], PHP_URL_PATH), '/') === '') ? null : $link[2],
            $set->socialLinks()
        ))) ?: null,
    ]);
@endphp
<script type="application/ld+json">{!! json_encode($business, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@if(request()->routeIs('home'))
@php
    $website = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        '@id' => url('/') . '#website',
        'name' => $set->company_name,
        'url' => url('/'),
        'publisher' => ['@id' => url('/') . '#business'],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($website, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endif
