<?php

namespace App\Http\Resources\Discount;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingDiscountResource extends JsonResource
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
            'minimum_price' => $this->minimum_price,
            'max_shipping_discount,' => $this->max_shipping_discount,
            'start_date,' => $this->start_date,
            'end_date,' => $this->end_date,
            'status' => ($this->end_date >= Carbon::now()) ? 'active' : 'not_active',
        ];
    }
}
