<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $fillable = [
        'user_id',
        'invoice_number',
        'number_resi',
        'marketplace_resi',
        'type',

        'bank_name',
        'no_rek',

        'shipping_cost',
        'shipping_discount',
        
        'address',
        'expedition',
        'expedition_service',
        'paid_off_time',
        'payment_method',
        'total_payment',
        'status', // ['pending', 'paid_off', 'expired', 'sent', 'canceled', 'finish']
    ];

    public function  scopeActivityTransaction($query)
    {
        $query->select(
            DB::raw("COUNT(if(status = 'pending', status, null)) as pending"),
            DB::raw("COUNT(if(status = 'paid_off', status, null)) as paid_off"),
            DB::raw("COUNT(if(status = 'sent', status, null)) as sent"),
            DB::raw("COUNT(if(status = 'expired', status, null)) as expired"),
            DB::raw("COUNT(if(status = 'canceled', status, null)) as canceled"),
            DB::raw("COUNT(if(status = 'finish', status, null)) as finish"),
            DB::raw("COUNT(*) as total"),
        );
    }

    public function scopeJoinProduct($query)
    {
        $query->join('transaction_products as a', 'a.transaction_id', '=', 'transactions.id')
            ->select('transactions.id', 'transactions.user_id', 'a.product_name');
    }

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

    public function payments()
    {
        return $this->hasMany(Payment::class, 'transaction_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
