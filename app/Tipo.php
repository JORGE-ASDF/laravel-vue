<?php

namespace App;
use App\Producto;
use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
   public function comments()
   {
       return $this->belongsToMany(Producto::class);
   }
   protected $fillable = ["name","updated_at"];
}
