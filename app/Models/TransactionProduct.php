<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionProduct extends Model
{
    use HasFactory;

    protected $table = 'transaction_products';
    protected $fillable = [
        'transaction_id',
        'product_slug',
        'image',
        'product_name',
        'discount_product',
        'discount_group',
        'discount_customer',
        'price',
        'description',
        'quantity',
        'notes'
    ];

    public $timestamps = false;

}
