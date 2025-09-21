<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\UsersMetadata;
use App\Models\Perfiles;
use App\Models\Estados;
use Illuminate\Support\Facades\Hash;
class UsuariosController extends Controller
{
    public function __construct()
    {
        if(session("perfil_id")!=1)
        {
            abort(404);
        }
    }
    public function usuarios_index()
    {
        $datos = UsersMetadata::with(['users', 'estados', 'perfiles'])->orderBy('id', 'desc')->paginate(2);
        return Inertia::render('usuarios/Home', ['datos'=>$datos]);
    }
    public function usuarios_add()
    {
        $perfiles=Perfiles::get();
        return Inertia::render('usuarios/Add', ['perfiles'=>$perfiles]);
    }
    public function usuarios_add_post(Request $request)
    {
        $validated= $request->validate(
            [
                'perfil_id'=>'required|exists:perfiles,id',
                'nombre'=>'required|min:5',
                'correo'=>['required', 'email', 'max:255', 'unique:users,email'],
                'telefono'=>'required',
                'password'=>['required', 'string', 'min:6', 'confirmed']
            ],[
                'perfil_id.required'=>'El campo perfil está vacío',
                'perfil_id.exists'=>'El perfil seleccionado no es válido',
                'nombre.required'=>'El campo nombre está vacío',
                'nombre.min'=>'El campo Nombre debe tener al menos 5 caracteres',
                'correo.required'=>'El campo correo está vacío',
                'correo.email'=>'El formato del correo electrónico no es válido',
                'correo.max'=>'El correo electrónico no debe exeder los 255 caracteres',
                'correo.unique'=>'El correo electrónico no es válido',
                'telefono.required'=>'El campo teléfono está vacío',
                'password.required'=>'El campo contraseña está vacío',
                'password.min'=>'La contraseña debe tener al menos 6 caracteres',
                'password.confirmed'=>'Las contraseñas no coinciden'
            ]
        );
        //user 
        $save = new User();
        $save->name = $request->nombre;
        $save->email = $request->correo;
        $save->password = Hash::make($request->password);
        $save->created_at = date('Y-m-d H:i:s');
        $save->save();

        //users_metadata
        $save2 = new UsersMetadata();
        $save2->telefono = $request->telefono;
        $save2->users_id = $save->id;
        $save2->perfiles_id = $request->perfil_id;
        $save2->estados_id = 1;
        $save2->save();
        //select setval('users_id_seq', (select max(id) from users  ));
        return redirect()->route('usuarios_add')->with(['css'=>'success', 'mensaje'=>'Se creó el registro exitosamente']);
    }
    public function usuarios_edit(Request $request, $id)
    {
        $datos=UsersMetadata::with('users')->findOrFail($id);
        $perfiles=Perfiles::get();
        return Inertia::render('usuarios/Edit', ['perfiles'=>$perfiles, 'datos'=>$datos]);
    }
    public function usuarios_edit_post(Request $request, $id)
    {
        $usersMetadata=UsersMetadata::findOrFail($id);
        $rules = [
            'perfil_id' => 'required|exists:perfiles,id',
            'nombre' => 'required|min:5|max:100',
            'correo' => ['required', 'email', 'max:255', 'unique:users,email,' .$usersMetadata->users_id], 
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

        //users_metadata
        $usersMetadata->perfiles_id = $validated['perfil_id'];
        $usersMetadata->telefono = $validated['telefono'];
        $usersMetadata->save();
        //users
        $user = User::findOrFail($usersMetadata->users_id);
        $user->name = $validated['nombre'];
        $user->email = $validated['correo'];
        if($request->filled('password'))
        {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();
        return redirect()->route('usuarios_edit', ['id'=>$usersMetadata->id])->with(['css'=>'success', 'mensaje'=>'Se modificó el registro exitosamente']);
    }
    public function usuarios_delete(Request $request, $id)
    {
        $save = UsersMetadata::findOrFail($id);
        try {
            $save->delete();
            return redirect()->route('usuarios_index')->with(['css'=>'success', 'mensaje'=>'Se eliminó el registro exitosamente']);
        } catch (\Exception $e) {
            return redirect()->route('usuarios_index')->with(['css'=>'danger', 'mensaje'=>'Ocurrió un error inesperado: '.$e->getMessage()]);
        }
    }
}
