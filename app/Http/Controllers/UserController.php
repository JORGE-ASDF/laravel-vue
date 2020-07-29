<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;

use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function index(){
        //retorna la vista index de usuario
        return view('user.index');
    }
    public function show(){
        //retorna una collecion con todos los usuarios paginados
        return UserResource::collection(User::latest()->paginate(5));
    }
    public function profile()
    {
        //variable que almacena el id del usuario loggeado
        $user_id = auth()->user()->id;
        //retorna un recurso con la info del usuario por id
        return UserResource::make(User::findOrFail($user_id));
    }
    public function update(Request $request)
    {
        //se verifica que el request contenga imagen
        if($request->get('avatar'))
        {
            //variable que almacena la imagen del request
            $image = $request->get('avatar');
            //se renombra el nombre de la imagen
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            //se guarda la imagen en una carpeta dentro del proyecto
            Image::make($request->get('avatar'))->save(public_path('images/').$name);
        }
        //si no se recibe una imagen del request
        else{
            //el nombre de la imagen se iguala al avatar del usuario loggeado
            $name = auth()->user()->avatar;
        }
        //almacena el id del usuario loggeado
        $user_id = auth()->user()->id;
        //se busca el usuario por id y se actuliza la info
        User::findOrFail($user_id)->update([
        'name'=>request('name'),
        'direccion'=>request('direccion'),
        'telefono'=>request('telefono'),
        'avatar'=>$name
       ]);
       //se retorna una respuesta en formato json
       return response()->json('success');
    }
}

