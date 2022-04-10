<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantOptionValue extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'product_variant_option_values';
    protected $fillable = [
        'product_variant_id',
        'variant_option_name'
    ];

    public $timestamps = false;
}
