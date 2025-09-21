<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Publicaciones;
use App\Models\Categorias;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PublicacionesController extends Controller
{
    public function publicaciones_index()
    {
        $datos = Publicaciones::with('categorias')->orderBy('id', 'desc')->paginate(5);
        return Inertia::render('publicaciones/Home', ['datos'=>$datos]);
    }
    public function publicaciones_add()
    {
        $categorias=Categorias::get();
        return Inertia::render('publicaciones/Add', ['categorias'=>$categorias]);
    }
    public function publicaciones_add_post(Request $request)
    {
        //dd($request);
       // dd($request->foto->getClientOriginalName());
        $validated= $request->validate(
            [
                'categoria_id'=>'required|exists:categorias,id',
                'nombre'=>'required|min:5|max:100',
                'descripcion'=>'required|min:10',
                'foto'=>'required|image|mimes:jpeg,png,jpg|max:2048'
            ],[
                'categoria_id.required'=>'El campo categoría está vacío',
                'categoria_id.exists'=>'La categoría seleccionada no es válida',
                'nombre.required'=>'El campo nombre está vacío',
                'nombre.min'=>'El campo Nombre debe tener al menos 5 caracteres',
                'nombre.max'=>'El campo Nombre no debe exeder los 100 caracteres',
                'descripcion.required'=>'El campo descripción está vacío',
                'descripcion.min'=>'El campo descripción debe tener al menos 5 caracteres',
                'foto.required'=>'El campo foto está vacío',
                'foto.image'=>'El archivo debe ser una imagen válida',
                'foto.mimes'=>'La imagen debe ser de tipo: jpeg, png, jpg',
                'foto.max'=>'La imagen no debe pesar mas de 2MB',
            ]
        );
        try {
           $path= $request->file('foto')->store('publicaciones', 's3');
        } catch (\Exception $e) {
            return redirect()->route('publicaciones_add')->with(['css'=>'danger', 'mensaje'=>"Error al subir la imagen: {$e->getMessage()}"]);
        }
        
        $publicaciones = new Publicaciones();
        $publicaciones->categorias_id = $request->categoria_id;
        $publicaciones->nombre=$request->nombre;
        $publicaciones->slug = Str::slug($request->nombre, '-');
        $publicaciones->descripcion = $request->descripcion;
        $publicaciones->foto = $path;
        $publicaciones->save();

        //si falla el postgres al crear el registro
        //select setval('publicaciones_id_seq', (select max(id) from publicaciones  ));
        //redireccionamos
        return redirect()->route('publicaciones_add')->with(['css'=>'success', 'mensaje'=>'Se creó el registro exitosamente']);
    }
    public function publicaciones_edit(Request $request, $id)
    {
        $datos= Publicaciones::findOrFail($id);
        $categorias=Categorias::get();
        return Inertia::render('publicaciones/Edit', ['categorias'=>$categorias, 'datos'=>$datos]);
    }
    public function publicaciones_edit_post(Request $request, $id){
        $validated= $request->validate(
            [
                'categoria_id'=>'required|exists:categorias,id',
                'nombre'=>'required|min:5|max:100',
                'descripcion'=>'required|min:10',
                //'foto'=>'required|image|mimes:jpeg,png,jpg|max:2048'
            ],[
                'categoria_id.required'=>'El campo categoría está vacío',
                'categoria_id.exists'=>'La categoría seleccionada no es válida',
                'nombre.required'=>'El campo nombre está vacío',
                'nombre.min'=>'El campo Nombre debe tener al menos 5 caracteres',
                'nombre.max'=>'El campo Nombre no debe exeder los 100 caracteres',
                'descripcion.required'=>'El campo descripción está vacío',
                'descripcion.min'=>'El campo descripción debe tener al menos 5 caracteres',
                //'foto.required'=>'El campo foto está vacío',
                //'foto.image'=>'El archivo debe ser una imagen válida',
                //'foto.mimes'=>'La imagen debe ser de tipo: jpeg, png, jpg',
                //'foto.max'=>'La imagen no debe pesar mas de 2MB',
            ]
        );
        //validamos foto
        if($request->hasFile('foto'))
        {
            $foto = $request->file('foto');
            //validamos tipo de archivo
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if( !in_array($foto->getMimeType(), $allowedTypes) ){
                return redirect()
                ->back()
                ->withErrors(['foto' =>'Solo se permiten imágenes JPEG, PNG o JPG'])
                ->withInput();
            }
            //valida tamaño del archivo
            if($foto->getSize()>2 * 1024 * 1024)
            {
                return redirect()
                ->back()
                ->withErrors(['foto' =>'La imagen no debe pesar mas de 2MB'])
                ->withInput();
            }
        }
        $publicacion =Publicaciones::findOrFail($id);
        $fotoAnterior= $publicacion->foto;
        $nuevaRuta = $publicacion->foto;
        if($request->hasFile('foto'))
        {
            try {
                $nuevaRuta= $request->file('foto')->store('publicaciones', 's3');
            } catch (\Exception $e) {
                return redirect()->route('publicaciones_edit', ['id'=>$id])->with(['css'=>'danger', 'mensaje'=>"Error al subir la imagen: {$e->getMessage()}"]);
            }
            //eliminamos la foto anterior
            try {
                if($fotoAnterior && Storage::disk('s3')->exists($fotoAnterior))
                {
                    try {
                        Storage::disk('s3')->delete($fotoAnterior);
                    } catch (\Exception $e2) {
                        return redirect()->route('publicaciones_edit', ['id'=>$id])->with(['css'=>'danger', 'mensaje'=>"Error al eliminar foto anterior: {$e->getMessage()}"]);
                    }
                }
            } catch (\Exception $e) {
                return redirect()->route('publicaciones_edit', ['id'=>$id])->with(['css'=>'danger', 'mensaje'=>"Error al eliminar foto anterior: {$e->getMessage()}"]);
            }
        }
        //guardamos el registro en la BD
        $publicacion->categorias_id = $request->categoria_id;
        $publicacion->nombre = $request->nombre;
        $publicacion->slug = Str::slug($request->nombre, '-');
        $publicacion->descripcion = $request->descripcion;
        if($request->hasFile('foto'))
        {
            $publicacion->foto = $nuevaRuta;
        }
        
        
        $publicacion->save();
        return redirect()->route('publicaciones_edit', ['id'=>$id])->with(['css'=>'success', 'mensaje'=>'Se modificó el registro exitosamente']);
    }
    public function publicaciones_delete(Request $request, $id)
    {
        $datos = Publicaciones::findOrFail($id);
        //eliminar la foto anterior
        if($datos->foto)
        {
            try {
                if(Storage::disk('s3')->exists($datos->foto))
                {
                    Storage::disk('s3')->delete($datos->foto);
                }else
                {
                    return redirect()->route('publicaciones_delete', ['id'=>$id])->with(['css'=>'danger', 'mensaje'=>"Ocurrió un error inesperado (la foto no existe en s3)"]);
                }
            } catch (\Exception $e) {
                return redirect()->route('publicaciones_delete', ['id'=>$id])->with(['css'=>'danger', 'mensaje'=>"Ocurrió un error inesperado: {$e->getMessage()}"]);
            }
        }
        //eliminar el registro
        try {
            $datos->delete();
            return redirect()->route('publicaciones_index')->with(['css'=>'success', 'mensaje'=>'Se eliminó el registro exitosamente']);
        } catch (\Exception $e) {
            return redirect()->route('publicaciones_delete', ['id'=>$id])->with(['css'=>'danger', 'mensaje'=>"Ocurrió un error inesperado: {$e->getMessage()}"]);
        }
    }
}
