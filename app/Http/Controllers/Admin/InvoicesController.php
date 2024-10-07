<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\InvoiceHelper;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Helpers\QRCodeHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Invoice as PDFInvoice;

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
            'vat_percentage' => 'required|numeric',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $nextInvoiceNumber = InvoiceHelper::generateInvoiceNumber();

            $subtotal = array_reduce($request->items, function ($carry, $item) {
                return $carry + ($item['quantity'] * $item['price']);
            }, 0);

            $vat_amount = ($subtotal * $request->vat_percentage) / 100;
            $total = $subtotal + $vat_amount - ($request->discount ?? 0);

            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'invoice_number' => $nextInvoiceNumber,
                'type' => 'invoice',
                'issue_date' => now(),
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
                $this->generatePdf($invoice->id, $qrCodeData);
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

    private function generatePdf($invoiceId, $qrCodeData)
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

        $invoiceItems = [];
        foreach ($invoice->items as $item) {
            $invoiceItems[] = (new InvoiceItem())
                ->title($item->product->name)
                ->description($item->product->description ?? 'No Description available for ' . $item->product->name . '.')
                ->pricePerUnit($item->price)
                ->quantity($item->quantity)
                ->discountByPercent($item->discount ?? 0);
        }

        try {
            $pdfInvoice = PDFInvoice::make($invoice->type, 'default')
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
                ->filename($invoice->invoice_number)
                ->logo(public_path('assets/images/brand/logo-no-background.png'))
                ->notes($invoice->notes ?? 'Thank you for your business!')
                ->setCustomData(['qrCodeData' => $qrCodeData]);

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
                $qrCodeData = QRCodeHelper::generateQRCodeDataUri($invoice);
                $this->generatePdf($invoice->id, $qrCodeData);
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
                $this->generatePdf($invoice->id, $qrCodeData);
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
