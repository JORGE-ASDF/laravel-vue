<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name'=>$this->resource->name,
            'direccion'=>$this->resource->direccion,
            'telefono'=>$this->resource->telefono,
            'email'=>$this->resource->email,
            'role'=>$this->resource->role->nombre,
            'avatar'=>$this->resource->avatar,
            'created_at'=>$this->resource->created_at->diffForHumans(),
            'updated_at'=>$this->resource->updated_at->diffForHumans()
        ];
    }
}
