@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="page-heading mb-0">
        <h1>Services</h1>
        <p>Each service gets its own page, a card on the home page, a row on the Services page and a link in the menu and footer.</p>
    </div>
    <a href="{{ route('admin_service_create') }}" class="btn-admin-primary"><i class="fa-solid fa-plus me-1"></i> Add Service</a>
</div>

<div class="card-custom-p">
    @forelse($services as $service)
    <div class="section-row {{ $service->is_active ? '' : 'is-hidden' }}">
        <div class="d-flex flex-column gap-1">
            <form action="{{ route('admin_service_move', [$service->id, 'up']) }}" method="post">@csrf<button class="move-btn" title="Move up" {{ $loop->first ? 'disabled' : '' }}><i class="fa-solid fa-chevron-up"></i></button></form>
            <form action="{{ route('admin_service_move', [$service->id, 'down']) }}" method="post">@csrf<button class="move-btn" title="Move down" {{ $loop->last ? 'disabled' : '' }}><i class="fa-solid fa-chevron-down"></i></button></form>
        </div>
        @if($service->image)<img src="{{ cms_image($service->image) }}" alt="" class="service-thumb">@endif
        <div class="flex-grow-1 min-w-0">
            <a href="{{ route('admin_service_edit', $service->id) }}" class="row-link fw-semibold text-decoration-none">@if($service->icon)<i class="fa-solid {{ $service->icon }} text-primary-600 me-1"></i>@endif {{ $service->title }}</a>
            <div class="text-xs text-gray-500 text-truncate">/services/{{ $service->slug }} · {{ $service->short }}</div>
        </div>
        <div class="d-flex gap-1 align-items-center">
            @include('admin.partials.status', ['status' => $service->is_active ? 'Visible' : 'Hidden'])
            <a href="{{ $service->url() }}" target="_blank" class="btn-icon" title="View"><i class="fa-solid fa-arrow-up-right-from-square text-xs"></i></a>
            <a href="{{ route('admin_service_edit', $service->id) }}" class="btn-icon btn-icon-edit" title="Edit"><i class="fa-solid fa-pen text-xs"></i></a>
            <form action="{{ route('admin_service_delete', $service->id) }}" method="post" onsubmit="return confirm('Delete this service?')">@csrf
                <button class="btn-icon btn-icon-delete" title="Delete"><i class="fa-solid fa-trash text-xs"></i></button>
            </form>
        </div>
    </div>
    @empty
    <p class="text-sm text-gray-500 mb-0">No services yet.</p>
    @endforelse
</div>
@endsection
