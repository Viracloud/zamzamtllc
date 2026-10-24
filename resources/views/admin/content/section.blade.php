@extends('admin.layouts.master')
@section('content')
@php($isGlobal = $section->page === 'global')
<div class="page-heading">
    @if($page)
        <a href="{{ route('admin_content_page_edit', $page->id) }}" class="text-sm text-primary-600 text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i> {{ $page->name }}</a>
    @else
        <a href="{{ route('admin_content_pages') }}" class="text-sm text-primary-600 text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i> Pages &amp; Sections</a>
    @endif
    <h1 class="mt-2">{{ $section->adminLabel() }}</h1>
    <p>{{ $type['description'] ?? '' }} {{ \App\Support\SectionTypes::TOKEN_HELP }}</p>
</div>

@include('admin.content.partials.icon-list')

<div class="row g-4">
    <div class="{{ count($type['groups']) ? 'col-12 col-xxl-5' : 'col-12 col-xl-8' }}">
        <form action="{{ route('admin_content_section_update', $section->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <h6 class="cus-heading">Content</h6>
            <div class="card-custom-p">
                @unless($isGlobal)
                <div class="row g-3 mb-1">
                    <div class="col-sm-8">
                        <label class="label-custom" for="s-name">Admin label (optional)</label>
                        <input type="text" id="s-name" name="name" value="{{ old('name', $section->name) }}" class="form-control-custom" placeholder="{{ $type['label'] }}">
                    </div>
                    <div class="col-sm-4 d-flex align-items-end">
                        <label class="d-flex align-items-center gap-2 mb-2">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" class="form-check-input m-0" name="is_active" value="1" {{ $section->is_active ? 'checked' : '' }}>
                            <span class="text-sm">Visible</span>
                        </label>
                    </div>
                </div>
                <hr class="my-3">
                @endunless

                @forelse($type['fields'] as $name => $def)
                    @include('admin.content.partials.field', [
                        'name' => "fields[$name]",
                        'id' => "f-$name",
                        'def' => $def,
                        'value' => old("fields.$name", $section->field($name)),
                    ])
                @empty
                    <p class="text-sm text-gray-500">This section has no text fields; edit its items.</p>
                @endforelse

                <button type="submit" class="btn-admin-primary">Save Section</button>
            </div>
        </form>
    </div>

    @if(count($type['groups']))
    <div class="col-12 col-xxl-7">
        @foreach($type['groups'] as $groupKey => [$groupLabel, $noun, $itemFields])
            @php($items = $section->items->where('group', $groupKey)->values())
            <h6 class="cus-heading" id="group-{{ $groupKey }}">{{ $groupLabel }} <span class="text-gray-400 fw-normal">({{ $items->count() }})</span></h6>

            @foreach($items as $item)
            <div class="item-card {{ $item->is_active ? '' : 'is-hidden' }}" id="item-{{ $item->id }}">
                <div class="item-card-head">
                    <div class="d-flex gap-1">
                        <form action="{{ route('admin_content_item_move', [$item->id, 'up']) }}" method="post">@csrf<button class="move-btn" title="Move up" {{ $loop->first ? 'disabled' : '' }}><i class="fa-solid fa-chevron-up"></i></button></form>
                        <form action="{{ route('admin_content_item_move', [$item->id, 'down']) }}" method="post">@csrf<button class="move-btn" title="Move down" {{ $loop->last ? 'disabled' : '' }}><i class="fa-solid fa-chevron-down"></i></button></form>
                    </div>
                    <button type="button" class="item-toggle" data-bs-toggle="collapse" data-bs-target="#item-body-{{ $item->id }}" aria-expanded="false">
                        @if($item->icon)<i class="fa-solid {{ $item->icon }} text-primary-600"></i>@endif
                        <span class="text-truncate">{{ \Illuminate\Support\Str::limit(cms_text($item->title ?: $item->value ?: $item->subtitle ?: ucfirst($noun)), 70) }}</span>
                        @unless($item->is_active)<span class="badge-soft">hidden</span>@endunless
                        <i class="fa-solid fa-chevron-down ms-auto text-gray-400"></i>
                    </button>
                    <form action="{{ route('admin_content_item_delete', $item->id) }}" method="post" onsubmit="return confirm('Delete this {{ $noun }}?')">@csrf
                        <button class="btn-icon btn-icon-delete" title="Delete"><i class="fa-solid fa-trash text-xs"></i></button>
                    </form>
                </div>
                <div class="collapse {{ old('_item') == $item->id ? 'show' : '' }}" id="item-body-{{ $item->id }}">
                    <form action="{{ route('admin_content_item_update', $item->id) }}" method="post" class="item-card-body">
                        @csrf
                        <input type="hidden" name="_item" value="{{ $item->id }}">
                        @foreach($itemFields as $fieldName => $def)
                            @include('admin.content.partials.field', [
                                'name' => "item[$fieldName]",
                                'id' => "i{$item->id}-$fieldName",
                                'def' => $def,
                                'value' => old('_item') == $item->id ? old("item.$fieldName") : $item->{$fieldName},
                            ])
                        @endforeach
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <label class="d-flex align-items-center gap-2 mb-0">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" class="form-check-input m-0" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }}>
                                <span class="text-sm">Visible</span>
                            </label>
                            <button type="submit" class="btn-admin-primary-sm">Save {{ $noun }}</button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach

            <div class="item-card item-card-new mb-4">
                <button type="button" class="item-toggle" data-bs-toggle="collapse" data-bs-target="#new-{{ $groupKey }}" aria-expanded="false">
                    <i class="fa-solid fa-plus text-primary-600"></i> <span>Add {{ $noun }}</span>
                </button>
                <div class="collapse {{ old('group') === $groupKey ? 'show' : '' }}" id="new-{{ $groupKey }}">
                    <form action="{{ route('admin_content_item_store', $section->id) }}" method="post" class="item-card-body">
                        @csrf
                        <input type="hidden" name="group" value="{{ $groupKey }}">
                        @foreach($itemFields as $fieldName => $def)
                            @include('admin.content.partials.field', [
                                'name' => "item[$fieldName]",
                                'id' => "new-$groupKey-$fieldName",
                                'def' => $def,
                                'value' => old('group') === $groupKey ? old("item.$fieldName") : null,
                            ])
                        @endforeach
                        <button type="submit" class="btn-admin-primary-sm"><i class="fa-solid fa-plus me-1"></i> Add {{ $noun }}</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>
@endsection

@push('scripts')
@include('admin.content.partials.editor-scripts')
@endpush
