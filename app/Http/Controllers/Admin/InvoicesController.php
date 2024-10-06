<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\QRCodeHelper;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Customer;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice as PDFInvoice;

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

            $this->generatePdf($invoice->id);
            return redirect()->route('admin.invoices.index')->with('success', 'Invoice created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Invoice creation failed: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);

        DB::beginTransaction();

        try {
            $invoice->items()->delete();
            $invoice->delete();

            DB::commit();

            return redirect()->route('admin.invoices.index')->with('success', 'Invoice deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Invoice deletion failed: ' . $e->getMessage()]);
        }
    }

    private function generatePdf($invoiceId)
    {
        $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($invoiceId);

        $seller = new Party([
            'name' => 'Al Najm Al Saeed Co. Ltd.',
            'address' => '456 Corporate Blvd, Business City',
            'code' => '54321',
            'custom_fields' => [
                'email' => 'info@alsaeedstar.com',
            ],
        ]);

        $buyer = new Buyer([
            'name' => $invoice->customer->name,
            'address' => $invoice->customer->address,
            'code' => $invoice->customer->tax_number,
            'custom_fields' => [
                'email' => $invoice->customer->email,
            ],
        ]);

        $qrCodeData = QRCodeHelper::generateQRCodeDataUri($invoice);

        $invoiceItems = [];
        foreach ($invoice->items as $item) {
            $invoiceItems[] = (new InvoiceItem())
                ->title($item->product->name)
                ->description('Description of ' . $item->product->name)
                ->pricePerUnit($item->price)
                ->quantity($item->quantity)
                ->discountByPercent($item->discount ?? 0);
        }

        $pdfInvoice = PDFInvoice::make('invoice', 'default')
            ->serialNumberFormat($invoice->invoice_number)
            ->date(Carbon::parse($invoice->issue_date))
            ->dateFormat('d/m/Y')
            ->seller($seller)
            ->buyer($buyer)
            ->addItems($invoiceItems)
            ->taxRate($invoice->vat_percentage)
            ->currencySymbol('SAR')
            ->currencyCode('SAR')
            ->currencyFraction('halalas.')
            ->filename('INVOICE-' . $invoice->invoice_number)
            ->logo(public_path('assets/images/brand/logo-no-background.png'))
            ->notes($invoice->notes ?? 'Thank you for your business!')
            ->setCustomData(['qrCodeData' => $qrCodeData]);

        $pdfInvoice->save('public');
    }
}
