<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';
    protected $fillable = [
        'banner',
        'url',
        'order'
    ];

    public $timestamps = false;

    protected $appends = [
        'banner_url'
    ];

    public function getBannerUrlAttribute()
    {
        return url('') . Storage::url($this->attributes['banner']);
    }
}
