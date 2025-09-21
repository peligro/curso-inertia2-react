<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Perfiles;
use App\Models\UsersMetadata;
class PerfilesController extends Controller
{
    public function __construct()
    {
        if(session("perfil_id")!=1)
        {
            abort(404);
        }
    }
    public function perfiles_index()
    {
        $datos = Perfiles::orderBy('id', 'desc')->get();
        return Inertia::render('perfiles/Home', ['datos'=>$datos]);
    }
    public function perfiles_post(Request $request)
    {
        $validated= $request->validate(
            [
                'nombre'=>'required|min:5',
            ],[
                'nombre.required'=>'El campo nombre está vacío',
                'nombre.min'=>'El campo Nombre debe tener al menos 5 caracteres',
            ]
        );
        
        //validar si existe el nombre de la categoría
        $existe = Perfiles::where(["nombre"=>$request->nombre])->first();
        if(is_object($existe))
        {
            return redirect()->route('perfiles_index')->with(['css'=>'danger', 'mensaje'=>'Ocurrió un error inesperado (El perfil ya existe)']);
        }
        //creamos
        $save = new Perfiles();
        $save->nombre=$request->nombre;
        $save->save();
        //si falla el postgres al crear el registro
        //select setval('perfiles_id_seq', (select max(id) from perfiles  ));
        //redireccionamos
        return redirect()->route('perfiles_index')->with(['css'=>'success', 'mensaje'=>'Se creó el registro exitosamente']);
    }
    public function perfiles_put(Request $request, $id)
    {
        $validated= $request->validate(
            [
                'nombre'=>'required|min:5',
            ],[
                'nombre.required'=>'El campo nombre está vacío',
                'nombre.min'=>'El campo Nombre debe tener al menos 5 caracteres',
            ]
        );
        //editar
        $save = Perfiles::find($id);
        if(!is_object($save))
        {
            return redirect()->route('perfiles_index')->with(['css'=>'danger', 'mensaje'=>'Ocurrió un error inesperado (Perfiles no encontrada)']);
        }
        $save->nombre=$request->nombre;
        $save->save();
        return redirect()->route('perfiles_index')->with(['css'=>'success', 'mensaje'=>'Se modificó el registro exitosamente']);
    }
    public function perfiles_delete(Request $request, $id)
    {
        $save = Perfiles::find($id);
        if(!is_object($save))
        {
            return redirect()->route('perfiles_index')->with(['css'=>'danger', 'mensaje'=>'Ocurrió un error inesperado (Perfiles no encontrada)']);
        }

        //validamos que no exista el perfil en alguna publicación
        if(UsersMetadata::where(['perfiles_id'=>$id])->count()>=1)
        {
            return redirect()->route('perfiles_index')->with(['css'=>'danger', 'mensaje'=>'Ocurrió un error inesperado (Perfil existe en usuarios)']);
        }
        //eliminamos el registro
        try {
            $save->delete();
            return redirect()->route('perfiles_index')->with(['css'=>'success', 'mensaje'=>'Se eliminó el registro exitosamente']);
        } catch (\Exception $e) {
            return redirect()->route('perfiles_index')->with(['css'=>'danger', 'mensaje'=>"Ocurrió un error inesperado (No se puede eliminar el perfil: {$e->getMessage()})"]);
        }
    }
}
