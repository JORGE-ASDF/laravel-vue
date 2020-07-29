<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductoResource;
use App\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ProductoController extends Controller
{
    public function index(){
        return view('producto.index');
    }
    public function ProductType(){
        return view('producto.productType');
    }
    public function show(){
        return ProductoResource::collection(Producto::latest()->paginate(5));
    }
    public function store(Request $request){
        $this->validate($request, [
            'image' => 'required',
            'nombre'=>'required',
            'id_tipo'=>'required',
            'stock'=>'required',
            'precio'=>'required'
    ]);

    if($request->get('image'))
    {
        $image = $request->get('image');
        $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        Image::make($request->get('image'))->save(public_path('images/').$name);
    }

   $producto = Producto::create([
    'nombre'=>request('nombre'),
    'precio'=>request('precio'),
    'stock'=>request('stock'),
    'image'=>$name,
    'id_tipo'=>request('id_tipo')

   ]);
   return ProductoResource::make($producto);
    }
    public function edit($id)
    {
      return ProductoResource::make(Producto::findOrFail($id));
    }
    public function update(Request $request, $id){
        $producto = Producto::findOrFail($id)->update([
            'id'=>$id,
            'nombre'=>request('nombre'),
            'precio'=>request('precio'),
            'stock'=>request('stock'),
            'id_tipo'=>request('id_tipo'),
            'updated_at'=>Carbon::now()
        ]);
        return json_encode($producto);
    }
    public function destroy($id){
        Producto::destroy($id);
        return;
    }
    public function search(Request $request){
        $search = $request->get('nombre');
        $producto = ProductoResource::collection(Producto::where('nombre', 'LIKE', "%$search%")->get());
        return $producto;
    }
    public function searchType(Request $request){
        $search = $request->get('tipo');
        $producto = ProductoResource::collection(Producto::where('id_tipo', $search)->get());
        return $producto;
    }
}
