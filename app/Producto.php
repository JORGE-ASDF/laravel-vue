<?php

namespace App;
use App\Tipo;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{

    protected $fillable =['nombre','precio','stock','id_tipo','image'];

   public function tipo()
   {
       return $this->belongsTo(Tipo::class,'id_tipo');
   }
   public function order()
   {
       return $this->hasMany(Order::class);
   }
   public function rating()
   {
       return $this->hasMany(Rating::class);
   }
}
