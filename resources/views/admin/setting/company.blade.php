@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Company Settings</h1>
    <p>Everything the website shows about the company. Fields highlighted in yellow still hold a <code>[placeholder]</code> from the design file.</p>
</div>

<form action="{{ route('admin_setting_company_update') }}" method="post">
    @csrf
    <div class="row g-4">
        @foreach($groups as $group => $fields)
        <div class="col-12 col-xl-6">
            <h6 class="cus-heading">{{ $group }}</h6>
            <div class="card-custom-p h-100">
                @foreach($fields as $field => $label)
                <div class="mb-3">
                    <label class="label-custom" for="f-{{ $field }}">{{ $label }}</label>
                    <input type="{{ $field === 'fmcsa_url' ? 'url' : ($field === 'email' ? 'email' : 'text') }}"
                           id="f-{{ $field }}" name="{{ $field }}"
                           value="{{ old($field, $setting->{$field}) }}"
                           class="form-control-custom {{ $field !== 'fmcsa_url' && $setting->isPlaceholder($field) ? 'placeholder-input' : '' }}">
                    @if($field === 'fmcsa_url' && $setting->fmcsaLink())
                        <div class="field-hint">Current link: <a href="{{ $setting->fmcsaLink() }}" target="_blank" rel="noopener">{{ \Illuminate\Support\Str::limit($setting->fmcsaLink(), 70) }}</a></div>
                    @elseif($field === 'mc_number')
                        <div class="field-hint">Shown as "MC-{{ $setting->mc_number }}".</div>
                    @elseif($field === 'driver_pay')
                        <div class="field-hint">Shown as "{{ $setting->driver_pay }} / mile", e.g. $0.65.</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <div class="card-custom-p mt-4">
        <button type="submit" class="btn-admin-primary">Save Settings</button>
    </div>
</form>
@endsection
