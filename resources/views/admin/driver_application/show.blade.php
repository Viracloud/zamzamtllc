@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="page-heading mb-0">
        <a href="{{ route('admin_driver_application_index') }}" class="text-sm text-primary-600 text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i> All applications</a>
        <h1 class="mt-2">{{ $application->full_name }}</h1>
        <p>{{ $application->position }} &middot; received {{ $application->created_at->format('F j, Y \a\t g:i A') }} ({{ $application->created_at->diffForHumans() }})</p>
    </div>
    @include('admin.partials.status', ['status' => $application->status])
</div>

<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card-custom-p mb-4">
            <h2 class="section-title">Applicant</h2>
            <dl class="detail-list">
                <div><dt>Name</dt><dd>{{ $application->full_name }}</dd></div>
                <div><dt>Mobile phone</dt><dd><a href="tel:{{ preg_replace('/[^\d+]/', '', $application->phone) }}">{{ $application->phone }}</a></dd></div>
                <div><dt>Email</dt><dd><a href="mailto:{{ $application->email }}">{{ $application->email }}</a></dd></div>
                <div><dt>State</dt><dd>{{ \App\Models\DriverApplication::STATES[$application->state] ?? $application->state }}</dd></div>
                <div><dt>CDL-A experience</dt><dd>{{ $application->experience }}</dd></div>
                <div><dt>Position</dt><dd>{{ $application->position }}</dd></div>
            </dl>
        </div>
        <div class="card-custom-p">
            <h2 class="section-title">SMS consent</h2>
            <dl class="detail-list">
                <div><dt>Opted in</dt><dd>{!! $application->sms_consent ? '<span class="status-pill status-hired">Yes</span>' : '<span class="status-pill status-closed">No — do not send recruiting texts</span>' !!}</dd></div>
                @if($application->sms_consent)
                <div><dt>Consent given</dt><dd>{{ $application->created_at->format('F j, Y g:i:s A T') }}</dd></div>
                <div><dt>Wording shown</dt><dd class="text-gray-600">{{ $application->sms_consent_text }}</dd></div>
                @endif
                <div><dt>IP address</dt><dd class="text-gray-500">{{ $application->ip_address ?: '—' }}</dd></div>
            </dl>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card-custom-p mb-4">
            <h2 class="section-title">Status</h2>
            <form action="{{ route('admin_driver_application_status', $application->id) }}" method="post">
                @csrf
                <select name="status" class="form-control-custom mb-3">
                    @foreach(\App\Models\DriverApplication::STATUSES as $s)
                        <option value="{{ $s }}" {{ $application->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-admin-primary w-100">Update Status</button>
            </form>
        </div>
        <div class="card-custom-p">
            <h2 class="section-title">Danger zone</h2>
            <button type="button" class="btn-admin-delete w-100" data-bs-toggle="modal" data-bs-target="#modal_delete">Delete Application</button>
        </div>
    </div>
</div>

@include('admin.partials.delete-modal', ['label' => 'Application', 'action' => route('admin_driver_application_delete', $application->id)])
@endsection
