<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
class DeepSeekController extends Controller
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.deepseek.key');
        $this->baseUrl = config('services.deepseek.url');
    }


    public function deepseek_index()
    {
        return Inertia::render('deepseek/Home');
    }
    public function deepseek_api()
    {
        $startTime=microtime(true);//inicio del timer

        $pregunta='Eres un experto en desarrollo de aplicaciones web.
                    Necesito que me indiques qué ventajas y desventajas tiene desarrollar aplicaciones bajo el stack Laravel + Inertia + React + Typescript';
        
        $respuesta = Http::withHeaders(
            [
                'Authorization'=>'Bearer '.$this->apiKey, 
                'Content-Type'=>'application/json'
            ]
        )->post($this->baseUrl.'chat/completions', 
        [
            'model'=>'deepseek-chat',
            'messages'=>[
                [
                    'role'=>'user',
                    'content'=>$pregunta
                ]
                ],
                'temperature'=>0.7,
                'max_tokens'=>500
        ]);
        $respuestaData = $respuesta->json();
        $respuestaIA = $respuestaData['choices'][0]['message']['content'] ?? 'No se recibió respuesta';
        $endTime=microtime(true);//fin del timer
        $tiempo = round( ($endTime - $startTime) * 1000, 2);
        
        return Inertia::render('deepseek/API', ['pregunta'=>$pregunta, 'respuesta'=>$respuestaIA, 'tiempo'=>$tiempo]); 
    }
    public function deepseek_chatbot()
    {
        return Inertia::render('deepseek/Chatbot');
    }
    public function deepseek_chatbot_post(Request $request)
    {
        $validated= $request->validate(
            [
                'pregunta'=>'required|string|min:5',
            ],[
                'pregunta.required'=>'El campo pregunta está vacío',
                'pregunta.string'=>'La pregunta debe ser un texto',
                'pregunta.min'=>'El campo pregunta debe tener al menos 5 caracteres',
            ]
        );
        $startTime=microtime(true);//inicio del timer

        $respuesta = Http::withHeaders(
                                [
                                    'Authorization'=>'Bearer '.$this->apiKey, 'Content-Type'=>'application/json'
                                ])
                                ->post($this->baseUrl.'chat/completions', 
                                    [ 
                                        'model'=>'deepseek-chat', 
                                        'messages'=>[
                                                        [ 'role'=>'user', 'content'=>$request->pregunta ]
                                        ],
                                        'temperature'=>0.7,
                                        'max_tokens'=>500
                                    ]);
        $respuestaIA = $respuesta->json()['choices'][0]['message']['content'] ?? 'No se recibió respuesta';
        $endTime=microtime(true);//fin del timer
        $tiempo = round( ($endTime - $startTime) * 1000, 2);
        
        return Inertia::render('deepseek/Chatbot', 
        [
            'api_response'=>[
                'pregunta_enviada'=>$request->pregunta, 
                'respuesta'=>$respuestaIA, 
                'tiempo'=>$tiempo
            ]
        ]); 
    }
    public function deepseek_consulta_simple()
    {
        $startTime=microtime(true);//inicio del timer

        $pregunta='Eres un experto en bases de datos PostgreSQL. Tu tarea es convertir este texto a una consulta SQL válida:
            Texto: "Muestra los usuarios que se registraron en marzo 2026"
            Formato de salida:
            - nunca uses * en las consultas SQL
            - siempre los datos ordénalos por el id de forma descendente
            - solo la consulta SQL, sin explicaciones ni comentarios
                    ';
        $schema= <<<EOL
                Table: users
                Columnas:
                 - id (int)
                 - name (string)
                 - email (string)
                 - created_at (datetime)
                EOL;
        $respuesta = Http::withHeaders(
                                [
                                    'Authorization'=>'Bearer '.$this->apiKey, 
                                    'Content-Type'=>'application/json'
                                ])
                                ->post($this->baseUrl.'chat/completions', 
                                    [ 
                                        'model'=>'deepseek-chat', 
                                        'messages'=>[
                                                        [ 
                                                            'role'=>'user', 
                                                            'content'=>"Tienes que convertir este texto en una consulta SQL válida.\n\nEsquema de la tabla:\n$schema\n\nTexto: \"$pregunta\""]
                                        ],
                                        'temperature'=>0.2,
                                        'max_tokens'=>500
                                    ]);
        $respuestaIA = $respuesta->json()['choices'][0]['message']['content'] ?? 'No se recibió respuesta';
        $endTime=microtime(true);//fin del timer
        $tiempo = round( ($endTime - $startTime) * 1000, 2);
        
        return Inertia::render('deepseek/ConsultaSimple', ['pregunta'=>$pregunta, 'respuesta'=>$respuestaIA, 'tiempo'=>$tiempo]); 
    }
}