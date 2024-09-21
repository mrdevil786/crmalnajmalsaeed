<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'price',
        'total'
    ];

    // An item belongs to an invoice
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // An item belongs to a product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
