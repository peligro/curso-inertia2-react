<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

//google-gemini-php
use Gemini\Data\Blob;
use Gemini\Enums\MimeType;
use Gemini\Laravel\Facades\Gemini;

class GeminiController extends Controller
{
    public function gemini_index()
    {
        return Inertia::render('gemini/Home');
    }
    public function gemini_ejemplo_1()
    {
        $startTime=microtime(true);//inicio del timer

        $pregunta='Eres un experto en desarrollo de aplicaciones web.
                    Necesito que me indiques qué ventajas y desventajas tiene desarrollar aplicaciones bajo el stack Laravel + Inertia + React + Typescript';
        
        $url= config('services.gemini.url').'models/gemini-2.0-flash:generateContent?key='.config('services.gemini.key');
        
                    $respuesta = Http::withHeaders(
            [
                'Content-Type'=>'application/json'
            ]
        )->post($url, 
        [
            'contents'=>[
                            [
                                'parts'=>[
                                    [
                                        'text'=>$pregunta
                                    ]
                                ]
                            ]
                        ],
                        'generationConfig'=>[
                            'temperature'=>0.7,
                            'maxOutputTokens'=>500
                        ]
           
        ]);
        $respuestaIA = 'No se recibió respuesta';

        if($respuesta->successFul())
        {
            $data = $respuesta->json();
            $respuestaIA = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No se recibió respuesta';
        }else
        {   
            $errorDetails = $respuesta->json();
            $respuestaIA = 'Error en la API:'.$respuesta->status().'-'.($errorDetails['error']['message'] ??'Error desconocido');
        }

        $endTime=microtime(true);//fin del timer
        $tiempo = round( ($endTime - $startTime) * 1000, 2);
        
        return Inertia::render('gemini/API', ['pregunta'=>$pregunta, 'respuesta'=>$respuestaIA, 'tiempo'=>$tiempo]); 
    }
    public function gemini_ejemplo_2()
    {
        return Inertia::render('gemini/Chatbot');
    }
    public function gemini_ejemplo_2_post(Request $request)
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

        
        $url= config('services.gemini.url').'models/gemini-2.0-flash:generateContent?key='.config('services.gemini.key');
        
                    $respuesta = Http::withHeaders(
            [
                'Content-Type'=>'application/json'
            ]
        )->post($url, 
        [
            'contents'=>[
                            [
                                'parts'=>[
                                    [
                                        'text'=>$request->pregunta
                                    ]
                                ]
                            ]
                        ],
                        'generationConfig'=>[
                            'temperature'=>0.7,
                            'maxOutputTokens'=>500
                        ]
           
        ]);
        $respuestaIA = 'No se recibió respuesta';

        if($respuesta->successFul())
        {
            $data = $respuesta->json();
            $respuestaIA = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No se recibió respuesta';
        }else
        {   
            $errorDetails = $respuesta->json();
            $respuestaIA = 'Error en la API:'.$respuesta->status().'-'.($errorDetails['error']['message'] ??'Error desconocido');
        }

        $endTime=microtime(true);//fin del timer
        $tiempo = round( ($endTime - $startTime) * 1000, 2);
        
