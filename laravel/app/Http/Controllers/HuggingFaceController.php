<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
class HuggingFaceController extends Controller
{
    public function huggingface_index()
    {
        return Inertia::render('huggingface/Home');
    }
    public function huggingface_index_post(Request $request)
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
        $token = config('services.huggingface.key');
        $model = "runwayml/stable-diffusion-v1-5";
 // Modelo gratuito de generación de imágenes
        $prompt = $request->input('prompt', 'un paisaje futurista con montañas y un cielo estrellado');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post('https://api-inference.huggingface.co/models/' . $model, [
            'inputs' => $prompt,
        ]);

        if ($response->successful()) {
            $image = $response->body();
            $filename = 'generated_image_' . time() . '.jpg';
            file_put_contents(public_path($filename), $image);

            return response()->json([
                'message' => 'Imagen generada con éxito',
                'url' => url($filename),
            ]);
        } else {
            return response()->json([
                'error' => 'Error al generar la imagen',
                'details' => $response->body(),
            ], 500);
        }
    }
}
