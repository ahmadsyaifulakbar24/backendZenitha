<?php

namespace App\Http\Resources\Transaction;

use App\Models\TransactionProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
        $transaction_product = $query_product->first();
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'bank_name' => $this->bank_name,
            'no_rek' => $this->no_rek,
            'total_price' => $this->total_price,
            'expired_time' => $this->expired_time,
            'paid_off_time' => $this->paid_off_time,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'transaction_product' => [
                'id' => $transaction_product->id,
                'product_slug' => $transaction_product->product_slug,
                'image' => $transaction_product->image,
                'product_name' => $transaction_product->product_name,
                'price' => $transaction_product->price,
                'quantity' => $transaction_product->quantity,
            ],
            'other_product' => $query_product->count() - 1
        ];
    }
}
