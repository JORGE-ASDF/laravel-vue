<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index(){
        //se retorna la vista index de catalogo
        return view('catalogo.index');
    }
}
