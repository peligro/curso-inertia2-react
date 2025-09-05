<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
class LayoutController extends Controller
{
    public function layout_index(Request $request)
    {
        return Inertia::render('layout/Home', []);
    }
    public function layout_ProgressIndicator(Request $request)
    {
        sleep(2);
        return Inertia::render('layout/ProgressIndicator', []);
    }
}
