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
            'main_product' => $main_product,
            'image' => $this->product_image()->first()->product_image_url
        ];
    }
}
