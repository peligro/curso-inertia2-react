import { PageProps } from '@inertiajs/core';
import { CategoriaInterface } from './CategoriaInterface';
export interface PublicacionesEditProps extends PageProps {
    categorias: CategoriaInterface[];
    flash?: {
        success?: string;
        error?: string;
        message?: string;
    };
    errors?: {
        categoria_id?: string;
        nombre?: string;
        descripcion?: string;
        foto?: string;
        [key: string]: string | undefined;
    };
    datos: {
        id: number;
        nombre: string;
        descripcion: string;
        categorias_id: number;
        foto: string;
        slug: string;
        fecha: string;
    };
    bucket: string; 
}