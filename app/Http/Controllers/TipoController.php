<?php

namespace App\Http\Controllers;

use App\Tipo;
use Illuminate\Http\Request;
use App\Http\Resources\TypeResource;
use Carbon\Carbon;

class TipoController extends Controller
{
    public function index(){
        return view('tipo.index');
    }
    public function show(){
        return TypeResource::collection(Tipo::latest()->paginate(5));
    }
    public function showAll(){
        return TypeResource::collection(Tipo::all());
    }

    public function store(Request $request)
    {
        $tipo = Tipo::create([
            'name' => request('name')
        ]);
        return TypeResource::make($tipo);
    }
    public function edit($id)
    {
      $tipo = Tipo::findOrFail($id);
      return response()->json($tipo);
    }
    public function update(Request $request, $id){
        $tipo = Tipo::findOrFail($id)->update([
            'name'=>request('name'),
            'updated_at'=>Carbon::now()
        ]);
       return json_encode($tipo);
    }
    public function destroy($id)
    {
        Tipo::destroy($id);
        return;
    }
}
