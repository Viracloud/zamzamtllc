@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="page-heading mb-0">
        <h1>Driver Applications</h1>
        <p>Applications sent from the "Quick Application" form.</p>
    </div>
    <a href="{{ route('admin_driver_application_export') }}" class="btn-admin-primary"><i class="fa-solid fa-file-csv me-1"></i> Export CSV</a>
</div>

<div class="filter-pills">
    <a href="{{ route('admin_driver_application_index') }}" class="{{ !$status ? 'active' : '' }}">All</a>
    @foreach(\App\Models\DriverApplication::STATUSES as $s)
        <a href="{{ route('admin_driver_application_index', ['status' => $s]) }}" class="{{ $status === $s ? 'active' : '' }}">{{ $s }}</a>
    @endforeach
</div>

<div class="card-custom-p5">
    <table id="listTable" class="table-dt stripe hover">
        <thead>
            <tr>
                <th>Received</th>
                <th>Name</th>
                <th>Position</th>
                <th>Experience</th>
                <th>State</th>
                <th>Phone</th>
                <th>SMS</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $application)
            <tr>
                <td data-order="{{ $application->created_at->timestamp }}" class="text-nowrap">{{ $application->created_at->format('M j, Y g:i A') }}</td>
                <td><strong>{{ $application->full_name }}</strong><div class="text-xs text-gray-500">{{ $application->email }}</div></td>
                <td>{{ $application->position }}</td>
                <td>{{ $application->experience }}</td>
                <td>{{ $application->state }}</td>
                <td class="text-nowrap">{{ $application->phone }}</td>
                <td>{!! $application->sms_consent ? '<i class="fa-solid fa-check text-green-600" title="Opted in"></i>' : '<span class="text-gray-400">—</span>' !!}</td>
                <td>@include('admin.partials.status', ['status' => $application->status])</td>
                <td>
                    <a href="{{ route('admin_driver_application_show', $application->id) }}" class="btn-icon btn-icon-edit" title="Open"><i class="fa-solid fa-eye text-xs"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@include('admin.partials.list-table-script')
@endsection
