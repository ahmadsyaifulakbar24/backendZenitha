<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductCombination extends Model
{
    use HasFactory;

    protected $table = 'product_combinations';
    protected $fillable = [
        'product_id',
        'product_slug',
        'combination_string',
        'sku',
        'price',
        'unique_string',
        'stock',
        'image',
        'status',
        'main'
    ];

    protected $appends = [
        'image_url'
    ];

    public function getCreatedAtAttribute($date) {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($date) {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }
    
    public function getImageUrlAttribute()
    {
        return !empty($this->attributes['image']) ? url('') . Storage::url($this->attributes['image']) : null;
    }    

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
