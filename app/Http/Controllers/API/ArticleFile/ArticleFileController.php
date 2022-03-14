<?php

namespace App\Http\Controllers\API\ArticleFile;

use App\Helpers\FileHelpers;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleFile\ArticleFileResource;
use App\Models\ArticleFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleFileController extends Controller
{
    public function get(Request $request)
    {
        $request->validate([
            'limit' => ['nullable', 'integer']
        ]);
        $limit = $request->input('limit', 10);
        $article_file = ArticleFile::orderBy('created_at', 'desc')->paginate($limit);
        return ResponseFormatter::success(ArticleFileResource::collection($article_file)->response()->getData(true), 'success get article file data');
    }

    public function show(ArticleFile $article_file)
    {
        return ResponseFormatter::success(new ArticleFileResource($article_file), 'success get article file data');
    }

    public function create(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file']
        ]);

        if($request->file) {
            $input['file'] = FileHelpers::upload_file('article_file', $request->file);
        }

        $article_file = ArticleFile::create($input);
        return ResponseFormatter::success(new ArticleFileResource($article_file), 'success create article file data');
    }

    public function delete(ArticleFile $article_file)
    {
        Storage::disk('public')->delete($article_file->file);
        $article_file->delete();

        return ResponseFormatter::success(null, 'success delete article file data');
    }
}
