<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $table = 'user_addresses';
    protected $fillable = [
        'user_id',
        'label',
        'recipients_name',
        'province_id',
        'city_id',
        'house_number',
        'phone_number',
        'address_description',
    ];
}
