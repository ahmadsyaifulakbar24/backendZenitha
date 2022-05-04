<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $fillable = [
        'unique_code',
        'total',
        'expired_time',
        'paid_off_time',
        'order_payment',
        'status',
    ];
    public $timestamps = false;

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
