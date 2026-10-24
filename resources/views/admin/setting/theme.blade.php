@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Theme Colors</h1>
    <p>Pick two base colours; every other shade on the website is generated from them. Text colours are adjusted automatically so they stay readable.</p>
</div>

@php($presets = [
    ['Logo gold & black', '#D4A24C', '#0E0E10'],
    ['Gold & charcoal', '#C9A54E', '#1F2328'],
    ['Silver & black', '#B8BEC6', '#0E0E10'],
    ['Blue & navy', '#1D4ED8', '#0A1F44'],
    ['Red & black', '#C8102E', '#111111'],
])

<form action="{{ route('admin_setting_theme_update') }}" method="post" id="themeForm">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-5">
            <div class="card-custom-p mb-4">
                <h2 class="section-title">Base colours</h2>

                <div class="mb-4">
                    <label class="label-custom" for="color_primary_text">Accent colour</label>
                    <div class="color-field">
                        <input type="color" id="color_primary_picker" value="{{ old('color_primary', $setting->color_primary) }}" aria-label="Pick accent colour">
                        <input type="text" id="color_primary_text" name="color_primary" value="{{ old('color_primary', $setting->color_primary) }}" class="form-control-custom" maxlength="7" pattern="#[0-9a-fA-F]{6}">
                    </div>
                    <div class="field-hint">Buttons, highlights, icons and the call-to-action band.</div>
                </div>

                <div class="mb-4">
                    <label class="label-custom" for="color_dark_text">Dark colour</label>
                    <div class="color-field">
                        <input type="color" id="color_dark_picker" value="{{ old('color_dark', $setting->color_dark) }}" aria-label="Pick dark colour">
                        <input type="text" id="color_dark_text" name="color_dark" value="{{ old('color_dark', $setting->color_dark) }}" class="form-control-custom" maxlength="7" pattern="#[0-9a-fA-F]{6}">
                    </div>
                    <div class="field-hint">Header buttons, feature strip, Safety section and footer. Keep it dark.</div>
                </div>

                <button type="submit" class="btn-admin-primary">Save Colours</button>
            </div>

            <div class="card-custom-p">
                <h2 class="section-title">Presets</h2>
                <div class="d-flex flex-column gap-2">
                    @foreach($presets as [$name, $primary, $dark])
                    <button type="button" class="preset-btn" data-primary="{{ $primary }}" data-dark="{{ $dark }}">
                        <span class="preset-swatch" style="background: {{ $dark }}"><span style="background: {{ $primary }}"></span></span>
                        {{ $name }}
                    </button>
                    @endforeach
                </div>
                <p class="field-hint mt-3 mb-0">A preset only fills the fields; press <strong>Save Colours</strong> to apply it.</p>
            </div>
        </div>

        <div class="col-12 col-xl-7">
            <div class="card-custom-p">
                <h2 class="section-title">Live preview</h2>
                <div class="theme-preview" id="themePreview" style="{{ collect($variables)->map(fn ($v, $k) => $k.':'.$v)->implode(';') }}">
                    <div class="tp-dark">
                        <div class="tp-kicker tp-on-dark">Reliable &middot; Safe &middot; On Time</div>
                        <div class="tp-title">ZAM ZAM <span>TRUCKING LLC</span></div>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="tp-btn">Request a Quote</span>
                            <span class="tp-btn-outline">Contact Dispatch</span>
                        </div>
                    </div>
                    <div class="tp-light">
                        <div class="tp-kicker tp-text">Our Services</div>
                        <div class="tp-heading">Moving America Forward</div>
                        <p>Sample paragraph with <span class="tp-text fw-semibold">a link in the accent colour</span>.</p>
                        <span class="tp-btn-dark">Request a Quote</span>
                    </div>
                    <div class="tp-cta">Ready to move your freight?</div>
                </div>

                <h2 class="section-title mt-4">Generated shades</h2>
                <div class="swatch-grid" id="swatchGrid">
                    @foreach($variables as $name => $value)
                        @continue(str_ends_with($name, '-rgb'))
                        <div class="swatch"><span style="background: {{ $value }}"></span><code>{{ $name }}</code><small>{{ $value }}</small></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
(function () {
    // Same maths as App\Support\Theme, so the preview matches the saved site.
    var WHITE = [255, 255, 255], BLACK = [0, 0, 0];
    function rgb(hex) { hex = hex.replace('#', ''); return [0, 2, 4].map(function (i) { return parseInt(hex.substr(i, 2), 16); }); }
    function hex(c) { return '#' + c.map(function (x) { return ('0' + Math.max(0, Math.min(255, Math.round(x))).toString(16)).slice(-2); }).join('').toUpperCase(); }
    function mix(a, b, t) { return a.map(function (x, i) { return x + (b[i] - x) * t; }); }
    function lum(c) { c = c.map(function (v) { v /= 255; return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4); }); return 0.2126 * c[0] + 0.7152 * c[1] + 0.0722 * c[2]; }
    function contrast(a, b) { var x = lum(a), y = lum(b); return (Math.max(x, y) + 0.05) / (Math.min(x, y) + 0.05); }
    function readable(c, bg, toward) { for (var t = 0; t <= 1.0001; t += 0.04) { var m = mix(c, toward, t); if (contrast(m, bg) >= 4.5) return m; } return toward; }
    function vars(p, d) {
        var a = rgb(p), base = rgb(d);
        return {
            '--accent': hex(a), '--accent-hover': hex(mix(a, WHITE, .18)), '--accent-light': hex(mix(a, WHITE, .4)),
            '--accent-deep': hex(mix(a, BLACK, .3)), '--accent-50': hex(mix(a, WHITE, .88)),
            '--accent-text': hex(readable(a, [245, 247, 251], BLACK)), '--accent-on-dark': hex(readable(a, base, WHITE)),
            '--accent-ink': hex(contrast(base, a) >= contrast(WHITE, a) ? base : WHITE),
            '--dark-950': hex(mix(base, BLACK, .35)), '--dark-900': hex(base), '--dark-800': hex(mix(base, WHITE, .06)), '--dark-700': hex(mix(base, WHITE, .13))
        };
    }
    var valid = /^#[0-9a-fA-F]{6}$/;
    function update() {
        var p = $('#color_primary_text').val(), d = $('#color_dark_text').val();
        if (!valid.test(p) || !valid.test(d)) return;
        var v = vars(p, d), preview = document.getElementById('themePreview'), html = '';
        Object.keys(v).forEach(function (k) {
            preview.style.setProperty(k, v[k]);
            html += '<div class="swatch"><span style="background:' + v[k] + '"></span><code>' + k + '</code><small>' + v[k] + '</small></div>';
        });
        $('#swatchGrid').html(html);
    }
    ['primary', 'dark'].forEach(function (n) {
        $('#color_' + n + '_picker').on('input', function () { $('#color_' + n + '_text').val(this.value.toUpperCase()); update(); });
        $('#color_' + n + '_text').on('input', function () { if (valid.test(this.value)) { $('#color_' + n + '_picker').val(this.value); update(); } });
    });
    $('.preset-btn').on('click', function () {
        $('#color_primary_text').val($(this).data('primary')).trigger('input');
        $('#color_dark_text').val($(this).data('dark')).trigger('input');
    });
})();
</script>
@endpush
