<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        if(Auth::check()==false)
        {
            return redirect()->route('login');
        }
        Auth::logout();
        $request->session()->forget('users_metadata_id');
        $request->session()->forget('perfil_id');
        $request->session()->forget('perfil');
        $request->session()->forget('estados_id');
        $request->session()->forget('estado');
        return redirect()->route('login');
    }
}
