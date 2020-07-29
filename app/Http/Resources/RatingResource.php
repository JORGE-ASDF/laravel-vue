<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'product'=>$this->resource->product_id,
            'user_id'=>$this->resource->user_id,
            'user'=>$this->resource->user->name,
            'avatar'=>$this->resource->user->avatar,
            'comment'=>$this->resource->comment,
            'rating'=>$this->resource->rating
        ];
    }
}
