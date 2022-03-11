<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';
    protected $fillable = [
        'user_id',
        'product_slug',
        'quantity',
    ];

    public function product_combination()
    {
        return $this->belongsTo(ProductCombination::class, 'product_slug', 'product_slug');
    }
    
}
