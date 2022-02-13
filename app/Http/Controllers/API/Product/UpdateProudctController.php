<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateProudctController extends Controller
{
    public function update(Request $request, Product $product)
    {
        $request->validate([
            // info product
            'sku' => [
                Rule::requiredIf(empty($request->variant)), 
                'unique:products,sku'
            ],
            'product_name' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'sub_category_id' => ['required', 'exists:sub_categories,id'],
            'price' => ['required', 'integer'],
            'minimum_order' => ['required', 'min:1'],
            'preorder' => ['required', 'boolean'],
            'duration_unit' => [
                Rule::requiredIf($request->preorder == 1),
                'in:day,week'
            ],
            'duration' => [
                Rule::requiredIf($request->preorder == 1),
                'integer'
            ],
            'description' => ['nullable', 'string'],
            'video_url' => ['nullable', 'url'],
            'total_stock' => [
                Rule::requiredIf(!empty($request->variant)),
                'integer'
            ],
            'product_weight' => ['required', 'integer'],
            'weight_unit' => ['required', 'in:gram,kg'],
            'size_guide' => ['nullable', 'string'],
            'status' => ['required', 'in:active,not_active'],

            // product_variant
            'variant' => ['nullable', 'array'],
            'variant.*.variant_name' => ['required_with:variant', 'exists:variants,variant_name'],
            'variant.*.variant_option' => ['required_with:variant.*.variant_name', 'array', 'exists:variant_options,variant_option_name'],

            // product combination
            'combination' => ['required_with:variant', 'array'],
            'combination.*.combination_string' => ['required_with:combination'],
            'combination.*.sku' => [ 'required_with:combination', 'unique:product_combinations,sku'],
            'combination.*.price' => [ 'required_with:combination', 'integer' ],
            'combination.*.stock' => [ 'required_with:combination', 'integer' ],
            'combination.*.image' => [ 'required_with:combination', 'image', 'mimes:jpeg,png,jpg,gif,svg' ],
            'combination.*.status' => [ 'required_with:combination', 'in:active,not_active' ],
        ]);
    }
}
