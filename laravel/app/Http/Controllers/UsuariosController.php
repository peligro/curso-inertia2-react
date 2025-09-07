<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\UsersMetadata;
class UsuariosController extends Controller
{
    public function usuarios_index()
    {
        $datos = UsersMetadata::with(['users', 'estados', 'perfiles'])
        ->orderBy('id', 'desc')
        ->paginate(3); 
        return Inertia::render('usuarios/Home', ['datos'=>$datos]);
    }
}
