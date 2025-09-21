<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
class ParametrosController extends Controller
{
    public function parametros_index($id, $slug)
    {
        return Inertia::render('parametros/Home', ['id'=>$id, 'slug'=>$slug]);
    }
    /*
    public function parametros_index(Request $request)
    {
        return Inertia::render('parametros/Home', ['id'=>$request->id, 'slug'=>$request->slug]);
    }
    */
    public function parametros_querystring(Request $request)
    {
        return Inertia::render('parametros/Querystring', ['id'=>$request->query('id'), 'slug'=>$request->query('slug')]);
    }
}
