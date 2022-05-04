<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'unique_code' => $this->unique_code,
            'total' => $this->total,
            'expired_time' => $this->expired_time,
            'paid_off_time' => $this->paid_off_time,
            'order_payment' => $this->order_payment,
            'status' => $this->status,
        ];
    }
}
