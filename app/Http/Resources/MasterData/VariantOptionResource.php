<?php

namespace App\Http\Resources\MasterData;

use Illuminate\Http\Resources\Json\JsonResource;

class VariantOptionResource extends JsonResource
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
            'variant_id' => $this->variant_id,
            'variant_option_name' => $this->variant_option_name,
            'default' => $this->default,
        ];
    }
}
