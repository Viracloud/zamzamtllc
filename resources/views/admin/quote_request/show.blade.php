@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="page-heading mb-0">
        <a href="{{ route('admin_quote_request_index') }}" class="text-sm text-primary-600 text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i> All quote requests</a>
        <h1 class="mt-2">{{ $quote->pickup_location }} &rarr; {{ $quote->delivery_location }}</h1>
        <p>Received {{ $quote->created_at->format('F j, Y \a\t g:i A') }} ({{ $quote->created_at->diffForHumans() }})</p>
    </div>
    @include('admin.partials.status', ['status' => $quote->status])
</div>

<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card-custom-p mb-4">
            <h2 class="section-title">Load details</h2>
            <dl class="detail-list">
                <div><dt>Pickup</dt><dd>{{ $quote->pickup_location }}</dd></div>
                <div><dt>Delivery</dt><dd>{{ $quote->delivery_location }}</dd></div>
                <div><dt>Pickup date</dt><dd>{{ $quote->pickup_date?->format('l, F j, Y') ?? '—' }}</dd></div>
                <div><dt>Weight</dt><dd>{{ $quote->weight ? number_format($quote->weight).' lbs' : '—' }}</dd></div>
                <div><dt>Equipment</dt><dd>{{ $quote->equipment }}</dd></div>
                <div><dt>Commodity &amp; notes</dt><dd>{!! $quote->notes ? nl2br(e($quote->notes)) : '—' !!}</dd></div>
            </dl>
        </div>
        <div class="card-custom-p">
            <h2 class="section-title">Contact</h2>
            <dl class="detail-list">
                <div><dt>Name</dt><dd>{{ $quote->full_name }}</dd></div>
                <div><dt>Company</dt><dd>{{ $quote->company ?: '—' }}</dd></div>
                <div><dt>Email</dt><dd><a href="mailto:{{ $quote->email }}?subject={{ rawurlencode('Rate quote: '.$quote->pickup_location.' to '.$quote->delivery_location) }}">{{ $quote->email }}</a></dd></div>
                <div><dt>Phone</dt><dd>@if($quote->phone)<a href="tel:{{ preg_replace('/[^\d+]/', '', $quote->phone) }}">{{ $quote->phone }}</a>@else — @endif</dd></div>
                <div><dt>IP address</dt><dd class="text-gray-500">{{ $quote->ip_address ?: '—' }}</dd></div>
            </dl>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card-custom-p mb-4">
            <h2 class="section-title">Status</h2>
            <form action="{{ route('admin_quote_request_status', $quote->id) }}" method="post">
                @csrf
                <select name="status" class="form-control-custom mb-3">
                    @foreach(\App\Models\QuoteRequest::STATUSES as $s)
                        <option value="{{ $s }}" {{ $quote->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-admin-primary w-100">Update Status</button>
            </form>
        </div>
        <div class="card-custom-p">
            <h2 class="section-title">Danger zone</h2>
            <button type="button" class="btn-admin-delete w-100" data-bs-toggle="modal" data-bs-target="#modal_delete">Delete Request</button>
        </div>
    </div>
</div>

@include('admin.partials.delete-modal', ['label' => 'Quote Request', 'action' => route('admin_quote_request_delete', $quote->id)])
@endsection
