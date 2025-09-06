<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Categorias;
use App\Models\Publicaciones;
use Illuminate\Support\Str;
class CategoriasController extends Controller
{
    public function categorias_index()
    {
        $datos=Categorias::orderBy('id', 'desc')->get();
        return Inertia::render('categorias/Home', ['datos'=>$datos]);
    }
    public function categorias_post(Request $request)
    {
        $validated =$request->validate([
            'nombre' => 'required|min:5',
        ],
        [
            'nombre.required'=>'El campo Nombre está vacío',
            'nombre.min'=>'El campo Nombre debe tener al menos 5 caracteres', 
        ]); 
        $existe=Categorias::where(["nombre"=>$request->nombre])->first();
        if(is_object($existe))
        {
            return redirect()->route('categorias_index')->with(['css'=>'danger', 'mensaje'=>'La categoría ya existe']);
        }
        $save=new Categorias();
        $save->nombre=$request->nombre;
        $save->slug=Str::slug($request->nombre, '-');
        $save->save();
         //si da problemas con los autoincrementables usar: SELECT setval('categorias_id_seq', (SELECT MAX(id) FROM tareas));
        return redirect()->route('categorias_index')->with(['css'=>'success', 'mensaje'=>'Se creó el registro exitosamente']);
    }
    public function categorias_put(Request $request, $id)
    {
        //dd($request);
        //dd($id);
        $validated =$request->validate([
            'nombre' => 'required|min:5',
            
        ],
        [
            'nombre.required'=>'El campo Nombre está vacío',
            'nombre.min'=>'El campo Nombre debe tener al menos 5 caracteres',

        ]); 
        $save = Categorias::find($id);
        if(!is_object($save))
        {
            return redirect()->route('categorias_index')->with(['css'=>'danger', 'mensaje'=>'Ocurrió un error inesperado']);
        }
        $save->nombre=$request->nombre;
        $save->slug=Str::slug($request->nombre, '-');
        $save->save();
        return redirect()->route('categorias_index')->with(['css'=>'success', 'mensaje'=>'Se modificó el registro exitosamente']);
    }
    public function categorias_delete(Request $request, $id)
    {
        $save = Categorias::find($id);
        if(!is_object($save))
        {
            return redirect()->route('categorias_index')->with(['css'=>'danger', 'mensaje'=>'Ocurrió un error inesperado']);
        }
        //validamos que no exista la categoría en alguna publicación
        if(Publicaciones::where(['categorias_id'=>$id])->count()>=1)
        {
            return redirect()->route('categorias_index')->with(['css'=>'danger', 'mensaje'=>'Ocurrió un error inesperado (Categoría existe en publicaciones)']);
        }
        //eliminamos el registro
        try {
            
            $save->delete();
            return redirect()->route('categorias_index')->with([
            'css' => 'success', 
            'mensaje' => 'Se eliminó registro exitosamente'
        ]);
        
        } catch (\Exception $e) {
            return redirect()->route('categorias_index')->with([
                'css' => 'danger', 
                'mensaje' => 'No se puede eliminar la categoría: ' . $e->getMessage()
            ]);
        }
    }
}
