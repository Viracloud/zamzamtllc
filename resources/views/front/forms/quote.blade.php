{{-- Quote form card. Pass $return = route name of the page it sits on. --}}
@php($forms = cms_global('forms'))
@php($equipment_options = cms_options('equipment'))
@php($e = $errors->quote)
@php($mine = old('_form') === 'quote')
@php($val = fn ($field, $default = '') => $mine ? old($field, $default) : $default)
<div class="card form-card reveal" id="quote-form">
    <h3>{{ cms_text($forms->field('quote_title')) }}</h3>
    <p class="form-sub">{{ cms_text($forms->field('quote_subtitle')) }}</p>

    @if(session('quote_success'))
        <div class="alert alert-success" role="status"><i class="fa-solid fa-circle-check"></i><span>{{ session('quote_success') }}</span></div>
    @elseif($e->any())
        <div class="alert alert-error" role="alert"><i class="fa-solid fa-circle-exclamation"></i><span>Please check the highlighted fields and try again.</span></div>
    @endif

    <form action="{{ route('quote_submit') }}" method="post" novalidate>
        @csrf
        <input type="hidden" name="_form" value="quote">
        <input type="hidden" name="_return" value="{{ $return ?? 'quote' }}">
        <div class="hp-field" aria-hidden="true">
            <label for="q-website">Website</label>
            <input type="text" id="q-website" name="website" tabindex="-1" autocomplete="off">
        </div>

        <div class="form-grid">
            <div class="field">
                <label for="q-pickup">Pickup city &amp; state <span class="req">*</span></label>
                <input id="q-pickup" name="pickup_location" class="input {{ $e->has('pickup_location') ? 'is-invalid' : '' }}" placeholder="Chicago, IL" value="{{ $val('pickup_location') }}" required maxlength="120">
                @if($e->has('pickup_location'))<div class="error-text">{{ $e->first('pickup_location') }}</div>@endif
            </div>
            <div class="field">
                <label for="q-delivery">Delivery city &amp; state <span class="req">*</span></label>
                <input id="q-delivery" name="delivery_location" class="input {{ $e->has('delivery_location') ? 'is-invalid' : '' }}" placeholder="Dallas, TX" value="{{ $val('delivery_location') }}" required maxlength="120">
                @if($e->has('delivery_location'))<div class="error-text">{{ $e->first('delivery_location') }}</div>@endif
            </div>
            <div class="field">
                <label for="q-date">Pickup date</label>
                <input id="q-date" type="date" name="pickup_date" class="input {{ $e->has('pickup_date') ? 'is-invalid' : '' }}" min="{{ date('Y-m-d') }}" value="{{ $val('pickup_date') }}">
                @if($e->has('pickup_date'))<div class="error-text">{{ $e->first('pickup_date') }}</div>@endif
            </div>
            <div class="field">
                <label for="q-weight">Weight (lbs)</label>
                <input id="q-weight" type="number" name="weight" class="input {{ $e->has('weight') ? 'is-invalid' : '' }}" placeholder="42,000" min="1" max="80000" inputmode="numeric" value="{{ $val('weight') }}">
                @if($e->has('weight'))<div class="error-text">{{ $e->first('weight') }}</div>@endif
            </div>

            <div class="field full">
                <span class="field-label" id="q-equipment-label">Equipment</span>
                <div class="segmented" role="radiogroup" aria-labelledby="q-equipment-label">
                    @foreach($equipment_options as $option)
                    <label>
                        <input type="radio" name="equipment" value="{{ $option }}" {{ $val('equipment', $equipment_options[0] ?? '') === $option ? 'checked' : '' }}>
                        <span>{{ $option }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="field-group-title">Your details</div>
            <div class="field">
                <label for="q-name">Full name <span class="req">*</span></label>
                <input id="q-name" name="full_name" class="input {{ $e->has('full_name') ? 'is-invalid' : '' }}" autocomplete="name" value="{{ $val('full_name') }}" required maxlength="120">
                @if($e->has('full_name'))<div class="error-text">{{ $e->first('full_name') }}</div>@endif
            </div>
            <div class="field">
                <label for="q-company">Company</label>
                <input id="q-company" name="company" class="input" autocomplete="organization" value="{{ $val('company') }}" maxlength="120">
            </div>
            <div class="field">
                <label for="q-email">Email <span class="req">*</span></label>
                <input id="q-email" type="email" name="email" class="input {{ $e->has('email') ? 'is-invalid' : '' }}" autocomplete="email" value="{{ $val('email') }}" required maxlength="160">
                @if($e->has('email'))<div class="error-text">{{ $e->first('email') }}</div>@endif
            </div>
            <div class="field">
                <label for="q-phone">Phone</label>
                <input id="q-phone" type="tel" name="phone" class="input" autocomplete="tel" value="{{ $val('phone') }}" maxlength="40">
            </div>
            <div class="field full">
                <label for="q-notes">Commodity &amp; notes</label>
                <textarea id="q-notes" name="notes" class="input" placeholder="Commodity, pallet count, appointment times, special requirements" maxlength="2000">{{ $val('notes') }}</textarea>
            </div>
            <div class="full">
                <button type="submit" class="btn btn-primary btn-block">{{ cms_text($forms->field('quote_button')) }}</button>
            </div>
        </div>
    </form>
</div>
