<?php

namespace App\Models;

use Carbon\Carbon;
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
        'total_price',
        'unique_code',
        
        'address',
        'expedition',
        'expired_time',
        'type',
        'payment_method',
        'status',

    ];

    public function getCreatedAtAttribute($date) {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($date) {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }
    
    public function getExpiredTimeAttribute($date) {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }

    public function transaction_product()
    {
        return $this->hasMany(TransactionProduct::class, 'transaction_id');
    }
}
