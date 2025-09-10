<?php

use Illuminate\Support\Facades\Route;


use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

use App\Http\Middleware\Acceso;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\PublicacionesController;
use App\Http\Controllers\S3ProxyController;
use App\Http\Controllers\PerfilesController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CerrarSesionController;

use App\Http\Controllers\OpenaiController;

//use Illuminate\Support\Facades\Storage;
//use Illuminate\Support\Facades\Response as ResponseStorage;

Route::get('/', [HomeController::class, 'home_index'])->name('home_index');

Route::get('/parametros/{id}/{slug}', [App\Http\Controllers\ParametrosController::class, 'parametros_index'])->name('parametros_index');
Route::get('/parametros-querystring', [App\Http\Controllers\ParametrosController::class, 'parametros_querystring'])->name('parametros_querystring');

Route::get('/layout', [LayoutController::class, 'layout_index'])->name('layout_index');
Route::get('/layout/progress-indicator', [LayoutController::class, 'layout_ProgressIndicator'])->name('layout_ProgressIndicator');

Route::get('/formulario', [FormularioController::class, 'formulario_index'])->name('formulario_index');
Route::get('/formulario/post', [FormularioController::class, 'formulario_post'])->name('formulario_post');
Route::post('/formulario/post', [FormularioController::class, 'formulario_post_post'])->name('formulario_post_post');

//Route::middleware(['auth' ])->group(function () {
Route::middleware([Acceso::class ])->group(function () {
    
    Route::get('/categorias', [CategoriasController::class, 'categorias_index'])->name('categorias_index');
    Route::post('/categorias', [CategoriasController::class, 'categorias_post'])->name('categorias_post');
    Route::put('/categorias/{id}', [CategoriasController::class, 'categorias_put'])->name('categorias_put');
    Route::delete('/categorias/{id}', [CategoriasController::class, 'categorias_delete'])->name('categorias_delete');

    Route::get('/publicaciones', [PublicacionesController::class, 'publicaciones_index'])->name('publicaciones_index');
    Route::get('/publicaciones/add', [PublicacionesController::class, 'publicaciones_add'])->name('publicaciones_add');
    Route::post('/publicaciones/add', [PublicacionesController::class, 'publicaciones_add_post'])->name('publicaciones_add_post');
    Route::get('/publicaciones/edit/{id}', [PublicacionesController::class, 'publicaciones_edit'])->name('publicaciones_edit');
    Route::post('/publicaciones/edit/{id}', [PublicacionesController::class, 'publicaciones_edit_post'])->name('publicaciones_edit_post');
    Route::get('/publicaciones/delete/{id}', [PublicacionesController::class, 'publicaciones_delete'])->name('publicaciones_delete');
    Route::get('/perfiles', [PerfilesController::class, 'perfiles_index'])->name('perfiles_index');
    Route::post('/perfiles', [PerfilesController::class, 'perfiles_post'])->name('perfiles_post');
    Route::put('/perfiles/{id}', [PerfilesController::class, 'perfiles_put'])->name('perfiles_put');
    Route::delete('/perfiles/{id}', [PerfilesController::class, 'perfiles_delete'])->name('perfiles_delete');

    Route::get('/usuarios', [UsuariosController::class, 'usuarios_index'])->name('usuarios_index');
    Route::get('/usuarios/add', [UsuariosController::class, 'usuarios_add'])->name('usuarios_add');
    Route::post('/usuarios/add', [UsuariosController::class, 'usuarios_add_post'])->name('usuarios_add_post');
    Route::get('/usuarios/edit/{id}', [UsuariosController::class, 'usuarios_edit'])->name('usuarios_edit');
    Route::post('/usuarios/edit/{id}', [UsuariosController::class, 'usuarios_edit_post'])->name('usuarios_edit_post');
    Route::get('/usuarios/eliminar/{id}', [UsuariosController::class, 'usuarios_eliminar'])->name('usuarios_eliminar');


    Route::get('/openai', [OpenaiController::class, 'openai_index'])->name('openai_index');
    Route::get('/openai/api', [OpenaiController::class, 'openai_api'])->name('openai_api');
    Route::get('/openiai/chatbot-api', [OpenaiController::class, 'openai_chatbot_api'])->name('openai_chatbot_api');
    Route::post('/openiai/chatbot-api', [OpenaiController::class, 'openai_chatbot_api_post'])->name('openai_chatbot_api_post');
});



Route::get('/auth/login', [LoginController::class, 'login_index'])->name('login');
Route::post('/auth/login', [LoginController::class, 'login_post'])->name('login_post');

Route::post('/auth/logout', [CerrarSesionController::class, 'logout'])->name('logout');


#bucket
Route::get('/s3/{bucket}/{path}', [S3ProxyController::class, 'serveFile'])
    ->where('path', '.*')
    ->name('s3.proxy');
/*
Route::get('/s3/{bucket}/{path}', function ($bucket, $path) {
    try {
        // Verificar que el bucket sea el correcto
        if ($bucket !== config('filesystems.disks.s3.bucket')) {
            abort(404);
        }
        
        // Obtener el archivo de S3
        $file = Storage::disk('s3')->get($path);
        $mimeType = Storage::disk('s3')->mimeType($path);
        
        // Devolver el archivo con los headers correctos
        return ResponseStorage::make($file, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline'
        ]);
        
    } catch (\Exception $e) {
        abort(404);
    }
})->where('path', '.*');*/
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