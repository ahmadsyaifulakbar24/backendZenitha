<?php

namespace App\Http\Controllers\API\Product;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductVariantOptionResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductVariantOptionController extends Controller
{
    public function get_product_variant_option(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 
            Rule::exists('pruducts', 'id')->where(function($query) {
                return $query->whereNull('deleted_at');
            })
        ]
        ]);
        
        $product = Product::find($request->product_id);
        return ResponseFormatter::success(ProductVariantOptionResource::collection($product->product_variant_option), 'success get product variant options');
    }
}
