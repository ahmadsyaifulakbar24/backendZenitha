<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingSetting extends Model
{
    use HasFactory;

    protected $table = 'shipping_settings';
    protected $fillable = [
        'minimum_price',
        'max_shipping_discount',
        'start_date',
        'end_date'
    ];

    public $timestamps = false;
}
