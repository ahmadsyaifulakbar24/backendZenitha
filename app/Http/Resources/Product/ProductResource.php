<?php

namespace App\Http\Resources\Product;

use App\Models\ProductCombination;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $main_product = ProductCombination::where([['product_id', $this->id], ['main', 1]])->first();
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'category' => $this->category,
            'sub_category' => $this->sub_category,
            'preorder' => $this->preorder,
            'rate' => $this->rate,
            'status' => $this->status,
            'price' => $this->price,
            'active_discount' => !empty($this->discount_type) ? 1 : 0,
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
            'main_product' => [
                'id' => $main_product->id,
                'product_slug' => $main_product->product_slug,
                'combination_string' => $main_product->combination_string,
                'price' => $main_product->price,
                'unique_string' => $main_product->unique_string,
            ],
            'image' => $this->product_image()->first()->product_image_url
        ];
    }
}
