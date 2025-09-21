import {PageProps} from '@inertiajs/core';

export interface OpenIAInterfaceSimple{
    pregunta: string;
    respuesta: string;
    tiempo: number;
}

export interface InertiaPageProps extends PageProps{
    props: OpenIAInterfaceSimple;
}

export interface APIResponse{
    pregunta_enviada: string;
    respuesta: string;
    tiempo: number;
    url?: string;
}

export interface PageCustomProps extends PageProps{
    errors?:{
        pregunta?: string;
        url?: string;
    };
    flash?:{
        success?: string;
        css?: string;
        mensaje?: string;
    };
    api_response?: APIResponse;
    bucket?: string;
    [key: string]: any;
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
    bucket?:string;
    // Permitir otras propiedades dinámicas
    [key: string]: any;
}