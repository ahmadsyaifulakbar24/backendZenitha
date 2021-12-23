<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $fillable = [
        'user_id',
        'invoice_number',
        'shipping_cost',
        'number_resi',
        'expedition',
        'marketplace_resi',
        'address',
        'shipping_discount',
        'product_discount',
        'total_price',
        'status',
        'payment_url'
    ];
}
