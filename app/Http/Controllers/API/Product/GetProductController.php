<?php

namespace App\Http\Controllers\API\product;

use App\Helpers\ResponseFormatter;
use App\Helpers\StrHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductCombinationDetailResource;
use App\Http\Resources\Product\ProductCombinationResource;
use App\Http\Resources\Product\ProductDetailResource;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Models\ProductCombination;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GetProductController extends Controller
{
    public function fetch(Request $request)
    {
        $request->validate([
            'category_id' => [ 'nullable', 'exists:categories,id' ],
            'sub_category_id' => [ 'nullable', 'exists:sub_categories,id'],
            'min_price' => [ 'nullable', 'integer'],
            'max_price' => [ 'nullable', 'integer'],
            'search' => ['nullable', 'string'],
            'limit' => ['nullable', 'integer'],
            'status' => ['nullable', 'in:active,not_active'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);
        $limit = $request->input('limit', 10);

        $product = Product::query();

        if($request->status) {
            $product->where('status', $request->status);
        }

        if($request->category_id) {
            $product->where('category_id', $request->category_id);
        }

        if($request->sub_category_id) 
        {
            $product->where('sub_category_id', $request->sub_category_id);
        }

        if($request->min_price) {
            $product->where('price', '>=', $request->min_price);
        }

        if($request->max_price)
        {
            $product->where('price', '<=', $request->max_price);
        }

        if($request->search) {
            $product->where('product_name', 'like', '%'.$request->search.'%');
        }

        $result = $product->orderBy('created_at', 'desc')->paginate($limit);
        return ResponseFormatter::success(ProductResource::collection($result)->response()->getData(true), 'success get product data');
    }

    public function show(Product $product)
    {
        return ResponseFormatter::success(new ProductDetailResource($product), 'success get product data');
    }

    public function product_combination(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'combination_string' => ['required', 'string']
        ]);

        $unique_string =  Str::lower(StrHelper::sort_character(Str::replace('-', '', $request->combination_string)));
        $product_combination = ProductCombination::where([['product_id', $request->product_id], ['unique_string', $unique_string], ['status', 'active']])->first();
        return ResponseFormatter::success(new ProductCombinationResource($product_combination), 'success get product combination data');
    }
    
    public function product_combination_slug(ProductCombination $product_combination) 
    {
        return ResponseFormatter::success(new ProductCombinationDetailResource($product_combination), 'success get product combination data');
    }
}
