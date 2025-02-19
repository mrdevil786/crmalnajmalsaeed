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

        // Calculate total sales and output VAT
        $salesData = Invoice::whereBetween('issue_date', [$periodFrom, $periodTo])
            ->selectRaw('SUM(total) as total_sales, SUM(vat_amount) as output_vat')
            ->first();

        // Calculate total purchases and input VAT (only completed purchases)
        $purchasesData = Purchase::whereBetween('purchase_date', [$periodFrom, $periodTo])
            ->where('status', 'completed')
            ->selectRaw('SUM(total) as total_purchases, SUM(tax_amount) as input_vat')
            ->first();

        $data = [
            'period_from' => $periodFrom,
            'period_to' => $periodTo,
            'total_sales' => $salesData->total_sales ?? 0,
            'total_purchases' => $purchasesData->total_purchases ?? 0,
            'output_vat' => $salesData->output_vat ?? 0,
            'input_vat' => $purchasesData->input_vat ?? 0,
            'net_vat_payable' => ($salesData->output_vat ?? 0) - ($purchasesData->input_vat ?? 0)
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
        return view('admin.vat-returns.show', compact('vatReturn'));
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
}
