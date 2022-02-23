<?php

namespace App\Http\Resources\Product;

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
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'category' => $this->category,
            'sub_category' => $this->sub_category,
            'preorder' => $this->preorder,
            'rate' => $this->rate,
            'status' => $this->status,
            'image' => $this->product_image()->first()->product_image_url
        ];
    }
}
