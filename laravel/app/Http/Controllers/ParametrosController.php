<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
class ParametrosController extends Controller
{
    public function parametros_index(Request $request)
    {
        return Inertia::render('parametros/Home', ["id"=>$request->id, "slug"=>$request->slug]);
    }
    /*
    public function parametros_index($id, $slug)
    {
        return Inertia::render('parametros/Home', ["id"=>$id, "slug"=>$slug]);
    }
    */
    public function parametros_querystring(Request $request)
    {
        // Obtener todos los parámetros query string
        $queryParams = $request->query();
        
        return Inertia::render('parametros/Querystring', [
            'query' => $queryParams, // Todos los parámetros
            'id' => $request->query('id'),
            'slug' => $request->query('slug')
        ]);
    }
}
