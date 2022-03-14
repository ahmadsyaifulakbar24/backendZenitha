<?php

namespace App\Http\Controllers\API\Article;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeleteArticleController extends Controller
{
    public function __invoke(Article $article)
    {
        !empty($article->image) ? Storage::disk('public')->delete($article->image) : null;
        $article->delete();
        
        return ResponseFormatter::success(null, 'success delete article data');
    }
}
