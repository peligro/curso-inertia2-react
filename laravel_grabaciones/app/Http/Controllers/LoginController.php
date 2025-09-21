<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\UsersMetadata;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login_index()
    {
        return Inertia::render('login/Home');
    }
    public function login_post(Request $request)
    {
        $validated= $request->validate(
            [
                'correo'=>['required', 'email', 'max:255'],
                'password'=>['required', 'string', 'min:6']
            ],[
                'correo.required'=>'El campo correo está vacío',
                'correo.email'=>'El formato del correo electrónico no es válido',
                'correo.max'=>'El correo electrónico no debe exeder los 255 caracteres',
                'password.required'=>'El campo contraseña está vacío',
                'password.min'=>'La contraseña debe tener al menos 6 caracteres',
            ]
        );
        //validamos si existe el correo del usuario
        $usuario = UsersMetadata::with('users')
                                ->where('estados_id', 1)
                                ->whereHas('users', function($query) use ($request){
                                    $query->where('email', $request->correo);
                                })
                                ->first();
        if(!is_object($usuario))
        {
            return redirect()->route('login')->with(['css'=>'danger', 'mensaje'=>'Ocurrió un error inesperado (El correo ingresado no es válido)']);
        }
        //login
        //$credentials=Auth::attempt(['email' => $email, 'password' => $password]);
        //if($credentials)
        if(Auth::attempt(['email' => $request->input('correo'), 'password' => $request->input('password')]))
        {   
            $request->session()->put('users_metadata_id', $usuario->id );
            $request->session()->put('perfil_id', $usuario->perfiles_id );
            $request->session()->put('perfil', $usuario->perfiles->nombre );
            $request->session()->put('estados_id', $usuario->estados_id );
            $request->session()->put('estado', $usuario->estados->nombre );
            return redirect()->intended('/');
        }else
        {
            return redirect()->route('login')->with(['css'=>'warning', 'mensaje'=>'Las credenciales ingresadas no son válidas']);
        }
    }
}
