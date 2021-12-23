<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCombination extends Model
{
    use HasFactory;

    protected $table = 'product_combinations';
    protected $fillable = [
        'product_id',
        'combination_string',
        'sku',
        'price',
        'unique_string',
        'stock',
        'image',
        'status'
    ];
}
