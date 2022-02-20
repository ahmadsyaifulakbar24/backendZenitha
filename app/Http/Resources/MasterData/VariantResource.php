<?php

namespace App\Http\Resources\MasterData;

use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
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
            'variant_name' => $this->variant_name,
            'image' => $this->image,
        ];
    }
}
