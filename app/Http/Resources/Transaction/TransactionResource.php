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
        $ongkir = ($this->shipping_discount > $this->shipping_cost) ? 0 : $this->shipping_cost - $this->shipping_discount;
        $total_bill = $this->transaction_product()->sum('price') - $ongkir;
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone_number' => $this->user->phone_number,
            ],
            'invoice_number' => $this->invoice_number,
            'bank_name' => $this->bank_name,
            'no_rek' => $this->no_rek,
            'total_bill' =>  $total_bill, 
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
