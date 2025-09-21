import {PageProps} from '@inertiajs/core';
import { PerfilInterface, UsersMetadataInterface } from './UsuariosInterfaces';

export interface UsuariosFormularioProps extends PageProps{
    perfiles: PerfilInterface[];
    errors?:{
        perfil_id?: string;
        nombre?:string;
        correo?:string;
        telefono?: string;
        password?: string;
        password_confirmation: string;
        [key: string]: string | undefined;
    };
    flash?:{
        success?:string;
        css?:string;
        mensaje?:string;
        error?: string;
    };
    datos?: UsersMetadataInterface;
}