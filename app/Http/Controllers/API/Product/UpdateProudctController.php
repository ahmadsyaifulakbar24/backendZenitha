<?php

namespace App\Http\Controllers\API\Product;

use App\Helpers\FileHelpers;
use App\Helpers\ResponseFormatter;
use App\Helpers\StrHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductDetailResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UpdateProudctController extends Controller
{
    public function update(Request $request, Product $product)
    {
        $request->validate([
            // info product
            'sku' => [
                'nullable', 
                'unique:products,sku,'.$product->id
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
            'combination.*.sku' => [ 'nullable', 'string'],
            'combination.*.price' => [ 'required_with:combination', 'integer' ],
            'combination.*.stock' => [ 'required_with:combination', 'integer' ],
            'combination.*.image' => [ 'nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg' ],
            'combination.*.status' => [ 'required_with:combination', 'in:active,not_active' ],

              // product image
            'product_image' => ['nullable', 'array'],
            'product_image.*.product_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
            'product_image.*.order' => ['required_with:product_image', 'integer'],
        ]);
        $input = $request->all();
        if(empty($request->variant)) {
            $input['total_stock'] =  $request->total_stock;
        }
        if(empty($request->variant)) {
            $input['price'] = $request->price;
        }
        if(empty($request->variant)) {
            $input['status'] = $request->status;
        }

        $product->update($input);

        // update Image Product
            $product_image_order =  $product->product_image()->get()->pluck('order')->toArray();
            foreach($request->product_image as $product_image) 
            {
                if(!empty($product_image['product_image'])) {
                    $path = FileHelpers::upload_file('product', $product_image['product_image']);
                    $product_images = [
                        'product_image' => $path,
                        'order' => $product_image['order']
                    ];
                } else {
                    $product_images = [
                        'order' => $product_image['order']
                    ];
                }
                if(in_array($product_image['order'], $product_image_order)) {
                    $product_image_d = $product->product_image()->where('order', $product_image['order'])->first();
                    Storage::disk('public')->delete($product_image_d['product_image']);
                    $product_image_d->update($product_images);
                } else {
                    $product->product_image()->create($product_images);
                }
                $orders[] = $product_image['order'];
            }
            $except_order = array_values(array_diff($product_image_order, $orders));
            if(!empty($except_order)) {
                $except_product_image = $product->product_image()->whereIn('order', $except_order);
                $p_imgs = $except_product_image->get()->pluck('product_image')->toArray();
                Storage::disk('public')->delete($p_imgs);
                $except_product_image->delete();
            }
        // end update image product
        
        // update product variant
            $product_variant_names = $product->product_variant_option()->get()->pluck('variant_name')->toArray();
            if($request->variant) {

                foreach($request->variant as $variant) {
                    if(in_array($variant['variant_name'], $product_variant_names)) {
                        $product_variant_option = $product->product_variant_option()->where('variant_name', $variant['variant_name'])->first();
                    } else {
                        $product_variant_option = $product->product_variant_option()->create([ 'variant_name' => $variant['variant_name'] ]);
                    }
                    $product_variant_option->product_variant_option_value()->sync($variant['variant_option']);
                    $variants[] = $variant['variant_name'];
                }
                $except_variant = array_values(array_diff($product_variant_names, $variants));
                if(!empty($except_variant)) {
                    $product->product_variant_option()->whereIn('variant_name', $except_variant)->delete();
                }

                // update product combination
                $total_stock = 0;
                $unique_strings = $product->product_combination()->get()->pluck('unique_string')->toArray();
                foreach($request->combination as $combination) {
                    $unique_string =  Str::lower(StrHelper::sort_character(Str::replace('-', '', $combination['combination_string'])));
                    $total_stock = 0 + $combination['stock'];
                    $prices[] = $combination['price'];
                    $statuses[] = $combination['status']; 
                    
                    if(!empty($combination['image'])) {
                        $image_path = FileHelpers::upload_file('product', $combination['image']);
                        $product_combination = [
                            'combination_string' => $combination['combination_string'],
                            'sku' => $combination['sku'],
                            'price' => $combination['price'],
                            'unique_string' => $unique_string,
                            'stock' => $combination['stock'],
                            'image' => $image_path,
                            'status' => $combination['status'],
                        ];
                    } else {
                        $product_combination = [
                            'combination_string' => $combination['combination_string'],
                            'sku' => $combination['sku'],
                            'price' => $combination['price'],
                            'unique_string' => $unique_string,
                            'stock' => $combination['stock'],
                            'status' => $combination['status'],
                        ];
                    }
                    if(in_array($unique_string, $unique_strings)) {
                        $product->product_combination()->where('unique_string', $unique_string)->first()->update($product_combination);
                    } else {
                        $product->product_combination()->create($product_combination);
                    }
                    $unique_string2[] = $unique_string;
                }
                $except_unique_string = array_values(array_diff($unique_strings, $unique_string2));
                if(!empty($except_unique_string)) {
                    $except_combination_image = $product->product_combination()->whereIn('unique_string', $except_unique_string);
                    $c_img = $except_combination_image->get()->pluck('image')->toArray();
                    Storage::disk('public')->delete($c_img);
                    $except_product_image->delete();
                }
                $product->update([ 
                    'total_stock' => $total_stock,
                    'price' => min($prices),
                    'status' => in_array('active', $statuses) ? 'active' : 'not_active'
                ]);
                // end update product combination
            }
        // end update product variant
        
        return ResponseFormatter::success(new ProductDetailResource($product), 'success update product data');
    }
}
