<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebConfig extends Model
{
    use HasFactory;
    protected $table = 'web_configs';
    protected $fillable = [
        'value'
    ];
    public $timestamps = false;
}
