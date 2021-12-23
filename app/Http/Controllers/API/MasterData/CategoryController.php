<?php

namespace App\Http\Controllers\API\MasterData;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\MasterData\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request, [
            'category_name' => ['required', 'string', 'unique:categories,category_name']
        ]);

        $category = Category::create([
            'category_name' => $request->category_name,
            'category_slug' => Str::slug($request->category_name, '-'),
        ]);

        return ResponseFormatter::success(
            new CategoryResource($category),
            $this->message('create')
        );
    }

    public function fetch ()
    {
        $category = Category::all();
        return ResponseFormatter::success(
            CategoryResource::collection($category),
            $this->message('get')
        );
    }

    public function show(Category $category) 
    {
        return ResponseFormatter::success(
            new CategoryResource($category),
            $this->message('get')
        );
    }

    public function update (Request $request, Category $category)
    {
        $this->validate($request, [
            'category_name' => ['required', 'string', 'unique:categories,category_name,'.$category->id]
        ]);

        $category->update([
            'category_name' => $request->category_name,
            'category_slug' => Str::slug($request->category_name, '-'),
        ]);

        return ResponseFormatter::success(
            new CategoryResource($category),
            $this->message('update')    
        );
    }

    public function delete (Category $category) 
    {
        if($category->product()->count() > 0) {
            return ResponseFormatter::errorValidation([
                'category' => "can't delete this category because it's already used in the product",
            ], 'failed delete category data');
        }
        $result = $category->delete();
        return ResponseFormatter::success([
            $result,
            $this->message('delete')
        ]);
    }

    public function message ($type) 
    {
        return 'success '.$type.' category data';
    }
}
