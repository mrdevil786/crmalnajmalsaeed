<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'unit'];

    // A product can be included in many items
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
