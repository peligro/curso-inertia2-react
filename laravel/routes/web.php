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
use App\Http\Controllers\DeepseekController;
use App\Http\Controllers\GeminiController;
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
    Route::get('/openiai/consulta-simple', [OpenaiController::class, 'openai_consulta_simple'])->name('openai_consulta_simple');
    Route::get('/openiai/cliente-oficial1', [OpenaiController::class, 'openai_cliente_oficial_1'])->name('openai_cliente_oficial_1');
    Route::get('/openiai/cliente-oficial2', [OpenaiController::class, 'openai_cliente_oficial_2'])->name('openai_cliente_oficial_2');
    Route::post('/openiai/cliente-oficial2', [OpenaiController::class, 'openai_cliente_oficial_2_post'])->name('openai_cliente_oficial_2_post');
    Route::get('/openiai/cliente-oficial3', [OpenaiController::class, 'openai_cliente_oficial_3'])->name('openai_cliente_oficial_3');
    Route::post('/openiai/cliente-oficial3', [OpenaiController::class, 'openai_cliente_oficial_3_post'])->name('openai_cliente_oficial_3_post');
    Route::get('/openiai/cliente-oficial4', [OpenaiController::class, 'openai_cliente_oficial_4_crear_publicacion'])->name('openai_cliente_oficial_4_crear_publicacion');
    Route::post('/openiai/cliente-oficial4', [OpenaiController::class, 'openai_cliente_oficial_4_crear_publicacion_post'])->name('openai_cliente_oficial_4_crear_publicacion_post');
    Route::post('/openiai/cliente-oficial4-save', [OpenaiController::class, 'openai_cliente_oficial_4_crear_publicacion_save_post'])->name('openai_cliente_oficial_4_crear_publicacion_save_post');
    

    
    Route::get('/deepseek', [DeepseekController::class, 'deepseek_index'])->name('deepseek_index');
    Route::get('/deepseek/api', [DeepseekController::class, 'deepseek_api'])->name('deepseek_api');
    Route::get('/deepseek/chatbot', [DeepseekController::class, 'deepseek_chatbot_api'])->name('deepseek_chatbot_api');
    Route::post('/deepseek/chatbot', [DeepseekController::class, 'deepseek_chatbot_api_post'])->name('deepseek_chatbot_api_post');
    Route::get('/deepseek/consulta-simple', [DeepseekController::class, 'deepseek_consulta_simple'])->name('deepseek_consulta_simple');

    Route::get('/gemini', [GeminiController::class, 'gemini_index'])->name('gemini_index');
    Route::get('/gemini/ejemplo-1', [GeminiController::class, 'gemini_ejemplo_1'])->name('gemini_ejemplo_1');
    Route::get('/gemini/ejemplo-2-chatbot', [GeminiController::class, 'gemini_ejemplo_2_chatbot_api'])->name('gemini_ejemplo_2_chatbot_api');
    Route::post('/gemini/ejemplo-2-chatbot', [GeminiController::class, 'gemini_ejemplo_2_chatbot_api_post'])->name('gemini_ejemplo_2_chatbot_api_post');
    Route::get('/gemini/ejemplo-3-consulta', [GeminiController::class, 'gemini_ejemplo_3_consulta_simple'])->name('gemini_ejemplo_3_consulta_simple');
    Route::get('/gemini/ejemplo-4-google-gemini-php', [GeminiController::class, 'gemini_ejemplo_4_google_gemini_php'])->name('gemini_ejemplo_4_google_gemini_php');
    Route::get('/gemini/ejemplo-5-google-gemini-php-chatbot', [GeminiController::class, 'gemini_ejemplo_5_google_gemini_php'])->name('gemini_ejemplo_5_google_gemini_php');
    Route::post('/gemini/ejemplo-5-google-gemini-php-chatbot', [GeminiController::class, 'gemini_ejemplo_5_google_gemini_php_post'])->name('gemini_ejemplo_5_google_gemini_php_post');
    Route::get('/gemini/ejemplo-6-google-gemini-php-imagen', [GeminiController::class, 'gemini_ejemplo_6_google_gemini_php_imagen'])->name('gemini_ejemplo_6_google_gemini_php_imagen');
    Route::post('/gemini/ejemplo-6-google-gemini-php-imagen', [GeminiController::class, 'gemini_ejemplo_6_google_gemini_php_imagen_post'])->name('gemini_ejemplo_6_google_gemini_php_imagen_post');
    Route::get('/gemini/ejemplo-7-google-gemini-php-generacion-de-imagen', [GeminiController::class, 'gemini_ejemplo_7_google_gemini_php_generacion_de_imagen'])->name('gemini_ejemplo_7_google_gemini_php_generacion_de_imagen');
    

    
    

    
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