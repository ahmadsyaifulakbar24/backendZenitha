<?php

namespace App\Http\Controllers\API\Article;

use App\Helpers\FileHelpers;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Article\ArticleDetailResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UpdateArticleController extends Controller
{
    public function __invoke(Request $request, Article $article)
    {
        $request->validate([
            'type' => ['required', 'in:video,article'],
            'title' => ['required', 'string'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,png,jpeg,gif,svg',
            ],
            'content' => [ 'required', 'string' ],
            'video_url' => [
                Rule::requiredIf($request->type == 'video'),
                'url'
            ],
        ]);

        $input = $request->except([
            'image',
            'video_url'
        ]);

        if($request->type == 'article')
        {
            $input['video_url'] = null;
            if($request->image) {
                $input['image'] = FileHelpers::upload_file('article', $request->image);
                Storage::disk('public')->delete($article->image);
            }
        } else {
            $input['video_url'] = $request->video_url;
            $input['image'] = null;
            !empty($article->image) ? Storage::disk('public')->delete($article->image) : null;
        }

        $article->update($input);
        return ResponseFormatter::success(new ArticleDetailResource($article));
    }
}
