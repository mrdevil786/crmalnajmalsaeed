<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\InvoiceHelper;
use App\Helpers\PDFHelper;
use App\Models\Item;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Helpers\QRCodeHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InvoicesController extends Controller
{
    public function index()
    {
        $invoices = Invoice::where('type', 'invoice')->latest()->get();

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
            'vat_percentage' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'issue_date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $nextInvoiceNumber = InvoiceHelper::generateNumber('INV');

            $subtotal = array_reduce($request->items, function ($carry, $item) {
                return $carry + ($item['quantity'] * $item['price']);
            }, 0);

            $discounted_subtotal = $subtotal - ($request->discount ?? 0);
            $vat_amount = ($discounted_subtotal * $request->vat_percentage) / 100;
            $total = $discounted_subtotal + $vat_amount;

            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'invoice_number' => $nextInvoiceNumber,
                'type' => 'invoice',
                'issue_date' => $request->issue_date,
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

            $qrCodeData = QRCodeHelper::generateQRCodeDataUri($invoice);

            if ($qrCodeData) {
                PDFHelper::generateInvoicePdf($invoice->id, $qrCodeData);
                DB::commit();
                return redirect()->route('admin.invoices.index')->with('success', 'Invoice created successfully');
            } else {
                throw new \Exception('QR Code generation failed.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Invoice creation failed: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $invoice = Invoice::with('items.product')->findOrFail($id);
        $customers = Customer::all();
        $products = Product::all();

        return view('admin.invoices.create-edit-view', compact('invoice', 'customers', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vat_percentage' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'issue_date' => 'required|date',
            'items' => 'required|array',
            'items.*.id' => 'nullable|exists:items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $invoice = Invoice::findOrFail($id);

            $subtotal = array_reduce($request->items, function ($carry, $item) {
                return $carry + ($item['quantity'] * $item['price']);
            }, 0);

            $discounted_subtotal = $subtotal - ($request->discount ?? 0);
            $vat_amount = ($discounted_subtotal * $request->vat_percentage) / 100;
            $total = $discounted_subtotal + $vat_amount;

            $invoice->update([
                'customer_id' => $request->customer_id,
                'issue_date' => $request->issue_date,
                'vat_percentage' => $request->vat_percentage,
                'subtotal' => $subtotal,
                'discount' => $request->discount,
                'vat_amount' => $vat_amount,
                'total' => $total,
                'notes' => $request->notes,
            ]);

            $existingItemIds = array_column($request->items, 'id');
            $invoice->items()->whereNotIn('id', $existingItemIds)->delete();

            foreach ($request->items as $item) {
                if (isset($item['id'])) {
                    $existingItem = Item::find($item['id']);
                    $existingItem->update([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total' => $item['quantity'] * $item['price'],
                    ]);
                } else {
                    Item::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total' => $item['quantity'] * $item['price'],
                    ]);
                }
            }

            $qrCodeData = QRCodeHelper::generateQRCodeDataUri($invoice);
            PDFHelper::generateInvoicePdf($invoice->id, $qrCodeData);

            DB::commit();
            return redirect()->route('admin.invoices.index')->with('success', 'Invoice updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Invoice update failed: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);

        DB::beginTransaction();

        try {
            $invoice->items()->delete();
            $pdfPath = storage_path('app/public/invoices/' . $invoice->invoice_number . '.pdf');
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }
            $invoice->delete();

            DB::commit();

            return redirect()->route('admin.invoices.index')->with('success', 'Invoice deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Invoice deletion failed: ' . $e->getMessage()]);
        }
    }

    public function download($id)
    {
        $invoice = Invoice::findOrFail($id);
        $pdfPath = storage_path('app/public/invoices/' . $invoice->invoice_number . '.pdf');

        if (!file_exists($pdfPath)) {
            try {
                $qrCodeData = QRCodeHelper::generateQRCodeDataUri($invoice);
                PDFHelper::generateInvoicePdf($invoice->id, $qrCodeData);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['error' => 'PDF generation failed: ' . $e->getMessage()]);
            }
        }

        return response()->download($pdfPath);
    }

    public function stream($id)
    {
        $invoice = Invoice::findOrFail($id);
        $pdfPath = storage_path('app/public/invoices/' . $invoice->invoice_number . '.pdf');

        if (!file_exists($pdfPath)) {
            try {
                $qrCodeData = QRCodeHelper::generateQRCodeDataUri($invoice);
                PDFHelper::generateInvoicePdf($invoice->id, $qrCodeData);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['error' => 'PDF generation failed: ' . $e->getMessage()]);
            }
        }

        return response()->stream(function () use ($pdfPath) {
            return readfile($pdfPath);
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($pdfPath) . '"',
        ]);
    }
}
