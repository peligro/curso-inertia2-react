<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
//cliente oficial
use OpenAI\Laravel\Facades\OpenAI;

//guardar en bd
use App\Models\Publicaciones;
use Illuminate\Support\Str; 

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
        Necesito que me indiques qué ventajas y desventajas tiene desarrollar aplicaciones bajo el stack Laravel + Inertia + React + Typescript';

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
        return Inertia::render('openai/ClienteOficial3');
    }
    public function openai_cliente_oficial_3_post(Request $request)
    {
        $request->validate([
            'pregunta' => 'required|string|min:5'
        ], [
            'pregunta.required' => 'El campo pregunta es obligatorio',
            'pregunta.min' => 'La pregunta debe tener al menos 5 caracteres',
            'pregunta.string' => 'La pregunta debe ser un texto',
        ]);

        $startTime = microtime(true); 

        try {
            $response = OpenAI::images()->create([
                'model' => 'dall-e-3',
                'prompt' => $request->pregunta,
                'size' => '1024x1024',
                'quality' => 'standard',
                'n' => 1,
            ]);

            $imageUrl = $response->data[0]->url;
            
            if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                throw new Exception('URL de imagen no válida');
            }
            
            // Usar Guzzle/Http con headers de navegador
            $imageContent = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept' => 'image/webp,image/apng,image/*,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.9',
                'Referer' => 'https://platform.openai.com/',
            ])->timeout(30)->get($imageUrl)->body();
            
             
            
            if (empty($imageContent)) {
                throw new Exception('No se pudo descargar la imagen (contenido vacío)');
            }
            
            $fileName = 'publicaciones/dalle_' . uniqid() . '.png';
            Storage::disk('s3')->put($fileName, $imageContent, 'public');
            
            $s3Path = $fileName;
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
            'api_response' => [
                'respuesta' => $s3Path,
                'tiempo' => $tiempo,
                'pregunta_enviada' => $request->pregunta
            ]
        ]);
    }
    public function openai_cliente_oficial_4_crear_publicacion()
    {
        return Inertia::render('openai/ClienteOficial4');
    }
    public function openai_cliente_oficial_4_crear_publicacion_post(Request $request)
    {
         $request->validate([
        'pregunta' => 'required|string|min:5'
    ], [
        'pregunta.required' => 'El campo pregunta es obligatorio',
        'pregunta.min' => 'La pregunta debe tener al menos 5 caracteres',
        'pregunta.string' => 'La pregunta debe ser un texto',
    ]);

    $startTime = microtime(true);
    
    $resultado = [
        'tiempo' => null,
        'pregunta_enviada'=>null,
        'titulo' => null,
        'texto' => null,
        'imagen_url' => null,
        'success' => false,
        'error' => null,
        'tiempo_total' => 0
    ];

    try {
        // 1. PRIMERO: Generar el contenido textual (título + artículo)
        $promptContenido = "Como experto en marketing digital y creación de contenido para LinkedIn, genera:
        
        1. UN TÍTULO atractivo (máximo 10 palabras)
        2. UN TEXTO para publicación en LinkedIn (máximo 300 palabras) sobre: {$request->pregunta}
        
        Formato de respuesta:
        TÍTULO: [aquí el título]
        TEXTO: [aquí el texto completo]";

        $responseContenido = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $promptContenido],
            ],
            'temperature' => 0.7,
            'max_tokens' => 800,
        ]);

        $contenidoCompleto = $responseContenido->choices[0]->message->content;
        
        // Extraer título y texto
        $lineas = explode("\n", $contenidoCompleto);
        $titulo = '';
        $texto = '';
        $enTexto = false;

        foreach ($lineas as $linea) {
            if (str_starts_with($linea, 'TÍTULO:')) {
                $titulo = trim(str_replace('TÍTULO:', '', $linea));
            } elseif (str_starts_with($linea, 'TEXTO:')) {
                $texto = trim(str_replace('TEXTO:', '', $linea));
                $enTexto = true;
            } elseif ($enTexto) {
                $texto .= "\n" . trim($linea);
            }
        }

        // 2. SEGUNDO: Generar imagen con DALL-E 3 basada en el título
        $promptImagen = "Crea una imagen profesional para LinkedIn sobre: {$titulo}. 
        Estilo profesional, corporativo, adecuado para redes sociales profesionales";

        $responseImagen = OpenAI::images()->create([
            'model' => 'dall-e-3',
            'prompt' => $promptImagen,
            'size' => '1024x1024',
            'quality' => 'standard',
            'n' => 1,
        ]);

        $imageUrl = $responseImagen->data[0]->url;
        
        // Descargar y guardar imagen en S3
        $imageContent = file_get_contents($imageUrl);
        if ($imageContent === false) {
            throw new Exception('No se pudo descargar la imagen de DALL-E');
        }
        
        $fileName = 'publicaciones/linkedin_' . uniqid() . '.png';
        Storage::disk('s3')->put($fileName, $imageContent, 'public');
        $s3Path = Storage::disk('s3')->url($fileName);

        // Preparar resultado final
        $resultado = [
            'pregunta_enviada'=>$request->pregunta,
            'titulo' => $titulo,
            'texto' => $texto,
            'imagen_url' => $fileName,
            'success' => true,
            'error' => null
        ];

    } catch (\Exception $e) {
        $resultado['error'] = 'Error: ' . $e->getMessage();
        $resultado['success'] = false;
    }

    $endTime = microtime(true);
    $resultado['tiempo'] = round(($endTime - $startTime) * 1000, 2);
    //dd($resultado);
    return Inertia::render('openai/ClienteOficial4', [
        'api_response' => $resultado,
        'pregunta_enviada' => $request->pregunta
    ]);
    }
    public function openai_cliente_oficial_4_crear_publicacion_save_post(Request $request)
    {
        //dd($request);
        $publicacion = new Publicaciones();
        $publicacion->categorias_id = 1;
        $publicacion->nombre = $request->titulo;
        $publicacion->slug = Str::slug($request->titulo);
        // Convertir saltos de línea a <br/>
        $publicacion->descripcion = nl2br($request->texto);
        $publicacion->foto = $request->imagen_url;
        $publicacion->save();

        return redirect()->route('openai_cliente_oficial_4_crear_publicacion')->with([
                    'css'=>'success','success' => 'Se creó el registro exitosamente'
                ]);
    }
}
