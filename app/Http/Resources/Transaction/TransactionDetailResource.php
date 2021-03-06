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
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone_number' => $this->user->phone_number,
            ],
            'invoice_number' => $this->invoice_number,
            'number_resi' => $this->number_resi,
            'marketplace_resi_url' => $this->marketplace_resi_url,
            'bank_name' => $this->bank_name,
            'no_rek' => $this->no_rek,
            'shipping_cost' => $this->shipping_cost,
            'shipping_discount' => $this->shipping_discount,
            'address' => $this->address,
            'expedition' => $this->expedition,
            'expedition_service' => $this->expedition_service,
            'paid_off_time' => $this->paid_off_time,
            'type' => $this->type,
            'payment_method' => $this->payment_method,
            'total_payment' => $this->total_payment,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'transaction_product' => TransactionProductResource::collection($this->transaction_product),
            'payment' => PaymentResource::collection($this->payments),
        ];
    }
}
