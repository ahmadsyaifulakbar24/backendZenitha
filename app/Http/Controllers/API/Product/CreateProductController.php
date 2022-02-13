<?php

namespace App\Http\Controllers\API\Product;

use App\Helpers\FileHelpers;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductDetailResource;
use App\Models\Product;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CreateProductController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            //info product
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

            // product image
            'product_image' => ['required', 'array'],
            'product_image.*.product_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
            'product_image.*.order' => ['required', 'integer'],
        ]);

        $input = $request->all();
        $input['rate'] = 0;
        $input['user_id'] = $request->user()->id;

        $result =  DB::transaction(function () use ($request, $input){  
            $product = Product::create($input);

            // insert image product
            foreach($request->product_image as $product_image) {
                $path = FileHelpers::upload_file('product', $product_image['product_image']);
                $product_images[] = [
                    'product_image' => $path,
                    'order' => $product_image['order']
                ];
            }
            $product->product_image()->createMany($product_images);

            if($request->variant) {
                // insert product variant
                foreach($request->variant as $variant) {
                    $product_variant_option = $product->product_variant_option()->create([ 'variant_name' => $variant['variant_name'] ]);
                    foreach ($variant['variant_option'] as $variant_option) {
                        $variant_options[] = [
                            'variant_option_name' => $variant_option
                        ];
                    }
                    $product_variant_option->product_variant_option_value()->createMany($variant_options);
                }
    
                // product_combination
                $total_stock = 0;
                foreach($request->combination as $combination) {
                    $unique_string =  Str::lower($this->sort_character(Str::replace('-', '', $combination['combination_string'])));
                    $image_path = FileHelpers::upload_file('product', $combination['image'], 'false');
                    $total_stock = 0 + $combination['stock'];
                    $product_combinations[] = [
                        'combination_string' => $combination['combination_string'],
                        'sku' => $combination['sku'],
                        'price' => $combination['price'],
                        'unique_string' => $unique_string,
                        'stock' => $combination['stock'],
                        'image' => $image_path,
                        'status' => $combination['status'],
                    ];
                }
                $product->product_combination()->createMany($product_combinations);
                $product->update([ 'total_stock' => $total_stock ]);
            }
            return $product;
        });
        
        return ResponseFormatter::success(new ProductDetailResource($result), 'success get product detail data');
    }

    public function sort_character($string)
    {
        $stringParts = str_split($string);
        sort($stringParts);
        return implode($stringParts);
    }
}
