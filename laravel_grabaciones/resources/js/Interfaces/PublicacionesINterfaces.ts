import {Page, PageProps} from '@inertiajs/core';
import { CategoriaInterface } from './CategoriaInterface';

export interface PublicacionesInterface{
    id?: number;
    nombre: string;
    slug: string;
    descripcion: string;
    categorias_id: number;
    categorias?: CategoriaInterface;
    foto: string;
    fecha: string;
}

export interface PaginationLink{
    url: string | null;
    label: string;
    active: boolean;
}

export interface PublicacionesPagination{
    current_page: number;
    data: PublicacionesInterface[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: PaginationLink[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;

}

export interface PublicacionesProps extends PageProps{
    datos: PublicacionesPagination;
    bucket: string;
    flash?:{
        success?:string;
        css?:string;
        mensaje?:string;
    }
}