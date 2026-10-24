@php($words = preg_split('/\s+/', trim($global_setting->company_name ?: 'ZAM ZAM TRUCKING LLC')))
@php($cut = max(1, (int) ceil(count($words) / 2)))
@php($main = array_slice($words, 0, $cut))
<a href="{{ route('home') }}" class="logo {{ $class ?? '' }}" aria-label="{{ $global_setting->company_name }} home">
    <img class="logo-mark" src="{{ $global_setting->imageUrl('logo', 'assets/front/img/brand/logo-240.png') }}" alt="" width="58" height="58">
    {{-- Wordmark: first half of the name large (first word in the accent colour), the rest small between rules --}}
    <span class="logo-text">
        <span class="logo-main"><span class="gold">{{ $main[0] }}</span> {{ implode(' ', array_slice($main, 1)) }}</span>
        @if(count($words) > 1)
            <span class="logo-sub">{{ implode(' ', array_slice($words, $cut)) }}</span>
        @endif
    </span>
</a>
