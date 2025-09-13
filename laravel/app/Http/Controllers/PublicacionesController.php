<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Categorias;
use App\Models\Publicaciones;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Storage;


class PublicacionesController extends Controller
{
    public function publicaciones_index()
    {
        $datos=Publicaciones::with('categorias')->orderBy('id', 'desc')->paginate(2);
        return Inertia::render('publicaciones/Home', ['datos'=>$datos]);
    }
    public function publicaciones_add()
    {
        $categorias=Categorias::orderBy('id', 'desc')->get();
        return Inertia::render('publicaciones/Add', ["categorias"=>$categorias]);
    }
    public function publicaciones_add_post(Request $request)
    {
        //dd($request->files);
       // print_r($request->foto->getClientOriginalName() );exit;
      
        $validated = $request->validate([
                'categoria_id' => 'required|exists:categorias,id',
                'nombre' => 'required|min:5|max:100',
                'descripcion' => 'required|min:10',
                'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'categoria_id.required' => 'El campo categoría es obligatorio',
                'categoria_id.exists' => 'La categoría seleccionada no es válida',
                'nombre.required' => 'El campo nombre es obligatorio',
                'nombre.min' => 'El nombre debe tener al menos 5 caracteres',
                'nombre.max' => 'El nombre no debe exceder los 100 caracteres',
                'descripcion.required' => 'El campo descripción es obligatorio',
                'descripcion.min' => 'La descripción debe tener al menos 10 caracteres',
                'foto.required' => 'La foto es obligatoria',
                'foto.image' => 'El archivo debe ser una imagen válida',
                'foto.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif o webp',
                'foto.max' => 'La imagen no debe pesar más de 2MB',
            ]);
            try {
                // Subir a S3
                $path = $request->file('foto')->store('publicaciones', 's3');
                //dd($path);
                // Obtener URL pública (depende de tu configuración de bucket)
                //$url = Storage::disk('s3')->url($path);
                //dd($url);
                 
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['foto' => 'Error al subir la imagen: ' . $e->getMessage()])->withInput();
            }
            
                $publicacion = new Publicaciones();
                $publicacion->categorias_id = $request->categoria_id;
                $publicacion->nombre = $request->nombre;
                $publicacion->slug = Str::slug($request->nombre);
                $publicacion->descripcion = $request->descripcion;
                $publicacion->foto = $path;
                $publicacion->save();

                return redirect()->route('publicaciones_add')->with([
                    'success' => 'Se creó el registro exitosamente'
                ]);
    }
    public function publicaciones_edit(Request $request, $id)
    {
        $datos=Publicaciones::findOrFail($id);
        $categorias=Categorias::orderBy('id', 'desc')->get();
        return Inertia::render('publicaciones/Edit', ['datos'=>$datos, 'categorias'=>$categorias]);
    }
    public function publicaciones_edit_post(Request $request, $id)
    {
        //dd($request);
        $validated = $request->validate([
                'categoria_id' => 'required|exists:categorias,id',
                'nombre' => 'required|min:5|max:100',
                'descripcion' => 'required|min:10',
               
            ], [
                'categoria_id.required' => 'El campo categoría es obligatorio',
                'categoria_id.exists' => 'La categoría seleccionada no es válida',
                'nombre.required' => 'El campo nombre es obligatorio',
                'nombre.min' => 'El nombre debe tener al menos 5 caracteres',
                'nombre.max' => 'El nombre no debe exceder los 100 caracteres',
                'descripcion.required' => 'El campo descripción es obligatorio',
                'descripcion.min' => 'La descripción debe tener al menos 10 caracteres',
                 
            ]);
        if ($request->hasFile('foto')) 
        {
            $foto = $request->file('foto');
            
            // Validar tipo de archivo
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($foto->getMimeType(), $allowedTypes)) {
                return redirect()->back()
                    ->withErrors(['foto' => 'Solo se permiten imágenes JPEG, PNG o JPG'])
                    ->withInput();
            }
            
            // Validar tamaño (2MB máximo)
            if ($foto->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()
                    ->withErrors(['foto' => 'La imagen no debe pesar más de 2MB'])
                    ->withInput();
            }
            
             
        }
        $publicacion = Publicaciones::findOrFail($id);
        $fotoAnterior = $publicacion->foto;
        $nuevaRuta = $publicacion->foto;
        if ($request->hasFile('foto')) 
        {
            try {
                $path = $request->file('foto')->store('publicaciones', 's3');

                
            } catch (\Exception $e) {
                
                return redirect()->route('publicaciones_edit', ['id'=>$id])->with(['css'=>'danger', 'mensaje'=>'Error al subir la imagen: ' . $e->getMessage()]);
            }
            //eliminamos la foto anterior 
             try {
                    
                if ($fotoAnterior && Storage::disk('s3')->exists($fotoAnterior))
                    {
                    try {
                        Storage::disk('s3')->delete($fotoAnterior);
                    } catch (\Exception $e) {
                        // Log del error pero no interrumpir el flujo
                        return redirect()->route('publicaciones_edit', ['id'=>$id])->with(['css'=>'danger', 'mensaje'=>'Error al eliminar foto anterior de S3: ' . $e->getMessage()]);
                    }
                }
                } catch (\Exception $e) {
                    // No interrumpir el flujo si falla la eliminación, solo loggear 
                    return redirect()->route('publicaciones_edit', ['id'=>$id])->with(['css'=>'danger', 'mensaje'=>'Error al eliminar foto anterior de S3: ' . $e->getMessage()]);
                } 
        }
        
        
        //guardamos el registro en la bd
        $publicacion = Publicaciones::findOrFail($id);
        $publicacion->categorias_id = $request->categoria_id;
        $publicacion->nombre = $request->nombre;
        $publicacion->slug = Str::slug($request->nombre);
        $publicacion->descripcion = $request->descripcion;
        if ($request->hasFile('foto')) 
        {
            $publicacion->foto = $path;
        }
        
        
        $publicacion->save();
        
        return redirect()->route('publicaciones_edit', ['id'=>$id])->with(['css'=>'success', 'mensaje'=>'Se modificó el registro exitosamente']);
        
    }
    public function publicaciones_delete(Request $request, $id)
    {
        $datos = Publicaciones::findOrFail($id);
        //eliminamos la foto anterior
         

            if ($datos->foto) {
                try {
                    // Verificar que la foto existe en S3 antes de intentar eliminarla
                    if (Storage::disk('s3')->exists($datos->foto)) {
                        Storage::disk('s3')->delete($datos->foto);
                        \Log::info('Foto eliminada de S3: ' . $datos->foto);
                        
                    } else {
                         return redirect()->route('publicaciones_index' )->with(['css'=>'danger', 'mensaje'=>'Ocurrió un error inesperado (la fotono existe en s3)']);
                    }
                            
                } catch (\Exception $e) {
                    return redirect()->route('publicaciones_index' )->with(['css'=>'danger', 'mensaje'=>'Ocurrió un error inesperado']);
                }
            }
    
            
        //eliminamos el registro
        try {
            
            $datos->delete();
            return redirect()->route('publicaciones_index')->with([
            'css' => 'success', 
            'mensaje' => 'Se eliminó registro exitosamente'
        ]);
        
        } catch (\Exception $e) {
            return redirect()->route('publicaciones_index')->with([
                'css' => 'danger', 
                'mensaje' => 'No se puede eliminar la categoría: ' . $e->getMessage()
            ]);
        }
    }
}
