<?php

namespace App\Http\Controllers\API\MasterData;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\MasterData\CategoryResource;
use App\Http\Resources\MasterData\SubCategoryResource;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request, [
            'category_id' => ['required', 'exists:categories,id'],
            'sub_category_name' => ['required', 'unique:sub_categories,sub_category_name'],
        ]);

        $category = Category::find($request->category_id);
        $sub_category = $category->sub_category()->create([
            'sub_category_name' => $request->sub_category_name,
            'sub_category_slug' => Str::slug($request->sub_category_name)
        ]);

        return ResponseFormatter::success(
            new SubCategoryResource($sub_category),
            $this->message('create')
        );
    }

    public function fetch(Request $request)
    {
        $this->validate($request, [
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $sub_category = SubCategory::where('category_id', $request->category_id)->get();
        return ResponseFormatter::success(
            SubCategoryResource::collection($sub_category),
            $this->message('get')
        );
    }

    public function show (SubCategory $sub_category)
    {
        return ResponseFormatter::success(
            new CategoryResource($sub_category),
            $this->message('get')
        );
    }

    public function update(Request $request, SubCategory $sub_category)
    {
        $request->validate([
            'sub_category_name' => ['required', 'unique:sub_categories,sub_category_name,'.$sub_category->id],
        ]);

        $sub_category->update([
            'sub_category_name' => $request->sub_category_name,
            'sub_category_slug' => Str::slug($request->sub_category_name)
        ]);

        return ResponseFormatter::success(
            new SubCategoryResource($sub_category),
            $this->message('update')
        );
    }

    public function delete(SubCategory $sub_category)
    {
        if($sub_category->product()->count() > 0) {
            return ResponseFormatter::errorValidation([
                'sub_category' => "can't delete this sub category because it's already used in the product"
            ], 'delete sub category data failed');
        }

        $result = $sub_category->delete();
        return ResponseFormatter::success(
            $result,
            $this->message('delete')
        );
    }

    public function message($type) 
    {
        return 'success '.$type.' sub category data';
    }
}
