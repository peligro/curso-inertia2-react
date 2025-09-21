import {PageProps} from '@inertiajs/core';

export interface UsuarioInterface{
    id?: number;
    name: string;
    email: string;
    remember_token?: string;
    created_at?: string;
}

export interface PerfilInterface{
    id?: number;
    nombre: string;
}

export interface EstadosInterface{
    id?: number;
    nombre: string;
}

export interface UsersMetadataInterface{
    id?: number;
    telefono: string;
    estados_id: number;
    perfiles_id: number;
    user_id: number;

    users?: UsuarioInterface;
    estados?: EstadosInterface;
    perfiles?: PerfilInterface;
}


export interface PaginationLink{
    url: string | null;
    label: string;
    active: boolean;
}

export interface UsuariosPagination{
    current_page: number;
    data: UsersMetadataInterface[];
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

export interface UsuariosProps extends PageProps{
    datos: UsuariosPagination;
    flash?:{
        success?:string;
        css?:string;
        mensaje?:string;
    }
}