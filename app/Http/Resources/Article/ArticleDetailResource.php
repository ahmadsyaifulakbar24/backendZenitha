<?php

namespace App\Http\Resources\Article;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data['id'] = $this->id;
        $data['slug'] = $this->slug;
        $data['type'] = $this->type;
        $data['title'] = $this->title;

        if($this->type == 'article') {
            $data['image_url'] = $this->image_url;
            $data['content'] = $this->content;
        }

        if($this->type == 'video') {
            $data['video_url'] = $this->video_url;
        }

        return $data;
    }
}
