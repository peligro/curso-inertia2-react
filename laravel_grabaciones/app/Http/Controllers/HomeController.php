<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
class HomeController extends Controller
{
    public function home_index()
    {
        return Inertia::render('home/Home');
        //return view("home/home");
    }
}