        return Inertia::render('gemini/Chatbot', 
        [
            'api_response'=>[
                'pregunta_enviada'=>$request->pregunta, 
                'respuesta'=>$respuestaIA, 
                'tiempo'=>$tiempo
            ]
        ]); 
    }
    public function gemini_ejemplo_3()
    {
        $startTime=microtime(true);//inicio del timer

        $schema= <<<EOL
                Table: users
                Columnas:
                 - id (int)
                 - name (string)
                 - email (string)
                 - created_at (datetime)
                EOL;
        $textoConsulta ="Muestra los usuarios que se registraron en marzo 2026";
        $promptCompleto = "Eres un experto en bases de datos PostgreSQL. Convierte el siguiente texto en una consulta SQL válida \n\n";
        $promptCompleto.="Esquema de la tabla: \n$schema\n\n";
        $promptCompleto.="Texto: \"$textoConsulta\"\n\n";
        $promptCompleto.="Reglas: \n";
        $promptCompleto.="- Nunca uses * en las consultas SQL\n";
        $promptCompleto.="-Siempre ordena los datos por el ud de forma descendente\n";
        $promptCompleto.="-Devuelve solo la consulta SQL, sin explicaciones ni comentarios";

        $preguntaParaVista = 'Eres un experto en bases de datos PostgreSQL. Tu tarea es convertir este texto en una consulta SQL válida:
        Texto: "' . $textoConsulta . '"
        Formato de salida: 
            - nunca uses * en las consultas SQL
            - siempre los datos ordénalos por el id de forma descendente
            - solo la consulta SQL, sin explicaciones ni comentarios';
        
        $url= config('services.gemini.url').'models/gemini-2.0-flash:generateContent?key='.config('services.gemini.key');
        
                    $respuesta = Http::withHeaders(
            [
                'Content-Type'=>'application/json'
            ]
        )->post($url, 
        [
            'contents'=>[
                            [
                                'parts'=>[
                                    [
                                        'text'=>$promptCompleto
                                    ]
                                ]
                            ]
                        ],
                        'generationConfig'=>[
                            'temperature'=>0.7,
                            'maxOutputTokens'=>500
                        ]
           
        ]);
        $respuestaIA = 'No se recibió respuesta';

        if($respuesta->successFul())
        {
            $data = $respuesta->json();
            $respuestaIA = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No se recibió respuesta';
            $respuestaIA=$this->cleanSqlResponse($respuestaIA);
        }else
        {   
            $errorDetails = $respuesta->json();
            $respuestaIA = 'Error en la API:'.$respuesta->status().'-'.($errorDetails['error']['message'] ??'Error desconocido');
        }

        $endTime=microtime(true);//fin del timer
        $tiempo = round( ($endTime - $startTime) * 1000, 2);
        
        return Inertia::render('gemini/ConsultaSimple', ['pregunta'=>$preguntaParaVista, 'respuesta'=>$respuestaIA, 'tiempo'=>$tiempo]); 
    }
    private function cleanSqlResponse($sql)
    {
        if (empty($sql)) {
            return 'No se recibió respuesta';
        }

        // 1. Remover backticks y markdown de código SQL
        $sql = preg_replace('/^```sql\s*/i', '', $sql);  // Remover ```sql al inicio
        $sql = preg_replace('/\s*```$/i', '', $sql);     // Remover ``` al final
        $sql = preg_replace('/^`(.*)`$/', '$1', $sql);   // Remover `texto` simple
        
        // 2. Remover posibles comillas alrededor de la consulta
        $sql = preg_replace('/^["\'](.*)["\']$/', '$1', $sql);
        
        // 3. Limpiar espacios en blanco
        $sql = trim($sql);
        
        // 4. Remover líneas de comentarios SQL (opcional)
        $sql = preg_replace('/^--.*$/m', '', $sql);      // Remover comentarios de una línea
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql); // Remover comentarios multilínea
        
        // 5. Asegurar que termina con punto y coma (si no está vacío)
        if (!empty($sql) && !str_ends_with($sql, ';')) {
            $sql .= ';';
        }
        
        // 6. Normalizar espacios múltiples
        $sql = preg_replace('/\s+/', ' ', $sql);
        
        return trim($sql);
    }
    public function gemini_ejemplo_4()
    {
        $startTime=microtime(true);//inicio del timer

        $pregunta='Eres un experto en desarrollo de aplicaciones web.
                    Necesito que me indiques qué ventajas y desventajas tiene desarrollar aplicaciones bajo el stack Laravel + Inertia + React + Typescript';
        
        $respuestaIA ='No se recibió respuesta';
        $error = null;
        $success=false;
        try {
            $generationConfig = new \Gemini\Data\GenerationConfig(
                temperature: 0.7,
                maxOutputTokens: 500,
                topP: 0.8,
                topK: 40
            );
            $result = Gemini::generativeModel(model: 'gemini-2.0-flash')
                             ->withGenerationConfig($generationConfig)
                             ->generateContent($pregunta);
            $respuestaIA = $result->text();
            $succes=true;
        
        } catch (\Exception $e) {
            $error= 'Error:'.$e->getMessage();
            $respuestaIA='Error:'.$e->getMessage();
        }
        $endTime=microtime(true);//fin del timer
        $tiempo = round( ($endTime - $startTime) * 1000, 2);
        
        return Inertia::render('gemini/GoogleGeminiPHP', ['pregunta'=>$pregunta, 'respuesta'=>$respuestaIA, 'tiempo'=>$tiempo]); 
    }
    public function gemini_ejemplo_5()
    {
        return Inertia::render('gemini/Chatbot2');
    }
    public function gemini_ejemplo_5_post(Request $request)
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

       
        
        $respuestaIA ='No se recibió respuesta';
        $error = null;
        $success=false;
        try {
            $generationConfig = new \Gemini\Data\GenerationConfig(
                temperature: 0.7,
                maxOutputTokens: 500,
                topP: 0.8,
                topK: 40
            );
            $result = Gemini::generativeModel(model: 'gemini-2.0-flash')
                             ->withGenerationConfig($generationConfig)
                             ->generateContent($request->pregunta);
            $respuestaIA = $result->text();
            $succes=true;
        
        } catch (\Exception $e) {
            $error= 'Error:'.$e->getMessage();
            $respuestaIA='Error:'.$e->getMessage();
        }
        $endTime=microtime(true);//fin del timer
        $tiempo = round( ($endTime - $startTime) * 1000, 2);
        
        return Inertia::render('gemini/Chatbot2', 
        [
            'api_response'=>[
                'pregunta_enviada'=>$request->pregunta, 
                'respuesta'=>$respuestaIA, 
                'tiempo'=>$tiempo
            ]
        ]); 
    }
    public function gemini_ejemplo_6()
    {
        return Inertia::render('gemini/Reconocimiento');
    }
    public function gemini_ejemplo_6_post(Request $request)
    {
        $validated= $request->validate(
            [
                'pregunta'=>'required|string|min:5',
                'url'=>'required|url|active_url'
            ],[
                'pregunta.required'=>'El campo pregunta está vacío',
                'pregunta.string'=>'La pregunta debe ser un texto',
                'pregunta.min'=>'El campo pregunta debe tener al menos 5 caracteres',
                'url.required'=>'El campo url está vacío',
                'url.url'=>'Debe ingresar una URL válida',
                'url.active_url'=>'La URL debe ser un dominio activo y accesible',
            ]
        );
        $startTime=microtime(true);//inicio del timer

       
        
        $respuestaIA ='No se recibió respuesta';
        $error = null;
        $success=false;
        try {
          
            $result = Gemini::generativeModel(model: 'gemini-2.0-flash')
                             ->generateContent(
                                [
                                    $request->pregunta,
                                    new Blob(
                                        mimeType: MimeType::IMAGE_JPEG,
                                        data:base64_encode(
                                            file_get_contents($request->url)
                                        )
                                    )
                                ]);
            $respuestaIA = $result->text();
            $succes=true;
        
        } catch (\Exception $e) {
            $error= 'Error:'.$e->getMessage();
            $respuestaIA='Error:'.$e->getMessage();
        }
        $endTime=microtime(true);//fin del timer
        $tiempo = round( ($endTime - $startTime) * 1000, 2);
        
        return Inertia::render('gemini/Reconocimiento', 
        [
            'api_response'=>[
                'pregunta_enviada'=>$request->pregunta, 
                'respuesta'=>$respuestaIA, 
                'tiempo'=>$tiempo
            ]
        ]); 
    }
}
