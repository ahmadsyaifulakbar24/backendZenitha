<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    protected $table = 'product_images';
    protected $fillable = [
        'product_id',
        'product_image',
        'order',
    ];

    public $timestamps = false;

    protected $appends = [
        'product_image_url'
    ];

    public function getProductImageUrlAttribute()
    {
        return !empty($this->attributes['product_image']) ? url('') . Storage::url($this->attributes['product_image']) : null;
    }    
}
