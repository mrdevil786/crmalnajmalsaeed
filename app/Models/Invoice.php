<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'invoice_number',
        'type',
        'issue_date',
        'due_date',
        'vat_percentage',
        'subtotal',
        'discount',
        'vat_amount',
        'total',
        'notes',
        'pdf_path'
    ];

    // An invoice belongs to a customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // An invoice can have many items
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
