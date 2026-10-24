{{-- Driver application form card. Pass $return = route name of the page it sits on. --}}
@php($forms = cms_global('forms'))
@php($experience_options = cms_options('experience'))
@php($positions = cms_options('positions'))
@php($states = \App\Models\DriverApplication::STATES)
@php($legal = cms_global('footer')->group('legal'))
@php($e = $errors->application)
@php($mine = old('_form') === 'application')
@php($val = fn ($field, $default = '') => $mine ? old($field, $default) : $default)
<div class="card form-card reveal" id="apply-form">
    <h3>{{ cms_text($forms->field('app_title')) }}</h3>
    <p class="form-sub">{{ cms_text($forms->field('app_subtitle')) }}</p>

    @if(session('application_success'))
        <div class="alert alert-success" role="status"><i class="fa-solid fa-circle-check"></i><span>{{ session('application_success') }}</span></div>
    @elseif($e->any())
        <div class="alert alert-error" role="alert"><i class="fa-solid fa-circle-exclamation"></i><span>Please check the highlighted fields and try again.</span></div>
    @endif

    <form action="{{ route('application_submit') }}" method="post" novalidate>
        @csrf
        <input type="hidden" name="_form" value="application">
        <input type="hidden" name="_return" value="{{ $return ?? 'careers' }}">
        <div class="hp-field" aria-hidden="true">
            <label for="a-website">Website</label>
            <input type="text" id="a-website" name="website" tabindex="-1" autocomplete="off">
        </div>

        <div class="form-grid">
            <div class="field">
                <label for="a-first">First name <span class="req">*</span></label>
                <input id="a-first" name="first_name" class="input {{ $e->has('first_name') ? 'is-invalid' : '' }}" autocomplete="given-name" value="{{ $val('first_name') }}" required maxlength="80">
                @if($e->has('first_name'))<div class="error-text">{{ $e->first('first_name') }}</div>@endif
            </div>
            <div class="field">
                <label for="a-last">Last name <span class="req">*</span></label>
                <input id="a-last" name="last_name" class="input {{ $e->has('last_name') ? 'is-invalid' : '' }}" autocomplete="family-name" value="{{ $val('last_name') }}" required maxlength="80">
                @if($e->has('last_name'))<div class="error-text">{{ $e->first('last_name') }}</div>@endif
            </div>
            <div class="field">
                <label for="a-phone">Mobile phone <span class="req">*</span></label>
                <input id="a-phone" type="tel" name="phone" class="input {{ $e->has('phone') ? 'is-invalid' : '' }}" autocomplete="tel" value="{{ $val('phone') }}" required maxlength="40">
                @if($e->has('phone'))<div class="error-text">{{ $e->first('phone') }}</div>@endif
            </div>
            <div class="field">
                <label for="a-email">Email <span class="req">*</span></label>
                <input id="a-email" type="email" name="email" class="input {{ $e->has('email') ? 'is-invalid' : '' }}" autocomplete="email" value="{{ $val('email') }}" required maxlength="160">
                @if($e->has('email'))<div class="error-text">{{ $e->first('email') }}</div>@endif
            </div>
            <div class="field">
                <label for="a-state">State <span class="req">*</span></label>
                <select id="a-state" name="state" class="input {{ $e->has('state') ? 'is-invalid' : '' }}" required>
                    <option value="">Choose a state</option>
                    @foreach($states as $code => $name)
                    <option value="{{ $code }}" {{ $val('state') === $code ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                @if($e->has('state'))<div class="error-text">{{ $e->first('state') }}</div>@endif
            </div>
            <div class="field">
                <label for="a-exp">CDL-A experience <span class="req">*</span></label>
                <select id="a-exp" name="experience" class="input {{ $e->has('experience') ? 'is-invalid' : '' }}" required>
                    <option value="">Choose one</option>
                    @foreach($experience_options as $option)
                    <option value="{{ $option }}" {{ $val('experience') === $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
                @if($e->has('experience'))<div class="error-text">{{ $e->first('experience') }}</div>@endif
            </div>

            <div class="field full">
                <span class="field-label" id="a-position-label">Position</span>
                <div class="segmented" role="radiogroup" aria-labelledby="a-position-label">
                    @foreach($positions as $option)
                    <label>
                        <input type="radio" name="position" value="{{ $option }}" {{ $val('position', $positions[0] ?? '') === $option ? 'checked' : '' }}>
                        <span>{{ $option }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <label class="consent full">
                <input type="checkbox" name="sms_consent" value="1" {{ $val('sms_consent') ? 'checked' : '' }}>
                <span>{{ \App\Http\Controllers\Front\FrontController::smsConsentText() }}@if($legal->isNotEmpty()) See our @foreach($legal as $link)<a href="{{ cms_url($link->link) }}">{{ cms_text($link->title) }}</a>{{ $loop->last ? '.' : ($loop->remaining === 1 ? ' and ' : ', ') }}@endforeach @endif</span>
            </label>

            <div class="full">
                <button type="submit" class="btn btn-dark btn-block">{{ cms_text($forms->field('app_button')) }}</button>
            </div>
        </div>
    </form>
</div>
