<?php

namespace App\Http\Resources\Transaction;

use App\Models\TransactionProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionSalesReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $query_product = TransactionProduct::where('transaction_id', $this->id);
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'number_resi' => $this->number_resi,
            'marketplace_resi_url' => $this->marketplace_resi_url,
            'bank_name' => $this->bank_name,
            'no_rek' => $this->no_rek,
            'shipping_cost' => $this->shipping_cost,
            'shipping_discount' => $this->shipping_discount,
            'discount_group' => $this->discount_group,
            'discount_customer' => $this->discount_customer,
            'total_price' => $this->total_price,
            'unique_code' => $this->unique_code,
            'address' => $this->address,
            'expedition' => $this->expedition,
            'expedition_service' => $this->expedition_service,
            'expired_time' => $this->expired_time,
            'paid_off_time' => $this->paid_off_time,
            'type' => $this->type,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'transaction_product' => TransactionProductResource::collection($this->transaction_product),
            'other_product' => $query_product->count() - 1
        ];
    }
}
