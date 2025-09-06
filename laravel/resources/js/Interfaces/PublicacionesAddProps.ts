import { PageProps } from '@inertiajs/core';
import { CategoriaInterface } from './CategoriaInterface';

export interface PublicacionesAddProps extends PageProps {
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
}