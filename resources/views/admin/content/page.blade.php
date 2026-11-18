@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
    <div class="page-heading mb-0">
        <a href="{{ route('admin_content_pages') }}" class="text-sm text-primary-600 text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i> All pages</a>
        <h1 class="mt-2">{{ $page->name }}</h1>
        @if($page->key !== 'service')
        <p><a href="{{ $page->url() }}" target="_blank">{{ $page->url() }} <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i></a></p>
        @else
        <p>These sections appear below the content of every service page.</p>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-8">
        <h6 class="cus-heading">Sections (top to bottom)</h6>
        <div class="card-custom-p mb-4">
            @forelse($sections as $section)
                <div class="section-row {{ $section->is_active ? '' : 'is-hidden' }}">
                    <div class="d-flex flex-column gap-1">
                        <form action="{{ route('admin_content_section_move', [$section->id, 'up']) }}" method="post">@csrf<button class="move-btn" title="Move up" {{ $loop->first ? 'disabled' : '' }}><i class="fa-solid fa-chevron-up"></i></button></form>
                        <form action="{{ route('admin_content_section_move', [$section->id, 'down']) }}" method="post">@csrf<button class="move-btn" title="Move down" {{ $loop->last ? 'disabled' : '' }}><i class="fa-solid fa-chevron-down"></i></button></form>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <a href="{{ route('admin_content_section_edit', $section->id) }}" class="row-link fw-semibold text-decoration-none">{{ $section->adminLabel() }}</a>
                        <div class="text-xs text-gray-500 text-truncate">
                            {{ $section->typeDefinition()['label'] }}
                            @if($section->title) · {{ \Illuminate\Support\Str::limit(cms_text($section->title), 60) }}@endif
                            @if($section->items_count) · {{ $section->items_count }} items @endif
                        </div>
                    </div>
                    <div class="d-flex gap-1 align-items-center">
                        <form action="{{ route('admin_content_section_toggle', $section->id) }}" method="post">@csrf
                            <button class="btn-icon" title="{{ $section->is_active ? 'Hide' : 'Show' }}"><i class="fa-solid {{ $section->is_active ? 'fa-eye' : 'fa-eye-slash' }} text-xs"></i></button>
                        </form>
                        <a href="{{ route('admin_content_section_edit', $section->id) }}" class="btn-icon btn-icon-edit" title="Edit"><i class="fa-solid fa-pen text-xs"></i></a>
                        <form action="{{ route('admin_content_section_duplicate', $section->id) }}" method="post">@csrf
                            <button class="btn-icon" title="Duplicate"><i class="fa-solid fa-copy text-xs"></i></button>
                        </form>
                        <form action="{{ route('admin_content_section_delete', $section->id) }}" method="post" onsubmit="return confirm('Delete this section and all its items?')">@csrf
                            <button class="btn-icon btn-icon-delete" title="Delete"><i class="fa-solid fa-trash text-xs"></i></button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500 mb-0">This page has no sections yet. Add one from the list below.</p>
            @endforelse
        </div>

        <h6 class="cus-heading">Add a section</h6>
        <div class="type-grid mb-4">
            @foreach($types as $typeKey => $type)
            <form action="{{ route('admin_content_section_store', $page->id) }}" method="post" class="type-card">
                @csrf
                <input type="hidden" name="type" value="{{ $typeKey }}">
                <strong>{{ $type['label'] }}</strong>
                <span>{{ $type['description'] ?? '' }}</span>
                <button type="submit" class="btn-admin-primary-sm mt-auto"><i class="fa-solid fa-plus me-1"></i> Add</button>
            </form>
            @endforeach
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <h6 class="cus-heading">Page settings</h6>
        <form action="{{ route('admin_content_page_update', $page->id) }}" method="post" class="card-custom-p mb-4">
            @csrf
            <div class="mb-3">
                <label class="label-custom" for="p-name">Page name</label>
                <input type="text" id="p-name" name="name" value="{{ old('name', $page->name) }}" class="form-control-custom">
                <div class="field-hint">Used in the breadcrumb of service pages and in the admin.</div>
            </div>
            @if($page->slug !== null)
            <div class="mb-3">
                <label class="label-custom" for="p-slug">Address</label>
                <div class="input-prefix"><span>/</span><input type="text" id="p-slug" name="slug" value="{{ old('slug', $page->slug) }}" class="form-control-custom"></div>
                <div class="field-hint">If you change it, update menu and button links that point to this page.</div>
            </div>
            @endif
            @if($page->key !== 'service')
            @include('admin.partials.seo-fields', [
                'title' => old('meta_title', $page->meta_title),
                'description' => old('meta_description', $page->meta_description),
                'fallbackTitle' => $page->key === 'home' ? $global_setting->company_name : $page->name . ' | ' . $global_setting->company_name,
                'fallbackDescription' => '',
                'url' => $page->url(),
                'noindex' => $page->key === 'home' ? null : (bool) old('noindex', $page->noindex),
            ])
            @endif
            @if($page->key !== 'home')
            <label class="d-flex align-items-center gap-2 mb-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" class="form-check-input m-0" name="is_active" value="1" {{ $page->is_active ? 'checked' : '' }}>
                <span class="text-sm">Page is visible on the website</span>
            </label>
            @endif
            <button type="submit" class="btn-admin-primary w-100">Save Page Settings</button>
        </form>

        @unless($page->is_system)
        <form action="{{ route('admin_content_page_delete', $page->id) }}" method="post" class="card-custom-p" onsubmit="return confirm('Delete this page and all its sections?')">
            @csrf
            <h2 class="section-title">Danger zone</h2>
            <button type="submit" class="btn-admin-delete w-100">Delete Page</button>
        </form>
        @endunless
    </div>
</div>
@endsection
