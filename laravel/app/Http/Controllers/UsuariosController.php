<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\UsersMetadata;
use App\Models\Perfiles;
use Illuminate\Support\Facades\Hash;
class UsuariosController extends Controller
{
    public function __construct()
    {
        if(session('perfil_id')!='1')
        {
            abort(404);
        }
    }
    public function usuarios_index()
    {
        $datos = UsersMetadata::with(['users', 'estados', 'perfiles'])
        ->orderBy('id', 'desc')
        ->paginate(3); 
        return Inertia::render('usuarios/Home', ['datos'=>$datos]);
    }
    public function usuarios_add()
    {
        $perfiles=Perfiles::get();
        return Inertia::render('usuarios/Add', ['perfiles'=>$perfiles]);
    }
    public function usuarios_add_post(Request $request)
    {
        $validated = $request->validate([
                'perfil_id' => 'required|exists:perfiles,id',
                'nombre' => 'required|min:5|max:100',
                'correo' => ['required', 'email', 'max:255', 'unique:users,email'],
                'telefono' => 'required',
                'password' => ['required', 'string', 'min:6', 'confirmed'] // Agregar 'confirmed'
            ], [
                'perfil_id.required' => 'El campo perfil es obligatorio',
                'perfil_id.exists' => 'El perfil seleccionado no es válido',
                'nombre.required' => 'El campo nombre es obligatorio',
                'nombre.min' => 'El nombre debe tener al menos 5 caracteres',
                'nombre.max' => 'El nombre no debe exceder los 100 caracteres',
                'correo.required' => 'El correo electrónico es obligatorio',
                'correo.email' => 'El formato del correo electrónico no es válido',
                'correo.max' => 'El correo electrónico no debe exceder los 255 caracteres',
                'correo.unique' => 'Este correo electrónico ya está registrado',
                'telefono.required' => 'El campo teléfono es obligatorio',
                'password.required' => 'La contraseña es obligatoria',
                'password.min' => 'La contraseña debe tener al menos 6 caracteres',
                'password.confirmed' => 'Las contraseñas no coinciden' 
        ]);
        $save=new User;
        $save->name=$request->nombre;
        $save->email=$request->correo;
        $save->password=Hash::make($request->password);
        $save->created_at=date('Y-m-d H:i:s');
        $save->save();

        $save2=new UsersMetadata;
        $save2->users_id=$save->id;
        $save2->perfiles_id=$request->perfil_id;
        $save2->telefono=$request->telefono;
        $save2->estados_id=1;
        $save2->save();
        return redirect()->route('usuarios_add')->with(['css'=>'success', 'mensaje'=>'Se creó el registro exitosamente']);    
    }
    public function usuarios_edit($id)
    {
        $datos=UsersMetadata::with('users')->with('estados')->with('perfiles')->findOrFail($id);
        $perfiles=Perfiles::get();
        return Inertia::render('usuarios/Edit', ['perfiles'=>$perfiles, 'datos'=>$datos]);
    }
    public function usuarios_edit_post(Request $request, $id)
    {
        $userMetadata = UsersMetadata::findOrFail($id); 
        $rules = [
            'perfil_id' => 'required|exists:perfiles,id',
            'nombre' => 'required|min:5|max:100',
            'correo' => ['required', 'email', 'max:255', 'unique:users,email,' .$userMetadata->users_id], // Correcto
            'telefono' => 'required',
        ];

        $messages = [
            'perfil_id.required' => 'El campo perfil es obligatorio',
            'perfil_id.exists' => 'El perfil seleccionado no es válido',
            'nombre.required' => 'El campo nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 5 caracteres',
            'nombre.max' => 'El nombre no debe exceder los 100 caracteres',
            'correo.required' => 'El correo electrónico es obligatorio',
            'correo.email' => 'El formato del correo electrónico no es válido',
            'correo.max' => 'El correo electrónico no debe exceder los 255 caracteres', 
            'correo.unique' => 'Este correo electrónico ya está registrado en otro usuario',
            'telefono.required' => 'El campo teléfono es obligatorio',
        ];

        // Solo validar password si se proporciona
        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:6', 'confirmed'];
            $rules['password_confirmation'] = ['required', 'string', 'min:6'];
            
            $messages['password.required'] = 'La contraseña es obligatoria';
            $messages['password.min'] = 'La contraseña debe tener al menos 6 caracteres';
            $messages['password.confirmed'] = 'Las contraseñas no coinciden';
            $messages['password_confirmation.required'] = 'La confirmación de contraseña es requerida';
            $messages['password_confirmation.min'] = 'La confirmación de contraseña debe tener al menos 6 caracteres';
        }

        $validated = $request->validate($rules, $messages);


        // Buscar y actualizar metadata - CORRECCIÓN IMPORTANTE: usar $id en lugar de $request->id
       
        
        $userMetadata->perfiles_id = $validated['perfil_id'];
        $userMetadata->telefono = $validated['telefono']; 
        $userMetadata->save();
        // Buscar y actualizar el usuario
        $user = User::findOrFail($userMetadata->users_id);
        $user->name = $validated['nombre'];
        $user->email = $validated['correo'];
        
        // Actualizar password solo si se proporcionó
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }  
        
        $user->save();

            
        return redirect()->route('usuarios_edit', ['id' => $id])
            ->with(['css' => 'success', 'mensaje' => 'Se modificó el registro exitosamente']);
    }
    public function usuarios_eliminar(Request $request, $id)
    {
        $save = UsersMetadata::findOrFail($id); 
         //eliminamos el registro
        try {
            
            $save->delete();
            // Ahora eliminamos el usuario de la tabla users
            $user = User::findOrFail($save->users_id);
            $user->delete();
            return redirect()->route('usuarios_index')->with([
            'css' => 'success', 
            'mensaje' => 'Se eliminó registro exitosamente'
        ]);
        
        } catch (\Exception $e) {
            return redirect()->route('usuarios_index')->with([
                'css' => 'danger', 
                'mensaje' => 'Ocurrió un error inesperado'
            ]);
        }
    }
    
}
