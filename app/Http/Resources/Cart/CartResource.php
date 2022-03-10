<?php

namespace App\Http\Resources\Cart;

use App\Http\Resources\Product\ProductCombinationResource;
use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'quantity' => $this->quantity,
            'product_combination' => new ProductCombinationResource($this->product_combination),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
