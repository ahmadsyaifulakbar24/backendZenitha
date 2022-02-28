<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OtherSetting extends Model
{
    use HasFactory;

    protected $table = 'other_settings';
    protected $fillable = [
        'category',
        'content',
        'order',
        'type',
    ];

    public $timestamps = false;

    protected $appends = [
        'banner_url'
    ];

    public function getBannerUrlAttribute()
    {
        return url('') . Storage::url($this->attributes['content']);
    }
}
