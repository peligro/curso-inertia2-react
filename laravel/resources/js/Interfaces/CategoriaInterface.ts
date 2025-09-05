// resources/js/Interfaces/CategoriaInterface.ts
import { PageProps } from '@inertiajs/core';

export interface CategoriaInterface {
    id?: number;
    nombre: string;
    slug: string; 
}

export interface CategoriaProps extends PageProps {
    datos: CategoriaInterface[];
}