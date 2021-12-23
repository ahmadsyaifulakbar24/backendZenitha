<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantOption extends Model
{
    use HasFactory;
    
    protected $table = 'product_variant_options';
    protected $fillable = [
        'product_id',
        'variant_name'
    ];
}
