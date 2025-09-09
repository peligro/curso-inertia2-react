import { PageProps } from '@inertiajs/core';
import { PerfilInterface, UsersMetadaInterface } from './UsuariosInterface';

export interface UsuariosFormularioProps extends PageProps {
    perfiles: PerfilInterface[];
    flash?: {
        success?: string;
        error?: string;
        message?: string;
        css?: string;
        mensaje?: string;
    };
    errors?: {
        perfil_id?: string;
        nombre?: string;
        correo?: string;
        telefono?: string;
        password?: string;
        password_confirmation?: string;
        [key: string]: string | undefined;
    };
    datos?:UsersMetadaInterface;
}