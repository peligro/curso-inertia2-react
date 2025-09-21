<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

//cliente oficial
use OpenAI\Laravel\Facades\OpenAI;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
        $startTime=microtime(true);//inicio del timer

        $pregunta='Eres un experto en desarrollo de aplicaciones web.
                    Necesito que me indiques qué ventajas y desventajas tiene desarrollar aplicaciones bajo el stack Laravel + Inertia + React + Typescript';
        $respuesta = Http::withHeaders(
                                [
                                    'Authorization'=>'Bearer '.config('services.openai.key'), 'Content-Type'=>'application/json'
                                ])
                                ->post(config('services.openai.url').'chat/completions', 
                                    [ 
                                        'model'=>'gpt-3.5-turbo', 
                                        'messages'=>[
                                                        [ 'role'=>'user', 'content'=>$pregunta ]
                                        ],
                                        'temperature'=>0.7,
                                        'max_tokens'=>500
                                    ]);
        $respuestaIA = $respuesta->json()['choices'][0]['message']['content'] ?? 'No se recibió respuesta';
        $endTime=microtime(true);//fin del timer
        $tiempo = round( ($endTime - $startTime) * 1000, 2);
        
        return Inertia::render('openai/API', ['pregunta'=>$pregunta, 'respuesta'=>$respuestaIA, 'tiempo'=>$tiempo]); 
    }
    public function openai_chatbot_api()
    {
        return Inertia::render('openai/ChatbotAPI');
    }
    public function openai_chatbot_api_post(Request $request)
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
                                    'Authorization'=>'Bearer '.config('services.openai.key'), 'Content-Type'=>'application/json'
                                ])
                                ->post(config('services.openai.url').'chat/completions', 
                                    [ 
                                        'model'=>'gpt-3.5-turbo', 
                                        'messages'=>[
                                                        [ 'role'=>'user', 'content'=>$request->pregunta ]
                                        ],
                                        'temperature'=>0.7,
                                        'max_tokens'=>500
                                    ]);
        $respuestaIA = $respuesta->json()['choices'][0]['message']['content'] ?? 'No se recibió respuesta';
        $endTime=microtime(true);//fin del timer
        $tiempo = round( ($endTime - $startTime) * 1000, 2);
        
        return Inertia::render('openai/ChatbotAPI', 
        [
            'api_response'=>[
                'pregunta_enviada'=>$request->pregunta, 
                'respuesta'=>$respuestaIA, 
                'tiempo'=>$tiempo
            ]
        ]); 
    }
    public function openai_consulta_simple()
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
                                    'Authorization'=>'Bearer '.config('services.openai.key'), 'Content-Type'=>'application/json'
                                ])
                                ->post(config('services.openai.url').'chat/completions', 
                                    [ 
                                        'model'=>'gpt-3.5-turbo', 
                                        'messages'=>[
                                                        [ 
                                                            'role'=>'user', 
                                                            'content'=>"Tienes que convertir este texto en una consulta SQL válida.\n\nEsquema de la tabla:\n$schema\n\nTexto: \"$pregunta\""]
                                        ],
                                        'temperature'=>0.2
                                    ]);
        $respuestaIA = $respuesta->json()['choices'][0]['message']['content'] ?? 'No se recibió respuesta';
        $endTime=microtime(true);//fin del timer
        $tiempo = round( ($endTime - $startTime) * 1000, 2);
        
        return Inertia::render('openai/ConsultaSimple', ['pregunta'=>$pregunta, 'respuesta'=>$respuestaIA, 'tiempo'=>$tiempo]); 
    }
    public function openai_cliente_oficial_1()
    {
        $startTime=microtime(true);
        $pregunta='Eres un experto en desarrollo de aplicaciones web.
                    Necesito que me indiques qué ventajas y desventajas tiene desarrollar aplicaciones bajo el stack Laravel + Inertia + React + Typescript';
        try {
            $response = OpenAI::chat()->create(
                [
                    'model'=>'gpt-3.5-turbo',
                    'messages'=>[
                        [
                            'role'=>'user',
                            'content'=>$pregunta
                        ]
                        ],
                    'temperature'=>0.7,
                    'max_tokens'=>500
                ],
               
                
            );
            $respuestaIA = $response->choices[0]->message->content;
            $success = true;
            $error = null;
        } catch (\Exception $e) {
            $respuestaIA = "No se recibió respuesta";
            $success = false;
            $error = $e->getMessage();
        }
        $endTime = microtime(true);
        $tiempo = round( ($endTime - $startTime) * 1000, 2);
        
        return Inertia::render('openai/ClienteOficial1', 
            [
                'pregunta'=>$pregunta, 
                'respuesta'=>$respuestaIA, 
                'tiempo'=>$tiempo,
                'success'=>$success,
                'error'=>$error
            ]
        ); 
    }
    public function openai_cliente_oficial_2()
    {
        return Inertia::render('openai/ClienteOficial2');
    }
    public function openai_cliente_oficial_2_post(Request $request)
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
        $startTime=microtime(true);
        try {
            $response = OpenAI::chat()->create(
                [
                    'model'=>'gpt-3.5-turbo',
                    'messages'=>[
                        [
                            'role'=>'user',
                            'content'=>$request->pregunta
                        ]
                        ],
                    'temperature'=>0.7,
                    'max_tokens'=>500
                ],
               
                
            );
            $respuestaIA = $response->choices[0]->message->content;
            $success = true;
            $error = null;
        } catch (\Exception $e) {
            $respuestaIA = "No se recibió respuesta";
            $success = false;
            $error = $e->getMessage();
        }
        $endTime = microtime(true);
        $tiempo = round( ($endTime - $startTime) * 1000, 2);
        
        
        return Inertia::render('openai/ClienteOficial2', 
        [
            'api_response'=>[
                'pregunta_enviada'=>$request->pregunta, 
                'respuesta'=>$respuestaIA, 
                'tiempo'=>$tiempo,
                'success'=>$success,
                'error'=>$error
            ]
        ]); 
    }
    public function openai_cliente_oficial_3()
    {
        return Inertia::render('openai/ClienteOficial3');
    }
    public function openai_cliente_oficial_3_post(Request $request)
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
        $startTime=microtime(true);
        $s3Path=null;
        $error;
        $imageUrl=null;
        try {
            //generamos imagen desde la IA
            $response = OpenAI::images()->create(
                [
                    'model'=>'dall-e-3',
                    'prompt'=>$request->pregunta,
                    'size'=>'1024x1024',
                    'quality'=>'standard',
                    'n'=>1
                ]);
            $imageUrl = $response->data[0]->url;
            //descargamos la imagen
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $imageUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ]);
            $imageContent = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if($imageContent ===false || $httpCode!==200)
            {
                throw new Exception("Error al descargar imagen (HTTP {$httpCode})");
            }

            //crear archivo temporal
            $tempFilePath = tempnam(sys_get_temp_dir(), 'dalle_');
            file_put_contents($tempFilePath, $imageContent);
            //crear UploadedFile
            $uploadedFile = new UploadedFile(
                $tempFilePath,
                'dalle_image.png',
                'image/png',
                null,
                true
            );

            //subir a S3 (igual que tu publicaciones)
            $s3Path = $uploadedFile->store('publicaciones', 's3');

            //limpiar
            unlink($tempFilePath);
            

        } catch (\Exception $e) {
            $error = 'Error: '.$e->getMessage();
            
            if(isset($tempFilePath) && file_exists($tempFilePath)){
                unlink($tempFilePath);
            }
        }

        $endTime = microtime(true);
        $tiempo = round( ($endTime - $startTime) * 1000, 2);
        
        
        return Inertia::render('openai/ClienteOficial3', 
        [
            'api_response'=>[
                'pregunta_enviada'=>$request->pregunta, 
                'respuesta'=>$s3Path, 
                'tiempo'=>$tiempo,
                'url'=>$imageUrl
            ]
        ]); 
    }
    public function openai_cliente_oficial_4()
    {
        return Inertia::render('openai/ClienteOficial4');
    }
    public function openai_cliente_oficial_4_post(Request $request)
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
        $startTime=microtime(true);

        $resultado =[
            'tiempo'=>null,
            'pregunta_enviada'=>null,
            'titulo'=>null,
            'texto'=>null,
            'imagen_url'=>null,
            'success'=>false,
            'error'=>null,
            'tiempo_total'=>0
        ];
        try {
           //Generar el contenido textual (título + artículo)
           $promptContenido="Como experto en marketing digital y creación de contenido para Linkedin, genera:
            
           1. UN TÍTULO atractivo (máximo 10 palabras)
           2. UN TEXTO para publicación en Linkedin (máximo 300 palabras) sobre {$request->pregunta}

           Formato de respuesta:
           TÍTULO: [aquí va el título]
           TEXTO: [aquí el texto completo]
           ";
           $responseContenido = OpenAI::chat()->create([
                                        'model'=>'gpt-3.5-turbo', 
                                        'messages'=>[
                                                        [ 'role'=>'user', 'content'=>$promptContenido ]
                                        ],
                                        'temperature'=>0.7,
                                        'max_tokens'=>800
           ]);
           $contenidoCompleto = $responseContenido->choices[0]->message->content;
           //extraer título y texto
           $lineas = explode("\n", $contenidoCompleto);
           $titulo='';
           $texto='';
           $enTexto=false;

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
            
            //genera imagen con DALL-E-3 basada en el título
            $promptImagen="Crea una imagen profesional para LinkedIn sobre: {$titulo}.
            Estilo profesional, corporativo, adecuado para redes sociales profesionales";
            $response = OpenAI::images()->create(
                [
                    'model'=>'dall-e-3',
                    'prompt'=>$promptImagen,
                    'size'=>'1024x1024',
                    'quality'=>'standard',
                    'n'=>1
                ]);
            $imageUrl = $response->data[0]->url;
            //descargamos la imagen
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $imageUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ]);
            $imageContent = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if($imageContent ===false || $httpCode!==200)
            {
                throw new Exception("Error al descargar imagen (HTTP {$httpCode})");
            }

            //crear archivo temporal
            $tempFilePath = tempnam(sys_get_temp_dir(), 'dalle_');
            file_put_contents($tempFilePath, $imageContent);
            //crear UploadedFile
            $uploadedFile = new UploadedFile(
                $tempFilePath,
                'dalle_image.png',
                'image/png',
                null,
                true
            );

            //subir a S3 (igual que tu publicaciones)
            $s3Path = $uploadedFile->store('publicaciones', 's3');

            //limpiar
            unlink($tempFilePath);
            $resultado =[
            'pregunta_enviada'=>$request->pregunta,
            'titulo'=>$titulo,
            'texto'=>$texto,
            'imagen_url'=>$s3Path,
            'success'=>true,
            'error'=>null,
        ];
        } catch (\Exception $e) {
           $resultado['error']='Error:'.$e->getMessage();
           $resultado['success']=false;
        }
        $endTime=microtime(true);
        $resultado['tiempo']=round( ($endTime - $startTime) * 1000, 2);
        $resultado["url"] = $imageUrl;
        return Inertia::render('openai/ClienteOficial4', [
            'api_response'=>$resultado,
            'pregunta_enviado'=>$request->pregunta
        ]);
    }
    public function openai_cliente_oficial_4_post_save(Request $request)
    {
        $save=new Publicaciones;
        $save->categorias_id=1;
        $save->nombre= $request->titulo;
        $save->slug = Str::slug($request->titulo, '-');
        $save->descripcion = nl2br($request->texto);
        $save->foto = $request->imagen_url;
        $save->save();

        return redirect()->route('openai_cliente_oficial_4')->with(['css'=>'success', 'mensaje'=>'Se creó el registro exitosamente']);
    }
}
