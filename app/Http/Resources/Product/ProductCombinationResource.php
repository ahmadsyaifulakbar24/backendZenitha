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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
