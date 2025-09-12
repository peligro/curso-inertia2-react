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
    public function gemini_ejemplo_1(Request $request)
    {
        $startTime = microtime(true);

        $pregunta = 'Eres un experto en desarrollo de aplicaciones web. 
        Necesito que me indiques qué ventajas y desventajas tiene desarrollar aplicaciones bajo el stack Laravel + Inertia + React + Typescript';

        // URL corregida - nota el cambio en la estructura
        $url = config('services.gemini.url') . 'models/gemini-2.0-flash:generateContent?key=' . config('services.gemini.key');
        $respuesta = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $pregunta]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 500,
            ]
        ]);

        $endTime = microtime(true);
        $tiempo = round(($endTime - $startTime) * 1000, 2);

        $respuestaIA = 'No se recibió respuesta';
        
        if ($respuesta->successful()) {
            $data = $respuesta->json();
            
            $respuestaIA = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No se recibió respuesta';
        } else {
            // Más información del error
            $errorDetails = $respuesta->json();
            $respuestaIA = 'Error en la API: ' . $respuesta->status() . ' - ' . 
                          ($errorDetails['error']['message'] ?? 'Unknown error');
        }

        return Inertia::render('gemini/Ejemplo1', [
            'pregunta' => $pregunta,
            'respuesta' => $respuestaIA, 
            'tiempo' => $tiempo
        ]);
    }
    public function gemini_ejemplo_2_chatbot_api()
    {
        return Inertia::render('gemini/Chatbot');
    }
    public function gemini_ejemplo_2_chatbot_api_post(Request $request)
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
        $startTime = microtime(true);


        $url = config('services.gemini.url') . 'models/gemini-2.0-flash:generateContent?key=' . config('services.gemini.key');
        $respuesta = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $request->pregunta]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 500,
            ]
        ]);

        $endTime = microtime(true);
        $tiempo = round(($endTime - $startTime) * 1000, 2);

        $respuestaIA = 'No se recibió respuesta';
        
        if ($respuesta->successful()) {
            $data = $respuesta->json();
            
            $respuestaIA = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No se recibió respuesta';
        } else {
            // Más información del error
            $errorDetails = $respuesta->json();
            $respuestaIA = 'Error en la API: ' . $respuesta->status() . ' - ' . 
                          ($errorDetails['error']['message'] ?? 'Unknown error');
        }

        return Inertia::render('gemini/Chatbot', [
            'api_response' => [
                'respuesta' => $respuestaIA,
                'tiempo' => $tiempo,
                'pregunta_enviada' => $request->pregunta
            ]
        ]);
    }
    public function gemini_ejemplo_3_consulta_simple()
    {
        $startTime = microtime(true);

        $schema = <<<EOL
            Tabla: users
            Columnas:
            - id (int)
            - name (string)
            - email (string)
            - created_at (datetime)
            EOL;

        $textoConsulta = "Muestra los usuarios que se registraron en marzo de 2024";
        
        // Prompt completo para Gemini (esto es lo que realmente se envía)
        $promptCompleto = "Eres un experto en bases de datos MySQL. Convierte el siguiente texto en una consulta SQL válida.\n\n";
        $promptCompleto .= "Esquema de la tabla:\n$schema\n\n";
        $promptCompleto .= "Texto: \"$textoConsulta\"\n\n";
        $promptCompleto .= "Reglas:\n";
        $promptCompleto .= "- Nunca uses * en las consultas SQL\n";
        $promptCompleto .= "- Siempre ordena los datos por el id de forma descendente\n";
        $promptCompleto .= "- Devuelve solo la consulta SQL, sin explicaciones ni comentarios";

        // Para mostrar en la vista (manteniendo tu formato original)
        $preguntaParaVista = 'Eres un experto en bases de datos MySQL. Tu tarea es convertir este texto en una consulta SQL válida:
        Texto: "' . $textoConsulta . '"
        Formato de salida: 
            - nunca uses * en las consultas SQL
            - siempre los datos ordénalos por el id de forma descendente
            - solo la consulta SQL, sin explicaciones ni comentarios';

        $respuesta = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(config('services.gemini.url') . 'models/gemini-2.0-flash:generateContent?key=' . config('services.gemini.key'), [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $promptCompleto]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.2,
                'maxOutputTokens' => 200,
                'topP' => 0.1,
                'topK' => 1
            ]
        ]);

        $endTime = microtime(true);
        $tiempo = round(($endTime - $startTime) * 1000, 2);

        $respuestaIA = 'No se recibió respuesta';
        
        if ($respuesta->successful()) {
            $data = $respuesta->json();
            $respuestaIA = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No se recibió respuesta';
            $respuestaIA = $this->cleanSqlResponse($respuestaIA);
        } else {
            $respuestaIA = 'Error en la API: ' . $respuesta->status();
        }

        return Inertia::render('gemini/ConsultaSimple', [
            'pregunta' => $preguntaParaVista,
            'respuesta' => $respuestaIA, 
            'tiempo' => $tiempo
        ]); 
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
    public function gemini_ejemplo_4_google_gemini_php()
    {
        $startTime = microtime(true);

        $pregunta = 'Eres un experto en desarrollo de aplicaciones web. 
        Necesito que me indiques qué ventajas y desventajas tiene desarrollar aplicaciones bajo el stack Laravel + Inertia + React + Typescript';

        $respuestaIA = 'No se recibió respuesta';
        $error = null;
        $success = false;

        try {
            // Crear el objeto GenerationConfig correctamente
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
            $success = true;

        } catch (\Exception $e) {
            $error = 'Error: ' . $e->getMessage();
            $respuestaIA = 'Error: ' . $e->getMessage();
        }

        $endTime = microtime(true);
        $tiempo = round(($endTime - $startTime) * 1000, 2);

        return Inertia::render('gemini/GoogleGeminiPHP', [
            'pregunta' => $pregunta,
            'respuesta' => $respuestaIA, 
            'tiempo' => $tiempo,
            'error' => $error,
            'success' => $success
        ]);
    }
    public function gemini_ejemplo_5_google_gemini_php()
    {
        return Inertia::render('gemini/Chatbot2');
    }
    public function gemini_ejemplo_5_google_gemini_php_post(Request $request)
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
        $startTime = microtime(true);

       

        $respuestaIA = 'No se recibió respuesta';
        $error = null;
        $success = false;

        try {
            // Crear el objeto GenerationConfig correctamente
            $generationConfig = new \Gemini\Data\GenerationConfig(
                temperature: 0.7,
                maxOutputTokens: 500, //8192
                topP: 0.8,
                topK: 40
            );

            $result = Gemini::generativeModel(model: 'gemini-2.0-flash')
                ->withGenerationConfig($generationConfig)
                ->generateContent($request->pregunta);

            $respuestaIA = $result->text();
            $success = true;
        } catch (\Exception $e) {
            $error = 'Error: ' . $e->getMessage();
            $respuestaIA = 'Error: ' . $e->getMessage();
        }

        $endTime = microtime(true);
        $tiempo = round(($endTime - $startTime) * 1000, 2);

        return Inertia::render('gemini/Chatbot2', [
            'api_response' => [
                'respuesta' => $respuestaIA,
                'tiempo' => $tiempo,
                'pregunta_enviada' => $request->pregunta
            ]
        ]);
    }
    public function gemini_ejemplo_6_google_gemini_php_imagen()
    {
        return Inertia::render('gemini/Reconocimiento');
    }
    public function gemini_ejemplo_6_google_gemini_php_imagen_post(Request $request)
    {
        $request->validate(
            [
                'pregunta' => 'required|string|min:5',
                'url' => 'required|url|active_url'
            ],
            [
                'pregunta.required' => 'El campo pregunta es obligatorio',
                'pregunta.min' => 'La pregutna debe tener al menos 5 caracteres',
                'pregunta.string' => 'La pregunta debe ser un texto',
                'url.required' => 'El campo URL es obligatorio',
                'url.url' => 'Debe ingresar una URL válida',
                'url.active_url' => 'La URL debe ser un dominio activo y accesible',
            ]
        );
         
        $startTime = microtime(true);

       

        $respuestaIA = 'No se recibió respuesta';
        $error = null;
        $success = false;

        try {
            // Crear el objeto GenerationConfig correctamente
            $result = Gemini::generativeModel(model: 'gemini-2.0-flash')
            ->generateContent([
                $request->pregunta,
                new Blob(
                    mimeType: MimeType::IMAGE_JPEG,
                    data: base64_encode(
                        file_get_contents($request->url)
                    )
                )
            ]);

            $respuestaIA = $result->text();
            $success = true;
        } catch (\Exception $e) {
            $error = 'Error: ' . $e->getMessage();
            $respuestaIA = 'Error: ' . $e->getMessage();
        }

        $endTime = microtime(true);
        $tiempo = round(($endTime - $startTime) * 1000, 2);

        return Inertia::render('gemini/Reconocimiento', [
            'api_response' => [
                'respuesta' => $respuestaIA,
                'tiempo' => $tiempo,
                'pregunta_enviada' => $request->pregunta
            ]
        ]);
        

       
    }
    public function gemini_ejemplo_7_google_gemini_php_generacion_de_imagen()
    {
         $prompt = 'yoda bebiendo coca cola'; // Tu prompt de texto

        // Usa el método `generativeModel` para especificar un modelo
        // que soporte la generación de imágenes.
        $result = Gemini::generativeModel(model: 'gemini-2.5-flash-image-preview')
                        ->generateContent($prompt);

        // La respuesta puede contener varios "partes" (texto, imagen, etc.).
        // Iteramos para encontrar la parte que contiene la imagen.
        foreach ($result->candidates->content->parts as $part) {
            // Verificamos si la parte es un blob de datos en línea (imagen).
            if ($part->inline_data) {
                // Obtenemos el tipo MIME y los datos de la imagen.
                $mimeType = $part->inline_data->mimeType;
                $imageData = base64_decode($part->inline_data->data);

                // Generamos un nombre de archivo único.
                $fileName = 'yoda_bebiendo_coca_cola.'. pathinfo($mimeType, PATHINFO_EXTENSION);

                // Guardamos la imagen en el disco público.
                Storage::disk('public')->put($fileName, $imageData);

                return response()->json();
            }
        }

        return response()->json([
            'message' => 'No se pudo generar la imagen o la respuesta no contenía una imagen.',
        ], 500);
    }
}
