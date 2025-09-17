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
    url?:string;
}
export interface PublicacionLinkedinResponse {
  pregunta_enviada: string;
  titulo: string;
  texto: string;
  imagen_url: string;
  success: boolean;
  error: string | null;
  tiempo: number;
  url?:string;
}
// Extender PageProps para incluir la firma de índice
export interface PageCustomProps extends PageProps {
    errors?: {
        pregunta?: string;
        url?:string; 
    };
    flash?: {
        success?: string;
        css?: string;
        mensaje?: string;
    };
    api_response?: ApiResponse;
    aws_bucket?:string;
    // Permitir otras propiedades dinámicas
    [key: string]: any;
}
export interface PageCustomLinkedinProps extends PageProps {
    errors?: {
        pregunta?: string;
        url?:string; 
    };
    flash?: {
        success?: string;
        css?: string;
        mensaje?: string;
    };
    api_response?: PublicacionLinkedinResponse;
    // Permitir otras propiedades dinámicas
    [key: string]: any;
}