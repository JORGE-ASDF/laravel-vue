<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VentasResource extends JsonResource
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
           'user_name'=>$this->resource->user->name,
           'product_name'=>$this->resource->producto->nombre,
           'total'=>$this->resource->total,
           'cantidad'=>$this->resource->cantidad,
           'created_at'=>$this->resource->created_at->diffForHumans()
       ];
    }
}
