<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model
{
    use HasFactory;

    protected $table = 'web_settings';
    protected $fillable = [
        'logo',
        'name',
        'description',
        'email',
        'phone',
        'province_id',
        'city_id',
        'district_id',
        'postal_code',
        'address',

        'fb_status',
        'fb',
        'tw_status',
        'tw',
        'yt_status',
        'yt',
        'ig_status',
        'ig'
    ];
}
