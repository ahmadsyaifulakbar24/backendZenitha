<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Evidence extends Model
{
    use HasFactory;

    protected $table = 'evidence';
    protected $fillable = [
        'evidence'
    ];

    protected $appends = [
        'evidence_url'
    ];

    public function getEvidenceUrlAttribute()
    {
        return !empty($this->attributes['evidence']) ? url('') . Storage::url($this->attributes['evidence']) : null;
    }
}
