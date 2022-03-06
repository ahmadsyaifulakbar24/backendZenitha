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
        'number_resi',
        'marketplace_resi',

        'shipping_cost',
        'shipping_discount',
        'product_discount',
        'product_price',
        'total_price',
        'unique_code',
        
        'address',
        'expedition',
        'expired_time',
        'type',
        'payment_method',
        'status',

    ];
}
