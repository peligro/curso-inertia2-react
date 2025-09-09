import { PageProps } from '@inertiajs/core';

export interface UsuarioInterface {
    id?: number;
    name: string;
    email: string; 
    remember_token?: string;
    created_at?: string;
}

export interface PerfilInterface {
    id?: number;
    nombre: string;
}

export interface EstadosInterface {
    id?: number;
    nombre: string;
}

export interface UsersMetadaInterface {
    id?: number;
    telefono: string;
    estados_id: number;
    perfiles_id: number;
    user_id: number;
    
    // CORREGIR: usar singular como en el modelo Laravel
    users?: UsuarioInterface;
    estados?: EstadosInterface;  // singular, no "estados"
    perfiles?: PerfilInterface;   // singular, no "perfiles"
}

// Interfaces para la paginación
export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface UsuariosPagination {
    current_page: number;
    data: UsersMetadaInterface[];
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
 
export interface UsuariosProps extends PageProps {
    datos: UsuariosPagination;   
}