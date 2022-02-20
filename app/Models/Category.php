<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $fillable = [
        'category_name',
        'category_slug',
        'image',
    ];

    public $timestamps = false;

    protected $appends = [
        'image_url'
    ];

    public function getImageUrlAttribute()
    {
        return !empty($this->attributes['image']) ? url('') . Storage::url($this->attributes['image']) : null;
    }

    public function sub_category()
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }

    public function product ()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
