@extends('admin.layouts.master')
@section('content')
@php($exists = $service->exists)
<div class="page-heading">
    <a href="{{ route('admin_service_index') }}" class="text-sm text-primary-600 text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i> All services</a>
    <h1 class="mt-2">{{ $exists ? $service->title : 'New Service' }}</h1>
    @if($exists)<p><a href="{{ $service->url() }}" target="_blank">{{ $service->url() }} <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i></a></p>@endif
</div>

@include('admin.content.partials.icon-list')

<form action="{{ $exists ? route('admin_service_update', $service->id) : route('admin_service_store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="card-custom-p mb-4">
                <h2 class="section-title">Content</h2>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="label-custom" for="title">Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $service->title) }}" class="form-control-custom" required>
                    </div>
                    <div class="col-md-4">
                        <label class="label-custom" for="slug">Address</label>
                        <div class="input-prefix"><span>/services/</span><input type="text" id="slug" name="slug" value="{{ old('slug', $service->slug) }}" class="form-control-custom" placeholder="auto"></div>
                    </div>
                </div>
                <div class="mt-3">
                    @include('admin.content.partials.field', ['name' => 'short', 'id' => 'short', 'def' => ['Short description (cards and banner)', 'text'], 'value' => old('short', $service->short)])
                    @include('admin.content.partials.field', ['name' => 'intro', 'id' => 'intro', 'def' => ['Introduction', 'textarea', 'Separate paragraphs with an empty line. The first paragraph is also shown on the Services page.'], 'value' => old('intro', $service->intro)])
                    @include('admin.content.partials.field', ['name' => 'features', 'id' => 'features', 'def' => ["What's included", 'lines', 'One per line.'], 'value' => old('features', $service->features)])
                    @include('admin.content.partials.field', ['name' => 'ideal', 'id' => 'ideal', 'def' => ['Ideal for', 'lines', 'One per line.'], 'value' => old('ideal', $service->ideal)])
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card-custom-p mb-4">
                <h2 class="section-title">Photo &amp; icon</h2>
                <div class="image-preview is-photo">
                    @if($service->image)<img src="{{ cms_image($service->image) }}" alt="">@else<span class="text-sm text-gray-500">No photo</span>@endif
                </div>
                <input type="file" name="image_upload" accept="image/jpeg,image/png,image/webp" class="form-control-custom mb-3">
                @include('admin.content.partials.field', ['name' => 'image_alt', 'id' => 'image_alt', 'def' => ['Photo description (for accessibility & SEO)', 'text'], 'value' => old('image_alt', $service->image_alt)])
                @include('admin.content.partials.field', ['name' => 'icon', 'id' => 'icon', 'def' => ['Icon', 'icon'], 'value' => old('icon', $service->icon)])
            </div>
            <div class="card-custom-p mb-4">
                <h2 class="section-title">SEO</h2>
                @include('admin.content.partials.field', ['name' => 'meta_title', 'id' => 'meta_title', 'def' => ['SEO title', 'text', 'Leave empty to use "Title | Company".'], 'value' => old('meta_title', $service->meta_title)])
                @include('admin.content.partials.field', ['name' => 'meta_description', 'id' => 'meta_description', 'def' => ['SEO description', 'textarea'], 'value' => old('meta_description', $service->meta_description)])
            </div>
            <div class="card-custom-p">
                <label class="d-flex align-items-center gap-2 mb-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" class="form-check-input m-0" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                    <span class="text-sm">Visible on the website</span>
                </label>
                <button type="submit" class="btn-admin-primary w-100">{{ $exists ? 'Save Service' : 'Create Service' }}</button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
@include('admin.content.partials.editor-scripts')
@endpush
