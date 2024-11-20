<?php

namespace App\Helpers;

use App\Models\Invoice;

class InvoiceHelper
{
    public static function generateNumber($prefix)
    {
        $lastInvoice = Invoice::orderBy('created_at', 'desc')->first();
        return $lastInvoice
            ? $prefix . '-' . str_pad((int)substr($lastInvoice->invoice_number, 4) + 1, 6, '0', STR_PAD_LEFT)
            : $prefix . '-000001';
    }
}
