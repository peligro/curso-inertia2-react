import { PageProps } from '@inertiajs/core';

export interface PerfilesInterface {
    id?: number;
    nombre: string;
    slug: string; 
}

export interface PerfilesProps extends PageProps {
    datos: PerfilesInterface[];
}