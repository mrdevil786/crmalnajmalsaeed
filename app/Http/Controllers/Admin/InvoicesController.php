<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoicesController extends Controller
{
    public function index()
    {
        $invoices = Invoice::all();
        return view('admin.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('admin.invoices.create-edit-view', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'type' => 'required|in:invoice,quote',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date',
            'vat_percentage' => 'required|numeric',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $lastInvoice = Invoice::orderBy('id', 'desc')->first();
            $nextInvoiceNumber = $lastInvoice ? 'INV-' . str_pad((int)substr($lastInvoice->invoice_number, 4) + 1, 6, '0', STR_PAD_LEFT) : 'INV-000001';

            $subtotal = array_reduce($request->items, function ($carry, $item) {
                return $carry + ($item['quantity'] * $item['price']);
            }, 0);

            $vat_amount = ($subtotal * $request->vat_percentage) / 100;
            $total = $subtotal + $vat_amount - ($request->discount ?? 0);

            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'invoice_number' => $nextInvoiceNumber,
                'type' => $request->type,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'vat_percentage' => $request->vat_percentage,
                'subtotal' => $subtotal,
                'discount' => $request->discount,
                'vat_amount' => $vat_amount,
                'total' => $total,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                Item::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.invoices.index')->with('success', 'Invoice created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Invoice creation failed: ' . $e->getMessage()]);
        }
    }
}
