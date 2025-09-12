import { PageProps } from "@inertiajs/core";


export interface OpenIAInterfaceSimple {
  pregunta: string;
  respuesta:string;
  tiempo: number;
}

export interface InertiaPageProps extends PageProps {
  props: OpenIAInterfaceSimple;
}

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
        url?:string;
    };
    api_response?: ApiResponse;
    // Permitir otras propiedades dinámicas
    [key: string]: any;
}