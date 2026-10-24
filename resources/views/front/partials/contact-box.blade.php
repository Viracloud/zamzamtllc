@php($s = $global_setting)
<div class="contact-box">
    <div class="contact-item">
        <div class="contact-icon"><i class="fa-solid fa-phone"></i></div>
        <div><span>Dispatch 24/7</span><a href="{{ $s->phoneHref() }}">{{ $s->phone }}</a></div>
    </div>
    <div class="contact-item">
        <div class="contact-icon"><i class="fa-solid fa-envelope"></i></div>
        <div><span>Email</span><a href="mailto:{{ $s->email }}">{{ $s->email }}</a></div>
    </div>
    <div class="contact-item">
        <div class="contact-icon"><i class="fa-solid fa-location-dot"></i></div>
        <div><span>Office</span><strong>{{ $s->address_street }}, {{ $s->address_city }}</strong></div>
    </div>
</div>
