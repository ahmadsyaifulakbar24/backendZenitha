<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Discount\DiscountResource;
use App\Models\Discount;
use App\Models\ProductCombination;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    private $data;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    
    public function toArray($request)
    {
        $main_product = ProductCombination::where([['product_id', $this->id], ['main', 1]])->first();
        
        $discount_group_data = null;
        $discount_user_data = null;
        if($request->user_id) {
            $user = User::find($request->user_id);
            $discount_group = Discount::where([['group_user_id', $user->roles->first()->id], ['category_id', $this->category->id]])->first();
            $discount_group_status = (Carbon::now() >= $discount_group->start_date && Carbon::now() <= $discount_group->end_date ) ? 'active' : 'not_active';
            if($discount_group_status == 'active') {
                $discount_group_data = [
                    'discount_type' => $discount_group->discount_type,
                    'discount' => $discount_group->discount,
                ];
            }
            
            $discount_user = Discount::where([['user_id', $user->id], ['category_id', $this->category->id]])->first();
            $discount_user_status = (Carbon::now() >= $discount_user->start_date && Carbon::now() <= $discount_user->end_date ) ? 'active' : 'not_active';
            if($discount_user_status == 'active') {
                $discount_user_data = [
                    'discount_type' => $discount_user->discount_type,
                    'discount' => $discount_user->discount,
                ];
            }
        }

        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'category' => $this->category,
            'sub_category' => $this->sub_category,
            'preorder' => $this->preorder,
            'rate' => $this->rate,
            'status' => $this->status,
            'price' => $this->price,
            'active_discount' => !empty($this->discount_type) ? 1 : 0,
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
            'discount_group' => $discount_group_data,
            'discount_user' => $discount_user_data,
            'main_product' => $main_product,
            'image' => $this->product_image()->first()->product_image_url
        ];
    }
}
