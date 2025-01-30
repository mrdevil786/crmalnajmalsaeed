<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Invoice as PDFInvoice;

class PDFHelper
{
    public static function generateInvoicePdf($invoiceId, $qrCodeData = null)
    {
        // app()->setLocale('ar');

        $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($invoiceId);

        $seller = new Party([
            'name' => 'Al Najm Al Saeed Co. Ltd.',
            'address' => 'Prince Abdulaziz bin Musaed bin Julawi, Riyadh',
            'code' => '12628',
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
                ->notes($invoice->notes ?? 'Thank you for your business!');

            if ($invoice->discount > 0) {
                $pdfInvoice->totalDiscount($invoice->discount);
            }

            $customData = ['iban' => 'SA1105000068205836972000'];
            if ($qrCodeData) {
                $customData['qrCodeData'] = $qrCodeData;
            }
            $pdfInvoice->setCustomData($customData);

            $pdfInvoice->save('invoices');
        } catch (\Exception $e) {
            throw new \Exception('PDF generation failed: ' . $e->getMessage());
        }
    }
}
