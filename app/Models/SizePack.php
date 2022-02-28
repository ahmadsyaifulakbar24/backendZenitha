<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SizePack extends Model
{
    use HasFactory;

    protected $table = 'size_packs';
    protected $fillable = [
        'name',
        'file'
    ];

    public $timestamps = false;

    protected $appends = [
        'file_url'
    ];

    public function getFileUrlAttribute()
    {
        return url('') . Storage::url($this->attributes['file']);
    }
}
