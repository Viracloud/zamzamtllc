@php($s = $global_setting)
<div class="contact-box">
    <div class="contact-item">
        <div class="contact-icon"><i class="fa-solid fa-phone" aria-hidden="true"></i></div>
        <div><span>{{ cms_label('lbl_c_phone') }}</span><a href="{{ $s->phoneHref() }}">{{ $s->phone }}</a></div>
    </div>
    <div class="contact-item">
        <div class="contact-icon"><i class="fa-solid fa-envelope" aria-hidden="true"></i></div>
        <div><span>{{ cms_label('lbl_c_email') }}</span><a href="mailto:{{ $s->email }}">{{ $s->email }}</a></div>
    </div>
    <div class="contact-item">
        <div class="contact-icon"><i class="fa-solid fa-location-dot" aria-hidden="true"></i></div>
        <div><span>{{ cms_label('lbl_c_office') }}</span><strong>{{ $s->address_street }}, {{ $s->address_city }}</strong></div>
    </div>
</div>
