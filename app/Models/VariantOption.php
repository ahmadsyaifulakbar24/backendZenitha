<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantOption extends Model
{
    use HasFactory;

    protected $table = 'variant_options';
    protected $fillable = [
        'variant_id',
        'variant_option_name',
        'default',
    ];

    public $timestamps = false;
}
