<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
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
            'id'=>$this->resource->id,
            'nombre'=>$this->resource->nombre,
            'image'=>$this->resource->image,
            'stock'=>$this->resource->stock,
            'precio'=>$this->resource->precio,
            'id_tipo'=>$this->resource->id_tipo,
            'tipo'=>$this->resource->tipo->name,
            'created_at'=>$this->resource->created_at->diffForHumans(),
            'updated_at'=>$this->resource->updated_at->diffForHumans()
        ];
    }
}
