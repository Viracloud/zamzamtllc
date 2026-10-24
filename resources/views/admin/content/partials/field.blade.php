{{-- One schema field. Params: $name (input name), $def [label, type, help?, options?], $value, $id --}}
@php([$label, $type] = $def)
@php($help = $def[2] ?? null)
@php($options = $def[3] ?? [])
<div class="mb-3">
    @if($type === 'toggle')
        <label class="d-flex align-items-center gap-2 cursor-pointer">
            <input type="hidden" name="{{ $name }}" value="0">
            <input type="checkbox" class="form-check-input m-0" name="{{ $name }}" value="1" {{ $value ? 'checked' : '' }}>
            <span class="label-custom mb-0">{{ $label }}</span>
        </label>
    @else
        <label class="label-custom" for="{{ $id }}">{{ $label }}</label>

        @switch($type)
            @case('textarea')
                <textarea id="{{ $id }}" name="{{ $name }}" rows="3" class="form-control-custom">{{ $value }}</textarea>
                @break
            @case('lines')
                <textarea id="{{ $id }}" name="{{ $name }}" rows="5" class="form-control-custom font-mono-sm">{{ $value }}</textarea>
                @break
            @case('html')
                <textarea id="{{ $id }}" name="{{ $name }}" class="html-editor">{{ $value }}</textarea>
                @break
            @case('icon')
                <div class="icon-field">
                    <span class="icon-preview"><i class="fa-solid {{ $value ?: 'fa-icons' }}"></i></span>
                    <input type="text" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" class="form-control-custom icon-input" list="fa-icons" placeholder="fa-truck" autocomplete="off">
                </div>
                @break
            @case('select')
                <select id="{{ $id }}" name="{{ $name }}" class="form-control-custom">
                    @foreach($options as $optValue => $optLabel)
                        <option value="{{ $optValue }}" {{ (string) $value === (string) $optValue ? 'selected' : '' }}>{{ $optLabel }}</option>
                    @endforeach
                </select>
                @break
            @case('image')
                <div class="d-flex gap-3 align-items-start flex-wrap">
                    <div class="image-preview is-photo mb-0" style="width: 220px; height: 130px">
                        @if($value)<img src="{{ cms_image($value) }}" alt="">@else<span class="text-sm text-gray-500">No image</span>@endif
                    </div>
                    <div class="flex-grow-1" style="min-width: 220px">
                        <input type="file" id="{{ $id }}" name="image_upload" accept="image/jpeg,image/png,image/webp" class="form-control-custom">
                        @if($value && !str_starts_with($value, 'assets/'))
                        <label class="d-flex align-items-center gap-2 mt-2 text-sm"><input type="checkbox" class="form-check-input m-0" name="image_remove" value="1"> Remove uploaded image (use default)</label>
                        @endif
                    </div>
                </div>
                @break
            @default
                <input type="text" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" class="form-control-custom" @if($type === 'link') placeholder="/about, https://…, mailto:…" @endif>
        @endswitch
    @endif

    @if($help)<div class="field-hint">{{ $help }}</div>@endif
</div>
