<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\MasterData\CategoryResource;
use App\Http\Resources\MasterData\SubCategoryResource;
use App\Models\Discount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCombinationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $discount_group_data = null;
        $discount_user_data = null;
        if($request->user_id) {
            $user = User::find($request->user_id);
            $discount_group = Discount::where([['group_user_id', $user->roles->first()->id], ['category_id', $this->product->category->id]])->first();
            if($discount_group) {
                $discount_group_status = (Carbon::now() >= $discount_group->start_date && Carbon::now() <= $discount_group->end_date ) ? 'active' : 'not_active';
                if($discount_group_status == 'active') {
                    $discount_group_data = [
                        'discount_type' => $discount_group->discount_type,
                        'discount' => $discount_group->discount,
                    ];
                }
            }
            
            $discount_user = Discount::where([['user_id', $user->id], ['category_id', $this->product->category->id]])->first();
            if($discount_user) {
                $discount_user_status = (Carbon::now() >= $discount_user->start_date && Carbon::now() <= $discount_user->end_date ) ? 'active' : 'not_active';
                if($discount_user_status == 'active') {
                    $discount_user_data = [
                        'discount_type' => $discount_user->discount_type,
                        'discount' => $discount_user->discount,
                    ];
                }
            }
        }
        
        return [
            'id' => $this->id,
            'product_slug' => $this->product_slug,
            'combination_string' => $this->combination_string,
            'sku' => $this->sku,
            'price' => $this->price,
            'stock' => $this->stock,
            'image_url' => $this->image_url,
            'status' => $this->status,
            'main' => $this->main,
            'product' => [
                'category' => new CategoryResource($this->product->category),
                'sub_category' => new SubCategoryResource($this->product->sub_category),
                'minimum_order' => $this->product->minimum_order,
                'preorder' => $this->product->preorder,
                'duration' => $this->product->duration,
                'duration_unit' => $this->product->duration_unit,
                'size_unit' => $this->product->size_unit,
                'height' => $this->product->height,
                'length' => $this->product->length,
                'product_weight' => $this->product->product_weight,
                'weight_unit' => $this->product->weight_unit,
                'discount_type' => $this->product->discount_type,
                'discount' => $this->product->discount,
                'discount_group' => $discount_group_data,
                'discount_user' => $discount_user_data,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
