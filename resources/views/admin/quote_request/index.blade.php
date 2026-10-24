@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="page-heading mb-0">
        <h1>Quote Requests</h1>
        <p>Requests sent from the "Request a Quote" form.</p>
    </div>
    <a href="{{ route('admin_quote_request_export') }}" class="btn-admin-primary"><i class="fa-solid fa-file-csv me-1"></i> Export CSV</a>
</div>

<div class="filter-pills">
    <a href="{{ route('admin_quote_request_index') }}" class="{{ !$status ? 'active' : '' }}">All</a>
    @foreach(\App\Models\QuoteRequest::STATUSES as $s)
        <a href="{{ route('admin_quote_request_index', ['status' => $s]) }}" class="{{ $status === $s ? 'active' : '' }}">{{ $s }}</a>
    @endforeach
</div>

<div class="card-custom-p5">
    <table id="listTable" class="table-dt stripe hover">
        <thead>
            <tr>
                <th>Received</th>
                <th>Lane</th>
                <th>Equipment</th>
                <th>Contact</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotes as $quote)
            <tr>
                <td data-order="{{ $quote->created_at->timestamp }}" class="text-nowrap">{{ $quote->created_at->format('M j, Y g:i A') }}</td>
                <td>
                    <strong>{{ $quote->pickup_location }} &rarr; {{ $quote->delivery_location }}</strong>
                    <div class="text-xs text-gray-500">
                        {{ $quote->pickup_date ? 'Pickup '.$quote->pickup_date->format('m/d/Y') : 'No pickup date' }}
                        @if($quote->weight) &middot; {{ number_format($quote->weight) }} lbs @endif
                    </div>
                </td>
                <td>{{ $quote->equipment }}</td>
                <td>
                    {{ $quote->full_name }}@if($quote->company)<div class="text-xs text-gray-500">{{ $quote->company }}</div>@endif
                </td>
                <td>@include('admin.partials.status', ['status' => $quote->status])</td>
                <td>
                    <a href="{{ route('admin_quote_request_show', $quote->id) }}" class="btn-icon btn-icon-edit" title="Open"><i class="fa-solid fa-eye text-xs"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@include('admin.partials.list-table-script')
@endsection
