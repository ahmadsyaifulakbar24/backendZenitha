<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    public $timestamps = false;

    protected $appends = [
        'logo_url'
    ];

    public function getLogoUrlAttribute()
    {
        return url('') . Storage::url($this->attributes['logo']);
    }
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
