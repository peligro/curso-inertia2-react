<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
class FormulariosController extends Controller
{
    public function formularios_index()
    {
        return Inertia::render('formularios/Home');
    }
    public function formularios_post()
    {
        return Inertia::render('formularios/Post');
    }
    public function formularios_post_post(Request $request)
    {
        $validated= $request->validate(
            [
                'nombre'=>'required|min:5',
                'correo'=>'required|email'
            ],[
                'nombre.required'=>'El campo nombre está vacío',
                'nombre.min'=>'El campo Nombre debe tener al menos 5 caracteres',
                'correo.required'=>'El campo Correo está vacío',
                'correo.email'=>'El Correo ingresado no es válido'
            ]
        );
        return redirect()->route('formularios_post')->with(['css'=>'success', 'mensaje'=>"Los datos ingresados son: nombre:{$request->nombre} | correo={$request->correo} "]);
    }
}
