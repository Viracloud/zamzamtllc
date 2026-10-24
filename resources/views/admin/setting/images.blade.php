@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Logo &amp; Images</h1>
    <p>Upload JPG, PNG or WebP files up to 5 MB. Empty slots use the built-in default.</p>
</div>

@php($defaults = [
    'logo' => 'assets/front/img/brand/logo-240.png',
    'favicon' => 'assets/front/img/brand/favicon-64.png',
    'hero_image' => 'assets/front/img/hero.jpg',
    'about_image' => 'assets/front/img/about.jpg',
])
@php($photos = ['hero_image', 'about_image'])

<form action="{{ route('admin_setting_images_update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        @foreach($images as $field => [$label, $help])
        <div class="col-12 col-md-6">
            <div class="card-custom-p h-100">
                <h2 class="section-title">{{ $label }}</h2>
                <div class="image-preview {{ in_array($field, $photos) ? 'is-photo' : '' }}">
                    @if($setting->{$field})
                        <img src="{{ asset('uploads/'.$setting->{$field}) }}" alt="{{ $label }}">
                    @else
                        <img src="{{ asset($defaults[$field]) }}" alt="Default {{ $label }}">
                    @endif
                </div>
                <p class="text-sm text-gray-500">{{ $help }}</p>
                <input type="file" name="{{ $field }}" accept="image/jpeg,image/png,image/webp" class="form-control-custom">
                @if($setting->{$field})
                    <button type="submit" form="reset-{{ $field }}" class="btn btn-link btn-sm text-danger px-0 mt-2">Remove and use default</button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="card-custom-p mt-4">
        <button type="submit" class="btn-admin-primary">Upload Images</button>
    </div>
</form>

@foreach($images as $field => $meta)
    @if($setting->{$field})
    <form id="reset-{{ $field }}" action="{{ route('admin_setting_image_reset', $field) }}" method="post" class="d-none">@csrf</form>
    @endif
@endforeach
@endsection
