<?php

namespace App\Http\Controllers;
use App\Models\Perfiles;
use App\Models\UsersMetadata;
use Illuminate\Http\Request;
use Inertia\Inertia;
class PerfilesController extends Controller
{
    public function __construct()
    {
        if(session('perfil_id')!='1')
        {
            abort(404);
        }
    }
    public function perfiles_index()
    {
        $datos=Perfiles::orderBy('id', 'desc')->get();
        return Inertia::render('perfiles/Home', ['datos'=>$datos]);
    }
    public function perfiles_post(Request $request)
    {
        $validated =$request->validate([
            'nombre' => 'required|min:5',
        ],
        [
            'nombre.required'=>'El campo Nombre está vacío',
            'nombre.min'=>'El campo Nombre debe tener al menos 5 caracteres', 
        ]); 
        $existe=Perfiles::where(["nombre"=>$request->nombre])->first();
        if(is_object($existe))
        {
            return redirect()->route('perfiles_index')->with(['css'=>'danger', 'mensaje'=>'El perfil ya existe']);
        }
        $save=new Perfiles();
        $save->nombre=$request->nombre;
        $save->save();
         //si da problemas con los autoincrementables usar: SELECT setval('perfiles_id_seq', (SELECT MAX(id) FROM perfiles));
        return redirect()->route('perfiles_index')->with(['css'=>'success', 'mensaje'=>'Se creó el registro exitosamente']);
    }
    public function perfiles_put(Request $request, $id)
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
        $save = Perfiles::find($id);
        if(!is_object($save))
        {
            return redirect()->route('perfiles_index')->with(['css'=>'danger', 'mensaje'=>'Ocurrió un error inesperado']);
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
            return redirect()->route('perfiles_index')->with(['css'=>'danger', 'mensaje'=>'Ocurrió un error inesperado']);
        }
        //validamos que no exista el perfil en algún usuario
        if(UsersMetadata::where(['perfiles_id'=>$id])->count()>=1)
        {
            return redirect()->route('perfiles_index')->with(['css'=>'danger', 'mensaje'=>'Ocurrió un error inesperado (Perfil existe en usuarios)']);
        }
        //eliminamos el registro
        try {
            
            $save->delete();
            return redirect()->route('perfiles_index')->with([
            'css' => 'success', 
            'mensaje' => 'Se eliminó registro exitosamente'
        ]);
        
        } catch (\Exception $e) {
            return redirect()->route('perfiles_index')->with([
                'css' => 'danger', 
                'mensaje' => 'No se puede eliminar la categoría: ' . $e->getMessage()
            ]);
        }
    }
}
