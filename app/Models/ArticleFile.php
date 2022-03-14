<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleFile extends Model
{
    use HasFactory;

    protected $table = 'article_files';
    protected $fillable = [
        'file'
    ];

    public $timestamps = false;
}
