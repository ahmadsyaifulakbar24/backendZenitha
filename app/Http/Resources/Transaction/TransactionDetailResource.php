<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailResource extends JsonResource
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
            'invoice_number' => $this->invoice_number,
            'number_resi' => $this->number_resi,
            'marketplace_resi' => $this->marketplace_resi,
            'shipping_cost' => $this->shipping_cost,
            'shipping_discount' => $this->shipping_discount,
            'total_price' => $this->total_price,
            'unique_code' => $this->unique_code,
            'address' => $this->address,
            'address' => $this->address,
            'expedition' => $this->expedition,
            'expired_time' => $this->expired_time,
            'type' => $this->type,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'transaction_product' => TransactionProductResource::collection($this->transaction_product),
        ];
    }
}
