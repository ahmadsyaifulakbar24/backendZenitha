<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;

    protected $table = 'variants';
    protected $fillable = [
        'variant_name',
        'image'
    ];

    public $timestamps = false;

    public function variant_option() {
        return $this->hasMany(VariantOption::class, 'variant_id');
    }
}
