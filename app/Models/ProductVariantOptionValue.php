<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantOptionValue extends Model
{
    use HasFactory;

    protected $table = 'product_variant_option_values';
    protected $fillable = [
        'product_variant_id',
        'variant_option_name'
    ];
}
