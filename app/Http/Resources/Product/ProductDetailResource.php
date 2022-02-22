<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
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
            'product_name' => $this->product_name,
            'category' => $this->category,
            'sub_category' => $this->sub_category,
            'minimum_order' => $this->minimum_order,
            'preorder' => $this->preorder,
            'duration' => $this->duration,
            'duration_unit' => $this->duration_unit,
            'description' => $this->description,
            'video_url' => $this->video_url,
            'product_weight' => $this->product_weight,
            'weight_unit' => $this->weight_unit,
            'rate' => $this->rate,
            'size_guide' => $this->size_guide,
            'status' => $this->status,
            'product_image' => ProductImageResource::collection($this->product_image),
            'product_variant_option' => ProductVariantOptionResource::collection($this->product_variant_option),
            'product_combination' => ProductCombinationResource::collection($this->product_combination),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
