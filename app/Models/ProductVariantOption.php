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

    public $timestamps = false;

    public function product_variant_option_value()
    {
        return $this->hasMany(ProductVariantOptionValue::class, 'product_variant_option_id');
    }
}
