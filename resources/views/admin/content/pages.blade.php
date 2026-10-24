@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Pages &amp; Sections</h1>
    <p>Every page of the website is built from sections. Open a page to edit, add, reorder, hide or delete its sections.</p>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card-custom-p">
            <div class="overflow-x-auto">
            <table class="table-simple">
                <thead><tr><th>Page</th><th>Address</th><th>Sections</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @foreach($pages as $page)
                    <tr>
                        <td>
                            <a class="row-link" href="{{ route('admin_content_page_edit', $page->id) }}">{{ $page->name }}</a>
                            @if($page->is_system)<span class="badge-soft ms-1">built-in</span>@endif
                        </td>
                        <td class="text-sm">
                            @if($page->key === 'service')
                                <span class="text-gray-500">shown under every service page</span>
                            @else
                                <a href="{{ $page->url() }}" target="_blank">/{{ $page->slug }}</a>
                            @endif
                        </td>
                        <td>{{ $page->sections_count }}</td>
                        <td>@include('admin.partials.status', ['status' => $page->is_active ? 'Visible' : 'Hidden'])</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin_content_page_edit', $page->id) }}" class="btn-icon btn-icon-edit" title="Edit"><i class="fa-solid fa-pen text-xs"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card-custom-p mb-4">
            <h2 class="section-title">Add a page</h2>
            <form action="{{ route('admin_content_page_store') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label class="label-custom" for="new-name">Page name</label>
                    <input type="text" id="new-name" name="name" value="{{ old('name') }}" class="form-control-custom" placeholder="e.g. Partners" required>
                </div>
                <div class="mb-3">
                    <label class="label-custom" for="new-slug">Address</label>
                    <div class="input-prefix"><span>{{ url('/') }}/</span><input type="text" id="new-slug" name="slug" value="{{ old('slug') }}" class="form-control-custom" placeholder="partners" required></div>
                    <div class="field-hint">Lowercase letters, numbers and dashes.</div>
                </div>
                <button type="submit" class="btn-admin-primary w-100">Create Page</button>
            </form>
        </div>
        <div class="card-custom-p">
            <h2 class="section-title">Site-wide content</h2>
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin_content_global', 'header') }}" class="preset-btn"><i class="fa-solid fa-bars"></i> Header &amp; menu</a>
                <a href="{{ route('admin_content_global', 'footer') }}" class="preset-btn"><i class="fa-solid fa-table-columns"></i> Footer</a>
                <a href="{{ route('admin_content_global', 'forms') }}" class="preset-btn"><i class="fa-solid fa-rectangle-list"></i> Forms &amp; service page labels</a>
                <a href="{{ route('admin_service_index') }}" class="preset-btn"><i class="fa-solid fa-truck-fast"></i> Services</a>
            </div>
        </div>
    </div>
</div>
@endsection
