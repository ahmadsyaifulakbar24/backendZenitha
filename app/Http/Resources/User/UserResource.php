<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'gender' => $this->gender,
            'phone_number' => $this->phone_number,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'role' => $this->roles[0]->name,
            'role_id' => $this->roles[0]->id,
            'parent' => [
                'id' => !empty($this->parent) ? $this->parent->id : null,
                'name' => !empty($this->parent) ? $this->parent->name : null,
            ]
        ];
    }
}
