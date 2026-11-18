{{--
    SEO title + description with live character counters and a Google result preview.
    Params: $title, $description (current values), $fallbackTitle, $fallbackDescription (used when empty),
            $url (page address), $noindex (bool, or null to hide the checkbox)
--}}
<div class="seo-fields" data-tokens='@json(cms_tokens())' data-fallback-title="{{ $fallbackTitle }}" data-fallback-description="{{ $fallbackDescription ?? '' }}">
    <div class="mb-3">
        <label class="label-custom d-flex justify-content-between" for="seo-title">
            <span>SEO title</span>
            <span class="seo-count" data-for="seo-title" data-min="30" data-max="60"></span>
        </label>
        <input type="text" id="seo-title" name="meta_title" value="{{ $title }}" class="form-control-custom" placeholder="{{ $fallbackTitle }}">
        <div class="field-hint">Shown as the blue link in Google. 30–60 characters. Leave empty to use “{{ $fallbackTitle }}”.</div>
    </div>
    <div class="mb-3">
        <label class="label-custom d-flex justify-content-between" for="seo-description">
            <span>SEO description</span>
            <span class="seo-count" data-for="seo-description" data-min="70" data-max="160"></span>
        </label>
        <textarea id="seo-description" name="meta_description" rows="3" class="form-control-custom" placeholder="{{ $fallbackDescription ?? '' }}">{{ $description }}</textarea>
        <div class="field-hint">The grey text under the link. 70–160 characters. {{ \App\Support\SectionTypes::TOKEN_HELP }}</div>
    </div>

    <div class="seo-preview" aria-label="Google preview">
        <div class="seo-preview-label">Google preview</div>
        <div class="seo-preview-url">{{ \Illuminate\Support\Str::after($url, '://') }}</div>
        <div class="seo-preview-title"></div>
        <div class="seo-preview-desc"></div>
    </div>

    @if(!is_null($noindex ?? null))
    <label class="d-flex align-items-start gap-2 mt-3">
        <input type="hidden" name="noindex" value="0">
        <input type="checkbox" class="form-check-input m-0 mt-1" name="noindex" value="1" {{ $noindex ? 'checked' : '' }}>
        <span class="text-sm">Hide from Google <span class="text-gray-500">(noindex — the page stays on the site but is left out of search results and the sitemap)</span></span>
    </label>
    @endif
</div>

@once
@push('scripts')
<script>
document.querySelectorAll('.seo-fields').forEach(function (box) {
    var tokens = JSON.parse(box.dataset.tokens || '{}');
    var title = box.querySelector('#seo-title'), desc = box.querySelector('#seo-description');
    var fill = function (s) { Object.keys(tokens).forEach(function (k) { s = s.split(k).join(tokens[k] || ''); }); return s.trim(); };
    var cut = function (s, n) { return s.length > n ? s.slice(0, n - 1).trim() + '…' : s; };

    function update() {
        var t = fill(title.value) || fill(box.dataset.fallbackTitle);
        var d = fill(desc.value) || fill(box.dataset.fallbackDescription);
        box.querySelector('.seo-preview-title').textContent = cut(t, 60);
        box.querySelector('.seo-preview-desc').textContent = d ? cut(d, 160) : 'No description: Google will pick some text from the page.';
        [[title, t], [desc, d]].forEach(function (pair) {
            var counter = box.querySelector('.seo-count[data-for="' + pair[0].id + '"]');
            var n = pair[1].length, min = +counter.dataset.min, max = +counter.dataset.max;
            counter.textContent = n + ' / ' + max;
            counter.className = 'seo-count ' + (n === 0 ? 'seo-bad' : (n < min || n > max ? 'seo-warn' : 'seo-good'));
        });
    }
    title.addEventListener('input', update);
    desc.addEventListener('input', update);
    update();
});
</script>
@endpush
@endonce
