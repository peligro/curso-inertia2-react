<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Categorias;
use Illuminate\Support\Str;
class CategoriasController extends Controller
{
    public function categorias_index()
    {
        $datos=Categorias::orderBy('id', 'desc')->get();
        return Inertia::render('categorias/Home', ['datos'=>$datos]);
    }
}
