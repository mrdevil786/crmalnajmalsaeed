<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Invoice as PDFInvoice;

class QuotationsController extends Controller
{
    public function index()
    {
        $quotations = Invoice::where('type', 'quotation')->latest()->get();
        return view('admin.quotations.index', compact('quotations'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('admin.quotations.create-edit-view', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vat_percentage' => 'required|numeric',
            'due_date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $nextQuotationNumber = $this->generateInvoiceNumber();

            $subtotal = array_reduce($request->items, function ($carry, $item) {
                return $carry + ($item['quantity'] * $item['price']);
            }, 0);

            $vat_amount = ($subtotal * $request->vat_percentage) / 100;
            $total = $subtotal + $vat_amount - ($request->discount ?? 0);

            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'invoice_number' => $nextQuotationNumber,
                'type' => 'quotation',
                'issue_date' => now(),
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
            return redirect()->route('admin.quotations.index')->with('success', 'Quotation created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Quotation creation failed: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $quotation = Invoice::with('items.product')->findOrFail($id);
        $customers = Customer::all();
        $products = Product::all();

        return view('admin.quotations.create-edit-view', compact('quotation', 'customers', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vat_percentage' => 'required|numeric',
            'due_date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $invoice = Invoice::findOrFail($id);

            $subtotal = array_reduce($request->items, function ($carry, $item) {
                return $carry + ($item['quantity'] * $item['price']);
            }, 0);

            $vat_amount = ($subtotal * $request->vat_percentage) / 100;
            $total = $subtotal + $vat_amount - ($request->discount ?? 0);

            $invoice->update([
                'customer_id' => $request->customer_id,
                'due_date' => $request->due_date,
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

            $this->generatePdf($invoice->id);

            DB::commit();
            return redirect()->route('admin.quotations.index')->with('success', 'Quotation updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Quotation update failed: ' . $e->getMessage()]);
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
            return redirect()->route('admin.quotations.index')->with('success', 'Quotation deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Quotation deletion failed: ' . $e->getMessage()]);
        }
    }

    private function generateInvoiceNumber()
    {
        $lastInvoice = Invoice::orderBy('id', 'desc')->first();
        return $lastInvoice
            ? 'QUT-' . str_pad((int)substr($lastInvoice->invoice_number, 3) + 1, 6, '0', STR_PAD_LEFT)
            : 'QUT-000001';
    }

    private function generatePdf($invoiceId)
    {
        $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($invoiceId);

        $seller = new Party([
            'name' => 'Al Najm Al Saeed Co. Ltd.',
            'address' => '8611 Thabit Ibn Uddai, Ad Dhubbat, Riyadh',
            'code' => '12623',
            'vat' => '312508185500003',
            'custom_fields' => [
                'email' => 'info@alsaeedstar.com',
            ],
        ]);

        $buyer = new Buyer([
            'name' => $invoice->customer->name,
            'address' => $invoice->customer->address,
            'code' => $invoice->customer->pincode,
            'vat' => $invoice->customer->tax_number,
            'custom_fields' => [
                'email' => $invoice->customer->email,
            ],
        ]);

        $quotationItems = [];
        foreach ($invoice->items as $item) {
            $quotationItems[] = (new InvoiceItem())
                ->title($item->product->name)
                ->description($item->product->description ?? 'No Description available for ' . $item->product->name . '.')
                ->pricePerUnit($item->price)
                ->quantity($item->quantity);
        }

        try {
            $pdfInvoice = PDFInvoice::make('quotation', 'default')
                ->serialNumberFormat($invoice->invoice_number)
                ->date(Carbon::parse($invoice->issue_date))
                ->dateFormat('d/m/Y')
                ->seller($seller)
                ->buyer($buyer)
                ->addItems($quotationItems)
                ->taxRate($invoice->vat_percentage)
                ->currencySymbol('SAR')
                ->currencyCode('SAR')
                ->currencyFraction('halalas.')
                ->filename($invoice->invoice_number)
                ->logo(public_path('assets/images/brand/logo-no-background.png'))
                ->notes($invoice->notes ?? 'Thank you for your business!');

            $pdfInvoice->save('invoices');
        } catch (\Exception $e) {
            throw new \Exception('PDF generation failed: ' . $e->getMessage());
        }
    }

    public function download($id)
    {
        $invoice = Invoice::findOrFail($id);
        $pdfPath = storage_path('app/public/invoices/' . $invoice->invoice_number . '.pdf');

        if (!file_exists($pdfPath)) {
            try {
                $this->generatePdf($invoice->id);
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
                $this->generatePdf($invoice->id);
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
