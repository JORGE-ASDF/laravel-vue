<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ["id_cliente",'id_producto','total','cantidad'];
    public function user()
    {
        return $this->belongsTo(User::class,'id_cliente');

    }
    public function producto()
    {
        return $this->belongsTo(Producto::class,'id_producto');

    }
}
