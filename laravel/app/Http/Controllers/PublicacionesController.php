<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Categorias;
use App\Models\Publicaciones;
use Illuminate\Support\Str;
use Cloudinary\Cloudinary;


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
      /*dd([
        'cloudinary_config' => config('cloudinary'),
        'filesystems_cloudinary' => config('filesystems.disks.cloudinary'),
        'env_CLOUDINARY_URL' => env('CLOUDINARY_URL'),
        'env_CLOUDINARY_CLOUD_NAME' => env('CLOUDINARY_CLOUD_NAME'),
        'env_CLOUDINARY_API_KEY' => env('CLOUDINARY_API_KEY'),
        'env_CLOUDINARY_API_SECRET' => env('CLOUDINARY_API_SECRET'),
    ]);*/
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
                $cloudinary = new Cloudinary([
                    'cloud' => [
                        'cloud_name' => config('filesystems.disks.cloudinary.cloud_name'),
                        'api_key' => config('filesystems.disks.cloudinary.api_key'),
                        'api_secret' => config('filesystems.disks.cloudinary.api_secret'),
                    ]
                ]);

                $uploadedFile = $cloudinary->uploadApi()->upload(
                    $request->file('foto')->getRealPath(),
                    ['folder' => 'udemy-course/publicaciones']
                );  
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['foto' => 'Error al subir la imagen: ' . $e->getMessage()])->withInput();
            }
            // Guardar la URL de Cloudinary
                $publicacion = new Publicaciones();
                $publicacion->categorias_id = $request->categoria_id;
                $publicacion->nombre = $request->nombre;
                $publicacion->slug = Str::slug($request->nombre);
                $publicacion->descripcion = $request->descripcion;
                $publicacion->foto = $uploadedFile['secure_url'];
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
        if ($request->hasFile('foto')) 
        {
            try {
                $cloudinary = new Cloudinary([
                    'cloud' => [
                        'cloud_name' => config('filesystems.disks.cloudinary.cloud_name'),
                        'api_key' => config('filesystems.disks.cloudinary.api_key'),
                        'api_secret' => config('filesystems.disks.cloudinary.api_secret'),
                    ]
                ]);

                $uploadedFile = $cloudinary->uploadApi()->upload(
                    $request->file('foto')->getRealPath(),
                    ['folder' => 'udemy-course/publicaciones']
                );  
                $foto=$uploadedFile['secure_url'];

                
            } catch (\Exception $e) {
                
                return redirect()->route('publicaciones_edit', ['id'=>$id])->with(['css'=>'danger', 'mensaje'=>'Error al subir la imagen: ' . $e->getMessage()]);
            }
            //eliminamos la foto anterior 
            try {
                    // Extraer el public_id de la URL anterior
                    $urlParts = parse_url($fotoAnterior);
                    $pathParts = pathinfo($urlParts['path']);
                    $publicId = $pathParts['dirname'] . '/' . $pathParts['filename'];
                    
                    // Eliminar la versión y transformaciones si existen
                    $publicId = preg_replace('/\/v\d+$/', '', $publicId);
                    //dd($publicId);
                    // Eliminar la foto anterior
                    $cloudinary->uploadApi()->destroy($publicId);
                    
                } catch (\Exception $e) {
                    // No interrumpir el flujo si falla la eliminación, solo loggear 
                    return redirect()->route('publicaciones_edit', ['id'=>$id])->with(['css'=>'danger', 'mensaje'=>'Error al eliminar foto anterior de Cloudinary: ' . $e->getMessage()]);
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
            $publicacion->foto = $foto;
        }
        
        
        $publicacion->save();
        
        return redirect()->route('publicaciones_edit', ['id'=>$id])->with(['css'=>'success', 'mensaje'=>'Se modificó el registro exitosamente']);
        
    }
    public function publicaciones_delete(Request $request, $id)
    {
        $datos = Publicaciones::findOrFail($id);
        //eliminamos la foto anterior
         $cloudinary = new Cloudinary([
                    'cloud' => [
                        'cloud_name' => config('filesystems.disks.cloudinary.cloud_name'),
                        'api_key' => config('filesystems.disks.cloudinary.api_key'),
                        'api_secret' => config('filesystems.disks.cloudinary.api_secret'),
                    ]
                ]);

        try {
                    // Extraer el public_id de la URL anterior
                    $urlParts = parse_url($datos->foto);
                    $pathParts = pathinfo($urlParts['path']);
                    $publicId = $pathParts['dirname'] . '/' . $pathParts['filename'];
                    
                    // Eliminar la versión y transformaciones si existen
                    $publicId = preg_replace('/\/v\d+$/', '', $publicId);
                    //dd($publicId);
                    // Eliminar la foto anterior
                    $cloudinary->uploadApi()->destroy($publicId);
                    
                } catch (\Exception $e) {
                    // No interrumpir el flujo si falla la eliminación, solo loggear 
                    return redirect()->route('publicaciones_index' )->with(['css'=>'danger', 'mensaje'=>'Error al eliminar foto anterior de Cloudinary: ' . $e->getMessage()]);
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
