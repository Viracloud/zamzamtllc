<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverApplication;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminDriverApplicationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $applications = DriverApplication::when(in_array($status, DriverApplication::STATUSES), fn ($q) => $q->where('status', $status))
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.driver_application.index', compact('applications', 'status'));
    }

    public function show($id)
    {
        $application = DriverApplication::findOrFail($id);
        return view('admin.driver_application.show', compact('application'));
    }

    public function change_status(Request $request, $id)
    {
        $request->validate(['status' => ['required', Rule::in(DriverApplication::STATUSES)]]);
        DriverApplication::findOrFail($id)->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status updated.');
    }

    public function delete($id)
    {
        DriverApplication::where('id', $id)->delete();
        return redirect()->route('admin_driver_application_index')->with('success', 'Application deleted.');
    }

    public function export()
    {
        $columns = ['id', 'created_at', 'status', 'first_name', 'last_name', 'phone', 'email', 'state', 'experience', 'position', 'sms_consent', 'ip_address'];
        return $this->csvDownload('driver-applications-' . date('Y-m-d') . '.csv', $columns, DriverApplication::orderBy('id', 'desc')->get());
    }
}
