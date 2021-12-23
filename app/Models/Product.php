<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';
    protected $fillable = [
        'user_id',
        'product_slug',
        'sku',
        'product_name',
        'category_id',
        'sub_category_id',
        'price',
        'minimum_order',
        'preorder',
        'duration_unit',
        'duration',
        'description',
        'video_url',
        'total_stock',
        'product_weight',
        'weight_unit',
        'rate',
        'size_guide',
        'status'
    ];
}
