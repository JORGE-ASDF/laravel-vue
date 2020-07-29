<?php

namespace App\Http\Controllers;

use App\Order;

use Illuminate\Http\Request;

use function GuzzleHttp\json_decode;

class CarritoController extends Controller
{
    public function index(){
        return view('cart.index');
    }
    public function store(Request $request){
     foreach($request->data['data'] as $data){
         $order= new Order([
             'id_cliente'=>auth()->user()->id,
             'id_producto'=>$data['id'],
             'total'=>$data['cantidad']*$data['precio'],
             'cantidad'=>$data['cantidad']
         ]);
            $order->saveOrFail();
     }
     return response()->json('exito');
    }
}
