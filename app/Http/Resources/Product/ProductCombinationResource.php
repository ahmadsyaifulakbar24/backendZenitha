<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCombinationResource extends JsonResource
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
            'product' => [
                'preorder' => $this->product->preorder,
                'duration' => $this->product->duration,
                'duration_unit' => $this->product->duration_unit,
                'size_unit' => $this->product->size_unit,
                'height' => $this->product->height,
                'length' => $this->product->length,
                'product_weight' => $this->product->product_weight,
                'weight_unit' => $this->product->weight_unit,
                'discount_type' => $this->product->discount_type,
                'discount' => $this->product->discount,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
