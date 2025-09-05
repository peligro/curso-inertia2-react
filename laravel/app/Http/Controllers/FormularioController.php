<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
class FormularioController extends Controller
{
    public function formulario_index()
    {
        return Inertia::render('formulario/Home');
    }
    public function formulario_post()
    {
        return Inertia::render('formulario/Post');
    }
    public function formulario_post_post(Request $request)
    {
        //dd($request);
        $validated =$request->validate([
            'nombre' => 'required|min:5',
            'correo' => 'required|email',
            
        ],
        [
            'nombre.required'=>'El campo Nombre está vacío',
            'nombre.min'=>'El campo Nombre debe tener al menos 5 caracteres',
            'correo.required'=>'El campo Correo está vacío',
            'correo.email'=>'El Correo ingresado no es válido',

            
        ]); 
        return redirect()->route('formulario_post')->with(['css'=>'success', 'mensaje'=>"Los datos ingresados son: nombre:{$request->nombre} | correo:{$request->correo}"]);
    }
}
