<?php

namespace App\Http\Resources\Discount;

use App\Http\Resources\MasterData\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
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
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
            'category' => new CategoryResource($this->category),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
