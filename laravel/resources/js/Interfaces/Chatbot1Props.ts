import { PageProps } from "@inertiajs/core";

// Interfaces
export interface ApiResponse {
    respuesta: string;
    tiempo: number;
    pregunta_enviada: string;
}

// Extender PageProps para incluir la firma de índice
export interface PageCustomProps extends PageProps {
    errors?: {
        pregunta?: string;
    };
    api_response?: ApiResponse;
    // Permitir otras propiedades dinámicas
    [key: string]: any;
}