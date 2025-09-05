<?php

use Illuminate\Support\Facades\Route;


use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\PublicacionesController;


Route::get('/', [HomeController::class, 'home_index'])->name('home_index');

Route::get('/parametros/{id}/{slug}', [App\Http\Controllers\ParametrosController::class, 'parametros_index'])->name('parametros_index');
Route::get('/parametros-querystring', [App\Http\Controllers\ParametrosController::class, 'parametros_querystring'])->name('parametros_querystring');

Route::get('/layout', [LayoutController::class, 'layout_index'])->name('layout_index');
Route::get('/layout/progress-indicator', [LayoutController::class, 'layout_ProgressIndicator'])->name('layout_ProgressIndicator');

Route::get('/formulario', [FormularioController::class, 'formulario_index'])->name('formulario_index');
Route::get('/formulario/post', [FormularioController::class, 'formulario_post'])->name('formulario_post');
Route::post('/formulario/post', [FormularioController::class, 'formulario_post_post'])->name('formulario_post_post');

Route::get('/categorias', [CategoriasController::class, 'categorias_index'])->name('categorias_index');
Route::post('/categorias', [CategoriasController::class, 'categorias_post'])->name('categorias_post');
Route::put('/categorias/{id}', [CategoriasController::class, 'categorias_put'])->name('categorias_put');
Route::delete('/categorias/{id}', [CategoriasController::class, 'categorias_delete'])->name('categorias_delete');

Route::get('/publicaciones', [PublicacionesController::class, 'publicaciones_index'])->name('publicaciones_index');

#método custom 404
Route::any('{any}', function () {
    return Inertia::render('Errors/Error404', [
        'status' => 404,
        'message' => 'Página no encontrada'
    ])->toResponse(request())->setStatusCode(Response::HTTP_NOT_FOUND);
})->where('any', '.*');
#método custom health
Route::get('/health', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Application is running',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0',
        'environment' => app()->environment(),
        'database' => [
            'connected' => \Illuminate\Support\Facades\DB::connection()->getPdo() ? true : false,
            'driver' => config('database.default')
        ]
    ], 200);
})->name('health.check');