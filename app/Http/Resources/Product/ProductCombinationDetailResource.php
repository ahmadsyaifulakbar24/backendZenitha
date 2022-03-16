<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCombinationDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_slug' => $this->product_slug,
            'combination_string' => $this->combination_string,
            'sku' => $this->sku,
            'price' => $this->price,
            'stock' => $this->stock,
            'image_url' => $this->image_url,
            'status' => $this->status,
            'main' => $this->main,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'product' => [
                'id' => $this->product->id,
                'product_name' => $this->product->product_name,
                'category' => $this->product->category,
                'sub_category' => $this->product->sub_category,
                'minimum_order' => $this->product->minimum_order,
                'preorder' => $this->product->preorder,
                'duration' => $this->product->duration,
                'duration_unit' => $this->product->duration_unit,
                'description' => $this->product->description,
                'video_url' => $this->product->video_url,
                'product_weight' => $this->product->product_weight,
                'weight_unit' => $this->product->weight_unit,
                'rate' => $this->product->rate,
                'size_guide' => $this->product->size_guide,
                'discount_type' => $this->discount_type,
                'discount' => $this->discount,
                'status' => $this->product->status,
                'product_image' => ProductImageResource::collection($this->product->product_image),
                'product_variant_option' => ProductVariantOptionResource::collection($this->product->product_variant_option),
                'created_at' => $this->product->created_at,
                'updated_at' => $this->product->updated_at,
            ],
        ];
    }
}
