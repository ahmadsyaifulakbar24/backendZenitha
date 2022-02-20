<?php

namespace App\Http\Resources\MasterData;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
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
            'category_id' => $this->category_id,
            'sub_category_slug' => $this->sub_category_slug,
            'sub_category_name' => $this->sub_category_name,
        ];
    }
}
