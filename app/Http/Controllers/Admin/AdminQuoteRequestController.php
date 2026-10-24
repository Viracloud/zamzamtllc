<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminQuoteRequestController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $quotes = QuoteRequest::when(in_array($status, QuoteRequest::STATUSES), fn ($q) => $q->where('status', $status))
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.quote_request.index', compact('quotes', 'status'));
    }

    public function show($id)
    {
        $quote = QuoteRequest::findOrFail($id);
        return view('admin.quote_request.show', compact('quote'));
    }

    public function change_status(Request $request, $id)
    {
        $request->validate(['status' => ['required', Rule::in(QuoteRequest::STATUSES)]]);
        QuoteRequest::findOrFail($id)->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status updated.');
    }

    public function delete($id)
    {
        QuoteRequest::where('id', $id)->delete();
        return redirect()->route('admin_quote_request_index')->with('success', 'Quote request deleted.');
    }

    public function export()
    {
        $columns = ['id', 'created_at', 'status', 'pickup_location', 'delivery_location', 'pickup_date', 'weight', 'equipment', 'full_name', 'company', 'email', 'phone', 'notes'];
        return $this->csvDownload('quote-requests-' . date('Y-m-d') . '.csv', $columns, QuoteRequest::orderBy('id', 'desc')->get());
    }
}
