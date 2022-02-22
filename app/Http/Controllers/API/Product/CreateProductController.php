<?php

namespace App\Http\Controllers\API\Product;

use App\Helpers\FileHelpers;
use App\Helpers\ResponseFormatter;
use App\Helpers\StrHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductDetailResource;
use App\Models\Product;
use App\Models\ProductCombination;
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
            'sku' => [ 'nullable', 'string' ],
            'product_name' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'sub_category_id' => ['required', 'exists:sub_categories,id'],
            'price' => [
                Rule::requiredIf(empty($request->variant)),
                'integer'
            ],
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
            'stock' => [
                Rule::requiredIf(empty($request->variant)),
                'integer'
            ],
            'product_weight' => ['required', 'integer'],
            'weight_unit' => ['required', 'in:gram,kg'],
            'size_guide' => ['nullable', 'string'],
            'status' => [
                Rule::requiredIf(empty($request->variant)),
                'in:active,not_active'
            ],

            // product_variant
            'variant' => ['nullable', 'array'],
            'variant.*.variant_name' => ['required_with:variant', 'exists:variants,variant_name'],
            'variant.*.variant_option' => ['required_with:variant.*.variant_name', 'array', 'exists:variant_options,variant_option_name'],

            // product combination
            'combination' => ['required_with:variant', 'array'],
            'combination.*.combination_string' => ['required_with:combination'],
            'combination.*.sku' => [ 'nullable', 'string'],
            'combination.*.price' => [ 'required_with:combination', 'integer' ],
            'combination.*.stock' => [ 'required_with:combination', 'integer' ],
            'combination.*.image' => [ 'nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg' ],
            'combination.*.status' => [ 'required_with:combination', 'in:active,not_active' ],
            'combination.*.main' => [ 'required_with:combination', 'boolean'],

            // product image
            'product_image' => ['required', 'array'],
            'product_image.*.product_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
            'product_image.*.order' => ['required', 'integer'],
        ]);

        // insert product
        $input = $request->all();
        $input['rate'] = 0;
        $input['user_id'] = $request->user()->id;
        $input['status'] = empty($request->variant) ? $request->status : 'not_active';
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

        // insert product variant
        if($request->variant) {
            foreach($request->variant as $variant) {
                $product_variant_option = $product->product_variant_option()->create([ 'variant_name' => $variant['variant_name'] ]);
                $product_variant_option->product_variant_option_value()->sync($variant['variant_option']);
            }

            // product_combination
            foreach($request->combination as $combination) {
                $string = $request->product_name.' '.$combination['combination_string'];
                $product_slug = $this->slug_cek($string);
                $unique_string =  Str::lower(StrHelper::sort_character(Str::replace('-', '', $combination['combination_string'])));
                if(!empty($combination['image'])) {
                    $image_path = FileHelpers::upload_file('product', $combination['image']);
                } else {
                    $image_path = null;
                }
                $statuses[] = $combination['status']; 
                $product_combinations[] = [
                    'product_slug' => $product_slug,
                    'combination_string' => $combination['combination_string'],
                    'sku' => $combination['sku'],
                    'price' => $combination['price'],
                    'unique_string' => $unique_string,
                    'stock' => $combination['stock'],
                    'image' => $image_path,
                    'status' => $combination['status'],
                    'main' => $combination['main'],
                ];
            }
            $product->product_combination()->createMany($product_combinations);
            $product->update([ 
                'status' => in_array('active', $statuses) ? 'active' : 'not_active'
            ]);
        } else {
            $product_slug = $this->slug_cek($request->product_name);
            $product->product_combination()->create([
                'product_slug' => $product_slug,
                'sku' => $request->sku,
                'price' => $request->price,
                'stock' => $request->stock,
                'status' => $request->status,
                'main' => 1,
            ]);
        }
        
        return ResponseFormatter::success(new ProductDetailResource($product), 'success create product data');
    }

    public function slug_cek($string)
    {
        $slug = Str::slug($string);
        $count = ProductCombination::whereRaw("product_slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }
}
