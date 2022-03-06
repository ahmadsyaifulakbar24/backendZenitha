<?php

namespace App\Http\Controllers\API\MasterData;

use App\Helpers\FileHelpers;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\MasterData\CategoryResource;
use App\Http\Resources\MasterData\CategoryWithSubResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'category_name' => ['required', 'string', 'unique:categories,category_name'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
        ]);
        
        $path = FileHelpers::upload_file('category', $request->image, true);
        $category = Category::create([
            'category_name' => $request->category_name,
            'category_slug' => Str::slug($request->category_name, '-'),
            'image' => $path,
        ]);

        return ResponseFormatter::success(
            new CategoryResource($category),
            $this->message('create')
        );
    }

    public function fetch (Request $request)
    {
        $request->validate([
            'with_sub' => ['nullable', 'boolean']
        ]);

        $category = Category::orderBy('id', 'desc')->get();
        if($request->with_sub == 1) {
            $result = CategoryWithSubResource::collection($category);
        } else {
            $result = CategoryResource::collection($category);
        }
        return ResponseFormatter::success(
            $result,
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
            'category_name' => ['required', 'string', 'unique:categories,category_name,'.$category->id],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
        ]);

        $input = $request->all();
        $input['category_slug'] = Str::slug($request->category_name, '-');
        if($request->image) {
            $path = FileHelpers::upload_file('category', $request->image, false);
            $input['image'] = $path;
        }
        $category->update($input);

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
