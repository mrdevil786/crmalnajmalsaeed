<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VatReturn;
use App\Models\Invoice;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VatReturnsController extends Controller
{
    public function index()
    {
        $vatReturns = VatReturn::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.vat-returns.index', compact('vatReturns'));
    }

    public function create()
    {
        // Redirect to index since we're using modal now
        return redirect()->route('admin.vat-returns.index');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'period_from' => 'required|date',
            'period_to' => 'required|date|after:period_from'
        ]);

        $periodFrom = Carbon::parse($request->period_from);
        $periodTo = Carbon::parse($request->period_to);

        // Calculate total sales and output VAT using Eloquent
        $invoices = Invoice::whereBetween('issue_date', [$periodFrom, $periodTo])
            ->where('type', 'invoice')
            ->get();

        $totalSales = $invoices->sum('total');
        $outputVat = $invoices->sum('vat_amount');

        // Calculate total purchases and input VAT using Eloquent (only completed purchases)
        $purchases = Purchase::whereBetween('purchase_date', [$periodFrom, $periodTo])
            ->where('status', 'completed')
            ->get();

        $totalPurchases = $purchases->sum('total');
        $inputVat = $purchases->sum('tax_amount');

        $data = [
            'period_from' => $periodFrom,
            'period_to' => $periodTo,
            'total_sales' => $totalSales,
            'total_purchases' => $totalPurchases,
            'output_vat' => $outputVat,
            'input_vat' => $inputVat,
            'net_vat_payable' => $outputVat - $inputVat
        ];

        return view('admin.vat-returns.preview', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'period_from' => 'required|date',
            'period_to' => 'required|date|after:period_from',
            'total_sales' => 'required|numeric',
            'total_purchases' => 'required|numeric',
            'output_vat' => 'required|numeric',
            'input_vat' => 'required|numeric',
            'net_vat_payable' => 'required|numeric',
            'notes' => 'nullable|string'
        ]);

        $vatReturn = VatReturn::create($request->all());

        return redirect()->route('admin.vat-returns.index')
            ->with('success', 'VAT Return has been submitted successfully.');
    }

    public function show(VatReturn $vatReturn)
    {
        $data = [
            'period_from' => $vatReturn->period_from,
            'period_to' => $vatReturn->period_to,
            'total_sales' => $vatReturn->total_sales,
            'total_purchases' => $vatReturn->total_purchases,
            'output_vat' => $vatReturn->output_vat,
            'input_vat' => $vatReturn->input_vat,
            'net_vat_payable' => $vatReturn->net_vat_payable,
            'notes' => $vatReturn->notes,
            'status' => $vatReturn->status
        ];

        return view('admin.vat-returns.preview', compact('data'));
    }

    public function updateStatus(VatReturn $vatReturn)
    {
        if ($vatReturn->status !== 'draft') {
            return back()->with('error', 'Only draft VAT returns can be submitted.');
        }

        $vatReturn->update([
            'status' => 'submitted',
            'updated_at' => now()
        ]);

        return back()->with('success', 'VAT return has been submitted successfully.');
    }

    public function destroy(VatReturn $vatReturn)
    {
        if ($vatReturn->status === 'submitted') {
            return back()->with('error', 'Submitted VAT returns cannot be deleted.');
        }

        $vatReturn->delete();

        return back()->with('success', 'VAT return has been deleted successfully.');
    }
}
