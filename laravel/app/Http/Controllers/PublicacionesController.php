<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Categorias;
use App\Models\Publicaciones;
use Illuminate\Support\Str;
class PublicacionesController extends Controller
{
    public function publicaciones_index()
    {
        $datos=Publicaciones::orderBy('id', 'desc')->paginate(2);
        return Inertia::render('publicaciones/Home', ['datos'=>$datos]);
    }
}
