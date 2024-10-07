<?php

namespace App\Helpers;

use App\Models\Invoice;

class InvoiceHelper
{
    public static function generateInvoiceNumber()
    {
        $lastInvoice = Invoice::orderBy('id', 'desc')->first();
        return $lastInvoice
            ? 'INV-' . str_pad((int)substr($lastInvoice->invoice_number, 4) + 1, 6, '0', STR_PAD_LEFT)
            : 'INV-000001';
    }

    public static function generateQuotationNumber()
    {
        $lastInvoice = Invoice::orderBy('id', 'desc')->first();
        return $lastInvoice
            ? 'QUT-' . str_pad((int)substr($lastInvoice->invoice_number, 4) + 1, 6, '0', STR_PAD_LEFT)
            : 'QUT-000001';
    }
}
