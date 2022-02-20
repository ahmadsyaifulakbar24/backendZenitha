<?php

namespace App\Http\Resources\WebSetting;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'banner_url' => $this->banner_url,
            'order' => $this->order,
            'url' => $this->url,
        ];
    }
}
