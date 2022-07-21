<?php

namespace App\Http\Resources\Cart;

use App\Http\Resources\Product\ProductCombinationResource;
use App\Http\Resources\Product\ProductResource;
use App\Models\Discount;
use App\Models\User;
use Carbon\Carbon;
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
        $user = User::find($request->user_id);

        $discount_group = Discount::where([['group_user_id', $user->roles->first()->id], ['category_id', $this->product_combination->product->category->id]])->first();
        $discount_group_status = (Carbon::now() >= $discount_group->start_date && Carbon::now() <= $discount_group->end_date ) ? 'active' : 'not_active';
        $discount_group_data = null;
        if($discount_group_status == 'active') {
            $discount_group_data = [
                'discount_type' => $discount_group->discount_type,
                'discount' => $discount_group->discount,
            ];
        }
        
        $discount_user = Discount::where([['user_id', $user->id], ['category_id', $this->product_combination->product->category->id]])->first();
        $discount_user_status = (Carbon::now() >= $discount_user->start_date && Carbon::now() <= $discount_user->end_date ) ? 'active' : 'not_active';
        $discount_user_data = null;
        if($discount_user_status == 'active') {
            $discount_user_data = [
                'discount_type' => $discount_user->discount_type,
                'discount' => $discount_user->discount,
            ];
        }
        
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'product_name' => $this->product_combination->product->product_name,
            'product_image' => $this->product_combination->product->product_image()->withTrashed()->first()->product_image_url,
            'product_name' => $this->product_combination->product->discount,
            'product_name' => $this->product_combination->product->discount_type,
            'discount_group' => $discount_group_data,
            'discount_user' => $discount_user_data,
            'description' => $this->product_combination->product->description,
            'product_combination' => new ProductCombinationResource($this->product_combination),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->product_combination->product->deleted_at,
        ];
    }
}
