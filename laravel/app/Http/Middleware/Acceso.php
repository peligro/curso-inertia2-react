<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\UsersMetadata;
class Acceso
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check()==false)
        {
            return redirect()->route('login');
        }
        $usuario = UsersMetadata::where(['users_id'=>Auth::id()])->first();
        if($usuario->estados_id==2)
        {
            Auth::logout();
            $request->session()->forget('users_metadata_id');
            $request->session()->forget('perfil_id'); 
            $request->session()->forget('perfil');
            $request->session()->forget('estados_id'); 
            $request->session()->forget('estado');
            return redirect()->route('login');
        }
        $request->session()->put('users_metadata_id', $usuario->id);
        $request->session()->put('perfil_id', $usuario->perfiles_id);
        $request->session()->put('perfil', $usuario->perfiles->nombre);
        $request->session()->put('estados_id', $usuario->estados_id);
        $request->session()->put('estado', $usuario->estados->nombre);
        return $next($request);
    }
}
