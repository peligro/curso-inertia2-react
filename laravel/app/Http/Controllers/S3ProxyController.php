<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class S3ProxyController extends Controller
{
    /**
     * Servir archivos de S3 con validaciones y caching
     */
    public function serveFile($bucket, $path)
    {
        try {
            // 1. Validar bucket
            if (!$this->isValidBucket($bucket)) {
                Log::warning("Intento de acceso a bucket no autorizado: {$bucket}");
                abort(404);
            }
            
            // 2. Validar path (opcional, para seguridad)
            if (!$this->isValidPath($path)) {
                Log::warning("Intento de acceso a path no válido: {$path}");
                abort(404);
            }
            
            // 3. Verificar que el archivo existe
            if (!Storage::disk('s3')->exists($path)) {
                abort(404);
            }
            
            // 4. Obtener metadata
            $mimeType = Storage::disk('s3')->mimeType($path);
            $fileSize = Storage::disk('s3')->size($path);
            $lastModified = Storage::disk('s3')->lastModified($path);
            
            // 5. Configurar headers
            $headers = $this->getFileHeaders($mimeType, $fileSize, $lastModified);
            
            // 6. Devolver el archivo
            return Storage::disk('s3')->response($path, null, $headers);
            
        } catch (\Exception $e) {
            Log::error("Error en S3 Proxy: {$e->getMessage()}");
            abort(404);
        }
    }
    
    /**
     * Validar que el bucket sea el correcto
     */
    private function isValidBucket($bucket): bool
    {
        return $bucket === config('filesystems.disks.s3.bucket');
    }
    
    /**
     * Validar el path (opcional, para seguridad)
     */
    private function isValidPath($path): bool
    {
        // Permitir solo paths que comiencen con "publicaciones/"
        return str_starts_with($path, 'publicaciones/');
        
        // O para ser más permisivo:
        // return true;
    }
    
    /**
     * Generar headers para la respuesta
     */
    private function getFileHeaders($mimeType, $fileSize, $lastModified): array
    {
        return [
            'Content-Type' => $mimeType,
            'Content-Length' => $fileSize,
            'Content-Disposition' => 'inline',
            'Last-Modified' => gmdate('D, d M Y H:i:s T', $lastModified),
            'Cache-Control' => 'public, max-age=3600', // 1 hora de cache
            'Expires' => gmdate('D, d M Y H:i:s T', time() + 3600),
        ];
    }
    
    /**
     * Método para descargar archivos en lugar de mostrarlos
     */
    public function downloadFile($bucket, $path)
    {
        try {
            if (!$this->isValidBucket($bucket)) {
                abort(404);
            }
            
            if (!Storage::disk('s3')->exists($path)) {
                abort(404);
            }
            
            // Forzar descarga en lugar de visualización
            return Storage::disk('s3')->download($path);
            
        } catch (\Exception $e) {
            Log::error("Download error: {$e->getMessage()}");
            abort(404);
        }
    }
}