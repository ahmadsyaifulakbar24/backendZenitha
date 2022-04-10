<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;
    protected $table = 'articles';
    protected $fillable = [
        'slug',
        'type',
        'title',
        'image',
        'content',
        'video_url'
    ];

    protected $appends = [
        'image_url',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($article) {
            $counter = 0;
            $slug = Str::slug($article->title);
            $original_slug = Str::slug($article->title);
            while(static::where('slug', $slug)->count() > 0) {
                $counter++;
                $slug = "{$original_slug}-{$counter}";
            }
            $article->slug = $slug;
        });
    }

    public function getImageUrlAttribute()
    {
        return !empty($this->attributes['image']) ? url('') . Storage::url($this->attributes['image']) : null;
    }

    public function getCreatedAtAttribute($date) {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($date) {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }
}
