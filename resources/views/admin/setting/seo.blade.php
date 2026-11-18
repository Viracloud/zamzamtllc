@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>SEO &amp; Tracking</h1>
    <p>Connect the website to Google and Bing, set the image shown when a link is shared, and check every page's search listing at a glance.</p>
</div>

@php
    $company = $setting->company_name;
    $rows = collect();
    foreach ($pages as $p) {
        $rows->push([
            'type' => 'Page',
            'name' => $p->name,
            'url' => $p->url(),
            'title' => $p->meta_title ? cms_text($p->meta_title) : ($p->key === 'home' ? $company : $p->name . ' | ' . $company),
            'desc' => cms_text($p->meta_description),
            'active' => $p->is_active,
            'noindex' => $p->noindex,
            'edit' => route('admin_content_page_edit', $p->id),
        ]);
    }
    foreach ($services as $s) {
        $rows->push([
            'type' => 'Service',
            'name' => $s->title,
            'url' => $s->url(),
            'title' => $s->meta_title ? cms_text($s->meta_title) : $s->title . ' | ' . $company,
            'desc' => cms_text($s->meta_description ?: $s->short),
            'active' => $s->is_active,
            'noindex' => $s->noindex,
            'edit' => route('admin_service_edit', $s->id),
        ]);
    }
    $grade = fn (int $len, int $min, int $max) => $len === 0 ? 'bad' : ($len < $min || $len > $max ? 'warn' : 'good');
@endphp

<form action="{{ route('admin_setting_seo_update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-6">
            <h6 class="cus-heading">Search engines</h6>
            <div class="card-custom-p h-100">
                <div class="mb-3">
                    <label class="label-custom" for="f-gsv">Google Search Console verification code</label>
                    <input type="text" id="f-gsv" name="google_site_verification" value="{{ old('google_site_verification', $setting->google_site_verification) }}" class="form-control-custom {{ $errors->has('google_site_verification') ? 'is-invalid' : '' }}" placeholder="e.g. 1a2B3c4D5e6F7g8H9i0J…" autocomplete="off">
                    @error('google_site_verification')<div class="field-hint text-danger">{{ $message }}</div>@enderror
                    <div class="field-hint">In <a href="https://search.google.com/search-console" target="_blank" rel="noopener">Search Console</a> add the site as a <em>URL prefix</em> property and choose <em>HTML tag</em>. Paste the code (or the whole tag), save, then press Verify there. Afterwards submit <code>{{ route('sitemap') }}</code> under Sitemaps.</div>
                </div>
                <div class="mb-3">
                    <label class="label-custom" for="f-bsv">Bing Webmaster Tools verification code <span class="text-gray-500">(optional)</span></label>
                    <input type="text" id="f-bsv" name="bing_site_verification" value="{{ old('bing_site_verification', $setting->bing_site_verification) }}" class="form-control-custom {{ $errors->has('bing_site_verification') ? 'is-invalid' : '' }}" autocomplete="off">
                    @error('bing_site_verification')<div class="field-hint text-danger">{{ $message }}</div>@enderror
                    <div class="field-hint">Bing also powers Yahoo and DuckDuckGo results. Bing can import the site straight from Search Console instead.</div>
                </div>
                <div class="mb-0">
                    <label class="label-custom" for="f-ga">Google Analytics 4 Measurement ID <span class="text-gray-500">(optional)</span></label>
                    <input type="text" id="f-ga" name="google_analytics_id" value="{{ old('google_analytics_id', $setting->google_analytics_id) }}" class="form-control-custom {{ $errors->has('google_analytics_id') ? 'is-invalid' : '' }}" placeholder="G-XXXXXXXXXX" autocomplete="off">
                    @error('google_analytics_id')<div class="field-hint text-danger">{{ $message }}</div>@enderror
                    <div class="field-hint">Found in Google Analytics under Admin → Data streams. Leave empty to turn tracking off.</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <h6 class="cus-heading">Sharing image</h6>
            <div class="card-custom-p h-100">
                <div class="image-preview is-photo">
                    @if($setting->og_image)
                        <img src="{{ asset('uploads/'.$setting->og_image) }}" alt="Sharing image">
                    @else
                        <img src="{{ cms_image(optional(\App\Models\Section::where('page', 'home')->where('type', 'hero')->first())->image, 'assets/front/img/hero.jpg') }}" alt="Current sharing image (home banner)">
                    @endif
                </div>
                <p class="text-sm text-gray-500">Shown when a page is shared on Facebook, WhatsApp, X, LinkedIn or in messages. Best size 1200×630 (JPG or PNG, up to 5 MB). {{ $setting->og_image ? '' : 'Right now the home page banner is used.' }} Service pages use their own photo.</p>
                <input type="file" name="og_image" accept="image/jpeg,image/png,image/webp" class="form-control-custom {{ $errors->has('og_image') ? 'is-invalid' : '' }}">
                @error('og_image')<div class="field-hint text-danger">{{ $message }}</div>@enderror
                @if($setting->og_image)
                    <button type="submit" form="reset-og" class="btn btn-link btn-sm text-danger px-0 mt-2">Remove and use the home banner</button>
                @endif
            </div>
        </div>
    </div>

    <div class="card-custom-p mt-4">
        <button type="submit" class="btn-admin-primary">Save SEO Settings</button>
    </div>
</form>
@if($setting->og_image)
<form id="reset-og" action="{{ route('admin_setting_image_reset', 'og_image') }}" method="post" class="d-none">@csrf</form>
@endif

<h6 class="cus-heading mt-5">Search listings overview</h6>
<div class="card-custom-p">
    <p class="text-sm text-gray-500 mb-3">How each page can appear in Google. Aim for a title of 30–60 characters and a description of 70–160. <span class="seo-pill seo-good">Good</span> <span class="seo-pill seo-warn">Too short / long</span> <span class="seo-pill seo-bad">Missing</span></p>
    <div class="table-responsive">
        <table class="table seo-table align-middle mb-0">
            <thead>
                <tr><th>Page</th><th>Title</th><th>Description</th><th>Google</th><th></th></tr>
            </thead>
            <tbody>
                @foreach($rows as $r)
                <tr>
                    <td>
                        <strong>{{ $r['name'] }}</strong>
                        <div class="text-xs text-gray-500">{{ $r['type'] }} · <a href="{{ $r['url'] }}" target="_blank" rel="noopener">{{ \Illuminate\Support\Str::after($r['url'], '://') }}</a></div>
                    </td>
                    <td><span class="seo-pill seo-{{ $grade(mb_strlen($r['title']), 30, 60) }}">{{ mb_strlen($r['title']) }}</span> <span class="text-sm">{{ \Illuminate\Support\Str::limit($r['title'], 60) }}</span></td>
                    <td><span class="seo-pill seo-{{ $grade(mb_strlen($r['desc']), 70, 160) }}">{{ mb_strlen($r['desc']) }}</span> <span class="text-sm text-gray-500">{{ \Illuminate\Support\Str::limit($r['desc'], 70) }}</span></td>
                    <td class="text-nowrap">
                        @if(!$r['active'])<span class="status-pill status-closed">Hidden</span>
                        @elseif($r['noindex'])<span class="status-pill status-spam">Not indexed</span>
                        @else<span class="status-pill status-hired">Indexed</span>@endif
                    </td>
                    <td><a href="{{ $r['edit'] }}" class="btn-icon btn-icon-edit" title="Edit SEO"><i class="fa-solid fa-pen text-xs"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
