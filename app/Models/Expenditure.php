<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenditure extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'date',
        'category',
        'payment_method',
        'user_id',
        'invoice_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
