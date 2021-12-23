<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $table = 'sub_categories';
    protected $fillable = [
        'category_id',
        'sub_category_slug',
        'sub_category_name',
    ];

    public $timestamps = false;

    public function product ()
    {
        return $this->hasMany(Product::class, 'sub_category_id');
    }
}
