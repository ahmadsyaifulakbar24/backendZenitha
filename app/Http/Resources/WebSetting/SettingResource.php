<?php

namespace App\Http\Resources\WebSetting;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
            'logo_url' => $this->logo_url,
            'name' => $this->name,
            'description' => $this->description,
            'email' => $this->email,
            'phone' => $this->phone,
            'province' => [
                'id' => $this->province->id,
                'province' => $this->province->province
            ],
            'city' => [
                'id' => $this->city->id,
                'city' => $this->city->city
            ],
            'district' => [
                'id' => $this->district->id,
                'district' => $this->district->district
            ],
            'postal_code' => $this->postal_code,
            'address' => $this->address,
            'fb_status' => $this->fb_status,
            'fb' => $this->fb,
            'tw_status' => $this->tw_status,
            'tw' => $this->tw,
            'yt_status' => $this->yt_status,
            'yt' => $this->yt,
            'ig_status' => $this->ig_status,
            'ig' => $this->ig,
        ];
    }
}
