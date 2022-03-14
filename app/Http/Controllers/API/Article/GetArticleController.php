<?php

namespace App\Http\Controllers\API\Article;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Article\ArticleDetailResource;
use App\Http\Resources\Article\ArticleResource;
use App\Models\Article;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;

class GetArticleController extends Controller
{
    public function get(Request $request)
    {
        $request->validate([
            'type' => ['nullable', 'in:video,article'],
            'limit' => ['nullable', 'integer']
        ]);
        $limit = $request->input('limit', 10);

        $article = Article::query();
        if($request->type) {
            $article->where('type', $request->type);
        }
        $result = $article->orderBy('created_at', 'asc')->paginate($limit);

        return ResponseFormatter::success(ArticleResource::collection($result)->response()->getData(true), 'success get article data');
    }

    public function show(Article $article)
    {
        return ResponseFormatter::success(new ArticleDetailResource($article), 'success get article data');
    }
}
