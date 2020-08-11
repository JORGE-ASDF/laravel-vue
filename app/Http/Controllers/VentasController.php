<?php

namespace App\Http\Controllers;

use App\Order;

use App\Http\Resources\VentasResource;

use Illuminate\Http\Request;

class VentasController extends Controller
{
    public function index(){
        return view('ventas.index');
    }
    public function show(){
        return VentasResource::collection(Order::latest()->paginate(5));
    }
}
