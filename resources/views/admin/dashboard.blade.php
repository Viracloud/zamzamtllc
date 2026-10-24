@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Welcome back, {{ Auth::guard('admin')->user()->name }}</h1>
    <p>Here's what's new on the website.</p>
</div>

@if(count($missing_fields))
<div class="alert-setup">
    <i class="fa-solid fa-triangle-exclamation"></i>
    <div>
        <strong>The website still shows placeholder values for:</strong>
        {{ implode(', ', $missing_fields) }}.
        <a href="{{ route('admin_setting_company') }}">Fill them in under Company Settings &rarr;</a>
        @if(!$global_setting->notificationEmail())
            <br><small>Until a real dispatch email is set, new quotes and applications are saved here but no notification email is sent.</small>
        @endif
    </div>
</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="{{ route('admin_quote_request_index', ['status' => 'New']) }}" class="card-custom d-block text-decoration-none">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box bg-blue-100 text-primary-600"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                <div>
                    <p class="text-sm text-gray-500 mb-0">New Quote Requests</p>
                    <p class="text-2xl fw-bold mb-0 text-gray-900">{{ number_format($new_quotes) }}</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="{{ route('admin_quote_request_index') }}" class="card-custom d-block text-decoration-none">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box bg-green-100 text-green-600"><i class="fa-solid fa-truck-fast"></i></div>
                <div>
                    <p class="text-sm text-gray-500 mb-0">All Quote Requests</p>
                    <p class="text-2xl fw-bold mb-0 text-gray-900">{{ number_format($total_quotes) }}</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="{{ route('admin_driver_application_index', ['status' => 'New']) }}" class="card-custom d-block text-decoration-none">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box bg-orange-100 text-orange-600"><i class="fa-solid fa-id-card"></i></div>
                <div>
                    <p class="text-sm text-gray-500 mb-0">New Driver Applications</p>
                    <p class="text-2xl fw-bold mb-0 text-gray-900">{{ number_format($new_applications) }}</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="{{ route('admin_driver_application_index') }}" class="card-custom d-block text-decoration-none">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box bg-purple-100 text-purple-600"><i class="fa-solid fa-users"></i></div>
                <div>
                    <p class="text-sm text-gray-500 mb-0">All Applications</p>
                    <p class="text-2xl fw-bold mb-0 text-gray-900">{{ number_format($total_applications) }}</p>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-6">
        <div class="card-custom-p h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title mb-0">Latest Quote Requests</h2>
                <a href="{{ route('admin_quote_request_index') }}" class="text-sm text-primary-600 text-decoration-none">View all</a>
            </div>
            @if($recent_quotes->isEmpty())
                <p class="text-sm text-gray-500 mb-0">No quote requests yet.</p>
            @else
            <div class="overflow-x-auto">
            <table class="table-simple">
                <thead><tr><th>Lane</th><th>Contact</th><th>Status</th><th>Received</th></tr></thead>
                <tbody>
                @foreach($recent_quotes as $quote)
                    <tr>
                        <td><a class="row-link" href="{{ route('admin_quote_request_show', $quote->id) }}">{{ $quote->pickup_location }} &rarr; {{ $quote->delivery_location }}</a></td>
                        <td>{{ $quote->full_name }}</td>
                        <td>@include('admin.partials.status', ['status' => $quote->status])</td>
                        <td class="text-gray-500 text-nowrap">{{ $quote->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
            @endif
        </div>
    </div>
    <div class="col-12 col-xl-6">
        <div class="card-custom-p h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title mb-0">Latest Driver Applications</h2>
                <a href="{{ route('admin_driver_application_index') }}" class="text-sm text-primary-600 text-decoration-none">View all</a>
            </div>
            @if($recent_applications->isEmpty())
                <p class="text-sm text-gray-500 mb-0">No applications yet.</p>
            @else
            <div class="overflow-x-auto">
            <table class="table-simple">
                <thead><tr><th>Name</th><th>Position</th><th>Status</th><th>Received</th></tr></thead>
                <tbody>
                @foreach($recent_applications as $application)
                    <tr>
                        <td><a class="row-link" href="{{ route('admin_driver_application_show', $application->id) }}">{{ $application->full_name }}</a></td>
                        <td>{{ $application->position }}</td>
                        <td>@include('admin.partials.status', ['status' => $application->status])</td>
                        <td class="text-gray-500 text-nowrap">{{ $application->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
