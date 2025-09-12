<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
//cliente oficial
use OpenAI\Laravel\Facades\OpenAI;

class OpenaiController extends Controller
{
    public function openai_index()
    {
        return Inertia::render('openai/Home');
    }
    public function openai_api()
    {
        $startTime = microtime(true); // Inicio del timer

        $pregunta = 'Eres un experto en desarrollo de aplicaciones web. 
        Necesito que me indiques qué ventajas tiene desarrollar aplicaciones bajo el stack Laravel + Inertia + React + Typescript';

        $respuesta = Http::withHeaders([
            'Authorization' => 'Bearer '.config('services.openai.key'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'user', 
                    'content' => $pregunta
                ],
            ],
            'temperature' => 0.7,
            'max_tokens' => 500,
        ]);
        $endTime = microtime(true); // Fin del timer
        $tiempo = round(($endTime - $startTime) * 1000, 2); // Tiempo en milisegundos
        // Obtener la respuesta de la IA
        $respuestaIA = $respuesta->json()['choices'][0]['message']['content'] ?? 'No se recibió respuesta';
        return Inertia::render('openai/API', ['pregunta'=>$pregunta,'respuesta' => $respuestaIA, 'tiempo'=>$tiempo]);
    }
    public function openai_chatbot_api()
    {
        return Inertia::render('openai/ChatbotAPI');
    }
    public function openai_chatbot_api_post(Request $request)
    {
        $request->validate(
            [
                'pregunta' => 'required|string|min:5'
            ],
            [
                'pregunta.required' => 'El campo pregunta es obligatorio',
                'pregunta.min' => 'La pregutna debe tener al menos 5 caracteres',
                'pregunta.string' => 'La pregunta debe ser un texto',
            ]
        );

        $startTime = microtime(true); // Inicio del timer

        

        $respuesta = Http::withHeaders([
            'Authorization' => 'Bearer '.config('services.openai.key'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'user', 
                    'content' => $request->pregunta
                ],
            ],
            'temperature' => 0.7,
            'max_tokens' => 500,
        ]);
        $endTime = microtime(true); // Fin del timer
        $tiempo = round(($endTime - $startTime) * 1000, 2); // Tiempo en milisegundos
        // Obtener la respuesta de la IA
        $respuestaIA = $respuesta->json()['choices'][0]['message']['content'] ?? 'No se recibió respuesta';
        return Inertia::render('openai/ChatbotAPI', [
            'api_response' => [
                'respuesta' => $respuestaIA,
                'tiempo' => $tiempo,
                'pregunta_enviada' => $request->pregunta
            ]
        ]);
    }
    public function openai_consulta_simple()
    {
        $startTime = microtime(true); // Inicio del timer

        $pregunta = 'Eres un experto en bases de datos MySQL. Tu tarea es convertir este texto en una consulta SQL válida:
        Texto: "Muestra los usuarios que se registraron en marzo de 2024"
        Formato de salida: 
            - nunca uses * en las consultas SQL
            - siempre los datos ordénalos por el id de forma descendente
            - solo la consulta SQL, sin explicaciones ni comentarios';
        $schema = <<<EOL
            Tabla: users
            Columnas:
            - id (int)
            - name (string)
            - email (string)
            - created_at (datetime)
            EOL;
        $respuesta =  Http::withHeaders([
                    'Authorization' => 'Bearer '.config('services.openai.key'),
                    'Content-Type' => 'application/json',
                ])->post('https://api.openai.com/v1/chat/completions',  [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'user', 'content' => "Tienes que convertir este texto en una consulta SQL válida.\n\nEsquema de la tabla:\n$schema\n\nTexto: \"$pregunta\""],
                    ],
                    'temperature' => 0.2,
                ]);
        $endTime = microtime(true); // Fin del timer
        $tiempo = round(($endTime - $startTime) * 1000, 2); // Tiempo en milisegundos
        // Obtener la respuesta de la IA
        $respuestaIA = $respuesta->json()['choices'][0]['message']['content'] ?? 'No se recibió respuesta';
        return Inertia::render('openai/ConsultaSimple', ['pregunta'=>$pregunta,'respuesta' => $respuestaIA, 'tiempo'=>$tiempo]); 
    }

    public function openai_cliente_oficial_1()
    {
        $startTime = microtime(true); // Inicio del timer

        $pregunta = 'Eres un experto en desarrollo de aplicaciones web. 
        Necesito que me indiques qué ventajas tiene desarrollar aplicaciones bajo el stack Laravel + Inertia + React + Typescript';

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'user', 
                        'content' => $pregunta
                    ],
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);
            
            $respuestaIA = $response->choices[0]->message->content;
            $success = true;
            $error = null;

        } catch (\Exception $e) {
            $respuestaIA = 'No se recibió respuesta';
            $success = false;
            $error = $e->getMessage();
        } 
        $endTime = microtime(true); // Fin del timer
        $tiempo = round(($endTime - $startTime) * 1000, 2); // Tiempo en milisegundos

 
        return Inertia::render('openai/ClienteOficial1', [
            'pregunta' => $pregunta,
            'respuesta' => $respuestaIA,
            'tiempo' => $tiempo,
            'success' => $success,
            'error' => $error
        ]); 
    }
    public function openai_cliente_oficial_2()
    {
        return Inertia::render('openai/ClienteOficial2');
    }
    public function openai_cliente_oficial_2_post(Request $request)
    {
         $request->validate(
            [
                'pregunta' => 'required|string|min:5'
            ],
            [
                'pregunta.required' => 'El campo pregunta es obligatorio',
                'pregunta.min' => 'La pregutna debe tener al menos 5 caracteres',
                'pregunta.string' => 'La pregunta debe ser un texto',
            ]
        );

        $startTime = microtime(true); // Inicio del timer

        

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'user', 
                        'content' => $request->pregunta
                    ],
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);
            
            $respuestaIA = $response->choices[0]->message->content;
            $success = true;
            $error = null;

        } catch (\Exception $e) {
            $respuestaIA = 'No se recibió respuesta';
            $success = false;
            $error = $e->getMessage();
        } 
        $endTime = microtime(true); // Fin del timer
        $tiempo = round(($endTime - $startTime) * 1000, 2); // Tiempo en milisegundos

 
        return Inertia::render('openai/ClienteOficial2', [
            'api_response' => [
                'respuesta' => $respuestaIA,
                'tiempo' => $tiempo,
                'pregunta_enviada' => $request->pregunta
            ]
        ]);
    }
    public function openai_cliente_oficial_3()
    {
        $startTime = microtime(true);
        $pregunta = 'yoda de star wars bebiendo coca cola';

        try {
            $response = OpenAI::images()->create([
                'model' => 'dall-e-3',
                'prompt' => $pregunta,
                'size' => '1024x1024',
                'quality' => 'standard',
                'n' => 1,
            ]);

            $imageUrl = $response->data[0]->url;
            
            if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                throw new Exception('URL de imagen no válida');
            }
            
            // Descargar la imagen directamente a un resource temporal
            $imageContent = file_get_contents($imageUrl);
            if ($imageContent === false) {
                throw new Exception('No se pudo descargar la imagen');
            }
            
            
            $fileName = 'publicaciones/dalle_' . uniqid() . '.png';
            Storage::disk('s3')->put($fileName, $imageContent, 'public');
            
            // Obtener path 
            $s3Path = $fileName; // 'dalle/dalle_68c332d2b3307.png'
            
            
            $success = true;
            $error = null;

        } catch (\Exception $e) {
           
            $s3Path = null;
            $success = false;
            $error = 'Error: ' . $e->getMessage(); 
        }
        
        $endTime = microtime(true);
        $tiempo = round(($endTime - $startTime) * 1000, 2);

        return Inertia::render('openai/ClienteOficial3', [
            'pregunta' => $pregunta,
            'respuesta' => $s3Path,
            'tiempo' => $tiempo,
            'success' => $success,
            'error' => $error,
        ]); 
    }
}
