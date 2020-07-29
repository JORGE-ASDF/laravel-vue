<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductoResource;
use App\Producto;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //se usa el middeware de autentificacion
        $this->middleware('auth');


    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    //funcion para retornar a la vista home
    public function index()
    {
        //se retorna la vista
        return view('home');
    }
    //funcion para obtener los 3 productos agregados recientemente
    public function show(){
        //variable que obtiene en forma de coleccin los 3 productos recientes
        $productos =  ProductoResource::collection(Producto::latest()->paginate(3));
        //se retona la variable
        return $productos;
    }
}
