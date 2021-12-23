<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model
{
    use HasFactory;

    protected $table = 'web_settings';
    protected $fillable = [
        'site_logo',
        'site_name',
        'site_description',
        'site_email',
        'province_id',
        'city_id'
    ];
}
