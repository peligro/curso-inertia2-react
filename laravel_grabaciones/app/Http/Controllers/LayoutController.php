<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
class LayoutController extends Controller
{
    public function layout_index()
    {
        return Inertia::render('layout/Home');
    }
    public function layout_ProgressIndicator()
    {
        sleep(3);
        return Inertia::render('layout/ProgressIndicator');
    }
}
