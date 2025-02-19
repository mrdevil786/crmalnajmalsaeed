<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VatReturn extends Model
{
    protected $fillable = [
        'period_from',
        'period_to',
        'total_sales',
        'total_purchases',
        'output_vat',
        'input_vat',
        'net_vat_payable',
        'status',
        'notes',
        'submitted_at'
    ];

    protected $casts = [
        'period_from' => 'date',
        'period_to' => 'date',
        'total_sales' => 'decimal:2',
        'total_purchases' => 'decimal:2',
        'output_vat' => 'decimal:2',
        'input_vat' => 'decimal:2',
        'net_vat_payable' => 'decimal:2',
    ];

    protected $dates = [
        'period_from',
        'period_to',
        'submitted_at'
    ];
}
