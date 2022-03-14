<?php

namespace App\Http\Controllers\API\Article;

use App\Helpers\FileHelpers;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Article\ArticleDetailResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CreateArticleController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'type' => ['required', 'in:video,article'],
            'title' => ['required', 'string'],
            'image' => [
                Rule::requiredIf($request->type == 'article'),
                'image',
                'mimes:jpg,png,jpeg,gif,svg',
            ],
            'content' => [
                Rule::requiredIf($request->type == 'article'),
                'string'
            ],
            'video_url' => [
                Rule::requiredIf($request->type == 'video'),
                'url'
            ],
        ]);

        $input = $request->except([
            'image',
            'content',
            'video_url'
        ]);
        if($request->type == 'article')
        {
            $input['content'] = $request->content;
            if($request->image) {
                $input['image'] = FileHelpers::upload_file('article', $request->image);
            }
        } else {
            $input['video_url'] = $request->video_url;
        }

        $article = Article::create($input);
        return ResponseFormatter::success(new ArticleDetailResource($article));
    }
}
